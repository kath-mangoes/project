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
$position_query = "SELECT bo.brgyOfficer_id, bo.position, u.first_name, u.last_name, u.middle_name 
                  FROM tbl_brgyofficer bo
                  JOIN tbl_user u ON bo.user_id = u.user_id
                  WHERE bo.user_id = ? AND bo.status = 'Active'";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("s", $user_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();

$canEdit = false;
$brgyOfficer_id = null;
$officerFullName = "";

if ($position_result->num_rows > 0) {
    $officer_data = $position_result->fetch_assoc();
    $position = $officer_data['position'];
    $brgyOfficer_id = $officer_data['brgyOfficer_id'];
    
    // Construct full name of the officer
    $firstName = $officer_data['first_name'];
    $lastName = $officer_data['last_name'];
    $middleName = $officer_data['middle_name'];
    $officerFullName = $firstName . ' ' . ($middleName ? $middleName . ' ' : '') . $lastName;
    
    // Only Barangay Secretary can edit/update certificates
    $canEdit = ($position == 'Barangay Secretary');
}
$position_stmt->close();

// If not authorized, redirect with error
if (!$canEdit) {
    header("Location: barangayid.php?error=2");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $bid_id = $_POST['BID_id'];
    $status = $_POST['status'];
    
    // If status is "Completed", update dateReceived
    $dateReceived = null;
    if ($status == 'Completed') {
        $dateReceived = date('Y-m-d');
        $dateReceivedSet = true;
    } else {
        $dateReceivedSet = false;
    }
    
    // First, get the user_id and res_id associated with this bid
    $get_bid_query = "SELECT user_id, res_id FROM tbl_bid WHERE BID_id = ?";
    $bid_stmt = $conn->prepare($get_bid_query);
    $bid_stmt->bind_param("i", $bid_id);
    $bid_stmt->execute();
    $bid_result = $bid_stmt->get_result();
    
    if ($bid_result->num_rows > 0) {
        $bid_data = $bid_result->fetch_assoc();
        $bid_user_id = $bid_data['user_id'];
        $res_id = $bid_data['res_id'];
        
        // Update tbl_bid database
        if ($dateReceivedSet) {
            $sql = "UPDATE tbl_bid SET status = ? WHERE BID_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $bid_id);
        } else {
            $sql = "UPDATE tbl_bid SET status = ? WHERE BID_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $bid_id);
        }
        
        if ($stmt->execute()) {
            // Send notification to the user
            $notification_message = "Your barangay ID request has been updated to: " . $status;
            $notification_type = "bid_update";
            $notification_date = date('Y-m-d H:i:s');
            
            $notify_sql = "INSERT INTO tbl_notifications (user_id, message, notification_type, date_created, is_read) 
                          VALUES (?, ?, ?, ?, 0)";
            $notify_stmt = $conn->prepare($notify_sql);
            $notify_stmt->bind_param("ssss", $bid_user_id, $notification_message, $notification_type, $notification_date);
            $notify_stmt->execute();
            $notify_stmt->close();
            
            // Log the action in tbl_audit
           
            date_default_timezone_set('Asia/Manila');
            $currentDateTime = date('Y-m-d H:i:s');
            $details = "Barangay ID Status Updated to: " . $status;
            $role = "barangay_of"; // Default role for officers
            $processedBy = $officerFullName; // Use the full name instead of user_id
            $audit_status = "ID Status Updated";
            $requestType = "ID Issuance"; // Set requestType to ID Issuance as requested
            
            $audit_sql = "INSERT INTO tbl_audit (user_id, brgyOfficer_id, res_id, requestType, role, details, processedBy, dateTimeCreated, status, lastEdited) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $audit_stmt = $conn->prepare($audit_sql);
            $audit_stmt->bind_param("siisssssss", $bid_user_id, $brgyOfficer_id, $res_id, $requestType, $role, $details, $processedBy, $currentDateTime, $audit_status, $currentDateTime);
            $audit_stmt->execute();
            $audit_stmt->close();
            
            // Success
            header("Location: barangayid.php?success=2");
            exit();
        } else {
            // Failed
            header("Location: barangayid.php?error=8&msg=" . $stmt->error);
            exit();
        }
        $stmt->close();
    } else {
        // BID not found
        header("Location: barangayid.php?error=3");
        exit();
    }
    $bid_stmt->close();
} else {
    // Form was not submitted
    header("Location: barangayid.php");
    exit();
}

// Close database connection
$conn->close();
?>