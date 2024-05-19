<?php
include 'db.php'; // Include the database connection file

// Ensure that the JOINs and field names match the database schema correctly
$sql = "SELECT s.first_name, s.middle_name, s.last_name, s.class_admitted, f.fee_amount, fs.status 
        FROM students s
        JOIN fees f ON s.class_admitted = f.class
        LEFT JOIN fee_status fs ON s.student_id = fs.student_id";
$result = $conn->query($sql);

$fee_status_data = [];

if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $fee_status_data[] = $row;
    }
}

echo json_encode($fee_status_data);
?>
