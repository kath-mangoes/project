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

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];
    
    // If status is "Completed", update dateReceived
    $dateReceived = null;
    if ($status == 'Completed') {
        $dateReceived = date('Y-m-d');
        $dateReceivedSet = true;
    } else {
        $dateReceivedSet = false;
    }
    
    // First, get the user_id and res_id associated with this certification
    $get_certification_query = "SELECT user_id, res_id, natureOfCase FROM tbl_compgriev WHERE complaint_id = ?";
    $cert_stmt = $conn->prepare($get_certification_query);
    $cert_stmt->bind_param("i", $complaint_id);
    $cert_stmt->execute();
    $cert_result = $cert_stmt->get_result();
    
    if ($cert_result->num_rows > 0) {
        $complain_data = $cert_result->fetch_assoc();
        $complain_user_id = $complain_data['user_id'];
        $res_id = $complain_data['res_id'];
        $natureOfCase = $complain_data['natureOfCase'];
        
        // Update database
        if ($dateReceivedSet) {
            $sql = "UPDATE tbl_compgriev SET status = ? WHERE complaint_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $complaint_id);
        } else {
            $sql = "UPDATE tbl_compgriev SET status = ? WHERE complaint_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $complaint_id);
        }
        
        if ($stmt->execute()) {
            // Send notification to the user
            $notification_message = "Your complain request has been updated to: " . $status;
            $notification_type = "complain_update";
            $notification_date = date('Y-m-d H:i:s');
            
            $notify_sql = "INSERT INTO tbl_notifications (user_id, message, notification_type, date_created, is_read) 
                          VALUES (?, ?, ?, ?, 0)";
            $notify_stmt = $conn->prepare($notify_sql);
            $notify_stmt->bind_param("ssss", $complain_user_id, $notification_message, $notification_type, $notification_date);
            $notify_stmt->execute();
            $notify_stmt->close();
            
            // Log the action in tbl_audit
            date_default_timezone_set('Asia/Manila');
            $currentDateTime = date('Y-m-d H:i:s');
            $details = "Complain Status Updated to: " . $status;
            $role = "admin"; // Default role for officers
            $processedBy = "admin"; // Use the full name instead of user_id
            $audit_status = "Complain Status Updated";
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", $complain_user_id, $brgyOfficer_id, $res_id, $natureOfCase, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success
            header("Location: complains.php?success=2");
            exit();
        } else {
            // Failed
            header("Location: complains.php?error=8&msg=" . $stmt->error);
            exit();
        }
        $stmt->close();
    } else {
        // Certification not found
        header("Location: complains.php?error=3");
        exit();
    }
    $cert_stmt->close();
} else {
    // Form was not submitted
    header("Location: complains.php");
    exit();
}

// Close database connection
$conn->close();
?>