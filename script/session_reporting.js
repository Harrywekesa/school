document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    const sessionForm = document.getElementById('session-form');
    sessionForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('create_session.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Session created successfully.');
            } else {
                alert('Error creating session.');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    const reportForm = document.getElementById('report-form');
    reportForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('generate_session_reports.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const reportResult = document.getElementById('report-result');
            const reportContent = document.getElementById('report-content');
            reportResult.classList.remove('hidden');
            reportContent.innerHTML = data.report;
        })
        .catch(error => console.error('Error:', error));
    });
});
