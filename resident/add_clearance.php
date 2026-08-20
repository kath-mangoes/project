<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../connection/config.php';

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Get resident information based on user_id
$query = "SELECT res_id FROM tbl_residents WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $resident = $result->fetch_assoc();
    $res_id = $resident['res_id'];
} else {
    // Redirect if resident profile not found
    header("Location: index.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $clearanceType = $_POST['clearance_type'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $address = $_POST['address'];
    $purpose = $_POST['purpose'];
    $registeredVoter = ($_POST['registered_voter'] === 'Yes') ? 1 : 0;

    // Set the timezone to Manila, Philippines
    date_default_timezone_set('Asia/Manila');

    $status = "To Be Approved"; // Default status

    // Combine name fields
    $name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;

    $document_path = ""; // Will store comma-separated file names
    $uploaded_files = [];

    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/clearance-documents/";

        // Create upload directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
    
        foreach ($_FILES['document_path']['name'] as $key => $filename) {
            if ($_FILES['document_path']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['document_path']['tmp_name'][$key];
                
                // Ensure unique filename
                $unique_name = time() . '_' . uniqid() . '_' . basename($filename);
                $target_file = $upload_dir . $unique_name;
    
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_files[] = $unique_name;
                }
            }
        }
    
        if (!empty($uploaded_files)) {
            $document_path = implode(",", $uploaded_files); // Save as CSV string
        } else {
            $error_message = "No files were successfully uploaded.";
        }
    }

    $insertQuery = "INSERT INTO tbl_clearance (res_id, user_id, clearanceType, name, address, purpose, 
               registeredVoter, document_path, status) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "iissssiss",
        $res_id,
        $user_id,
        $clearanceType,
        $name,
        $address,
        $purpose,
        $registeredVoter,
        $document_path,
        $status
    );
    

    if ($insertStmt->execute()) {
        // Redirect with success parameter for SweetAlert
        header("Location: barangay-clearance.php?success=1");
        exit();
    } else {
        // Redirect with error parameter
        header("Location: barangay-clearance.php?error=1&message=" . urlencode($conn->error));
        exit();
    }

    $insertStmt->close();
}
?>
