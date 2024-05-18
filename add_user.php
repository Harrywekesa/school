<?php
include 'db.php';
require 'PHPMailer/src/PHPMailer.php'; // Include the PHPMailer class file
require 'PHPMailer/src/SMTP.php'; // Include the SMTP class file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the username already exists
    $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $statement->bindParam(':username', $username);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        echo "Username already exists. Please choose a different username.";
        exit;
    }

    // Hash the password before saving it to the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insertStatement = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $insertStatement->bindParam(':username', $username);
    $insertStatement->bindParam(':password', $hashedPassword);
    $insertStatement->bindParam(':role', $role);

    if ($insertStatement->execute()) {
        echo "User registered successfully";

        // Sending email notification
        $mail = new PHPMailer(); // Create a new PHPMailer instance
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.example.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'your-email@example.com'; // SMTP username
        $mail->Password = 'your-password'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom('your-email@example.com', 'Your Name'); // Set email format: from (email, name)
        $mail->addAddress($username); // Add a recipient
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Registration Confirmation'; // Email subject
        $mail->Body = 'Congratulations! You have successfully registered as a user.'; // Email body

        if ($mail->send()) {
            echo "Email notification sent successfully";
        } else {
            echo "Error: Email notification could not be sent";
        }

        // Redirect to the login page or another page
        header('Location: login.html');
        exit;
    } else {
        echo "Error: Could not register user";
    }
} else {
    echo "Invalid request method.";
}
?>
