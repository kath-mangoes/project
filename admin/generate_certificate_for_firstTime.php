<?php
$certification_id = $_GET['certification_id'] ?? null;
include '../connection/config.php';

if (!$certification_id) {
    echo json_encode([
        "content1" => "<p>Error: Missing certification_id</p>",
        "content2" => ""
    ]);
    exit;
}

$view_certificate = $conn->query("
    SELECT c.*, u.address AS user_address, u.first_name, u.middle_name, u.last_name, u.birthday
    FROM tbl_certification c
    LEFT JOIN tbl_residents u ON u.user_id = c.user_id
    WHERE c.certification_id = '$certification_id'
");

$row = $view_certificate->fetch_assoc();

if (!$row) {
    echo json_encode([
        "content1" => "<p>No certificate found.</p>",
        "content2" => ""
    ]);
    exit;
}



// 1. Today's date
$today = new DateTime(); 

$todayWord = $today->format('F j, Y'); // convert to word format

$day = $today->format('j');       // 1-31
$daySuffix = $today->format('S'); // st, nd, rd, th
$month = $today->format('F');     // January, February, ...
$year = $today->format('Y');      // 2025

// ----------------------------
// 2. Validity date = +1 year
$validity = clone $today;
$validity->modify('+1 year');
$validDay = $validity->format('j');
$validDaySuffix = $validity->format('S');
$validMonth = $validity->format('F');
$validYear = $validity->format('Y');

// ----------------------------
// 3. Age calculation
$birthday = $row['birthday']; // e.g., "1990-09-22"
$birthDate = new DateTime($birthday);
$age = $today->diff($birthDate)->y; // difference in years




$user_address = $row['user_address'];

$fullname = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);

// ================== HEADER TEMPLATE ==================
$header = '
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
    <img src="../assets/left-logo.png" alt="Barangay Logo" class="img-fluid" style="max-height:70px;">
    <div class="text-center flex-fill px-2">
        <div><b>Republic of the Philippines</b></div>
        <div><b>City of Manila</b></div>
        <div><b>BARANGAY 400 ZONE 41, DISTRICT IV</b></div>
        <div><b>SAMPALOC, MANILA</b></div>
        <div class="office"><small>OFFICE OF THE BARANGAY CHAIRMAN</small></div>
    </div>
    <img src="../assets/right-logo.png" alt="Bagong Pilipinas Logo" class="img-fluid" style="max-height:70px;">
</div>
<hr class="my-2">
';

// ================== CONTENT 1 ==================
ob_start();
include '../templates/outh_of_undertaking.php';
$content1_body = ob_get_clean();

$content1 = '
<div class="certificate p-3">
    ' . $header . '
   <div class="certificate-body text-justify">
    ' . $content1_body . '
    </div>
</div>
';

// ================== CONTENT 2 ==================
ob_start();
include '../templates/firstime_job_seeker.php';
$content2_body = ob_get_clean();

$content2 = '
<div class="certificate p-3">
    ' . $header . '
   
    <div class="certificate-body text-justify">
        ' . $content2_body . '
    </div>
</div>
';

// Return JSON
echo json_encode([
    "content1" => $content1,
    "content2" => $content2
]);
