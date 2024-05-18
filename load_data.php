<?php
include 'db.php';

$announcements = $conn->query("SELECT message FROM announcements ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$events = $conn->query("SELECT event_name, event_date FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['announcements' => $announcements, 'events' => $events]);
?>
