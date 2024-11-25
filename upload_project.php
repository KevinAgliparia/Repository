<?php
// upload_project.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_new_project'])) {
    $projectName = $_POST['project_name'];
    $projectMembers = $_POST['project_members'];
    $description = $_POST['description'];
    $externalLink = $_POST['external_link'];

    $photoPath = null;
    $filePath = null;

    // Handle photo upload
    if (isset($_FILES['project_photo']) && $_FILES['project_photo']['error'] === 0) {
        $photoDir = "uploads/photos/$userId/";
        if (!is_dir($photoDir)) {
            mkdir($photoDir, 0777, true);
        }
        $photoPath = $photoDir . uniqid() . "_" . basename($_FILES['project_photo']['name']);
        if (!move_uploaded_file($_FILES['project_photo']['tmp_name'], $photoPath)) {
            $_SESSION['upload_status'] = "Error uploading project photo.";
            header("Location: student.php");
            exit;
        }
    }

    // Handle ZIP file upload
    if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] === 0) {
        $fileDir = "uploads/files/$userId/";
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
        $filePath = $fileDir . uniqid() . "_" . basename($_FILES['project_file']['name']);
        if (!move_uploaded_file($_FILES['project_file']['tmp_name'], $filePath)) {
            $_SESSION['upload_status'] = "Error uploading project file.";
            header("Location: student.php");
            exit;
        }
    }

    // Insert project details into the database
    $stmt = $conn->prepare("INSERT INTO projects (user_id, project_name, project_members, external_link, description, photo_path, file_path, upload_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issssss", $userId, $projectName, $projectMembers, $externalLink, $description, $photoPath, $filePath);

    if ($stmt->execute()) {
        $_SESSION['upload_status'] = "Project uploaded successfully!";
    } else {
        $_SESSION['upload_status'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect to avoid form resubmission
    header("Location: student.php");
    exit;
} else {
    header("Location: student.php");
    exit;
}
