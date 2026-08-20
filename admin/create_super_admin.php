<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Flag to check if included from another file (like login.php)
$called_from_login = basename($_SERVER['PHP_SELF']) === 'login.php';

// Include database connection
require_once('../connection/config.php');

// --- Super Admin fixed details ---
$fixed_user_id = 9999;
$first_name = "Barangay";
$middle_name = "400";
$last_name = "Admin";
$plain_password = "Admin_258"; // Default password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
$email = "Barangay400Admin@gmail.com";
$mobile = "09123456789";
$address = "Admin Address";
$account_status = "Active";
$role = "admin";
$terms = 1;

try {
    // Begin transaction
    mysqli_begin_transaction($conn);

    // Check if super admin already exists
    $check_sql = "SELECT id FROM tbl_user WHERE user_id = ?";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt_check, "i", $fixed_user_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        // Super Admin exists, update details
        $update_sql = "UPDATE tbl_user SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            password = ?, 
            email = ?, 
            mobile = ?, 
            address = ?, 
            account_status = ?, 
            role = ?, 
            terms = ?
            WHERE user_id = ?";
        $stmt_update = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "sssssssssii", 
            $first_name, $middle_name, $last_name, 
            $hashed_password, $email, $mobile, $address, 
            $account_status, $role, $terms, $fixed_user_id
        );
        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception("Failed to update super admin: " . mysqli_stmt_error($stmt_update));
        }
        mysqli_stmt_close($stmt_update);
    } else {
        // Super Admin does not exist, insert new
        $insert_sql = "INSERT INTO tbl_user 
            (user_id, first_name, middle_name, last_name, password, email, mobile, address, account_status, role, terms)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt_insert, "isssssssssi", 
            $fixed_user_id, $first_name, $middle_name, $last_name, 
            $hashed_password, $email, $mobile, $address, 
            $account_status, $role, $terms
        );
        if (!mysqli_stmt_execute($stmt_insert)) {
            throw new Exception("Failed to insert super admin: " . mysqli_stmt_error($stmt_insert));
        }
        mysqli_stmt_close($stmt_insert);
    }

    mysqli_stmt_close($stmt_check);
    mysqli_commit($conn);

    if (!$called_from_login) {
        header("Location: residents.php?success=super_admin_created");
        exit();
    }

} catch (Exception $e) {
    mysqli_rollback($conn);
    if (!$called_from_login) {
        header("Location: residents.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Close DB connection
mysqli_close($conn);
?>
