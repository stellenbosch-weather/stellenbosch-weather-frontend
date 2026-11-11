const apiBaseUrl = 'http://weather.sun.ac.za/api/';
let historyChart = null;
let originalData = [];

function changeDate(days) {
    const dateInput = document.getElementById('historyDate');
    const currentDate = moment(dateInput.value);
    const newDate = currentDate.add(days, 'days');
    
    // Respect the limits
    const minDate = moment('2010-05-24');
    const today = moment();
    
    if (newDate.isBefore(minDate, 'day') || newDate.isAfter(today, 'day')) return;

    dateInput.value = newDate.format('YYYY-MM-DD');
    fetchHistory();
}

async function fetchHistory() {
    try {
        const dateInput = document.getElementById('historyDate');
        let selectedDate = dateInput.value ? moment(dateInput.value) : moment();

        const minDate = moment('2010-05-24');

        // Don't allow fetching for future dates
        if (selectedDate.isAfter(moment(), 'day')) {
            selectedDate = moment();
            dateInput.value = selectedDate.format('YYYY-MM-DD');
        } else if (selectedDate.isBefore(minDate, 'day')) {
            selectedDate = minDate;
            dateInput.value = selectedDate.format('YYYY-MM-DD');
        }
        
        // Calculate start of selected day (00:00) and start of next day (00:00)
        const start = selectedDate.clone().startOf('day').unix();
        const end = selectedDate.clone().add(1, 'days').startOf('day').unix();

        const response = await fetch(`${apiBaseUrl}history/?start=${start}&end=${end}`);
        const data = await response.json();

        if (Array.isArray(data)) {
            // Save current checkbox states before clearing
            const activeCheckboxes = Array.from(document.querySelectorAll('#historyControls input[type="checkbox"]:checked'))
                .map(cb => cb.id.replace('check_', ''));

            originalData = data;
            if (data.length > 0) {
                setupHistoryControls(data, activeCheckboxes);
            }
            
            if (!historyChart) {
                createHistoryChart(selectedDate);
            } else {
                updateChartAxis(selectedDate);
                updateChartDatasets();
            }
        }
    } catch (error) {
        console.error('Error fetching history:', error);
    }
}

const fieldDescriptions = {
    'TrackerWM_Avg': 'Direct Normal Irradiance(DNI)',
    'Tracker2WM_Avg': 'Direct Normal Irradiance(DNI) (not installed anymore)',
    'ShadowWM_Avg': 'Diffuse Horizontal Irradiance (DHI)',
    'SunWM_Avg': 'Global Horizontal Irradiance (GHI)',
    'ShadowbandWM_Avg': 'Diffuse Horizontal Irradiance (DHI) Shadow Band',
    'DNICalc_Avg': 'Calculated Direct Normal Irradiance(DNI)',
    'AirTC_Avg': 'Temperature',
    'RH': 'Relative Humidity',
    'WS_ms_S_WVT': 'Wind Speed',
    'WindDir_D1_WVT': 'Wind Direction',
    'WindDir_SD1_WVT': 'Wind direction standard deviation',
    'BP_mB_Avg': 'Barometric pressure',
    'UVA_Avg': 'Average UV A',
    'UVB_Avg': 'Average UV B'
};

function setupHistoryControls(data, activeKeys = []) {
    const controls = document.getElementById('historyControls');
    if (!controls) return;

    // Get all keys from the first record, excluding non-numeric/time fields
    const keys = Object.keys(data[0]).filter(key => 
        !['Date', 'Time', 'TimeStamp', 'Record'].includes(key)
    );

    controls.innerHTML = ''; // Clear existing

    keys.forEach(key => {
        const wrapper = document.createElement('div');
        wrapper.className = 'form-check mb-2';
        wrapper.style.breakInside = 'avoid';
    
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'form-check-input';
        checkbox.id = `check_${key}`;
        
        // Restore previous selection or use default if it's the first load
        if (activeKeys.length > 0) {
            checkbox.checked = activeKeys.includes(key);
        } else {
            checkbox.checked = (key === 'AirTC_Avg'); // Temperature default
        }
        
        checkbox.onchange = () => updateChartDatasets();

        const label = document.createElement('label');
        label.className = 'form-check-label fw-bold';
        label.htmlFor = `check_${key}`;
        label.textContent = key;

        const description = document.createElement('div');
        description.className = 'text-muted small';
        description.textContent = fieldDescriptions[key] || '';

        wrapper.appendChild(checkbox);
        wrapper.appendChild(label);
        wrapper.appendChild(description);
        controls.appendChild(wrapper);
    });
}

function createHistoryChart(selectedDate) {
    const ctx = document.getElementById('historyChart').getContext('2d');
    
    historyChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [] // Will be populated by updateChartDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    type: 'time',
                    min: selectedDate.clone().startOf('day').toDate(),
                    max: selectedDate.clone().add(1, 'days').startOf('day').toDate(),
                    time: {
                        unit: 'hour',
                        displayFormats: { hour: 'HH:mm' }
                    },
                    title: { display: true, text: 'Time' }
                },
                y: {
                    title: { display: true, text: 'Value' }
                }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    updateChartDatasets();
}

function updateChartAxis(selectedDate) {
    if (!historyChart) return;
    historyChart.options.scales.x.min = selectedDate.clone().startOf('day').toDate();
    historyChart.options.scales.x.max = selectedDate.clone().add(1, 'days').startOf('day').toDate();
}

function updateChartDatasets() {
    if (!historyChart) return;

    const datasets = [];
    const controls = document.querySelectorAll('#historyControls input[type="checkbox"]');
    
    // Define some colors for different fields
    const colors = [
        '#ff6384', '#36a2eb', '#4bc0c0', '#ff9f40', '#9966ff', 
        '#ffcd56', '#c9cbcf', '#77dd77', '#f4a460', '#e06666'
    ];

    controls.forEach((cb, index) => {
        if (cb.checked) {
            const key = cb.id.replace('check_', '');
            datasets.push({
                label: key.replace(/_/g, ' '),
                data: originalData.map(entry => ({
                    x: moment(entry.TimeStamp).toDate(),
                    y: entry[key]
                })),
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '33', // 20% opacity
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.2
            });
        }
    });

    historyChart.data.datasets = datasets;
    historyChart.update();
}

window.onload = () => {
    const dateInput = document.getElementById('historyDate');
    if (dateInput) {
        const today = moment().format('YYYY-MM-DD');
        dateInput.value = today;
        dateInput.max = today;
        dateInput.min = '2010-05-24';
        dateInput.onchange = fetchHistory;
    }
    fetchHistory();
};