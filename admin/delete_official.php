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
    // Get resident ID and user ID from form
    $brgyOfficer_id = mysqli_real_escape_string($conn, $_POST['brgyOfficer_id']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    // Begin transaction to ensure data consistency
    mysqli_begin_transaction($conn);
    
    try {
      
        // Delete from tbl_residents first
        $query_resident = "DELETE FROM tbl_brgyofficer WHERE brgyOfficer_id = ? AND user_id = ?";
        $stmt_resident = mysqli_prepare($conn, $query_resident);
        mysqli_stmt_bind_param($stmt_resident, "is", $brgyOfficer_id, $user_id);
        $result_resident = mysqli_stmt_execute($stmt_resident);
        
        if (!$result_resident) {
            throw new Exception("Failed to delete resident record: " . mysqli_stmt_error($stmt_resident));
        }
        
        // Then delete from tbl_user
        $query_user = "DELETE FROM tbl_user WHERE user_id = ?";
        $stmt_user = mysqli_prepare($conn, $query_user);
        mysqli_stmt_bind_param($stmt_user, "s", $user_id);
        $result_user = mysqli_stmt_execute($stmt_user);
        
        if (!$result_user) {
            throw new Exception("Failed to delete user account: " . mysqli_stmt_error($stmt_user));
        }
        
        // If both deletions were successful, commit the transaction
        mysqli_commit($conn);
        
        // Close prepared statements
        mysqli_stmt_close($stmt_resident);
        mysqli_stmt_close($stmt_user);
        
        // Redirect with success message
        header("Location: officials.php?success=3");
        exit();
        
    } catch (Exception $e) {
        // If any errors occurred, roll back the transaction
        mysqli_rollback($conn);
        
        // Redirect with error message
        header("Location: officials.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If accessed directly without POST request
    header("Location: officials.php?error=invalid_request");
    exit();
}

// Close connection
mysqli_close($conn);
?>