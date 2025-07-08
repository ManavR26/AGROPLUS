<?php
require_once "includes/config.php";
session_start();

// Check admin access
if(!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "admin"){
    header("location: admin_login.php");
    exit;
}

$success = $error = "";

// Handle form submission for Government Schemes
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_scheme'])){
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    
    // Handle image upload
    $target_dir = "uploads/government_schemes/";
    if(!file_exists($target_dir)){
        mkdir($target_dir, 0777, true);
    }
    
    $image = "";
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
    
    $sql = "INSERT INTO government_schemes_content (title, description, image) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $image);
    
    if(mysqli_stmt_execute($stmt)){
        $success = "Scheme added successfully!";
    } else {
        $error = "Error adding scheme.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Government Schemes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="admin-container">
        <h1>Add Government Scheme</h1>
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" name="add_scheme" class="submit-btn">Add Scheme</button>
        </form>
    </div>
</body>
</html> 