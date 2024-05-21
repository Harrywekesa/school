<?php
include 'db.php'; // Include the database connection file

$year = $_POST['year'];
$term = $_POST['term'];
$class = $_POST['class'];
$student_id = $_POST['student_id'];
$marks = $_POST['marks'];

try {
    $sql = "INSERT INTO marks (year, term, class, student_id, marks) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$year, $term, $class, $student_id, $marks]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close the connection
?>
