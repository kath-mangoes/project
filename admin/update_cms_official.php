<?php
include '../connection/config.php'; // adjust this path as needed

if (isset($_POST['update_official'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $committee = $_POST['committee'];
    $email = $_POST['email'];

    // Check if new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../dist/assets/images/website/cmsOff/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        $stmt = $conn->prepare("UPDATE web_officials SET name=?, position=?, committee=?, email=?, image=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $position, $committee, $email, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE web_officials SET name=?, position=?, committee=?, email=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $position, $committee, $email, $id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: cms-officials.php");
    exit();
}
?>
