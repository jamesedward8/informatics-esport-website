<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$mysqli = new mysqli("localhost", "root", "", "esport");

header('Content-Type: application/json'); // Set JSON header for all responses

if ($mysqli->connect_errno) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

$role = isset($_POST['role']) ? $_POST['role'] : null; // Get user role from session
$idteam = isset($_POST['idteam']) ? $_POST['idteam'] : null; // Get team ID from POST data

// Debugging: Check presence of required variables
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

// Continue if all checks passed
$target_dir = "uploads/";
$target_file = $target_dir . $idteam . ".jpg"; // Save as {idteam}.jpg
$imageFileType = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

// Validate the image file
$check = getimagesize($_FILES['profile_picture']['tmp_name']);
if (!$check) {
    echo json_encode(['status' => 'error', 'message' => 'File is not an image.']);
    exit();
}

// Restrict to only .jpg files
if ($imageFileType !== 'jpg') {
    echo json_encode(['status' => 'error', 'message' => 'Only JPG files are allowed.']);
    exit();
}

// Limit file size to 2MB
if ($_FILES['profile_picture']['size'] > 2000000) {
    echo json_encode(['status' => 'error', 'message' => 'File is too large. Max size is 2MB.']);
    exit();
}

// Attempt file upload, overwriting if file exists
if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
    echo json_encode(['status' => 'success', 'message' => 'Profile picture uploaded successfully!', 'imagePath' => $target_file]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error uploading your file.']);
}
exit();