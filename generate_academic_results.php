<?php
include 'db.php'; // Include the database connection file

$year = $_POST['year'];
$term = $_POST['term'];
$class = $_POST['class'];
$student_id = $_POST['student_id'] ?? null;

$response = [];

try {
    if ($student_id) {
        // Fetch individual student results
        $sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name, 
                       m.marks, m.term, m.year
                FROM students s
                JOIN marks m ON s.student_id = m.student_id
                WHERE m.year = ? AND m.term = ? AND s.class_admitted = ? AND s.student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year, $term, $class, $student_id]);
    } else {
        // Fetch class results
        $sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name, 
                       m.marks, m.term, m.year
                FROM students s
                JOIN marks m ON s.student_id = m.student_id
                WHERE m.year = ? AND m.term = ? AND s.class_admitted = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year, $term, $class]);
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = "<table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Marks</th>
                        <th>Term</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($result as $row) {
        $results .= "<tr>
                        <td>{$row['student_id']}</td>
                        <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                        <td>{$row['marks']}</td>
                        <td>{$row['term']}</td>
                        <td>{$row['year']}</td>
                     </tr>";
    }
    $results .= "</tbody></table>";

    $response['results'] = $results;
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close the connection
?>
