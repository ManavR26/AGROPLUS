<?php
require_once "includes/config.php";
session_start();

// Strict admin check
if(!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "admin"){
    header("location: admin_login.php");
    exit;
}

// Fetch the scheme to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM government_schemes WHERE id = $id");
    $scheme = mysqli_fetch_assoc($result);
}

// Handle form submission for updating the scheme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = "UPDATE government_schemes SET name='$name', description='$description' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: admin_government_schemes.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Scheme</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Scheme</h1>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($scheme['name']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($scheme['description']); ?></textarea>
            <button type="submit">Update Scheme</button>
        </form>
    </div>
</body>
</html> 