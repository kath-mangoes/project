<?php



session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Fetch the user's data from the "users" table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT u.email, u.mobile, u.image, u.is_logged_in, 
               b.first_name, b.middle_name, b.last_name
        FROM tbl_user u
        JOIN tbl_brgyofficer b ON u.user_id = b.user_id
        WHERE u.user_id = '$id'";
$result = $conn->query($sql);

// Initialize the variables with default values
$image = "../public_html/default_image.png"; // Assuming default image name
$first_name = "";
$last_name = "";
$mobile = "";
$email = "";
$is_logged_in = 0; // Default to not logged in

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"];
    $full_name = $_SESSION['full_name']; // Fetch full name from session
    $mobile = $row["mobile"];
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in']; // Now safe to use
}

// Assign the value of $username and $uploadID to $_SESSION variables
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in; // Using the initialized variable
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

            .btn-primary {
                background-color: #141E30 !important;
            }

            .btn-primary:hover {
                background-color:rgb(16, 21, 36)  !important;
            }

            .btn-outline-primary:hover {
                background-color: #141E30  !important;
            }

            .page-item.active .page-link {
                background-color: #4B49AC !important;
                border-color: rgb(67, 64, 141) !important;
            }

            .page-item.active .page-link:hover {
                background-color: rgb(67, 64, 141) !important;
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
                    <a class="nav-link" href="residents.php">
                        <i class="fa-solid fa-user"></i>
                        <span class="menu-title">Residents</span>
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
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
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
                        1 => "Official Added Successfully",
                        2 => "Official Updated Successfully",
                        3 => "Official Deleted Successfully",
                        4 => "Official Status Updated Successfully"
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

                // Get the officer position from tbl_brgyofficer
                $position = 'Unknown'; // Default value
                $canEdit = false;      // Default permission

                if (!empty($user_id)) {
                    $position_query = "SELECT position FROM tbl_brgyofficer WHERE user_id = ? AND status = 'Active'";
                    $position_stmt = $conn->prepare($position_query);
                    $position_stmt->bind_param("s", $user_id);
                    $position_stmt->execute();
                    $position_result = $position_stmt->get_result();

                    if ($position_result->num_rows > 0) {
                        $position = $position_result->fetch_assoc()['position'];
                        // Only Barangay Secretary can edit/update residents
                        $canEdit = ($position == 'Barangay Secretary');
                    }
                    $position_stmt->close();
                }

                // Build WHERE clause based on search
                $where_conditions = [];
                $params = [];
                $types = "";

                if (!empty($search)) {
                    $searchValue = "%$search%";
                    $where_conditions[] = "(b.first_name LIKE ? OR b.middle_name LIKE ? OR b.last_name LIKE ? OR b.address LIKE ? OR b.mobile LIKE ? OR b.user_id LIKE ?)";
                    $params = array_merge($params, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
                    $types .= "ssssss";
                }

                // Combine WHERE conditions
                $where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

                // Count total records for pagination
                $count_sql = "SELECT COUNT(*) as total FROM tbl_brgyofficer b JOIN tbl_user u ON b.user_id = u.user_id WHERE $where_clause";

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

                // Fetch official data query
                $sql = "SELECT b.*, u.account_status, u.email, u.mobile
        FROM tbl_brgyofficer b 
        JOIN tbl_user u ON b.user_id = u.user_id 
        WHERE $where_clause 
        ORDER BY b.last_name ASC 
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

                // Get the next res_id for new resident
                $next_id_query = "SELECT MAX(brgyOfficer_id) as max_id FROM tbl_brgyofficer";
                $next_id_result = $conn->query($next_id_query);
                $next_id = 1; // Default if no records yet
                if ($next_id_result->num_rows > 0) {
                    $next_id = $next_id_result->fetch_assoc()['max_id'] + 1;
                }
                ?>

                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <!-- Header section -->
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Barangay Official Management</p>

                                </div>

                                <!-- Position info and permissions -->
                                <div class="alert alert-info mb-3" style="background-color: #CFE8FF;">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>Current Position:</strong>
                                            <?php echo htmlspecialchars($position); ?>
                                            <?php if ($canEdit): ?>
                                                <span class="badge badge-success ml-2 font-weight-bold text-white">Can
                                                    Manage Official</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary ml-2">View Only Access</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search section -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search by name, address, mobile, or ID">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        id="clearButton" style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Residents table -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Official ID</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Age</th>
                                                <th>Position</th>
                                                <th>Start Term</th>
                                                <th>End Term</th>
                                                <th>Account Status</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()):
                                                    // Calculate age from birthday
                                                    $birthday = new DateTime($row['birthday']);
                                                    $today = new DateTime();
                                                    $age = $birthday->diff($today)->y;
                                                ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['mobile'] ?? ''); ?></td>

                                                        <td><?php echo $age; ?></td>
                                                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                                                        <td><?php echo htmlspecialchars(date('F d, Y h:i A', strtotime($row['startTerm']))); ?></td>
<td>
  <?php echo !empty($row['endTerm']) ? htmlspecialchars(date('F d, Y h:i A', strtotime($row['endTerm']))) : 'N/A'; ?>
</td>

                                                        <td>
                                                            <span
                                                                class="badge <?php echo ($row['account_status'] == 'Active') ? 'badge-success' : 'badge-danger'; ?> text-white font-weight-bold">
                                                                <?php echo htmlspecialchars($row['account_status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge <?php echo ($row['status'] == 'Active') ? 'badge-success' : 'badge-danger'; ?> text-white font-weight-bold">
                                                                <?php echo htmlspecialchars($row['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <!-- View button for all positions -->
                                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                                data-target="#viewModal<?php echo $row['brgyOfficer_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>

                                                            <?php if ($canEdit): ?>
                                                                <!-- Edit button -->
                                                                <button class="btn btn-warning btn-sm" data-toggle="modal" 
                                                                    data-target="#editModal<?php echo $row['brgyOfficer_id']; ?>">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button>

                                                                <!-- Status toggle button -->
                                                                <?php if ($row['account_status'] == 'Active'): ?>
                                                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                                        data-target="#deactivateModal<?php echo $row['brgyOfficer_id']; ?>">
                                                                        <i class="fa-solid fa-user-slash"></i>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                                                        data-target="#activateModal<?php echo $row['brgyOfficer_id']; ?>">
                                                                        <i class="fa-solid fa-user-check"></i>
                                                                    </button>
                                                                <?php endif; ?>

                                                                <!-- Delete button -->
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                                    data-target="#deleteModal<?php echo $row['brgyOfficer_id']; ?>">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewModal<?php echo $row['brgyOfficer_id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold"
                                                                        id="viewModalLabel">
                                                                        <i class="fas fa-user mr-2"></i>Official Information
                                                                    </h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <!-- Status badge at top -->
                                                                    <div class="text-center mb-4">
                                                                        <span
                                                                            class="badge badge-pill px-4 py-2 font-weight-bold text-white
                                                            <?php echo ($row['account_status'] == 'Active') ? 'badge-success' : 'badge-danger'; ?>">
                                                                            <i
                                                                                class="fas <?php echo ($row['account_status'] == 'Active') ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars($row['account_status']); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Personal Information -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Personal
                                                                                Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Official
                                                                                            ID</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['user_id']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Full
                                                                                            Name</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Official
                                                                                            Position</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['position']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Birthday</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo date('F d, Y', strtotime($row['birthday'])); ?>
                                                                                            (<?php echo $age; ?> years old)
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Mobile
                                                                                            Number</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['mobile']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Email</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['email']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['address']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">
                                                                        <i class="fas fa-times mr-1"></i> Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <?php if ($canEdit): ?>
                                                        <!-- Edit Modal -->
                                                        <div class="modal fade" id="editModal<?php echo $row['brgyOfficer_id']; ?>"
                                                            tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-warning text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="editModalLabel">
                                                                            <i class="fas fa-edit mr-2"></i>Edit Official
                                                                            Information
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="update_official.php" method="POST"
                                                                        class="needs-validation" novalidate>
                                                                        <div class="modal-body py-4">
                                                                            <input type="hidden" name="brgyOfficer_id"
                                                                                value="<?php echo $row['brgyOfficer_id']; ?>">
                                                                            <input type="hidden" name="user_id"
                                                                                value="<?php echo $row['user_id']; ?>">

                                                                            <!-- Personal Information -->
                                                                            <div class="card mb-4">
                                                                                <div class="card-header bg-light">
                                                                                    <h6 class="m-0 font-weight-bold text-primary">
                                                                                        Personal Information</h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="last_name<?php echo $row['brgyOfficer_id']; ?>">Last
                                                                                                    Name</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="last_name<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="last_name"
                                                                                                    value="<?php echo htmlspecialchars($row['last_name']); ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="first_name<?php echo $row['brgyOfficer_id']; ?>">First
                                                                                                    Name</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="first_name<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="first_name"
                                                                                                    value="<?php echo htmlspecialchars($row['first_name']); ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="middle_name<?php echo $row['brgyOfficer_id']; ?>">Middle
                                                                                                    Name</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="middle_name<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="middle_name"
                                                                                                    value="<?php echo htmlspecialchars($row['middle_name']); ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="birthday<?php echo $row['brgyOfficer_id']; ?>">Birthday</label>
                                                                                                <input type="date"
                                                                                                    class="form-control"
                                                                                                    id="birthday<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="birthday"
                                                                                                    value="<?php echo $row['birthday']; ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="mobile<?php echo $row['brgyOfficer_id']; ?>">Mobile
                                                                                                    Number</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="mobile<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="mobile"
                                                                                                    value="<?php echo htmlspecialchars($row['mobile']); ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="address<?php echo $row['brgyOfficer_id']; ?>">Address</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="address<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="address"
                                                                                                    value="<?php echo htmlspecialchars($row['address']); ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Term Information -->
                                                                            <div class="card mb-4">
                                                                                <div class="card-header bg-light">
                                                                                    <h6 class="m-0 font-weight-bold text-primary">
                                                                                        Term and Position Information</h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="startTerm<?php echo $row['brgyOfficer_id']; ?>">Start of Term</label>
                                                                                                <input type="date"
                                                                                                    class="form-control"
                                                                                                    id="startTerm<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="startTerm"
                                                                                                    value="<?php echo isset($row['startTerm']) ? $row['startTerm'] : ''; ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="endTerm<?php echo $row['brgyOfficer_id']; ?>">End of Term</label>
                                                                                                <input type="date"
                                                                                                    class="form-control"
                                                                                                    id="endTerm<?php echo $row['brgyOfficer_id']; ?>"
                                                                                                    name="endTerm"
                                                                                                    value="<?php echo isset($row['endTerm']) ? $row['endTerm'] : ''; ?>"
                                                                                                    required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="position<?php echo $row['brgyOfficer_id']; ?>">Position</label>
                                                                                                <select id="position<?php echo $row['brgyOfficer_id']; ?>" name="position" class="form-control" required>
                                                                                                    <option value="">Select Position</option>
                                                                                                    <option value="Barangay Captain" <?php echo ($row['position'] == 'Barangay Captain') ? 'selected' : ''; ?>>Barangay Captain</option>
                                                                                                    <option value="Barangay Secretary" <?php echo ($row['position'] == 'Barangay Secretary') ? 'selected' : ''; ?>>Barangay Secretary</option>
                                                                                                    <option value="Barangay Treasurer" <?php echo ($row['position'] == 'Barangay Treasurer') ? 'selected' : ''; ?>>Barangay Treasurer</option>
                                                                                                    <option value="Kagawad" <?php echo ($row['position'] == 'Kagawad') ? 'selected' : ''; ?>>Kagawad</option>
                                                                                                    <option value="SK Chairman" <?php echo ($row['position'] == 'SK Chairman') ? 'selected' : ''; ?>>SK Chairman</option>
                                                                                                    <option value="Other" <?php echo ($row['position'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="status<?php echo $row['brgyOfficer_id']; ?>">Status</label>
                                                                                                <select id="status<?php echo $row['brgyOfficer_id']; ?>" name="status" class="form-control" required>
                                                                                                    <option value="">Select Status</option>
                                                                                                    <option value="Active" <?php echo ($row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                                                                    <option value="Inactive" <?php echo ($row['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                                            </button>
                                                                            <button type="submit" class="btn btn-warning">
                                                                                <i class="fas fa-save mr-1"></i> Update Official
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Activate Modal -->
                                                        <div class="modal fade" id="activateModal<?php echo $row['brgyOfficer_id']; ?>"
                                                            tabindex="-1" role="dialog" aria-labelledby="activateModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-success text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="activateModalLabel">
                                                                            <i class="fas fa-user-check mr-2"></i>Activate Official
                                                                            Account
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body py-4">
                                                                        <div class="text-center mb-4">
                                                                            <i
                                                                                class="fas fa-user-check text-success fa-4x mb-3"></i>
                                                                            <h5 class="font-weight-bold">Activate Official Account
                                                                            </h5>
                                                                        </div>
                                                                        <p class="text-center">
                                                                            Are you sure you want to activate the account for:
                                                                            <strong><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?></strong>?
                                                                        </p>
                                                                        <p class="text-center text-muted">
                                                                            The official will be able to log in and access barangay
                                                                            services after activation.
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                                        </button>
                                                                        <form action="update_official_status.php" method="POST">
                                                                            <input type="hidden" name="user_id"
                                                                                value="<?php echo $row['user_id']; ?>">
                                                                            <input type="hidden" name="status" value="Active">
                                                                            <button type="submit" class="btn btn-success">
                                                                                <i class="fas fa-check-circle mr-1"></i> Activate
                                                                                Account
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Deactivate Modal -->
                                                        <div class="modal fade" id="deactivateModal<?php echo $row['brgyOfficer_id']; ?>"
                                                            tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-danger text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="deactivateModalLabel">
                                                                            <i class="fas fa-user-slash mr-2"></i>Deactivate
                                                                            Official Account
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body py-4">
                                                                        <div class="text-center mb-4">
                                                                            <i class="fas fa-user-slash text-danger fa-4x mb-3"></i>
                                                                            <h5 class="font-weight-bold">Deactivate Official Account
                                                                            </h5>
                                                                        </div>
                                                                        <p class="text-center">
                                                                            Are you sure you want to deactivate the account for:
                                                                            <strong><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?></strong>?
                                                                        </p>
                                                                        <p class="text-center text-muted">
                                                                            The official will no longer be able to log in or access
                                                                            barangay services after deactivation.
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                                        </button>
                                                                        <form action="update_official_status.php" method="POST">
                                                                            <input type="hidden" name="user_id"
                                                                                value="<?php echo $row['user_id']; ?>">
                                                                            <input type="hidden" name="status" value="Inactive">
                                                                            <button type="submit" class="btn btn-danger">
                                                                                <i class="fas fa-user-slash mr-1"></i> Deactivate
                                                                                Account
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="deleteModal<?php echo $row['brgyOfficer_id']; ?>"
                                                            tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-danger text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="deleteModalLabel">
                                                                            <i class="fas fa-trash mr-2"></i>Delete Resident
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body py-4">
                                                                        <div class="text-center mb-4">
                                                                            <i
                                                                                class="fas fa-exclamation-triangle text-danger fa-4x mb-3"></i>
                                                                            <h5 class="font-weight-bold">Delete Confirmation</h5>
                                                                        </div>
                                                                        <p class="text-center">
                                                                            Are you sure you want to delete the official:
                                                                            <strong><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?></strong>?
                                                                        </p>
                                                                        <div class="alert alert-warning">
                                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                                            <strong>Warning:</strong> This action cannot be undone.
                                                                            All associated data will be permanently removed.
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                                        </button>
                                                                        <form action="delete_official.php" method="POST">
                                                                            <input type="hidden" name="brgyOfficer_id"
                                                                                value="<?php echo $row['brgyOfficer_id']; ?>">
                                                                            <input type="hidden" name="user_id"
                                                                                value="<?php echo $row['user_id']; ?>">
                                                                            <button type="submit" class="btn btn-danger">
                                                                                <i class="fas fa-trash mr-1"></i> Delete Official
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                                        <p class="lead text-muted">No official found.</p>
                                                        <?php if (!empty($search)): ?>
                                                            <p>No results for:
                                                                <strong><?php echo htmlspecialchars($search); ?></strong>
                                                            </p>
                                                            <a href="residents_management.php"
                                                                class="btn btn-outline-primary mt-2">
                                                                <i class="fas fa-redo mr-1"></i> Clear Search
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
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
            document.addEventListener('DOMContentLoaded', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');

                // Update file input label with selected filename
                $('.custom-file-input').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').html(fileName);
                });

                // Password validation
                var password = document.getElementById("password");
                var confirm_password = document.getElementById("confirm_password");

                function validatePassword() {
                    if (password.value != confirm_password.value) {
                        confirm_password.setCustomValidity("Passwords do not match");
                    } else {
                        confirm_password.setCustomValidity("");
                    }
                }

                if (password && confirm_password) {
                    password.addEventListener("change", validatePassword);
                    confirm_password.addEventListener("keyup", validatePassword);
                }

                // Loop over them and prevent submission
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
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