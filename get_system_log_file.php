<?php
include 'db.php';

try {
    $sql = "SELECT u.username, l.activity, DATE(l.activity_time) as date, TIME(l.activity_time) as time
            FROM system_log l
            JOIN users u ON l.user_id = u.id
            ORDER BY l.activity_time DESC";
    $stmt = $conn->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($logs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn = null;
?>
