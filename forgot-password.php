
<?php
include_once 'connection/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $select = "SELECT * FROM tbl_user WHERE email = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];

        // Generate and store a password reset token
        $reset_token = bin2hex(random_bytes(32));
        $update = "UPDATE tbl_user SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE user_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ss", $reset_token, $user['user_id']);
        $stmt->execute();

        // Send password reset email to the user
        $reset_link = 'http://localhost/public_html/reset_password.php?token=' . urlencode($reset_token);

        sendPasswordResetEmail($user['email'], $first_name, $last_name, $reset_link);

        $success_message = 'Password reset instructions have been sent to your email address.';
    } else {
        $error_message = 'No account found with the provided email address.';
    }
}

function sendPasswordResetEmail($email, $first_name, $last_name, $reset_link) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'systembarangaymanagement@gmail.com';
        $mail->Password = 'qqwafpvwljoixsxa';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('systembarangaymanagement@gmail.com', 'Barangay Management System');
        $mail->addAddress($email, $first_name . ' ' . $last_name);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = '
            <html>
            <body style="font-family: Arial, sans-serif;">
                <div style="background-color: #f6f6f6; padding: 20px;">
                    <div style="background-color: white; padding: 20px; border-radius: 10px;">
                        <h2 style="color: #4A90E2;">Password Reset Request</h2>
                        <p>Dear ' . $first_name . ' ' . $last_name . ',</p>
                        <p>Please click the following link to reset your password:</p>
                        <a href="' . $reset_link . '" style="background-color: #4A90E2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Reset Password</a>
                        <p style="color: #666;">If you did not request this password reset, please ignore this email.</p>
                    </div>
                </div>
            </body>
            </html>';

        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
            max-width: 500px;
        }

        .auth-header {
            background: var(--primary-color);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .auth-body {
            padding: 40px;
        }

        .form-group label {
            font-weight: 500;
            color: var(--accent-color);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #E8EEF4;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .remember-me {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .social-login {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E8EEF4;
        }

        .social-btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: 2px solid #E8EEF4;
            background: white;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: var(--secondary-color);
        }

        .auth-footer {
            text-align: center;
            padding: 20px;
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-header">
                <h2><i class="fas fa-sign-in-alt mr-2"></i>Forgot Password</h2>
                <p class="mb-0">You're requesting to change your password</p>
            </div>
            
            <div class="auth-body">
            <h1></h1>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php } ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt mr-2"></i>Send Reset Instructions
                </button>
               
                
            </form>
        </div>
    </div>


</body>
</html>