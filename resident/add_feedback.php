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
    // Get the feedback from the form
    $feedback = trim($_POST['feedback']);
    $user_id = $_SESSION['user_id'];
    
    // Validate feedback
    if (empty($feedback)) {
        header("Location: feedback.php?error=1");
        exit();
    }
    
    // Get the res_id from tbl_residents based on user_id
    $sql_resident = "SELECT res_id FROM tbl_residents WHERE user_id = ?";
    $stmt_resident = $conn->prepare($sql_resident);
    $stmt_resident->bind_param("i", $user_id);
    $stmt_resident->execute();
    $result_resident = $stmt_resident->get_result();
    
    if ($result_resident->num_rows > 0) {
        $row = $result_resident->fetch_assoc();
        $res_id = $row['res_id'];
        
        // Insert feedback into database
        $sql = "INSERT INTO tbl_feedback (res_id, user_id, feedback, dateCreated) 
                VALUES (?, ?, ?, CURRENT_TIMESTAMP())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $res_id, $user_id, $feedback);
        
        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: feedback.php?success=1");
            exit();
        } else {
            // Database error
            header("Location: feedback.php?error=2");
            exit();
        }
        
        $stmt->close();
    } else {
        // No corresponding resident record found
        header("Location: feedback.php?error=3");
        exit();
    }
    
    $stmt_resident->close();
} else {
    // Not a POST request
    header("Location: feedback.php");
    exit();
}

$conn->close();
?>