<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['id'];
$status = $data['status'];

try {
    $sql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$status, $userId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
