<?php
include '../connection/config.php';
session_start();

// 🔧 Add this function to handle file uploads
function uploadFile($fileInputName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES[$fileInputName]['tmp_name'];
        $fileName = basename($_FILES[$fileInputName]['name']);
        $targetDir = "../dist/assets/images/website/cmsServe/";
        $targetFile = $targetDir . uniqid() . '_' . $fileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($fileTmp, $targetFile)) {
            return basename($targetFile);
        }
    }
    return null;
}

// UPDATE Service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $category = $_POST['category'];

    $file = uploadFile('file'); // ✅ This line now works

    if ($file) {
        $stmt = $conn->prepare("UPDATE web_services SET title=?, description=?, requirements=?, category=?, file=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $description, $requirements, $category, $file, $id);
    } else {
        $stmt = $conn->prepare("UPDATE web_services SET title=?, description=?, requirements=?, category=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $description, $requirements, $category, $id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: cms-services.php");
    exit();
}
?>
