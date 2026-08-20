<?php
// Start session
session_start();

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    
    // Validate status value
    if ($status != 'Verified' && $status != 'Inactive') {
        header("Location: residents.php?error=invalid_status");
        exit();
    }

    // Check if user exists in tbl_user
    $check_user_query = "SELECT user_id FROM tbl_user WHERE user_id = ?";
    $stmt_check = mysqli_prepare($conn, $check_user_query);
    mysqli_stmt_bind_param($stmt_check, "s", $user_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        header("Location: residents.php?error=user_not_found");
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // Begin transaction to ensure both updates happen together
    mysqli_begin_transaction($conn);

    try {
        // Update user account status
        $query = "UPDATE tbl_user SET account_status = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $status, $user_id);
        $result_user = mysqli_stmt_execute($stmt);

        if (!$result_user) {
            throw new Exception("Failed to update tbl_user: " . mysqli_stmt_error($stmt));
        }

        // Also update status in tbl_brgyofficer table
        $query_brgy = "UPDATE tbl_brgyofficer SET status = ? WHERE user_id = ?";
        $stmt_brgy = mysqli_prepare($conn, $query_brgy);
        mysqli_stmt_bind_param($stmt_brgy, "ss", $status, $user_id);
        $result_brgy = mysqli_stmt_execute($stmt_brgy);

        if (!$result_brgy) {
            throw new Exception("Failed to update tbl_brgyofficer: " . mysqli_stmt_error($stmt_brgy));
        }

        // If status changed to Inactive, also log out the user if they're currently logged in
        if ($status == 'Inactive') {
            $update_login = "UPDATE tbl_user SET is_logged_in = 0, is_logged_in_time = NULL WHERE user_id = ?";
            $stmt_login = mysqli_prepare($conn, $update_login);
            mysqli_stmt_bind_param($stmt_login, "s", $user_id);
            mysqli_stmt_execute($stmt_login);
            mysqli_stmt_close($stmt_login);
        }

        // Commit the transaction if both updates succeed
        mysqli_commit($conn);

        // Success
        header("Location: officials.php?success=4");
        exit();
    } catch (Exception $e) {
        // If any error occurs, roll back the transaction
        mysqli_rollback($conn);
        header("Location: officials.php?error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        // Close prepared statements
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt_brgy);
    }

} else {
    // If accessed directly without POST request
    header("Location: officials.php?error=invalid_request");
}

// Close connection
mysqli_close($conn);
?>
