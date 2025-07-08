<?php
require_once "config.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SESSION['user_type'] != 'customer') {
    echo json_encode(['success' => false, 'message' => 'Only customers can add to cart']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}

// Check if product exists and has stock
$sql = "SELECT stock FROM products WHERE id = ? AND stock > 0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Product not available']);
    exit;
}

// Delete any old cart items for this user
$delete_sql = "DELETE FROM cart_items WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $delete_sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

// Add new item to cart
$insert_sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)";
$stmt = mysqli_prepare($conn, $insert_sql);
mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $product_id);
mysqli_stmt_execute($stmt);

echo json_encode(['success' => true]); 