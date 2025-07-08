<?php
require_once "includes/config.php";
session_start();

// Strict admin check
if(!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "admin"){
    header("location: admin_login.php");
    exit;
}

// Handle form submission for new government scheme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Insert new scheme into the database
    $query = "INSERT INTO government_schemes (name, description) VALUES ('$name', '$description')";
    if (mysqli_query($conn, $query)) {
        $message = "New scheme created successfully.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch government schemes from the database
$schemes = mysqli_query($conn, "SELECT * FROM government_schemes");

// Check for query errors
if (!$schemes) {
    die("Database query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Government Schemes - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
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

        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            margin-right: 5px;
        }

        .edit-btn {
            background: #2196F3;
        }

        .delete-btn {
            background: #f44336;
        }

        .show-btn {
            background: #4CAF50;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            background-color: #e7f3fe;
            color: #31708f;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Manage Government Schemes</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Form to add new government scheme -->
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Scheme Name" required>
            <textarea name="description" placeholder="Scheme Description" required></textarea>
            <button type="submit">Add Scheme</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($scheme = mysqli_fetch_assoc($schemes)): ?>
                    <tr>
                        <td><?php echo $scheme['id']; ?></td>
                        <td><?php echo htmlspecialchars($scheme['name']); ?></td>
                        <td><?php echo htmlspecialchars($scheme['description']); ?></td>
                        <td>
                            <a href="edit_scheme.php?id=<?php echo $scheme['id']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="delete_scheme.php?id=<?php echo $scheme['id']; ?>" class="action-btn delete-btn">Delete</a>
                            <a href="show_scheme.php?id=<?php echo $scheme['id']; ?>" class="action-btn show-btn">Show</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 