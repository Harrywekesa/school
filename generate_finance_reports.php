<?php
include 'db.php'; // Include the database connection file

$report_type = $_POST['report_type'];
$response = [];

try {
    if ($report_type == 'school') {
        // Fetch entire school report
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
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = "<table>
                    <thead>
                        <tr>
                            <th>Admission Number</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Total Fee Payable</th>
                            <th>Fee Paid</th>
                            <th>Fee Balance</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($result as $row) {
            $report .= "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                            <td>{$row['class_admitted']}</td>
                            <td>" . number_format($row['total_fee_payable'], 2) . "</td>
                            <td>" . number_format($row['fee_paid'], 2) . "</td>
                            <td>" . number_format($row['fee_balance'], 2) . "</td>
                        </tr>";
        }
        $report .= "</tbody></table>";
        $response['report'] = $report;

    } elseif ($report_type == 'class') {
        // Fetch class report
        $class = $_POST['class'];
        $sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name, 
                       f.fee_amount AS total_fee_payable, 
                       COALESCE(SUM(p.amount_paid), 0) AS fee_paid, 
                       (f.fee_amount - COALESCE(SUM(p.amount_paid), 0)) AS fee_balance
                FROM students s
                JOIN fees f ON s.class_admitted = f.class
                LEFT JOIN payments p ON s.student_id = p.student_id
                WHERE s.class_admitted = ?
                GROUP BY s.student_id, s.first_name, s.middle_name, s.last_name, f.fee_amount";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$class]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = "<table>
                    <thead>
                        <tr>
                            <th>Admission Number</th>
                            <th>Student Name</th>
                            <th>Total Fee Payable</th>
                            <th>Fee Paid</th>
                            <th>Fee Balance</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($result as $row) {
            $report .= "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                            <td>" . number_format($row['total_fee_payable'], 2) . "</td>
                            <td>" . number_format($row['fee_paid'], 2) . "</td>
                            <td>" . number_format($row['fee_balance'], 2) . "</td>
                        </tr>";
        }
        $report .= "</tbody></table>";
        $response['report'] = $report;

    } elseif ($report_type == 'student') {
        // Fetch individual student report
        $student_id = $_POST['student_id'];
        $sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name, s.class_admitted, 
                       f.fee_amount AS total_fee_payable, 
                       COALESCE(SUM(p.amount_paid), 0) AS fee_paid, 
                       (f.fee_amount - COALESCE(SUM(p.amount_paid), 0)) AS fee_balance
                FROM students s
                JOIN fees f ON s.class_admitted = f.class
                LEFT JOIN payments p ON s.student_id = p.student_id
                WHERE s.student_id = ?
                GROUP BY s.student_id, s.first_name, s.middle_name, s.last_name, s.class_admitted, f.fee_amount";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$student_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = "<table>
                    <thead>
                        <tr>
                            <th>Admission Number</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Total Fee Payable</th>
                            <th>Fee Paid</th>
                            <th>Fee Balance</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($result as $row) {
            $report .= "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                            <td>{$row['class_admitted']}</td>
                            <td>" . number_format($row['total_fee_payable'], 2) . "</td>
                            <td>" . number_format($row['fee_paid'], 2) . "</td>
                            <td>" . number_format($row['fee_balance'], 2) . "</td>
                        </tr>";
        }
        $report .= "</tbody></table>";
        $response['report'] = $report;
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}

$conn = null; // Close the connection
?>
