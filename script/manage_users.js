document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Load users
    fetch('get_users.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('users-table-body');
            data.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.status}</td>
                    <td>
                        <button onclick="toggleUserStatus(${user.id}, '${user.status}')">${user.status === 'active' ? 'Deactivate' : 'Activate'}</button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error:', error));
});

function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    fetch('update_user_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: userId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload the page to reflect changes
        } else {
            alert('Error updating user status.');
        }
    })
    .catch(error => console.error('Error:', error));
}
