<?php
include '../connection/config.php'; // adjust this path as needed

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: Delete the image file too
    $result = $conn->query("SELECT image FROM web_officials WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        $image_path = "../dist/assets/images/website/cmsOff/" . $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $conn->query("DELETE FROM web_officials WHERE id = $id");
}

header("Location: cms-officials.php");
exit();
?>
