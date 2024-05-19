<?php

$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "toor"; // Replace with your MySQL password
$dbname = "primary_school";

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database_name');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$report_type = $_GET['report_type'];
$identifier = $_GET['identifier'];

if ($report_type == 'school') {
    $sql = "SELECT students.first_name, students.last_name, students.class_id, student_fees.fee FROM student_fees JOIN students ON student_fees.student_id = students.student_id";
} elseif ($report_type == 'class') {
    $sql = "SELECT students.first_name, students.last_name, students.class_id, student_fees.fee FROM student_fees JOIN students ON student_fees.student_id = students.student_id WHERE students.class_id='$identifier'";
} elseif ($report_type == 'student') {
    $sql = "SELECT students.first_name, students.last_name, students.class_id, student_fees.fee FROM student_fees JOIN students ON student_fees.student_id = students.student_id WHERE students.student_id='$identifier'";
} else {
    echo "Invalid report type";
    exit;
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table><tr><th>First Name</th><th>Last Name</th><th>Class</th><th>Fee</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['first_name']}</td><td>{$row['last_name']}</td><td>{$row['class_id']}</td><td>{$row['fee']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No records found";
}

$conn->close();
?>

<script>
    window.print();
</script>
