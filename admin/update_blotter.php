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
    $blotter_id = $_POST['blotter_id'];
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
    $get_blotter_query = "SELECT user_id, res_id, natureOfCase FROM tbl_blotter WHERE blotter_id = ?";
    $blotter_stmt = $conn->prepare($get_blotter_query);
    $blotter_stmt->bind_param("i", $blotter_id);
    $blotter_stmt->execute();
    $blotter_result = $blotter_stmt->get_result();
    
    if ($blotter_result->num_rows > 0) {
        $blotter_data = $blotter_result->fetch_assoc();
        $blotter_user_id = $blotter_data['user_id'];
        $res_id = $blotter_data['res_id'];
        $natureOfCase = $blotter_data['natureOfCase'];
        
        // Update database
        if ($dateReceivedSet) {
            $sql = "UPDATE tbl_blotter SET status = ? WHERE blotter_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $blotter_id);
        } else {
            $sql = "UPDATE tbl_blotter SET status = ? WHERE blotter_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $blotter_id);
        }
        
        if ($stmt->execute()) {
            // Send notification to the user
            $notification_message = "Your blotter request has been updated to: " . $status;
            $notification_type = "blotter_update";
            $notification_date = date('Y-m-d H:i:s');
            
            $notify_sql = "INSERT INTO tbl_notifications (user_id, message, notification_type, date_created, is_read) 
                          VALUES (?, ?, ?, ?, 0)";
            $notify_stmt = $conn->prepare($notify_sql);
            $notify_stmt->bind_param("ssss", $blotter_user_id, $notification_message, $notification_type, $notification_date);
            $notify_stmt->execute();
            $notify_stmt->close();
            
            // Log the action in tbl_audit
            date_default_timezone_set('Asia/Manila');
            $currentDateTime = date('Y-m-d H:i:s');
            $details = "Blotter Status Updated to: " . $status;
            $role = "admin"; // Default role for officers
            $processedBy = "admin"; // Use the full name instead of user_id
            $audit_status = "Blotter Status Updated";
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", $blotter_user_id, $brgyOfficer_id, $res_id, $natureOfCase, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success
            header("Location: blotter.php?success=2");
            exit();
        } else {
            // Failed
            header("Location: blotter.php?error=8&msg=" . $stmt->error);
            exit();
        }
        $stmt->close();
    } else {
        // Certification not found
        header("Location: blotter.php?error=3");
        exit();
    }
    $cert_stmt->close();
} else {
    // Form was not submitted
    header("Location: blotter.php");
    exit();
}

// Close database connection
$conn->close();
?>