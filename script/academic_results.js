document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    const resultsForm = document.getElementById('results-form');
    const resultsResult = document.getElementById('results-result');
    const resultsContent = document.getElementById('results-content');
    const printResultsButton = document.getElementById('print-results');

    resultsForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('generate_academic_results.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            resultsResult.classList.remove('hidden');
            resultsContent.innerHTML = data.results;
        })
        .catch(error => console.error('Error:', error));
    });

    printResultsButton.addEventListener('click', function() {
        window.print();
    });
});
