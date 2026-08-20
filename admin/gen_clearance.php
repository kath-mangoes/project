<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$clearanceTypes = [
    'Barangay Clearance' => [
        'template' => 'temp-gm.jpg',
        'folder' => 'BarangayClearance',
        'name_pos' => [550, 680],
        'address_pos' => [750, 730],
        'other_purpose_pos' => [1110, 1650],
        'checkbox_positions' => [
            'Local Employment' => [250, 1075],
            'PWD ID' => [250, 1130],
            'Hospital Requirement' => [250, 1185],
            'Transfer Residencency' => [250, 1240],
            'Bank Transaction' => [250, 1295],
            'Proof of Indigency' => [250, 1350],
            'Financial Assistance' => [950, 1075],
            'Maynilad Requirement' => [950, 1130],
            'School Requirement' => [950, 1180],
            'Proof of Residency' => [950, 1240],
            'Medical Assistance' => [950, 1295],
        ]
    ],
    'Declogging' => [
        'template' => 'temp-service.jpeg',
        'folder' => 'DecloggingClearance',
        'address_pos' => [800, 1024],
        'certification_type_pos' => [300, 1024]
    ],
    'Garbage Disposal' => [
        'template' => 'temp-service.jpeg',
        'folder' => 'GarbageDisposalClearance',
        'address_pos' => [800, 1024],
        'certification_type_pos' => [260, 1024]
    ]
];

$font = __DIR__ . '/generate_certificate/fonts/TimesNewRoman.ttf';
$checkmarkImagePath = __DIR__ . '/generate_certificate/icons/checkmark.png';

if (!file_exists($font) || !file_exists($checkmarkImagePath)) {
    die("Font or checkmark icon not found.");
}

if (isset($_POST['clearance_id'])) {
    $clearance_id = intval($_POST['clearance_id']);
    $res = mysqli_query($conn, "SELECT * FROM `tbl_clearance` WHERE `clearance_id` = $clearance_id AND `status` = 'Approved'");
    if (!$res || mysqli_num_rows($res) === 0) {
        die("No approved request found.");
    }

    $row = mysqli_fetch_assoc($res);
    $type = $row['clearanceType'];

    if (!array_key_exists($type, $clearanceTypes)) {
        die("Invalid clearance type.");
    }

    $data = $clearanceTypes[$type];
    $templatePath = __DIR__ . '/generate_certificate/templates/' . $data['template'];
    $savePath = __DIR__ . '/generate_certificate/' . $data['folder'];

    if (!file_exists($templatePath)) {
        die("Template not found: $templatePath");
    }

    if (!is_dir($savePath)) {
        mkdir($savePath, 0777, true);
    }

    $name = $row['name'];
    $address = $row['address'];
    $purpose = trim($row['purpose']);

    $image = imagecreatefromjpeg($templatePath);
    if (!$image) {
        die("Failed to load template.");
    }

    $black = imagecolorallocate($image, 0, 0, 0);

    if ($type === 'Barangay Clearance') {
        // Draw name and address
        imagettftext($image, 20, 0, $data['name_pos'][0], $data['name_pos'][1], $black, $font, $name);
        imagettftext($image, 20, 0, $data['address_pos'][0], $data['address_pos'][1], $black, $font, $address);

        // Match checkbox case-insensitively
        $matchedKey = null;
        foreach ($data['checkbox_positions'] as $key => $coords) {
            if (strcasecmp($key, $purpose) === 0) {
                $matchedKey = $key;
                break;
            }
        }

        if ($matchedKey) {
            [$x, $y] = $data['checkbox_positions'][$matchedKey];
            $checkmark = imagecreatefrompng($checkmarkImagePath);
            if ($checkmark) {
                imagealphablending($image, true);
                imagesavealpha($image, true);
                $resized = imagescale($checkmark, 30, 30);
                imagecopy($image, $resized, $x, $y, 0, 0, 30, 30);
                imagedestroy($checkmark);
                imagedestroy($resized);
            } else {
                error_log("Checkmark load failed for ID: $clearance_id");
            }
        } elseif (!empty($purpose) && isset($data['other_purpose_pos'])) {
            imagettftext($image, 20, 0, $data['other_purpose_pos'][0], $data['other_purpose_pos'][1], $black, $font, $purpose);
        }
    }

    if (in_array($type, ['Declogging', 'Garbage Disposal'])) {
        if (isset($data['certification_type_pos'])) {
            imagettftext($image, 25, 0, $data['certification_type_pos'][0], $data['certification_type_pos'][1], $black, $font, $type);
        }
        if (isset($data['address_pos'])) {
            imagettftext($image, 25, 0, $data['address_pos'][0], $data['address_pos'][1], $black, $font, $address);
        }
    }

    $safeName = preg_replace('/\s+/', '_', $name);
    $filename = $savePath . '/' . $safeName . '_' . $clearance_id . '_' . time() . '.jpg';
    imagejpeg($image, $filename);
    imagedestroy($image);

    echo "Clearance certificate generated successfully!";
}
?>
