<?php
session_start();
include '../connection/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $feedback_id = $_POST['feedback_id'];
    $feedback = trim($_POST['feedback']);
    $user_id = $_SESSION['user_id'];
    
    // Validate feedback
    if (empty($feedback)) {
        header("Location: feedback.php?error=1");
        exit();
    }
    
    // Verify that the feedback belongs to the current user
    $check_sql = "SELECT * FROM tbl_feedback WHERE feedback_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $feedback_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update the feedback
        $update_sql = "UPDATE tbl_feedback SET feedback = ?, lastEdited = CURRENT_TIMESTAMP() 
                      WHERE feedback_id = ?";
        
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $feedback, $feedback_id);
        
        if ($update_stmt->execute()) {
            // Redirect with success message
            header("Location: feedback.php?success=2");
            exit();
        } else {
            // Database error
            header("Location: feedback.php?error=2");
            exit();
        }
        
        $update_stmt->close();
    } else {
        // Feedback not found or does not belong to the user
        header("Location: feedback.php?error=4");
        exit();
    }
    
    $check_stmt->close();
} else {
    // Not a POST request
    header("Location: feedback.php");
    exit();
}

$conn->close();
?>