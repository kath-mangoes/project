<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// ✅ Use active_user_id if switched, otherwise fallback to user_id
$id = isset($_SESSION['active_user_id']) ? $_SESSION['active_user_id'] : $_SESSION['user_id'];

$sql = "SELECT u.first_name, u.middle_name, u.last_name, u.suffix, 
        u.email, u.image, u.is_logged_in, r.address, r.birthday, r.birthplace,
        r.civilStatus, r.bloodType, r.height, r.weight,
        r.precinctNumber, r.SSSGSIS_Number, r.TIN_number
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$image = "https://400.com/default_image.png";
$first_name = $middle_name = $last_name = $suffix = $email = "";
$address = $birthday = $birthplace = $civilStatus = "";
$bloodType = $height = $weight = "";
$precinctNumber = $SSSGSIS_Number = $TIN_number = "";
$is_logged_in = 0;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"];
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $suffix = $row["suffix"];
    $email = $row["email"];
    $address = $row["address"];
    $birthday = $row["birthday"];
    $birthplace = $row["birthplace"];
    $civilStatus = $row["civilStatus"];
    $bloodType = $row["bloodType"];
    $height = $row["height"];
    $weight = $row["weight"];
    $precinctNumber = $row["precinctNumber"];
    $SSSGSIS_Number = $row["SSSGSIS_Number"];
    $TIN_number = $row["TIN_number"];
    $is_logged_in = $row["is_logged_in"];
}

$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Barangay ID Issuance Request | Barangay System</title>
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
                // ✅ Get current active user (switched or logged-in head)
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

if (empty($user_id)) {
    // No valid session, redirect to login
    header("Location: ../login.php");
    exit();
}

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

// Fetch notifications for the current user
$query = "SELECT * FROM tbl_notifications 
        WHERE user_id = ? 
        AND (is_read = 0 OR (is_read = 1 AND date_created > DATE_SUB(NOW(), INTERVAL 24 HOUR)))
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
                <!-- Barangay ID Request Modal -->
                <div class="modal fade" id="IDRequestModal" tabindex="-1" role="dialog" aria-labelledby="IDRequestModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="IDRequestModalLabel">Submit Barangay ID Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="add_bid.php" method="POST" enctype="multipart/form-data" novalidate id="idRequestForm" class="needs-validation">
                                <div class="modal-body">

                                    <!-- Personal Information Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-user-circle me-2"></i>Personal Information</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                                        value="<?= htmlspecialchars($last_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                                        value="<?= htmlspecialchars($first_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="middle_name">Middle Name</label>
                                                    <input type="text" class="form-control" id="middle_name" name="middle_name"
                                                        value="<?= htmlspecialchars($middle_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="suffix">Suffix</label>
                                                    <input type="text" class="form-control" id="suffix" name="suffix"
                                                        value="<?= htmlspecialchars($suffix) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    value="<?= htmlspecialchars($address) ?>" readonly>
                                            </div>                       
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="birthday">Birthday</label>
                                                    <input type="text" class="form-control" id="birthday" name="birthday"
                                                        value="<?= htmlspecialchars($birthday) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="birthplace">Birthplace</label>
                                                    <input type="text" class="form-control" id="birthplace" name="birthplace"
                                                        value="<?= htmlspecialchars($birthplace) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="civilStatus">Civil Status</label>
                                                    <input type="text" class="form-control" id="civilStatus" name="civilStatus"
                                                        value="<?= htmlspecialchars($civilStatus) ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="bloodType">Blood Type</label>
                                                    <input type="text" class="form-control" id="bloodType" name="bloodType"
                                                        value="<?= htmlspecialchars($bloodType) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="height">Height (cm)</label>
                                                    <input type="text" class="form-control" id="height" name="height"
                                                        value="<?= htmlspecialchars($height) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="weight">Weight (kg)</label>
                                                    <input type="text" class="form-control" id="weight" name="weight"
                                                        value="<?= htmlspecialchars($weight) ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- ID Information Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-id-card me-2"></i>Valid ID Number</h5>
                                        <div class="row">
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="precinctNumber">Precinct Number</label>
                                                    <input type="text" class="form-control" id="precinctNumber" name="precinctNumber"
                                                        value="<?= htmlspecialchars($precinctNumber) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="SSSGSIS_Number">SSS/GSIS Number</label>
                                                    <input type="text" class="form-control" id="SSSGSIS_Number" name="SSSGSIS_Number"
                                                        value="<?= htmlspecialchars($SSSGSIS_Number) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="TIN_number">TIN Number</label>
                                                    <input type="text" class="form-control" id="TIN_number" name="TIN_number"
                                                        value="<?= htmlspecialchars($TIN_number) ?>" readonly>
                                                </div>
                                            </div>

                                            
                                        </div>
                                    </div>

                                    <!-- Emergency Contact Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-phone-alt me-2"></i>Emergency Contact Information</h5>
                                        <div class="form-group">
                                            <label for="personTwoName">Contact Person Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoName" name="personTwoName" placeholder="Enter Contact Person Name" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoName_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="personTwoAddress">Contact Person Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoAddress" name="personTwoAddress" placeholder="Enter Contact Person Address" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoAddress_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="personTwoContactInfo">Contact Person Phone Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoContactInfo" name="personTwoContactInfo" maxlength="11" pattern="\d{11}" 
                                                required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" placeholder="Enter Contact Person Phone Number" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoContactInfo_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Document Upload Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Supporting Documents</h5>
                                        <div class="file-upload">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5>Upload Supporting Document/Image</h5>
                                            <p>Please upload any required documents (Valid ID, Proof of Residency, etc.)</p>
                                            <input type="file" class="form-control" id="document_path" name="document_path[]" multiple>
                                            <div class="file-upload-info mt-2">
                                                <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small> <br>
                                                <small><i class="fas fa-info-circle me-1"></i>Hold Ctrl to select multiple files.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Terms and Submit -->
                                    <div class="mb-3 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="terms" required>
                                            <label class="form-check-label" for="terms">I confirm that the information provided is accurate and complete</label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit ID Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Form validation
                        (function() {
                            'use strict';

                            // Fetch all forms to apply validation
                            var forms = document.querySelectorAll('.needs-validation');

                            // Loop and prevent submission
                            Array.prototype.slice.call(forms).forEach(function(form) {
                                form.addEventListener('submit', function(event) {
                                    if (!form.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        })();
                    });
                    
                    document.getElementById('document_path').addEventListener('change', function () {
                            const files = this.files;
                            let fileListHTML = '<ul class="mt-2 mb-0">';
                            
                            for (let i = 0; i < files.length; i++) {
                                fileListHTML += '<li><i class="fas fa-check-circle text-success me-2"></i><strong>' + files[i].name + '</strong></li>';
                            }
                            
                            fileListHTML += '</ul>';

                            const existingInfo = document.querySelector('.file-selected-info');
                            if (existingInfo) {
                                existingInfo.remove();
                            }

                            const fileInfo = document.createElement('div');
                            fileInfo.className = 'file-selected-info';
                            fileInfo.innerHTML = fileListHTML;
                            this.parentNode.appendChild(fileInfo);
                        });
                </script>

                <?php
                include '../connection/config.php';

// Check for success messages
if (isset($_GET['success'])) {
    $successMessages = [
        1 => "Barangay ID Request Submitted Successfully"
    ];

    if (isset($successMessages[$_GET['success']])) {
        echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "success",
        title: "' . $successMessages[$_GET['success']] . '",
        showConfirmButton: false,
        timer: 1500
    });
});
</script>';
    }
}

// Check for error messages
if (isset($_GET['error'])) {
    echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Something went wrong. Please try again.",
        showConfirmButton: true
    });
});
</script>';
}

// ✅ Always use active_user_id if switched, otherwise use user_id
$user_id = isset($_SESSION['active_user_id']) ? $_SESSION['active_user_id'] : $_SESSION['user_id'];

$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Date filter variables
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build WHERE clause for both queries
$where_conditions = ["b.user_id = ?"];
$params = [$user_id];
$types = "s"; // String for user_id (varchar in DB)

// Enhanced search: Get all column names from the tbl_bid table
$columnsQuery = "SHOW COLUMNS FROM tbl_bid";
$columnsResult = $conn->query($columnsQuery);
$searchFields = [];

if ($columnsResult) {
    while ($column = $columnsResult->fetch_assoc()) {
        $searchFields[] = "b." . $column['Field'];
    }
}

if (!empty($search) && !empty($searchFields)) {
    $searchConditions = [];
    foreach ($searchFields as $field) {
        $searchConditions[] = "$field LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= "s";
    }
    $where_conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
}

// Combine WHERE conditions
$where_clause = implode(" AND ", $where_conditions);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM tbl_bid b
WHERE $where_clause";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
$count_stmt->close();

// Fetch ID requests query
$sql = "SELECT b.BID_id, b.user_id, b.last_name, b.first_name, b.middle_name, b.suffix, b.address, 
b.ID_No, b.birthday, b.birthplace, b.status, b.created_at, b.dateApplied, b.dateIssued
FROM tbl_bid b
WHERE $where_clause
ORDER BY b.created_at DESC 
LIMIT ? OFFSET ?";

// Add limit and offset params
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Prepare and execute statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
                ?>

                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Barangay ID Requests</p>
                                    <div class="ml-auto">
                                        <button class="btn btn-primary mb-3" data-toggle="modal"
                                            data-target="#IDRequestModal">Request ID</button>
                                    </div>
                                </div>
                                <!-- Filter section -->

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search ID Requests">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                        style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID Request No.</th>
                                                <th>Full Name</th>
                                                <th>Address</th>
                                                <th>ID Number</th>
                                                <th>Date Applied</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['BID_id']); ?></td>
                                                        <td>
                                                            <?php 
                                                                echo htmlspecialchars(
                                                                    $row['last_name'] . ', ' . 
                                                                    $row['first_name'] . ' ' . 
                                                                    $row['middle_name'] . ' ' . 
                                                                    $row['suffix']
                                                                ); 
                                                            ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['ID_No']); ?></td>
                                                        <td><?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?></td>
                                                        <td>
                                                            <span class="badge 
                                <?php
                                                    if ($row['status'] == 'Approved') echo 'badge-warning text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Processed') echo 'badge-info text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Denied') echo 'badge-danger text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Released') echo 'badge-primary text-white font-weight-bold';
                                                    else echo 'badge-warning text-white font-weight-bold';
                                ?>">
                                                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                                data-target="#viewIDModal<?php echo $row['BID_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewIDModal<?php echo $row['BID_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Enhanced Header with gradient background -->
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                        <i class="fas fa-id-card mr-2"></i>Barangay ID Request Details
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body py-4">
                                                                    <!-- Status badge at top -->
                                                                    <div class="text-center mb-4">
                                                                        <span class="badge badge-pill px-4 py-2 font-weight-bold text-white
                                            <?php
                                                    if ($row['status'] == 'Approved') echo 'badge-warning';
                                                    elseif ($row['status'] == 'Processed') echo 'badge-info';
                                                    elseif ($row['status'] == 'Denied') echo 'badge-danger';
                                                    elseif ($row['status'] == 'Released') echo 'badge-primary';
                                                    else echo 'badge-warning';
                                            ?>">
                                                                            <i class="fas 
                                                <?php
                                                    if ($row['status'] == 'Approved') echo 'fa-check-circle';
                                                    elseif ($row['status'] == 'Processed') echo 'fa-cog';
                                                    elseif ($row['status'] == 'Denied') echo 'fa-times-circle';
                                                    elseif ($row['status'] == 'Released') echo 'fa-paper-plane';
                                                    else echo 'fa-exclamation-circle';
                                                ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                                        </span>
                                                                    </div>

                                                                    <?php
                                                                    // Fetch complete details for the view modal
                                                                    $detail_sql = "SELECT * FROM tbl_bid WHERE BID_id = ?";
                                                                    $detail_stmt = $conn->prepare($detail_sql);
                                                                    $detail_stmt->bind_param("i", $row['BID_id']);
                                                                    $detail_stmt->execute();
                                                                    $detail_result = $detail_stmt->get_result();
                                                                    $detail = $detail_result->fetch_assoc();
                                                                    $detail_stmt->close();
                                                                    ?>

                                                                    <!-- Personal Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                            <!-- Remarks Section (if available) -->
                                                                        <?php if (!empty($row['remarks'])): ?>
                                                                            <div class="card border-0 shadow-sm mb-4">
                                                                                <div class="card-header bg-light py-3">
                                                                                    <h6 class="font-weight-bold text-primary mb-0">
                                                                                        <i class="fas fa-comment-alt mr-2"></i>Remarks
                                                                                    </h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <p><?php echo nl2br(htmlspecialchars($row['remarks'])); ?>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Personal Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Full Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php 
                                                                                            echo htmlspecialchars(
                                                                                                $row['last_name'] . ', ' . 
                                                                                                $row['first_name'] . ' ' . 
                                                                                                $row['middle_name'] . ' ' . 
                                                                                                $row['suffix']
                                                                                            ); 
                                                                                        ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['address']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Birthday</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo date('F d, Y', strtotime($detail['birthday'])); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Birthplace</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['birthplace']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Valid ID Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['ID_No']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Precinct Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['precinctNumber']) ? htmlspecialchars($detail['precinctNumber']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Blood Type</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['bloodType']) ? htmlspecialchars($detail['bloodType']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Height / Weight</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php
                                                                                            echo (!empty($detail['height']) ? htmlspecialchars($detail['height']) . ' cm' : 'N/A');
                                                                                            echo ' / ';
                                                                                            echo (!empty($detail['weight']) ? htmlspecialchars($detail['weight']) . ' kg' : 'N/A');
                                                                                            ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Additional Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-info-circle mr-2"></i>Additional Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">SSSGSIS Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['SSSGSIS_Number']) ? htmlspecialchars($detail['SSSGSIS_Number']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Tin Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['TIN_number']) ? htmlspecialchars($detail['TIN_number']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <!-- Contact Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-address-card mr-2"></i>Contact Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoName']) ? htmlspecialchars($detail['personTwoName']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Address</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoAddress']) ? htmlspecialchars($detail['personTwoAddress']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoContactInfo']) ? htmlspecialchars($detail['personTwoContactInfo']) : 'N/A'; ?></p>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    
                            
                                                                    <!-- Timeline Card -->
                                                                    <div class="card border-0 shadow-sm">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-history mr-2"></i>Request Timeline
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <ul class="timeline">
                                                                                <li class="timeline-item">
                                                                                    <div class="timeline-marker bg-success"></div>
                                                                                    <div class="timeline-content">
                                                                                        <h4 class="timeline-title font-weight-bold">Date Applied</h4>
                                                                                        <p class="timeline-date">
                                                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                                                            <?php echo date('F d, Y h:i A', strtotime($detail['dateApplied'])); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </li>





                                                                                <?php if (!empty($detail['dateIssued']) && $detail['status'] == 'Released'): ?>
                                                                                    <li class="timeline-item">
                                                                                        <div class="timeline-marker bg-success"></div>
                                                                                        <div class="timeline-content">
                                                                                            <h4 class="timeline-title font-weight-bold">Date Released</h4>
                                                                                            <p class="timeline-date">
                                                                                                <i class="far fa-calendar-alt mr-1"></i>
                                                                                                <?php echo date('F d, Y h:i A', strtotime($detail['dateIssued'])); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                <?php endif; ?>


                                                                            </ul>
                                                                        </div>
                                                                    </div>


                                                                <?php endwhile; ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="7" class="text-center">No ID reports found</td>
                                                                </tr>
                                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"
                                                    style="background:color:#141E30 !important;"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true"> &raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>





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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');
            var clearFilters = document.getElementById('clearFilters');
            var filterForm = document.getElementById('filterForm');

            // Original clear button for search form
            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    searchInput.value = '';
                    searchForm.submit(); // Submit the form to reload all records
                });
            }

            // New clear filters button for filter form
            if (clearFilters) {
                clearFilters.addEventListener('click', function() {
                    // Get all date input fields in the filter form
                    var dateInputs = filterForm.querySelectorAll('input[type="date"]');

                    // Clear all date input values
                    dateInputs.forEach(function(input) {
                        input.value = '';
                    });

                    // Submit the filter form to reload with cleared filters
                    filterForm.submit();
                });
            }
        });
        
        //NAME VALIDATION
        function validateLettersOnly(field) {
            field.addEventListener('input', function() {
                const regex = /^[A-Za-z\s]*$/;
                const errorMessage = this.parentElement.querySelector('.error-message');

                if (!regex.test(this.value)) {
                    this.classList.add('is-invalid');
                    if (errorMessage) {
                        errorMessage.textContent = 'Please enter a Valid Input.';
                        errorMessage.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const fieldsToValidate = ['last_name', 'first_name', 'middle_name'];

            fieldsToValidate.forEach(function(id) {
                const field = document.getElementById(id);
                if (field) {
                    validateLettersOnly(field);
                }
            });
        });


        //TIN VALIDATION
        document.addEventListener('DOMContentLoaded', function() {
            const tinInput = document.getElementById('TIN_number');

            tinInput.addEventListener('input', function(e) {
                // Remove non-digit characters
                let value = this.value.replace(/\D/g, '');

                // Apply hyphen formatting
                if (value.length <= 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})$/, '$1-$2-$3');
                } else if (value.length > 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,3})$/, '$1-$2-$3-$4');
                }

                this.value = value;

                const errorMessage = this.parentElement.querySelector('.error-message');

                // Validation
                if (value.replace(/\D/g, '').length < 9 || value.replace(/\D/g, '').length > 12) {
                    this.classList.add('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    if (errorMessage) errorMessage.style.display = 'none';
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
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Add SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->

</body>

</html>