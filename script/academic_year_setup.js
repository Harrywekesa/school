document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    const yearSetupForm = document.getElementById('year-setup-form');
    yearSetupForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('create_academic_year.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Academic year created successfully.');
            } else {
                alert('Error creating academic year.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
