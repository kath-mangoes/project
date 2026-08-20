<?php
include '../connection/config.php'; // adjust this path as needed

if (isset($_POST['add_official'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $committee = $_POST['committee'];
    $email = $_POST['email'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "../dist/assets/images/website/cmsOff/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO web_officials (name, position, committee, email, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $position, $committee, $email, $image);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: cms-officials.php");
    exit();
}
?>
