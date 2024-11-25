<?php
include 'db.php';
$project_id = $_GET['id'];
$sql = "SELECT project_name, username, upload_date, description, external_link FROM projects WHERE project_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
?>
