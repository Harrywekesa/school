<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE verification_token = :token");
        $stmt->bindParam(':token', $token);
        if ($stmt->execute()) {
            echo "Email verified successfully!";
        } else {
            echo "Error: Could not verify email.";
        }
    } else {
        echo "Invalid token.";
    }
}
?>
