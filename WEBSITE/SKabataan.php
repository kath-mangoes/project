<?php
include 'connect.php';

// Fetch all SK Officials
$query = "SELECT * FROM web_officials WHERE committee = 'Sangguniang Kabataan'";
$result = mysqli_query($conn, $query);

$sk_chairman = null;
$sk_secretary = null;
$sk_kagawads = [];

while ($row = mysqli_fetch_assoc($result)) {
    $position = strtolower($row['position']);

    if (strpos($position, 'chairman') !== false) {
        $sk_chairman = $row;
    } elseif (strpos($position, 'secretary') !== false) {
        $sk_secretary = $row;
    } elseif (strpos($position, 'kagawad') !== false) {
        $sk_kagawads[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <title>Barangay Sangguniang Kabataan</title>
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
                <h1>Sangguniang Kabataan</h1>
                <p>Serving the community with dedication and integrity</p>
            </div>
        </div>
    </section>
    
    <section class="barangay-council">
        <div class="council-container">
            <!-- SK Chairman -->
            <?php if ($sk_chairman): ?>
                <div class="council-row" style="justify-content: center;">
                    <div class="council-member">
                        <img src="../dist/assets/images/website/cmsOff/<?= $sk_chairman['image'] ?>" alt="<?= $sk_chairman['name'] ?>">
                        <h3><?= $sk_chairman['name'] ?></h3>
                        <p><?= $sk_chairman['position'] ?></p>
                        <p style="color:#141E30; font-size: small;"><?= $sk_chairman['email'] ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SK Secretary -->
            <?php if ($sk_secretary): ?>
                <div class="council-row" style="justify-content: center;">
                    <div class="council-member">
                        <img src="../dist/assets/images/website/cmsOff/<?= $sk_secretary['image'] ?>" alt="<?= $sk_secretary['name'] ?>">
                        <h3><?= $sk_secretary['name'] ?></h3>
                        <p><?= $sk_secretary['position'] ?></p>
                        <p style="color:#141E30; font-size: small;"><?= $sk_secretary['email'] ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SK Kagawads -->
            <?php
            $count = 0;
            foreach ($sk_kagawads as $kagawad):
                if ($count % 3 == 0) echo '<div class="council-row">';
            ?>
                <div class="council-member">
                    <img src="../dist/assets/images/website/cmsOff/<?= $kagawad['image'] ?>" alt="<?= $kagawad['name'] ?>">
                    <h3><?= $kagawad['name'] ?></h3>
                    <p><?= $kagawad['position'] ?></p>
                    <p style="color:#141E30; font-size: small;"><?= $kagawad['email'] ?></p>
                </div>
            <?php
                $count++;
                if ($count % 3 == 0) echo '</div>';
            endforeach;
            if ($count % 3 != 0) echo '</div>'; // Close last row if not full
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
