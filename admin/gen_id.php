<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // ✅ Force JSON response

include '../connection/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$barangayIdFields = [
    'template' => 'temp-id.jpg',
    'folder'   => 'BarangayIdCert',
    'name_pos' => [190, 233],
    'address_pos' => [190, 253],
    'precinct_pos' => [730, 60],
    'blood_type_pos' => [955, 60],
    'birthdate_pos' => [735, 85],
    'birthplace_pos' => [980, 85],
    'height_pos' => [687, 115],
    'weight_pos' => [870, 115],
    'ssgsis_pos' => [730, 140],
    'tin_pos' => [955, 140],
    'marital_status_pos' => [1030, 115],
    'person_two_name_pos' => [690, 191],
    'person_two_contact_info_pos' => [740, 249],
    'person_two_address_pos' => [735, 220],
];

$font = __DIR__ . '/generate_certificate/fonts/TimesNewRoman.ttf';

if (!file_exists($font)) {
    echo json_encode(["success" => false, "message" => "Font not found."]);
    exit;
}

if (isset($_POST['BID_id'])) {
    $BID_id = intval($_POST['BID_id']);

    $res = mysqli_query($conn, "SELECT * FROM `tbl_bid` WHERE `BID_id` = $BID_id");
    if (!$res || mysqli_num_rows($res) == 0) {
        echo json_encode(["success" => false, "message" => "No approved request found for the given ID."]);
        exit;
    }

    $row = mysqli_fetch_assoc($res);

    // Combine full name
    $name = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']);
    $address = $row['address'];
    $precinctNumber = $row['precinctNumber'];
    $bloodType = $row['bloodType'];
    $birthdate = $row['birthday'];
    $birthplace = $row['birthplace'];
    $height = $row['height'];
    $weight = $row['weight'];
    $SSSGSIS_Number = $row['SSSGSIS_Number'];
    $TIN_number = $row['TIN_number'];
    $marital_status = $row['civilStatus'];
    $personTwoName = $row['personTwoName'];
    $personTwoContactInfo = $row['personTwoContactInfo'];
    $personTwoAddress = $row['personTwoAddress'];

    $templatePath = __DIR__ . '/generate_certificate/templates/' . $barangayIdFields['template'];
    $savePath = __DIR__ . '/generate_certificate/' . $barangayIdFields['folder'];

    if (!file_exists($templatePath)) {
        echo json_encode(["success" => false, "message" => "Template not found."]);
        exit;
    }

    if (!is_dir($savePath)) {
        mkdir($savePath, 0777, true);
    }

    $image = imagecreatefromjpeg($templatePath);
    $color = imagecolorallocate($image, 0, 0, 0); // Black

    // Custom draw function with adjustable font size
    $drawField = function ($text, $position, $size = 17) use ($image, $font, $color) {
        imagettftext($image, $size, 0, $position[0], $position[1], $color, $font, $text);
    };

    $fields = [
        ['text' => $name, 'key' => 'name_pos'],
        ['text' => $address, 'key' => 'address_pos'],
        ['text' => $precinctNumber, 'key' => 'precinct_pos'],
        ['text' => $bloodType, 'key' => 'blood_type_pos'],
        ['text' => $birthdate, 'key' => 'birthdate_pos', 'size' => 15],
        ['text' => $birthplace, 'key' => 'birthplace_pos'],
        ['text' => $height, 'key' => 'height_pos'],
        ['text' => $weight, 'key' => 'weight_pos'],
        ['text' => $SSSGSIS_Number, 'key' => 'ssgsis_pos'],
        ['text' => $TIN_number, 'key' => 'tin_pos'],
        ['text' => $marital_status, 'key' => 'marital_status_pos'],
        ['text' => $personTwoName, 'key' => 'person_two_name_pos'],
        ['text' => $personTwoContactInfo, 'key' => 'person_two_contact_info_pos'],
        ['text' => $personTwoAddress, 'key' => 'person_two_address_pos'],
    ];

    foreach ($fields as $field) {
        $size = isset($field['size']) ? $field['size'] : 17;
        $drawField($field['text'], $barangayIdFields[$field['key']], $size);
    }

    $safeName = preg_replace('/\s+/', '_', $name);
    $filename = $savePath . '/' . $safeName . '_' . $BID_id . '_' . time() . '.jpg';
    imagejpeg($image, $filename);
    imagedestroy($image);

    echo json_encode([
        "success" => true,
        "message" => "ID certificate generated successfully!",
        "file"    => $filename
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request or missing certification ID."]);
}
