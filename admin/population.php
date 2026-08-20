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
    <title>Population | Barangay System</title>
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

                <?php
                include '../connection/config.php';

                // Get total population (total user count)
                $sql_population = "SELECT COUNT(*) as total_count FROM tbl_user";
                $result_population = mysqli_query($conn, $sql_population);
                $row_population = mysqli_fetch_assoc($result_population);
                $total_population = $row_population['total_count'];

                // Get total registered voters
                $sql_voters = "SELECT COUNT(*) as voter_count FROM tbl_residents WHERE 	is_registered_voter = 'Registered'";
                $result_voters = mysqli_query($conn, $sql_voters);
                $row_voters = mysqli_fetch_assoc($result_voters);
                $total_voters = $row_voters['voter_count'];

                // Get male count
                $sql_male = "SELECT COUNT(*) as male_count FROM tbl_residents WHERE gender = 'Male'";
                $result_male = mysqli_query($conn, $sql_male);
                $row_male = mysqli_fetch_assoc($result_male);
                $total_male = $row_male['male_count'];

                // Get female count
                $sql_female = "SELECT COUNT(*) as female_count FROM tbl_residents WHERE gender = 'Female'";
                $result_female = mysqli_query($conn, $sql_female);
                $row_female = mysqli_fetch_assoc($result_female);
                $total_female = $row_female['female_count'];

                // Get senior count
                $sql_senior = "SELECT COUNT(*) as senior_count FROM tbl_residents WHERE is_senior = 'Yes'";
                $result_senior = mysqli_query($conn, $sql_senior);
                $row_senior = mysqli_fetch_assoc($result_senior);
                $total_senior = $row_senior['senior_count'];

                // Get PWD count
                $sql_pwd = "SELECT COUNT(*) as pwd_count FROM tbl_residents WHERE is_pwd = 'Yes'";
                $result_pwd = mysqli_query($conn, $sql_pwd);
                $row_pwd = mysqli_fetch_assoc($result_pwd);
                $total_pwd = $row_pwd['pwd_count'];

                // Get 4PS count
                $sql_4ps = "SELECT COUNT(*) as fourps_count FROM tbl_residents WHERE is_4ps_member = 'Yes'";
                $result_4ps = mysqli_query($conn, $sql_4ps);
                $row_4ps = mysqli_fetch_assoc($result_4ps);
                $total_4ps = $row_4ps['fourps_count'];
                ?>

                <div class="row">
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">TOTAL POPULATION</p>
                                <p class="fs-30 mb-2"><?php echo $total_population; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">REGISTERED VOTERS</p>
                                <p class="fs-30 mb-2"><?php echo $total_voters; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">MALE</p>
                                <p class="fs-30 mb-2"><?php echo $total_male; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">FEMALE</p>
                                <p class="fs-30 mb-2"><?php echo $total_female; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">SENIOR</p>
                                <p class="fs-30 mb-2"><?php echo $total_senior; ?></p>
                                
                                <!-- Right-aligned Export Button -->
                                <div class="text-end">
                                    <a href="export_senior.php?format=excel" class="btn btn-primary btn-sm">
                                         Export
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">PWD</p>
                                <p class="fs-30 mb-2"><?php echo $total_pwd; ?></p>
                                
                                <!-- Right-aligned Export Button -->
                                <div class="text-end">
                                    <a href="export_pwd.php?format=excel" class="btn btn-primary btn-sm">
                                         Export
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">4PS</p>
                                <p class="fs-30 mb-2"><?php echo $total_4ps; ?></p>
                                
                                <!-- Right-aligned Export Button -->
                                <div class="text-end">
                                    <a href="export_four.php?format=excel" class="btn btn-primary btn-sm">
                                         Export
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              
                <?php


                // Initialize pagination variables for demographics
                $demo_page = $_GET['demo_page'] ?? 1;
                $demo_limit = 10;
                $demo_offset = ($demo_page - 1) * $demo_limit;

                // Fetch age group statistics from tbl_residents
                $age_groups = [
                    '18-30' => ['male' => 0, 'female' => 0, 'total' => 0],
                    '31-40' => ['male' => 0, 'female' => 0, 'total' => 0],
                    '41-50' => ['male' => 0, 'female' => 0, 'total' => 0],
                    '51-60' => ['male' => 0, 'female' => 0, 'total' => 0],
                    '61-70' => ['male' => 0, 'female' => 0, 'total' => 0],
                    '71-80' => ['male' => 0, 'female' => 0, 'total' => 0],
                    'total' => ['male' => 0, 'female' => 0, 'total' => 0]
                ];

                // Query to get residents count by age group and gender from tbl_residents
                $residents_sql = "SELECT 
CASE 
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 18 AND 30 THEN '18-30'
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 31 AND 40 THEN '31-40'
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 41 AND 50 THEN '41-50'
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 51 AND 60 THEN '51-60'
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 61 AND 70 THEN '61-70'
    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 71 AND 80 THEN '71-80'
    ELSE 'other'
END AS age_group,
gender,
COUNT(*) as count
FROM tbl_residents
WHERE TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 18 AND 80
GROUP BY age_group, gender
ORDER BY age_group";

                $residents_result = $conn->query($residents_sql);

                if ($residents_result) {
                    while ($row = $residents_result->fetch_assoc()) {
                        $age_group = $row['age_group'];
                        $gender = strtolower($row['gender']);
                        $count = $row['count'];

                        if (isset($age_groups[$age_group]) && ($gender == 'male' || $gender == 'female')) {
                            $age_groups[$age_group][$gender] = $count;
                            $age_groups[$age_group]['total'] += $count;

                            // Update totals
                            $age_groups['total'][$gender] += $count;
                            $age_groups['total']['total'] += $count;
                        }
                    }
                }

                // For demographics, we don't need pagination for the table itself, 
                // but we'll keep this to maintain uniformity with the page structure
                $demo_total_rows = count($age_groups) - 1; // Minus 1 for the total row
                $demo_total_pages = ceil($demo_total_rows / $demo_limit);
                ?>

                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Resident Demographics</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Age Group</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($age_groups as $group => $data): ?>
                                                <?php if ($group != 'total'):
                                                    // Calculate gender ratio for each age group
                                                    $group_male_ratio = $data['total'] > 0 ? round(($data['male'] / $data['total']) * 100, 1) : 0;
                                                    $group_female_ratio = $data['total'] > 0 ? round(($data['female'] / $data['total']) * 100, 1) : 0;
                                                ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($group); ?></td>
                                                        <td><?php echo number_format($data['male']); ?></td>
                                                        <td><?php echo number_format($data['female']); ?></td>
                                                        <td><?php echo number_format($data['total']); ?></td>

                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="font-weight-bold">
                                                <td>Total</td>
                                                <td><?php echo number_format($age_groups['total']['male']); ?></td>
                                                <td><?php echo number_format($age_groups['total']['female']); ?></td>
                                                <td><?php echo number_format($age_groups['total']['total']); ?></td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Add view details modal structure -->
                                <div class="modal fade" id="viewDemographicsModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content shadow-lg border-0">
                                            <div class="modal-header bg-gradient-primary text-white py-3">
                                                <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                    <i class="fas fa-chart-bar mr-2"></i>Age Group Details
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body py-4" id="demographicsDetailContent">
                                                <!-- Content will be loaded dynamically -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($demo_page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?demo_page=<?php echo $demo_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $demo_total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $demo_page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?demo_page=<?php echo $i; ?>"
                                                    style="background:color: #141E30 !important;"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($demo_page >= $demo_total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?demo_page=<?php echo $demo_page + 1; ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true"> &raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php


                    // Initialize pagination variables for residents
                    $residents_search = $_GET['residents_search'] ?? '';
                    $residents_page = $_GET['residents_page'] ?? 1;
                    $residents_limit = 10;
                    $residents_offset = ($residents_page - 1) * $residents_limit;

                    // Build the query to fetch residents
                    $residents_where_conditions = [];
                    $residents_params = [];
                    $residents_types = "";

                    // Add search condition if search parameter exists
                    if (!empty($residents_search)) {
                        $residents_where_conditions[] = "(r.first_name LIKE ? OR r.middle_name LIKE ? OR r.last_name LIKE ?)";
                        $residents_params[] = "%$residents_search%";
                        $residents_params[] = "%$residents_search%";
                        $residents_params[] = "%$residents_search%";
                        $residents_types .= "sss";
                    }

                    // Combine WHERE conditions
                    $residents_where_clause = !empty($residents_where_conditions) ? "WHERE " . implode(" AND ", $residents_where_conditions) : "";

                    // Count total records for pagination
                    $residents_count_sql = "SELECT COUNT(*) as total 
                FROM tbl_residents r 
                LEFT JOIN tbl_user u ON r.user_id = u.user_id 
                $residents_where_clause";

                    if (!empty($residents_params)) {
                        $residents_count_stmt = $conn->prepare($residents_count_sql);
                        $residents_count_stmt->bind_param($residents_types, ...$residents_params);
                        $residents_count_stmt->execute();
                        $residents_count_result = $residents_count_stmt->get_result();
                        $residents_count_stmt->close();
                    } else {
                        $residents_count_result = $conn->query($residents_count_sql);
                    }

                    $residents_total_rows = $residents_count_result->fetch_assoc()['total'];
                    $residents_total_pages = ceil($residents_total_rows / $residents_limit);

                    // Fetch residents
                    $residents_sql = "SELECT r.res_id, r.user_id, r.first_name, r.middle_name, r.last_name, u.image
                FROM tbl_residents r
                LEFT JOIN tbl_user u ON r.user_id = u.user_id
                $residents_where_clause
                ORDER BY r.last_name ASC
                LIMIT ? OFFSET ?";

                    // Add limit and offset params
                    $residents_params[] = $residents_limit;
                    $residents_params[] = $residents_offset;
                    $residents_types .= "ii";

                    // Prepare and execute statement
                    $residents_stmt = $conn->prepare($residents_sql);
                    if (!empty($residents_params)) {
                        $residents_stmt->bind_param($residents_types, ...$residents_params);
                    }
                    $residents_stmt->execute();
                    $residents_result = $residents_stmt->get_result();
                    $residents_stmt->close();
                    ?>

                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Residents</p>
                                </div>
                                <!-- Filter section -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="residentsSearchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="residents_search" id="residentsSearchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($residents_search); ?>"
                                                    placeholder="Search Residents">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="residentsClearButton"
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
                                                <th>Profile</th>
                                                <th>Resident ID</th>
                                                <th>Full Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($residents_result->num_rows > 0): ?>
                                                <?php while ($row = $residents_result->fetch_assoc()):
                                                    // Format full name
                                                    $fullName = $row['first_name'];
                                                    if (!empty($row['middle_name'])) {
                                                        $fullName .= ' ' . substr($row['middle_name'], 0, 1) . '.';
                                                    }
                                                    $fullName .= ' ' . $row['last_name'];

                                                    // Default image if none available
                                                    $profileImage = !empty($row['image']) ? $row['image'] : 'default-avatar.jpg';
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <img src="../dist/assets/images/user/<?php echo htmlspecialchars($profileImage); ?>"
                                                                alt="Profile" class="rounded-circle" width="50" height="50">
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['res_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($fullName); ?></td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm" data-toggle="modal" title="View Resident"
                                                                data-target="#viewResidentModal<?php echo $row['res_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- View Resident Modal -->
                                                    <div class="modal fade" id="viewResidentModal<?php echo $row['res_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Enhanced Header with gradient background -->
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                        <i class="fas fa-user mr-2"></i>Resident Details
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body py-4">
                                                                    <!-- Profile Photo -->
                                                                    <div class="text-center mb-4">
                                                                        <img src="../dist/assets/images/user/<?php echo htmlspecialchars($profileImage); ?>"
                                                                            alt="Profile" class="rounded-circle" width="150" height="150">
                                                                    </div>

                                                                    <!-- Resident Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Personal Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">First Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['first_name']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Middle Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($row['middle_name']) ? htmlspecialchars($row['middle_name']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Last Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['last_name']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Resident ID</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['res_id']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">User ID</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['user_id']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No residents found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($residents_page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?residents_search=<?php echo htmlspecialchars($residents_search); ?>&residents_page=<?php echo $residents_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $residents_total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $residents_page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?residents_search=<?php echo htmlspecialchars($residents_search); ?>&residents_page=<?php echo $i; ?>"
                                                    style="background:color:#141E30 !important;"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($residents_page >= $residents_total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?residents_search=<?php echo htmlspecialchars($residents_search); ?>&residents_page=<?php echo $residents_page + 1; ?>"
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
            // Barangay Officials search
            var searchFormOfficials = document.getElementById('officialsSearchForm');
            var searchInputOfficials = document.getElementById('officialsSearchInput');
            var clearButtonOfficials = document.getElementById('officialsClearButton');

            // Demographics search
            var searchFormDemographics = document.getElementById('DemographicsSearchForm');
            var searchInputDemographics = document.getElementById('DemographicsSearchInput');
            var clearButtonDemographics = document.getElementById('DemographicsclearButton');

            // Residents search
            var searchFormResidents = document.getElementById('residentsSearchForm');
            var searchInputResidents = document.getElementById('residentsSearchInput');
            var clearButtonResidents = document.getElementById('residentsClearButton');

            // Filter form elements
            var filterForm = document.getElementById('filterForm');
            var clearFilters = document.getElementById('clearFilters');

            // Officials clear button
            if (clearButtonOfficials) {
                clearButtonOfficials.addEventListener('click', function() {
                    searchInputOfficials.value = '';
                    searchFormOfficials.submit();
                });
            }

            // Demographics clear button
            if (clearButtonDemographics) {
                clearButtonDemographics.addEventListener('click', function() {
                    searchInputDemographics.value = '';
                    searchFormDemographics.submit();
                });
            }

            // Residents clear button
            if (clearButtonResidents) {
                clearButtonResidents.addEventListener('click', function() {
                    searchInputResidents.value = '';
                    searchFormResidents.submit();
                });
            }

            // Clear filters button for filter form
            if (clearFilters && filterForm) {
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