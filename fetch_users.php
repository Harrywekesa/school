<?php
include 'db.php';

// Fetch users from the database
$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert the user data to JSON format
echo json_encode($users);
?>
