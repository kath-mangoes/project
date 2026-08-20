<?php
// Start session to manage user authentication and flash messages
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to request a barangay ID.";
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
    // Validate and sanitize input data
    $res_id = isset($_POST['resident_id']) ? sanitize($_POST['resident_id']) : '';
    $user_id = isset($_POST['user_id']) ? sanitize($_POST['user_id']) : '';
    $first_name = isset($_POST['first_name']) ? sanitize($_POST['first_name']) : '';
    $middle_name = isset($_POST['middle_name']) ? sanitize($_POST['middle_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize($_POST['last_name']) : '';
    $address = isset($_POST['address']) ? sanitize($_POST['address']) : '';
    $birthdate = isset($_POST['birthdate']) ? sanitize($_POST['birthdate']) : '';
    $birthplace = isset($_POST['birthplace']) ? sanitize($_POST['birthplace']) : '';
    $ID_No = isset($_POST['ID_No']) ? sanitize($_POST['ID_No']) : '';
    $precinctNumber = isset($_POST['precinctNumber']) ? sanitize($_POST['precinctNumber']) : '';
    $bloodType = isset($_POST['bloodType']) ? sanitize($_POST['bloodType']) : '';
    $height = isset($_POST['height']) ? sanitize($_POST['height']) : '';
    $weight = isset($_POST['weight']) ? sanitize($_POST['weight']) : '';
    $SSSGSIS_Number = isset($_POST['SSSGSIS_Number']) ? sanitize($_POST['SSSGSIS_Number']) : '';
    $TIN_number = isset($_POST['TIN_number']) ? sanitize($_POST['TIN_number']) : '';
    $personTwoName = isset($_POST['personTwoName']) ? sanitize($_POST['personTwoName']) : '';
    $personTwoAddress = isset($_POST['personTwoAddress']) ? sanitize($_POST['personTwoAddress']) : '';
    $personTwoContactInfo = isset($_POST['personTwoContactInfo']) ? sanitize($_POST['personTwoContactInfo']) : '';
    $dateApplied = isset($_POST['dateApplied']) ? sanitize($_POST['dateApplied']) : '';
    
    // If first_name, last_name are empty, try to retrieve them from the database
    if (empty($first_name) || empty($last_name)) {
        $resident_query = "SELECT first_name, middle_name, last_name FROM tbl_residents WHERE resident_id = ?";
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
        $user_query = "SELECT user_id FROM tbl_residents WHERE resident_id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "i", $res_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_user_id);
        
        if (mysqli_stmt_fetch($stmt)) {
            $user_id = $db_user_id;
        }
        mysqli_stmt_close($stmt);
    }
    
    // Format full name
    $name = $first_name;
    if (!empty($middle_name)) {
        $name .= " " . substr($middle_name, 0, 1) . ".";
    }
    $name .= " " . $last_name;

    // Default status for new ID requests
    $status = "To Be Approved";
    
    // Get current date for dateToday
    $dateToday = date("Y-m-d");
    
    // File upload handling for ID document
    $document_path = ""; // Will store comma-separated file names
    $uploaded_files = [];

    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/id-documents/";

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
    if(empty($res_id) || empty($address) || empty($birthdate) || empty($birthplace) || 
       empty($ID_No) || empty($personTwoName) || empty($personTwoAddress) || empty($personTwoContactInfo)) {
        $_SESSION['error'] = "All required fields must be filled in.";
        header("Location: index.php");
        exit();
    }
    
    // Prepare SQL statement to insert data into tbl_bid
    $sql = "INSERT INTO tbl_bid (resident_id, user_id, name, address, birthdate, birthplace, ID_No, precinctNumber, 
            bloodType, height, weight, SSSGSIS_Number, TIN_number, personTwoName, personTwoAddress, 
            personTwoContactInfo, dateApplied, document_path, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "isssssssddsssssssss", 
            $res_id, 
            $user_id, 
            $name, 
            $address, 
            $birthdate,
            $birthplace,
            $ID_No,
            $precinctNumber,
            $bloodType,
            $height,
            $weight,
            $SSSGSIS_Number,
            $TIN_number,
            $personTwoName,
            $personTwoAddress,
            $personTwoContactInfo,
            $dateApplied,
      
            $document_path,
            $status);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Get the last inserted ID
            $id_request_id = mysqli_insert_id($conn);
            
            // Log the action in tbl_audit
            $details = "Barangay ID Request Submitted";
            $requestType = "Barangay ID";
            $currentDateTime = date('Y-m-d H:i:s');
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, residenet_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $role = "barangay_official"; // Default role for officers
            $processedBy = $officer_fullname; // User who processed the request
            $audit_status = "Barangay ID Request Created";
            
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
            header("Location: barangayid.php?success=1");
            exit();
        } else {
            // Error, redirect with error message
            header("Location: barangayid.php?error=db&msg=" . mysqli_error($conn));
            exit();
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error with prepared statement
        header("Location: barangayid.php?error=prep&msg=" . mysqli_error($conn));
        exit();
    }
    
} else {
    // If not POST request, redirect to certificates page
    header("Location: barangayid.php");
    exit();
}
?>