<?php
include 'db.php'; // Include the database connection file

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

try {
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (:name, :email, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->execute();
    echo "Feedback successfully submitted";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<script>
    window.onload = function() {
        document.getElementById('feedback-success').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('feedback-success').classList.add('hidden');
        }, 3000);
    }
</script>
