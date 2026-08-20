<?php
// Start session and include database connection
include '../connection/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user has permission to update certificates (Barangay Secretary only)
$user_id = $_SESSION['user_id'];
$position_query = "SELECT bo.brgyOfficer_id, bo.position, bo.first_name, bo.middle_name, bo.last_name 
                  FROM tbl_brgyofficer bo 
                  WHERE bo.user_id = ? AND bo.status = 'Active'";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("s", $user_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();

$canEdit = false;
$brgyOfficer_id = null;
$officer_fullname = "";

if ($position_result->num_rows > 0) {
    $officer_data = $position_result->fetch_assoc();
    $position = $officer_data['position'];
    $brgyOfficer_id = $officer_data['brgyOfficer_id'];
    
    // Get the full name of the officer for the "processedBy" field
    $officer_fullname = $officer_data['first_name'] . ' ' . $officer_data['middle_name'] . ' ' . $officer_data['last_name'];
    
    // Only Barangay Secretary can edit/update clearance
    $canEdit = ($position == 'Barangay Secretary');
}
$position_stmt->close();

// If not authorized, redirect with error
if (!$canEdit) {
    header("Location: certificate.php?error=2");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $certification_id = $_POST['certification_id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'] ?? '';
    
    // If status is "Completed", update dateReceived
    $dateReceived = null;
    if ($status == 'Completed') {
        $dateReceived = date('Y-m-d');
        $dateReceivedSet = true;
    } else {
        $dateReceivedSet = false;
    }
    
    // First, get the user_id and res_id associated with this certification
    $get_certification_query = "SELECT user_id, res_id, certificationType FROM tbl_certification WHERE certification_id = ?";
    $cert_stmt = $conn->prepare($get_certification_query);
    $cert_stmt->bind_param("i", $certification_id);
    $cert_stmt->execute();
    $cert_result = $cert_stmt->get_result();
    
    if ($cert_result->num_rows > 0) {
        $certification_data = $cert_result->fetch_assoc();
        $certification_user_id = $certification_data['user_id'];
        $res_id = $certification_data['res_id'];
        $certificateType = $certification_data['certificationType'];
        
        // Update database
        if ($dateReceivedSet) {
            $sql = "UPDATE tbl_certification SET status = ?, remarks = ?, dateReceived = ? WHERE certification_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $status, $remarks, $dateReceived, $certification_id);
        } else {
            $sql = "UPDATE tbl_certification SET status = ?, remarks = ? WHERE certification_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $remarks, $certification_id);
        }
        
        if ($stmt->execute()) {
            // Send notification to the user
            $notification_message = "Your certificate request has been updated to: " . $status;
            $notification_type = "certification_update";
            $notification_date = date('Y-m-d H:i:s');
            
            $notify_sql = "INSERT INTO tbl_notifications (user_id, message, notification_type, date_created, is_read) 
                          VALUES (?, ?, ?, ?, 0)";
            $notify_stmt = $conn->prepare($notify_sql);
            $notify_stmt->bind_param("ssss", $certification_user_id, $notification_message, $notification_type, $notification_date);
            $notify_stmt->execute();
            $notify_stmt->close();
            
            // Log the action in tbl_audit
            date_default_timezone_set('Asia/Manila');
            $currentDateTime = date('Y-m-d H:i:s');
            $details = "Certificate Status Updated to: " . $status;
            $role = "barangay_of"; // Default role for officers
            $audit_status = "Certificate Status Updated";
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", $certification_user_id, $brgyOfficer_id, $res_id, $certificateType, $role, $details, $officer_fullname, $currentDateTime, $audit_status, $currentDateTime);
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success
            header("Location: certificate.php?success=2");
            exit();
        } else {
            // Failed
            header("Location: certificate.php?error=8&msg=" . $stmt->error);
            exit();
        }
        $stmt->close();
    } else {
        // Certification not found
        header("Location: certificate.php?error=3");
        exit();
    }
    $cert_stmt->close();
} else {
    // Form was not submitted
    header("Location: certificate.php");
    exit();
}

// Close database connection
$conn->close();
?>