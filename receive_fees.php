<?php
include 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admission_number = $_POST['admission_number'];
    $payment_method = $_POST['payment_method'];
    $amount_paid = $_POST['amount_paid'];
    $mpesa_phone_number = isset($_POST['mpesa_phone_number']) ? $_POST['mpesa_phone_number'] : null;

    // Fetch the student's details
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :admission_number");
    $stmt->bindParam(':admission_number', $admission_number);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "No student found with this admission number.";
        exit();
    }

    $student_id = $student['student_id'];
    $class_admitted = $student['class_admitted'];

    // Fetch the fee amount for the class
    $stmt = $conn->prepare("SELECT fee_amount FROM fees WHERE class = :class_admitted");
    $stmt->bindParam(':class_admitted', $class_admitted);
    $stmt->execute();
    $class_fee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$class_fee) {
        echo "No fee record found for this class.";
        exit();
    }

    $total_fee_amount = $class_fee['fee_amount'];

    // Fetch fee status
    $stmt = $conn->prepare("SELECT * FROM fee_status WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $fee_status = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fee_status) {
        // Insert initial fee status if it doesn't exist
        $stmt = $conn->prepare("INSERT INTO fee_status (student_id, fee_amount, status) VALUES (:student_id, :fee_amount, 'unpaid')");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':fee_amount', $total_fee_amount);
        $stmt->execute();

        $fee_status['fee_amount'] = $total_fee_amount;
    }

    $new_balance = $fee_status['fee_amount'] - $amount_paid;

    // Update fee status
    $stmt = $conn->prepare("UPDATE fee_status SET fee_amount = :new_balance, status = IF(:new_balance > 0, 'unpaid', 'paid') WHERE student_id = :student_id");
    $stmt->bindParam(':new_balance', $new_balance);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    // Insert payment record
    if ($payment_method == 'mpesa') {
        $stmt = $conn->prepare("INSERT INTO payments (student_id, amount_paid, payment_method, mpesa_phone_number, payment_date) VALUES (:student_id, :amount_paid, :payment_method, :mpesa_phone_number, NOW())");
        $stmt->bindParam(':mpesa_phone_number', $mpesa_phone_number);
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (student_id, amount_paid, payment_method, payment_date) VALUES (:student_id, :amount_paid, :payment_method, NOW())");
    }
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':amount_paid', $amount_paid);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->execute();

    echo "Payment received successfully. New balance: " . $new_balance;

    // Fetch updated fee status for the receipt
    $stmt = $conn->prepare("SELECT * FROM fee_status WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $updated_fee_status = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch payment history and fee statement for a student
$student_payments = [];
if (isset($_POST['admission_number'])) {
    $stmt = $conn->prepare("SELECT * FROM payments WHERE student_id = :student_id ORDER BY payment_date DESC");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $student_payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Fees - Primary School</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function toggleMpesaField() {
            var paymentMethod = document.getElementById('payment_method').value;
            var mpesaField = document.getElementById('mpesa-phone-number-field');
            if (paymentMethod === 'mpesa') {
                mpesaField.style.display = 'block';
            } else {
                mpesaField.style.display = 'none';
            }
        }
        function printReceipt() {
            window.print();
        }
    </script>
</head>
<body>
    <header>
        <h1>Receive Fees - Primary School</h1>
    </header>
    <main>
        <section>
            <h2>Receive and Receipt Fees</h2>
            <form id="receive-fees-form" action="receive_fees.php" method="post">
                <div class="form-row">
                    <label for="admission_number">Admission Number:</label>
                    <input type="text" id="admission_number" name="admission_number" required>
                </div>
                <div class="form-row">
                    <label for="payment_method">Payment Method:</label>
                    <select id="payment_method" name="payment_method" onchange="toggleMpesaField()" required>
                        <option value="cash">Cash</option>
                        <option value="mpesa">Mpesa</option>
                    </select>
                </div>
                <div class="form-row" id="mpesa-phone-number-field" style="display: none;">
                    <label for="mpesa_phone_number">Mpesa Phone Number:</label>
                    <input type="text" id="mpesa_phone_number" name="mpesa_phone_number">
                </div>
                <div class="form-row">
                    <label for="amount_paid">Amount Paid:</label>
                    <input type="number" id="amount_paid" name="amount_paid" required>
                </div>
                <button type="submit" class="green-button">Submit Payment</button>
            </form>
            <?php if (isset($updated_fee_status)) { ?>
                <div id="receipt">
                    <h3>Fee Receipt</h3>
                    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']); ?></p>
                    <p><strong>Admission Number:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                    <p><strong>Class:</strong> <?php echo htmlspecialchars($student['class_admitted']); ?></p>
                    <p><strong>Amount Paid:</strong> <?php echo htmlspecialchars($amount_paid); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
                    <?php if ($payment_method == 'mpesa') { ?>
                        <p><strong>Mpesa Phone Number:</strong> <?php echo htmlspecialchars($mpesa_phone_number); ?></p>
                    <?php } ?>
                    <p><strong>New Balance:</strong> <?php echo htmlspecialchars($updated_fee_status['fee_amount']); ?></p>
                    <button onclick="printReceipt()">Print Receipt</button>
                </div>
            <?php } ?>
        </section>
        <?php if (!empty($student_payments)) { ?>
        <section>
            <h2>Fee Statement</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                        <th>Mpesa Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student_payments as $payment) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($payment['amount_paid']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($payment['mpesa_phone_number'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
        <?php } ?>
    </main>
    <footer>
        <p>Contact us: info@primaryschool.com | +1 234 567 890</p>
    </footer>
</body>
</html>
