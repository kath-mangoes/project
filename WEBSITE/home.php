<?php
include 'connect.php';

$announcement = "No announcements available.";
$date_display = "--";

// Fetch the latest event based on dateCreated
$query = "SELECT * FROM tbl_event ORDER BY dateCreated DESC LIMIT 1";

$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $title = $row['title'];
    $location = $row['location'] ?? 'Barangay Hall'; // fallback location if null
    $created = strtotime($row['dateCreated']);

    $formattedDate = date("F j, Y", $created);
    $formattedTime = date("g:i A", $created); // Extract time from dateCreated
    $date_display = strtoupper(date("d M", $created));

    $announcement = "$title on $formattedDate @ $formattedTime at $location.";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <title>Barangay 400, Sampaloc Manila</title>

    <link rel="stylesheet" href="../WEBSITE/css/home.css">
    <link rel="stylesheet" href="../WEBSITE/css/footer.css">
    <link rel="stylesheet" href="../WEBSITE/css/header.css">
    <link rel="stylesheet" href="../WEBSITE/css/event_table.css">
    <script src="../WEBSITE/JS/home.js"></script>
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
                    <li><a href="BarangayC.php">Barangay Committees</a></li>
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
        <div class="slideshow-container">
            <div class="slide fade">
                <img src="https://barangay400.com/WEBSITE/image/b1.png" alt="Barangay Image 1">
            </div>
            <div class="slide fade">
                <img src="https://barangay400.com/WEBSITE/image/b2.png" alt="Barangay Image 2">
            </div>
        </div>

            <!-- Office Hours Card Overlay -->
        <div class="office-hours-card">
            <h3>Office Hours</h3>
            <p><strong>Monday to Friday:</strong> 8:00 AM - 5:00 PM</p>
            <p><strong>Address:</strong> Barangay 400, Zone 41, Sampaloc, Manila</p>
        </div>
    </section>

    <style>
        .announcement-bar {
    display: flex;
    align-items: center;
    background-color: #c00; /* red background */
    color: white;
    font-family: Arial, sans-serif;
    padding: 10px 0;
    position: relative;
}

.announcement-label {
    background-color: #0047ab; /* blue color */
    padding: 15px 20px;
    clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%);
    font-weight: bold;
    white-space: nowrap;
}

.announcement-content {
    display: flex;
    align-items: center;
    width: 100%;
    position: relative;
    padding-left: 10px;
}

.announcement-content marquee {
    flex: 1;
    font-size: 16px;
    white-space: nowrap;
}

.announcement-date {
    background-color: #0047ab;
    padding: 5px 10px;
    margin-left: 10px;
    font-weight: bold;
    font-size: 12px;
    border-radius: 3px;
}

.announcement-nav button {
    background-color: #0047ab;
    border: none;
    color: white;
    margin-left: 5px;
    padding: 5px 10px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 3px;
}

    </style>

    <!-- Announcement Bar -->
    <section class="announcement-bar">
        <div class="announcement-label">Announcements</div>
        <div class="announcement-content">
            <marquee behavior="scroll" direction="left" scrollamount="6">
                <?php echo $announcement; ?>
            </marquee>
        </div>
    </section>


    <style>

    .about-section {
      background-color: #ffffff;
      padding: 60px 20px;
      max-width: 100%;
      margin: 50px 50px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .about-section h2 {
      font-size: 2rem;
      font-weight: bold;
      color: #333;
      text-align: left;
    }

    .about-section p {
      font-size: 1rem;
      line-height: 1.6;
      color: #555;
      margin-bottom: 15px;
      text-align: justify;
      margin: 50px auto;
    }

    @media (max-width: 600px) {
      .about-section {
        padding: 40px 15px;
      }

      .about-section h2 {
        font-size: 1.5rem;
      }

      .about-section p {
        font-size: 0.95rem;
      }
    }
  </style>

<section class="about-section">
    <h2>About Barangay 400, Sampaloc Manila</h2>
    <p>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Barangay 400 is one of the many barangays located in the heart of Sampaloc, Manila. Known for its active community and dedication to public service,
      Barangay 400 serves as a vital part of the district, supporting local programs focused on health, safety, cleanliness, and community welfare. The barangay works closely with residents and local institutions to ensure progress and safety within its jurisdiction.
      With accessible public services and active civic engagement, Barangay 400 continues to uphold its commitment to improving the quality of life for its constituents.
    </p>
</section>






    <section id="weather">
        <h2>Barangay 400 Zone 41, Sampaloc, Manila Weather Center</h2>
        <p style="padding-bottom: 50px;" >Stay updated with the latest weather forecast in Barangay 400 Zone 41</p>

        <div class="weather-container">
             <!-- Windy Live Weather Map (Right Side) -->
            <div class="weather-map">
                <iframe src="https://embed.windy.com/embed2.html?lat=14.6137&lon=121.0198&zoom=10&level=surface&overlay=rain&menu=&message=&marker=&calendar=now&pressure=&type=map&location=coordinates&detail=&detailLat=14.6137&detailLon=121.0198&metricWind=km/h&metricTemp=%C2%B0C&radarRange=-1" frameborder="0"></iframe>
            </div>

            <!-- Weather Card (Left Side) -->
            <div class="weather-card" id="weather-card">
                <h3>Sampaloc, Manila</h3>
                <p class="temperature" id="temp">Loading...</p>
                <p class="condition" id="condition">Loading...</p>
                <p id="pressure">Pressure: --</p>
                <p id="humidity">Humidity: --</p>
                <p id="wind">Wind: --</p>
                <div class="forecast">
                    <div class="forecast-item"><span>Morning</span> <span id="morning">--</span></div>
                    <div class="forecast-item"><span>Day</span> <span id="day">--</span></div>
                    <div class="forecast-item"><span>Evening</span> <span id="evening">--</span></div>
                    <div class="forecast-item"><span>Night</span> <span id="night">--</span></div>
                </div>
            </div>

           
        </div>
    </section>




    <!--START EVENTS -->
    <br>
    <section class="announcement">
        
        <!-- Events Section -->
        <h2 class="section-title">Events</h2>
        <div id="eventGrid" class="announcement-grid">


            <div class="table-container">
                <table class="event-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Image</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 40%;">Description</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM tbl_event ORDER BY dateCreated DESC";
                        $result = mysqli_query($con, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td><img src="../dist/assets/images/uploads/events/' . $row['image'] . '" alt="' . $row['title'] . '" class="event-img"></td>';
                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
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
            text-align: center;
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

    <br><br><br>
    <!-- MAP SECTION -->
    <style>
        #map {
            height: 500px;
            width: 100%;
        }

        #barangay-map{
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #barangay-map h2 {
            color: #041562;
            font-size: 30px;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-bottom: 20px;
        }

        #barangay-map p{
            font-size: 20px;
            margin-bottom: 30px;
        }
    </style>

    <section id="barangay-map">
        <h2>Barangay 400 Zone 41, Sampaloc, Manila MAP</h2>
        <p style="padding-bottom: 20px;">
            Charting Progress, Connecting Community: Navigating the Barangay 400 Master Action Plan (MAP)
        </p>

        <div id="map">
            <iframe 
                src="https://www.google.com/maps?q=Barangay%20400%20Sampaloc%20Manila&output=embed"
                width="100%" 
                height="500" 
                style="border:0; border-radius: 20px;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
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
