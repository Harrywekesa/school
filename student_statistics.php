<?php
include 'db.php'; // Include the database connection file

// Get total number of students
$total_students_query = "SELECT COUNT(*) as total_students FROM students";
$total_students_result = $conn->query($total_students_query);
$total_students = $total_students_result->fetch(PDO::FETCH_ASSOC)['total_students'];

// Get number of students by class
$students_by_class_query = "SELECT class_admitted as class, COUNT(*) as count FROM students GROUP BY class_admitted";
$students_by_class_result = $conn->query($students_by_class_query);
$students_by_class = $students_by_class_result->fetchAll(PDO::FETCH_ASSOC);

// Get average age of students
$average_age_query = "SELECT AVG(age) as average_age FROM students";
$average_age_result = $conn->query($average_age_query);
$average_age = $average_age_result->fetch(PDO::FETCH_ASSOC)['average_age'];

echo json_encode([
    'total_students' => $total_students,
    'students_by_class' => $students_by_class,
    'average_age' => $average_age
]);
?>
