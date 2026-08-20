<?php
session_start();

// ✅ Always check for an active session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Use active_user_id (if switching), otherwise fallback to real login user_id
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'];

include '../connection/config.php';

// ✅ Fetch user data using active_user_id
$sql = "SELECT u.email, u.image, u.is_logged_in,
               r.first_name, r.middle_name, r.last_name
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $active_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize default values
$image = "https://barangay400.com/default_image.png"; 
$first_name = "";
$last_name = "";
$email = "";
$is_logged_in = 0;

// ✅ Fill variables from DB result
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $image = $row["image"] ?: $image;
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $email = $row["email"];
    $is_logged_in = $row["is_logged_in"];

    // ✅ Build full name directly from DB (not just session)
    $full_name = trim($first_name . " " . ($row["middle_name"] ?? '') . " " . $last_name);
} else {
    $full_name = "Unknown User";
}

// ✅ Store into session for easy access in UI
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
    <title>Dashboard | Barangay System</title>
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

    <!-- Boxicons -->
     <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
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
                    class="me-2" alt="logo" /> </a>

            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logos.png"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>


            <ul class="navbar-nav navbar-nav-right">
                <?php
                // ✅ Get current user ID from session (use active_user_id if set)
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

if (empty($user_id)) {
    // If no session found, redirect to login
    header("Location: ../login.php");
    exit();
}

// ✅ Handle mark all as read action
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    $markAllQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE user_id = ?";
    $markAllStmt = $conn->prepare($markAllQuery);
    $markAllStmt->bind_param('s', $user_id);
    $markAllStmt->execute();

    // Redirect back to the current page without the query parameter
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// ✅ Handle mark single notification as read
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

// ✅ Fetch notifications for the current user
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

// ✅ Count unread notifications
$unreadQuery = "SELECT COUNT(*) as count FROM tbl_notifications 
                WHERE user_id = ? AND is_read = 0";

$unreadStmt = $conn->prepare($unreadQuery);
$unreadStmt->bind_param('s', $user_id);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadRow = $unreadResult->fetch_assoc();
$unreadCount = (int)$unreadRow['count'];

// ✅ Time ago function
function time_ago($timestamp)
{
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

// ✅ Notification style helper
function getNotificationStyle($type)
{
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
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="row">
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                <h3 class="font-weight-bold">Welcome Resident <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                                <!-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
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
include '../connection/config.php';

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

// Use active_user_id if it exists, otherwise fallback to logged-in user
$residentID = $_SESSION['active_user_id'] ?? $_SESSION['user_id'];

// Function to get count for a resident using res_id OR user_id
function getUserTableCount($conn, $tableName, $residentID)
{
    $query = "SELECT COUNT(*) as total FROM $tableName WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $residentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        return (int)$row['total'];
    } else {
        return 0;
    }
}

// Function to get monthly data for a specific user
function getUserMonthlyData($conn, $tableName, $dateField, $residentID)
{
    $monthlyData = array_fill(0, 12, 0);

    $query = "SELECT MONTH($dateField) as month, COUNT(*) as count 
              FROM $tableName 
              WHERE YEAR($dateField) = YEAR(CURRENT_DATE()) 
              AND user_id = ?
              GROUP BY MONTH($dateField)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $residentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $monthIndex = (int)$row['month'] - 1;
            $monthlyData[$monthIndex] = (int)$row['count'];
        }
    }

    return $monthlyData;
}

// Get counts per table
$certificationCount = getUserTableCount($conn, 'tbl_certification', $residentID);
$clearanceCount     = getUserTableCount($conn, 'tbl_clearance', $residentID);
$blotterCount       = getUserTableCount($conn, 'tbl_blotter', $residentID);
$complainCount      = getUserTableCount($conn, 'tbl_compgriev', $residentID);

// Get monthly data
$complaintsMonthlyData = getUserMonthlyData($conn, 'tbl_compgriev', 'dateFiled', $residentID);
$blotterMonthlyData    = getUserMonthlyData($conn, 'tbl_blotter', 'dateFiled', $residentID);

$complaintsDataJSON = json_encode($complaintsMonthlyData);
$blotterDataJSON    = json_encode($blotterMonthlyData);
?>

<style>
                    
.card-head {
    padding: 15px 25px;
    background-color: #f9fafb;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.icon-wrapper-y {
    background-color: #dbeafe;
    border-radius: 10px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.icon-wrapper-y i {
    font-size: 30px;
    color: #1e3a8a;
}

.event-details {
    flex-grow: 1;
}

.event-title {
    font-weight: bold;
    font-size: 20px;
    color: #1f2937;
    margin: 0;
}

.event-date {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
}

.view-btn {
    background-color: #111827;
    color: white;
    border: none;
    padding: 4px 12px;
    font-size: 15px;
    border-radius: 10px;
    cursor: pointer;
}
</style>

<?php
include '../connection/config.php'; // adjust if needed

$query = "SELECT title, dateCreated FROM tbl_event ORDER BY dateCreated DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

                <div class="card-head">
                    <div class="icon-wrapper-y">
                        <i class='bx bxs-bell'></i>
                    </div>
                    <div class="event-details">
                        <p class="event-title">Announcement</p>
                        <?php if (!empty($row)) : ?>
                        <p class="event-date">
                            <?php echo $row['title'] . ' | ' . date("M j, Y", strtotime($row['dateCreated'])); ?>
                        </p>
                    <?php else : ?>
                        <p class="event-date">No Announcement</p>
                    <?php endif; ?>
                    </div>
                    <form action="events.php" method="get">
                        <button class="view-btn">VIEW</button>
                    </form>
                </div>
                <br><br>


                <div class="row">
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">YOUR CERTIFICATE REQUEST</p>
                                <p class="fs-30 mb-2"><?php echo $certificationCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">YOUR CLEARANCE REQUEST</p>
                                <p class="fs-30 mb-2"><?php echo $clearanceCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">YOUR BLOTTER</p>
                                <p class="fs-30 mb-2"><?php echo $blotterCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">YOUR COMPLAIN</p>
                                <p class="fs-30 mb-2"><?php echo $complainCount; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Complain Overview</h5>
                                    <a href="barangay-complain.php" class="text-decoration-none">
                                        <i class="mdi mdi-dots-horizontal fs-4"></i>
                                    </a>
                                </div>
                                <p class="font-weight-500"></p>
                                <div id="approvedAreaChart-legend" class="chartjs-legend mt-4 mb-2"></div>
                                <canvas id="approvedAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Blotter Overview</h5>
                                    <a href="blotter.php" class="text-decoration-none">
                                        <i class="mdi mdi-dots-horizontal fs-4"></i>
                                    </a>
                                </div>
                                <p class="font-weight-500"></p>
                                <div id="deniedAreaChart-legend" class="chartjs-legend mt-4 mb-2"></div>
                                <canvas id="deniedAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Then add your chart initialization script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Get data from PHP
                        const complaintsData = <?php echo $complaintsDataJSON; ?>;
                        const blotterData = <?php echo $blotterDataJSON; ?>;

                        // Create the Complaint chart
                        if (document.getElementById('approvedAreaChart')) {
                            const ctx = document.getElementById('approvedAreaChart');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    datasets: [{
                                        label: 'Complaints',
                                        data: complaintsData,
                                        backgroundColor: '#141E30',
                                        borderRadius: 4,
                                        maxBarThickness: 30
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            border: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#9ca3af'
                                            }
                                        },
                                        y: {
                                            grid: {
                                                color: '#f3f4f6'
                                            },
                                            border: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#9ca3af'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: '#000',
                                            padding: 10,
                                            cornerRadius: 4,
                                            displayColors: false
                                        }
                                    }
                                }
                            });
                        }

                        // Create the Blotter chart
                        if (document.getElementById('deniedAreaChart')) {
                            const deniedCtx = document.getElementById('deniedAreaChart');
                            new Chart(deniedCtx, {
                                type: 'bar',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    datasets: [{
                                        label: 'Blotter Cases',
                                        data: blotterData,
                                        backgroundColor: '#EA4335',
                                        borderRadius: 4,
                                        maxBarThickness: 30
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            border: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#9ca3af'
                                            }
                                        },
                                        y: {
                                            grid: {
                                                color: '#f3f4f6'
                                            },
                                            border: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#9ca3af'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: '#000',
                                            padding: 10,
                                            cornerRadius: 4,
                                            displayColors: false
                                        }
                                    }
                                }
                            });
                        }
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>