<?php
include '../connection/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: cms-services.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Optional: Delete file from server if needed
    $getFile = $conn->query("SELECT file FROM web_services WHERE id = $id");
    if ($getFile && $row = $getFile->fetch_assoc()) {
        if (!empty($row['file']) && file_exists("../dist/assets/images/website/cmsServe/" . $row['file'])) {
            unlink("../dist/assets/images/website/cmsServe/" . $row['file']);
        }
    }

    // Delete the service from the database
    $conn->query("DELETE FROM web_services WHERE id = $id");

    // Redirect to the services page
    header("Location: cms-services.php");
    exit();
}
?>