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

// Redirect with error if user not logged in or not admin/secretary
if (!isset($_SESSION['user_id']) || !canManageEvents($conn, $_SESSION['user_id'])) {
    header("Location: events.php?error=permission_denied");
    exit();
}

// Handle ban/unban user request
if (isset($_POST['ban_action']) && isset($_POST['user_id_to_ban'])) {
    $user_id_to_ban = $_POST['user_id_to_ban'];
    $action = $_POST['ban_action'];
    
    if ($action === 'ban') {
        $reason = $_POST['ban_reason'] ?? '';
        
        // Ban the user
        $stmt = $conn->prepare("INSERT INTO tbl_banned_users (user_id, banned_by, reason, status) VALUES (?, ?, ?, 'Active')");
        $stmt->bind_param("sss", $user_id_to_ban, $_SESSION['user_id'], $reason);
        $result = $stmt->execute();
        
        if ($result) {
            header("Location: events.php?success=4");
        } else {
            header("Location: events.php?error=ban_failed");
        }
    } elseif ($action === 'unban') {
        // Unban the user (update status to Lifted)
        $stmt = $conn->prepare("UPDATE tbl_banned_users SET status = 'Lifted', lift_date = NOW() WHERE user_id = ? AND status = 'Active'");
        $stmt->bind_param("s", $user_id_to_ban);
        $result = $stmt->execute();
        
        if ($result) {
            header("Location: events.php?success=4");
        } else {
            header("Location: events.php?error=4");
        }
    }
    
    exit();
}
?>