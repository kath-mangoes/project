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
    <title>Household | Barangay System</title>
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
                    <a class="nav-link" href="household.php">
                        <i class="fa-solid fa-house"></i>
                        <span class="menu-title">House Hold</span>
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

                // ✅ Success/Error Alerts
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

                // ✅ Variables
                $user_id = $_SESSION['user_id'] ?? '';
                $search   = $_GET['search'] ?? '';
                $page     = (int)($_GET['page'] ?? 1);
                $limit    = 10;
                $offset   = ($page - 1) * $limit;

                // ✅ Date filters (optional)
                $date_from = $_GET['date_from'] ?? '';
                $date_to   = $_GET['date_to'] ?? '';

                // ✅ WHERE clause
                $where_conditions = [];
                $params = [];
                $types  = "";

                // 🔎 Searchable fields (from both tables)
                $searchFields = [
                    "tr.first_name",
                    "tr.middle_name",
                    "tr.last_name",
                    "tr.suffix",
                    "tr.address",
                    "tr.mobile",
                    "tr.email",
                    "tr.occupation",
                    "thh.user_id"
                ];

                if (!empty($search)) {
                    $searchConditions = [];
                    foreach ($searchFields as $field) {
                        $searchConditions[] = "$field LIKE ?";
                        $params[] = "%" . $search . "%";
                        $types   .= "s";
                    }
                    $where_conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
                }

                // ✅ Add date filter if provided
                if (!empty($date_from) && !empty($date_to)) {
                    $where_conditions[] = "DATE(thh.date_created) BETWEEN ? AND ?";
                    $params[] = $date_from;
                    $params[] = $date_to;
                    $types   .= "ss";
                }

                $where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

                // ✅ Count total records
                $count_sql = "SELECT COUNT(*) as total 
                            FROM tbl_household_head thh
                            LEFT JOIN tbl_residents tr ON thh.user_id = tr.user_id
                            WHERE $where_clause";

                if (!empty($params)) {
                    $count_stmt = $conn->prepare($count_sql);
                    $count_stmt->bind_param($types, ...$params);
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_rows   = $count_result->fetch_assoc()['total'];
                    $count_stmt->close();
                } else {
                    $count_result = $conn->query($count_sql);
                    $total_rows   = $count_result->fetch_assoc()['total'];
                }

                $total_pages = ceil($total_rows / $limit);

                // ✅ Fetch data
                $sql = "SELECT thh.*, tr.*
                        FROM tbl_household_head thh
                        LEFT JOIN tbl_residents tr ON thh.user_id = tr.user_id
                        WHERE $where_clause
                        ORDER BY thh.date_created DESC 
                        LIMIT ? OFFSET ?";

                // Add pagination separately
                $params_with_pagination = $params;
                $params_with_pagination[] = $limit;
                $params_with_pagination[] = $offset;
                $types_with_pagination = $types . "ii";

                $stmt = $conn->prepare($sql);
                if (!empty($params_with_pagination)) {
                    $stmt->bind_param($types_with_pagination, ...$params_with_pagination);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                ?>


                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Filter section for request button and validation -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                        <p class="card-title mb-0">Households</p>
                                    </div>

                                    <!-- Filter section -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <form method="GET" action="" class="form-inline" id="searchForm">
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <input type="text" name="search" id="searchInput" class="form-control"
                                                        value="<?php echo htmlspecialchars($search); ?>"
                                                        placeholder="Search...">
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
                                                    <th>Household ID</th>
                                                    <th>Head Name</th>
                                                    <th>Address</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php while ($row = $result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['household_head_id']); ?></td>
                                                            <td>
                                                                <?php 
                                                                    echo htmlspecialchars(trim(
                                                                        ucfirst($row['first_name']) . ' ' . 
                                                                        ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . 
                                                                        $row['last_name']
                                                                    )); 
                                                                ?>
                                                            </td>

                                                            <td><?php echo htmlspecialchars(substr($row['address'], 0, 50)) . (strlen($row['address']) > 50 ? '...' : ''); ?></td>
                                                            
                                                            <td>
                                                                <!-- View button -->
                                                                <button 
                                                                        class="btn btn-info btn-sm viewHHModal" 
                                                                        data-toggle="modal" 
                                                                        title="View Feedback"
                                                                        data-target="#viewHHModal"
                                                                        data-household_head_id="<?= $row['household_head_id'] ?>"
                                                                        data-fullname="<?= ucfirst($row['first_name']) . ' ' . 
                                                                            ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . 
                                                                            $row['last_name'] ?>"
                                                                        data-address="<?= $row['address'] ?>"
                                                                    >
                                                                        <i class="fa-solid fa-eye"></i>
                                                                    </button>


                                                                <!-- Edit/Update button -->
                                                                <!-- <button class="btn btn-warning btn-sm" data-toggle="modal" title="Respond"
                                                                    data-target="#editModal<?php echo $row['household_head_id']; ?>">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button> -->
                                                            </td>
                                                        </tr>

                                                        <!-- View Modal -->
                                                       <!-- View Modal -->





                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No Data found</td>
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










         $('.viewHHModal').click(function (e) { 
            e.preventDefault();

            let hhID     = $(this).data('household_head_id');
            let fullname = $(this).data('fullname');
            let address  = $(this).data('address');

            // Fill basic info
            $("#household_head_id").text(hhID);
            $("#fullname").text(fullname);
            $("#address").text(address);

            console.log(hhID);


            $.ajax({
                url: 'get_household_data.php',
                type: 'POST',
                data: { household_head_id: hhID },
                dataType: 'json',
                success: function(response) {
                    // ✅ Stats
                     // ✅ update counters
                    $('#total_members').text(response.total_members);
                    $('#total_voters').text(response.total_voters);
                    $('#total_adults').text(response.total_adults);
                    $('#total_minors').text(response.total_minors);

                    // ✅ Members
                    let tbody = $("#household_members_table tbody");
                    tbody.empty();

                    if (response.no_members) {
                        tbody.append(`
                            <tr>
                                <td colspan="4" class="text-center text-muted">No members found</td>
                            </tr>
                        `);
                    } else {
                        $.each(response.members, function(i, m) {
                            tbody.append(`
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${m.fullname}</td>
                                    <td>${m.age}</td>
                                    <td>${m.relationship}</td>
                                </tr>
                            `);
                        });
                    }

                    $('#viewHHModal').modal('show');
                }

            });
        });





    </script>






<div class="modal fade" id="viewHHModal" tabindex="-1"
    role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true"
    data-bs-backdrop="false" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <!-- Header -->
            <div class="modal-header bg-gradient-primary text-white py-3">
                <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                    <i class="fas fa-house mr-2"></i> Household Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body py-4">
                <div class="container">
                    <!-- Household Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted"><i class="fas fa-id-card mr-2"></i> Household ID</h6>
                                    <h5 class="font-weight-bold text-dark">
                                        <span id="household_head_id"></span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted"><i class="fas fa-user mr-2"></i> Name of Head</h6>
                                    <h5 class="font-weight-bold text-dark">
                                        <span id="fullname"></span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted"><i class="fas fa-map-marker-alt mr-2"></i> Address</h6>
                                    <h5 class="font-weight-bold text-dark">
                                        <span id="address"></span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Stats Section inside Modal -->
                        <div class="row text-center mb-4">
                            <div class="col-md-3">
                                <h6 class="text-muted">Total Members</h6>
                                <h4 class="font-weight-bold text-primary" id="total_members"></h4>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">Total Voters</h6>
                                <h4 class="font-weight-bold text-success" id="total_voters"></h4>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">Total Adults</h6>
                                <h4 class="font-weight-bold text-info" id="total_adults"></h4>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">Total Minors</h6>
                                <h4 class="font-weight-bold text-danger" id="total_minors"></h4>
                            </div>
                        </div>

                        <!-- Household Members Table -->
                        <table class="table table-striped table-hover mb-0" id="household_members_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fullname</th>
                                    <th>Age</th>
                                    <th>Relationship</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                            </div>
                        </div>
                    </div>
                    <!-- End Members Section -->
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>




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