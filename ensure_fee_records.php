<?php
include 'db.php';

// Initialize fee status for all students if they don't have a fee status record
$sql = "INSERT INTO fee_status (student_id, fee_amount, status)
        SELECT s.student_id, f.fee_amount, 'unpaid'
        FROM students s
        JOIN fees f ON s.class_admitted = f.class
        LEFT JOIN fee_status fs ON s.student_id = fs.student_id
        WHERE fs.student_id IS NULL";
$conn->query($sql);

echo "Initialized fee status records for all students.";
?>
