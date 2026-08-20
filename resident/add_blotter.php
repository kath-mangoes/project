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
    $complainant = $_POST['complainant'];
    $respondent = $_POST['respondent'];
    $victim = isset($_POST['victim']) ? $_POST['victim'] : null;
    $witness = isset($_POST['witness']) ? $_POST['witness'] : null;
    $natureOfCase = $_POST['natureOfCase'];
    $caseNumber = 'CASE-' . date('Ymd') . '-' . rand(1000, 9999); // Generate a case number
    $dateFiled = date('Y-m-d'); // Current date

    // Default values
    $brgyOfficer_id = 1; // Default officer ID, will be updated by the system later
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

    // Insert request into tbl_blotter
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
        // Redirect with success parameter for SweetAlert
        header("Location: blotter.php?success=1");
        exit();
    } else {
        // Redirect with error parameter
        header("Location: blotter.php?error=1&message=" . urlencode($conn->error));
        exit();
    }

    $insertStmt->close();
}
