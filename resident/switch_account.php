<?php
session_start();
include '../connection/config.php';

if (!isset($_SESSION['active_user_id'], $_SESSION['original_head_id'])) {
    die("Not logged in.");
}

$original_head_id = $_SESSION['original_head_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_user_id'])) {
    $target_user_id = (int)$_POST['target_user_id'];

    // Fetch target user
    $stmt = $conn->prepare("SELECT user_id, household_head_id FROM tbl_user WHERE user_id = ?");
    $stmt->bind_param("i", $target_user_id);
    $stmt->execute();
    $target = $stmt->get_result()->fetch_assoc();

    if ($target) {
        if ((int)$target['household_head_id'] === (int)$original_head_id) {
            $_SESSION['active_user_id'] = $target_user_id;
            header("Location: family.php");
            exit;
        } else {
            echo "❌ Target user is not in your household. Debug: target_head={$target['household_head_id']} original_head={$original_head_id}";
        }
    } else {
        echo "❌ Target account not found.";
    }
}
?>
