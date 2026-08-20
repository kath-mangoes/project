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

// Fetch the user's data from the barangay ID request table based on the user ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tbl_bid WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Initialize the variables with default values
$image = "default_image.jpg"; // Placeholder if document_path is used for image
$first_name = "";
$middle_name = "";
$last_name = "";
$remarks = "";
$suffix = "";
$address = "";
$marital_status = "";
$id_no = "";
$precinct_number = "";
$blood_type = "";
$birthday = "";
$birthplace = "";
$height = "";
$weight = "";
$status = "";
$sss_gsis_number = "";
$tin_number = "";
$document_path = "";
$date_applied = "";
$date_issued = "";
$date_expiration = "";
$person_two_name = "";
$person_two_address = "";
$person_two_contact_info = "";

// Check if the query was successful and populate variables
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $remarks = $row["remarks"];
    $suffix = $row["suffix"];
    $address = $row["address"];
    $marital_status = $row["marital_status"];
    $id_no = $row["ID_No"];
    $precinct_number = $row["precinctNumber"];
    $blood_type = $row["bloodType"];
    $birthday = $row["birthday"];
    $birthplace = $row["birthplace"];
    $height = $row["height"];
    $weight = $row["weight"];
    $status = $row["status"];
    $sss_gsis_number = $row["SSSGSIS_Number"];
    $tin_number = $row["TIN_number"];
    $document_path = $row["document_path"];
    $date_applied = $row["dateApplied"];
    $date_issued = $row["dateIssued"];
    $date_expiration = $row["dateExpiration"];
    $person_two_name = $row["personTwoName"];
    $person_two_address = $row["personTwoAddress"];
    $person_two_contact_info = $row["personTwoContactInfo"];
}

// Optional: Assign values to session for access in other pages
$_SESSION['full_name'] = $first_name . ' ' . $last_name;
$_SESSION['document_path'] = $document_path;
$_SESSION['status'] = $status;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BarangayID Request | Barangay System</title>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                <!-- Barangay ID Request Modal -->
                <div class="modal fade" id="IDRequestModal" tabindex="-1" role="dialog" aria-labelledby="IDRequestModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="IDRequestModalLabel">Submit Barangay ID Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="add_bid.php" method="POST" enctype="multipart/form-data" novalidate id="idRequestForm" class="needs-validation">
                                <div class="modal-body">

                                    <!-- Personal Information Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-user-circle me-2"></i>Personal Information</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                                        value="<?= htmlspecialchars($last_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                                        value="<?= htmlspecialchars($first_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="middle_name">Middle Name</label>
                                                    <input type="text" class="form-control" id="middle_name" name="middle_name"
                                                        value="<?= htmlspecialchars($middle_name) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="suffix">Suffix</label>
                                                    <input type="text" class="form-control" id="suffix" name="suffix"
                                                        value="<?= htmlspecialchars($suffix) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    value="<?= htmlspecialchars($address) ?>" readonly>
                                            </div>                       
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="birthday">Birthday</label>
                                                    <input type="text" class="form-control" id="birthday" name="birthday"
                                                        value="<?= htmlspecialchars($birthday) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="birthplace">Birthplace</label>
                                                    <input type="text" class="form-control" id="birthplace" name="birthplace"
                                                        value="<?= htmlspecialchars($birthplace) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="marital_status">Civil Status</label>
                                                    <input type="text" class="form-control" id="marital_status" name="marital_status"
                                                        value="<?= htmlspecialchars($marital_status) ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="blood_type">Blood Type</label>
                                                    <input type="text" class="form-control" id="blood_type" name="blood_type"
                                                        value="<?= htmlspecialchars($blood_type) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="height">Height (cm)</label>
                                                    <input type="text" class="form-control" id="height" name="height"
                                                        value="<?= htmlspecialchars($height) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="weight">Weight (kg)</label>
                                                    <input type="text" class="form-control" id="weight" name="weight"
                                                        value="<?= htmlspecialchars($weight) ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- ID Information Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-id-card me-2"></i>Valid ID Number</h5>
                                        <div class="row">
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="precint_number">Precinct Number</label>
                                                    <input type="text" class="form-control" id="precint_number" name="precint_number"
                                                        value="<?= htmlspecialchars($precint_number) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="SSSGSIS_Number">SSS/GSIS Number</label>
                                                    <input type="text" class="form-control" id="SSSGSIS_Number" name="SSSGSIS_Number"
                                                        value="<?= htmlspecialchars($SSSGSIS_Number) ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="TIN_number">TIN Number</label>
                                                    <input type="text" class="form-control" id="TIN_number" name="TIN_number"
                                                        value="<?= htmlspecialchars($TIN_number) ?>" readonly>
                                                </div>
                                            </div>

                                            
                                        </div>
                                    </div>

                                    <!-- Emergency Contact Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-phone-alt me-2"></i>Emergency Contact Information</h5>
                                        <div class="form-group">
                                            <label for="personTwoName">Contact Person Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoName" name="personTwoName" placeholder="Enter Contact Person Name" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoName_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="personTwoAddress">Contact Person Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoAddress" name="personTwoAddress" placeholder="Enter Contact Person Address" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoAddress_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="personTwoContactInfo">Contact Person Phone Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="personTwoContactInfo" name="personTwoContactInfo" maxlength="11" pattern="\d{11}" 
                                                required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" placeholder="Enter Contact Person Phone Number" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="personTwoContactInfo_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Document Upload Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Supporting Documents</h5>
                                        <div class="file-upload">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5>Upload Supporting Document/Image</h5>
                                            <p>Please upload any required documents (Valid ID, Proof of Residency, etc.)</p>
                                            <input type="file" class="form-control" id="document_path" name="document_path[]" multiple>
                                            <div class="file-upload-info mt-2">
                                                <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small> <br>
                                                <small><i class="fas fa-info-circle me-1"></i>Hold Ctrl to select multiple files.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Terms and Submit -->
                                    <div class="mb-3 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="terms" required>
                                            <label class="form-check-label" for="terms">I confirm that the information provided is accurate and complete</label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit ID Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Form validation
                        (function() {
                            'use strict';

                            // Fetch all forms to apply validation
                            var forms = document.querySelectorAll('.needs-validation');

                            // Loop and prevent submission
                            Array.prototype.slice.call(forms).forEach(function(form) {
                                form.addEventListener('submit', function(event) {
                                    if (!form.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        })();

                        // Auto-fill today's date
                        document.getElementById('dateToday').valueAsDate = new Date();

                        // Resident selection change handling
                        document.getElementById('res_id').addEventListener('change', function() {
                            var selectedOption = this.options[this.selectedIndex];
                            document.getElementById('user_id').value = selectedOption.getAttribute('data-userid');
                            document.getElementById('first_name').value = selectedOption.getAttribute('data-firstname');
                            document.getElementById('last_name').value = selectedOption.getAttribute('data-lastname');
                            document.getElementById('middle_name').value = selectedOption.getAttribute('data-middlename');
                        });

                        // Display file name when selected
                        document.getElementById('document_path').addEventListener('change', function() {
                            var fileName = this.files[0]?.name;
                            if (fileName) {
                                var fileInfo = document.createElement('p');
                                fileInfo.className = 'mt-2 mb-0';
                                fileInfo.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>Selected file: <strong>' + fileName + '</strong>';

                                // Remove previous file info if exists
                                var existingInfo = document.querySelector('.file-selected-info');
                                if (existingInfo) {
                                    existingInfo.remove();
                                }

                                fileInfo.className += ' file-selected-info';
                                this.parentNode.appendChild(fileInfo);
                            }
                        });
                    });
                </script>
                <?php
                include '../connection/config.php';

                // Check for success messages
                if (isset($_GET['success'])) {
                    $successMessages = [
                        1 => "Barangay ID Request Submitted Successfully",
                        2 => "Barangay ID Request Updated Successfully"
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
                        // Only Barangay Secretary can edit/update requests
                        $canEdit = ($position == 'Barangay Secretary');
                    }
                    $position_stmt->close();
                }

                // Date filter variables
                $date_from = $_GET['date_from'] ?? '';
                $date_to = $_GET['date_to'] ?? '';

                // Build WHERE clause based on search
                $where_conditions = [];
                $params = [];
                $types = "";

                // Get all column names from the tbl_clearance table for search
                $columnsQuery = "SHOW COLUMNS FROM tbl_bid";
                $columnsResult = $conn->query($columnsQuery);
                $searchFields = [];

                if ($columnsResult) {
                    while ($column = $columnsResult->fetch_assoc()) {
                        $searchFields[] = "b." . $column['Field'];
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
                $count_sql = "SELECT COUNT(*) as total FROM tbl_bid b WHERE $where_clause";

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

               // Fetch clearance requests query
$sql = "SELECT b.BID_id, b.res_id, b.user_id, b.first_name, b.middle_name, b.last_name, b.remarks, b.suffix,
b.address, b.ID_No, b.precinctNumber, b.bloodType, 
b.birthday, b.birthplace, b.document_path, b.height, b.weight,
b.SSSGSIS_Number, b.TIN_number, b.dateApplied, b.personTwoName, b.personTwoContactInfo, b.personTwoAddress,
b.status, b.created_at
FROM tbl_bid b
WHERE $where_clause
ORDER BY b.created_at DESC 
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
                                    <p class="card-title mb-0">Barangay BarangayID Requests</p>
                                    <div class="ml-auto">
                                        
                                            <!--<button class="btn btn-primary mb-3" data-toggle="modal"
                                                data-target="#IDRequestModal">Request</button>-->
                                       
                                    </div>
                                </div>


                               

                                <!-- Filter section -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search Blotter Request">
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
                                                <th>ID Request No.</th>
                                                <th>Full Name</th>
                                                <th>Address</th>
                                                <th>ID Number</th>
                                                <th>Date Applied</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['BID_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['ID_No']); ?></td>
                                                        <td><?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?></td>
                                                        <td>
                                                            <span class="badge 
                                                <?php
                                                    if ($row['status'] == 'To Be Approved')
                                                        echo 'badge-warning text-white font-weight-bold';
                                                    elseif ($row['status'] == 'On Going')
                                                        echo 'badge-info text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Denied')
                                                        echo 'badge-danger text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Resubmit')
                                                        echo 'badge-secondary text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Completed')
                                                        echo 'badge-success text-white font-weight-bold';
                                                    else
                                                        echo 'badge-warning text-white font-weight-bold';
                                ?>">
                                                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <!-- View button for all positions -->
                                                            <button class="btn btn-info btn-sm" data-toggle="modal" title="View"
                                                                data-target="#viewIDModal<?php echo $row['BID_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>

                                                            <button class="btn btn-warning btn-sm" data-toggle="modal" title="Update"
                                                                data-target="#editModal<?php echo $row['BID_id']; ?>">
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>
                                                    
                                                        </td>
                                                    </tr>

                                                   <!-- View Modal -->
                                                   <div class="modal fade" id="viewIDModal<?php echo $row['BID_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Enhanced Header with gradient background -->
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                        <i class="fas fa-id-card mr-2"></i>View Barangay ID
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body text-center">
                                                                    <?php
                                                                    $certification_id = $row['BID_id'];
                                                                    $baseFolder = "generate_certificate";
                                                                    $absoluteBasePath = __DIR__ . "/$baseFolder";

                                                                    // Check if base folder exists
                                                                    if (is_dir($absoluteBasePath)) {
                                                                        $allImages = [];

                                                                        // Search each subfolder inside generate_certificate
                                                                        foreach (glob("$absoluteBasePath/*", GLOB_ONLYDIR) as $subfolder) {
                                                                            $images = glob("$subfolder/*_{$certification_id}_*.jpg");
                                                                            $allImages = array_merge($allImages, $images);
                                                                        }

                                                                        if (!empty($allImages)) {
                                                                            // Sort all found images by modified time, descending
                                                                            usort($allImages, fn($a, $b) => filemtime($b) - filemtime($a));
                                                                            $latestImage = $allImages[0];
                                                                            $relativePath = $baseFolder . '/' . basename(dirname($latestImage));
                                                                            $filename = basename($latestImage);
                                                                            ?>

                                                                            <img src="<?php echo $relativePath . '/' . $filename; ?>" class="img-fluid mb-3" style="max-height: 500px;">
                                                                            <br>
                                                                            <a href="<?php echo $relativePath . '/' . $filename; ?>" download class="btn btn-success mt-2">
                                                                                <i class="fas fa-download"></i> Download Certificate
                                                                            </a>


                                                                           <a href="<?php echo $relativePath . '/' . $filename; ?>" 
                                                                            download 
                                                                            class="btn btn-warning mt-2 generate-id" 
                                                                            data-id="<?= $row['BID_id']; ?>">
                                                                                <i class="fas fa-sync-alt"></i> Re-Generate ID
                                                                            </a>

                                                                          


                                                                            <?php
                                                                        } else {
                                                                            echo "<p class='text-danger'>No certificate image found for this ID (BID_id: $certification_id).</p>";
                                                                        }
                                                                    } else {
                                                                        echo "<p class='text-danger'>Base folder does not exist: $absoluteBasePath</p>";
                                                                    }
                                                                    ?>

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

                                                   <!-- Edit Modal for Barangay Secretary only -->
                                                  
                                                        <div class="modal fade"
                                                            id="editModal<?php echo $row['BID_id']; ?>" tabindex="-1"
                                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-warning text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="editModalLabel">
                                                                            <i class="fas fa-edit mr-2"></i>Update Barangay ID
                                                                            Request
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
                                            <?php
                                                    if ($row['status'] == 'Approved') echo 'badge-warning';
                                                    elseif ($row['status'] == 'Processed') echo 'badge-info';
                                                    elseif ($row['status'] == 'Denied') echo 'badge-danger';
                                                    elseif ($row['status'] == 'Released') echo 'badge-primary';
                                                    else echo 'badge-warning';
                                            ?>">
                                                                            <i class="fas 
                                                <?php
                                                    if ($row['status'] == 'Approved') echo 'fa-check-circle';
                                                    elseif ($row['status'] == 'Processed') echo 'fa-cog';
                                                    elseif ($row['status'] == 'Denied') echo 'fa-times-circle';
                                                    elseif ($row['status'] == 'Released') echo 'fa-paper-plane';
                                                    else echo 'fa-exclamation-circle';
                                                ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                                        </span>
                                                                    </div>

                                                                    <?php
                                                                    // Fetch complete details for the view modal
                                                                    $detail_sql = "SELECT * FROM tbl_bid WHERE BID_id = ?";
                                                                    $detail_stmt = $conn->prepare($detail_sql);
                                                                    $detail_stmt->bind_param("i", $row['BID_id']);
                                                                    $detail_stmt->execute();
                                                                    $detail_result = $detail_stmt->get_result();
                                                                    $detail = $detail_result->fetch_assoc();
                                                                    $detail_stmt->close();
                                                                    ?>

                                                                    <!-- Personal Information Card -->
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
                                                                                        <label class="text-muted small text-uppercase">Full Name</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php 
                                                                                                echo htmlspecialchars($detail['first_name'] . ' ' . $detail['middle_name'] . ' ' . $detail['last_name']); 
                                                                                            ?>
                                                                                        </p>

                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['address']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Birthday</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo date('F d, Y', strtotime($detail['birthday'])); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Birthplace</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['birthplace']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">ID Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($detail['ID_No']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Precinct Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['precinctNumber']) ? htmlspecialchars($detail['precinctNumber']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Blood Type</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['bloodType']) ? htmlspecialchars($detail['bloodType']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Height / Weight</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php
                                                                                            echo (!empty($detail['height']) ? htmlspecialchars($detail['height']) . ' cm' : 'N/A');
                                                                                            echo ' / ';
                                                                                            echo (!empty($detail['weight']) ? htmlspecialchars($detail['weight']) . ' kg' : 'N/A');
                                                                                            ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Additional Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-info-circle mr-2"></i>Additional Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">SSSGSIS Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['SSSGSIS_Number']) ? htmlspecialchars($detail['SSSGSIS_Number']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Tin Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['TIN_number']) ? htmlspecialchars($detail['TIN_number']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                           <!-- Display Profile Image-->
                                                                            <?php if (!empty($detail['profileImage'])): ?>
                                                                                <div class="row mt-3">
                                                                                    <div class="col-12">
                                                                                        <label class="text-muted small text-uppercase">Profile Image</label>
                                                                                        <div class="text-center">
                                                                                            <img 
                                                                                                src="../dist/assets/images/uploads/id_images/<?php echo htmlspecialchars($detail['profileImage']); ?>" 
                                                                                                alt="Profile Image" 
                                                                                                class="img-thumbnail" 
                                                                                                style="max-height: 200px;"
                                                                                            >
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Contact Information Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-address-card mr-2"></i>Contact Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoName']) ? htmlspecialchars($detail['personTwoName']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Address</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoAddress']) ? htmlspecialchars($detail['personTwoAddress']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Contact Person Number</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($detail['personTwoContactInfo']) ? htmlspecialchars($detail['personTwoContactInfo']) : 'N/A'; ?></p>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- Document Preview Section -->
                                                                    <?php
                                                                        // Assuming $row['document_path'] contains comma-separated file paths
                                                                        $document_paths = explode(',', $row['document_path']);
                                                                        
                                                                        if (!empty($document_paths)): ?>
                                                                            <div class="card border-0 shadow-sm">
                                                                                <div class="card-header bg-light py-3">
                                                                                    <h6 class="font-weight-bold text-primary mb-0">
                                                                                        <i class="fas fa-file-alt mr-2"></i>Supporting Documents
                                                                                    </h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <?php foreach ($document_paths as $path):
                                                                                            $path = trim($path);
                                                                                            $file_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                                                                            
                                                                                            <div class="col-md-4 mb-4 text-center">
                                                                                                <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                                                                    <div class="img-container border p-2 mb-2">
                                                                                                        <img src="../dist/assets/images/uploads/id-documents/<?php echo $path; ?>"
                                                                                                             class="img-fluid" alt="Document">
                                                                                                    </div>
                                                                                                    <a href="../dist/assets/images/uploads/id-documents/<?php echo $path; ?>"
                                                                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                                        <i class="fas fa-external-link-alt mr-1"></i> View Full Size
                                                                                                    </a>
                                                                                                <?php else: ?>
                                                                                                    <div class="border p-4">
                                                                                                        <div class="display-4 text-muted mb-2">
                                                                                                            <i class="far fa-file-<?php
                                                                                                                echo $file_ext == 'pdf' ? 'pdf' :
                                                                                                                    (in_array($file_ext, ['doc', 'docx']) ? 'word' :
                                                                                                                    (in_array($file_ext, ['xls', 'xlsx']) ? 'excel' :
                                                                                                                    (in_array($file_ext, ['ppt', 'pptx']) ? 'powerpoint' : 'alt')));
                                                                                                            ?>"></i>
                                                                                                        </div>
                                                                                                        <p class="text-muted small mb-1"><?php echo strtoupper($file_ext); ?> Document</p>
                                                                                                        <a href="../dist/assets/images/uploads/id-documents/<?php echo $path; ?>"
                                                                                                           target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                                                            <i class="fas fa-eye mr-1"></i> View Document
                                                                                                        </a>
                                                                                                    </div>
                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                        <?php endforeach; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>



                                                                    
                                                                    
                                                                    
                                                                    
                                                                    <!-- Timeline Card -->
                                                                    <div class="card border-0 shadow-sm">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-history mr-2"></i>Request Timeline
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <ul class="timeline">
                                                                                <li class="timeline-item">
                                                                                    <div class="timeline-marker bg-success"></div>
                                                                                    <div class="timeline-content">
                                                                                        <h4 class="timeline-title font-weight-bold">Date Applied</h4>
                                                                                        <p class="timeline-date">
                                                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                                                            <?php echo date('F d, Y h:i A', strtotime($detail['dateApplied'])); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </li>





                                                                                <?php if (!empty($detail['dateIssued']) && $detail['status'] == 'Released'): ?>
                                                                                    <li class="timeline-item">
                                                                                        <div class="timeline-marker bg-success"></div>
                                                                                        <div class="timeline-content">
                                                                                            <h4 class="timeline-title font-weight-bold">Date Released</h4>
                                                                                            <p class="timeline-date">
                                                                                                <i class="far fa-calendar-alt mr-1"></i>
                                                                                                <?php echo date('F d, Y h:i A', strtotime($detail['dateIssued'])); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                







                                                                    <!-- <form action="update_BID.php" method="POST"> -->
                                                                    <form method="POST">
                                                                        <div class="modal-body py-4">
                                                                            <input type="hidden" name="BID_id"
                                                                                value="<?php echo $row['BID_id']; ?>">

                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="status<?php echo $row['BID_id']; ?>">Update
                                                                                    Status</label>
                                                                                <select class="form-control" name="status"
                                                                                    id="status<?php echo $row['BID_id']; ?>"
                                                                                    required>
                                                                                    <option value="">Select Status</option>
                                                                                    <option value="Approved" <?php echo ($row['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                                                    <option value="Denied" <?php echo ($row['status'] == 'Denied') ? 'selected' : ''; ?>>Denied</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="remarks<?php echo $row['BID_id']; ?>">Remarks/Comments</label>
                                                                                <textarea class="form-control" name="remarks"
                                                                                    id="remarks<?php echo $row['BID_id']; ?>"
                                                                                    rows="4"><?php echo isset($row['remarks']) ? htmlspecialchars($row['remarks']) : ''; ?></textarea>
                                                                            </div>

                                                                            <div class="alert alert-info">
                                                                                <small><i class="fas fa-info-circle mr-1"></i> As
                                                                                    Admin, you can update the status of
                                                                                    this certificate request.</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer bg-light">
                                                                            <button type="submit" class="btn btn-success generate-id" data-id="<?= $row['BID_id']; ?>" >
                                                                                <i class="fas fa-save mr-1"></i> Update Request
                                                                            </button>

                                                                            <!-- GENERATE ID  -->
                                                                            <?php
                                                                                $status = isset($row['status']) ? strtolower($row['status']) : '';
                                                                                $isApproved = ($status === 'approved');
                                                                                $isDenied = ($status === 'denied');
                                                                            ?>
                                                                            <!-- <button type="button" class="btn btn-success" 
                                                                                onclick="generateCertificate(<?php echo $row['BID_id']; ?>)" 
                                                                                <?php echo (!$isApproved || $isDenied) ? 'disabled' : ''; ?>>
                                                                                <i class="fas fa-save mr-1"></i> 
                                                                                <?php echo $isApproved ? 'Generate' : 'Waiting for Approval'; ?>
                                                                            </button> -->


                                                                           




                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                                            </button>
                                                                           
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No request found</td>
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
  
    <script>

    
                                                                                $('.generate-id').on("click",function (e) {
                                                                                    e.preventDefault();

                                                                                    var $btn = $(this);
                                                                                    var certification_id = $btn.data("id");

                                                                                    // Get form values
                                                                                    var status = $("#status" + certification_id).val();
                                                                                    console.log(status);

                                                                                    if(status=="Approved"){

                                                                                         var originalText = $btn.html();
                                                                                    $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
                                                                                    $btn.prop("disabled", true);

                                                                                    // AJAX request
                                                                                    $.ajax({
                                                                                        url: "gen_id.php",
                                                                                        type: "POST",
                                                                                        dataType: "json", 
                                                                                        data: {
                                                                                            BID_id: certification_id,
                                                                                        },
                                                                                      success: function (response) {
                                                                                        console.log(response); 
                                                                                        if (response.success) {
                                                                                            alert(response.message);

                                                                                           location.reload(); 
                                                                                        } else {
                                                                                            alert("Error: " + response.message + 
                                                                                                (response.error ? "\nDetails: " + response.error : ""));
                                                                                        }
                                                                                    },
                                                                                        error: function (xhr, status, error) {
                                                                                            alert("Network error: " + error);
                                                                                        },
                                                                                        complete: function () {
                                                                                            // Reset button state kahit success or fail
                                                                                            $btn.html(originalText);
                                                                                            $btn.prop("disabled", false);
                                                                                        }
                                                                                    });

                                                                                    }
                                                                                    // Show loading state
                                                                                   
                                                                                });

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