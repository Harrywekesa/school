<?php
include 'db.php'; // Include the database connection file

try {
    // Ensure that the JOINs and field names match the database schema correctly
    $sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name, s.class_admitted, 
                   f.fee_amount AS total_fee_payable, 
                   COALESCE(SUM(p.amount_paid), 0) AS fee_paid, 
                   (f.fee_amount - COALESCE(SUM(p.amount_paid), 0)) AS fee_balance
            FROM students s
            JOIN fees f ON s.class_admitted = f.class
            LEFT JOIN payments p ON s.student_id = p.student_id
            GROUP BY s.student_id, s.first_name, s.middle_name, s.last_name, s.class_admitted, f.fee_amount";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $fee_status_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($fee_status_data);
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>
