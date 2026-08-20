<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../connection/config.php';

$id = $_SESSION['user_id'];

// Update user information if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $conn->real_escape_string($_POST['first_name'] ?? '');
    $middle_name = $conn->real_escape_string($_POST['middle_name'] ?? '');
    $last_name = $conn->real_escape_string($_POST['last_name'] ?? '');
    $suffix = $conn->real_escape_string($_POST['suffix'] ?? '');
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $birthday = $conn->real_escape_string($_POST['birthday'] ?? '');
    $birthplace = $conn->real_escape_string($_POST['birthplace'] ?? '');
    $civilStatus = $conn->real_escape_string($_POST['civilStatus'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $precinctNumber = $conn->real_escape_string($_POST['precinctNumber'] ?? '');
    $residency_tenure = $conn->real_escape_string($_POST['residency_tenure'] ?? '');
    $is_registered_voter = $conn->real_escape_string($_POST['is_registered_voter'] ?? '');
    $bloodType = $conn->real_escape_string($_POST['bloodType'] ?? '');
    $height = $conn->real_escape_string($_POST['height'] ?? '');
    $weight = $conn->real_escape_string($_POST['weight'] ?? '');
    $typeOfID = $conn->real_escape_string($_POST['typeOfID'] ?? '');
    $IDNumber = $conn->real_escape_string($_POST['IDNumber'] ?? '');
    $barangay_number = $conn->real_escape_string($_POST['barangay_number'] ?? '');
    $SSSGSIS_Number = $conn->real_escape_string($_POST['SSSGSIS_Number'] ?? '');
    $TIN_number = $conn->real_escape_string($_POST['TIN_number'] ?? '');
    $is_senior = $conn->real_escape_string($_POST['is_senior'] ?? 'No');
    $is_pwd = $conn->real_escape_string($_POST['is_pwd'] ?? 'No');
    $is_4ps_member = $conn->real_escape_string($_POST['is_4ps_member'] ?? 'No');


    // Update user table
    $sql_user = "UPDATE tbl_user SET 
            email = '$email'
            WHERE user_id = '$id'";
    
    if ($conn->query($sql_user) === FALSE) {
        echo "Error updating user information: " . $conn->error;
        exit();
    }

    // Update residents table with all fields
    $sql_resident = "UPDATE tbl_residents SET 
            first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name',
            suffix = '$suffix',
            mobile = '$mobile',
            email = '$email',
            address = '$address',
            birthday = '$birthday',
            birthplace = '$birthplace',
            civilStatus = '$civilStatus',
            gender = '$gender',
            precinctNumber = '$precinctNumber',
            residency_tenure = '$residency_tenure',
            is_registered_voter = '$is_registered_voter',
            bloodType = '$bloodType',
            height = '$height',
            weight = '$weight',
            typeOfID = '$typeOfID',
            IDNumber = '$IDNumber',
            barangay_number = '$barangay_number',
            SSSGSIS_Number = '$SSSGSIS_Number',
            TIN_number = '$TIN_number',
            is_senior = '$is_senior',
            is_pwd = '$is_pwd',
            is_4ps_member = '$is_4ps_member'
            
            WHERE user_id = '$id'";

    
    if ($conn->query($sql_resident) === FALSE) {
        echo "Error updating resident information: " . $conn->error;
        exit();
    }

    
    // Handle profile picture upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']); // Generate unique filename
        $upload_dir = '../dist/assets/images/user/';
        $upload_file = $upload_dir . $file_name;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($file_tmp, $upload_file)) {
            // Update the database with the new profile image
            $sql = "UPDATE tbl_user SET image = '$file_name' WHERE user_id = '$id'";
            if ($conn->query($sql) === FALSE) {
                echo "Error updating profile image: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    }


    // Handle password change
    if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Verify the old password
            $sql = "SELECT password FROM tbl_user WHERE user_id = '$id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];

                // Check if the old password is correct
                if (password_verify($old_password, $hashed_password)) {
                    // Hash the new password and update it
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE tbl_user SET password = '$new_hashed_password' WHERE user_id = '$id'";
                    if ($conn->query($sql) === FALSE) {
                        echo "Error updating password: " . $conn->error;
                    }
                } else {
                    echo "Old password is incorrect.";
                    exit();
                }
            }
        } else {
            echo "New passwords do not match.";
            exit();
        }
    }

    // Redirect to the profile page with success message
    header("Location: profile-management.php?success=1");
    exit();
}
?>