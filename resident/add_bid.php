<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../connection/config.php';

$user_id = $_SESSION['user_id'];

// Get resident information
$query = "SELECT res_id AS res_id FROM tbl_residents WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $resident = $result->fetch_assoc();
    $res_id = $resident['res_id'];
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather personal info (readonly fields)
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'] ?? '';
    $address = $_POST['address'];
    $civilStatus = $_POST['civilStatus'];
    $precinctNumber = $_POST['precinctNumber'];
    $bloodType = $_POST['bloodType'];
    $birthday = $_POST['birthday'];
    $birthplace = $_POST['birthplace'];
    $height = (float) $_POST['height'];
    $weight = (float) $_POST['weight'];
    $SSSGSIS_Number = $_POST['SSSGSIS_Number'];
    $TIN_number = $_POST['TIN_number'];

    // Emergency contact
    $personTwoName = $_POST['personTwoName'];
    $personTwoAddress = $_POST['personTwoAddress'];
    $personTwoContactInfo = $_POST['personTwoContactInfo'];

    // Status
    $status = "To Be Approved";

    // File upload
    $document_path = "";
    $uploaded_files = [];

    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/id-documents/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        foreach ($_FILES['document_path']['name'] as $key => $filename) {
            if ($_FILES['document_path']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['document_path']['tmp_name'][$key];
                $unique_name = time() . '_' . uniqid() . '_' . basename($filename);
                $target_file = $upload_dir . $unique_name;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_files[] = $unique_name;
                }
            }
        }

        if (!empty($uploaded_files)) {
            $document_path = implode(",", $uploaded_files);
        }
    }

    // Insert into database
    $insertQuery = "INSERT INTO tbl_bid (
        res_id, user_id, last_name, first_name, middle_name, suffix, address, civilStatus, precinctNumber, 
        bloodType, birthday, birthplace, height, weight, status, SSSGSIS_Number, TIN_number, 
        document_path, personTwoName, personTwoAddress, personTwoContactInfo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertQuery);

    if (!$insertStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $insertStmt->bind_param(
        "iissssssssssddsssssss", // 21 characters: i=integer, s=string, d=double
        $res_id,
        $user_id,
        $last_name,
        $first_name,
        $middle_name,
        $suffix,
        $address,
        $civilStatus,
        $precinctNumber,
        $bloodType,
        $birthday,
        $birthplace,
        $height,
        $weight,
        $status,
        $SSSGSIS_Number,
        $TIN_number,
        $document_path,
        $personTwoName,
        $personTwoAddress,
        $personTwoContactInfo
    );
    

    if (!$insertStmt->execute()) {
        header("Location: barangay-id.php?error=1&message=" . urlencode($insertStmt->error));
    } else {
        header("Location: barangay-id.php?success=1");
    }

    $insertStmt->close();
}
?>
