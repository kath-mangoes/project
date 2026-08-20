<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../connection/config.php';

$user_id = $_SESSION['user_id'];

// Fetch resident id
$query = "SELECT res_id FROM tbl_residents WHERE user_id = ?";
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
    // ===== Step 1: General Required Fields =====
    $certificationType = trim($_POST['certificationType'] ?? '');
    $first_name        = trim($_POST['first_name'] ?? '');
    $middle_name       = trim($_POST['middle_name'] ?? '');
    $last_name         = trim($_POST['last_name'] ?? '');
    $suffix            = trim($_POST['suffix'] ?? '');
    $address           = trim($_POST['address'] ?? '');
    $purpose           = trim($_POST['purpose'] ?? '');
    $other_purpose     = trim($_POST['other_purpose'] ?? '');
    $resident_status   = trim($_POST['resident_status'] ?? '');

    if (empty($certificationType)) {
        echo "<script>alert('❌ Please select a Certificate Type.'); history.back();</script>";
        exit();
    }
    if (empty($first_name)) {
        echo "<script>alert('❌ First Name is required.'); history.back();</script>";
        exit();
    }
    if (empty($last_name)) {
        echo "<script>alert('❌ Last Name is required.'); history.back();</script>";
        exit();
    }
    if (empty($address)) {
        echo "<script>alert('❌ Address is required.'); history.back();</script>";
        exit();
    }

    // ===== Step 2: Purpose Handling =====
    if ($certificationType === 'Calamity') {
        $purpose = 'Calamity';
        $other_purpose = 'N/A';
    } elseif ($purpose === 'Other') {
        if (empty($other_purpose)) {
            echo "<script>alert('❌ Please specify your purpose since you selected Other.'); history.back();</script>";
            exit();
        }
        $purpose = $other_purpose;
    }

    if ($certificationType !== 'Calamity' && empty($purpose)) {
        echo "<script>alert('❌ Please select your Purpose.'); history.back();</script>";
        exit();
    }

    // ===== Step 3: Calamity-specific =====
    $type_of_calamity = $calamity_date = $calamity_time = $location = $what_is_caused = $calamity_purpose = $requested_by = $calamity_notes = '';

    if ($certificationType === 'Calamity') {
        $type_of_calamity = trim($_POST['calamityType'] ?? '');
        $calamity_date    = trim($_POST['calamityDate'] ?? '');
        $calamity_time    = trim($_POST['calamityTimeFire'] ?? '');
        $location         = trim($_POST['calamityLocationFire'] ?? '');
        $what_is_caused   = trim($_POST['calamityCaused'] ?? '');
        $calamity_purpose = trim($_POST['calamityPurpose'] ?? '');
        $requested_by     = trim($_POST['requestedBy'] ?? '');
        $calamity_notes   = trim($_POST['calamityNotes'] ?? '');

        if (empty($type_of_calamity)) {
            echo "<script>alert('❌ Please select the Type of Calamity.'); history.back();</script>";
            exit();
        }
        if (empty($calamity_date)) {
            echo "<script>alert('❌ Please provide the Date of the Calamity.'); history.back();</script>";
            exit();
        }
        if (empty($requested_by)) {
            echo "<script>alert('❌ Please enter who requested the Calamity certificate.'); history.back();</script>";
            exit();
        }

        // Fire-specific validations
        if ($type_of_calamity === 'Fire') {
            if (empty($calamity_time)) {
                echo "<script>alert('❌ Please provide the Time of the Fire incident.'); history.back();</script>";
                exit();
            }
            if (empty($location)) {
                echo "<script>alert('❌ Please provide the Location of the Fire incident.'); history.back();</script>";
                exit();
            }
            if (empty($what_is_caused)) {
                echo "<script>alert('❌ Please specify what the Fire incident caused.'); history.back();</script>";
                exit();
            }
        }

        // Non-Fire calamities must have purpose
        if ($type_of_calamity !== 'Fire' && empty($calamity_purpose)) {
            echo "<script>alert('❌ Please select a Purpose for your Calamity request.'); history.back();</script>";
            exit();
        }
    }

    // ===== Step 4: Setup common data =====
    $dateToday = date('Y-m-d');
    $status = "To Be Approved";
    $name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($suffix ? ' ' . $suffix : '');

    // ===== Step 5: File upload handling =====
    $document_path = "";
    $uploaded_files = [];
    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/certification-documents/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        foreach ($_FILES['document_path']['name'] as $key => $filename) {
            if ($_FILES['document_path']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['document_path']['tmp_name'][$key];
                $unique_name = time() . '_' . uniqid() . '_' . basename($filename);
                if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
                    $uploaded_files[] = $unique_name;
                }
            }
        }
        if (!empty($uploaded_files)) {
            $document_path = implode(",", $uploaded_files);
        }
    }

    // ===== Step 6: Final purpose =====
    $final_purpose = ($purpose === 'Other') ? $other_purpose : $purpose;

    // ===== Step 7: Insert query =====
    $insertQuery = "INSERT INTO tbl_certification (
        res_id, user_id, certificationType, name, address, purpose, resident_status, dateToday,
        document_path, status, type_of_calamity, calamity_date, calamity_time, what_is_caused, location, calamity_purpose, requested_by, calamity_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "iissssisssssssssss",
        $res_id,
        $user_id,
        $certificationType,
        $name,
        $address,
        $final_purpose,
        $resident_status,
        $dateToday,
        $document_path,
        $status,
        $type_of_calamity,
        $calamity_date,
        $calamity_time,
        $what_is_caused,
        $location,
        $calamity_purpose,
        $requested_by,
        $calamity_notes
    );

    if ($insertStmt->execute()) {
        header("Location: barangay-certificate.php?success=1");
        exit();
    } else {
        echo "Error executing query: " . $conn->error;
        header("Location: barangay-certificate.php?error=1&message=" . urlencode($conn->error));
        exit();
    }

    $insertStmt->close();
}
?>
