<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Retrieve all uploaded projects
$sql = "SELECT projects.project_id, projects.project_name, projects.upload_date, projects.file_path, projects.photo_path, projects.description, projects.external_link, projects.project_members, users.username 
        FROM projects 
        INNER JOIN users ON projects.user_id = users.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>Welcome to Admin Dashboard</h1>
        <p>Hello, <?php echo $_SESSION['username']; ?>!</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" id="search" placeholder="Search projects..." onkeyup="filterProjects()">
        <h3>Projects List:</h3>
    </div>
    <h3>Projects List:</h3>
    <!-- Project Cards -->
    <div class="project-cards-container" id="project-container">
        
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div 
            class="project-card" 
            data-name="<?php echo htmlspecialchars($row['project_name'], ENT_QUOTES); ?>" 
            data-user="<?php echo htmlspecialchars($row['username'], ENT_QUOTES); ?>"
            onclick="showDetails(
                '<?php echo htmlspecialchars($row['project_name'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($row['username'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($row['upload_date'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($row['photo_path'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($row['external_link'], ENT_QUOTES); ?>'
            )">
            <h4><?php echo htmlspecialchars($row['project_name']); ?></h4>
            <p>Uploaded by: <?php echo htmlspecialchars($row['username']); ?></p>
            <p>Members: <?php echo htmlspecialchars($row['project_members']); ?></p>
            <p>Upload Date: <?php echo htmlspecialchars($row['upload_date']); ?></p>
            <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank">Project Link</a>
            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a>
        </div>
    <?php } ?>
</div>


    <!-- Modal for Project Details -->
    <div id="project-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3 id="modal-project-name"></h3>
            <img id="modal-photo" src="" alt="Project Photo" class="project-photo">
            <div id="project-details">
                <p><strong>Uploaded by:</strong> <span id="modal-username"></span></p>
                <p><strong>Upload Date:</strong> <span id="modal-upload-date"></span></p>
                <p><strong>Description:</strong> <span id="modal-overview"></span></p>
                <a id="modal-url" href="#" target="_blank" class="project-link">Visit Project</a>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>
