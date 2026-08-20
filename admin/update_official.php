<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    // Get brgyOfficer_id and user_id from form
    $brgyOfficer_id = $_POST['brgyOfficer_id'];
    $user_id = $_POST['user_id'];
    
    // Sanitize input data
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Get the new term fields
    $startTerm = mysqli_real_escape_string($conn, $_POST['startTerm']);
    $endTerm = mysqli_real_escape_string($conn, $_POST['endTerm']);
    
    // Get the position field
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    
    
    // Begin transaction to ensure both tables are updated or none
    mysqli_begin_transaction($conn);
    
    try {
        // Update tbl_brgyofficer with position and status included
        $update_officer_query = "UPDATE tbl_brgyofficer SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            birthday = ?, 
            mobile = ?, 
            address = ?,
            startTerm = ?,
            endTerm = ?,
            position = ?
        WHERE brgyOfficer_id = ?";
        
        $stmt_officer = mysqli_prepare($conn, $update_officer_query);
        mysqli_stmt_bind_param($stmt_officer, "sssssssssi", 
            $first_name, 
            $middle_name, 
            $last_name, 
            $birthday, 
            $mobile, 
            $address,
            $startTerm,
            $endTerm,
            $position,
            $brgyOfficer_id
        );
        
        $officer_update_result = mysqli_stmt_execute($stmt_officer);
        
        if (!$officer_update_result) {
            throw new Exception("Failed to update official information: " . mysqli_stmt_error($stmt_officer));
        }
        
        // Update corresponding user information in tbl_user
        $update_user_query = "UPDATE tbl_user SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            mobile = ?, 
            address = ? 
        WHERE user_id = ?";
        
        $stmt_user = mysqli_prepare($conn, $update_user_query);
        mysqli_stmt_bind_param($stmt_user, "sssssi", 
            $first_name, 
            $middle_name, 
            $last_name, 
            $mobile, 
            $address, 
            $user_id
        );
        
        $user_update_result = mysqli_stmt_execute($stmt_user);
        
        if (!$user_update_result) {
            throw new Exception("Failed to update user information: " . mysqli_stmt_error($stmt_user));
        }
        
        // If both updates were successful, commit the transaction
        mysqli_commit($conn);
        
        // Close prepared statements
        mysqli_stmt_close($stmt_officer);
        mysqli_stmt_close($stmt_user);
        
        // Redirect with success message
        header("Location: officials.php?success=2");
        exit();
        
    } catch (Exception $e) {
        // If any errors occurred, roll back the transaction
        mysqli_rollback($conn);
        
        // Close prepared statements if they exist
        if (isset($stmt_officer)) mysqli_stmt_close($stmt_officer);
        if (isset($stmt_user)) mysqli_stmt_close($stmt_user);
        
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