<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <title>Barangay Clearance</title>
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
            <img src="https://barangay400.com/WEBSITE/image/conatactbg.png" alt="Barangay Image" class="banner-image">
            <div class="hero-text">
                <h1>Barangay Clearance and Services</h1>
                <p>Serving the community with dedication and integrity</p>
            </div>
        </div>
    </section>

    <section class="certification">
        <div class="certification-container">
            <div class="category-card">
                <div class="cert-card-container">
                    <?php
                    include 'connect.php';

                    $query = "SELECT * FROM web_services WHERE category = 'Barangay Clearances and Services'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <div class="cert-card">
                                <img src="../dist/assets/images/website/cmsServe/<?php echo htmlspecialchars($row['file']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="cert-img" style="padding-top: 15px;">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                                <p class="what">What is <?php echo htmlspecialchars($row['title']); ?>?</p>
                                <p class="desc"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

                                <p class="docrequire">Requirements</p>
                                <ul class="requirements">
                                    <?php
                                    $reqs = explode(',', $row['requirements']);
                                    foreach ($reqs as $req) {
                                        echo "<li>" . htmlspecialchars(trim($req)) . "</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php endwhile;
                    else: ?>
                        <p>No services available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <style>
        .certification-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 20px;
        }

        .category-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
        }

        .cert-card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cert-card {
            background: #fff;
            border: 3px solid #DA1212;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            text-align: left;
            transition: transform 0.3s ease-in-out;
            width: 300px;
        }

        .cert-card:hover {
            transform: scale(1.05);
            border-color: #041562;
        }

        .cert-card h3 {
            color: #041562;
            margin: 10px 0 0;
            text-align: center;
            font-size: 1.2rem;
        }

        .cert-img {
            width: 100px;
            height: auto;
            display: block;
            margin: 0 auto 10px auto;
        }

        .what, .docrequire {
            font-style: italic;
            font-weight: bold;
            margin-top: 10px;
            font-size: 1rem;
        }

        .desc {
            text-align: justify;
            padding: 10px 0;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .requirements {
            padding-left: 20px;
            list-style-type: disc;
            font-size: 0.95rem;
        }

        /* Responsive Styles */
        @media screen and (max-width: 600px) {
            .cert-card-container {
                flex-direction: column;
                align-items: center;
            }

            .cert-card {
                width: 90%;
                margin: 10px auto;
                padding: 20px;
            }

            .cert-card h3 {
                font-size: 1.1rem;
            }

            .desc, .requirements {
                font-size: 1rem;
            }

            .what, .docrequire {
                font-size: 1.05rem;
            }

            .cert-img {
                width: 80px;
            }
        }
    </style>


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
