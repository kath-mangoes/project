<?php

ob_start(); // prevent header issues
session_start();

if (isset($_SESSION['error'])) {
    echo '<div style="color:red;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<div style="color:green;">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}


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
$email = "";
$mobile = "";
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
    $email = $row["email"];
    $mobile = $row["mobile"];
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
    <title>Residents | Barangay System</title>
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
                        1 => "Resident Added Successfully",
                        2 => "Resident Updated Successfully",
                        3 => "Resident Deleted Successfully",
                        4 => "Resident Status Updated Successfully"
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



                // Build WHERE clause based on search
                $where_conditions = [];
                $params = [];
                $types = "";

                if (!empty($search)) {
                    $searchValue = "%$search%";
                    $where_conditions[] = "(r.first_name LIKE ? OR r.middle_name LIKE ? OR r.last_name LIKE ? OR r.address LIKE ? OR r.mobile LIKE ? OR r.user_id LIKE ?)";
                    $params = array_merge($params, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
                    $types .= "ssssss";
                }

                // Combine WHERE conditions
                $where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

                // Count total records for pagination
                $count_sql = "SELECT COUNT(*) as total FROM tbl_residents r JOIN tbl_user u ON r.user_id = u.user_id WHERE $where_clause";

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

                // Fetch residents data query
                $sql = "SELECT r.*, u.account_status, u.email
                FROM tbl_residents r 
                JOIN tbl_user u ON r.user_id = u.user_id 
                WHERE $where_clause 
                ORDER BY r.last_name ASC 
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
                $next_id_query = "SELECT MAX(res_id) as max_id FROM tbl_residents";
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
                                    <p class="card-title mb-0">Barangay Residents Management</p>
                                    <div class="ml-auto">
                                        <button class="btn btn-primary mb-3" data-toggle="modal"
                                            data-target="#addResidentModal">
                                            <i class="fas fa-user-plus mr-2"></i>Add New Resident
                                        </button>
                                    </div>
                                </div>


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
                                    <div class="export-buttons">
                                        <!-- Import Button and Modal Trigger -->
                                       <button type="button" class="btn btn-info mb-2" data-toggle="modal" data-target="#importModal">
                                            <i class="fa-solid fa-file-import"></i> Import Excel
                                        </button>
                                        <a href="export_resident.php?format=pdf" class="btn btn-danger mb-2">
                                            <i class="fa-solid fa-file-pdf"></i> Export PDF
                                        </a>
                                        <a href="export_resident.php?format=excel" class="btn btn-success mb-2">
                                            <i class="fa-solid fa-file-excel"></i> Export Excel
                                        </a>
                                    </div>
                                </div>

     
                                <!-- Import Modal -->
                                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importModalLabel">Import Residents from Excel</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="import_resident.php" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="excelFile">Select Excel File</label>
                                                        <input type="file" class="form-control-file" id="excelFile" name="excel_file" accept=".xlsx,.xls" required>
                                                    </div>
                                                    <div class="alert alert-info">
                                                        <strong>Note:</strong> Please use the template format for importing data. 
                                                        <a href="templates/residents_template.xlsx" download class="alert-link">Download Template</a>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <strong>Required Columns:</strong> First Name, Last Name, Email (if empty, one will be generated)
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Import</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Residents table -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Resident ID</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()):
                                                    // Calculate age from birthday
                                                    $birthdate = new DateTime($row['birthday']);
                                                    $today = new DateTime();
                                                    $age = $birthdate->diff($today)->y;
                                                ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                                        <td><?php echo $age; ?></td>
                                                        <td>
                                                            <?php
                                                                $status = $row['account_status'];
                                                                $badgeClass = '';

                                                                if ($status == 'Active') {
                                                                    $badgeClass = 'badge-success';
                                                                } elseif ($status == 'Inactive') {
                                                                    $badgeClass = 'badge-danger';
                                                                } else {
                                                                    $badgeClass = 'badge-secondary'; // fallback for unknown status
                                                                }
                                                            ?>
                                                            <span class="badge <?php echo $badgeClass; ?> text-white font-weight-bold">
                                                                <?php echo htmlspecialchars($status); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <!-- View button for all positions -->
                                                            <button class="btn btn-info btn-sm" data-toggle="modal" title="View Resident Details"
                                                                data-target="#viewModal<?php echo $row['res_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>


                                                            <!-- Edit button -->
                                                            <button class="btn btn-warning btn-sm" data-toggle="modal" title="Edit Resident Details"
                                                                data-target="#editModal<?php echo $row['res_id']; ?>">
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>

                                                            <!-- Determine button and modal based on account_status -->
                                                            <?php if ($row['account_status'] == 'Active'): ?>
                                                                <!-- Deactivate Button -->
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal" title="Deactivate Resident"
                                                                data-target="#deactivateModal<?php echo $row['res_id']; ?>">
                                                                    <i class="fas fa-user-slash"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <!-- Activate Button -->
                                                                <button class="btn btn-success btn-sm" data-toggle="modal" title="Activate Resident"
                                                                data-target="#activateModal<?php echo $row['res_id']; ?>">
                                                                    <i class="fas fa-user-check"></i>
                                                                </button>
                                                            <?php endif; ?>


                                                            <!-- Delete button -->
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" title="Delete Resident"
                                                                data-target="#deleteModal<?php echo $row['res_id']; ?>">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>

                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewModal<?php echo $row['res_id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold"
                                                                        id="viewModalLabel">
                                                                        <i class="fas fa-user mr-2"></i>Resident Information
                                                                    </h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <!-- Status badge at top -->
                                                                    <div class="text-center mb-4">
                                                                        <?php
                                                                            $status = $row['account_status'];
                                                                            $badgeClass = '';
                                                                            $iconClass = '';

                                                                            if ($status === 'Active') {
                                                                                $badgeClass = 'badge-success';
                                                                                $iconClass = 'fa-check-circle';
                                                                            } elseif ($status === 'Inactive') {
                                                                                $badgeClass = 'badge-danger';
                                                                                $iconClass = 'fa-times-circle';
                                                                            } else {
                                                                                $badgeClass = 'badge-secondary'; // fallback
                                                                                $iconClass = 'fa-info-circle';
                                                                            }
                                                                        ?>
                                                                        <span class="badge badge-pill px-4 py-2 font-weight-bold text-white <?php echo $badgeClass; ?>">
                                                                            <i class="fas <?php echo $iconClass; ?> mr-1"></i>Resident's Account
                                                                            <?php echo htmlspecialchars($status); ?>
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
                                                                                            class="text-muted small text-uppercase">Resident
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
                                                                                            class="text-muted small text-uppercase">Gender</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['gender']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Civil
                                                                                            Status</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['civilStatus']); ?>
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
                                                                                    <!--<div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Birthplace</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php //echo htmlspecialchars($row['birthplace']); ?>
                                                                                        </p>
                                                                                    </div>-->
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
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Address and Residency Information -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-home mr-2"></i>Address &
                                                                                Residency Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['address']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Resident
                                                                                            Status</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['residency_tenure']); ?>
                                                                                        </p>
                                                                                         <?php if (!empty($row['proof_of_residency_document'])): ?>
                                                                                         <a href="../<?php echo htmlspecialchars($row['proof_of_residency_document']); ?>" target="_blank">
                                                                                            View Proof Of Residency
                                                                                         </a>
                                                                                        <?php else: ?>
                                                                                            <span>No proof_of_residency uploaded.</span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Voter
                                                                                            Status</label>
                                                                            
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['voterStatus']); ?>
                                                                                            
                                                                                        </p>
                                                                                        <?php if (!empty($row['voter_document'])): ?>
                                                                                         <a href="../<?php echo htmlspecialchars($row['voter_document']); ?>" target="_blank">
                                                                                            View Voter's ID
                                                                                         </a>
                                                                                        <?php else: ?>
                                                                                            <span>No voter's id uploaded.</span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Precinct
                                                                                            Number</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['precinctNumber']) ? htmlspecialchars($row['precinctNumber']) : 'N/A'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Additional Information -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i
                                                                                    class="fas fa-info-circle mr-2"></i>Additional
                                                                                Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Blood
                                                                                            Type</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['bloodType']) ? htmlspecialchars($row['bloodType']) : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Height</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['height']) ? htmlspecialchars($row['height']) . ' cm' : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Weight</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['weight']) ? htmlspecialchars($row['weight']) . ' kg' : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Senior
                                                                                            Citizen</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['is_senior']); ?>
                                                                                            <?php if (($row['is_senior'] === 'Yes' || $row['is_pwd'] === 'Yes') && !empty($row['senior_document'])): ?>
                                                                                                <a href="../<?php echo htmlspecialchars($row['senior_document']); ?>" target="_blank">
                                                                                                    <p>View Senior Citizen Document</p>
                                                                                                </a>
                                                                                            
                                                                                            <?php endif; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">PWD</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['is_pwd']); ?>
                                                                                            <p class="font-weight-bold mb-2">
                                                                                            
                                                                                            <?php if (($row['is_senior'] === 'Yes' || $row['is_pwd'] === 'Yes') && !empty($row['pwd_document'])): ?>
                                                                                                <a href="../<?php echo htmlspecialchars($row['pwd_document']); ?>" target="_blank">
                                                                                                    <p>View PWD Document</p>
                                                                                                </a>
                                                                                            
                                                                                            <?php endif; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">4Ps
                                                                                            Member</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['is_4ps_member']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- ID Information -->
                                                                    <div class="card border-0 shadow-sm">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-id-card mr-2"></i>ID
                                                                                Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">ID
                                                                                            Type</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['typeOfID']) ? htmlspecialchars($row['typeOfID']) : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">ID
                                                                                            Number</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['IDNumber']) ? htmlspecialchars($row['IDNumber']) : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">SSS/GSIS
                                                                                            Number</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['SSSGSIS_Number']) ? htmlspecialchars($row['SSSGSIS_Number']) : 'Not provided'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">TIN
                                                                                            Number</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo !empty($row['TIN_number']) ? htmlspecialchars($row['TIN_number']) : 'Not provided'; ?>
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


                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal<?php echo $row['res_id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-gradient-warning text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold"
                                                                        id="editModalLabel">
                                                                        <i class="fas fa-edit mr-2"></i>Edit Resident
                                                                        Information
                                                                    </h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="update_resident.php" method="POST"
                                                                    class="needs-validation" novalidate>
                                                                    <div class="modal-body py-4">
                                                                        <input type="hidden" name="res_id"
                                                                            value="<?php echo $row['res_id']; ?>">
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
                                                                                                for="last_name<?php echo $row['res_id']; ?>">Last
                                                                                                Name</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="last_name<?php echo $row['res_id']; ?>"
                                                                                                name="last_name"
                                                                                                value="<?php echo htmlspecialchars($row['last_name']); ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="first_name<?php echo $row['res_id']; ?>">First
                                                                                                Name</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="first_name<?php echo $row['res_id']; ?>"
                                                                                                name="first_name"
                                                                                                value="<?php echo htmlspecialchars($row['first_name']); ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="middle_name<?php echo $row['res_id']; ?>">Middle
                                                                                                Name</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="middle_name<?php echo $row['res_id']; ?>"
                                                                                                name="middle_name"
                                                                                                value="<?php echo htmlspecialchars($row['middle_name']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <?php
                                                                                // Calculate the latest birthday allowed (18 years ago from today)
                                                                                $maxBirthday = date('Y-m-d', strtotime('-18 years'));
                                                                                ?>


                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="birthday<?php echo $row['res_id']; ?>">Birthday</label>
                                                                                            <input type="date"
                                                                                                class="form-control"
                                                                                                id="birthday<?php echo $row['res_id']; ?>"
                                                                                                name="birthday"
                                                                                                max="<?php echo $maxBirthday; ?>"
                                                                                                value="<?php echo $row['birthday']; ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="gender<?php echo $row['res_id']; ?>">Gender</label>
                                                                                            <select class="form-control"
                                                                                                id="gender<?php echo $row['res_id']; ?>"
                                                                                                name="gender" required>
                                                                                                <option value="Male" <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male
                                                                                                </option>
                                                                                                <option value="Female" <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="civilStatus<?php echo $row['res_id']; ?>">Civil
                                                                                                Status</label>
                                                                                            <select class="form-control"
                                                                                                id="civilStatus<?php echo $row['res_id']; ?>"
                                                                                                name="civilStatus" required>
                                                                                                <option value="Single" <?php echo ($row['civilStatus'] == 'Single') ? 'selected' : ''; ?>>Single
                                                                                                </option>
                                                                                                <option value="Married" <?php echo ($row['civilStatus'] == 'Married') ? 'selected' : ''; ?>>
                                                                                                    Married</option>
                                                                                                <option value="Widowed" <?php echo ($row['civilStatus'] == 'Widowed') ? 'selected' : ''; ?>>
                                                                                                    Widowed</option>
                                                                                                <option value="Divorced" <?php echo ($row['civilStatus'] == 'Divorced') ? 'selected' : ''; ?>>
                                                                                                    Divorced</option>
                                                                                                <option value="Separated" <?php echo ($row['civilStatus'] == 'Separated') ? 'selected' : ''; ?>>
                                                                                                    Separated</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="mobile<?php echo $row['res_id']; ?>">Mobile
                                                                                                Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="mobile<?php echo $row['res_id']; ?>"
                                                                                                name="mobile"
                                                                                                maxlength="11" pattern="\d{11}" 
                                                                                                required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                                                                                                value="<?php echo htmlspecialchars($row['mobile']); ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="birthplace<?php echo $row['res_id']; ?>">Birthplace</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="birthplace<?php echo $row['res_id']; ?>"
                                                                                                name="birthplace"
                                                                                                value="<?php echo htmlspecialchars($row['birthplace']); ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Address and Residency Information -->
                                                                        <div class="card mb-4">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="m-0 font-weight-bold text-primary">
                                                                                    Address & Residency Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="address<?php echo $row['res_id']; ?>">Address</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="address<?php echo $row['res_id']; ?>"
                                                                                                name="address"
                                                                                                value="<?php echo htmlspecialchars($row['address']); ?>"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="residentStatus<?php echo $row['res_id']; ?>">Resident
                                                                                                Status</label>
                                                                                            <select class="form-control"
                                                                                                id="residentStatus<?php echo $row['res_id']; ?>"
                                                                                                name="residentStatus" required>
                                                                                                <option value="Permanent" <?php echo ($row['residentStatus'] == 'Permanent') ? 'selected' : ''; ?>>
                                                                                                    Permanent</option>
                                                                                                <option value="Temporary" <?php echo ($row['residentStatus'] == 'Temporary') ? 'selected' : ''; ?>>
                                                                                                    Temporary</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="voterStatus<?php echo $row['res_id']; ?>">Voter
                                                                                                Status</label>
                                                                                            <select class="form-control"
                                                                                                id="voterStatus<?php echo $row['res_id']; ?>"
                                                                                                name="voterStatus" required>
                                                                                                <option value="Registered" <?php echo ($row['is_registered_voter'] == 'Registered') ? 'selected' : ''; ?>>
                                                                                                    Registered</option>
                                                                                                <option value="Not Registered"
                                                                                                    <?php echo ($row['is_registered_voter'] == 'Not Registered') ? 'selected' : ''; ?>>Not Registered
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="precinctNumber<?php echo $row['res_id']; ?>">Precinct
                                                                                                Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="precinctNumber<?php echo $row['res_id']; ?>"
                                                                                                name="precinctNumber"
                                                                                                value="<?php echo htmlspecialchars($row['precinctNumber']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Additional Information -->
                                                                        <div class="card mb-4">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="m-0 font-weight-bold text-primary">
                                                                                    Additional Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="bloodType<?php echo $row['res_id']; ?>">Blood
                                                                                                Type</label>
                                                                                            <select class="form-control"
                                                                                                id="bloodType<?php echo $row['res_id']; ?>"
                                                                                                name="bloodType">
                                                                                                <option value="" <?php echo empty($row['bloodType']) ? 'selected' : ''; ?>>--
                                                                                                    Select --</option>
                                                                                                <option value="A+" <?php echo ($row['bloodType'] == 'A+') ? 'selected' : ''; ?>>A+
                                                                                                </option>
                                                                                                <option value="A-" <?php echo ($row['bloodType'] == 'A-') ? 'selected' : ''; ?>>A-
                                                                                                </option>
                                                                                                <option value="B+" <?php echo ($row['bloodType'] == 'B+') ? 'selected' : ''; ?>>B+
                                                                                                </option>
                                                                                                <option value="B-" <?php echo ($row['bloodType'] == 'B-') ? 'selected' : ''; ?>>B-
                                                                                                </option>
                                                                                                <option value="AB+" <?php echo ($row['bloodType'] == 'AB+') ? 'selected' : ''; ?>>AB+
                                                                                                </option>
                                                                                                <option value="AB-" <?php echo ($row['bloodType'] == 'AB-') ? 'selected' : ''; ?>>AB-
                                                                                                </option>
                                                                                                <option value="O+" <?php echo ($row['bloodType'] == 'O+') ? 'selected' : ''; ?>>O+
                                                                                                </option>
                                                                                                <option value="O-" <?php echo ($row['bloodType'] == 'O-') ? 'selected' : ''; ?>>O-
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="height<?php echo $row['res_id']; ?>">Height
                                                                                                (cm)</label>
                                                                                            <input type="number" step="0.01"
                                                                                                class="form-control"
                                                                                                id="height<?php echo $row['res_id']; ?>"
                                                                                                name="height"
                                                                                                value="<?php echo htmlspecialchars($row['height']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="weight<?php echo $row['res_id']; ?>">Weight
                                                                                                (kg)</label>
                                                                                            <input type="number" step="0.01"
                                                                                                class="form-control"
                                                                                                id="weight<?php echo $row['res_id']; ?>"
                                                                                                name="weight"
                                                                                                value="<?php echo htmlspecialchars($row['weight']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="is_senior<?php echo $row['res_id']; ?>">Senior
                                                                                                Citizen</label>
                                                                                            <select class="form-control"
                                                                                                id="is_senior<?php echo $row['res_id']; ?>"
                                                                                                name="is_senior">
                                                                                                <option value="Yes" <?php echo ($row['is_senior'] == 'Yes') ? 'selected' : ''; ?>>Yes
                                                                                                </option>
                                                                                                <option value="No" <?php echo ($row['is_senior'] == 'No') ? 'selected' : ''; ?>>No
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="is_pwd<?php echo $row['res_id']; ?>">PWD</label>
                                                                                            <select class="form-control"
                                                                                                id="is_pwd<?php echo $row['res_id']; ?>"
                                                                                                name="is_pwd">
                                                                                                <option value="Yes" <?php echo ($row['is_pwd'] == 'Yes') ? 'selected' : ''; ?>>Yes
                                                                                                </option>
                                                                                                <option value="No" <?php echo ($row['is_pwd'] == 'No') ? 'selected' : ''; ?>>No
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="is_4ps_member<?php echo $row['res_id']; ?>">4Ps
                                                                                                Member</label>
                                                                                            <select class="form-control"
                                                                                                id="is_4ps_member<?php echo $row['res_id']; ?>"
                                                                                                name="is_4ps_member">
                                                                                                <option value="Yes" <?php echo ($row['is_4ps_member'] == 'Yes') ? 'selected' : ''; ?>>Yes
                                                                                                </option>
                                                                                                <option value="No" <?php echo ($row['is_4ps_member'] == 'No') ? 'selected' : ''; ?>>No
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- ID Information -->
                                                                        <div class="card">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="m-0 font-weight-bold text-primary">ID
                                                                                    Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="typeOfID<?php echo $row['res_id']; ?>">ID
                                                                                                Type</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="typeOfID<?php echo $row['res_id']; ?>"
                                                                                                name="typeOfID"
                                                                                                value="<?php echo htmlspecialchars($row['typeOfID']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="IDNumber<?php echo $row['res_id']; ?>">ID
                                                                                                Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="IDNumber<?php echo $row['res_id']; ?>"
                                                                                                name="IDNumber"
                                                                                                value="<?php echo htmlspecialchars($row['typeOfID']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <!--<div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="SSSGSIS_Number<?php //echo $row['res_id']; ?>">SSS/GSIS
                                                                                                Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="SSSGSIS_Number<?php //echo $row['res_id']; ?>"
                                                                                                name="SSSGSIS_Number"
                                                                                                value="<?php //echo htmlspecialchars($row['SSSGSIS_Number']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="TIN_number<?php //echo $row['res_id']; ?>">TIN
                                                                                                Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="TIN_number<?php //echo $row['res_id']; ?>"
                                                                                                name="TIN_number"
                                                                                                value="<?php //echo htmlspecialchars($row['TIN_number']); ?>">
                                                                                        </div>
                                                                                    </div>-->
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
                                                                            <i class="fas fa-save mr-1"></i> Update Resident
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Activate Modal -->
                                                    <div class="modal fade" id="activateModal<?php echo $row['res_id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="activateModalLabel<?php echo $row['res_id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title" id="activateModalLabel<?php echo $row['res_id']; ?>">
                                                                        Activate Resident Account
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <i class="fas fa-user-check text-success fa-4x mb-3"></i>
                                                                    <p>Are you sure you want to <strong>activate</strong> the account for:</p>
                                                                    <strong><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?></strong>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <form action="update_resident_status.php" method="POST">
                                                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                                        <input type="hidden" name="status" value="Active">
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-check-circle mr-1"></i> Confirm Activation
                                                                        </button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Deactivate Modal -->
                                                    <div class="modal fade" id="deactivateModal<?php echo $row['res_id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel<?php echo $row['res_id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="deactivateModalLabel<?php echo $row['res_id']; ?>">
                                                                        Deactivate Resident Account
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <i class="fas fa-user-slash text-danger fa-4x mb-3"></i>
                                                                    <p>Are you sure you want to <strong>deactivate</strong> the account for:</p>
                                                                    <strong><?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']); ?></strong>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <form action="update_resident_status.php" method="POST">
                                                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                                        <input type="hidden" name="status" value="Inactive">
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="fas fa-user-slash mr-1"></i> Confirm Deactivation
                                                                        </button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal<?php echo $row['res_id']; ?>"
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
                                                                        Are you sure you want to delete the resident:
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
                                                                    <form action="delete_resident.php" method="POST">
                                                                        <input type="hidden" name="res_id"
                                                                            value="<?php echo $row['res_id']; ?>">
                                                                        <input type="hidden" name="user_id"
                                                                            value="<?php echo $row['user_id']; ?>">
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="fas fa-trash mr-1"></i> Delete Resident
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                                        <p class="lead text-muted">No residents found.</p>
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
                    <!-- Add Resident Modal -->
                    <div class="modal fade" id="addResidentModal" tabindex="-1" role="dialog"
                        aria-labelledby="addResidentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content shadow-lg border-0">
                                <div class="modal-header bg-gradient-primary text-white py-3">
                                    <h5 class="modal-title font-weight-bold" id="addResidentModalLabel">
                                        <i class="fas fa-user-plus mr-2"></i>Add New Resident
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="add_resident.php" method="POST" class="needs-validation"
                                    enctype="multipart/form-data" novalidate>
                                    <div class="modal-body py-4">
                                        <!-- Account Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    
                                                    <div class="col-md">
                                                        <div class="form-group">
                                                            <label for="email">Email Address</label>
                                                            <input type="email" class="form-control" id="email" name="email"
                                                                required>
                                                            <div class="invalid-feedback">Please provide a valid email address.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password" required>
                                                            <div class="invalid-feedback">Please provide a password.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="confirm_password">Confirm Password</label>
                                                            <input type="password" class="form-control" id="confirm_password"
                                                                name="confirm_password" required>
                                                            <div class="invalid-feedback">Passwords do not match.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Personal Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="last_name">Last Name</label>
                                                            <input type="text" class="form-control" id="last_name"
                                                                name="last_name" required>
                                                                <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please provide a valid last name.</div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="first_name">First Name</label>
                                                            <input type="text" class="form-control" id="first_name"
                                                                name="first_name" required>
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please provide a valid First name.</div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="middle_name">Middle Name</label>
                                                            <input type="text" class="form-control" id="middle_name"
                                                                name="middle_name">
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please provide a valid Middle name.</div>
                                                        </div>
                                                    </div>   
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="birthday">Birthday</label>
                                                            <input type="date" class="form-control" id="birthday"
                                                                max="<?php echo $maxBirthday; ?>"
                                                                name="birthday" required>
                                                            <div class="invalid-feedback">Please provide a birthday.</div>
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Birthday must be before 2008.</div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="birthplace">Birthplace</label>
                                                            <input type="text" class="form-control" id="birthplace"
                                                                name="birthplace" required>
                                                            <div class="invalid-feedback">Please provide a birthplace.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>
                                                            <select class="form-control" id="gender" name="gender" required>
                                                                <option value="">Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a gender.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="civilStatus">Civil Status</label>
                                                            <select class="form-control" id="civilStatus" name="civilStatus"
                                                                required>
                                                                <option value="">Select Civil Status</option>
                                                                <option value="Single">Single</option>
                                                                <option value="Married">Married</option>
                                                                <option value="Divorced">Divorced</option>
                                                                <option value="Widowed">Widowed</option>
                                                                <option value="Separated">Separated</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a civil status.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile Number</label>
                                                            <input type="text" class="form-control" id="mobile" name="mobile" 
                                                            maxlength="11" pattern="\d{11}" 
                                                            required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;"></div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="address">Complete Address</label>
                                                            <input type="text" class="form-control" id="address" name="address"
                                                                required>
                                                            <div class="invalid-feedback">Please provide an address.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Additional Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bloodType">Blood Type</label>
                                                            <select class="form-control" id="bloodType" name="bloodType">
                                                                <option value="">Select Blood Type</option>
                                                                <option value="A+">A+</option>
                                                                <option value="A-">A-</option>
                                                                <option value="B+">B+</option>
                                                                <option value="B-">B-</option>
                                                                <option value="AB+">AB+</option>
                                                                <option value="AB-">AB-</option>
                                                                <option value="O+">O+</option>
                                                                <option value="O-">O-</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="height">Height (cm)</label>
                                                            <input type="number" step="0.01" class="form-control" id="height"
                                                                name="height" maxlength="3">
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please enter a valid height.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="weight">Weight (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" id="weight"
                                                                name="weight" maxlength="3">
                                                                <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please enter a valid weight.</div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="precinctNumber">Precinct Number</label>
                                                            <input type="text" class="form-control" id="precinctNumber"
                                                                name="precinctNumber">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="barangay_number">Barangay Number</label>
                                                            <input type="text" class="form-control" id="barangay_number"
                                                                name="barangay_number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="residentStatus">Resident Status</label>
                                                            <select class="form-control" id="residentStatus"
                                                                name="residentStatus" required>
                                                                <option value="">Select Resident Status</option>
                                                                <option value="Permanent">Permanent</option>
                                                                <option value="Temporary">Temporary</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a resident status.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="voterStatus">Voter Status</label>
                                                            <select class="form-control" id="voterStatus" name="voterStatus"
                                                                required>
                                                                <option value="">Select Voter Status</option>
                                                                <option value="Active">Active</option>
                                                                <option value="Inactive">Inactive</option>
                                                                <option value="Not Registered">Not Registered</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a voter status.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Identification Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Identification Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="typeOfID">Type of ID</label>
                                                            <select class="form-control" id="typeOfID" name="typeOfID">
                                                                <option value="">Select ID Type</option>
                                                                <option value="PhilSys ID">PhilSys ID</option>
                                                                <option value="Driver's License">Driver's License</option>
                                                                <option value="Passport">Passport</option>
                                                                <option value="Postal ID">Postal ID</option>
                                                                <option value="Voter's ID">Voter's ID</option>
                                                                <option value="PRC ID">PRC ID</option>
                                                                <option value="Senior Citizen ID">Senior Citizen ID</option>
                                                                <option value="PWD ID">PWD ID</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="IDNumber">Valid ID Number</label>
                                                            <input type="text" class="form-control" id="IDNumber"
                                                                name="IDNumber">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="SSSGSIS_Number">SSS/GSIS Number</label>
                                                            <input type="text" class="form-control" id="SSSGSIS_Number"
                                                                name="SSSGSIS_Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="TIN_number">TIN Number</label>
                                                            <input type="text" class="form-control" id="TIN_number"
                                                                name="TIN_number">
                                                            <div class="error-message" style="display:none; color:red; font-size: 0.875em; margin-top:5px;">Please enter a valid TIN number.</div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="id_image">Upload ID Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="id_image"
                                                                    name="id_image">
                                                                <label class="custom-file-label" for="id_image">Choose
                                                                    file</label>
                                                            </div>
                                                            <small class="form-text text-muted">Upload a clear image of the ID
                                                                (JPG, PNG, PDF formats only, max 2MB)</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Special Categories -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Special Categories</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Senior Citizen</label>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="senior_yes" name="is_senior" value="Yes"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label" for="senior_yes">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="senior_no" name="is_senior" value="No"
                                                                    class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="senior_no">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Person with Disability (PWD)</label>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="pwd_yes" name="is_pwd" value="Yes"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label" for="pwd_yes">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="pwd_no" name="is_pwd" value="No"
                                                                    class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="pwd_no">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>4Ps Member</label>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="4ps_yes" name="is_4ps_member"
                                                                    value="Yes" class="custom-control-input">
                                                                <label class="custom-control-label" for="4ps_yes">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="4ps_no" name="is_4ps_member" value="No"
                                                                    class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="4ps_no">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Profile Photo -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0 font-weight-bold text-primary">Profile Photo</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="image">Upload Profile Photo</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="image"
                                                                    name="image">
                                                                <label class="custom-file-label" for="image">Choose file</label>
                                                            </div>
                                                            <small class="form-text text-muted">Upload a recent 2x2 photo (JPG,
                                                                PNG formats only, max 1MB)</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Terms and Conditions -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="terms" name="terms"
                                                    required>
                                                <label class="custom-control-label" for="terms">I agree to the terms and
                                                    conditions and privacy policy</label>
                                                <div class="invalid-feedback">You must agree before submitting.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Save Resident
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
    .error-input {
        border-color: red !important;
    }
    .error-message {
        color: red;
        font-size: 0.875em; /* small */
        margin-top: 5px;
        display: none;
    }
</style>







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
    <style>
        .is-invalid {
            border-color: red !important;
        }
    </style>
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


        //Mobile Number Validation
        const mobileField = document.getElementById('mobile');
        const mobileError = mobileField.parentElement.querySelector('.error-message');

        mobileField.addEventListener('input', function() {
            // Remove non-digit characters immediately
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 11) {
                mobileField.classList.add('is-invalid'); // Bootstrap's red border
                mobileError.textContent = 'Mobile number must be exactly 11 digits.';
                mobileError.style.display = 'block';
            } else if (this.value.length < 11 && this.value.length > 0) {
                mobileField.classList.add('is-invalid');
                mobileError.textContent = 'Mobile number must be exactly 11 digits.';
                mobileError.style.display = 'block';
            } else {
                mobileField.classList.remove('is-invalid');
                mobileError.style.display = 'none';
            }
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