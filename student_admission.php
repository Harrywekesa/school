<?php
include 'db.php'; // Include the database connection file

$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$last_name = $_POST['last_name'];
$birth_certificate_number = $_POST['birth_certificate_number'];
$gender = $_POST['gender'];
$class_admitted = $_POST['class_admitted'];
$emergency_contact_name = $_POST['emergency_contact_name'];
$emergency_contact_phone = $_POST['emergency_contact_phone'];
$emergency_contact_email = $_POST['emergency_contact_email'];
$age = $_POST['age'];
$admission_date = $_POST['admission_date'];
$admitted_by = $_POST['admitted_by'];
$student_photo = $_FILES['student_photo']['name'];

// Generate Admission Number
$admission_number = generateAdmissionNumber($conn, $admission_date);

// Handle file upload
if (!empty($student_photo)) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($student_photo);
    move_uploaded_file($_FILES["student_photo"]["tmp_name"], $target_file);
}

try {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO students (first_name, middle_name, last_name, birth_certificate_number, gender, class_admitted, emergency_contact_name, emergency_contact_phone, emergency_contact_email, age, admission_date, admitted_by, student_photo, student_id) VALUES (:first_name, :middle_name, :last_name, :birth_certificate_number, :gender, :class_admitted, :emergency_contact_name, :emergency_contact_phone, :emergency_contact_email, :age, :admission_date, :admitted_by, :student_photo, :admission_number)");

    // Bind the parameters
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':middle_name', $middle_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':class_admitted', $class_admitted);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':birth_certificate_number', $birth_certificate_number);
    $stmt->bindParam(':emergency_contact_name', $emergency_contact_name);
    $stmt->bindParam(':emergency_contact_phone', $emergency_contact_phone);
    $stmt->bindParam(':emergency_contact_email', $emergency_contact_email);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':admission_date', $admission_date);
    $stmt->bindParam(':admitted_by', $admitted_by);
    $stmt->bindParam(':student_photo', $student_photo);
    $stmt->bindParam(':admission_number', $admission_number);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful insertion, return the student details and admission number
        $response = [
            'admission_number' => $admission_number,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'birth_certificate_number' => $birth_certificate_number,
            'gender' => $gender,
            'class_admitted' => $class_admitted,
            'emergency_contact_name' => $emergency_contact_name,
            'emergency_contact_phone' => $emergency_contact_phone,
            'emergency_contact_email' => $emergency_contact_email,
            'age' => $age,
            'admission_date' => $admission_date,
            'admitted_by' => $admitted_by,
            'student_photo' => $student_photo
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => "Error: " . $stmt->errorInfo()[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
}

$conn = null;

// Function to generate admission number
function generateAdmissionNumber($conn, $admission_date) {
    $date = date_create($admission_date);
    $year = date_format($date, "y");
    $month = date_format($date, "m");

    // Get the count of students admitted on the same date
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE admission_date = ?");
    $stmt->bindParam(1, $admission_date);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // Format the admission number
    $admission_number = $count + 1 . "/" . $month . "/" . $year;

    return $admission_number;
}
?>
