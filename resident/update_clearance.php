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

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $clearance_id = $_POST['clearance_id'];
    $clearanceType = $_POST['clearance_type'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $address = $_POST['address'];
    $purpose = $_POST['purpose'];
    $registeredVoter = ($_POST['registered_voter'] === 'Yes') ? 1 : 0;
    $resident_status = ($_POST['resident_status'] === 'Yes') ? 1 : 0;
    $birthday = $_POST['birthday'];
    $date_time_applied = $_POST['date_time_applied'];

    // Set the timezone to Manila, Philippines
    date_default_timezone_set('Asia/Manila');

    // Format the date correctly for MySQL datetime
    $dateApplied = date('Y-m-d H:i:s', strtotime($date_time_applied));

    // Combine name fields
    $name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;

    // Check if clearance record exists and belongs to the user
    $checkQuery = "SELECT * FROM tbl_clearance WHERE clearance_id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $clearance_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        // Record not found or doesn't belong to user
        header("Location: my-request.php?error=1&message=" . urlencode("Unauthorized access or record not found"));
        exit();
    }

    $checkStmt->close();

    // File upload handling
    $documentUpdate = "";
    if (isset($_FILES['document_path']) && $_FILES['document_path']['error'] == 0) {
        $upload_dir = "../dist/assets/images/uploads/clearance-documents/";

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . '_' . $_FILES['document_path']['name'];
        $target_file = $upload_dir . $file_name;

        // Move the uploaded file to target directory
        if (move_uploaded_file($_FILES['document_path']['tmp_name'], $target_file)) {
            // Get the old document path
            $oldDocQuery = "SELECT document_path FROM tbl_clearance WHERE clearance_id = ?";
            $oldDocStmt = $conn->prepare($oldDocQuery);
            $oldDocStmt->bind_param("i", $clearance_id);
            $oldDocStmt->execute();
            $oldDocResult = $oldDocStmt->get_result();
            
            if ($oldDocResult->num_rows > 0) {
                $oldDoc = $oldDocResult->fetch_assoc();
                $oldDocPath = $upload_dir . $oldDoc['document_path'];
                
                // Delete old file if it exists
                if (!empty($oldDoc['document_path']) && file_exists($oldDocPath)) {
                    unlink($oldDocPath);
                }
            }
            
            $oldDocStmt->close();
            $documentUpdate = ", document_path = '$file_name'";
        } else {
            // Handle upload failure
            header("Location: my-request.php?error=1&message=" . urlencode("Failed to upload document"));
            exit();
        }
    }

  // Update the query to use prepared statements properly for the document_path
$updateQuery = "UPDATE tbl_clearance SET 
clearanceType = ?, 
name = ?, 
address = ?, 
purpose = ?, 
registeredVoter = ?, 
resident_status = ?, 
birthday = ?, 
dateApplied = ?";

// Add document_path parameter if a file was uploaded
$params = [$clearanceType, $name, $address, $purpose, $registeredVoter, 
$resident_status, $birthday, $dateApplied];
$types = "ssssiiss";

if (isset($_FILES['document_path']) && $_FILES['document_path']['error'] == 0 && !empty($file_name)) {
$updateQuery .= ", document_path = ?";
$params[] = $file_name;
$types .= "s";
}

$updateQuery .= " WHERE clearance_id = ? AND user_id = ?";
$params[] = $clearance_id;
$params[] = $user_id;
$types .= "ii";

$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param($types, ...$params);
    if ($updateStmt->execute()) {
        // Redirect with success parameter for SweetAlert
        header("Location: my-request.php?success=2"); // 2 for update success
        exit();
    } else {
        // Redirect with error parameter
        header("Location: my-request.php?error=1&message=" . urlencode($conn->error));
        exit();
    }

    $updateStmt->close();
} else {
    // If not POST request, redirect to the main page
    header("Location: my-request.php");
    exit();
}

$conn->close();
?>