<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "toor"; // Replace with your MySQL password
$dbname = "primary_school";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST['first_name']); // Sanitize user input
    $middle_name = htmlspecialchars($_POST['middle_name']); // Sanitize user input
    $last_name = htmlspecialchars($_POST['last_name']); // Sanitize user input
    $class_admitted = htmlspecialchars($_POST['class_admitted']); // Sanitize user input
    $emergency_contact_name = htmlspecialchars($_POST['emergency_contact_name']); // Sanitize user input
    $emergency_contact_phone = htmlspecialchars($_POST['emergency_contact_phone']); // Sanitize user input
    $emergency_contact_email = filter_var($_POST['emergency_contact_email'], FILTER_SANITIZE_EMAIL); // Sanitize and validate email
    $age = (int)$_POST['age']; // Ensure age is an integer

    // Handle file upload with validation
    $student_photo = "";
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == UPLOAD_ERR_OK) {
        $allowed_extensions = array('jpg', 'jpeg', 'png');
        $photo_extension = pathinfo($_FILES['student_photo']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($photo_extension), $allowed_extensions)) {
            $photo_tmp_name = $_FILES['student_photo']['tmp_name'];
            $photo_name = basename($_FILES['student_photo']['name']);
            $upload_dir = 'uploads/';
            $student_photo = $upload_dir . uniqid() . "_" . $photo_name; // Use a unique name to prevent overwriting
            move_uploaded_file($photo_tmp_name, $student_photo);
        } else {
            echo "Invalid file type. Please upload an image (jpg, jpeg, or png).";
            exit(); // Stop script execution
        }
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO students (first_name, middle_name, last_name, class_admitted, emergency_contact_name, emergency_contact_phone, emergency_contact_email, age, student_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssii", $first_name, $middle_name, $last_name, $class_admitted, $emergency_contact_name, $emergency_contact_phone, $emergency_contact_email, $age, $student_photo);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
}
?>
