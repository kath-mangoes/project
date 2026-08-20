<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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
    // Get user_id and status from form
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];  // Correctly access the status
    
    // Sanitize input data
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $status = mysqli_real_escape_string($conn, $status);

    // Begin transaction to ensure both tables are updated or none
    mysqli_begin_transaction($conn);
    
    try {
        // Update the user account status in tbl_user
        $update_user_query = "UPDATE tbl_user SET account_status = ? WHERE user_id = ?";
        
        // Prepare the query with string format 'ss' for user_id and status
        $stmt_user = mysqli_prepare($conn, $update_user_query);
        mysqli_stmt_bind_param($stmt_user, "ss", $status, $user_id);
        
        // Execute the query
        $user_update_result = mysqli_stmt_execute($stmt_user);
        
        if (!$user_update_result) {
            throw new Exception("Failed to update user account status: " . mysqli_stmt_error($stmt_user));
        }
        
        // Optionally, update officer status in tbl_brgyofficer if needed
        $update_officer_query = "UPDATE tbl_brgyofficer SET status = ? WHERE user_id = ?";
        
        // Prepare the query with string format 'ss' for user_id and status
        $stmt_officer = mysqli_prepare($conn, $update_officer_query);
        mysqli_stmt_bind_param($stmt_officer, "ss", $status, $user_id);
        
        // Execute the query
        $officer_update_result = mysqli_stmt_execute($stmt_officer);
        
        if (!$officer_update_result) {
            throw new Exception("Failed to update officer status: " . mysqli_stmt_error($stmt_officer));
        }

        // If both updates were successful, commit the transaction
        mysqli_commit($conn);
        
        // Close prepared statements
        mysqli_stmt_close($stmt_user);
        mysqli_stmt_close($stmt_officer);
        
        // Redirect with success message
        header("Location: officials.php?success=1");
        exit();
        
    } catch (Exception $e) {
        // If any errors occurred, roll back the transaction
        mysqli_rollback($conn);
        
        // Close prepared statements if they exist
        if (isset($stmt_user)) mysqli_stmt_close($stmt_user);
        if (isset($stmt_officer)) mysqli_stmt_close($stmt_officer);
        
        // Redirect with error message
        header("Location: officials.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If accessed directly without POST request
    header("Location: officials.php?error=invalid_request");
    exit();
}
?>
