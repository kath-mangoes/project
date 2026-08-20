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

// Get resident information based on user_id
$query = "SELECT res_id FROM tbl_residents WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $resident = $result->fetch_assoc();
    $res_id = $resident['res_id'];
} else {
    // Redirect if resident profile not found
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add_existing') {
        $res_id = $_POST['res_id'];
        $household_id = $_POST['household_id'];
        $head_id = $_POST['head_id'];
        $position = $_POST['position'];

        if ($res_id && $household_id && $head_id) {
            $stmt = $conn->prepare("UPDATE tbl_residents SET household_id = ?, head_of_family_id = ?, position = ? WHERE id = ?");
            $stmt->bind_param("iisi", $household_id, $head_id, $position, $res_id);
            $stmt->execute();
        }

    } elseif ($action === 'add_child') {
        $fname = $_POST['firstname'];
        $mname = $_POST['middlename'];
        $lname = $_POST['lastname'];
        $bdate = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $household_id = $_POST['household_id'];
        $head_id = $_POST['head_of_family_id'];

        // Calculate age
        $birthDate = new DateTime($bdate);
        $today = new DateTime();
        $age = $birthDate->diff($today)->y;

        if ($age < 18) {
            $stmt = $conn->prepare("INSERT INTO tbl_residents
                (firstname, middlename, lastname, birthdate, age, gender, household_id, head_of_family_id, position) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Child')");
            $stmt->bind_param("ssssiisi", $fname, $mname, $lname, $bdate, $age, $gender, $household_id, $head_id);
            $stmt->execute();
        }
    }
}

header("Location: resident_household.php");
exit;

    $insertStmt->close();
?>
