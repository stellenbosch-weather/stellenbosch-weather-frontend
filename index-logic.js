async function update() {
    console.log('Updating weather data...');
    clearAlertMessage();

    try {
        const response = await fetch('http://localhost:8081/current');
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error fetching data:', error);
        setAlertMessage('Error fetching weather data', 'warning')
    }
}

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

function clearAlertMessage() {
    alertPlaceholder.innerHTML = '';
}

function setAlertMessage(message, type) {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    alertPlaceholder.innerHTML = '';
    alertPlaceholder.append(wrapper)
}

window.onload = update;
window.setInterval(update, 60000);