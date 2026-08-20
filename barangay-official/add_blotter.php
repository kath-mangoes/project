<?php
session_start();
include '../connection/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// First, get the user ID from the session
$user_id = $_SESSION['user_id'];

// Then use it in your position query
$position_query = "SELECT bo.brgyOfficer_id, bo.position, bo.first_name, bo.middle_name, bo.last_name 
                  FROM tbl_brgyofficer bo 
                  WHERE bo.user_id = ? AND bo.status = 'Active'";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("s", $user_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();

// Initialize these variables
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (empty($_POST['resident_id']) || !is_numeric($_POST['resident_id'])) {
        header("Location: blotter.php?error=1&message=" . urlencode("Resident ID is required"));
        exit();
    }
    
    $res_id = (int)$_POST['resident_id']; // The resident's ID
    
    // Get the user_id from the form
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    
    // If user_id wasn't sent from the form, try to get it from the resident_id
    if (empty($user_id)) {
        $user_query = "SELECT user_id FROM tbl_residents WHERE res_id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("i", $res_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        
        if ($user_result->num_rows > 0) {
            $user_row = $user_result->fetch_assoc();
            $user_id = $user_row['user_id'];
        }
        $user_stmt->close();
    }
    
    $complainant = $_POST['complainant'];
    $respondent = $_POST['respondent'];
    $victim = isset($_POST['victim']) ? $_POST['victim'] : null;
    $witness = isset($_POST['witness']) ? $_POST['witness'] : null;
    $natureOfCase = $_POST['natureOfCase'];
    $caseNumber = 'CASE-' . date('Ymd') . '-' . rand(1000, 9999); // Generate a case number
    $dateFiled = date('Y-m-d'); // Current date

    
    $status = "To Be Approved"; // Default status

    // File upload handling
    $document_path = ""; // Will store comma-separated file names
    $uploaded_files = [];

    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/blotter-documents/";

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

    // Validate required fields
    if (empty($complainant) || empty($respondent) || empty($natureOfCase)) {
        header("Location: blotter.php?error=1&message=" . urlencode("Please fill all required fields"));
        exit();
    }

    // Insert request into tbl_compgriev
    $insertQuery = "INSERT INTO tbl_blotter (res_id, user_id, dateFiled, caseNumber, complainant, 
                   respondent, victim, witness, natureOfCase, brgyOfficer_id, 
                   document_path, status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertQuery);

    $insertStmt->bind_param(
        "issssssssiss",
        $res_id,
        $user_id,
        $dateFiled,
        $caseNumber,
        $complainant,
        $respondent,
        $victim,
        $witness,
        $natureOfCase,
        $brgyOfficer_id,
        $document_path,
        $status
    );

    if ($insertStmt->execute()) {
        // Get the last inserted ID
        $complaint_id = $conn->insert_id;
        
        // Log the action in tbl_audit
        $details = "Blotter Request Submitted";
        $requestType = $natureOfCase;
        // Set the timezone to Philippines/Manila
        date_default_timezone_set('Asia/Manila');
        $currentDateTime = date('Y-m-d H:i:s');
        
        $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $role = "barangay_official"; // Default role for officers
        $processedBy = $officer_fullname; // Officer who processed the request
        $audit_status = "Blotter Request Created";
        
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("siisssssss", $user_id, $brgyOfficer_id, $res_id, $requestType, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
        $audit_stmt->execute();
        $audit_stmt->close();
        
        header("Location: blotter.php?success=1");
        exit();
    } else {
        header("Location: blotter.php?error=3&message=" . urlencode("Database error: " . $conn->error));
        exit();
    }
    $insertStmt->close();
}

// If not a POST request or something went wrong
header("Location: blotter.php");
exit();
?>