<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php'; // Ensure this file has the PDO connection

$id = $_SESSION['user_id'];
$sql = "SELECT 
    u.email, u.mobile, u.image, u.is_logged_in,
    r.first_name, r.middle_name, r.last_name, r.birthday, r.birthplace,
    r.civilStatus, r.address, r.gender, r.precinctNumber, r.residentStatus,
    r.voterStatus, r.bloodType, r.height, r.weight, r.typeOfID,
    r.IDNumber, r.SSSGSIS_Number, r.TIN_number, r.barangay_number,
    r.is_senior, r.is_pwd, r.is_4ps_member, r.suffix,
    r.is_household_head, r.household_head_name, r.relationship_to_head,
    r.senior_document, r.pwd_document, r.is_registered_voter,
    r.voter_document, r.proof_of_residency_document, r.residency_tenure,
    r.occupation,
    TIMESTAMPDIFF(YEAR, r.birthday, CURDATE()) as age
FROM tbl_user u
JOIN tbl_residents r ON u.user_id = r.user_id
WHERE u.user_id = '$id'";

$result = $conn->query($sql);


$username = "Guest";
$image = "https://barangay400.com/dist/assets/images/default_image.png";

$first_name = $middle_name = $last_name = $mobile = $email = $address = $birthday = $birthplace = $civilStatus = $gender = "";
$precinctNumber = $residentStatus = $voterStatus = $bloodType = $height = $weight = $typeOfID = $IDNumber = "";
$SSSGSIS_Number = $TIN_number = $barangay_number = "";
$age = $is_senior = $is_pwd = $is_4ps_member = 0;
$is_logged_in = 0;
$errors = [];
$success = "";
$resident_data = null;
$matching_members = [];
$household_members = [];
$is_child = false;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?: "default_image.jpg";
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $mobile = $row["mobile"];
    $email = $row["email"];
    $address = $row["address"];
    $birthday = $row["birthday"];
    $birthplace = $row["birthplace"];
    $civilStatus = $row["civilStatus"];
    $gender = $row["gender"];
    $precinctNumber = $row["precinctNumber"];
    $residentStatus = $row["residentStatus"];
    $voterStatus = $row["voterStatus"];
    $bloodType = $row["bloodType"];
    $height = $row["height"];
    $weight = $row["weight"];
    $typeOfID = $row["typeOfID"];
    $IDNumber = $row["IDNumber"];
    $SSSGSIS_Number = $row["SSSGSIS_Number"];
    $TIN_number = $row["TIN_number"];
    $barangay_number = $row["barangay_number"];
    $age = $row["age"];
    $is_senior = $row["is_senior"];
    $is_pwd = $row["is_pwd"];
    $is_4ps_member = $row["is_4ps_member"];
    $is_logged_in = $row['is_logged_in'];
}

$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = $first_name . ' ' . $last_name;

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$first_name = $_POST['first_name'] ?? '';
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$birthday = $_POST['birthday'] ?? '2000-01-01';
$household_head_name = $_POST['household_head_name'] ?? '';
$relationship_to_head = $_POST['relationship_to_head'] ?? '';

$birthday_date = new DateTime($birthday);
$today = new DateTime();
$age = $today->diff($birthday_date)->y;

// Example insert — make sure tbl_residents has matching columns
$stmt = $pdo->prepare("INSERT INTO tbl_residents (first_name, middle_name, last_name, birthday, household_head_name, relationship_to_head) VALUES (:first_name, :middle_name, :last_name, :birthday, :household_head_name, :relationship_to_head)");
$stmt->execute([
    ':first_name' => $first_name,
    ':middle_name' => $middle_name,
    ':last_name' => $last_name,
    ':birthday' => $birthday,
    ':household_head_name' => $household_head_name,
    ':relationship_to_head' => $relationship_to_head
]);


if (isset($_GET['select_id'])) {
    $select_id = intval($_GET['select_id']);
    $stmt = $pdo->prepare("SELECT * FROM tbl_residents WHERE res_id = ?");
    $stmt->execute([$select_id]);
    $resident_data = $stmt->fetch();
    if ($resident_data && isset($resident_data['birthday'])) {
        $birthDate = new DateTime($resident_data['birthday']);
        $age = $birthDate->diff(new DateTime('today'))->y;
        $is_child = ($age < 18);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_existing = ($_POST['is_existing'] ?? '') == '1';
    $is_child = ($_POST['is_child'] ?? '') == '1';

    $fields = ['first_name', 'middle_name', 'last_name', 'birthday', 'birthplace', 'civilStatus', 'mobile', 'gender', 'address', 'voterStatus', 'household_head_name', 'relationship_to_head'];
    foreach ($fields as $field) {
        $$field = clean_input($_POST[$field] ?? '');
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) $errors[] = "First name must contain only letters.";
    if (!preg_match("/^[a-zA-Z\s]+$/", $middle_name)) $errors[] = "Middle name must contain only letters.";
    if (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) $errors[] = "Last name must contain only letters.";
    if (!empty($mobile) && !preg_match("/^[0-9]{11}$/", $mobile)) {
        $errors[] = "Mobile must be exactly 11 digits.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO tbl_residents
            (first_name, middle_name, last_name, birthday, birthplace, civilStatus,
             mobile, gender, address, voterStatus, household_head_name, relationship_to_head, residentStatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')");
        if ($stmt->execute([
            $first_name, $middle_name, $last_name, $birthday, $birthplace, $civilStatus,
            $mobile, $gender, $address, $voterStatus, $household_head_name, $relationship_to_head
        ])) {
            $success = "Family member successfully added.";
        } else {
            $errors[] = "Failed to add resident.";
        }
    }
}

// Show matching options
if (isset($_GET['lastname']) && isset($_GET['middlename'])) {
    $stmt = $pdo->prepare("SELECT * FROM tbl_residents WHERE last_name = ? AND middle_name = ?");
    $stmt->execute([clean_input($_GET['lastname']), clean_input($_GET['middlename'])]);
    $matching_members = $stmt->fetchAll();
}

// Load household members if head is selected
if ($resident_data && $resident_data['household_id']) {
    $stmt = $pdo->prepare("SELECT * FROM tbl_residents WHERE household_id = ?");
    $stmt->execute([$resident_data['household_id']]);
    $household_members = $stmt->fetchAll();
}

// Load current user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM tbl_residents WHERE res_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Get household members
$household_stmt = $conn->prepare("SELECT * FROM tbl_residents WHERE household_id = ?");
$household_stmt->bind_param("i", $user['household_id']);
$household_stmt->execute();
$household_members = $household_stmt->get_result();

// Get potential members with same last name
$potential_stmt = $conn->prepare("SELECT * FROM tbl_residents WHERE household_id IS NULL AND last_name = ? AND res_id != ?");
$potential_stmt->bind_param("si", $user['last_name'], $user['res_id']);
$potential_stmt->execute();
$potential_members = $potential_stmt->get_result();
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
    <?php if (!empty($errors)) {
    echo '<ul style="color:red">';
    foreach ($errors as $err) echo "<li>$err</li>";
    echo '</ul>';
} ?>

<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
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
                            <li class="nav-item"> <a class="nav-link" href="resident_household.php">Household Management</a></li>
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
                     <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h2 class="mb-0 text-center">Resident Household Manager</h2>
                                    </div>
                                   <div class="card-body">
                                    <?php if (!empty($errors)) { ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <?php if ($success) { ?>
                                        <div class="alert alert-success">
                                            <p><?= $success ?></p>
                                        </div>
                                    <?php } ?>
                                
                                    <!-- Resident Form -->
                                    <form method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" id="first_name" name="first_name" value="<?= $resident_data['first_name'] ?? '' ?>" class="form-control" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" id="middle_name" name="middle_name" value="<?= $resident_data['middle_name'] ?? '' ?>" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" value="<?= $resident_data['last_name'] ?? '' ?>" class="form-control" required>
                                            </div>
                                        </div>
                                
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="birthday" class="form-label">Birthday</label>
                                                <input type="date" id="birthday" name="birthday" value="<?= $resident_data['birthday'] ?? '' ?>" class="form-control" required onchange="calculateAge()">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="mobile" class="form-label">Mobile</label>
                                                <input type="text" id="mobile" name="mobile" value="<?= $resident_data['mobile'] ?? '' ?>" class="form-control">
                                            </div>
                                        </div>
                                
                                        <!-- Hide form elements for minors -->
                                        <div id="adultForm" style="display: <?= $is_child ? 'none' : 'block' ?>;">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select id="gender" name="gender" class="form-control" required>
                                                        <option value="Male" <?= (isset($resident_data['gender']) && $resident_data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                        <option value="Female" <?= (isset($resident_data['gender']) && $resident_data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                        <option value="Other" <?= (isset($resident_data['gender']) && $resident_data['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="civilStatus" class="form-label">Civil Status</label>
                                                    <select id="civilStatus" name="civilStatus" class="form-control" required disabled>
                                                        <option value="Single" <?= (isset($resident_data['civilStatus']) && $resident_data['civilStatus'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                        <option value="Married" <?= (isset($resident_data['civilStatus']) && $resident_data['civilStatus'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                        <option value="Widowed" <?= (isset($resident_data['civilStatus']) && $resident_data['civilStatus'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                                        <option value="Divorced" <?= (isset($resident_data['civilStatus']) && $resident_data['civilStatus'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                                        <option value="Separated" <?= (isset($resident_data['civilStatus']) && $resident_data['civilStatus'] == 'Separated') ? 'selected' : ''; ?>>Separated</option>
                                                    </select>
                                                </div>
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="birthplace" class="form-label">Birthplace</label>
                                                    <input type="text" id="birthplace" name="birthplace" value="<?= $resident_data['birthplace'] ?? '' ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" id="address" name="address" value="<?= $resident_data['address'] ?? '' ?>" class="form-control">
                                                </div>
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="age" class="form-label">Age</label>
                                                    <input type="text" id="age" name="age" value="<?= isset($resident_data['birthday']) ? calculateAgeFromDate($resident_data['birthday']) : 'N/A' ?>" class="form-control" readonly>
                                                </div>
                                
                                                <div class="col-md-6">
                                                    <label for="voterStatus" class="form-label">Voter Status</label>
                                                    <select id="voterStatus" name="voterStatus" class="form-control" required <?= $is_child ? 'disabled' : ''; ?>>
                                                        <option value="Select Status" <?= empty($resident_data['voterStatus']) ? 'selected' : ''; ?>>Select Status</option>
                                                        <option value="Registered" <?= isset($resident_data['voterStatus']) && $resident_data['voterStatus'] == 'Registered' ? 'selected' : ''; ?>>Registered</option>
                                                        <option value="Not Registered" <?= isset($resident_data['voterStatus']) && $resident_data['voterStatus'] == 'Not Registered' ? 'selected' : ''; ?>>Not Registered</option>
                                                    </select>
                                                </div>
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="position" class="form-label">Position in Family</label>
                                                    <select id="position" name="position" class="form-control" required <?= $is_child ? 'disabled' : ''; ?>>
                                                        <option value="Father" <?= isset($resident_data['position']) && $resident_data['position'] == 'Father' ? 'selected' : ''; ?>>Father</option>
                                                        <option value="Mother" <?= isset($resident_data['position']) && $resident_data['position'] == 'Mother' ? 'selected' : ''; ?>>Mother</option>
                                                        <option value="Child" <?= isset($resident_data['position']) && $resident_data['position'] == 'Child' && !$is_child ? 'selected' : ''; ?>>Child</option>
                                                        <option value="Relative" <?= isset($resident_data['position']) && $resident_data['position'] == 'Relative' && !$is_child ? 'selected' : ''; ?>>Relative</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="head_of_family_id" class="form-label">Head of Family</label>
                                                    <input type="text" id="head_of_family_id" name="head_of_family_id" value="<?= $head_of_family_full_name ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div id="head_checkbox_container" style="display: none; margin-top: 10px;">
                                                    <input type="checkbox" name="is_head" value="1" id="is_head_checkbox">
                                                    <label for="is_head_checkbox">Set as Head of Family</label>

                                                </div>  
                                                <div class="col-md-6">
                                                    <label for="barangay_id" class="form-label">Barangay ID</label>
                                                    <input type="text" id="barangay_id" name="barangay_id" value="<?= $resident_data['barangay_id'] ?? '' ?>" class="form-control" required <?= $is_child ? 'disabled' : '' ?>>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" name="email" value="<?= $resident_data['email'] ?? '' ?>" class="form-control" <?= $is_child ? 'disabled' : '' ?>>
                                                </div>
                                                
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label for="matching_family" class="form-label">Select Family Members</label>
                                                    <select id="matching_family" name="matching_family" class="form-control">
                                                        <?php foreach ($matching_members as $res): ?>
                                                            <option value="<?= $res['res_id'] ?>"><?= $res['first_name'] . ' ' . $res['middle_name'] . ' ' . $res['last_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <button type="submit" class="btn btn-primary">Save Member</button>
                                    </form>
                                    
                                        <!-- Household Members -->
                                        <?php if (!empty($household_members)): ?>
                                            <h3 class="mt-4">Household Members</h3>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Birthday</th>
                                                        <th>Position</th>
                                                        <th>Role</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($household_members as $member): ?>
                                                        <tr>
                                                            <td><?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] ?></td>
                                                            <td><?= $member['birthday'] ?></td>
                                                            <td><?= $member['position'] ?></td>
                                                            <td><?= $member['res_id'] == $member['head_of_family_id'] ? 'Head of Family' : 'Member' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
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
               // Function to calculate age based on the birthday input
                  function calculateAge() {
                    const birthDate = new Date(document.getElementById("birthday").value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                
                    // Set age field
                    document.getElementById("age").value = age;
                
                    const voterStatus = document.getElementById("voterStatus");
                    const barangayId = document.getElementById("barangay_id");
                    const email = document.getElementById("email");
                    const mobile = document.getElementById("mobile");
                    const position = document.getElementById("position");
                
                    if (age < 18) {
                        voterStatus.value = "Not Registered";
                        voterStatus.disabled = true;
                        barangayId.disabled = true;
                        email.disabled = true;
                        mobile.disabled = true;
                
                        // Limit position choices
                        position.innerHTML = `
                            <option value="Child">Child</option>
                            <option value="Relative">Relative</option>
                        `;
                    } else {
                        voterStatus.disabled = false;
                        barangayId.disabled = false;
                        email.disabled = false;
                        mobile.disabled = false;
                
                        // Restore full position options
                        position.innerHTML = `
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                            <option value="Child">Child</option>
                            <option value="Relative">Relative</option>
                        `;
                    }
                
                    updateHeadCheckbox();
                }

                
                function updateHeadCheckbox() {
                    const position = document.getElementById("position").value;
                    const headCheckbox = document.getElementById("head_checkbox_container");
                
                    if (position === "Father" || position === "Mother") {
                        headCheckbox.style.display = "block";
                    } else {
                        headCheckbox.style.display = "none";
                        document.getElementById("is_head").checked = false;
                    }
                }
                
                document.addEventListener("DOMContentLoaded", function () {
                    calculateAge(); // Initial check on load
                
                    document.getElementById("position").addEventListener("change", updateHeadCheckbox);
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

</html>/script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../dist/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../dist/assets/js/dashboard.js"></script>
</body>

</html>