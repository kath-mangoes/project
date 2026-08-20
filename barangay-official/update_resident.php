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
    $res_id = $_POST['res_id'];
    $user_id = $_POST['user_id'];
    
    // Sanitize input data
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $birthplace = mysqli_real_escape_string($conn, $_POST['birthplace']);
    $civilStatus = mysqli_real_escape_string($conn, $_POST['civilStatus']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $precinctNumber = mysqli_real_escape_string($conn, $_POST['precinctNumber']);
    $residentStatus = mysqli_real_escape_string($conn, $_POST['residentStatus']);
    $voterStatus = mysqli_real_escape_string($conn, $_POST['voterStatus']);
    $bloodType = mysqli_real_escape_string($conn, $_POST['bloodType']);
    $height = empty($_POST['height']) ? NULL : mysqli_real_escape_string($conn, $_POST['height']);
    $weight = empty($_POST['weight']) ? NULL : mysqli_real_escape_string($conn, $_POST['weight']);
    $typeOfID = mysqli_real_escape_string($conn, $_POST['typeOfID']);
    $IDNumber = mysqli_real_escape_string($conn, $_POST['IDNumber']);
    $SSSGSIS_Number = mysqli_real_escape_string($conn, $_POST['SSSGSIS_Number']);
    $TIN_number = mysqli_real_escape_string($conn, $_POST['TIN_number']);
    $is_senior = mysqli_real_escape_string($conn, $_POST['is_senior']);
    $is_pwd = mysqli_real_escape_string($conn, $_POST['is_pwd']);
    $is_4ps_member = mysqli_real_escape_string($conn, $_POST['is_4ps_member']);
    
    // Begin transaction to ensure both tables are updated or none
    mysqli_begin_transaction($conn);
    
    try {
        // Update tbl_residents
        $update_resident_query = "UPDATE tbl_residents SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            birthday = ?, 
            birthplace = ?, 
            civilStatus = ?, 
            mobile = ?, 
            gender = ?, 
            address = ?, 
            precinctNumber = ?, 
            residentStatus = ?, 
            voterStatus = ?, 
            bloodType = ?, 
            height = ?, 
            weight = ?, 
            typeOfID = ?, 
            IDNumber = ?, 
            SSSGSIS_Number = ?, 
            TIN_number = ?, 
            is_senior = ?, 
            is_pwd = ?, 
            is_4ps_member = ? 
        WHERE res_id = ?";
        
        $stmt_resident = mysqli_prepare($conn, $update_resident_query);
        mysqli_stmt_bind_param($stmt_resident, "ssssssssssssssssssssssi", 
            $first_name, 
            $middle_name, 
            $last_name, 
            $birthday, 
            $birthplace, 
            $civilStatus, 
            $mobile, 
            $gender, 
            $address, 
            $precinctNumber, 
            $residentStatus, 
            $voterStatus, 
            $bloodType, 
            $height, 
            $weight, 
            $typeOfID, 
            $IDNumber, 
            $SSSGSIS_Number, 
            $TIN_number, 
            $is_senior, 
            $is_pwd, 
            $is_4ps_member, 
            $res_id
        );
        
        $resident_update_result = mysqli_stmt_execute($stmt_resident);
        
        if (!$resident_update_result) {
            throw new Exception("Failed to update resident information: " . mysqli_stmt_error($stmt_resident));
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
        mysqli_stmt_bind_param($stmt_user, "ssssss", 
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
        mysqli_stmt_close($stmt_resident);
        mysqli_stmt_close($stmt_user);
        
        // Redirect with success message
        header("Location: residents.php?success=2");
        exit();
        
    } catch (Exception $e) {
        // If any errors occurred, roll back the transaction
        mysqli_rollback($conn);
        
        // Close prepared statements if they exist
        if (isset($stmt_resident)) mysqli_stmt_close($stmt_resident);
        if (isset($stmt_user)) mysqli_stmt_close($stmt_user);
        
        // Redirect with error message
        header("Location: residents.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If accessed directly without POST request
    header("Location: residents.php?error=invalid_request");
    exit();
}
?>