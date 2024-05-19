<?php
include 'db.php'; // Include the database connection file

if (!isset($_GET['admission_number'])) {
    echo "No admission number provided.";
    exit();
}

$admission_number = $_GET['admission_number'];

try {
    // Fetch the admission details
    $stmt = $conn->prepare("SELECT * FROM students WHERE admission_number = :admission_number");
    $stmt->bindParam(':admission_number', $admission_number);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "No details found for this admission number.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Details - Primary School</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function printDetails() {
            window.print();
        }
    </script>
</head>
<body>
    <header>
        <h1>Admission Details - Primary School</h1>
    </header>
    <main>
        <section>
            <h2>Student Admission Details</h2>
            <p><strong>Admission Number:</strong> <?php echo htmlspecialchars($student['admission_number']); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></p>
            <p><strong>Middle Name:</strong> <?php echo htmlspecialchars($student['middle_name']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($student['last_name']); ?></p>
            <p><strong>Class Admitted:</strong> <?php echo htmlspecialchars($student['class_admitted']); ?></p>
            <p><strong>Emergency Contact Name:</strong> <?php echo htmlspecialchars($student['emergency_contact_name']); ?></p>
            <p><strong>Emergency Contact Phone:</strong> <?php echo htmlspecialchars($student['emergency_contact_phone']); ?></p>
            <p><strong>Emergency Contact Email:</strong> <?php echo htmlspecialchars($student['emergency_contact_email']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($student['age']); ?></p>
            <p><strong>Admission Date:</strong> <?php echo htmlspecialchars($student['admission_date']); ?></p>
            <p><strong>Admitted By (ID):</strong> <?php echo htmlspecialchars($student['admitted_by']); ?></p>
            <p><strong>Student Photo:</strong> <img src="uploads/<?php echo htmlspecialchars($student['student_photo']); ?>" alt="Student Photo" style="max-width: 100px;"></p>
            <button onclick="printDetails()">Print</button>
            <a href="student_admission.html"><button>Back to Admission Form</button></a>
        </section>
    </main>
    <footer>
        <p>Contact us: info@primaryschool.com | +1 234 567 890</p>
    </footer>
</body>
</html>
