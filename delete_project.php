<?php
// delete_project.php

include 'db.php';

if (isset($_POST['delete_project_id'])) {
    $deleteProjectId = $_POST['delete_project_id'];

    // Fetch file and photo paths for deletion
    $fetchPathsSql = "SELECT photo_path, file_path FROM projects WHERE project_id = $deleteProjectId";
    $result = $conn->query($fetchPathsSql);

    if ($result->num_rows > 0) {
        $paths = $result->fetch_assoc();
        if (!empty($paths['photo_path']) && file_exists($paths['photo_path'])) {
            unlink($paths['photo_path']); // Delete photo
        }
        if (!empty($paths['file_path']) && file_exists($paths['file_path'])) {
            unlink($paths['file_path']); // Delete file
        }
    }

    // Delete project record
    $stmt = $conn->prepare("DELETE FROM projects WHERE project_id = ?");
    $stmt->bind_param("i", $deleteProjectId);
    if ($stmt->execute()) {
        header('Location: student.php?delete_status=success');
    } else {
        echo "Error deleting project: " . $conn->error;
    }
    $stmt->close();
}
// Retrieve and clear the session delete status
if (isset($_SESSION['delete_status'])) {
    $deleteMessage = $_SESSION['delete_status'];
    unset($_SESSION['delete_status']);
}


