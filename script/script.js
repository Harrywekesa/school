document.addEventListener('DOMContentLoaded', () => {
    // Toggle menu for mobile
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Form validation for login
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
    if (announcementsList) {
        const announcements = [
            'School will be closed on the 4th of July.',
            'Parent-teacher meetings will be held next week.'
        ];

        announcements.forEach(announcement => {
            const li = document.createElement('li');
            li.textContent = announcement;
            announcementsList.appendChild(li);
        });
    }

    // Load upcoming events dynamically
    const eventsList = document.getElementById('events-list');
    if (eventsList) {
        const events = [
            'Sports Day - 10th June',
            'Science Fair - 20th June'
        ];

        events.forEach(event => {
            const li = document.createElement('li');
            li.textContent = event;
            eventsList.appendChild(li);
        });
    }

    // Load staff details dynamically
    const staffList = document.getElementById('staff-list');
    if (staffList) {
        const staffMembers = [
            'Mr. John Doe - Principal',
            'Ms. Jane Smith - Vice Principal',
            'Mrs. Emily Johnson - Teacher',
            'Mr. Michael Brown - Teacher'
        ];

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

    // Form validation and dynamic class addition for student admission
    const admissionForm = document.getElementById('admission-form');
    const addClassBtn = document.getElementById('add-class-btn');
    const classAdmittedSelect = document.getElementById('class_admitted');
    const admissionSuccess = document.getElementById('admission-success');

    if (addClassBtn) {
        addClassBtn.addEventListener('click', () => {
            const newClass = prompt('Enter new class:');
            if (newClass) {
                const newOption = document.createElement('option');
                newOption.value = newClass;
                newOption.textContent = newClass;
                classAdmittedSelect.appendChild(newOption);
            }
        });
    }

    if (admissionForm) {
        admissionForm.addEventListener('submit', (e) => {
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const emergencyContactName = document.getElementById('emergency_contact_name').value;
            const emergencyContactPhone = document.getElementById('emergency_contact_phone').value;
            const emergencyContactEmail = document.getElementById('emergency_contact_email').value;
            const age = document.getElementById('age').value;

            if (!firstName || !lastName || !emergencyContactName || !emergencyContactPhone || !emergencyContactEmail || !age) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }

            // Simulate form submission and display success message
            e.preventDefault();
            setTimeout(() => {
                admissionForm.reset();
                admissionSuccess.classList.remove('hidden');
                setTimeout(() => {
                    admissionSuccess.classList.add('hidden');
                }, 3000);
            }, 500);
        });
    }
});
