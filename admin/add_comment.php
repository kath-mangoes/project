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

// Get user's role if they are active
function getUserRole($conn, $user_id) {
    $stmt = $conn->prepare("SELECT role FROM tbl_user WHERE user_id = ? AND account_status = 'Active'");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['role'];
    }

    return null;
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
    } else {
        // Get user position if they are an official
        $position = getUserRole($conn, $user_id);
        $is_official = !empty($position) ? 1 : 0;
        
        // Insert comment into database
        $stmt = $conn->prepare("INSERT INTO tbl_event_comments (event_id, user_id, comment, position, is_official, comment_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssi", $event_id, $user_id, $comment, $position, $is_official);
        $result = $stmt->execute();
        
        if ($result) {
            header("Location: events.php?success=3");
        } else {
            header("Location: events.php?error=3");
        }
    }
    
    exit();
}
?>