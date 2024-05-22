<?php
include 'db.php';

$year = $_POST['year'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

try {
    $sql = "INSERT INTO academic_years (year, start_date, end_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$year, $start_date, $end_date]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
