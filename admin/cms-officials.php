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
    <title>Manage Officials Tab | Barangay System</title>
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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

    <div class="container py-4">
    <h2 class="mb-4">Manage Officials</h2>

    <!-- Add Official Form -->
    <form action="add_cms_official.php" method="POST" enctype="multipart/form-data" class="row g-3 bg-light p-4 rounded shadow-sm mb-5">
        <div class="col-md-4">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="Official Name" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Position</label>
            <select name="position" class="form-select" required>
                <option value="" disabled selected>Select Position</option>
                <option>Barangay Captain</option>
                <option>Barangay Secretary</option>
                <option>Barangay Treasurer</option>
                <option>Barangay Kagawad</option>
                <option>Lupon Tagapamayapa</option>
                <option>Barangay Tanod</option>
                <option>SK Chairman</option>
                <option>SK Secretary</option>
                <option>SK Kagawad</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Committee</label>
            <select name="committee" class="form-select" required>
                <option value="" disabled selected>Select Committee</option>
                <option>Barangay Council</option>
                <option>Barangay Committees</option>
                <option>Sangguniang Kabataan</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Profile Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="col-12 text-end">
            <button type="submit" name="add_official" class="btn btn-primary">Add Official</button>
        </div>
    </form>

    <!-- Officials List -->
    <h3 class="mb-3">Existing Officials</h3>
    <div class="table-responsive">
        <!-- Table of Officials -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Committee</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $officials = $conn->query("SELECT * FROM web_officials");
while ($row = $officials->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['position']; ?></td>
                    <td><?= $row['committee']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><img src="../dist/assets/images/website/cmsOff/<?= $row['image']; ?>" width="50"></td>
                    <td>
                        <!-- Edit Icon Button -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </button>

                        <!-- Delete Icon Link -->
                        <a href="delete_cms_official.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this official?')" title="Delete">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="update_cms_official.php" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id']; ?>">Edit Official</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $row['name']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Position</label>
                                        <select name="position" class="form-select" required>
                                            <?php 
                                            $positions = ["Barangay Captain", "Barangay Secretary", "Barangay Treasure", "Barangay Kagawad", "Lupon Tagapamayapa", "Barangay Tanod", "SK Chairman", "SK Secretary", "SK Kagawad"];
                                            foreach ($positions as $position) {
                                                $selected = $row['position'] == $position ? "selected" : "";
                                                echo "<option value='$position' $selected>$position</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Committee</label>
                                        <select name="committee" class="form-select" required>
                                            <option value="Barangay Council" <?= $row['committee'] == 'Barangay Council' ? 'selected' : '' ?>>Barangay Council</option>
                                            <option value="Barangay Committees" <?= $row['committee'] == 'Barangay Committees' ? 'selected' : '' ?>>Barangay Committees</option>
                                            <option value="Sangguniang Kabataan" <?= $row['committee'] == 'Sangguniang Kabataan' ? 'selected' : '' ?>>Sangguniang Kabataan</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>New Image (optional)</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update_official" class="btn btn-success">Save Changes</button>
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
