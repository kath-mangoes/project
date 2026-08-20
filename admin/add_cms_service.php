<?php
include '../connection/config.php';
session_start();

// Function to handle file upload
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

// CREATE Service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $category = $_POST['category'];
    $file = uploadFile('file');

    $stmt = $conn->prepare("INSERT INTO web_services (title, description, requirements, category, file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $requirements, $category, $file);

    if ($stmt->execute()) {
        echo "Inserted successfully";
        header("Location: cms-services.php"); // Uncomment after testing
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
