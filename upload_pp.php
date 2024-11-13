<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "esport");

header('Content-Type: application/json'); 

if ($mysqli->connect_errno) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

$role = $_SESSION['profile'] ?? null;
$idteam = $_POST['idteam'] ?? null;

if (!$role || $role !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'User role is invalid or not set.']);
    exit();
}

if (!$idteam) {
    echo json_encode(['status' => 'error', 'message' => 'Team ID is missing.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. POST data or file missing.']);
    exit();
}

$target_dir = "uploads/";
$target_file = $target_dir . $idteam . ".jpg";
$imageFileType = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

$check = getimagesize($_FILES['profile_picture']['tmp_name']);
if (!$check) {
    echo json_encode(['status' => 'error', 'message' => 'File is not an image.']);
    exit();
}

if ($imageFileType !== 'jpg') {
    echo json_encode(['status' => 'error', 'message' => 'Only JPG files are allowed.']);
    exit();
}

if ($_FILES['profile_picture']['size'] > 2000000) {
    echo json_encode(['status' => 'error', 'message' => 'File is too large. Max size is 2MB.']);
    exit();
}

if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
    echo json_encode(['status' => 'success', 'message' => 'Profile picture uploaded successfully!', 'imagePath' => $target_file]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error uploading your file.']);
}
exit();