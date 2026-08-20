<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Use active_user_id if set, otherwise fallback to the logged-in user_id
$id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'];

// Fetch the user's data from the "users" and "residents" table based on the active user ID
$sql = "SELECT u.email, u.image, u.is_logged_in, 
               r.first_name, r.middle_name, r.last_name
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize the variables with default values
$image = "https://barangay400.com/default_image.png"; // Default image
$first_name = "";
$last_name = "";
$full_name = "";
$email = "";
$is_logged_in = 0; // Default to not logged in

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $image = $row["image"] ?: $image;
    $first_name = $row["first_name"];
    $last_name  = $row["last_name"];
    $full_name  = trim($first_name . ' ' . $row["middle_name"] . ' ' . $last_name);
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in'];
}

// Assign values to session for use in UI
$_SESSION['image'] = $image;
$_SESSION['full_name'] = $full_name;
$_SESSION['is_logged_in'] = $is_logged_in;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Services | Barangay System</title>
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

            <ul class="navbar-nav mr-lg-2">

            </ul>
            <ul class="navbar-nav navbar-nav-right">
            <?php
                // Get current user ID from session (respect active account switching)
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

if (empty($user_id)) {
    // Not logged in, redirect just in case
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

// Fetch notifications for the current user - Only show unread ones and recently read ones (last 24 hours)
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
        <style>
            .hero-section {
                height: 300px;
                /* Reduced from 400px */
                max-width: 1400px;
                /* Add this to control width */
                margin-left: auto;
                /* Center the hero section */
                margin-right: auto;
                background-image: url('');
                background-size: no-repeat;
                background-position: center;
                border-radius: 24px;
                position: relative;
                margin-bottom: 60px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            }

            .hero-section .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(28, 62, 196, 0.85) 0%, rgba(15, 15, 80, 0.92) 100%);
                z-index: 1;
            }

            .hero-content {
                position: relative;
                z-index: 2;
                text-align: center;
                padding: 130px 20px;
                color: white;
            }

            .hero-content h1 {
                font-size: 48px;
                font-weight: 800;
                margin-bottom: 20px;
                letter-spacing: 0.5px;
                text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            }

            .hero-content p {
                font-size: 20px;
                font-weight: 400;
                max-width: 700px;
                margin: 0 auto;
                opacity: 0.9;
            }

            /* Service Categories Container */
            .services-container {
                padding: 30px 0;
                max-width: 1400px;
                margin: 0 auto;
            }

            /* Category Section - Enhanced */
            .category-section {
                background-color: #ffffff;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.07);
                padding: 40px;
                margin-bottom: 60px;
                transition: all 0.4s ease;
                border: 1px solid rgba(240, 240, 240, 0.8);
            }

            .category-section:hover {
                box-shadow: 0 15px 50px rgba(32, 76, 229, 0.15);
                transform: translateY(-8px);
            }

            /* Category Header - Enhanced */
            .category-header {
                display: flex;
                align-items: center;
                margin-bottom: 40px;
                padding-bottom: 25px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .header-icon {
                width: 70px;
                height: 70px;
                background: #CFE8FF;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 25px;
                box-shadow: 0 10px 20px rgba(32, 76, 229, 0.25);
            }

            .header-icon i {
                font-size: 30px;
                color: #141E30;
            }

            .header-text h2 {
                font-size: 28px;
                font-weight: 700;
                margin: 0 0 8px 0;
                color: #121c50;
            }

            .header-text p {
                font-size: 16px;
                color: #656565;
                margin: 0;
            }

            /* Service Cards Grid - Enhanced */
            .service-cards {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
                gap: 35px;
            }

            /* Service Card - Enhanced */
            .service-card {
                background-color: #fff;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
                transition: all 0.4s ease;
                position: relative;
                display: flex;
                border: 1px solid rgba(0, 0, 0, 0.03);
                height: 100%;
            }

            .service-card:hover {
                transform: translateY(-12px);
                box-shadow: 0 20px 35px rgba(32, 76, 229, 0.18);
                border-color: rgba(32, 76, 229, 0.15);
            }

            /* Premium Indicator - Enhanced */
            .service-card.premium {
                background: linear-gradient(to right bottom, #ffffff, #f5f9ff);
                border: 1px solid rgba(32, 76, 229, 0.08);
            }

            .service-card.featured {
                background: linear-gradient(to right bottom, #ffffff, #f2f7ff);
                border: 1px solid rgba(32, 76, 229, 0.08);
            }

            .card-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #4B49AC;
                color: white;
                padding: 7px 14px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 0.5px;
                box-shadow: 0 5px 15px rgba(32, 76, 229, 0.35);
            }

            /* Service Icon - Enhanced */
            .service-icon {
                width: 90px;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(to bottom, rgba(32, 76, 229, 0.12), rgba(32, 76, 229, 0.06));
            }

            .service-icon i {
                font-size: 30px;
            }

            /* Service Content - Enhanced */
            .service-content {
                padding: 30px 25px;
                flex: 1;
            }

            .service-content h3 {
                font-size: 20px;
                font-weight: 700;
                margin: 0 0 12px 0;
                color: #121c50;
            }

            .service-content p {
                font-size: 15px;
                color: #656565;
                margin-bottom: 20px;
                line-height: 1.6;
            }

            /* Service Meta Info - Enhanced */
            .service-meta {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 20px;
                gap: 12px;
            }

            .service-meta span {
                font-size: 13px;
                color: #555;
                background-color: #f5f7ff;
                padding: 6px 14px;
                border-radius: 25px;
                display: inline-flex;
                align-items: center;
                font-weight: 500;
            }

            .service-meta span i {
                margin-right: 7px;
                font-size: 12px;
                color: #1e40eb;
            }

            /* Apply Button - Enhanced */
            .btn-apply {
                display: inline-flex;
                align-items: center;
                padding: 12px 28px;
                background: #141E30;
                color: white;
                border-radius: 30px;
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 8px 20px rgba(32, 76, 229, 0.25);
                margin-top: 5px;
            }

            .btn-apply i {
                margin-left: 10px;
                font-size: 14px;
                transition: transform 0.3s ease;
            }

            .btn-apply:hover {
                background: rgb(115, 114, 179);
                box-shadow: 0 12px 25px rgba(32, 76, 229, 0.35);
                transform: translateY(-3px);
                color: #fff;
                list-style: none;
                text-decoration: none;
            }

            .btn-apply:hover i {
                transform: translateX(4px);
            }

            /* Animation Effects - Enhanced */
            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-12px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            .service-card.premium .service-icon,
            .service-card.featured .service-icon {
                animation: float 5s ease-in-out infinite;
            }

            /* Responsive Design - Improved */
            @media (max-width: 1200px) {
                .service-cards {
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                }

                .category-section {
                    padding: 35px;
                }
            }

            @media (max-width: 768px) {
                .hero-section {
                    height: 300px;
                }

                .hero-content {
                    padding: 100px 20px;
                }

                .hero-content h1 {
                    font-size: 36px;
                }

                .hero-content p {
                    font-size: 18px;
                }

                .category-section {
                    padding: 25px;
                }

                .service-cards {
                    grid-template-columns: 1fr;
                    gap: 25px;
                }

                .header-icon {
                    width: 60px;
                    height: 60px;
                }

                .header-text h2 {
                    font-size: 24px;
                }
            }
        </style>
        <?php
        // Add this at the top of your services.php file after your HTML head section
// Make sure to include SweetAlert library
        
        // Check for success or error messages from redirects
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: 'Your service request has been submitted successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
    ";
        }

        if (isset($_GET['error']) && $_GET['error'] == 1) {
            $errorMessage = isset($_GET['message']) ? $_GET['message'] : 'An error occurred while processing your request.';
            echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: '$errorMessage',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    </script>
    ";
        }
        ?>
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-header bg-primary text-white">
                                <h2 class="mb-0 text-center">Services</h2>
                            </div>


                            <!-- Barangay Certificates -->
                            <div class="category-section">
                                <div class="category-header">
                                    <div class="header-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="header-text">
                                        <h2>Barangay Certificates</h2>
                                        <p>Official documents for various civic needs</p>
                                    </div>
                                </div>

                                <div class="service-cards">
                                    <!-- Good Moral Certificate -->
                                    <div class="service-card premium">
                                        <!--<div class="card-badge">Popular</div>-->
                                        <div class="service-icon">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Good Moral Character</h3>
                                            <p>Certificate attesting to resident's good standing in the community</p>
                                            <div class="service-meta">
                                               
                                                <span><i class="far fa-clock"></i> Processing: 1-2 days</span>
                                                 <span><i class="far fa-file-alt"></i> Requirements: 4</span>
                                                <span><ul>
                                                    <li>Inhabitants Form</li>
                                                    <li>Letter from the Admin</li>
                                                    <li>Valid ID</li>
                                                    <li>Log Sheet</li>
                                                    </ul>
                                                </span>
                                                
                                                
                                            </div>
                                            <a href="barangay-certificate.php" class="btn-apply">Request Service <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>

                                    <!-- First-time Job Seeker -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>First-time Job Seeker</h3>
                                            <p>Certificate for residents seeking employment for the first time</p>
                                            <div class="service-meta">
                                                
                                                <span><i class="far fa-clock"></i> Processing: 1 day        </span>
                                                <span><i class="far fa-file-alt"></i> Requirements: 4</span>
                                                <span><ul>
                                                    <li>Inhabitants Form</li>
                                                    <li>Letter from the Admin</li>
                                                    <li>Oath of Undertaking</li>
                                                    <li>Valid ID</li>
                                                    </ul>
                                                </span>
                                            </div>
                                            <a href="barangay-certificate.php" class="btn-apply">Request Service<i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>

                                    <!-- Calamity Certificate -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-house-damage"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Calamity</h3>
                                            <p>Certificate for residents affected by natural disasters</p>
                                            <div class="service-meta">
                                                
                                                
                                                <span><i class="far fa-clock"></i> Processing: Same day</span>
                                                <span><i class="far fa-file-alt"></i> Requirements: 3</span>
                                                <span><ul>
                                                    <li>Inhabitants Form</li>
                                                <li>Valid ID</li>
                                                <li>Log Sheet</li>
                                                </ul></span>
                                                
                                            </div>
                                            <a href="barangay-certificate.php" class="btn-apply">Request Service<i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barangay Complaints -->
                            <div class="category-section">
                                <div class="category-header">
                                    <div class="header-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="header-text">
                                        <h2>Barangay Complaints</h2>
                                        <p>File official reports and concerns</p>
                                    </div>
                                </div>

                                <div class="service-cards">
                                    

                                    <!-- Complaints & Grievance -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Complaints & Grievance</h3>
                                            <p>Submit concerns and issues to the barangay council</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-clock"></i> Response: 2-3 days</span>
                                                <span><i class="fas fa-user-check"></i> Tracked</span>
                                            </div>
                                            <a href="barangay-complain.php" class="btn-apply">Submit Complaint <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barangay Clearance & Services -->
                            <div class="category-section">
                                <div class="category-header">
                                    <div class="header-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="header-text">
                                        <h2>Barangay Clearance & Services</h2>
                                        <p>Essential documentation and community services</p>
                                    </div>
                                </div>

                                <div class="service-cards">
                                    <!-- ID Issuance -->
                                    <div class="service-card premium">
                                        <!--<div class="card-badge">New</div>-->
                                        <div class="service-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>ID Issuance</h3>
                                            <p>Application for official barangay identification card</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-clock"></i> Processing: 3-5 days</span>
                                                <span><i class="far fa-file-alt"></i> Requirements: 3</span>
                                                <span><ul>
                                                    
                                                <li>Valid ID</li>
                                                <li>Comelec Stub</li>
                                                <li>Voters Stub</li>
                                                </ul></span>
                                            </div>
                                            <a href="barangay-id.php" class="btn-apply">Request Service <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>

                                    <!-- Barangay Clearance -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Barangay Clearance</h3>
                                            <p>Official document certifying no derogatory record</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-clock"></i> Processing: 1 day</span>
                                                <span><i class="far fa-file-alt"></i> Requirements: 3</span>
                                                <span><ul>
                                                    <li>Inhabitants Form</li>
                                                <li>Valid ID</li>
                                                <li>Letter from Admin</li>
                                                </ul></span>
                                            </div>
                                            <a href="barangay-clearance.php" class="btn-apply">Request Service<i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>

                                    <!-- Garbage Disposal -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Garbage Disposal</h3>
                                            <p>Schedule and request waste collection services</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-calendar-alt"></i> Weekly Service</span>
                                                <span><i class="fas fa-recycle"></i> Segregation Required</span>
                                                
                                            </div>
                                            <a href="barangay-clearance.php" class="btn-apply">Request Service <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>

                                    <!-- Declogging -->
                                    <div class="service-card">
                                        <div class="service-icon">
                                            <i class="fas fa-water"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Declogging</h3>
                                            <p>Request for drainage and canal declogging services</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-clock"></i> Response: 1-2 days</span>
                                                <span><i class="fas fa-hard-hat"></i> Professional Team</span>
                                                <span><i class="far fa-file-alt"></i> Requirements: 2</span>
                                                 <span><ul>
                                                     
                                                <li>Letter Request</li>
                                                <li>Picture of the Canals</li>
                                                
                                                </ul></span>
                                            </div>
                                            <a href="barangay-clearance.php" class="btn-apply">Request Service <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barangay Programs -->
                            <!--<div class="category-section">
                                <div class="category-header">
                                    <div class="header-icon">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <div class="header-text">
                                        <h2>Barangay Programs</h2>
                                        <p>Community initiatives and development projects</p>
                                    </div>
                                </div>

                                <div class="service-cards">
                                   
                                    <div class="service-card featured">

                                        <div class="service-icon">
                                            <i class="fas fa-city"></i>
                                        </div>
                                        <div class="service-content">
                                            <h3>Community Development Program</h3>
                                            <p>Participate in local community improvement initiatives</p>
                                            <div class="service-meta">
                                                <span><i class="far fa-user-circle"></i> Open for Volunteers</span>
                                                <span><i class="fas fa-calendar-week"></i> Monthly Projects</span>
                                            </div>
                                            <a href="services_request.php" class="btn-apply">Join Program <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>-->




                            <!-- content-wrapper ends -->
                            <!-- partial:partials/_footer.html -->
                            <footer class="footer" style="background-color: LightGray;">
                                <div class="d-flex justify-content-center">
                                    <span
                                        class="text-muted text-center text-sm-left d-block d-sm-inline-block font-weight-bold">Copyright
                                        ©
                                        2025-2030. <a href="" target="_blank">Barangay System</a>. All rights
                                        reserved.</span>
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

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>




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