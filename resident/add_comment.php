<?php
session_start();
include '../connection/config.php'; // Include your database connection

// Check if user is banned
function isUserBanned($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM tbl_banned_users WHERE user_id = ? AND status = 'Active'");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
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

// Get user's role
function getUserRole($conn, $user_id) {
    // First check if the user is an official
    $stmt = $conn->prepare("SELECT user_id FROM tbl_brgyofficer WHERE user_id = ? AND status = 'Active'");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return 'official';
    }
    
    // Check if the user is a resident
    $stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_id = ? AND account_status = 'Active'");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return 'resident';
    }
    
    // Default role if not found in any table
    return 'guest';
}

// Redirect with error if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: events.php?error=login_required");
    exit();
}

// Handle adding comment
if (isset($_POST['add_comment']) && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    
    // Check if user is banned
    if (isUserBanned($conn, $user_id)) {
        header("Location: events.php?error=user_banned");
        exit();
    }
    
    // Determine role of the user
    $user_role = getUserRole($conn, $user_id);
    
    // Only allow officials and residents to comment
    if ($user_role == 'official' || $user_role == 'resident') {
        // Get user position - for officials, get from database; for residents, set as "Resident"
        if ($user_role == 'official') {
            $position = getUserPosition($conn, $user_id);
        } else {
            $position = "Resident";
        }
        
        // Set is_official to 1 for both officials and residents as requested
        $is_official = 1;
        
        // Insert comment into database
        $stmt = $conn->prepare("INSERT INTO tbl_event_comments (event_id, user_id, comment, position, is_official, comment_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssi", $event_id, $user_id, $comment, $position, $is_official);
        $result = $stmt->execute();
        
        if ($result) {
            header("Location: events.php?success=1");
        } else {
            header("Location: events.php?error=db_error");
        }
    } else {
        header("Location: events.php?error=unauthorized_role");
    }
    
    exit();
}

// Handle deleting comment (optional functionality)
if (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if the user is the comment owner or an admin
    $stmt = $conn->prepare("SELECT user_id FROM tbl_event_comments WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $comment = $result->fetch_assoc();
        
        // Check if user is the comment owner or an admin
        $is_admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin';
        
        if ($comment['user_id'] == $user_id || $is_admin) {
            // Delete the comment
            $delete_stmt = $conn->prepare("DELETE FROM tbl_event_comments WHERE comment_id = ?");
            $delete_stmt->bind_param("i", $comment_id);
            $delete_result = $delete_stmt->execute();
            
            if ($delete_result) {
                header("Location: events.php?success=2");
            } else {
                header("Location: events.php?error=2");
            }
        } else {
            header("Location: events.php?error=2");
        }
    } else {
        header("Location: events.php?error=2");
    }
    
    exit();
}
?>