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
    $clearance_id = $_POST['clearance_id'];
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
    
    // First, get the user_id and res_id associated with this clearance
    $get_clearance_query = "SELECT user_id, res_id, clearanceType FROM tbl_clearance WHERE clearance_id = ?";
    $clear_stmt = $conn->prepare($get_clearance_query);
    $clear_stmt->bind_param("i", $clearance_id);
    $clear_stmt->execute();
    $clear_result = $clear_stmt->get_result();
    
    if ($clear_result->num_rows > 0) {
        $clearance_data = $clear_result->fetch_assoc();
        $clearance_user_id = $clearance_data['user_id'];
        $res_id = $clearance_data['res_id'];
        $clearanceType = $clearance_data['clearanceType'];
        
        // Update database
        if ($dateReceivedSet) {
            $sql = "UPDATE tbl_clearance SET status = ?, remarks = ?, dateReceived = ? WHERE clearance_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $status, $remarks, $dateReceived, $clearance_id);
        } else {
            $sql = "UPDATE tbl_clearance SET status = ?, remarks = ? WHERE clearance_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $remarks, $clearance_id);
        }
        
        if ($stmt->execute()) {
            // Send notification to the user
            $notification_message = "Your clearance request has been updated to: " . $status;
            $notification_type = "clearance_update";
            $notification_date = date('Y-m-d H:i:s');
            
            $notify_sql = "INSERT INTO tbl_notifications (user_id, message, notification_type, date_created, is_read) 
                          VALUES (?, ?, ?, ?, 0)";
            $notify_stmt = $conn->prepare($notify_sql);
            $notify_stmt->bind_param("ssss", $clearance_user_id, $notification_message, $notification_type, $notification_date);
            $notify_stmt->execute();
            $notify_stmt->close();
            
            // Log the action in tbl_audit
            date_default_timezone_set('Asia/Manila');
            $currentDateTime = date('Y-m-d H:i:s');
            $details = "Clearance Status Updated to: " . $status;
            $role = "admin"; // Default role for officers
            $audit_status = "Clearance Status Updated";
            $processedBy ="admin";
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", $clearance_user_id, $brgyOfficer_id, $res_id, $clearanceType, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success
            header("Location: clearance.php?success=2");
            exit();
        } else {
            // Failed
            header("Location: clearance.php?error=8&msg=" . $stmt->error);
            exit();
        }
        $stmt->close();
    } else {
        // Clearance not found
        header("Location: clearance.php?error=3");
        exit();
    }
    $clear_stmt->close();
} else {
    // Form was not submitted
    header("Location: clearance.php");
    exit();
}

// Close database connection
$conn->close();
?>