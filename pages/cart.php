<?php
session_start();
include '../includes/db.php';

// 1. HANDLE ADD TO CART ACTION
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $user_id = 1; // Default for now
    $qty = 1;

    // Check if product already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);
    
    if ($check->rowCount() > 0) {
        $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
    } else {
        $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")->execute([$user_id, $product_id, $qty]);
    }
    header("Location: cart.php");
    exit();
}

// 2. FETCH CART ITEMS TO DISPLAY
$user_id = 1;
$stmt = $conn->prepare("SELECT p.name, p.price, p.image, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div style="padding: 20px; max-width: 800px; margin: auto;">
    <h2>Your Shopping Cart</h2>
    <a href="../index.php">Continue Shopping</a>
    <hr>

    <?php if ($items): ?>
        <table border="1" width="100%" cellpadding="10" style="border-collapse: collapse;">
            <tr style="background:#f4f4f4;">
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <?php $sub = $item['price'] * $item['quantity']; $total += $sub; ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($sub, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3>Grand Total: $<?php echo number_format($total, 2); ?></h3>
    <?php else: ?>
        <p>Cart is empty!</p>
    <?php endif; ?>
</div>
</body>
</html>