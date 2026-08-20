<?php
// Start the session at the beginning of the file
session_start();

// Include database connection
include '../connection/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $feedback_id = $_POST['feedback_id'] ?? '';
    $action = $_POST['action'] ?? '';
    $action_by = $_POST['action_by'] ?? '';
    
    // Validate input
    if (empty($feedback_id) || empty($action) || empty($action_by)) {
        // Redirect with error
        header('Location: feedback.php?error=1');
        exit();
    }
    
    // Prepare update statement
    $sql = "UPDATE tbl_feedback SET action = ?, action_by = ?, lastEdited = NOW() WHERE feedback_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssi", $action, $action_by, $feedback_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect with success message
            header('Location: feedback.php?success=3');
            exit();
        } else {
            // Query execution failed
            header('Location: feedback.php?error=1');
            exit();
        }
    } else {
        // Statement preparation failed
        header('Location: feedback.php?error=1');
        exit();
    }
} else {
    // If not a POST request, redirect back
    header('Location: feedback.php');
    exit();
}
?>