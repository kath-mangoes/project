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
    <title>Barangay Clearance Request | Barangay System</title>
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
                // ✅ Use active_user_id if present, otherwise fallback to normal user_id
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

if (!$user_id) {
    // No session → force login
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
    $markReadQuery = "UPDATE tbl_notifications SET is_read = 1 
                      WHERE notification_id = ? AND user_id = ?";
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
                <!-- Feedback Modal -->
<div class="modal fade" id="FeedbackModal" tabindex="-1" role="dialog" aria-labelledby="FeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="FeedbackModalLabel">Submit Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_feedback.php" method="POST" novalidate id="feedbackForm" class="needs-validation">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="feedback">Feedback</label>
                        <div class="input-group">
                            <textarea class="form-control" id="feedback" name="feedback" rows="5" placeholder="Enter your feedback here" required></textarea>
                            <div class="input-group-append">
                                <span class="input-group-text validation-icon" id="feedback_validation">
                                    <i class="fas fa-check text-success d-none"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Submit -->
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="agreement" required>
                            <label class="form-check-label" for="agreement">
                                I agree that this feedback is accurate and appropriate
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
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
</script>

<?php
include '../connection/config.php';

// ✅ Always use active_user_id if available, fallback to real user_id
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? null;

// If no valid user, force login
if (!$active_user_id) {
    header("Location: ../login.php");
    exit();
}

// ✅ Success messages
if (isset($_GET['success'])) {
    $successMessages = [
        1 => "Feedback Submitted Successfully",
        2 => "Feedback Updated Successfully",
        3 => "Feedback Deleted Successfully"
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

// ✅ Error messages
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

// Pagination + search
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// ✅ Build WHERE clause
$where_conditions = ["f.user_id = ?"];
$params = [$active_user_id];
$types = "i"; // user_id is integer

// Enhanced search: get all feedback table columns
$columnsQuery = "SHOW COLUMNS FROM tbl_feedback";
$columnsResult = $conn->query($columnsQuery);
$searchFields = [];

if ($columnsResult) {
    while ($column = $columnsResult->fetch_assoc()) {
        $searchFields[] = "f." . $column['Field'];
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

// Final WHERE
$where_clause = implode(" AND ", $where_conditions);

// ✅ Count total rows
$count_sql = "SELECT COUNT(*) as total 
              FROM tbl_feedback f
              WHERE $where_clause";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'] ?? 0;
$total_pages = ceil($total_rows / $limit);
$count_stmt->close();

// ✅ Fetch feedbacks
$sql = "SELECT f.feedback_id, f.res_id, f.user_id, f.brgyOfficer_id, 
               f.feedback, f.action, f.action_by, 
               f.dateCreated, f.lastEdited
        FROM tbl_feedback f
        WHERE $where_clause
        ORDER BY f.dateCreated DESC 
        LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

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
                    <p class="card-title mb-0">Feedback History</p>
                    <div class="ml-auto">
                        <button class="btn btn-primary mb-3" data-toggle="modal"
                            data-target="#FeedbackModal">Submit Feedback</button>
                    </div>
                </div>
                <!-- Filter section -->

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <form method="GET" action="" class="form-inline" id="searchForm">
                            <div class="input-group mb-2 mr-sm-2">
                                <input type="text" name="search" id="searchInput" class="form-control"
                                    value="<?php echo htmlspecialchars($search); ?>"
                                    placeholder="Search Feedback">
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
                                <th>Feedback ID</th>
                                <th>Feedback</th>
                                <th>Date Created</th>
                                <th>Action</th>
                                <th>Action By</th>
                                <th>Last Edited</th>
                                <th>Controls</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['feedback_id']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($row['feedback'], 0, 50)) . (strlen($row['feedback']) > 50 ? '...' : ''); ?></td>
                                        <td><?php echo date('F d, Y h:i A', strtotime($row['dateCreated'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['action'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($row['action_by'] ?? 'N/A'); ?></td>
                                        <td><?php echo $row['lastEdited'] ? date('F d, Y h:i A', strtotime($row['lastEdited'])) : 'N/A'; ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#viewModal<?php echo $row['feedback_id']; ?>"><i
                                                    class="fa-solid fa-eye"></i></button>
                                            <button class="btn btn-warning btn-sm edit-feedback" data-toggle="modal"
                                                data-target="#editModal<?php echo $row['feedback_id']; ?>"
                                                data-id="<?php echo $row['feedback_id']; ?>"
                                                data-feedback="<?php echo htmlspecialchars($row['feedback']); ?>">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-feedback" data-toggle="modal"
                                                data-target="#deleteModal<?php echo $row['feedback_id']; ?>"
                                                data-id="<?php echo $row['feedback_id']; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal<?php echo $row['feedback_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content shadow-lg border-0">
                                                <!-- Enhanced Header with gradient background -->
                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                        <i class="fas fa-comment-alt mr-2"></i>Feedback Details
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body py-4">
                                                    <!-- Feedback content card -->
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light py-3">
                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                <i class="fas fa-comment mr-2"></i>Feedback Content
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['feedback'])); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Metadata Card -->
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light py-3">
                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                <i class="fas fa-info-circle mr-2"></i>Feedback Information
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="info-group mb-3">
                                                                        <label class="text-muted small text-uppercase">Feedback ID</label>
                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['feedback_id']); ?></p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label class="text-muted small text-uppercase">Date Created</label>
                                                                        <p class="font-weight-bold mb-2"><?php echo date('F d, Y h:i A', strtotime($row['dateCreated'])); ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="info-group mb-3">
                                                                        <label class="text-muted small text-uppercase">Last Edited</label>
                                                                        <p class="font-weight-bold mb-2"><?php echo $row['lastEdited'] ? date('F d, Y h:i A', strtotime($row['lastEdited'])) : 'Not edited yet'; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Response Card -->
                                                    <?php if (!empty($row['action'])): ?>
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-light py-3">
                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                <i class="fas fa-reply mr-2"></i>Official Response
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="info-group mb-3">
                                                                        <label class="text-muted small text-uppercase">Action Taken</label>
                                                                        <p class="font-weight-bold mb-2"><?php echo nl2br(htmlspecialchars($row['action'])); ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="info-group mb-0">
                                                                        <label class="text-muted small text-uppercase">Responded By</label>
                                                                        <p class="font-weight-bold mb-0"><?php echo htmlspecialchars($row['action_by']); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times mr-1"></i> Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row['feedback_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-warning text-white">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Feedback</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="update_feedback.php" method="POST" class="needs-validation" novalidate>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="feedback_id" value="<?php echo $row['feedback_id']; ?>">
                                                        
                                                        <div class="form-group">
                                                            <label for="edit_feedback<?php echo $row['feedback_id']; ?>">Feedback</label>
                                                            <div class="input-group">
                                                                <textarea class="form-control" id="edit_feedback<?php echo $row['feedback_id']; ?>" name="feedback" rows="5" required><?php echo htmlspecialchars($row['feedback']); ?></textarea>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text validation-icon">
                                                                        <i class="fas fa-check text-success d-none"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Terms and Submit -->
                                                        <div class="mb-3 d-flex justify-content-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="agreement<?php echo $row['feedback_id']; ?>" required>
                                                                <label class="form-check-label" for="agreement<?php echo $row['feedback_id']; ?>">
                                                                    I confirm that the information provided is accurate and appropriate
                                                                </label>
                                                                <div class="invalid-feedback">
                                                                    You must agree before submitting.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-warning">Update Feedback</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row['feedback_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel">Delete Feedback</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="delete_feedback.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="feedback_id" value="<?php echo $row['feedback_id']; ?>">
                                                        <p>Are you sure you want to delete this feedback?</p>
                                                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No feedback found</td>
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
                                    style="background:color:#00563B !important;"><?php echo $i; ?></a>
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