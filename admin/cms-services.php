<!--../dist/assets/images/website/cmsServe/-->
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
    <title>Manage Services Tab | Barangay System</title>
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

        <!-- Main starts here -->
        <div class="container mt-5">
            <div class="card mx-auto" style="max-width: 1500px;">
                <h2 style="padding-left: 20px;">Manage Website</h2>
                <div class="card-body">
                    <h1 class="card-title">Manage Services Tab</h1>
                    <form method="POST" action="add_cms_service.php" enctype="multipart/form-data" class="mb-4">
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Service Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Barangay Certificate">Barangay Certificate</option>
                                <option value="Barangay Blotter and Complaint">Barangay Blotter and Complaint</option>
                                <option value="Barangay Clearances and Services">Barangay Clearances and Services</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea name="requirements" class="form-control" rows="3" required></textarea>
                            <div class="file-upload-info mt-2">
                                <small><i class="fas fa-info-circle me-1"></i>please put a comma to seperate the requirements (e.g, Valid Id, Form)</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                        </div>
                    </form>

                    <h3>Existing Services</h3>
                    <table class="table table-bordered mt-4" style="word-wrap: break-word; table-layout: fixed;">
                        <thead class="table-dark">
                            <tr>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 35%;">Description</th>
                            <th style="width: 15%;">Requirements</th>
                            <th style="width: 10%;">Image</th>
                            <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $services = $conn->query("SELECT * FROM web_services ORDER BY id DESC");
                            while ($row = $services->fetch_assoc()) { ?>
                            <tr>
                                <td class="text-wrap"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="text-wrap"><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                                <td class="text-wrap"><?= htmlspecialchars($row['requirements']) ?></td>
                                <td>
                                <?php if ($row['file']): ?>
                                    <a href="../dist/assets/images/website/cmsServe/<?= $row['file'] ?>" target="_blank">View</a>
                                <?php else: ?>
                                    No File
                                <?php endif; ?>
                                </td>
                                <td>
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <a href="delete_cms_services.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this?')">
                                    <i class="fa fa-trash"></i>
                                </a>

                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <form method="POST" action="update_cms_service.php" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Service</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Service Title</label>
                                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Service Description</label>
                                                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="requirements" class="form-label">Requirements</label>
                                                <textarea name="requirements" class="form-control" rows="3" required><?php echo htmlspecialchars($row['requirements']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Category</label>
                                                <select name="category" class="form-select" required>
                                                    <option value="Barangay Certificate" <?php if ($row['category'] == 'Barangay Certificate') echo 'selected'; ?>>Barangay Certificate</option>
                                                    <option value="Barangay Blotter and Complaint" <?php if ($row['category'] == 'Barangay Blotter and Complaint') echo 'selected'; ?>>Barangay Blotter and Complaint</option>
                                                    <option value="Barangay Clearances and Services" <?php if ($row['category'] == 'Barangay Clearances and Services') echo 'selected'; ?>>Barangay Clearances and Services</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="update_service" class="btn btn-success">Save changes</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>

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
