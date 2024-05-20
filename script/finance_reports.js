document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('report-type');
    const additionalOptions = document.getElementById('additional-options');
    const classSelect = document.getElementById('class');
    const studentIdInput = document.getElementById('student-id');
    const reportForm = document.getElementById('report-form');
    const reportResult = document.getElementById('report-result');
    const reportContent = document.getElementById('report-content');

    reportTypeSelect.addEventListener('change', function() {
        if (this.value === 'class') {
            additionalOptions.classList.remove('hidden');
            classSelect.required = true;
            studentIdInput.required = false;
            studentIdInput.parentElement.classList.add('hidden');
            classSelect.parentElement.classList.remove('hidden');
        } else if (this.value === 'student') {
            additionalOptions.classList.remove('hidden');
            classSelect.required = false;
            studentIdInput.required = true;
            studentIdInput.parentElement.classList.remove('hidden');
            classSelect.parentElement.classList.add('hidden');
        } else {
            additionalOptions.classList.add('hidden');
            classSelect.required = false;
            studentIdInput.required = false;
        }
    });

    reportForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('generate_finance_reports.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            reportResult.classList.remove('hidden');
            reportContent.innerHTML = data.report;
        })
        .catch(error => console.error('Error:', error));
    });
});
