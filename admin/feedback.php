<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$phone_number = "";
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
    $phone_number = $row["phone_number"];
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
    <title>Feedback Report | Barangay System</title>
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

                        </div>
                    </div>
                </div>
                <?php
                    include '../connection/config.php';

                    // Check for success messages
                    if (isset($_GET['success'])) {
                        $successMessages = [
                            1 => "Clearance Request Submitted Successfully",
                            2 => "Clearance Request Updated Successfully",
                            3 => "Feedback Action Updated Successfully"
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

                    // Initialize variables
                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                    $search = $_GET['search'] ?? '';
                    $page = $_GET['page'] ?? 1;
                    $limit = 10;
                    $offset = ($page - 1) * $limit;

                    // Date filter variables
                    $date_from = $_GET['date_from'] ?? '';
                    $date_to = $_GET['date_to'] ?? '';

                    // Build WHERE clause based on search for feedback table
                    $where_conditions = [];
                    $params = [];
                    $types = "";

                    // Get all column names from the tbl_feedback table for search
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

                    // Combine WHERE conditions
                    $where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1"; // 1=1 ensures valid SQL if no conditions

                    // Count total records for pagination
                    $count_sql = "SELECT COUNT(*) as total FROM tbl_feedback f WHERE $where_clause";

                    if (!empty($params)) {
                        $count_stmt = $conn->prepare($count_sql);
                        $count_stmt->bind_param($types, ...$params);
                        $count_stmt->execute();
                        $count_result = $count_stmt->get_result();
                        $total_rows = $count_result->fetch_assoc()['total'];
                        $count_stmt->close();
                    } else {
                        $count_result = $conn->query($count_sql);
                        $total_rows = $count_result->fetch_assoc()['total'];
                    }

                    $total_pages = ceil($total_rows / $limit);

                    // Fetch feedback data query
                    $sql = "SELECT f.feedback_id, f.res_id, f.user_id, f.brgyOfficer_id, 
                            f.feedback, f.action, f.action_by, f.dateCreated, f.lastEdited
                            FROM tbl_feedback f
                            WHERE $where_clause
                            ORDER BY f.dateCreated DESC 
                            LIMIT ? OFFSET ?";

                    // Add limit and offset params
                    $params[] = $limit;
                    $params[] = $offset;
                    $types .= "ii";

                    // Prepare and execute statement
                    $stmt = $conn->prepare($sql);
                    if (!empty($params)) {
                        $stmt->bind_param($types, ...$params);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    ?>

                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Filter section for request button and validation -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                        <p class="card-title mb-0">Feedback Management</p>
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
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            id="clearButton" style="padding:10px;">&times;</button>
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
                                                    <th>User ID</th>
                                                    <th>Feedback</th>
                                                    <th>Action Taken</th>
                                                    <th>Date Created</th>
                                                    <th>Last Edited</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php while ($row = $result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['feedback_id']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                                            <td><?php echo htmlspecialchars(substr($row['feedback'], 0, 50)) . (strlen($row['feedback']) > 50 ? '...' : ''); ?></td>
                                                            <td><?php echo !empty($row['action']) ? htmlspecialchars(substr($row['action'], 0, 50)) . (strlen($row['action']) > 50 ? '...' : '') : '<span class="text-muted">No action taken</span>'; ?></td>
                                                            <td><?php echo date('F d, Y h:i A', strtotime($row['dateCreated'])); ?></td>
                                                            <td><?php echo !empty($row['lastEdited']) ? date('F d, Y h:i A', strtotime($row['lastEdited'])) : 'N/A'; ?></td>
                                                            <td>
                                                                <span class="badge <?php echo empty($row['action']) ? 'badge-warning' : 'badge-success'; ?> text-white font-weight-bold">
                                                                    <?php echo empty($row['action']) ? 'Pending' : 'Addressed'; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <!-- View button -->
                                                                <button class="btn btn-info btn-sm" data-toggle="modal" title="View Feedback"
                                                                    data-target="#viewModal<?php echo $row['feedback_id']; ?>">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                </button>

                                                                <!-- Edit/Update button -->
                                                                <button class="btn btn-warning btn-sm" data-toggle="modal" title="Respond"
                                                                    data-target="#editModal<?php echo $row['feedback_id']; ?>">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <!-- View Modal -->
                                                        <div class="modal fade" id="viewModal<?php echo $row['feedback_id']; ?>" tabindex="-1"
                                                            role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <!-- Enhanced Header with gradient background -->
                                                                    <div class="modal-header bg-gradient-primary text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                            <i class="fas fa-comment-alt mr-2"></i>Feedback Details
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body py-4">
                                                                        <!-- Status badge at top -->
                                                                        <div class="text-center mb-4">
                                                                            <span class="badge badge-pill px-4 py-2 font-weight-bold text-white
                                                                                <?php echo empty($row['action']) ? 'badge-warning' : 'badge-success'; ?>">
                                                                                <i class="fas <?php echo empty($row['action']) ? 'fa-clock' : 'fa-check-circle'; ?> mr-1"></i>
                                                                                <?php echo empty($row['action']) ? 'Pending' : 'Addressed'; ?>
                                                                            </span>
                                                                        </div>

                                                                        <!-- Feedback Information Card -->
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
                                                                                            <p class="font-weight-bold mb-2">
                                                                                                <?php echo htmlspecialchars($row['feedback_id']); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="info-group mb-3">
                                                                                            <label class="text-muted small text-uppercase">User ID</label>
                                                                                            <p class="font-weight-bold mb-2">
                                                                                                <?php echo htmlspecialchars($row['user_id']); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="info-group mb-3">
                                                                                            <label class="text-muted small text-uppercase">Date Created</label>
                                                                                            <p class="font-weight-bold mb-2">
                                                                                                <?php echo date('F d, Y h:i A', strtotime($row['dateCreated'])); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="info-group mb-3">
                                                                                            <label class="text-muted small text-uppercase">Last Updated</label>
                                                                                            <p class="font-weight-bold mb-2">
                                                                                                <?php echo !empty($row['lastEdited']) ? date('F d, Y h:i A', strtotime($row['lastEdited'])) : 'Not updated yet'; ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Feedback Content Card -->
                                                                        <div class="card border-0 shadow-sm mb-4">
                                                                            <div class="card-header bg-light py-3">
                                                                                <h6 class="font-weight-bold text-primary mb-0">
                                                                                    <i class="fas fa-comment mr-2"></i>Feedback Content
                                                                                </h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="p-3 bg-light rounded">
                                                                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['feedback'])); ?></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Action Taken Card (if available) -->
                                                                        <?php if (!empty($row['action'])): ?>
                                                                            <div class="card border-0 shadow-sm mb-4">
                                                                                <div class="card-header bg-light py-3">
                                                                                    <h6 class="font-weight-bold text-success mb-0">
                                                                                        <i class="fas fa-clipboard-check mr-2"></i>Action Taken
                                                                                    </h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="p-3 bg-light rounded">
                                                                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($row['action'])); ?></p>
                                                                                        <?php if (!empty($row['action_by'])): ?>
                                                                                            <div class="text-muted small mt-3">
                                                                                                <i class="fas fa-user mr-1"></i> Action by: <?php echo htmlspecialchars($row['action_by']); ?>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div class="alert alert-warning">
                                                                                <i class="fas fa-exclamation-triangle mr-2"></i> No action has been taken on this feedback yet.
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
                                                        <div class="modal fade" id="editModal<?php echo $row['feedback_id']; ?>" tabindex="-1"
                                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-warning text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold" id="editModalLabel">
                                                                            <i class="fas fa-edit mr-2"></i>Update Feedback Response
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Make sure the form action points to the correct location -->
                                                                    <form action="edit_feedback.php" method="POST">
                                                                        <div class="modal-body py-4">
                                                                            <input type="hidden" name="feedback_id" value="<?php echo $row['feedback_id']; ?>">

                                                                            <!-- Original Feedback (read-only) -->
                                                                            <div class="form-group">
                                                                                <label for="original_feedback<?php echo $row['feedback_id']; ?>">
                                                                                    <strong>Original Feedback</strong>
                                                                                </label>
                                                                                <div class="p-3 bg-light rounded">
                                                                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['feedback'])); ?></p>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Action Input Field -->
                                                                            <div class="form-group">
                                                                                <label for="action<?php echo $row['feedback_id']; ?>">
                                                                                    <strong>Action Taken</strong>
                                                                                </label>
                                                                                <textarea class="form-control" name="action" 
                                                                                    id="action<?php echo $row['feedback_id']; ?>" 
                                                                                    rows="5" required><?php echo isset($row['action']) ? htmlspecialchars($row['action']) : ''; ?></textarea>
                                                                                <small class="form-text text-muted">
                                                                                    Describe the action taken to address this feedback.
                                                                                </small>
                                                                            </div>

                                                                            <!-- Action By Input Field -->
                                                                            <div class="form-group">
                                                                                <label for="action_by<?php echo $row['feedback_id']; ?>">
                                                                                    <strong>Action By</strong>
                                                                                </label>
                                                                                <input type="text" class="form-control" name="action_by" 
                                                                                    id="action_by<?php echo $row['feedback_id']; ?>" 
                                                                                    value="<?php echo isset($row['action_by']) ? htmlspecialchars($row['action_by']) : (isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''); ?>" required>
                                                                                <small class="form-text text-muted">
                                                                                    Name or position of the person taking action.
                                                                                </small>
                                                                            </div>

                                                                            <div class="alert alert-info">
                                                                                <small><i class="fas fa-info-circle mr-1"></i> Your response will be recorded and may be visible to the user who submitted this feedback.</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer bg-light">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                                            </button>
                                                                            <button type="submit" class="btn btn-success">
                                                                                <i class="fas fa-save mr-1"></i> Update Response
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No feedback found</td>
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
                                                        style="background-color: <?php echo ($i == $page) ? '#141E30' : ''; ?> !important;"><?php echo $i; ?></a>
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
                        <span
                            class="text-muted text-center text-sm-left d-block d-sm-inline-block font-weight-bold">Copyright
                            ©
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