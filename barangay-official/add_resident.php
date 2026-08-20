<?php
// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once '../connection/config.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Function to get the next user_id
function getNextUserId($conn) {
    $query = "SELECT user_id FROM tbl_user ORDER BY user_id DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $lastId = $result->fetch_assoc()['user_id'];
        return $lastId + 1;
    }
    return 1;
}

// Function to validate input
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to send welcome email with credentials
function sendWelcomeEmail($recipientEmail, $recipientName, $email, $password) {
    // Hardcoded barangay information
    $barangayInfo = [
        'name' => 'Barangay 400',
        'portal_url' => 'http://localhost/barangay-system/login.php',
        'email' => 'contact@yourbarangay.com',
        'phone' => '(123) 456-7890',
        'address' => 'Barangay 400 Zone 41 Sampaloc, Manila'
    ];
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                         
        $mail->Host       = 'smtp.gmail.com';                   
        $mail->SMTPAuth   = true;                                
        $mail->Username   = 'systembarangaymanagement@gmail.com';             
        $mail->Password   = 'qqwafpvwljoixsxa';                
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       
        $mail->Port       = 587;                                  
        // Recipients
        $mail->setFrom('systembarangaymanagement@gmail.com', $barangayInfo['name'] . ' Barangay Management System');
        $mail->addAddress($recipientEmail, $recipientName);       // Add a recipient

        // Content
        $mail->isHTML(true);                                      // Set email format to HTML
        $mail->Subject = 'Welcome to ' . $barangayInfo['name'] . ' Barangay Management System';
        
        // Email body HTML
        $currentYear = date('Y');
        $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Barangay Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header img {
            max-width: 100px;
            height: auto;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-left: 1px solid #e1e1e1;
            border-right: 1px solid #e1e1e1;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-table {
            width: 100%;
            border-collapse: collapse;
        }
        .credentials-table td {
            padding: 10px;
            border-bottom: 1px solid #e1e1e1;
        }
        .credentials-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #666666;
            border-radius: 0 0 5px 5px;
            border: 1px solid #e1e1e1;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
          
            <h1>' . $barangayInfo['name'] . '</h1>
            <p>Barangay Management System</p>
        </div>
        
        <div class="content">
            <h2>Welcome, ' . $recipientName . '!</h2>
            <p>Thank you for registering with the ' . $barangayInfo['name'] . ' Barangay Management System. Your account has been successfully created, and you can now access our digital services.</p>
            
            <div class="credentials-box">
                <h3>Your Login Credentials</h3>
                <table class="credentials-table">
                    <tr>
                        <td>Email:</td>
                        <td><strong>' . $email . '</strong></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><strong>' . $password . '</strong></td>
                    </tr>
                    <tr>
                        <td>Portal URL:</td>
                        <td><a href="' . $barangayInfo['portal_url'] . '">' . $barangayInfo['portal_url'] . '</a></td>
                    </tr>
                </table>
            </div>
            
            <div class="security-notice">
                <strong>⚠️ Important Security Notice:</strong>
                <p>For your security, we strongly recommend changing your password immediately after your first login. Your temporary password should not be shared with anyone.</p>
            </div>
            
            <p>With your account, you can:</p>
            <ul>
                <li>Request barangay certificates and other documents</li>
                <li>Submit concerns and complaints</li>
                <li>Stay updated on barangay announcements</li>
                <li>Access barangay services online</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="' . $barangayInfo['portal_url'] . '" class="button">Log In Now</a>
            </div>
            
            <p>If you have any questions or need assistance, please contact our office at <a href="mailto:' . $barangayInfo['email'] . '">' . $barangayInfo['email'] . '</a> or call us at ' . $barangayInfo['phone'] . '.</p>
            
            <p>Best Regards,<br>
            The ' . $barangayInfo['name'] . ' Barangay Team</p>
        </div>
        
        <div class="footer">
            <p>' . $barangayInfo['address'] . '</p>
            <p>© ' . $currentYear . ' ' . $barangayInfo['name'] . ' Barangay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
        ';
        
        // Plain text version for non-HTML mail clients
        $mail->AltBody = 'Welcome to ' . $barangayInfo['name'] . ' Barangay Management System!
        
Your account has been successfully created.

YOUR LOGIN CREDENTIALS:
- Email: ' . $email . '
- Password: ' . $password . '
- Portal URL: ' . $barangayInfo['portal_url'] . '

IMPORTANT: For your security, please change your password immediately after your first login.

If you have any questions, contact us at ' . $barangayInfo['email'] . ' or ' . $barangayInfo['phone'] . '.

Best Regards,
The ' . $barangayInfo['name'] . ' Barangay Team';

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate form data
    $username = validate_input($_POST['username']);
    $email = validate_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = validate_input($_POST['first_name']);
    $middle_name = validate_input($_POST['middle_name'] ?? '');
    $last_name = validate_input($_POST['last_name']);
    $birthday = validate_input($_POST['birthday']);
    $birthplace = validate_input($_POST['birthplace']);
    $gender = validate_input($_POST['gender']);
    $civilStatus = validate_input($_POST['civilStatus']);
    $mobile = validate_input($_POST['mobile'] ?? '');
    $address = validate_input($_POST['address']);
    $bloodType = validate_input($_POST['bloodType'] ?? '');
    $height = !empty($_POST['height']) ? validate_input($_POST['height']) : NULL;
    $weight = !empty($_POST['weight']) ? validate_input($_POST['weight']) : NULL;
    $precinctNumber = validate_input($_POST['precinctNumber'] ?? '');
    $barangay_number = validate_input($_POST['barangay_number'] ?? '');
    $residentStatus = validate_input($_POST['residentStatus']);
    $voterStatus = validate_input($_POST['voterStatus']);
    $typeOfID = validate_input($_POST['typeOfID'] ?? '');
    $IDNumber = validate_input($_POST['IDNumber'] ?? '');
    $SSSGSIS_Number = validate_input($_POST['SSSGSIS_Number'] ?? '');
    $TIN_number = validate_input($_POST['TIN_number'] ?? '');
    $is_senior = validate_input($_POST['is_senior'] ?? 'No');
    $is_pwd = validate_input($_POST['is_pwd'] ?? 'No');
    $is_4ps_member = validate_input($_POST['is_4ps_member'] ?? 'No');
    $terms = isset($_POST['terms']) ? 1 : 0;
    
    // Initialize response array
    $response = array(
        'status' => 'error',
        'message' => 'An error occurred while processing your request.'
    );
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: residents.php");
        exit();
    }
    
    // Check if username already exists
    $check_username = "SELECT * FROM tbl_user WHERE username = ?";
    $stmt = $conn->prepare($check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists!";
        header("Location: residents.php");
        exit();
    }
    
    // Check if email already exists
    $check_email = "SELECT * FROM tbl_user WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: residents.php");
        exit();
    }
    
    // Get the next user_id
    $user_id = getNextUserId($conn);
    
    // Hash password for database storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Default account status
    $account_status = "Active";
    $role = "resident";
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Handle profile photo upload
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = "../dist/assets/images/user/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowed_ext = array("jpg", "jpeg", "png");
            
            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $new_filename = $user_id . '_profile_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image = $new_filename;
                }
            }
        }
        
        // Handle ID image upload
        $id_image_path = NULL;
        if (isset($_FILES['id_image']) && $_FILES['id_image']['error'] == 0) {
            $upload_dir = "../dist/assets/images/uploads/id_images/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['id_image']['name'], PATHINFO_EXTENSION);
            $allowed_ext = array("jpg", "jpeg", "png", "pdf");
            
            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $new_filename = $user_id . '_id_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['id_image']['tmp_name'], $upload_path)) {
                    $id_image_path = $new_filename;
                }
            }
        }
        
        // Insert into tbl_user
        $insert_user = "INSERT INTO tbl_user (user_id, first_name, middle_name, last_name, username, password, image, email, mobile, address, account_status, role, terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert_user);
        $stmt->bind_param("ssssssssssssi", $user_id, $first_name, $middle_name, $last_name, $username, $hashed_password, $image, $email, $mobile, $address, $account_status, $role, $terms);
        
        if (!$stmt->execute()) {
            throw new Exception("Error inserting user data: " . $stmt->error);
        }
        
        // Insert into tbl_residents
        $insert_resident = "INSERT INTO tbl_residents (user_id, first_name, middle_name, last_name, birthday, birthplace, civilStatus, mobile, gender, address, precinctNumber, residentStatus, voterStatus, bloodType, height, weight, typeOfID, IDNumber, SSSGSIS_Number, TIN_number, barangay_number, is_senior, is_pwd, is_4ps_member) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert_resident);
        $stmt->bind_param("ssssssssssssssddssssssss", $user_id, $first_name, $middle_name, $last_name, $birthday, $birthplace, $civilStatus, $mobile, $gender, $address, $precinctNumber, $residentStatus, $voterStatus, $bloodType, $height, $weight, $typeOfID, $IDNumber, $SSSGSIS_Number, $TIN_number, $barangay_number, $is_senior, $is_pwd, $is_4ps_member);
        
        if (!$stmt->execute()) {
            throw new Exception("Error inserting resident data: " . $stmt->error);
        }
        
        // If we reach here, both inserts were successful
        $conn->commit();
        
        // Send welcome email with login credentials
        $recipientName = $first_name . ' ' . $last_name;
        
        // Try to send email
        $email_sent = sendWelcomeEmail($email, $recipientName, $email, $password);
        
        // Redirect with success parameter
        header("Location: residents.php?success=1");
        exit();
        
    } catch (Exception $e) {
        // An error occurred, rollback transaction
        $conn->rollback();
        
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: residents.php");
        exit();
    }
    
} else {
    // If not a POST request, redirect to residents page
    header("Location: residents.php");
    exit();
}
?>