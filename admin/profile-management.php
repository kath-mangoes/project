<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Fetch the user's data from the tbl_user table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT * FROM tbl_user WHERE user_id = '$id'";
$result = $conn->query($sql);

// Initialize the variables with default values
$image = "default_image.jpg"; // Assuming default image name
$first_name = "";
$middle_name = "";
$last_name = "";
$address = "";
$mobile = "";
$email = "";
$is_logged_in = 0;
$account_status = "";
$civilStatus = "";
$profile_title = "My Profile";

// Check if the query was successful and populate variables
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?? "default_image.jpg";
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $address = $row["address"];
    $mobile = $row["mobile"];
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in'];
    $account_status = $row["account_status"];
    $civilStatus = $row["civilStatus"];
}

// Assign the value of $username and other variables to $_SESSION variables
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = $first_name . ' ' . $last_name;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Profile Management | Barangay System</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <!--FONTAWESOME CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../dist/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />
</head>

<body>
    <div class="container-scroller">

    </div>
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logos.png"
                    class="me-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logos.png"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>


            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <img src="../dist/assets/images/user/<?php echo $_SESSION['image']; ?>" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="profile-management.php">
                            <i class="ti-user text-primary"></i> Profile Management</a>
                           
                        <a class="dropdown-item" href="logout.php">
                            <i class="ti-power-off text-primary"></i> Logout </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <style>
            .nav-link i {
                margin-right: 10px;
            }
        </style>
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fa-solid fa-s"></i>
                        <span class="menu-title">Services</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#requestManagement" aria-expanded="false"
                        aria-controls="requestManagement">
                        <i class="fa-solid fa-registered"></i>
                        <span class="menu-title">Request</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="requestManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="certificate.php"> Certificate Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="clearance.php"> Clearance Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangayid.php"> Barangay ID Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="blotter.php"> Blotter Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="complains.php"> Complains Request </a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="officials.php">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="menu-title">Officials</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="audit.php">
                        <i class="fa-solid fa-clock"></i>
                        <span class="menu-title">Audit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="population.php">
                        <i class="fa-solid fa-rectangle-list"></i>
                        <span class="menu-title">Populations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="residents.php">
                        <i class="fa-solid fa-user"></i>
                        <span class="menu-title">Residents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cms-dashboard.php">
                        <i class="fa-solid fa-desktop"></i>
                        <span class="menu-title">Manage Website</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">
                        <i class="fa-solid fa-comments"></i>
                        <span class="menu-title">Feedback Reports</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#settingsManagement" aria-expanded="false"
                        aria-controls="settingsManagement">
                        <i class="fa-solid fa-gear"></i>
                        <span class="menu-title">Settings</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="settingsManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="profile-management.php">Profile
                                    Management</a></li>
                            <li class="nav-item"> <a class="nav-link" href="create_super_admin.php"onclick="return confirm('Are you sure you want to create/reset the Super Admin account?');">Create/reset default admin</a></li>
                            <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="row">
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                <!--<h3 class="font-weight-bold">Welcome</h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
                            </div>
                            <div class="col-12 col-xl-4">
                                <div class="justify-content-end d-flex">
                                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                // Check if the "success" parameter is set in the URL
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    // Use SweetAlert to show a success message
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "success",
                                title: "Settings Updated Successfully",
                                showConfirmButton: false,
                                timer: 1500 // Close after 1.5 seconds
                            });
                        });
                    </script>';
                }

                $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
                $profile_title = '';

                switch ($role) {
                    case 'admin':
                        $profile_title = 'Admin Profile Settings';
                        break;
                    case 'barangay_official':
                        $profile_title = 'Barangay Official Profile Settings';
                        break;
                    case 'resident':
                        $profile_title = 'Residents Profile Settings';
                        break;

                    default:
                        $profile_title = 'Profile Settings';
                        break;
                }

                ?>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h2 class="mb-0 text-center"><?php echo htmlspecialchars($profile_title); ?></h2>
                            </div>
                            <div class="card-body">
                                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3 text-center">
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="position-relative">
                                                <img id="profileImagePreview"
                                                    src="../dist/assets/images/user/<?php echo htmlspecialchars($image); ?>"
                                                    class="rounded-circle img-thumbnail"
                                                    style="width: 150px; height: 150px; object-fit: cover;"
                                                    alt="Profile Image">
                                                <label for="profile_image"
                                                    class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle"
                                                    style="padding: 3px 10px;">
                                                    <i class="fas fa-upload"></i>
                                                    <input type="file" id="profile_image" name="image" class="d-none" accept="image/*">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Personal Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Personal Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" id="first_name" name="first_name"
                                                        value="<?php echo htmlspecialchars($first_name); ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" id="middle_name" name="middle_name"
                                                        value="<?php echo htmlspecialchars($middle_name); ?>"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" id="last_name" name="last_name"
                                                        value="<?php echo htmlspecialchars($last_name); ?>"
                                                        class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" id="address" name="address"
                                                        value="<?php echo htmlspecialchars($address); ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="civilStatus" class="form-label">Civil Status</label>
                                                    <select id="civilStatus" name="civilStatus" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="Single" <?php echo ($civilStatus == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                        <option value="Married" <?php echo ($civilStatus == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                        <option value="Widowed" <?php echo ($civilStatus == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                                        <option value="Separated" <?php echo ($civilStatus == 'Separated') ? 'selected' : ''; ?>>Separated</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Contact Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" name="email"
                                                        value="<?php echo htmlspecialchars($email); ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mobile" class="form-label">Contact Number</label>
                                                    <input type="text" id="mobile" name="mobile"
                                                        maxlength="11" pattern="\d{11}" 
                                                        required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                                                        value="<?php echo htmlspecialchars($mobile); ?>"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Change Password</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="old_password" class="form-label">Old Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="old_password" name="old_password"
                                                            class="form-control">
                                                        <span class="input-group-text toggle-password"
                                                            data-target="old_password" style="cursor: pointer;">
                                                            <i class="fas fa-eye" style="color:black;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="new_password" name="new_password"
                                                            class="form-control">
                                                        <span class="input-group-text toggle-password"
                                                            data-target="new_password" style="cursor: pointer;">
                                                            <i class="fas fa-eye" style="color:black;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="confirm_password" name="confirm_password"
                                                            class="form-control">
                                                        <span class="input-group-text toggle-password"
                                                            data-target="confirm_password" style="cursor: pointer;">
                                                            <i class="fas fa-eye" style="color:black;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 small text-muted">
                                                    Leave password fields empty if you don't want to change your password.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <a href="profile-management.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .error-input {
                        border-color: red !important;
                    }
                    .error-message {
                        color: red;
                        font-size: 0.875em; /* small text */
                        margin-top: 5px;
                        display: none;
                    }


                </style>

                <script>
                    document.getElementById('profile_image').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('profileImagePreview').src = e.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    // Helper to show error
                    function showError(field, message) {
                        field.classList.add('error-input');
                        let errorElem = field.nextElementSibling;
                        if (!errorElem || !errorElem.classList.contains('error-message')) {
                            errorElem = document.createElement('div');
                            errorElem.className = 'error-message';
                            field.parentNode.appendChild(errorElem);
                        }
                        errorElem.textContent = message;
                        errorElem.style.display = 'block';
                    }

                    // Helper to clear error
                    function clearError(field) {
                        field.classList.remove('error-input');
                        let errorElem = field.nextElementSibling;
                        if (errorElem && errorElem.classList.contains('error-message')) {
                            errorElem.style.display = 'none';
                        }
                    }

                    // Validate names (only letters)
                    const nameFields = ['first_name', 'middle_name', 'last_name'];
                    nameFields.forEach(id => {
                        const field = document.getElementById(id);
                        field.addEventListener('input', function() {
                            const regex = /^[A-Za-z\s]*$/;
                            if (!regex.test(this.value)) {
                                showError(this, 'Please enter letters only.');
                            } else {
                                clearError(this);
                            }
                        });
                    });

                    // Validate mobile (only numbers, 11 digits)
                    const mobileField = document.getElementById('mobile');
                    mobileField.addEventListener('input', function() {
                        const regex = /^\d{0,11}$/;
                        if (!regex.test(this.value)) {
                            showError(this, 'Numbers only, up to 11 digits.');
                        } else if (this.value.length !== 11 && this.value.length > 0) {
                            showError(this, 'Mobile number must be exactly 11 digits.');
                        } else {
                            clearError(this);
                        }
                    });

                    // Validate passwords (at least 1 number and 1 special character)
                    const passwordFields = ['new_password', 'confirm_password'];
                    passwordFields.forEach(id => {
                    const field = document.getElementById(id);
                        field.addEventListener('input', function() {
                            const regex = /^(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).*$/;
                            if (this.value.length > 0 && !regex.test(this.value)) {
                                showError(this, 'Password must have at least 1 number and 1 special character.');
                            } else {
                                clearError(this);
                            }
                        });
                    });



                    // Password Visibility Toggle
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function() {
                            const targetId = this.getAttribute('data-target');
                            const passwordInput = document.getElementById(targetId);
                            const icon = this.querySelector('i');

                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                icon.classList.remove('fa-eye');
                                icon.classList.add('fa-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                icon.classList.remove('fa-eye-slash');
                                icon.classList.add('fa-eye');
                            }
                        });
                    });
                </script>



                <br><br><br><br><br><br><br>

                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer" style="background-color: LightGray;">
                    <div class="d-flex justify-content-center">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block font-weight-bold">Copyright ©
                            2025-2030. <a href="" target="_blank">Barangay System</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- jQuery -->

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- Add SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- endinject -->
    <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
    <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="../dist/assets/js/dataTables.select.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../dist/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../dist/assets/js/dashboard.js"></script>
</body>

</html>