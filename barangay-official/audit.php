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
    <title>Audit | Barangay System</title>
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

            .btn-primary:hover {
                background-color: #0e1624 !important;
            }

            .btn-outline-primary:hover {
                background-color: #0e1624 !important;
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
        1 => "Certificate Request Submitted Successfully",
        2 => "Certificate Request Updated Successfully"
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

// Build WHERE clause based on search
$where_conditions = [];
$params = [];
$types = "";

// Get all column names from the tbl_audit table for search
$columnsQuery = "SHOW COLUMNS FROM tbl_audit";
$columnsResult = $conn->query($columnsQuery);
$searchFields = [];

if ($columnsResult) {
    while ($column = $columnsResult->fetch_assoc()) {
        $searchFields[] = "a." . $column['Field'];
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
$count_sql = "SELECT COUNT(*) as total FROM tbl_audit a WHERE $where_clause";

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

// Fetch audit logs query
$sql = "SELECT a.audit_id, a.res_id, a.brgyOfficer_id, a.requestType, 
        a.user_id, a.role, a.details, a.processedBy, 
        a.dateTimeCreated, a.status, a.lastEdited
        FROM tbl_audit a
        WHERE $where_clause
        ORDER BY a.dateTimeCreated DESC 
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
                <!-- Title section -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <p class="card-title mb-0">Audit Log</p>
                </div>

              

                <!-- Filter section -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <form method="GET" action="" class="form-inline" id="searchForm">
                            <div class="input-group mb-2 mr-sm-2">
                                <input type="text" name="search" id="searchInput" class="form-control"
                                    value="<?php echo htmlspecialchars($search); ?>"
                                    placeholder="Search Audit Log">
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
                                <th>Audit ID</th>
                                <th>Request Type</th>
                                <th>User ID</th>
                                <th>Role</th>
                                <th>Details</th>
                                <th>Processed By</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['audit_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['requestType']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($row['details'], 0, 30)) . (strlen($row['details']) > 30 ? '...' : ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['processedBy']); ?></td>
                                        <td><?php echo date('F d, Y h:i A', strtotime($row['lastEdited'])); ?></td>
                                       
                                        <td>
                                            <!-- View button for all positions -->
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#viewModal<?php echo $row['audit_id']; ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- View Modal -->
                                    <div class="modal fade"
                                        id="viewModal<?php echo $row['audit_id']; ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content shadow-lg border-0">
                                                <!-- Enhanced Header with gradient background -->
                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                    <h5 class="modal-title font-weight-bold"
                                                        id="viewModalLabel">
                                                        <i class="fas fa-file-alt mr-2"></i>Audit Log Details
                                                    </h5>
                                                    <button type="button" class="close text-white"
                                                        data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body py-4">
                                                  

                                                    <!-- Card container for details -->
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light py-3">
                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                <i class="fas fa-info-circle mr-2"></i>Audit Information
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Audit ID</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['audit_id']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Request Type</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['requestType']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">User ID</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['user_id']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Role</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['role']); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Resident ID</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['res_id']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Barangay Officer ID</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['brgyOfficer_id']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Processed By</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo htmlspecialchars($row['processedBy']); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Date Created</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo date('F d, Y h:i A', strtotime($row['dateTimeCreated'])); ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="info-group mb-3">
                                                                        <label
                                                                            class="text-muted small text-uppercase">Last Edited</label>
                                                                        <p class="font-weight-bold mb-2">
                                                                            <?php echo date('F d, Y h:i A', strtotime($row['lastEdited'])); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Details Section -->
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light py-3">
                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                <i class="fas fa-file-alt mr-2"></i>Details
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p><?php echo nl2br(htmlspecialchars($row['details'])); ?></p>
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
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No audit logs found</td>
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
        document.addEventListener('DOMContentLoaded', function () {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');
            var clearFilters = document.getElementById('clearFilters');
            var filterForm = document.getElementById('filterForm');

            // Original clear button for search form
            if (clearButton) {
                clearButton.addEventListener('click', function () {
                    searchInput.value = '';
                    searchForm.submit(); // Submit the form to reload all records
                });
            }

            // New clear filters button for filter form
            if (clearFilters) {
                clearFilters.addEventListener('click', function () {
                    // Get all date input fields in the filter form
                    var dateInputs = filterForm.querySelectorAll('input[type="date"]');

                    // Clear all date input values
                    dateInputs.forEach(function (input) {
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