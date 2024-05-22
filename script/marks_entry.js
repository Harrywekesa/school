document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // Load years
    fetch('get_years.php')
        .then(response => response.json())
        .then(data => {
            const yearSelect = document.getElementById('year');
            data.forEach(year => {
                const option = document.createElement('option');
                option.value = year.year;
                option.textContent = year.year;
                yearSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));

    // Load classes
    fetch('get_classes.php')
        .then(response => response.json())
        .then(data => {
            const classSelect = document.getElementById('class');
            data.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.class;
                option.textContent = cls.class;
                classSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));

    // Load subjects
    fetch('get_subjects.php')
        .then(response => response.json())
        .then(data => {
            const subjectSelect = document.getElementById('subject');
            data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.subject;
                option.textContent = subject.subject;
                subjectSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
});

function loadStudents() {
    const year = document.getElementById('year').value;
    const term = document.getElementById('term').value;
    const cls = document.getElementById('class').value;
    const subject = document.getElementById('subject').value;

    fetch(`get_students.php?year=${year}&term=${term}&class=${cls}&subject=${subject}`)
        .then(response => response.json())
        .then(data => {
            const studentsContainer = document.getElementById('students-container');
            studentsContainer.innerHTML = '';

            data.forEach(student => {
                const div = document.createElement('div');
                div.classList.add('form-row');
                div.innerHTML = `
                    <label for="student-${student.id}">${student.name}</label>
                    <input type="number" id="student-${student.id}" name="marks[${student.id}]" required>
                `;
                studentsContainer.appendChild(div);
            });

            studentsContainer.classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

document.getElementById('marks-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('submit_marks.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Marks submitted successfully.');
            document.getElementById('marks-form').reset();
            document.getElementById('students-container').classList.add('hidden');
        } else {
            alert('Error submitting marks.');
        }
    })
    .catch(error => console.error('Error:', error));
});
