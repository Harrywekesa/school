<?php
include 'db.php'; // Include the database connection file

$report_year = $_POST['report_year'];
$report_term = $_POST['report_term'];

$response = [];

try {
    $sql = "SELECT COUNT(*) AS total_students,
                   SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_students,
                   SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS inactive_students
            FROM students
            WHERE year_admitted = ? AND term_admitted = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$report_year, $report_term]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $response['report'] = "<table>
                            <thead>
                                <tr>
                                    <th>Total Students</th>
                                    <th>Active Students</th>
                                    <th>Inactive Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{$result['total_students']}</td>
                                    <td>{$result['active_students']}</td>
                                    <td>{$result['inactive_students']}</td>
                                </tr>
                            </tbody>
                           </table>";

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close the connection
?>
