<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if project_id is set
    if (!isset($_POST['project_id']) || empty($_POST['project_id'])) {
        die("Invalid project ID.");
    }

    $projectId = intval($_POST['project_id']);
    $projectName = $_POST['project_name'] ?? '';
    $projectMembers = $_POST['project_members'] ?? '';
    $description = $_POST['description'] ?? '';
    $externalLink = $_POST['external_link'] ?? '';

    $photoPath = null;
    $filePath = null;

    if (!isset($_POST['project_id']) || empty($_POST['project_id'])) {
        echo "Error: Missing project ID.";
        error_log("Invalid project ID. POST data: " . var_export($_POST, true));
        exit;
    }
    $projectMembers = isset($_POST['project_members']) ? implode(',', $_POST['project_members']) : '';

    // Update the project in the database
    $sql = "UPDATE projects SET project_members = ? WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $projectMembers, $projectId);

    if ($stmt->execute()) {
        echo "Project updated successfully!";
    } else {
        echo "Error updating project: " . $conn->error;
    }

    


    
    
    // Validate project ID
    $stmt = $conn->prepare("SELECT COUNT(*) FROM projects WHERE project_id = ?");
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if (!$exists) {
        die("Invalid project ID.");
    }

    // Handle photo upload
    if (isset($_FILES['photo_path']) && $_FILES['photo_path']['error'] === 0) {
        $targetDir = "uploads/photos/";
        $photoPath = $targetDir . uniqid() . "_" . basename($_FILES['photo_path']['name']);
        if (!move_uploaded_file($_FILES['photo_path']['tmp_name'], $photoPath)) {
            die("Error uploading photo.");
        }
    }

    // Handle ZIP file upload
    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === 0) {
        $targetDir = "uploads/files/";
        $filePath = $targetDir . uniqid() . "_" . basename($_FILES['file_path']['name']);
        if (!move_uploaded_file($_FILES['file_path']['tmp_name'], $filePath)) {
            die("Error uploading ZIP file.");
        }
    }

    // Update database
    $sql = "UPDATE projects SET 
        project_name = ?, 
        project_members = ?, 
        description = ?, 
        external_link = ?";
    
    if ($photoPath) {
        $sql .= ", photo_path = ?";
    }
    if ($filePath) {
        $sql .= ", file_path = ?";
    }
    $sql .= " WHERE project_id = ?";

    $stmt = $conn->prepare($sql);

    if ($photoPath && $filePath) {
        $stmt->bind_param("ssssssi", $projectName, $projectMembers, $description, $externalLink, $photoPath, $filePath, $projectId);
    } elseif ($photoPath) {
        $stmt->bind_param("sssss", $projectName, $projectMembers, $description, $externalLink, $photoPath, $projectId);
    } elseif ($filePath) {
        $stmt->bind_param("sssss", $projectName, $projectMembers, $description, $externalLink, $filePath, $projectId);
    } else {
        $stmt->bind_param("ssssi", $projectName, $projectMembers, $description, $externalLink, $projectId);
    }

    if ($stmt->execute()) {
        header('Location: student.php?status=success');
    } else {
        echo "Error updating project: " . $conn->error;
    }
    $stmt->close();
}
?>
