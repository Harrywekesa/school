<?php
include 'db.php'; // Include the database connection file

$year = $_POST['year'];
$term = $_POST['term'];

try {
    $sql = "INSERT INTO academic_sessions (year, term) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$year, $term]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close the connection
?>
