<?php
session_start();
include '../connection/config.php'; // Include your database connection

// Check if user is admin or Barangay Secretary
function canManageEvents($conn, $user_id) {
    // Check if user is admin
    $stmt = $conn->prepare("SELECT role FROM tbl_user WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['role'] === 'admin') {
            return true;
        }
    }
    
    // Check if user is Barangay Secretary
    $position = getUserPosition($conn, $user_id);
    if ($position === 'Barangay Secretary') {
        return true;
    }
    
    return false;
}

// Get user's position if they are a barangay official
function getUserPosition($conn, $user_id) {
    $stmt = $conn->prepare("SELECT position FROM tbl_brgyofficer WHERE user_id = ? AND status = 'Active'");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['position'];
    }
    return null;
}

// Redirect with error if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: events.php?error=login_required");
    exit();
}

// Handle adding new event
if (isset($_POST['add_event']) && canManageEvents($conn, $_SESSION['user_id'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $brgyOfficer_id = 0; // Get from database based on user_id
    $res_id = 0; // Get from database based on user_id
    
    // Get brgyOfficer_id if user is a barangay officer
    $stmt = $conn->prepare("SELECT brgyOfficer_id FROM tbl_brgyofficer WHERE user_id = ? AND status = 'Active'");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $brgyOfficer_id = $result->fetch_assoc()['brgyOfficer_id'];
    }
    
    // Get res_id if user is a resident
    $stmt = $conn->prepare("SELECT res_id FROM tbl_residents WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $res_id = $result->fetch_assoc()['res_id'];
    }
    
    // Upload image if provided
    $image = '';
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../dist/assets/images/uploads/events/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['event_image']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target_path)) {
            $image = $file_name;
        }
    }
    
    // Insert event into database
    $stmt = $conn->prepare("INSERT INTO tbl_event (brgyOfficer_id, res_id, user_id, title, description, image, dateCreated, lastEdited) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iissss", $brgyOfficer_id, $res_id, $_SESSION['user_id'], $title, $description, $image);
    $result = $stmt->execute();
    
    if ($result) {
        header("Location: events.php?success=1");
    } else {
        header("Location: events.php?error=1");
    }
    
    exit();
}

?>