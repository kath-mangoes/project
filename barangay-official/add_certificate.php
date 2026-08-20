<?php
// Start session to manage user authentication and flash messages
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to request a certificate.";
    header("Location: login.php");
    exit();
}

// Include database connection
include '../connection/config.php';

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

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // For debugging
    // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
    
    // Validate and sanitize input data
    $res_id = isset($_POST['resident_id']) ? sanitize($_POST['resident_id']) : '';
    $user_id = isset($_POST['user_id']) ? sanitize($_POST['user_id']) : '';
    $certificationType = isset($_POST['certificationType']) ? sanitize($_POST['certificationType']) : '';
    $first_name = isset($_POST['first_name']) ? sanitize($_POST['first_name']) : '';
    $middle_name = isset($_POST['middle_name']) ? sanitize($_POST['middle_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize($_POST['last_name']) : '';
    $address = isset($_POST['address']) ? sanitize($_POST['address']) : '';
    $purpose = isset($_POST['purpose']) ? sanitize($_POST['purpose']) : '';
    $registered_voter = isset($_POST['registered_voter']) ? ($_POST['registered_voter'] == 'Yes' ? 1 : 0) : 0;
    $resident_status = isset($_POST['resident_status']) ? ($_POST['resident_status'] == 'Yes' ? 1 : 0) : 0;
    $birthday = isset($_POST['birthday']) ? sanitize($_POST['birthday']) : '';
    $date_time_applied = isset($_POST['date_time_applied']) ? sanitize($_POST['date_time_applied']) : '';
    
    // If first_name, last_name are empty, try to retrieve them from the database
    if (empty($first_name) || empty($last_name)) {
        $resident_query = "SELECT first_name, middle_name, last_name FROM tbl_residents WHERE res_id = ?";
        $stmt = mysqli_prepare($conn, $resident_query);
        mysqli_stmt_bind_param($stmt, "i", $res_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_first_name, $db_middle_name, $db_last_name);
        
        if (mysqli_stmt_fetch($stmt)) {
            $first_name = $db_first_name;
            $middle_name = $db_middle_name;
            $last_name = $db_last_name;
        }
        mysqli_stmt_close($stmt);
    }
    
    // If user_id is empty, retrieve it from the database
    if (empty($user_id)) {
        $user_query = "SELECT user_id FROM tbl_residents WHERE res_id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "i", $res_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_user_id);
        
        if (mysqli_stmt_fetch($stmt)) {
            $user_id = $db_user_id;
        }
        mysqli_stmt_close($stmt);
    }
    
    // Format name with middle initial if available
    $name = $first_name;
    if (!empty($middle_name)) {
        $name .= " " . substr($middle_name, 0, 1) . ".";
    }
    $name .= " " . $last_name;

    // Default status for new certificate requests
    $status = "To Be Approved";
    
    // Get current date for dateToday
    $dateToday = date("Y-m-d");
    
    // File upload handling
    $document_paths = [];

    if (isset($_FILES['document_path']) && is_array($_FILES['document_path']['name'])) {
        $target_dir = "../dist/assets/images/uploads/certification-documents/";
    
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    
        $allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
    
        foreach ($_FILES['document_path']['name'] as $key => $original_filename) {
            if ($_FILES['document_path']['error'][$key] == 0) {
                $timestamp = time() . '_' . $key; // unique filename
                $unique_filename = $timestamp . "_" . basename($original_filename);
                $target_file = $target_dir . $unique_filename;
    
                $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                if (in_array($file_extension, $allowed_extensions)) {
                    if ($_FILES['document_path']['size'][$key] <= 5000000) {
                        if (move_uploaded_file($_FILES['document_path']['tmp_name'][$key], $target_file)) {
                            $document_paths[] = $unique_filename;
                        } else {
                            $_SESSION['error'] = "Error uploading file: " . htmlspecialchars($original_filename);
                            header("Location: certificate.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = "File too large: " . htmlspecialchars($original_filename) . " (Max 5MB)";
                        header("Location: certificate.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Invalid file type: " . htmlspecialchars($original_filename);
                    header("Location: certificate.php");
                    exit();
                }
            } else if ($_FILES['document_path']['error'][$key] != 4) {
                $_SESSION['error'] = "Upload error for file: " . htmlspecialchars($original_filename);
                header("Location: certificate.php");
                exit();
            }
        }
    
        // Convert array to comma-separated string for DB storage
        $document_path = implode(',', $document_paths);
    
    } else {
        $document_path = ""; // No file uploaded
    }


    
    // Debug output to check variables before insert
    // echo "res_id: $res_id, user_id: $user_id, name: $name, first_name: $first_name, middle_name: $middle_name, last_name: $last_name";
    // exit();
    
    // Validate required fields
    if(empty($res_id) || empty($certificationType) || empty($address) || 
       empty($purpose) || empty($birthday) || empty($date_time_applied)) {
        $_SESSION['error'] = "All fields are required. Please fill in all the required information.";
        header("Location: index.php");
        exit();
    }
    
    // Check if name and user_id are still empty after all attempts
    if(empty($name) || empty($user_id)) {
        $_SESSION['error'] = "Could not retrieve resident information. Please try again.";
        header("Location: certificate.php");
        exit();
    }
    
    // Prepare SQL statement to insert data
    $sql = "INSERT INTO tbl_certification (res_id, user_id, certificationType, name, address, purpose, 
            registeredVoter, resident_status, birthday, dateToday, dateApplied, document_path, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "isssssiiissss", 
            $res_id, 
            $user_id, 
            $certificationType, 
            $name, 
            $address, 
            $purpose,
            $registered_voter, 
            $resident_status, 
            $birthday, 
            $dateToday, 
            $date_time_applied,
            $document_path, 
            $status);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Get the last inserted ID
            $certificate_id = mysqli_insert_id($conn);
            
            // Log the action in tbl_audit
            $details = "Certificate Request Submitted";
            $requestType = $certificationType;
            // Set the timezone to Philippines/Manila
            date_default_timezone_set('Asia/Manila');

            // Generate current date and time in Manila timezone
            $currentDateTime = date('Y-m-d H:i:s');
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $role = "barangay_official"; // Default role for officers
            $processedBy = $officer_fullname; // User who processed the request
            $audit_status = "Certificate Request Created";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", 
                $user_id, 
                $brgyOfficer_id, 
                $res_id, 
                $requestType, 
                $role, 
                $details, 
                $processedBy, 
                $currentDateTime, 
                $audit_status, 
                $currentDateTime);
                
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success, redirect with success message
            header("Location: certificate.php?success=1");
            exit();
        } else {
            // Error, redirect with error message
            header("Location: certificate.php?error=db&msg=" . mysqli_error($conn));
            exit();
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error with prepared statement
        header("Location: certificate.php?error=prep&msg=" . mysqli_error($conn));
        exit();
    }
    
} else {
    // If not POST request, redirect to certificates page
    header("Location: certificate.php");
    exit();
}
?>