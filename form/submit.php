<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$db = 'propelrr_php';
$user = 'root';
$pass = '';
$port = '3306';

$dsn = "mysql:host=$host;dbname=$db;port=$port";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection error: ' . $e->getMessage()]);
    exit;
}

$errors = [];

// Server-side validation
$fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
$dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

if (empty($fullName) || !preg_match("/^[a-zA-Z,. ]+$/", $fullName)) {
    $errors['fullName'] = "Invalid full name";
}
if ($email === false) {
    $errors['email'] = "Invalid email address";
}
if (!preg_match("/^09[0-9]{9}$/", $mobile)) {
    $errors['mobile'] = "Invalid mobile number";
}
if (empty($dob)) {
    $errors['dob'] = "Date of birth is required";
}
if (empty($gender)) {
    $errors['gender'] = "Gender is required";
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, mobile, dob, age, gender) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $fullName,
        $email,
        $mobile,
        $dob,
        $age,
        $gender
    ]);
    echo json_encode(['success' => true]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database insert error: ' . $e->getMessage()]);
}
