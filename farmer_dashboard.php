<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in and is a farmer
if(!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "farmer"){
    header("location: login.php");
    exit;
}

$success_message = $error_message = "";

// Handle product upload
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["product_name"]);
    $price = floatval($_POST["price"]);
    $category = trim($_POST["category"]);
    $description = trim($_POST["description"]);
    
    // Handle image upload
    $image_path = "";
    if(isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
        $filename = $_FILES["product_image"]["name"];
        $filetype = $_FILES["product_image"]["type"];
        $filesize = $_FILES["product_image"]["size"];
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!array_key_exists($ext, $allowed)) {
            $error_message = "Error: Please select a valid image format (jpg, jpeg, png).";
        } else {
            if(!file_exists("assets/images/products")) {
                mkdir("assets/images/products", 0777, true);
            }
            
            $new_filename = uniqid() . "." . $ext;
            $image_path = "assets/images/products/" . $new_filename;
            
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $image_path);
        }
    }
    
    if(empty($error_message)) {
        $sql = "INSERT INTO products (farmer_id, name, price, category, description, image, stock) 
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "isdsss", 
                $_SESSION["user_id"],
                $name,
                $price,
                $category,
                $description,
                $image_path
            );
            
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error adding product. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Get farmer's products
$products = [];
$sql = "SELECT * FROM products WHERE farmer_id = ? ORDER BY id DESC";
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .upload-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .upload-header {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .upload-form {
            display: grid;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .image-upload {
            border: 2px dashed #4CAF50;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .image-upload label {
            cursor: pointer;
            color: #4CAF50;
        }
        .image-upload input[type="file"] {
            display: none;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .submit-btn {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background: #45a049;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4CAF50;
            padding: 1rem 2rem;
            color: white;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-brand a {
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="index.php">AgroPlus</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="farmer_dashboard.php">Add Product</a></li>
            <li><a href="view_products.php">View My Products</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></li>
        </ul>
    </nav>

    <div class="upload-container">
        <h1 class="upload-header">Enter the Product Information here..!!</h1>
        
        <?php if(!empty($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(!empty($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form class="upload-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="image-upload">
                <label for="product_image">
                    <div id="upload-text">Choose File</div>
                    <img id="preview" class="preview-image">
                </label>
                <input type="file" id="product_image" name="product_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">- Category -</option>
                    <option value="Vegetables">Vegetables</option>
                    <option value="Fruits">Fruits</option>
                    <option value="Grains">Grains</option>
                    <option value="Dairy">Dairy</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="product_name" required placeholder="Enter product name">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" required placeholder="Enter product description"></textarea>
            </div>

            <div class="form-group">
                <label>Price (₹)</label>
                <input type="number" name="price" step="0.01" required placeholder="Enter price">
            </div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('product_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            const uploadText = document.getElementById('upload-text');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    uploadText.textContent = file.name;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html> 