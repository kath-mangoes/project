<?php 
session_start();

include '../connection/config.php';

// ✅ Use active_user_id if set, fallback to user_id
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? null;

// If no session at all, force login
if (!$active_user_id) {
    header("Location: ../login.php");
    exit();
}

// Fetch the user's data from the database based on the active user ID
$sql = "SELECT u.email, u.mobile, u.image, u.is_logged_in,
               r.first_name, r.middle_name, r.last_name, r.suffix, r.birthday, r.birthplace, 
               r.civilStatus, r.address, r.gender, r.precinctNumber, r.residency_tenure, 
               r.is_registered_voter, r.bloodType, r.height, r.weight, r.typeOfID, 
               r.IDNumber, r.barangay_number, r.SSSGSIS_Number, r.TIN_number, r.is_senior, r.is_pwd, r.is_4ps_member,
               TIMESTAMPDIFF(YEAR, r.birthday, CURDATE()) as age
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $active_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize the variables with default values
$image = "https://barangay400.com/dist/assets/images/default_image.png"; // Default image
$first_name = $middle_name = $last_name = $suffix = "";
$mobile = $email = $address = $birthday = $birthplace = $civilStatus = "";
$gender = $precinctNumber = $residency_tenure = $is_registered_voter = "";
$bloodType = $height = $weight = $typeOfID = $IDNumber = $barangay_number = "";
$SSSGSIS_Number = $TIN_number = $age = "";
$is_senior = $is_pwd = $is_4ps_member = "";
$is_logged_in = 0; // Default to not logged in

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?: "https://barangay400.com/dist/assets/images/default_image.png";
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $suffix = $row["suffix"];
    $mobile = $row["mobile"];
    $email = $row["email"];
    $address = $row["address"];
    $birthday = $row["birthday"];
    $birthplace = $row["birthplace"];
    $civilStatus = $row["civilStatus"];
    $residentStatus = $row["residentStatus"];
    $gender = $row["gender"];
    $precinctNumber = $row["precinctNumber"];
    $residency_tenure = $row["residency_tenure"];
    $is_registered_voter = $row["is_registered_voter"];
    $bloodType = $row["bloodType"];
    $height = $row["height"];
    $weight = $row["weight"];
    $typeOfID = $row["typeOfID"];
    $IDNumber = $row["IDNumber"];
    $barangay_number = $row["barangay_number"];
    $SSSGSIS_Number = $row["SSSGSIS_Number"];
    $TIN_number = $row["TIN_number"];
    $age = $row["age"];
    $is_senior = $row["is_senior"];
    $is_pwd = $row["is_pwd"];
    $is_4ps_member = $row["is_4ps_member"];
    $is_logged_in = $row['is_logged_in'];
}

// Assign the value to $_SESSION variables (optional — only if you use them later)
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = trim($first_name . ' ' . $last_name);
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
            <?php
                // Get current user ID from session
                $user_id = $_SESSION['user_id'] ?? '';

                // Handle mark all as read action
                if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
                    $markAllQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE user_id = ?";
                    $markAllStmt = $conn->prepare($markAllQuery);
                    $markAllStmt->bind_param('s', $user_id);
                    $markAllStmt->execute();
                    
                    // Redirect back to the current page without the query parameter
                    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
                    exit;
                }

                // Handle mark single notification as read
                if (isset($_GET['read_notification']) && is_numeric($_GET['read_notification'])) {
                    $notificationId = $_GET['read_notification'];
                    $markReadQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?";
                    $markReadStmt = $conn->prepare($markReadQuery);
                    $markReadStmt->bind_param('is', $notificationId, $user_id);
                    $markReadStmt->execute();
                    
                    // Redirect to the appropriate page based on notification type
                    if (isset($_GET['redirect'])) {
                        header('Location: ' . $_GET['redirect']);
                        exit;
                    }
                }

                // Fetch notifications for the current user - Only show unread ones and recently read ones (last 24 hours)
                $query = "SELECT * FROM tbl_notifications 
                        WHERE user_id = ? AND (is_read = 0 OR (is_read = 1 AND date_created > DATE_SUB(NOW(), INTERVAL 24 HOUR)))
                        ORDER BY date_created DESC 
                        LIMIT 10";
                        
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $notifications = [];
                while ($row = $result->fetch_assoc()) {
                    $notifications[] = $row;
                }

                // Count unread notifications
                $unreadQuery = "SELECT COUNT(*) as count FROM tbl_notifications 
                                WHERE user_id = ? AND is_read = 0";
                                
                $unreadStmt = $conn->prepare($unreadQuery);
                $unreadStmt->bind_param('s', $user_id);
                $unreadStmt->execute();
                $unreadResult = $unreadStmt->get_result();
                $unreadRow = $unreadResult->fetch_assoc();
                $unreadCount = (int)$unreadRow['count'];

                // Function to get time ago
                function time_ago($timestamp) {
                    $time_ago = strtotime($timestamp);
                    $current_time = time();
                    $time_difference = $current_time - $time_ago;
                    
                    if ($time_difference < 60) {
                        return "Just now";
                    } elseif ($time_difference < 3600) {
                        $minutes = round($time_difference / 60);
                        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                    } elseif ($time_difference < 86400) {
                        $hours = round($time_difference / 3600);
                        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                    } else {
                        $days = round($time_difference / 86400);
                        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                    }
                }

                // Function to get appropriate icon and color based on notification type
                function getNotificationStyle($type) {
                    $style = [
                        'icon' => 'ti-info-alt',
                        'bg_color' => 'bg-primary',
                        'href' => '#'
                    ];
                    
                    if (strpos($type, 'certification') !== false) {
                        $style['icon'] = 'ti-file';
                        $style['bg_color'] = 'bg-primary';
                        $style['href'] = 'barangay-certificate.php';
                    } elseif (strpos($type, 'clearance') !== false) {
                        $style['icon'] = 'ti-clipboard';
                        $style['bg_color'] = 'bg-success';
                        $style['href'] = 'barangay-clearance.php';
                    } elseif (strpos($type, 'complain') !== false) {
                        $style['icon'] = 'ti-alert';
                        $style['bg_color'] = 'bg-warning';
                        $style['href'] = 'barangay-complain.php';
                    } elseif (strpos($type, 'blotter') !== false) {
                        $style['icon'] = 'ti-notepad';
                        $style['bg_color'] = 'bg-danger';
                        $style['href'] = 'blotter.php';
                    } elseif (strpos($type, 'bid') !== false) {
                        $style['icon'] = 'ti-id-badge';
                        $style['bg_color'] = 'bg-info';
                        $style['href'] = 'barangay-id.php';
                    }
                    
                    return $style;
                }
                ?>

                <!-- Notification dropdown HTML structure -->
                <li class="nav-item dropdown notification-dropdown">
                    <a class="nav-link dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell mx-0"></i>
                        <?php if ($unreadCount > 0): ?>
                            <div class="notification-count"><?php echo $unreadCount; ?></div>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        
                        <?php if (empty($notifications)): ?>
                            <div class="text-center p-3">No notifications</div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): 
                                $style = getNotificationStyle($notification['notification_type']);
                                $notificationLink = $style['href'] . '?read_notification=' . $notification['notification_id'] . '&redirect=' . urlencode($style['href']);
                            ?>
                                <a class="dropdown-item preview-item <?php echo ($notification['is_read'] == 0) ? 'unread-notification' : ''; ?>" href="<?php echo $notificationLink; ?>">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon <?php echo $style['bg_color']; ?>">
                                            <i class="<?php echo $style['icon']; ?> mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal"><?php echo htmlspecialchars($notification['message']); ?></h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            <?php echo time_ago($notification['date_created']); ?>
                                        </p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            
                            <?php if ($unreadCount > 0): ?>
                            <!-- Mark all as read button -->
                            <div class="dropdown-item d-flex justify-content-center border-top pt-3">
                                <a href="?mark_all_read=1" class="text-muted text-small" style="text-decoration: none;">
                                    <i class="ti-check mr-1"></i> Mark all as read
                                </a>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </li>

                <style>
                /* Updated notification styling */
                .notification-dropdown {
                    position: relative;
                }

                .notification-count {
                    position: absolute;
                    top: 2px;
                    right: 2px;
                    background-color: #ff0000;
                    color: #ffffff;
                    font-size: 10px;
                    height: 16px;
                    width: 16px;
                    line-height: 16px;
                    border-radius: 50%;
                    text-align: center;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .unread-notification {
                    background-color: rgba(0, 123, 255, 0.05);
                }

                /* Ensure the bell icon container has proper positioning */
                .nav-link {
                    position: relative;
                    display: inline-block;
                }
                </style>

                <script>
                // Add this JavaScript to ensure the notification count is visible
                document.addEventListener('DOMContentLoaded', function() {
                    // Check if we have unread notifications
                    var unreadCount = <?php echo $unreadCount; ?>;
                    if (unreadCount > 0) {
                        // Make sure the notification count is visible
                        var countElement = document.querySelector('.notification-count');
                        if (countElement) {
                            countElement.style.display = 'flex';
                        }
                    }
                });
                </script>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <img src="../dist/assets/images/user/<?php echo $_SESSION['image']; ?>" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="profile-management.php">
                            <i class="ti-user text-primary"></i> Profile Management</a>
                        <a class="dropdown-item" href="family.php">
                            <i class="ti-heart text-primary"></i> Household</a>
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
            .page-item.active .page-link {
                background-color: #0e1624 !important;
                border-color: #0e1624 !important;
            }

            .page-item.active .page-link:hover {
                background-color: #0e1624 !important;
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
                        <span class="menu-title">My Request</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="requestManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="barangay-certificate.php">Certificate Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-clearance.php">Clearance Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-id.php">ID Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="blotter.php">Blotter Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-complain.php">Complain Request</a></li>
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
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">
                        <i class="fa-solid fa-comments"></i>
                        <span class="menu-title">Feedback</span>
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
                            <li class="nav-item"> <a class="nav-link" href="profile-management.php">Profile Management</a></li>
                            <li class="nav-item"> <a class="nav-link" href="family.php">Household</a></li>
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
                                                <div class="col-md-3">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" id="first_name" name="first_name" 
                                                        value="<?php echo htmlspecialchars($first_name); ?>" 
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" id="middle_name" name="middle_name" 
                                                        value="<?php echo htmlspecialchars($middle_name); ?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" id="last_name" name="last_name" 
                                                        value="<?php echo htmlspecialchars($last_name); ?>" 
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="suffix" class="suffix">Suffix</label>
                                                    <input type="text" id="suffix" name="suffix" 
                                                        value="<?php echo htmlspecialchars($suffix); ?>" 
                                                        class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select id="gender" name="gender" class="form-control" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                        <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                        <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="civilStatus" class="form-label">Civil Status</label>
                                                    <select id="civilStatus" name="civilStatus" class="form-control" required>
                                                        <option value="">Select Civil Status</option>
                                                        <option value="Single" <?php echo ($civilStatus == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                        <option value="Married" <?php echo ($civilStatus == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                        <option value="Widowed" <?php echo ($civilStatus == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                                        <option value="Divorced" <?php echo ($civilStatus == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                                        <option value="Separated" <?php echo ($civilStatus == 'Separated') ? 'selected' : ''; ?>>Separated</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <?php
                                                // Calculate the latest birthday allowed (18 years ago from today)
                                                $maxBirthday = date('Y-m-d', strtotime('-18 years'));
                                            ?>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="birthday" class="form-label">Birthday</label>
                                                    <input type="date" id="birthday" name="birthday" 
                                                        value="<?php echo htmlspecialchars($birthday); ?>" 
                                                        value="<?php echo htmlspecialchars($birthday); ?>" 
                                                        max="<?php echo $maxBirthday; ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="birthplace" class="form-label">Birth Place</label>
                                                    <input type="text" id="birthplace" name="birthplace" 
                                                        value="<?php echo htmlspecialchars($birthplace); ?>" 
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="bloodType" class="form-label">Blood Type</label>
                                                    <select id="bloodType" name="bloodType" class="form-control">
                                                        <option value="">Select Blood Type</option>
                                                        <option value="A+" <?php echo ($bloodType == 'A+') ? 'selected' : ''; ?>>A+</option>
                                                        <option value="A-" <?php echo ($bloodType == 'A-') ? 'selected' : ''; ?>>A-</option>
                                                        <option value="B+" <?php echo ($bloodType == 'B+') ? 'selected' : ''; ?>>B+</option>
                                                        <option value="B-" <?php echo ($bloodType == 'B-') ? 'selected' : ''; ?>>B-</option>
                                                        <option value="AB+" <?php echo ($bloodType == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                                        <option value="AB-" <?php echo ($bloodType == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                                        <option value="O+" <?php echo ($bloodType == 'O+') ? 'selected' : ''; ?>>O+</option>
                                                        <option value="O-" <?php echo ($bloodType == 'O-') ? 'selected' : ''; ?>>O-</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="height" class="form-label">Height (cm)</label>
                                                    <input type="number" id="height" name="height" min="100" max="500" ma
                                                        value="<?php echo htmlspecialchars($height); ?>"
                                                        class="form-control" maxlength="3">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="weight" class="form-label">Weight (kg)</label>
                                                    <input type="number" step="0.01" id="weight" name="weight" 
                                                        value="<?php echo htmlspecialchars($weight); ?>" 
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                
                                                <div class="col-md-4">
                                                    <label for="is_senior" class="form-label">Senior Citizen</label>
                                                    <select id="is_senior" name="is_senior" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Yes" <?php echo ($is_senior == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($is_senior == 'No') ? 'selected' : ''; ?>>No</option>
                                                    
                                                    </select>
                                                    
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="is_pwd" class="form-label">PWD</label>
                                                    <select id="is_pwd" name="is_pwd" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Yes" <?php echo ($is_pwd == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($is_pwd == 'No') ? 'selected' : ''; ?>>No</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="is_4ps_member" class="form-label">4Ps Member</label>
                                                    <select id="is_4ps_member" name="is_4ps_member" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Yes" <?php echo ($is_4ps_member == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($is_4ps_member == 'No') ? 'selected' : ''; ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Add this section for 4Ps membership -->
                                            <div class="row mb-3">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contact Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Contact Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" id="address" name="address" 
                                                    value="<?php echo htmlspecialchars($address); ?>" 
                                                    class="form-control" required>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" name="email" 
                                                        value="<?php echo htmlspecialchars($email); ?>" 
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mobile" class="form-label">Phone Number</label>
                                                    <input type="text" id="mobile" name="mobile"  maxlength="11" pattern="\d{11}" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                                                        value="<?php echo htmlspecialchars($mobile); ?>" 
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Barangay Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Barangay Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="precinctNumber" class="form-label">Precinct Number</label>
                                                    <input type="text" id="precinctNumber" name="precinctNumber" 
                                                        value="<?php echo htmlspecialchars($precinctNumber); ?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="barangay_number" class="form-label">Barangay Number</label>
                                                    <input type="text" id="barangay_number" name="barangay_number" 
                                                        value="<?php echo htmlspecialchars($barangay_number); ?>" 
                                                        class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="residentStatus" class="form-label">Resident Status</label>
                                                    <select id="residentStatus" name="residentStatus" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Permanent" <?php echo ($residentStatus == 'Permanent') ? 'selected' : ''; ?>>Permanent</option>
                                                        <option value="Temporary" <?php echo ($residentStatus == 'Temporary') ? 'selected' : ''; ?>>Temporary</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="is_registered_voter" class="form-label">Voter Status</label>
                                                    <select id="is_registered_voter" name="is_registered_voter" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Registered" <?php echo ($is_registered_voter == 'Registered') ? 'selected' : ''; ?>>Registered</option>
                                                        <option value="Not Registered" <?php echo ($is_registered_voter == 'Not Registered') ? 'selected' : ''; ?>>Not Registered</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ID Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">ID Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="typeOfID" class="form-label">Type of ID</label>
                                                    <select id="typeOfID" name="typeOfID" class="form-select">
                                                        <option value="" <?php echo empty($typeOfID) ? 'selected' : ''; ?>>Select Type of ID</option>
                                                        <option value="Passport" <?php echo $typeOfID === 'Passport' ? 'selected' : ''; ?>>Passport</option>
                                                        <option value="Driver's License" <?php echo $typeOfID === 'Driver\'s License' ? 'selected' : ''; ?>>Driver's License</option>
                                                        <option value="National ID Card" <?php echo $typeOfID === 'National ID Card' ? 'selected' : ''; ?>>National ID Card</option>
                                                        <option value="Postal ID" <?php echo $typeOfID === 'Postal ID' ? 'selected' : ''; ?>>Postal ID</option>
                                                        <option value="TIN ID" <?php echo $typeOfID === 'TIN ID' ? 'selected' : ''; ?>>TIN ID</option>
                                                        <option value="GSIS ID" <?php echo $typeOfID === 'GSIS ID' ? 'selected' : ''; ?>>GSIS ID</option>
                                                        <option value="SSS ID" <?php echo $typeOfID === 'SSS ID' ? 'selected' : ''; ?>>SSS ID</option>
                                                        <option value="UMID" <?php echo $typeOfID === 'UMID' ? 'selected' : ''; ?>>UMID</option>
                                                        <option value="PhilHealth ID" <?php echo $typeOfID === 'PhilHealth ID' ? 'selected' : ''; ?>>PhilHealth ID</option>
                                                        <option value="Voter's ID" <?php echo $typeOfID === 'Voter\'s ID' ? 'selected' : ''; ?>>Voter's ID</option>
                                                        <option value="PRC ID" <?php echo $typeOfID === 'PRC ID' ? 'selected' : ''; ?>>PRC ID</option>
                                                        <option value="Other" <?php echo $typeOfID === 'Other' ? 'selected' : ''; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="IDNumber" class="form-label">Valid ID Number</label>
                                                    <input type="text" id="IDNumber" name="IDNumber" 
                                                        value="<?php echo htmlspecialchars($IDNumber); ?>" 
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="SSSGSIS_Number" class="form-label">SSS/GSIS Number</label>
                                                    <input type="text" id="SSSGSIS_Number" name="SSSGSIS_Number" 
                                                        value="<?php echo htmlspecialchars($SSSGSIS_Number); ?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="TIN_number" class="form-label">TIN Number</label>
                                                    <input type="text" id="TIN_number" name="TIN_number" 
                                                        value="<?php echo htmlspecialchars($TIN_number); ?>" 
                                                        class="form-control">
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
                                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                 </form>


                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.getElementById('profile_image').addEventListener('change', function (event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                document.getElementById('profileImagePreview').src = e.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    // Password Visibility Toggle
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function () {
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
                    
                    //HEIGHT VALIDATION             
                    document.addEventListener('DOMContentLoaded', function() {
            const heightInput = document.getElementById('height');

            heightInput.addEventListener('input', function() {
                // Remove non-digit characters immediately
                this.value = this.value.replace(/\D/g, '');

                const errorMessage = this.parentElement.querySelector('.error-message');

                // Check if more than 3 digits
                if (this.value.length > 3) {
                    this.value = this.value.slice(0, 3);
                }

                // Show or hide error based on value
                if (this.value === '' || parseInt(this.value) > 400) {
                    this.classList.add('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'none';
                }
            });
        });
        
                    //Weight Validation
                    document.addEventListener('DOMContentLoaded', function() {
            const weightInput = document.getElementById('weight');

            weightInput.addEventListener('input', function() {
                // Remove non-digit characters
                this.value = this.value.replace(/\D/g, '');

                const errorMessage = this.parentElement.querySelector('.error-message');

                // Limit to 3 digits
                if (this.value.length > 3) {
                    this.value = this.value.slice(0, 3);
                }

                // Show/hide error if over 500 or empty
                if (this.value === '' || parseInt(this.value) > 500) {
                    this.classList.add('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'none';
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