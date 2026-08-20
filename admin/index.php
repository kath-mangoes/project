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
    $mobile = $row["mobile"];
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
                background-color: #4B49AC !important;
            }

            .btn-primary:hover {
                background-color: rgb(67, 64, 141) !important;
            }

            .btn-outline-primary:hover {
                background-color: #4B49AC !important;
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
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                <h3 class="font-weight-bold">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
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
                // Include the database connection
                include '../connection/config.php';

                // Query to get total count of residents
                $residentQuery = "SELECT COUNT(*) AS total_residents FROM tbl_residents";
                $residentResult = mysqli_query($conn, $residentQuery);
                $residentData = mysqli_fetch_assoc($residentResult);
                $totalResidents = $residentData['total_residents'];

                // Query to get total count of barangay officials
                $officialQuery = "SELECT COUNT(*) AS total_officials FROM tbl_brgyofficer";
                $officialResult = mysqli_query($conn, $officialQuery);
                $officialData = mysqli_fetch_assoc($officialResult);
                $totalOfficials = $officialData['total_officials'];

                // Query to get total count of blotters
                $blotterQuery = "SELECT COUNT(*) AS total_blotters FROM tbl_blotter";
                $blotterResult = mysqli_query($conn, $blotterQuery);
                $blotterData = mysqli_fetch_assoc($blotterResult);
                $totalBlotters = $blotterData['total_blotters'];

                // Query to get total count of complaints
                $complainQuery = "SELECT COUNT(*) AS total_complaints FROM tbl_compgriev";
                $complainResult = mysqli_query($conn, $complainQuery);
                $complainData = mysqli_fetch_assoc($complainResult);
                $totalComplaints = $complainData['total_complaints'];

                // Query to get monthly complaint data for the chart
                $monthlyComplaintQuery = "SELECT 
    MONTH(dateFiled) AS month,
    COUNT(*) AS count
  FROM tbl_compgriev
  WHERE YEAR(dateFiled) = YEAR(CURRENT_DATE())
  GROUP BY MONTH(dateFiled)
  ORDER BY MONTH(dateFiled)";
                $monthlyComplaintResult = mysqli_query($conn, $monthlyComplaintQuery);

                // Initialize an array with all months set to 0
                $complaintData = array_fill(1, 12, 0);

                // Fill the array with actual data
                while ($row = mysqli_fetch_assoc($monthlyComplaintResult)) {
                    $complaintData[$row['month']] = $row['count'];
                }

                // Query to get monthly blotter data for the chart
                $monthlyBlotterQuery = "SELECT 
    MONTH(dateFiled) AS month,
    COUNT(*) AS count
  FROM tbl_blotter
  WHERE YEAR(dateFiled) = YEAR(CURRENT_DATE())
  GROUP BY MONTH(dateFiled)
  ORDER BY MONTH(dateFiled)";
                $monthlyBlotterResult = mysqli_query($conn, $monthlyBlotterQuery);

                // Initialize an array with all months set to 0
                $blotterData = array_fill(1, 12, 0);

                // Fill the array with actual data
                while ($row = mysqli_fetch_assoc($monthlyBlotterResult)) {
                    $blotterData[$row['month']] = $row['count'];
                }
                ?>

                <div class="row">
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">RESIDENTS</p>
                                <p class="fs-30 mb-2"><?php echo $totalResidents; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">BARANGAY OFFICIAL</p>
                                <p class="fs-30 mb-2"><?php echo $totalOfficials; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">BLOTTER</p>
                                <p class="fs-30 mb-2"><?php echo $totalBlotters; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">COMPLAIN</p>
                                <p class="fs-30 mb-2"><?php echo $totalComplaints; ?></p>
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
                                    <a href="complains.php" class="text-decoration-none">
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

                <!-- Chart initialization script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Create the Complaint chart
                        if (document.getElementById('approvedAreaChart')) {
                            const ctx = document.getElementById('approvedAreaChart');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    datasets: [{
                                        label: 'Complaints',
                                        data: [
                                            <?php echo $complaintData[1]; ?>,
                                            <?php echo $complaintData[2]; ?>,
                                            <?php echo $complaintData[3]; ?>,
                                            <?php echo $complaintData[4]; ?>,
                                            <?php echo $complaintData[5]; ?>,
                                            <?php echo $complaintData[6]; ?>,
                                            <?php echo $complaintData[7]; ?>,
                                            <?php echo $complaintData[8]; ?>,
                                            <?php echo $complaintData[9]; ?>,
                                            <?php echo $complaintData[10]; ?>,
                                            <?php echo $complaintData[11]; ?>,
                                            <?php echo $complaintData[12]; ?>
                                        ],
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
                                        data: [
                                            <?php echo $blotterData[1]; ?>,
                                            <?php echo $blotterData[2]; ?>,
                                            <?php echo $blotterData[3]; ?>,
                                            <?php echo $blotterData[4]; ?>,
                                            <?php echo $blotterData[5]; ?>,
                                            <?php echo $blotterData[6]; ?>,
                                            <?php echo $blotterData[7]; ?>,
                                            <?php echo $blotterData[8]; ?>,
                                            <?php echo $blotterData[9]; ?>,
                                            <?php echo $blotterData[10]; ?>,
                                            <?php echo $blotterData[11]; ?>,
                                            <?php echo $blotterData[12]; ?>
                                        ],
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