<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$certificateType = [
    'Good Moral' => [
        'template' => 'temp-gm.jpg',
        'folder' => 'GoodMoralCert',
        'name_pos' => [550, 680],
        'address_pos' => [750, 730],
        'mont_pos' =>[752, 1529],
        'day_pos' => [582, 1535],
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
    'First Time Job Seeker' => [
        'template' => 'temp-ftjs.jpeg',
        'folder' => 'FirstTimeJobSeekerCert',
        'name_pos' => [800, 825],
        'address_pos' => [220, 905],
        'month_pos' =>[684, 1257],
        'day_pos' => [486, 1259],
        'valid_pos' => [756, 1327],
        'date_pos1' => [1160, 1603],
        'date_pos2' => [1168, 1903],
        'string_pos1' => [1146, 1509],
        'string_pos2' => [1110, 1811]
    ],

    'Calamity' => [
        'template' => 'temp_calamity.jpg',
        'folder' => 'CalamityCert',
        'name_pos' => [390, 570],
        'address_pos' => [180, 640],
        'type_of_calamity_pos' => [750, 800],
        'calamity_date_pos' => [200, 840],
        'requested_by_pos' => [740, 890],
        'month_pos' =>[662, 1030],
        'day_pos' => [514, 1029]
    ]
];

$font = __DIR__ . '/generate_certificate/fonts/TimesNewRoman.ttf';
$checkmarkImagePath = __DIR__ . '/generate_certificate/icons/checkmark.png';



if (!file_exists($font) || !file_exists($checkmarkImagePath)) {
    die("Font or checkmark icon not found.");
}

    $certification_id = null;
    
    if (isset($_POST['certification_id'])) {
        $certification_id = intval($_POST['certification_id']);
    } elseif (isset($_GET['certification_id'])) {
        $certification_id = intval($_GET['certification_id']);
    }

    
    // Fetch only the specific request using the certification ID
    $res = mysqli_query($conn, "SELECT * FROM `tbl_certification` WHERE `certification_id` = $certification_id AND `status` = 'Approved'");
    if (!$res || mysqli_num_rows($res) == 0) {
        die("No approved request found for the given ID.");
    }
    
    $row = mysqli_fetch_assoc($res);
    $type = $row['certificationType'];
    
    if (array_key_exists($type, $certificateType)) {
        $data = $certificateType[$type];
        $templatePath = __DIR__ . '/generate_certificate/templates/' . $data['template'];
        $savePath = __DIR__ . '/generate_certificate/' . $data['folder'];

        if (!file_exists($templatePath)) {
            error_log("Template not found for $type at: $templatePath");
            exit;
        }

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        $name = $row['name'];
        $address = $row['address'];
        $purpose = $row['purpose'];
        $lowerPurpose = strtolower($purpose);
        $otherPurpose = $row['purpose'];
        $finalPurpose = ($lowerPurpose === 'other') ? $otherPurpose : $purpose;
        $calamityType = $row['type_of_calamity'];
        $calamityDate = $row['calamity_date'];
        $requestedBy = $row['requested_by'];
        $mont = date("F");
        $month = " of   ".$mont;
        $day = date("jS");
        $nextYear = date("F j, Y", strtotime("+1 year"));
        $todayNumeric = date("m/d/Y");
        $captain ="Felix C. Taguba";
        $sect = "Imelda M. Sanqing";

        $image = imagecreatefromjpeg($templatePath);
        if (!$image) {
            error_log("Failed to load template for certificate ID $certification_id ($type)");
            exit;
        }

        $color = imagecolorallocate($image, 0, 0, 0);

        // Draw name and address
        

        if ($type === 'Calamity') {
            // Always draw name and address
            imagettftext($image, 20, 0, $data['name_pos'][0], $data['name_pos'][1], $color, $font, $name);
            imagettftext($image, 20, 0, $data['address_pos'][0], $data['address_pos'][1], $color, $font, $address);
            imagettftext($image, 20, 0, $data['type_of_calamity_pos'][0], $data['type_of_calamity_pos'][1], $color, $font, $calamityType);
            imagettftext($image, 20, 0, $data['calamity_date_pos'][0], $data['calamity_date_pos'][1], $color, $font, $calamityDate);
            imagettftext($image, 20, 0, $data['requested_by_pos'][0], $data['requested_by_pos'][1], $color, $font, $requestedBy);
            imagettftext($image, 20, 0, $data['month_pos'][0], $data['month_pos'][1], $color, $font, $month);
            imagettftext($image, 20, 0, $data['day_pos'][0], $data['day_pos'][1], $color, $font, $day);


            // Log and draw other fields
            error_log("Calamity Certificate: $calamityType | $calamityDate | $requestedBy");

            if (!empty($calamityType)) {
                imagettftext($image, 20, 0, $data['type_of_calamity_pos'][0], $data['type_of_calamity_pos'][1], $color, $font, $calamityType);
            }

            if (!empty($calamityDate)) {
                imagettftext($image, 20, 0, $data['calamity_date_pos'][0], $data['calamity_date_pos'][1], $color, $font, $calamityDate);
            }

            if (!empty($requestedBy)) {
                imagettftext($image, 20, 0, $data['requested_by_pos'][0], $data['requested_by_pos'][1], $color, $font, $requestedBy);
            }
        } elseif ($type == 'Good Moral'){
            
            imagettftext($image, 30, 0, $data['name_pos'][0], $data['name_pos'][1], $color, $font, $name);
            imagettftext($image, 30, 0, $data['address_pos'][0], $data['address_pos'][1], $color, $font, $address);
            imagettftext($image, 25, 0, $data['mont_pos'][0], $data['mont_pos'][1], $color, $font, $mont);
            imagettftext($image, 25, 0, $data['day_pos'][0], $data['day_pos'][1], $color, $font, $day);
            
        } elseif ($type === 'First Time Job Seeker') {
            if(!empty($name) && isset($data['name_pos'])) {
                imagettftext($image, 30, 0, $data['name_pos'][0], $data['name_pos'][1], $color, $font, $name);
            }

            // Only render First Time Job Seeker-specific fields
            if (!empty($address) && isset($data['address_pos'])) {
                imagettftext($image, 30, 0, $data['address_pos'][0], $data['address_pos'][1], $color, $font, $address);
            }
            
            imagettftext($image, 25, 0, $data['month_pos'][0], $data['month_pos'][1], $color, $font, $month);
            imagettftext($image, 25, 0, $data['day_pos'][0], $data['day_pos'][1], $color, $font, $day);
            
            if (isset($data['valid_pos'])) {
                imagettftext(
                    $image,
                    28,
                    0,
                    $data['valid_pos'][0],
                    $data['valid_pos'][1],
                    $color,
                    $font,
                    $nextYear
                );
            }
            
            imagettftext($image, 28, 0, $data['date_pos1'][0], $data['date_pos1'][1], $color, $font, $todayNumeric);
            imagettftext($image, 28, 0, $data['date_pos2'][0], $data['date_pos2'][1], $color, $font, $todayNumeric);
            imagettftext($image, 28, 0, $data['string_pos1'][0], $data['string_pos1'][1], $color, $font, $captain);
            imagettftext($image, 28, 0, $data['string_pos2'][0], $data['string_pos2'][1], $color, $font, $sect);
            
        } elseif (array_key_exists($purpose, $data['checkbox_positions'])) {
            // Draw checkbox
            list($x, $y) = $data['checkbox_positions'][$purpose];
            $checkmark = imagecreatefrompng($checkmarkImagePath);
            if ($checkmark) {
                imagealphablending($image, true);
                imagesavealpha($image, true);
                $resized = imagescale($checkmark, 30, 30);
                imagecopy($image, $resized, $x, $y, 0, 0, 30, 30);
                imagedestroy($checkmark);
                imagedestroy($resized);
            } else {
                error_log("Checkmark load failed for ID: $certification_id");
            }

        } elseif (!empty($finalPurpose) && isset($data['other_purpose_pos'])) {
            // Draw other purpose text (only if not Calamity)
            imagettftext($image, 20, 0, $data['other_purpose_pos'][0], $data['other_purpose_pos'][1], $color, $font, $finalPurpose);
        }

        $safeName = preg_replace('/\s+/', '_', $name);
        $filename = $savePath . '/' . $safeName . '_' . $certification_id . '_' . time() . '.jpg';
        imagejpeg($image, $filename);
        imagedestroy($image);

        echo "Certificate generated successfully!";
    } else {
         echo "Invalid certificate type: '$type'. Available types are: " . implode(", ", array_keys($certificateType));
    }

?>
