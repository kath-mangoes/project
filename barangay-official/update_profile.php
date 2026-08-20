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
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $position = $conn->real_escape_string($_POST['position'] ?? '');
    $birthDate = $conn->real_escape_string($_POST['birthDate'] ?? '');
    $startTerm = $conn->real_escape_string($_POST['startTerm'] ?? '');
    $endTerm = $conn->real_escape_string($_POST['endTerm'] ?? '');
    $status = $conn->real_escape_string($_POST['status'] ?? '');
    
    // Update user table
    $sql_user = "UPDATE tbl_user SET 
            email = '$email', 
            mobile = '$mobile'
            WHERE user_id = '$id'";
    
    if ($conn->query($sql_user) === FALSE) {
        echo "Error updating user information: " . $conn->error;
        exit();
    }

    // Update brgyofficer table with all fields
    $sql_officer = "UPDATE tbl_brgyofficer SET 
            first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name', 
            address = '$address',
            mobile = '$mobile',
            position = '$position',
            birthDate = '$birthDate',
            startTerm = '$startTerm',
            endTerm = " . ($endTerm ? "'$endTerm'" : "NULL") . ",
            status = '$status'
            WHERE user_id = '$id'";
    
    if ($conn->query($sql_officer) === FALSE) {
        echo "Error updating officer information: " . $conn->error;
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