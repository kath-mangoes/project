<?php
/*
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "barangay");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function sanitize($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $middle_name = sanitize($_POST['middle_name']);
    $position = sanitize($_POST['position']);
    $address = sanitize($_POST['address']);
    $mobile = sanitize($_POST['mobile']);
    $birthdate = $_POST['birthdate'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (empty($position)) $errors[] = "Position is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (!preg_match("/^[0-9]{11}$/", $mobile)) $errors[] = "Mobile number must be 11 digits.";
    if (!strtotime($birthdate)) $errors[] = "Valid birthdate required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO tbl_brgyofficer 
            (first_name, last_name, middle_name, position, address, mobile, birthdate, email, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $first_name, $last_name, $middle_name, $position, $address, $mobile, $birthdate, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration successful.";
        } else {
            $errors[] = "Email already exists or database error.";
        }

        $stmt->close();
    }

    $conn->close();
}

*/
/*
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "barangay");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function sanitize($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $middle_name = sanitize($_POST['middle_name']);
    $position = sanitize($_POST['position']);
    $address = sanitize($_POST['address']);
    $mobile = sanitize($_POST['mobile']);
    $birthdate = $_POST['birthdate'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $user_id = uniqid('brgyofficer_');

    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (empty($position)) $errors[] = "Position is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (!preg_match("/^[0-9]{11}$/", $mobile)) $errors[] = "Mobile number must be 11 digits.";
    if (!strtotime($birthdate)) $errors[] = "Valid birthdate required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Optional: Use transactions to ensure both inserts succeed
        $conn->begin_transaction();

        try {
            // Insert into tbl_brgyofficer
            $stmt1 = $conn->prepare("INSERT INTO tbl_brgyofficer 
                (user_id, first_name, last_name, middle_name, position, address, mobile, birthdate, email) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssssssss", $first_name, $last_name, $middle_name, $position, $address, $mobile, $birthdate, $email, $hashed_password);
            $stmt1->execute();
            $stmt1->close();

            // Insert into tbl_user
            $stmt2 = $conn->prepare("INSERT INTO tbl_user (user_id, first_name, last_name, email, password, role, account_status)
                VALUES (?, ?, ?, ?, ?)");
            $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
            $role = $position; // or set a fixed role like 'officer' if needed
            $stmt2->bind_param("ssss", $full_name, $email, $hashed_password, $role);
            $stmt2->execute();
            $stmt2->close();

            $conn->commit();
            $success = "Registration successful.";

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Registration failed: " . $e->getMessage();
        }
    }

    $conn->close();
}*/

session_start();
include 'connection/config.php';
include 'functions.php';

if (isset($_POST['register'])) {
    // Collect and sanitize form data
    $user_id = uniqid('officer_');
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $position = trim($_POST['position']);
    $address = trim($_POST['address']);
    $mobile = trim($_POST['mobile']);
    $birthdate = $_POST['birthdate'];
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'barangay_official';
    $status = 'Inactive';  // Set to Inactive for initial registration

    // Validate fields
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (!preg_match('/^[0-9]{11}$/', $mobile)) {
        $errors[] = 'Mobile number must be 11 digits.';
    }
    if (strlen($_POST['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        $stmt_user = $conn->prepare("INSERT INTO tbl_user (
            user_id, first_name, last_name, email, password, role, account_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt_user === false) {
            die('MySQL prepare error for tbl_user: ' . $conn->error);
        }

        $stmt_user->bind_param("sssssss", $user_id, $first_name, $last_name, $email, $password, $role, $status);

        if ($stmt_user->execute()) {
   
            $stmt_officer = $conn->prepare("INSERT INTO tbl_brgyofficer (
                user_id, first_name, middle_name, last_name, position, address, mobile, birthday, email, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");


            if ($stmt_officer === false) {
                die('MySQL prepare error for tbl_brgyofficer: ' . $conn->error);
            }

            $stmt_officer->bind_param(
                "ssssssssss",
                $user_id, $first_name, $middle_name, $last_name, $position,
                $address, $mobile, $birthdate, $email, $status  // Set status to 'Inactive' here
            );

            if ($stmt_officer->execute()) {
                echo "<script>alert('Registration successful. The account is currently inactive. Please wait for admin approval.'); window.location.href='login.php';</script>";
                exit();
            } else {
                die('Error executing tbl_brgyofficer query: ' . $stmt_officer->error);
            }
        } else {
            die('Error executing tbl_user query: ' . $stmt_user->error);
        }
    } else {
        // If there are validation errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Barangay Official Registration | Barangay System</title>
    <!-- endinject -->
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --light: #F9F9F9;
            --blue: #141E30;
            --hover: #0e1624;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        body {
            background-image: url('https://source.unsplash.com/1600x900/?nature,landscape');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-weight: 500;
            color: var(--blue);
        }

        .error-msg {
            color: var(--red);
            font-size: 0.875em;
        }

        .password-rules {
            font-size: 0.8em;
            color: var(--dark-grey);
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html>
<head>
    <title>Barangay Official Registration </title>
    <style>
        :root {
            --light: #F9F9F9;
            --blue: #141E30;
            --hover: #0e1624;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }
        body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: url('https://barangay400.com/WEBSITE/image/b2.png') no-repeat center center fixed;
    background-size: cover;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(20, 30, 48, 0.8); /* dark overlay */
    backdrop-filter: blur(6px);
    z-index: -1;
}

.container {
    max-width: 900px;
    margin: 60px auto;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4);
    color: #fff;
}

h3 {
    text-align: center;
    color: #fff;
    margin-bottom: 30px;
}

label {
    font-weight: bold;
    color: #fff;
}

input, select, textarea {
    background-color: rgba(255,255,255,0.8);
    border: none;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    width: 100%;
}

input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 2px var(--yellow);
}

button {
    background-color: var(--yellow);
    border: none;
    width: 100%;
    padding: 12px;
    color: var(--dark);
    font-weight: bold;
    border-radius: 8px;
    font-size: 18px;
}

button:hover {
    background-color: var(--orange);
    color: #fff;
}


   

        .form-label {
            font-weight: 500;
            color: var(--blue);
            color: #fff;
        }

        .error-msg {
            color: var(--red);
            font-size: 0.875em;
        }

        .password-rules {
            font-size: 0.8em;
            color: var(--dark-grey);
        }
    </style>
</head>

<body>
    
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md"
            <div class="card p-4">
                <h3 class="text-center mb-4" style="color: #fff;">Register Barangay Officer</h3>
                <form id="registrationForm" method="POST" novalidate>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                        <div class="invalid-feedback">Only letters allowed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                         <input type="text" name="middle_name" class="form-control">
                        <div class="invalid-feedback">Only letters allowed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                        <div class="invalid-feedback">Only letters allowed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <select name="position" class="form-select" required>
                            <option value="">Select Position</option>
                            <option value="BarangayCaptain">Barangay Captain</option>
                            <option value="BarangayTreasurer">Barangay Treasurer</option>
                            <option value="BarangaySecretary">Barangay Secretary</option>
                            <option value="Lupon Tagapamayapa">Lupon Tagapamayapa</option>
                            <option value="Tanod">Tanod</option>
                            <option value="SKChairman">SK Chairman</option>
                            <option value="SKKagawad">SK Kagawad</option>
                            <option value="SKSecretary">Sk Secretary</option>
                        </select>
                        <div class="invalid-feedback">Please select a position.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" required>
                        <div class="invalid-feedback">Address is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number (11 digits)</label>
                        <input type="text" name="mobile" class="form-control" maxlength="11" required>
                        <div class="invalid-feedback">Enter a valid 11-digit mobile number.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control" required>
                        <div class="invalid-feedback">Birthdate is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback" id="emailError">Enter a valid and unique email.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <div class="password-rules">
                            Password must be at least 6 characters, contain 1 special character and 1 number.
                        </div>
                        <div class="invalid-feedback" id="passwordError">Password does not meet requirements.</div>
                    </div>
                    

                    <button type="submit" name="register" class="btn btn-primary w-100" style="background-color: var(--blue); border-color: var(--blue);">Register Officer</button>
                </form>
            </div>
        </div>
    </div>
    
    <br><br>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");

    const nameFields = ["first_name", "middle_name", "last_name"];
    nameFields.forEach(field => {
        const input = form[field];
        input.addEventListener("input", () => {
            const isValid = /^[A-Za-z]*$/.test(input.value);
            toggleValidation(input, isValid, "Only letters allowed.");
        });
    });

    const mobileInput = form["mobile"];
    mobileInput.addEventListener("input", () => {
        const isValid = /^\d{11}$/.test(mobileInput.value);
        toggleValidation(mobileInput, isValid, "Mobile number must be 11 digits.");
    });

    const emailInput = form["email"];
    emailInput.addEventListener("input", () => {
        const email = emailInput.value.trim();
        if (email === "") return;
        fetch(`check_email.php?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                const isValid = !data.exists;
                toggleValidation(emailInput, isValid, isValid ? "" : "This email is already registered.");
            });
    });

    const passwordInput = form["password"];
    passwordInput.addEventListener("input", () => {
        const pattern = /^(?=.*[!@#$%^&*])(?=.*\d).{6,}$/;
        const isValid = pattern.test(passwordInput.value);
        toggleValidation(passwordInput, isValid, "Min 6 characters with 1 special character and 1 number.");
    });

    form.addEventListener("submit", function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add("was-validated");
    });

    function toggleValidation(input, valid, message) {
        const feedback = input.parentElement.querySelector(".invalid-feedback");
        if (valid) {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            if (feedback) feedback.textContent = "";
        } else {
            input.classList.add("is-invalid");
            input.classList.remove("is-valid");
            if (feedback) feedback.textContent = message;
        }
    }
});
</script>
</body>
</html>












