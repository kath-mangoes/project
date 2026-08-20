<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../connection/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$position_query = "SELECT bo.brgyOfficer_id, bo.position, bo.first_name, bo.middle_name, bo.last_name 
                  FROM tbl_brgyofficer bo 
                  WHERE bo.user_id = ? AND bo.status = 'Active'";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("s", $user_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();
// Get the officer ID from the tbl_brgyofficer table
$user_id = $_SESSION['user_id'];
$brgyOfficer_id = null;

$officer_fullname = "";

if ($position_result->num_rows > 0) {
    $officer_data = $position_result->fetch_assoc();
    $position = $officer_data['position'];
    $brgyOfficer_id = $officer_data['brgyOfficer_id'];
    
    // Get the full name of the officer for the "processedBy" field
    $officer_fullname = $officer_data['first_name'] . ' ' . $officer_data['middle_name'] . ' ' . $officer_data['last_name'];
    

}
$position_stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $res_id = $_POST['resident_id'];
    $clearanceType = $_POST['clearanceType'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $purpose = $_POST['purpose'];
    $registeredVoter = $_POST['registered_voter'] === 'Yes' ? 1 : 0;
    $resident_status = $_POST['resident_status'] === 'Yes' ? 1 : 0;
    $birthday = $_POST['birthday'];
    $dateApplied = $_POST['date_time_applied'];
    $dateToday = $_POST['dateToday'];
    
    // Format the name with middle initial if available
    $name = $last_name . ', ' . $first_name;
    if (!empty($middle_name)) {
        $name .= ' ' . substr($middle_name, 0, 1) . '.';
    }
    
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


    
    // Set initial status
    $status = 'To Be Approved';
    
    // Insert into tbl_clearance
    $sql = "INSERT INTO tbl_clearance (res_id, user_id, clearanceType, name, address, purpose, registeredVoter, resident_status, birthday, dateToday, dateApplied, document_path, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssiiissss", $res_id, $user_id, $clearanceType, $name, $address, $purpose, $registeredVoter, $resident_status, $birthday, $dateToday, $dateApplied, $document_path, $status);
    
    if ($stmt->execute()) {
        // Get the last inserted ID
        $clearance_id = $conn->insert_id;
        
        // Log the action in tbl_audit
        $details = "Clearance Request Submitted";
        $requestType = $clearanceType;
        // Set the timezone to Philippines/Manila
        date_default_timezone_set('Asia/Manila');
        $currentDateTime = date('Y-m-d H:i:s');
        
        $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $role = "barangay_official"; // Default role for officers
        $processedBy = $officer_fullname; // User who processed the request
        $audit_status = "Clearance Request Created";
        
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("siisssssss", $user_id, $brgyOfficer_id, $res_id, $requestType, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
        $audit_stmt->execute();
        $audit_stmt->close();
        header("Location: clearance.php?success=1");
        exit();
    } else {
        header("Location: clearance.php?error=3");
        exit();
    }
    $stmt->close();
}

// If not a POST request or something went wrong
header("Location: clearance.php");
exit();
?>