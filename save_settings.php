<?php
include 'db.php'; // Include the database connection file

$institution_name = $_POST['institution_name'];
$theme = $_POST['theme'];
$contact_phone = $_POST['contact_phone'];
$contact_email = $_POST['contact_email'];
$vision = $_POST['vision'];
$mission = $_POST['mission'];

if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
    $logo_tmp_name = $_FILES['logo']['tmp_name'];
    $logo_name = basename($_FILES['logo']['name']);
    $upload_dir = 'uploads/';
    $logo_path = $upload_dir . $logo_name;
    move_uploaded_file($logo_tmp_name, $logo_path);
} else {
    $logo_path = null;
}

try {
    $sql = "INSERT INTO system_settings (institution_name, theme, logo, contact_phone, contact_email, vision, mission)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            institution_name = VALUES(institution_name),
            theme = VALUES(theme),
            logo = VALUES(logo),
            contact_phone = VALUES(contact_phone),
            contact_email = VALUES(contact_email),
            vision = VALUES(vision),
            mission = VALUES(mission)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$institution_name, $theme, $logo_path, $contact_phone, $contact_email, $vision, $mission]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close the connection
?>
