<?php
session_start();
include '../connection/config.php';

function logActivity($user_id, $role, $activity) {
    global $conn;
    $sql = "INSERT INTO tbl_audit (user_id, role, details) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $role, $activity);
    $stmt->execute();
}

if (isset($_SESSION['user_id'])) {
    // Log the logout activity
    logActivity($_SESSION['user_id'], $_SESSION['role'], 'Logged out');

    // Update is_logged_in status
    $updateStatus = "UPDATE tbl_user SET is_logged_in = 0, is_logged_in_time = NULL WHERE user_id = ?";
    $stmt = $conn->prepare($updateStatus);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    // Clear remember me cookies
    if (isset($_COOKIE['remember_user'])) {
        setcookie('remember_user', '', time() - 3600, '/');
    }
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    // Unset and destroy session
    $_SESSION = array();
    session_destroy();
}

// Redirect to login page
header("Location: ../login.php");
exit();
?>