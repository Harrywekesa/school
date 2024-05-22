<?php
include 'db.php';

try {
    $sql = "SELECT u.username, DATE(l.login_time) as date, TIME(l.login_time) as time
            FROM user_logins l
            JOIN users u ON l.user_id = u.id
            ORDER BY l.login_time DESC";
    $stmt = $conn->query($sql);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn = null;
?>
