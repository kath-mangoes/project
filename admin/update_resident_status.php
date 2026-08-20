<?php
// Start session
session_start();

// Check if user is logged in and has appropriate permissions
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'barangay_official')) {
    header("Location: ../index.php?error=unauthorized");
    exit();
}

// Include database connection
require_once('../connection/config.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID and status from form
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $valid_statuses = ['Active', 'Inactive']; // Add other allowed statuses here
    if (!in_array($status, $valid_statuses)) {
        die("Invalid status provided. Received: $status");
    }
    
    // Update user account status
    $query = "UPDATE tbl_user SET account_status = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $status, $user_id);
    
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        // If status changed to Inactive, also log out the user if they're currently logged in
        if ($status == 'Inactive') {
            $update_login = "UPDATE tbl_user SET is_logged_in = 0, is_logged_in_time = NULL WHERE user_id = ?";
            $stmt_login = mysqli_prepare($conn, $update_login);
            mysqli_stmt_bind_param($stmt_login, "s", $user_id);
            mysqli_stmt_execute($stmt_login);
            mysqli_stmt_close($stmt_login);
        }
        
        // Success
        header("Location: residents.php?success=4");
    } else {
        // Error
        header("Location: residents.php?error=" . urlencode("Failed to update status: " . mysqli_error($conn)));
    }
    
    mysqli_stmt_close($stmt);
} else {
    // If accessed directly without POST request
    header("Location: residents.php?error=invalid_request");
}

// Close connection
mysqli_close($conn);
?>