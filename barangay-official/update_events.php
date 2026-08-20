<?php 
session_start();
include '../connection/config.php';

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

// Handle editing event
if (isset($_POST['edit_event']) && canManageEvents($conn, $_SESSION['user_id'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Check if a new image was uploaded
    $image_query = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../dist/assets/images/uploads/events/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['event_image']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target_path)) {
            // Get the old image to delete it
            $stmt = $conn->prepare("SELECT image FROM tbl_event WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $old_image = $result->fetch_assoc()['image'];
                if (!empty($old_image) && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }
            }
            
            $image_query = ", image = ?";
        }
    }
    
    // Update event in database
    if (!empty($image_query)) {
        $query = "UPDATE tbl_event SET title = ?, description = ?, lastEdited = NOW() $image_query WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $title, $description, $file_name, $event_id);
    } else {
        $query = "UPDATE tbl_event SET title = ?, description = ?, lastEdited = NOW() WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $description, $event_id);
    }
    
    $result = $stmt->execute();
    
    if ($result) {
        header("Location: events.php?success=2");
    } else {
        header("Location: events.php?error=2");
    }
    
    exit();
}
?>