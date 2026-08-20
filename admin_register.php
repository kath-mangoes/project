<?php
session_start();
include 'connection/config.php';
include 'functions.php';

// Turn on error reporting for easier debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
    $user_id = uniqid('admin_'); // Generate unique admin ID
    $role = 'admin'; // Default role
    
    
    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        $error = "First name can only contain letters and spaces.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        $error = "Last name can only contain letters and spaces.";
    } else {
        // Proceed with registration
        $insertAdmin = "INSERT INTO tbl_user (user_id, first_name, last_name, email, password, role, account_status) 
                        VALUES (?, ?, ?, ?, ?, ?, 'Active')";
    
        $stmt = $conn->prepare($insertAdmin);
        if ($stmt) {
            $stmt->bind_param("ssssss", $user_id, $first_name, $last_name, $email, $password, $role);
            if ($stmt->execute()) {
                echo "<script>alert('Admin account created successfully!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Failed to create admin account. Please try again.";
            }
        } else {
            $error = "Database error. Please check your query.";
        }
    } */
    
    if (isset($_POST['register'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
        $user_id = uniqid('admin_'); // Generate unique admin ID
        $role = 'admin'; // Default role
    
        // Insert into tbl_user
        $insertAdmin = "INSERT INTO tbl_user (user_id, first_name, last_name, email, password, role, account_status) 
                        VALUES (?, ?, ?, ?, ?, ?, 'Active')";
    
        $stmt = $conn->prepare($insertAdmin);
        if ($stmt) {
            $stmt->bind_param("ssssss", $user_id, $first_name, $last_name, $email, $password, $role);
            if ($stmt->execute()) {
                echo "<script>alert('Admin account created successfully!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Failed to create admin account. Please try again.";
            }
        } else {
            $error = "Database error. Please check your query.";
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register New Admin | Barangay System</title>
    <!-- endinject -->
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --light: #F9F9F9;
      --blue: #141E30;
      --light-blue: #CFE8FF;
      --grey: #eee;
      --dark-grey: #AAAAAA;
      --dark: #342E37;
      --red: #DB504A;
      --yellow: #FFCE26;
      --light-yellow: #FFF2C6;
      --orange: #FD7238;
      --light-orange: #FFE0D3;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('https://barangay400.com/WEBSITE/image/b2.png') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(20, 30, 48, 0.85); /* dark overlay */
      backdrop-filter: blur(6px);
      z-index: -1;
    }

    .card {
      background: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(10px);
      color: #fff;
    }

    label, .form-label {
      color: #fff;
      font-weight: 500;
    }

    input {
      background-color: rgba(255, 255, 255, 0.85);
      border: none;
      color: #000;
    }

    input:focus {
      box-shadow: 0 0 0 2px var(--yellow);
      outline: none;
    }

    .btn-primary {
      background-color: var(--blue);
      border-color: var(--blue);
      color: #fff;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: var(--red);
      border-color: var(--red);
      color: #fff;
    }

    .card-header h3 {
      color: #fff;
    }

    .form-control::placeholder {
      color: #888;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card p-4">
          <div class="card-header text-center mb-4">
            <h3 class="mb-0">Register New Admin</h3>
          </div>
          <div class="card-body">
            <?php if (isset($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
              </div>

              <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email (Username)</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label">Password (minimum 6 characters)</label>
                <input type="password" name="password" class="form-control" placeholder="Create password" minlength="6" required>
              </div>

              <button type="submit" name="register" class="btn btn-primary w-100">Register Admin</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

