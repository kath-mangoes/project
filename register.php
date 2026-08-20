<?php
include 'connection/config.php';
include 'functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['register'])) {
    // Get form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = isset($_POST['middle_name']) ? mysqli_real_escape_string($conn, $_POST['middle_name']) : '';
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $suffix = isset($_POST['suffix']) ? mysqli_real_escape_string($conn, $_POST['suffix']) : '';
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $occupation = isset($_POST['occupation']) ? mysqli_real_escape_string($conn, $_POST['occupation']) : 'N/A'; // Optional
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $civilStatus = mysqli_real_escape_string($conn, $_POST['civilStatus']);
    $is_household_head = mysqli_real_escape_string($conn, $_POST['is_household_head']);
    $household_head_name = isset($_POST['household_head_name']) ? mysqli_real_escape_string($conn, $_POST['household_head_name']) : '';
    $relationship_to_head = isset($_POST['household_head_relationship']) ? mysqli_real_escape_string($conn, $_POST['household_head_relationship']) : '';
    $is_senior = mysqli_real_escape_string($conn, $_POST['is_senior']);
    $is_pwd = mysqli_real_escape_string($conn, $_POST['is_pwd']);
    $is_registered_voter = mysqli_real_escape_string($conn, $_POST['is_registered_voter']);
    $terms = isset($_POST['terms']) ? 1 : 0; // Checkbox for terms and conditions

    // Handle file uploads for documents
    $targetDir = "uploads/";
    $proof_of_residency = '';
    $pwd_document = '';
    $voter_document = '';
    $senior_document = '';

    // Proof of Residency
    /*if (isset($_FILES['proof_of_residency']) && $_FILES['proof_of_residency']['error'] == 0) {
        $proof_of_residency = 'uploads/' . basename($_FILES['proof_of_residency']['name']);
        move_uploaded_file($_FILES['proof_of_residency']['tmp_name'], $proof_of_residency);
    }

    // PWD Document (only if answered Yes)
    if (isset($_FILES['pwd_document']) && $_FILES['pwd_document']['error'] == 0 && $is_pwd == 'Yes') {
        $pwd_document = 'uploads/' . basename($_FILES['pwd_document']['name']);
        move_uploaded_file($_FILES['pwd_document']['tmp_name'], $pwd_document);
    }

    // Voter Document (only if answered Yes)
    if (isset($_FILES['voter_document']) && $_FILES['voter_document']['error'] == 0 && $is_registered_voter == 'Yes') {
        $voter_document = 'uploads/' . basename($_FILES['voter_document']['name']);
        move_uploaded_file($_FILES['voter_document']['tmp_name'], $voter_document);
    }

    // Senior Citizen Document (only if answered Yes)
    if (isset($_FILES['senior_document']) && $_FILES['senior_document']['error'] == 0 && $is_senior == 'Yes') {
        $senior_document = 'uploads/' . basename($_FILES['senior_document']['name']);
        move_uploaded_file($_FILES['senior_document']['tmp_name'], $senior_document);
    } */

    function handleUpload($inputName, $targetDir) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
            $filename = basename($_FILES[$inputName]['name']);
            $targetFile = $targetDir . uniqid() . "_" . $filename;
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                return $targetFile;
            }
        }
        return '';
    }
    
    $proof_of_residency = handleUpload('proof_of_residency', $targetDir);
    $pwd_document = handleUpload('pwd_document', $targetDir);
    $voter_document = handleUpload('voter_document', $targetDir);
    $senior_document = handleUpload('senior_document', $targetDir);
    
    // Insert into DB
    /*
    $sql = "INSERT INTO users (proof_of_residency, pwd_document, voter_document, senior_document) 
            VALUES (:proof, :pwd, :voter, :senior)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':proof' => $proof_of_residency,
        ':pwd' => $pwd_document,
        ':voter' => $voter_document,
        ':senior' => $senior_document
    ]); */
    

    // Validation
    $error = [];
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($mobile) || empty($address)) {
        $error[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Invalid email format.';
    } elseif (strlen($password) < 8 || !preg_match('/\d/', $password) || !preg_match('/[\W]/', $password)) {
        $error[] = 'Password must be at least 8 characters, include a number and a special character.';
    } elseif ($password !== $confirm_password) {
        $error[] = 'Passwords do not match.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_id = getNextUserId($conn);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM tbl_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $emailCheck = $stmt->get_result();

        if ($emailCheck->num_rows > 0) {
            $error[] = 'Email is already registered!';
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);
            $mail = new PHPMailer(true);

            try {
                // SMTP Settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'systembarangaymanagement@gmail.com';
                $mail->Password = 'qqwafpvwljoixsxa';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email Content

                $mail->setFrom('systembarangaymanagement@gmail.com', 'Barangay System');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = '
                    <html>
                    <body style="font-family: Arial, sans-serif;">
                        <div style="background-color: #f6f6f6; padding: 20px;">
                            <div style="background-color: white; padding: 20px; border-radius: 10px;">
                                <h2 style="color: #4A90E2;">Welcome to Barangay 400 Online System!</h2>
                                <p>Thank you for registering! Your OTP code is:</p>
                                <h1 style="color: #4A90E2; font-size: 32px; letter-spacing: 5px;">' . $otp . '</h1>
                                <p>Please enter this code to verify your account. This code will expire in 10 minutes.</p>
                                <p style="color: #666;">If you did not request this code, please ignore this email.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                $mail->AltBody = 'Your OTP code is: ' . $otp;
                
                // Add Custom Headers
                $mail->addCustomHeader('X-Mailer', 'PHPMailer');
                $mail->addCustomHeader('X-Priority', '3');
                $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
    
                $mail->send();

                // Save session data for OTP verification
                session_start();
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;
                $_SESSION['user_data'] = [
                    'user_id' => $user_id,
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'suffix' => $suffix,
                    'email' => $email,
                    'password' => $hashed_password,
                    'mobile' => $mobile,
                    'address' => $address,
                    'occupation' => $occupation,
                    'birthday' => $birthday,
                    'gender' => $gender,
                    'civilStatus' => $civilStatus,
                    'is_household_head' => $is_household_head,
                    'household_head_name' => $household_head_name,
                    'relationship_to_head' => $relationship_to_head,
                    'is_senior' => $is_senior,
                    'senior_document' => $senior_document,
                    'is_pwd' => $is_pwd,
                    'is_registered_voter' => $is_registered_voter,
                    'voter_document' => $voter_document,
                    'proof_of_residency' => $proof_of_residency,
                    'pwd_document' => $pwd_document,
                    'terms' => $terms,
                    'role' => 'resident',
                    //'documents' => $uploaded_documents 

                ];

                logActivity($user_id, 'Registration', 'Registration Initiated');

                header('Location: otp_verification.php');
                exit();
            } catch (Exception $e) {
                $error[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Barangay Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .progress {
            height: 20px;
        }
        .progress-bar {
            font-weight: bold;
        }
        .privacy-notice {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
    </style>
    <style>
        :root {
            --primary-color: #141E30;
            --secondary-color: #F5F7FA;
            --accent-color: #34495E;
        }

        body {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #E8EEF4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 40px auto;

        }

        .auth-header {
            background: var(--primary-color);
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 15px 15px 0 0;
            /* Match container's top radius */
            margin: 0;
            /* Remove margin */
            width: calc(100% + 40px);
            /* Adjust width to account for container margin */
            position: relative;
            /* Position relative to adjust for margin */
            left: -20px;
            /* Shift left to remove space */

        }

        .auth-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .auth-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .auth-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: 500;
            color: var(--accent-color);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #E8EEF4;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #357ABD;
            transform: translateY(-1px);
        }

        .error-alert {
            border-radius: 8px;
            margin-top: 20px;
        }

        .input-group-text {
            background-color: transparent;
            border: 2px solid #E8EEF4;
            border-right: none;
            border-radius: 0 8px 8px 0;
        }

        .password-toggle {
            cursor: pointer;
            color: var(--accent-color);
        }

        .terms-section {
            background: var(--secondary-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .auth-footer {
            text-align: center;
            padding: 20px 0;
            background: var(--secondary-color);
            width: 100%;
            margin: 0;
        }

        /* Fix for select dropdown appearance */
        select.form-control {
            height: auto;
            padding: 10px;
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="auth-container col-md-8 mx-auto">
            <!-- Header -->
            <div class="auth-header text-center mb-4">
                <h2><i class="fas fa-user-plus mr-2"></i>Create Account</h2>
                <p class="mb-0">Join our barangay system</p>
            </div>

            <!-- Error Display -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger error-alert">
                    <?php foreach ($error as $err): ?>
                        <p class="mb-0"><i class="fas fa-exclamation-circle mr-2"></i><?php echo $err; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Progress Bar -->
            <div class="progress mb-4">
                <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 25%;">
                    Step 1 of 4
                </div>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="" class="needs-validation" enctype="multipart/form-data" novalidate>
                
                <!-- Step 1: Privacy Notice -->
                <div id="step-1" class="step active">
                    <div class="privacy-notice mb-4">
                        <h5>Barangay 400, Sampaloc, Manila</h5>
                        <p>Barangay 400, Sampaloc, Manila is committed to protecting your personal data in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173). This Privacy Notice explains how we collect, use, store, and protect your personal information in the performance of our official duties and services to the community.</p>
                        <em style="color: red;">(Ang Barangay 400, Sampaloc, Manila ay nakatuon sa pagprotekta ng inyong personal na impormasyon alinsunod sa Data Privacy Act of 2012 (Republic Act No. 10173). Ipinapaliwanag ng Paunawang Pangpribado na ito kung paano namin kinokolekta, ginagamit, iniimbak, at pinoprotektahan ang inyong personal na impormasyon sa pagtupad ng aming tungkulin at serbisyo sa komunidad.)</em>

                        <h6>1. Collection of Personal Information</h6>
                        <p>We collect personal data from residents through the following means:</p>
                        <em style="color: red;">(Kinokolekta namin ang personal na impormasyon ng mga residente sa pamamagitan ng mga sumusunod na paraan:)</em>
                        <ul>
                            <li>Registration for barangay clearance, IDs, and certificates</li>
                            <em style="color: red;">(Pagpaparehistro para sa barangay clearance, ID, at mga sertipiko)</em>
                            <li>Participation in community events and relief distributions</li>
                            <em style="color: red;">(Pagsali sa mga aktibidad ng komunidad at pamamahagi ng ayuda)</em>
                            <li>Submissions for complaints, requests, or inquiries</li>
                            <em style="color: red;">(Pagsusumite ng reklamo, kahilingan, o tanong)</em>
                            <li>Health, safety, and disaster-related surveys and monitoring</li>
                            <em style="color: red;">(Mga survey at monitoring kaugnay sa kalusugan, kaligtasan, at sakuna)</em>
                        </ul>

                        <h6>2. Use of Personal Data</h6>
                        <p>Your personal information is used solely for:</p>
                        <em style="color: red;">(Ang inyong impormasyon ay ginagamit lamang para sa mga sumusunod na layunin:)</em>
                        <ul>
                            <li>Verification and issuance of official documents</li>
                            <em style="color: red;">(Pagpapatunay at paglalabas ng mga opisyal na dokumento)</em>
                            <li>Community safety and emergency response</li>
                            <em style="color: red;">(Kaligtasan ng komunidad at agarang tugon sa emerhensya)</em>
                            <li>Health programs, benefits, and relief efforts</li>
                            <em style="color: red;">(Mga programang pangkalusugan, benepisyo, at pamamahagi ng ayuda)</em>
                            <li>Monitoring of demographic and resident profiles</li>
                            <em style="color: red;">(Pagbabantay sa datos ng mga residente)</em>
                            <li>Communication of announcements and updates</li>
                            <em style="color: red;">(Pagbibigay ng mga anunsyo at mga mahalagang impormasyon)</em>
                        </ul>

                        <h6>3. Storage and Protection</h6>
                        <p>We take reasonable and appropriate security measures to:</p>
                        <em style="color: red;">(Nagpapatupad kami ng tamang seguridad upang:)</em>
                        <ul>
                            <li>Safeguard your data against loss, misuse, unauthorized access, disclosure, alteration, and destruction</li>
                            <em style="color: red;">(Protektahan ang inyong datos laban sa pagkawala, maling paggamit, hindi awtorisadong pag-access, pagbubunyag, pagbabago, at pagsira)</em>
                            <li>Store physical records in secure cabinets within the barangay office</li>
                            <em style="color: red;">(Itago ang mga pisikal na tala sa ligtas na kabinet sa barangay hall)</em>
                            <li>Use password-protected digital systems for electronic records</li>
                            <em style="color: red;">(Gumamit ng mga sistemang may password para sa elektronikong tala)</em>
                        </ul>

                        <p>Only authorized personnel of Barangay 400 have access to your personal information for legitimate and official purposes.</p>
                        <em style="color: red;">(Tanging awtorisadong tauhan lamang ng Barangay 400 ang may access sa inyong impormasyon para sa mga lehitimong layunin.)</em>

                        <h6>4. Disclosure and Sharing</h6>
                        <p>We do not disclose your personal data to third parties without your consent, except:</p>
                        <em style="color: red;">(Hindi namin ibinubunyag ang inyong personal na impormasyon sa ibang partido nang walang pahintulot, maliban kung:)</em>
                        <ul>
                            <li>When required by law or lawful orders of government agencies</li>
                            <em style="color: red;">(Kinakailangan ng batas o kautusan mula sa gobyerno)</em>
                            <li>When necessary for public health and safety, disaster response, or law enforcement</li>
                            <em style="color: red;">(Kailangan para sa pampublikong kalusugan, kaligtasan, pagtugon sa sakuna, o pagpapatupad ng batas)</em>
                        </ul>

                        <h6>5. Your Rights</h6>
                        <p>Under the Data Privacy Act, you have the right to:</p>
                        <em style="color: red;">(Ayon sa Data Privacy Act, kayo ay may karapatang:)</em>
                        <ul>
                            <li>Be informed about the collection and use of your personal data</li>
                            <em style="color: red;">(Mabatid kung paano kinokolekta at ginagamit ang inyong impormasyon)</em>
                            <li>Access and request a copy of your personal information</li>
                            <em style="color: red;">(Humiling ng kopya ng inyong personal na impormasyon)</em>
                            <li>Request correction or deletion of inaccurate or outdated data</li>
                            <em style="color: red;">(Humiling ng pagwawasto o pagtanggal ng maling impormasyon)</em>
                            <li>Withdraw your consent at any time (subject to legal obligations)</li>
                            <em style="color: red;">(Bawiin ang inyong pahintulot anumang oras, alinsunod sa mga legal na obligasyon)</em>
                        </ul>

                        <h6>6. Inquiries and Complaints</h6>
                        <p>For questions or concerns about your personal data, you may contact:</p>
                        <em style="color: red;">(Para sa mga tanong o alalahanin ukol sa inyong personal na impormasyon, maaaring makipag-ugnayan sa:)</em>
                        <p>Barangay 400 Admin</p>
                        <p>Barangay Hall, Barangay 400 zone 41, Sampaloc, Manila</p>
                        <p>Email: brgy400.manila@gmail.com</p>
                        <p>Contact Number: 0962 688 0014</p>
                    </div>

                    <!-- Terms Section -->
                    <div class="terms-section mb-4">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="privacyNoticeCheckbox" required>
                            <label class="custom-control-label" for="privacyNoticeCheckbox">I agree to the Privacy Notice</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="termsCheckbox" required>
                            <label class="custom-control-label" for="termsCheckbox">I accept the Terms and Conditions</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary" onclick="validateStep1()">I Agree and Continue</button>
                    </div>
                </div>


                <!-- Step 2: Personal Information -->
                <div id="step-2" class="step">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-2"></i>First Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" required oninput="validateTextOnly(this)">
                                <div class="invalid-feedback">Please enter letters only.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-2"></i>Last Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" required oninput="validateTextOnly(this)">
                                <div class="invalid-feedback">Please enter letters only.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-2"></i>Middle Name</label>
                                <input type="text" class="form-control" name="middle_name" oninput="validateTextOnly(this)">
                                <div class="invalid-feedback">Please enter letters only.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-tag mr-2"></i>Suffix</label>
                                <input type="text" class="form-control" name="suffix" placeholder="e.g., Jr., Sr., III" oninput="validateTextOnly(this)">
                                <div class="invalid-feedback">Please enter letters only.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt mr-2"></i>Birthday<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="birthday" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-venus-mars mr-2"></i>Gender<span class="text-danger">*</span></label>
                                <select class="form-control" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                                <div class="invalid-feedback">Please select a gender.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-heart mr-2"></i>Marital Status<span class="text-danger">*</span></label>
                                <select class="form-control" name="civilStatus" required>
                                    <option value="" selected disabled>Select Marital Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                                <div class="invalid-feedback">Please select your marital status.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-phone mr-2"></i>Phone Number<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" name="mobile" id="mobile" oninput="formatPhoneNumber(this)" onblur="checkDuplicatePhone()" maxlength="11">
                                    <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                        <button type="button" class="btn btn-primary" onclick="validateStep2()">Next</button>
                    </div>
                </div>



                <!-- Step 3: Household Information & Supporting Documents -->
                <div id="step-3" class="step">
                    <!-- 1. Household Head -->
                    <div class="form-group mb-3">
                        <label>Are you the Household Head? <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_household_head" id="is_household_head" required onchange="toggleHouseholdHeadDetails()">
                            <option value="" selected disabled>Select Answer</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- If No, show these -->
                    <div id="householdHeadDetails" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="household_head_name">Name of Household Head <span class="text-danger">*</span></label>
                            <select class="form-control" name="household_head_name" id="household_head_name" >
                                <option value="" disabled selected>Select household head</option>
                             <?php 
                                    $query = $conn->prepare("
                                        SELECT 
                                            tbl_household_head.household_head_id ,
                                            tbl_household_head.user_id AS hh_user_id,
                                            tbl_residents.*
                                        FROM tbl_household_head
                                        LEFT JOIN tbl_residents 
                                            ON tbl_residents.user_id = tbl_household_head.user_id
                                    ");
                                    $query->execute();
                                    $result = $query->get_result();

                                    while ($row = $result->fetch_assoc()) {
                                        // Handle middle name (optional)
                                        $middle = !empty($row['middle_name']) ? ' ' . $row['middle_name'] : '';
                                        $fullName = $row['first_name'] . $middle . ' ' . $row['last_name'];

                                        echo '<option value="' . $row['household_head_id'] . '">' . htmlspecialchars($fullName) . '</option>';
                                    }
                                    ?>


                            </select>
                        </div>


                        <div class="form-group mb-3">
                            <label>Relationship to Household Head <span class="text-danger">*</span></label>
                            <select class="form-control" name="household_head_relationship">
                                <option value="" selected disabled>Select Relationship</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Son">Son</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Relative">Relative</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Address -->
                    <div class="form-group mb-3">
                        <label>Household Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="address" required>
                    </div>

                    <!-- 3. Senior Citizen -->
                    <div class="form-group mb-3">
                        <label>Senior Citizen Status:</label>
                        <input type="text" class="form-control" id="autoSeniorStatus" name="is_senior" readonly>
                    </div>
                    

                    <!-- If Yes, upload Senior Citizen ID -->
                    <div id="seniorUploadSection" style="display: none;">
                        <div class="form-group mb-3">
                            <label>Upload Senior Citizen ID <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="seniorDocument" name="senior_document">
                                <label class="custom-file-label" for="seniorDocument">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PWD -->
                    <div class="form-group mb-3">
                        <label>Are you a Person with Disability (PWD)? <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_pwd" id="is_pwd" required onchange="toggleUpload('is_pwd', 'pwdUploadSection')">
                            <option value="" selected disabled>Select Answer</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- If Yes, upload PWD ID -->
                    <div id="pwdUploadSection" style="display: none;">
                        <div class="form-group mb-3">
                            <label>Upload PWD ID <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pwdDocument" name="pwd_document" >
                                <label class="custom-file-label" for="pwdDocument">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Voter -->
                    <div class="form-group mb-3">
                        <label>Are you a Registered Voter? <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_registered_voter" id="is_registered_voter" required onchange="toggleUpload('is_registered_voter', 'voterUploadSection')">
                            <option value="" selected disabled>Select Answer</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- If Yes, upload Voter ID -->
                    <div id="voterUploadSection" style="display: none;">
                        <div class="form-group mb-3">
                            <label>Upload Voter ID <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="voterDocument" name="voter_document">
                                <label class="custom-file-label" for="voterDocument">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Proof of Residency -->
                    <div class="form-group mb-3">
                        <label>Upload Proof of Residency <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="residencyDocument" name="proof_of_residency" required>
                            <label class="custom-file-label" for="residencyDocument">Choose file</label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                        <button type="button" class="btn btn-primary" onclick="validateStep3()">Next</button>
                    </div>
                </div>

                <!-- Step 4: Create Account -->
                <div id="step-4" class="step">
                    <div class="form-group mb-3">
                        <label>Occupation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Enter Occupation or N/A" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" oninput="checkPasswordStrength()" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                                    </span>
                                </div>
                            </div>
                            <small id="passwordHelp" class="form-text text-muted">
                                Must be at least 8 characters, include 1 number, and 1 special character.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                        <button type="submit" name="register" class="btn btn-primary" onclick="validateStep4()" id="registerButton">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </button>
                    </div>
                </div>


            </form>

            <!-- Footer -->
            <div class="auth-footer text-center mt-4">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome Icons -->

    <script>
        // Multi-Step Form Control
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            document.querySelectorAll('.step').forEach((div, index) => {
                div.classList.remove('active');
                if (index + 1 === step) {
                    div.classList.add('active');
                }
            });

            // Update Progress Bar
            const progress = Math.round((step / totalSteps) * 100);
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressBar').innerText = `Step ${step} of ${totalSteps}`;
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function validateStep1() {
            const privacyChecked = document.getElementById('privacyNoticeCheckbox').checked;
            const termsChecked = document.getElementById('termsCheckbox').checked;

            if (privacyChecked && termsChecked) {
                nextStep();
            } else {
                alert("Please agree to the Privacy Notice and Terms and Conditions before continuing.");
            }
        }

        // Validate Text-Only Fields
        function validateTextOnly(input) {
            const regex = /^[a-zA-Z\s]*$/; // Allow only letters and spaces
            if (!regex.test(input.value)) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }

        // Validate Phone Number: Only Digits, Exactly 10 Digits
        function validatePhoneNumber(input) {
            input.value = input.value.replace(/\D/g, ''); // Remove all non-numeric input immediately
            if (input.value.length !== 11) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
        
        

        // Validation for Step 2
        function validateStep2() {
            let valid = true;
            const step2Fields = document.querySelectorAll('#step-2 input[required], #step-2 select[required]');

            // Basic Required Fields
            step2Fields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Additional Text-Only Validation
            const textFields = ['first_name', 'last_name', 'middle_name', 'suffix'];
            textFields.forEach(name => {
                const field = document.getElementsByName(name)[0];
                if (field && field.value.trim() !== '' && !/^[a-zA-Z\s]+$/.test(field.value)) {
                    field.classList.add('is-invalid');
                    valid = false;
                }
            });

            // Phone Number Validation
            const phoneField = document.getElementsByName('mobile')[0];
            if (phoneField) {
                phoneField.value = phoneField.value.replace(/\D/g, ''); // Clean non-digits
                if (phoneField.value.length !== 11) {
                    phoneField.classList.add('is-invalid');
                    valid = false;
                }
            }

            // Dropdown Validation
            const genderField = document.getElementsByName('gender')[0];
            const maritalField = document.getElementsByName('civilStatus')[0];
            if (genderField.value === "") {
                genderField.classList.add('is-invalid');
                valid = false;
            }
            if (maritalField.value === "") {
                maritalField.classList.add('is-invalid');
                valid = false;
            }

            // Final Decision
            if (valid) {
                nextStep();
            } else {
                alert('Please correct the errors before proceeding.');
            }
        }

        // Toggle Password Visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        // Bootstrap Form Validation on Submit
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                const forms = document.getElementsByClassName('needs-validation');
                Array.prototype.forEach.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // STEP 3 VALDIATION
        // Show or Hide Household Head Details
        function toggleHouseholdHeadDetails() {
            const householdHeadSelect = document.getElementById('is_household_head');
            const householdHeadDetails = document.getElementById('householdHeadDetails');
            if (householdHeadSelect.value === 'No') {
                householdHeadDetails.style.display = 'block';
                document.getElementsByName('household_head_name')[0].required = true;
                document.getElementsByName('household_head_relationship')[0].required = true;
            } else {
                householdHeadDetails.style.display = 'none';
                document.getElementsByName('household_head_name')[0].required = false;
                document.getElementsByName('household_head_relationship')[0].required = false;
            }
        }

        
        function toggleUpload(selectId, sectionId) {
            const selectedValue = document.getElementById(selectId).value;
            const section = document.getElementById(sectionId);
            section.style.display = selectedValue === "Yes" ? "block" : "none";

            // Set required attribute on file input based on visibility
            const fileInput = section.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.required = (selectedValue === "Yes");
            }
        }


        // Validate Step 3 before next
        function validateStep3() {
            let valid = true;
            const step3Fields = document.querySelectorAll('#step-3 input[required], #step-3 select[required]');

            step3Fields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (valid) {
                nextStep();
            } else {
                alert('Please complete all required fields before proceeding.');
            }
        }

        // Bootstrap 4 file input label update
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('custom-file-input')) {
                let fileName = e.target.files[0]?.name;
                e.target.nextElementSibling.innerText = fileName || 'Choose file';
            }
        });


        //STEP 4 VALIDATION 
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        // Check password strength (live)
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const passwordHelp = document.getElementById('passwordHelp');

            const regexNumber = /\d/;
            const regexSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;

            let messages = [];

            if (password.length < 8) messages.push('Minimum 8 characters');
            if (!regexNumber.test(password)) messages.push('At least 1 number');
            if (!regexSpecialChar.test(password)) messages.push('At least 1 special character');

            if (messages.length > 0) {
                passwordHelp.innerHTML = messages.join(', ');
                passwordHelp.classList.remove('text-success');
                passwordHelp.classList.add('text-danger');
            } else {
                passwordHelp.innerHTML = 'Password looks good!';
                passwordHelp.classList.remove('text-danger');
                passwordHelp.classList.add('text-success');
            }
        }

        // Validate Step 4 before submit
        function validateStep4() {
            let valid = true;

            // Validate Email
            const emailField = document.getElementById('email');
            if (!emailField.value.includes('@')) {
                emailField.classList.add('is-invalid');
                valid = false;
            } else {
                emailField.classList.remove('is-invalid');
            }

            // Validate Password
            const passwordField = document.getElementById('password');
            const password = passwordField.value;
            const regexNumber = /\d/;
            const regexSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;

            if (password.length < 8 || !regexNumber.test(password) || !regexSpecialChar.test(password)) {
                passwordField.classList.add('is-invalid');
                valid = false;
            } else {
                passwordField.classList.remove('is-invalid');
            }

            // Validate Confirm Password
            const confirmPasswordField = document.getElementById('confirm_password');
            if (confirmPasswordField.value !== password) {
                confirmPasswordField.classList.add('is-invalid');
                valid = false;
            } else {
                confirmPasswordField.classList.remove('is-invalid');
            }

            // Validate Occupation
            const occupationField = document.getElementById('occupation');
            if (!occupationField.value.trim()) {
                occupationField.classList.add('is-invalid');
                valid = false;
            } else {
                occupationField.classList.remove('is-invalid');
            }

            if (valid) {
                // All validation passed, submit the form
                document.querySelector('form').submit();
            } else {
                alert('Please fix the errors before submitting.');
            }
        }
        
        //detecting senior citizen through birthday
        document.querySelector('input[name="birthday"]').addEventListener('change', function () {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
    
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
    
            const seniorStatusField = document.getElementById('autoSeniorStatus');
            const seniorUploadSection = document.getElementById('seniorUploadSection');
    
            if (age >= 60) {
                seniorStatusField.value = "Yes";
                seniorUploadSection.style.display = "block";
            } else {
                seniorStatusField.value = "No";
                seniorUploadSection.style.display = "none";
            }
        });
    </script>



</body>

</html>