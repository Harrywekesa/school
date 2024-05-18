document.addEventListener('DOMContentLoaded', () => {
    // Toggle menu for mobile
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('show-menu');
    });

    // Form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in both username and password.');
            }
        });
    }

    // Load announcements dynamically
    const announcementsList = document.getElementById('announcements-list');
    const announcements = [
        'School will be closed on the 4th of July.',
        'Parent-teacher meetings will be held next week.'
    ];

    announcements.forEach(announcement => {
        const li = document.createElement('li');
        li.textContent = announcement;
        announcementsList.appendChild(li);
    });

    // Load upcoming events dynamically
    const eventsList = document.getElementById('events-list');
    const events = [
        'Sports Day - 10th June',
        'Science Fair - 20th June'
    ];

    events.forEach(event => {
        const li = document.createElement('li');
        li.textContent = event;
        eventsList.appendChild(li);
    });

    // Load staff details dynamically
    const staffList = document.getElementById('staff-list');
    const staffMembers = [
        'Mr. John Doe - Principal',
        'Ms. Jane Smith - Vice Principal',
        'Mrs. Emily Johnson - Teacher',
        'Mr. Michael Brown - Teacher'
    ];

    if (staffList) {
        staffMembers.forEach(member => {
            const li = document.createElement('li');
            li.textContent = member;
            staffList.appendChild(li);
        });
    }

    // Feedback form submission
    const feedbackForm = document.getElementById('feedback-form');
    const feedbackSuccess = document.getElementById('feedback-success');

    if (feedbackForm) {
        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;

            if (!name || !email || !message) {
                alert('Please fill in all fields.');
                return;
            }

            // Simulate form submission
            setTimeout(() => {
                feedbackForm.reset();
                feedbackSuccess.classList.remove('hidden');
                setTimeout(() => {
                    feedbackSuccess.classList.add('hidden');
                }, 3000);
            }, 500);
        });
    }
});
