const apiBaseUrl = 'http://localhost:8081/';
// const apiBaseUrl = 'http://weather.sun.ac.za/api/';

const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

    async function downloadFile(station) {
        const checkbox = document.getElementById('acceptTermsYes');
        const emailInput = document.getElementById('email');
        const url = `${apiBaseUrl}download/`;

        const body = {
            file: station,
            agree: checkbox.checked ? 'yes' : 'no',
            email: emailInput.value
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(body)
            });

            if (!response.ok) {
                const errorText = await response.text();
                alertPlaceholder.innerHTML = `<div class="alert alert-danger" role="alert">Error downloading file: ${errorText || response.status}</div>`;
                return;
            }

            const blob = await response.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = `weather-data-${station}.zip`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(downloadUrl);
            a.remove();
        } catch (error) {
            alertPlaceholder.innerHTML = `<div class="alert alert-danger" role="alert">Error downloading file: ${error.message}</div>`;
        }
    }

    function enableButtons() {
    const checkbox = document.getElementById('acceptTermsYes');
    const emailInput = document.getElementById('email');
    const buttons = document.querySelectorAll('button[type="button"]');

    const isValid = checkbox.checked && emailInput.value.includes('@');

    buttons.forEach(button => {
        button.disabled = !isValid;
    });

}

window.onload = enableButtons;

const inputElement = document.getElementById('email');
inputElement.addEventListener('input', enableButtons);

const checkboxElement = document.getElementById('acceptTermsYes');
checkboxElement.addEventListener('change', enableButtons);