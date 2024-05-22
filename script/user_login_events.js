document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Load login events
    fetch('get_user_login_events.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('login-events-table-body');
            data.forEach(event => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${event.username}</td>
                    <td>${event.date}</td>
                    <td>${event.time}</td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error:', error));
});
