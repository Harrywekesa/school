document.addEventListener('DOMContentLoaded', () => {
    // Toggle menu for mobile
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Fetch and display fee status
    if (document.getElementById('fee-status-table-body')) {
        fetch('fee_status.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('fee-status-table-body');
                tableBody.innerHTML = ''; // Clear any existing content

                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.student_id}</td>
                        <td>${row.first_name} ${row.middle_name} ${row.last_name}</td>
                        <td>${row.class_admitted}</td>
                        <td>${parseFloat(row.total_fee_payable).toFixed(2)}</td>
                        <td>${parseFloat(row.fee_paid).toFixed(2)}</td>
                        <td>${parseFloat(row.fee_balance).toFixed(2)}</td>
                    `;
                    tableBody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error fetching fee status:', error));
    }


    // Fetch and display student statistics
    fetch('student_statistics.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-students').textContent = data.total_students;

            const studentsByClassList = document.getElementById('students-by-class');
            studentsByClassList.innerHTML = ''; // Clear any existing content
            data.students_by_class.forEach(classData => {
                const listItem = document.createElement('li');
                listItem.textContent = `Class ${classData.class}: ${classData.count} students`;
                studentsByClassList.appendChild(listItem);
            });

            document.getElementById('average-age').textContent = data.average_age.toFixed(2);
        })
        .catch(error => {
            console.error('Error fetching student statistics:', error);
        });

    // Fetch and display fee status
    fetch('fee_status.php')
        .then(response => response.json())
        .then(data => {
            const feeStatusTableBody = document.getElementById('fee-status-table-body');
            feeStatusTableBody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.first_name} ${item.middle_name} ${item.last_name}</td>
                    <td>${item.class_admitted}</td>
                    <td>${item.fee_amount}</td>
                    <td>${item.status || 'Pending'}</td>
                `;
                feeStatusTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching fee status:', error);
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

     // Function to update the admission number field and show the success message
     function updateAdmissionNumber(admission_number) {
        document.getElementById("admission_number").value = admission_number;
        document.getElementById("admission-success").classList.remove("hidden");
    }

    // AJAX request to submit the form data
    document.getElementById("admission-form").addEventListener("submit", function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "student_admission.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.admission_number) {
                        const params = new URLSearchParams(response).toString();
                        window.location.href = 'admission_details.html?' + params;
                    } else if (response.error) {
                        alert(response.error);
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            }
        };
        xhr.send(formData);
    });

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

    if (document.getElementById('admission-form')) {
        document.getElementById('admission-form').addEventListener('submit', (e) => {
            const firstName = document.getElementById('first_name').value;
            const middleName = document.getElementById('middle_name').value;
            const lastName = document.getElementById('last_name').value;
            const birthCertificateNumber = document.getElementById('birth_certificate_number').value;
            const emergencyContactName = document.getElementById('emergency_contact_name').value;
            const emergencyContactPhone = document.getElementById('emergency_contact_phone').value;
            const emergencyContactEmail = document.getElementById('emergency_contact_email').value;
            const age = document.getElementById('age').value;

            if (!firstName || !lastName || !birthCertificateNumber || !emergencyContactName || !emergencyContactPhone || !emergencyContactEmail || !age) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }

            if (!validateEmail(emergencyContactEmail)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }

            if (!validatePhoneNumber(emergencyContactPhone)) {
                e.preventDefault();
                alert('Please enter a valid phone number.');
                return;
            }

            setTimeout(() => {
                admissionSuccess.classList.remove('hidden');
                setTimeout(() => {
                    admissionSuccess.classList.add('hidden');
                }, 3000);
            }, 500);
        });
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePhoneNumber(phoneNumber) {
        const re = /^\d{10}$/;
        return re.test(phoneNumber);
    }
});
