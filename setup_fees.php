<?php
include 'db.php'; // Include the database connection file

$class = $_POST['class'];
$fee_amount = $_POST['fee_amount'];

try {
    $stmt = $conn->prepare("INSERT INTO fees (class, fee_amount) VALUES (:class, :fee_amount)");
    $stmt->bindParam(':class', $class);
    $stmt->bindParam(':fee_amount', $fee_amount);

    if ($stmt->execute()) {
        echo "Fee setup successfully completed";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<script>
    window.onload = function() {
        document.getElementById('fee-setup-success').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('fee-setup-success').classList.add('hidden');
        }, 3000);
    }
</script>
