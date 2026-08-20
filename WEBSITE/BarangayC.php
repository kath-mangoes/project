<?php
include 'connect.php';

$query = "SELECT * FROM web_officials WHERE committee = 'Barangay Committees'";
$result = mysqli_query($conn, $query);

// Initialize arrays to hold multiple entries
$lupons = [];
$tanods = [];

while ($row = mysqli_fetch_assoc($result)) {
    $position = strtolower($row['position']);
    if (strpos($position, 'lupon') !== false) {
        $lupons[] = $row;
    } elseif (strpos($position, 'tanod') !== false) {
        $tanods[] = $row;
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <title>Barangay Committees</title>
    <link rel="stylesheet" href="../WEBSITE/css/header.css">
    <link rel="stylesheet" href="../WEBSITE/css/footer.css">
    <link rel="stylesheet" href="../WEBSITE/css/ourB.css">
    
</head>
<body>
    <header>
        <p>Barangay 400 Sampaloc Manila</p>
        <ul class="nav-links" style="padding-right: 50px;">
            <li><a href="https://barangay400.com/">Home</a></li>
            <li>
                <a>Our Barangay</a>
                <ul class="dropdown">
                    <li><a href="ourBarangay.php">Barangay Councils</a></li>
                    <li><a href="BarangayC.php">Barangay Staffs</a></li>
                    <li><a href="SKabataan.php">Sangguniang Kabataan</a></li>
                </ul>
            </li>
            <li>
                <a>Services</a>
                <ul class="dropdown">
                    <li><a href="certification.php">Barangay Certifications</a></li>
                    <li><a href="complaints.php">Barangay Complaints</a></li>
                    <li><a href="clearance&services.php">Barangay Clearance & Services</a></li>
                </ul>
            </li>
            <li>
                <a href="a&e.php">Events</a>
            </li>
            <li><a href="https://barangay400.com/login.php">Login</a></li>
        </ul>
    </header>

    <section class="hero">
        <div class="hero-banner">
            <img src="https://barangay400.com/WEBSITE/image/b4.jpg" alt="Barangay Image" class="banner-image">
            <div class="hero-text">
                <h1>Barangay Staffs</h1>
                <p>Serving the community with dedication and integrity</p>
            </div>
        </div>
    </section>

    <section class="barangay-council">
        <div class="council-container">

            <!-- LUPON MEMBERS -->
            <h2 style="text-align:center; margin-bottom: 20px;">Lupon Members</h2>
            <?php
            $count = 0;
            foreach ($lupons as $lupon):
                if ($count % 3 == 0) echo '<div class="council-row">';
            ?>
                <div class="council-member">
                    <img src="../dist/assets/images/website/cmsOff/<?= $lupon['image'] ?>" alt="Lupon">
                    <h3><?= $lupon['name'] ?></h3>
                    <p><?= $lupon['position'] ?></p>
                    <p style='color: #141E30; font-size: small;'><?= $lupon['email'] ?></p>
                </div>
            <?php
                $count++;
                if ($count % 3 == 0) echo '</div>';
            endforeach;
            if ($count % 3 != 0) echo '</div>';
            ?>

            <!-- TANOD MEMBERS -->
            <h2 style="text-align:center; margin: 40px 0 20px;">Tanod Members</h2>
            <?php
            $count = 0;
            foreach ($tanods as $tanod):
                if ($count % 3 == 0) echo '<div class="council-row">';
            ?>
                <div class="council-member">
                    <img src="../dist/assets/images/website/cmsOff/<?= $tanod['image'] ?>" alt="Tanod">
                    <h3><?= $tanod['name'] ?></h3>
                    <p><?= $tanod['position'] ?></p>
                    <p style='color: #141E30; font-size: small;'><?= $tanod['email'] ?></p>
                </div>
            <?php
                $count++;
                if ($count % 3 == 0) echo '</div>';
            endforeach;
            if ($count % 3 != 0) echo '</div>';
            ?>
        </div>
    </section>






    <!-- ------------- footer ------------- -->
    <footer>
        <div class="container-two">

            <div class="footer-cont">
                <img src="https://barangay400.com/WEBSITE/image/phplogo.png" />
            </div>

            <div class="footer-cont">
                <h3>Republic of The Philippines</h3>
                <p>All content is in the public domain unless otherwise stated.</p>
            </div>

            <div class="footer-cont">
                <h3>About Barangay 400</h3>
                <p>Learn more about the Barangay 400 Sampaloc Manila, its structure, how they work and the people behind it.</p>

            </div>

            <div class="footer-cont">
                <h3>Government Links</h3>
                <ul class="govlinks">
                    <li><a href="https://op-proper.gov.ph/">Office of the President</a></li>
                    <li><a href="https://main.ovp.gov.ph/">Office of the Vice President</a></li>
                    <li><a href="https://web.senate.gov.ph/">Senate of the Philippines</a></li>
                    <li><a href="https://www.congress.gov.ph/">House of the Representative</a></li>
                    <li><a href="https://sc.judiciary.gov.ph/">Supreme Court</a></li>
                    <li><a href="https://barangay400.com/brgy_officer.php">Barangay Officer Register</a></li>
                    <li><a href="https://barangay400.com/admin_register.php">Admin Register</a></li>
                </ul>
            </div>
        </div>

        <div class="bottom-bar">
            <p>
                &copy; 2024 <a href="">The Official Website of Barangay 400, Sampaloc Manila City Philippines.</a> All Rights Reserved. 
            </p>
        </div>
    </footer>
</body>
</html>
