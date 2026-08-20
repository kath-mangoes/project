<?php
session_start();

// ✅ Always use active_user_id if available, otherwise fallback to real login
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? null;

// Check if no session at all → redirect to login
if (!$active_user_id) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Fetch the active user's data from the database
$id = $active_user_id;

$sql = "SELECT u.email, u.image, u.is_logged_in,
               r.first_name, r.middle_name, r.last_name
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize defaults
$image = "https://barangay400.com/default_image.png"; 
$first_name = "";
$last_name = "";
$full_name = "";
$email = "";
$is_logged_in = 0;

// If found, override defaults
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?? $image;
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"] ?? "";
    $last_name = $row["last_name"];
    $full_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name);
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in'];
}

// ✅ Store in session so the rest of the site can use it
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = $full_name;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Officials | Barangay System</title>
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
               // ✅ Always respect switched accounts
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

// If no user at all → block
if (!$user_id) {
    header("Location: ../login.php");
    exit;
}

// Handle mark all as read
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    $markAllQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE user_id = ?";
    $markAllStmt = $conn->prepare($markAllQuery);
    $markAllStmt->bind_param('s', $user_id);
    $markAllStmt->execute();

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle mark single notification as read
if (isset($_GET['read_notification']) && is_numeric($_GET['read_notification'])) {
    $notificationId = $_GET['read_notification'];
    $markReadQuery = "UPDATE tbl_notifications 
                      SET is_read = 1 
                      WHERE notification_id = ? AND user_id = ?";
    $markReadStmt = $conn->prepare($markReadQuery);
    $markReadStmt->bind_param('is', $notificationId, $user_id);
    $markReadStmt->execute();

    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
        exit;
    }
}

// Fetch notifications for the active user
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

// Function: time ago
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

// Function: notification style
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
                background: #141E30;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 25px;
                box-shadow: 0 10px 20px rgba(32, 76, 229, 0.25);
            }

            .header-icon i {
                font-size: 30px;
                color: white;
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
                background: #141E30;
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
                color: #141E30;
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
                color: #141E30;
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
                            <?php
                            // Include the existing database connection
                            include_once('../connection/config.php');

                            // Query to fetch barangay officials with user information including profile image
                            $sql = "SELECT b.*, u.image 
        FROM tbl_brgyofficer b
        LEFT JOIN tbl_user u ON b.user_id = u.user_id
        WHERE b.status = 'Active' 
        ORDER BY 
        CASE 
            WHEN b.position = 'Barangay Captain' THEN 1
            WHEN b.position = 'Barangay Secretary' THEN 2
            WHEN b.position = 'Barangay Treasurer' THEN 3
            WHEN b.position = 'Kagawad' THEN 4
            WHEN b.position = 'SK Chairman' THEN 5
            ELSE 6
        END";
                            $result = $conn->query($sql);
                            ?>

                            <div class="luxury-container">
                                <div class="luxury-header">
                                    <div class="luxury-title-container">
                                        <h1 class="luxury-title">BARANGAY OFFICIALS</h1>
                                        <div class="luxury-underline"></div>
                                    </div>
                                    <p class="luxury-subtitle">Leadership serving our community with dedication and
                                        integrity</p>
                                </div>

                                <div class="luxury-grid">
                                    <?php
                                    // Check if there are results
                                    if ($result && $result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            // Determine theme colors based on position
                                            $positionColor = "";
                                            $gradientStart = "";
                                            $gradientEnd = "";

                                            switch ($row['position']) {
                                                case 'Barangay Captain':
                                                    $positionColor = "#141E30";
                                                    break;
                                                case 'Barangay Secretary':
                                                    $positionColor = "#141E30";
                                                    break;
                                                case 'Barangay Treasurer':
                                                    $positionColor = "#141E30";
                                                    break;
                                                case 'Kagawad':
                                                    $positionColor = "#FFCE26";
                                                    break;
                                                case 'SK Chairman':
                                                    $positionColor = "#DB504A";
                                                    break;
                                                default:
                                                    $positionColor = "#141E30";
                                            }

                                            // Format term dates
                                            $startTerm = date('F d, Y', strtotime($row['startTerm']));
                                            $endTerm = !empty($row['endTerm']) ? date('F d, Y', strtotime($row['endTerm'])) : 'Present';

                                            // Format name properly with middle initial
                                            $middleInitial = !empty($row['middle_name']) ? substr($row['middle_name'], 0, 1) . '.' : '';
                                            $fullName = $row['first_name'] . ' ' . $middleInitial . ' ' . $row['last_name'];

                                            // Get profile image path or use default
                                            $imagePath = !empty($row['image']) ? '../dist/assets/images/user/' . $row['image'] : '../dist/assets/images/user/default-avatar.png';
                                            ?>
                                            <div class="luxury-card"
                                                data-position="<?php echo htmlspecialchars($row['position']); ?>">
                                                <div class="luxury-card-inner">
                                                    <div class="luxury-card-front">
                                                        <div class="luxury-position-indicator"
                                                            style="background: #141E30; color: white;">
                                                            <div class="luxury-position-title">
                                                                <?php echo htmlspecialchars($row['position']); ?></div>
                                                        </div>

                                                        <div class="luxury-profile-section">
                                                            <div class="luxury-profile-image-container"
                                                                style="border: 3px solid <?php echo $positionColor; ?>">
                                                                <img src="<?php echo $imagePath; ?>"
                                                                    alt="<?php echo htmlspecialchars($fullName); ?>"
                                                                    class="luxury-profile-image"
                                                                    onerror="this.src='../dist/assets/images/user/default-avatar.png'">
                                                            </div>

                                                            <h2 class="luxury-official-name">
                                                                <?php echo htmlspecialchars($fullName); ?></h2>
                                                        </div>

                                                        <div class="luxury-details-section">
                                                            <div class="luxury-detail-item">
                                                                <div class="luxury-icon-container"
                                                                    style="background-color: <?php echo $positionColor; ?>">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                </div>
                                                                <div class="luxury-detail-text">
                                                                    <?php echo htmlspecialchars($row['address']); ?></div>
                                                            </div>

                                                            <div class="luxury-detail-item">
                                                                <div class="luxury-icon-container"
                                                                    style="background-color: <?php echo $positionColor; ?>">
                                                                    <i class="fas fa-phone"></i>
                                                                </div>
                                                                <div class="luxury-detail-text">
                                                                    <?php echo htmlspecialchars($row['mobile']); ?></div>
                                                            </div>

                                                            <div class="luxury-detail-item">
                                                                <div class="luxury-icon-container"
                                                                    style="background-color: <?php echo $positionColor; ?>">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                </div>
                                                                <div class="luxury-detail-text">
                                                                    <?php echo $startTerm . ' - ' . $endTerm; ?></div>
                                                            </div>
                                                        </div>

                                                        <div class="luxury-card-footer"
                                                            style="background: linear-gradient(135deg, <?php echo $gradientStart; ?>, <?php echo $gradientEnd; ?>)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<div class='luxury-no-results'><p>No officials found.</p></div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <style>
                                /* Ultra Luxury High-End Styling */

                                .luxury-container {
                                    padding: 50px 20px;
                                    background-color: #f8f9fa;
                                    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+CiAgPGcgZmlsbD0iI2VlZWVlZSIgZmlsbC1vcGFjaXR5PSIwLjQiPgogICAgPGNpcmNsZSBjeD0iMyIgY3k9IjMiIHI9IjMiLz4KICA8L2c+Cjwvc3ZnPg==');
                                    background-attachment: fixed;
                                    position: relative;
                                }

                                .luxury-header {
                                    text-align: center;
                                    margin-bottom: 60px;
                                    padding-bottom: 20px;
                                    position: relative;
                                }

                                .luxury-title {
                                    font-size: 3.5rem;
                                    font-weight: 800;
                                    letter-spacing: 3px;
                                    color: #1a1a2e;
                                    margin: 0;
                                    padding: 10px 25px;
                                    text-transform: uppercase;
                                    position: relative;
                                    display: inline-block;
                                    text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
                                    background: linear-gradient(to right, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
                                    border-radius: 8px;
                                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                                    border-left: 5px solid #141E30;
                                    border-right: 5px solid #141E30;
                                }

                                .luxury-underline {
                                    position: absolute;
                                    bottom: -10px;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    height: 6px;
                                    width: 80%;
                                    background: linear-gradient(to right, #141E30,rgb(12, 21, 36), #141E30);
                                    border-radius: 3px;
                                    box-shadow: 0 3px 10px rgba(67, 97, 238, 0.3);
                                }

                                .luxury-subtitle {
                                    font-size: 1.3rem;
                                    color: #444;
                                    font-weight: 400;
                                    max-width: 700px;
                                    margin: 20px auto 0;
                                    position: relative;
                                    padding: 10px 20px;
                                    background-color: rgba(255, 255, 255, 0.7);
                                    border-radius: 50px;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                                }

                                /* Decorative elements for the title */
                                .luxury-title-container::before,
                                .luxury-title-container::after {
                                    content: "";
                                    position: absolute;
                                    width: 30px;
                                    height: 30px;
                                    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%234361ee"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>');
                                    background-size: contain;
                                    background-repeat: no-repeat;
                                    opacity: 0.6;
                                }

                                .luxury-title-container::before {
                                    left: -15px;
                                    top: 50%;
                                    transform: translateY(-50%) rotate(-25deg);
                                }

                                .luxury-title-container::after {
                                    right: -15px;
                                    top: 50%;
                                    transform: translateY(-50%) rotate(25deg);
                                }


                                .luxury-grid {
                                    display: grid;
                                    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                                    gap: 40px;
                                    margin: 0 auto;
                                    max-width: 1400px;
                                }

                                .luxury-card {
                                    perspective: 1500px;
                                    height: 500px;
                                    opacity: 0;
                                    transform: translateY(30px);
                                    transition: opacity 0.6s ease, transform 0.6s ease;
                                }

                                .luxury-card.animate {
                                    opacity: 1;
                                    transform: translateY(0);
                                }

                                .luxury-card-inner {
                                    position: relative;
                                    width: 100%;
                                    height: 100%;
                                    text-align: center;
                                    transition: transform 0.8s;
                                    transform-style: preserve-3d;
                                }

                                .luxury-card:hover .luxury-card-inner {
                                    transform: translateY(-15px);
                                }

                                .luxury-card-front {
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    -webkit-backface-visibility: hidden;
                                    backface-visibility: hidden;
                                    border-radius: 15px;
                                    overflow: hidden;
                                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                                    background-color: white;
                                    display: flex;
                                    flex-direction: column;
                                }

                                .luxury-position-indicator {
                                    height: 70px;
                                    width: 100%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                }

                                .luxury-position-title {
                                    color: white;
                                    font-weight: 700;
                                    font-size: 1.4rem;
                                    letter-spacing: 1px;
                                    text-transform: uppercase;
                                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                }

                                .luxury-profile-section {
                                    padding: 30px 20px;
                                    position: relative;
                                }

                                .luxury-profile-image-container {
                                    width: 150px;
                                    height: 150px;
                                    border-radius: 50%;
                                    overflow: hidden;
                                    margin: 0 auto 20px;
                                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                                    position: relative;
                                }

                                .luxury-profile-image {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    transition: transform 0.5s ease;
                                }

                                .luxury-card:hover .luxury-profile-image {
                                    transform: scale(1.1);
                                }

                                .luxury-official-name {
                                    font-size: 1.8rem;
                                    font-weight: 700;
                                    margin: 15px 0;
                                    color: #1a1a2e;
                                }

                                .luxury-details-section {
                                    padding: 0 30px 30px;
                                    flex-grow: 1;
                                }

                                .luxury-detail-item {
                                    display: flex;
                                    align-items: center;
                                    margin-bottom: 20px;
                                    position: relative;
                                }

                                .luxury-icon-container {
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    margin-right: 15px;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                                }

                                .luxury-detail-text {
                                    font-size: 1rem;
                                    color: #555;
                                    text-align: left;
                                    flex-grow: 1;
                                }

                                .luxury-card-footer {
                                    height: 10px;
                                    width: 100%;
                                }

                                .luxury-no-results {
                                    grid-column: 1 / -1;
                                    text-align: center;
                                    padding: 30px;
                                    background-color: white;
                                    border-radius: 10px;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                                }

                                /* Advanced responsive design */
                                @media (max-width: 1200px) {
                                    .luxury-grid {
                                        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                                    }
                                }

                                @media (max-width: 768px) {
                                    .luxury-title {
                                        font-size: 2.5rem;
                                    }

                                    .luxury-grid {
                                        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                                        gap: 30px;
                                    }

                                    .luxury-card {
                                        height: 480px;
                                    }

                                    .luxury-profile-image-container {
                                        width: 130px;
                                        height: 130px;
                                    }
                                }

                                @media (max-width: 576px) {
                                    .luxury-title {
                                        font-size: 2rem;
                                    }

                                    .luxury-grid {
                                        grid-template-columns: 1fr;
                                        max-width: 350px;
                                        margin: 0 auto;
                                    }

                                    .luxury-profile-image-container {
                                        width: 120px;
                                        height: 120px;
                                    }
                                }
                            </style>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Premium animation sequence for cards
                                    const cards = document.querySelectorAll('.luxury-card');

                                    // Create intersection observer for elegant fade-in
                                    const observer = new IntersectionObserver((entries) => {
                                        entries.forEach((entry) => {
                                            if (entry.isIntersecting) {
                                                setTimeout(() => {
                                                    entry.target.classList.add('animate');
                                                }, 100 * Array.from(cards).indexOf(entry.target));
                                                observer.unobserve(entry.target);
                                            }
                                        });
                                    }, { threshold: 0.1 });

                                    // Observe each card
                                    cards.forEach(card => {
                                        observer.observe(card);
                                    });

                                    // Add hover effect sound (subtle)
                                    cards.forEach(card => {
                                        card.addEventListener('mouseenter', function () {
                                            // Create subtle audio feedback (optional)
                                            // const hoverSound = new Audio('hover.mp3');
                                            // hoverSound.volume = 0.1;
                                            // hoverSound.play();
                                        });
                                    });

                                    // Add parallax effect to cards on mouse move
                                    document.addEventListener('mousemove', function (e) {
                                        const cards = document.querySelectorAll('.luxury-card-inner');
                                        const mouseX = e.clientX;
                                        const mouseY = e.clientY;

                                        cards.forEach(card => {
                                            const rect = card.getBoundingClientRect();
                                            const cardCenterX = rect.left + rect.width / 2;
                                            const cardCenterY = rect.top + rect.height / 2;

                                            const angleY = (mouseX - cardCenterX) * 0.01;
                                            const angleX = (cardCenterY - mouseY) * 0.01;

                                            // Only apply effect if mouse is relatively close to the card
                                            const distance = Math.sqrt(Math.pow(mouseX - cardCenterX, 2) + Math.pow(mouseY - cardCenterY, 2));
                                            if (distance < 400) {
                                                card.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) translateY(-10px)`;
                                            } else {
                                                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>




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