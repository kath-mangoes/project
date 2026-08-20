<?php
include 'connection/config.php';

function logActivity($user_id, $user_type, $activity) {
    global $conn;
    $sql = "INSERT INTO tbl_audit (user_id, role, details) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $user_type, $activity);
    $stmt->execute();
}

function getNextUserId($conn) {
    $query = "SELECT user_id FROM tbl_user ORDER BY CAST(user_id AS UNSIGNED) DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $lastId = $result->fetch_assoc()['user_id']; // e.g., '5'
        $number = intval($lastId);
        return (string)($number + 1); // returns '6'
    }

    return '1'; // First user
}


?>
