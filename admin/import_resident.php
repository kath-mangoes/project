<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

require '../vendor/autoload.php';

function getNextUserId($conn) {
    $i = 1;
    while (true) {
        $user_id = 'U' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT 1 FROM tbl_user WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            return $user_id;
        }
        $i++;
    }
}

function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function generateRandomPassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    return substr(str_shuffle($chars), 0, $length);
}

function sendWelcomeEmail($recipientEmail, $recipientName, $email, $password) {
    $barangayInfo = [
        'name' => 'Barangay 400',
        'portal_url' => 'https://barangay400.com/login.php',
        'email' => 'brgy400.manila@gmail.com',
        'phone' => '0962 688 0014',
        'address' => 'Barangay 400 Zone 41 Sampaloc, Manila'
    ];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'systembarangaymanagement@gmail.com';
        $mail->Password = 'qqwafpvwljoixsxa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('systembarangaymanagement@gmail.com', $barangayInfo['name'] . ' Barangay Management System');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ' . $barangayInfo['name'] . ' Barangay Management System';
        $mail->Body = "<html><body>
        <h2>Welcome, {$recipientName}!</h2>
        <p>Your account has been created:</p>
        <ul>
            <li>Email: <strong>{$email}</strong></li>
            <li>Password: <strong>{$password}</strong></li>
            <li><a href='{$barangayInfo['portal_url']}'>Login Here</a></li>
        </ul>
        <p>Please change your password after logging in for the first time.</p>
        <p>Contact: {$barangayInfo['email']} | {$barangayInfo['phone']}</p>
        </body></html>";

        $mail->AltBody = "Welcome to Barangay 400\n\nEmail: $email\nPassword: $password\nLogin: " . $barangayInfo['portal_url'];

        return $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["excel_file"]) && $_FILES["excel_file"]["error"] == 0) {
        $file_ext = strtolower(pathinfo($_FILES["excel_file"]["name"], PATHINFO_EXTENSION));
        if (!in_array($file_ext, ["xlsx", "xls"])) {
            $_SESSION['error'] = "Only Excel files are allowed.";
            header("Location: residents.php");
            exit();
        }

        $temp_file = $_FILES["excel_file"]["tmp_name"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $temp_file);
        finfo_close($finfo);

        $allowed_mime_types = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            $_SESSION['error'] = "Invalid Excel file format. Please upload a valid .xlsx or .xls file.";
            header("Location: residents.php");
            exit();
        }

        try {
            $spreadsheet = IOFactory::load($temp_file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $success_count = 0;
            $error_count = 0;
            $errors = [];

            foreach ($rows as $row) {
                if (empty($row[0]) && empty($row[1])) continue;

                $getValue = fn($key) => $row[array_search($key, $headers)] ?? '';

                $resident = [
                    'first_name' => $getValue('First Name'),
                    'middle_name' => $getValue('Middle Name'),
                    'last_name' => $getValue('Last Name'),
                    'email' => $getValue('Email'),
                    'birthday' => $getValue('Birthday'),
                    'birthplace' => $getValue('Birthplace'),
                    'gender' => $getValue('Gender'),
                    'civilStatus' => $getValue('Civil Status'),
                    'mobile' => $getValue('Mobile'),
                    'address' => $getValue('Address'),
                    'bloodType' => $getValue('Blood Type'),
                    'height' => $getValue('Height'),
                    'weight' => $getValue('Weight'),
                    'precinctNumber' => $getValue('Precinct Number'),
                    'barangay_number' => $getValue('Barangay Number'),
                    'residentStatus' => $getValue('Resident Status') ?: 'Permanent',
                    'voterStatus' => $getValue('Voter Status') ?: 'Yes',
                    'typeOfID' => $getValue('Type of ID'),
                    'IDNumber' => $getValue('ID Number'),
                    'SSSGSIS_Number' => $getValue('SSS/GSIS Number'),
                    'TIN_number' => $getValue('TIN Number'),
                    'is_senior' => $getValue('Is Senior'),
                    'is_pwd' => $getValue('Is PWD'),
                    'is_4ps_member' => $getValue('Is 4Ps Member'),
                ];

                if (!empty($resident['birthday']) && is_numeric($resident['birthday'])) {
                    $resident['birthday'] = date('Y-m-d', ($resident['birthday'] - 25569) * 86400);
                } else {
                    $resident['birthday'] = date('Y-m-d', strtotime($resident['birthday']));
                }

                if (empty($resident['email'])) {
                    $slug = strtolower(str_replace(' ', '', $resident['first_name'] . $resident['last_name']));
                    $resident['email'] = $slug . '@example.com';
                }

                if (empty($resident['first_name']) || empty($resident['last_name'])) {
                    $errors[] = "Missing required name data.";
                    $error_count++;
                    continue;
                }

                $stmt = $conn->prepare("SELECT email FROM tbl_user WHERE email = ?");
                $stmt->bind_param("s", $resident['email']);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $errors[] = "Email already exists: " . $resident['email'];
                    $error_count++;
                    continue;
                }

                $user_id = getNextUserId($conn);
                $password = generateRandomPassword();
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $account_status = 'Active';
                $role = 'resident';
                $terms = 1;

                $resident['is_senior'] = strtolower($resident['is_senior']) === 'yes' ? 'Yes' : 'No';
                $resident['is_pwd'] = strtolower($resident['is_pwd']) === 'yes' ? 'Yes' : 'No';
                $resident['is_4ps_member'] = strtolower($resident['is_4ps_member']) === 'yes' ? 'Yes' : 'No';

                $conn->begin_transaction();

                try {
                    $stmt = $conn->prepare("INSERT INTO tbl_user (user_id, first_name, middle_name, last_name, password, email, mobile, address, account_status, role, terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssssi",
                        $user_id,
                        $resident['first_name'],
                        $resident['middle_name'],
                        $resident['last_name'],
                        $hashed_password,
                        $resident['email'],
                        $resident['mobile'],
                        $resident['address'],
                        $account_status,
                        $role,
                        $terms
                    );
                    $stmt->execute();

                    $stmt = $conn->prepare("INSERT INTO tbl_residents (user_id, first_name, middle_name, last_name, birthday, birthplace, civilStatus, mobile, gender, address, precinctNumber, residentStatus, voterStatus, bloodType, height, weight, typeOfID, IDNumber, SSSGSIS_Number, TIN_number, barangay_number, is_senior, is_pwd, is_4ps_member) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $height = (float)$resident['height'];
                    $weight = (float)$resident['weight'];

                    $stmt->bind_param("ssssssssssssddssssssssss",
                    $user_id,
                    $resident['first_name'],
                    $resident['middle_name'],
                    $resident['last_name'],
                    $resident['birthday'],
                    $resident['birthplace'],
                    $resident['civilStatus'],
                    $resident['mobile'],
                    $resident['gender'],
                    $resident['address'],
                    $resident['precinctNumber'],
                    $resident['residentStatus'],
                    $resident['voterStatus'],
                    $resident['bloodType'],
                    $height,
                    $weight,
                    $resident['typeOfID'],
                    $resident['IDNumber'],
                    $resident['SSSGSIS_Number'],
                    $resident['TIN_number'],
                    $resident['barangay_number'],
                    $resident['is_senior'],
                    $resident['is_pwd'],
                    $resident['is_4ps_member']
                );

                    $stmt->execute();

                    $conn->commit();
                    sendWelcomeEmail($resident['email'], $resident['first_name'] . ' ' . $resident['last_name'], $resident['email'], $password);
                    $success_count++;
                } catch (Exception $e) {
                    $conn->rollback();
                    $errors[] = "Failed to insert data for " . $resident['first_name'] . ": " . $e->getMessage();
                    $error_count++;
                }
            }

            if ($success_count && !$error_count) {
                $_SESSION['success'] = "Successfully imported $success_count residents.";
            } elseif ($success_count && $error_count) {
                $_SESSION['warning'] = "$success_count residents imported with $error_count errors.<br>" . implode("<br>", $errors);
            } else {
                $_SESSION['error'] = "No residents were imported.<br>" . implode("<br>", $errors);
            }

            header("Location: residents.php?success=1");
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = "Failed to process Excel file: " . $e->getMessage();
            header("Location: residents.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Please upload an Excel file.";
        header("Location: residents.php");
        exit();
    }
} else {
    header("Location: residents.php");
    exit();
}
?>
