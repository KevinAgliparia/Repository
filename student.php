<?php
// student.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$successMessage = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_new_project'])) {
        include 'upload_project.php'; // Separate logic for uploading projects
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        include 'edit_project.php'; // Logic for editing projects
    } elseif (isset($_POST['delete_project_id'])) {
        include 'delete_project.php'; // Logic for deleting projects
    } else {
        die("Invalid operation.");
    }
}

// Retrieve and clear session upload status
if (isset($_SESSION['upload_status'])) {
    $successMessage = $_SESSION['upload_status'];
    unset($_SESSION['upload_status']);
}

// Fetch uploaded projects
$sql = "SELECT * FROM projects WHERE user_id='$userId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logout-section">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            <h1>ZDSPGC PROJECT REPOSITORY</h1>
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            
        </div>

        <!-- Projects Section -->
        <div class="projects-section">
            <div class="projects-header">
                <h3>Your Projects</h3>
                <div class="project-actions">
                <input type="text" id="search-bar" placeholder="Search projects..." oninput="searchProjects()">
                    <button class="open-modal-btn" onclick="openModal()">
                        <i class="fas fa-upload"></i> Upload New Project
                    </button>
                </div>
            </div>

            <div class="project-cards-container">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="project-card" onclick="openProjectModal(<?php echo $row['project_id']; ?>)">
                        <div class="project-card-header">
                            <h4 class="project-title"><?php echo $row['project_name']; ?></h4>
                            <img src="<?php echo htmlspecialchars($row['photo_path']); ?>" 
                                alt="Project Photo" 
                                style="width: 100%; height: auto; border-radius: 8px; margin-top: 10px;">
                            <p><strong>Members:</strong></p>
                                <ul>
                                    <?php 
                                    $members = explode(',', $row['project_members']); // Split members by commas
                                    foreach ($members as $member): 
                                    ?>
                                        <li><?php echo htmlspecialchars(trim($member)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <p><strong>Uploaded On:</strong> 
                                <?php 
                                    echo date("F j, Y, g:i A", strtotime($row['upload_date'])); 
                                ?>
                            </p>
                            
                        </div>
                        <div class="project-card-body">
                            
                            <a href="<?php echo $row['file_path']; ?>" download 
                                class="action-btn download-btn"
                                onclick="return confirmDownload('<?php echo $row['file_path']; ?>');">
                                    <i class="fas fa-download"></i> Download
                            </a>
                            <p>
                                <a href="<?php echo $row['external_link']; ?>" 
                                    target="_blank" 
                                    class="btn-link">
                                    View Project
                                </a>
                            </p>
                            <form action="delete_project.php" method="post" onsubmit="return confirmDelete();">
                                <input type="hidden" name="delete_project_id" value="<?php echo $row['project_id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>

                        </div>
                    </div>

                    <!-- Project Modal -->
                    <div id="project-modal-<?php echo $row['project_id']; ?>" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeProjectModal(<?php echo $row['project_id']; ?>)">&times;</span>
                            <!-- Display Section -->
                             
                            <div id="view-section-<?php echo $row['project_id']; ?>">
                            <h2>Project Details</h2>
                                <h3>Project Name: <?php echo htmlspecialchars($row['project_name']); ?></h3>
                                <h3>Project Photo:</h3>
                                <img src="<?php echo htmlspecialchars($row['photo_path']); ?>" alt="Project Photo" style="width:100%; height:auto; border-radius:8px; margin-bottom:10px;">
                                <p><strong>Members:</strong></p>
                                <ul>
                                    <?php 
                                    $members = explode(',', $row['project_members']); // Split members by commas
                                    foreach ($members as $member): 
                                    ?>
                                        <li><?php echo htmlspecialchars(trim($member)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                                <p><strong>External Link:</strong> 
                                    <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($row['external_link']); ?></a>
                                </p>
                                <p><strong>Project File:</strong> 
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" 
                                    download 
                                    onclick="return confirmDownload('<?php echo htmlspecialchars($row['file_path']); ?>');">
                                    <?php echo basename($row['file_path']); ?> <!-- Display the actual file name -->
                                    </a>
                                </p>
                                <p><strong>Uploaded On:</strong> 
                                    <?php 
                                        echo date("F j, Y, g:i A", strtotime($row['upload_date'])); 
                                    ?>
                                </p>

                                <button class="action-btn edit-btn" onclick="showEditForm(<?php echo $row['project_id']; ?>)">Edit</button>
                            </div>
                        
                        
                            <!-- Edit Section -->
                            <div id="edit-section-<?php echo $row['project_id']; ?>" style="display: none;">
                                
                                <form action="edit_project.php" method="post" enctype="multipart/form-data">
                                <h3>Edit Project</h3>
                                    <input type="hidden" name="project_id" value="<?php echo $row['project_id']; ?>">
                                    <input type="hidden" name="project_members_combined" value=""> <!-- Hidden combined members field -->
                                    
                                    <label for="project_name">Project Name:</label>
                                    <input type="text" name="project_name" value="<?php echo htmlspecialchars($row['project_name']); ?>" required>

                                    <label for="project_members">Project Members:</label>
                                    <div id="members-container-<?php echo $row['project_id']; ?>" class="members-container">
                                        <?php 
                                        $members = explode(',', $row['project_members']);
                                        foreach ($members as $member): 
                                        ?>
                                            <div class="member-input">
                                                <input type="text" name="project_members[]" value="<?php echo htmlspecialchars(trim($member)); ?>" required />
                                                <button type="button" onclick="removeMember(this)">Remove</button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" id="add-member-btn- <?php echo $row['project_id']; ?>" 
                                            class="add-member-btn btn btn-primary" 
                                            data-container-id="members-container-<?php echo $row['project_id']; ?>">
                                        Add Member
                                    </button>


                                    <label for="description">Description:</label>
                                    <textarea name="description" rows="5" class="modal-textarea" required><?php echo htmlspecialchars($row['description']); ?></textarea>

                                    <label for="external_link">External Link:</label>
                                    <input type="url" name="external_link" value="<?php echo htmlspecialchars($row['external_link']); ?>" required>

                                    <label for="photo_path">Project Photo:</label>
                                    <input type="file" name="photo_path" accept="image/*">

                                    <label for="file_path">Upload New ZIP File:</label>
                                    <input type="file" name="file_path" accept=".zip">

                                    <button type="submit" class="action-btn save-btn">Save Changes</button>
                                    <button type="button" class="action-btn cancel-btn" onclick="hideEditForm(<?php echo $row['project_id']; ?>)">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

       

    <!-- Upload Modal -->
    <div id="upload-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3>Upload New Project</h3>
            <form action="upload_project.php" method="post" enctype="multipart/form-data">
            
                <input type="hidden" name="upload_new_project" value="true">

                <label for="project_name">Project Name:</label>
                <input type="text" name="project_name" required>

                <label for="project_members">Project Members:</label>
                <div id="members-container">
                    <div class="member-input">
                        <input type="text" name="project_members[]" placeholder="Enter member name" required />
                        <button type="button" onclick="removeMember(this)">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-member-btn">Add Member</button>


                <label for="external_link">Project Link:</label>
                <input type="url" name="external_link" placeholder="https://example.com" required>

                <label for="description">Description:</label>
                <textarea name="description" rows="10" class="modal-textarea" required></textarea>

                <label for="project_photo">Project Photo:</label>
                <input type="file" name="project_photo" accept="image/*" required>

                <label for="project_file">Upload Project (ZIP only):</label>
                <input type="file" name="project_file" accept=".zip" required>

                <button type="submit" class="action-btn modal-submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit
                </button>
            </form>

        </div>
    </div>

    <script src="student.js"></script>
</body>
</html>
