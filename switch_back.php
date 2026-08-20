<?php
session_start();
include '../connection/config.php';

// Switch back to the real head
if (isset($_SESSION['original_head_id'])) {
    $_SESSION['user_id'] = $_SESSION['original_head_id'];
    header("Location: family.php");
    exit;
} else {
    echo "No head session stored.";
}
?>
