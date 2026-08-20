<?php
include_once 'connection/config.php';

if (isset($_GET['token'])) {
    $reset_token = mysqli_real_escape_string($conn, $_GET['token']);

    $select = "SELECT * FROM tbl_user WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("s", $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = "UPDATE tbl_user SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?";
                $stmt = $conn->prepare($update);
                $stmt->bind_param("si", $hashed_password, $user['user_id']);
                $stmt->execute();

                $success_message = 'Password reset successful. You can now log in with your new password.';
                header('Location: login.php');
                exit;
            }
            else {
                $error_message = 'New password and confirm password do not match.';
            }
        }
    } else {
        $error_message = 'Invalid or expired password reset token.';
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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
                <h2><i class="fas fa-sign-in-alt mr-2"></i>Reset Password</h2>
                <p class="mb-0">You're about to change your password</p>
            </div>
            
            <div class="auth-body">
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php } ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt mr-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>

    
   
</body>
</html>