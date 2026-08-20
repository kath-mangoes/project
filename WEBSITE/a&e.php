<?php 
include 'connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <title>Barangay Events</title>
    <link rel="stylesheet" href="../WEBSITE/css/footer.css">
    <link rel="stylesheet" href="../WEBSITE/css/header.css">
    <link rel="stylesheet" href="../WEBSITE/css/event_table.css">
    <script src="/SYSTEM/WEBSITE/JS/home.js"></script>
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

    <!--START EVENTS -->
    <br><br><br>
    <section class="announcement">
        <div class="search-filter-container">
            <input type="text" id="searchBar" placeholder="Search Events..." onkeyup="searchAnnouncements()">
        </div>

        <div class="search-filter-container">
            <select id="filterKeyword" onchange="filterAnnouncements()">
                <option value="">All Events</option>
                <option value="Senior Citizen">Senior Citizen</option>
                <option value="Health">Health</option>
                <option value="Youth">Youth</option>
                <option value="Community">Community</option>
                <option value="Emergency">Emergency</option>
            </select>
        </div>

        <!-- Events Section -->
        <h2 class="section-title">Events</h2>
        <div id="eventGrid" class="announcement-grid">


            <div class="table-container">
                <table class="event-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Image</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 15%;">Date Created</th>
                            <th style="width: 40%;">Description</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM tbl_event ORDER BY dateCreated DESC";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td><img src="../dist/assets/images/uploads/events/' . $row['image'] . '" alt="' . $row['title'] . '" class="event-img"></td>';
                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                            echo '<td>' . date("F d, Y", strtotime($row['dateCreated'])) . '</td>';
                            echo '<td>' . htmlspecialchars(substr($row['description'], 0, 100)) . '...</td>';
                            echo '<td><a href="viewEvents.php?id=' . $row['event_id'] . '" class="read-more">Read More</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </div>
    </section>
    <br><br><br>

<style>
    .search-filter-container {
        margin-bottom: 5px;
        padding: 10px 50px;
    }

    #searchBar, #filterKeyword {
        padding: 10px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        margin-top: 30px;
        margin-bottom: 20px;
        padding-left: 50px;
        color: #333;
    }

    .announcement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px 50px;
    }

    .card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease-in-out, background-color 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        background-color: #f1f1f1;
    }

    .card img.thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        padding: 15px;
    }

    .card-content h3 {
        margin: 0 0 10px;
        font-size: 18px;
    }

    .card-content .author {
        font-size: 14px;
        color: #777;
        margin-bottom: 10px;
    }

    .card-content .description {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .card-content .read-more {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }
</style>

<script>
    function searchAnnouncements() {
        const input = document.getElementById('searchBar').value.toLowerCase();
        const announcementCards = document.querySelectorAll('.announcement-card');
        const eventCards = document.querySelectorAll('.event-card');

        eventCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = title.includes(input) ? '' : 'none';
        });
    }

    function filterAnnouncements() {
        const filter = document.getElementById('filterKeyword').value.toLowerCase();
        const announcementCards = document.querySelectorAll('.announcement-card');
        const eventCards = document.querySelectorAll('.event-card');

        eventCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = filter === '' || title.includes(filter) ? '' : 'none';
        });
    }
</script>
    <!-- END EVENTS -->




    <!-- ------------- footer ------------- -->
    <footer>
        <div class="container-two">

            <div class="footer-cont">
                <img src="https://barangay400.com/WEBSITE/image/phplogo.png" />
            </div>

            <div class="footer-cont">
                <h3>Republic of The Philippines</h3>
                <p>All content is in the public domain unless otherwise stated.</p>
                
                <ul class="govlinks">
                    
                    
                    

                </ul>

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
