document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Load log file data
    fetch('get_system_log_file.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('log-file-table-body');
            data.forEach(log => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${log.username}</td>
                    <td>${log.activity}</td>
                    <td>${log.date}</td>
                    <td>${log.time}</td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error:', error));
});
