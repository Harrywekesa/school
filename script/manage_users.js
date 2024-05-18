document.addEventListener('DOMContentLoaded', function() {
    // Fetch existing users and display them
    fetchUsers();

    function fetchUsers() {
        // Make AJAX request to fetch users
        fetch('fetch_users.php')
            .then(response => response.json())
            .then(users => {
                // Update HTML with fetched user data
                const userList = document.getElementById('user-list');
                userList.innerHTML = '';

                users.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>${user.email_verified}</td>
                        <td>Actions</td>
                    `;
                    userList.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching users:', error));
    }
});
