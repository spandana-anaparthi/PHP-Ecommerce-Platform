<?php
include 'includes/db.php';
session_start();

// Fetch products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Online Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-container">
        <h1>Welcome to Our Store</h1>
        <nav>
            <a href="pages/login.php">Login</a>
            <a href="pages/cart.php">Cart</a>
        </nav>
    </div>
</header>

<main class="main-container" style="display: flex; gap: 20px; padding: 20px; flex-wrap: wrap;">
    <?php if ($products): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card" style="border: 1px solid #ccc; padding: 15px; width: 200px; text-align: center;">
                <img src="images/<?php echo $product['image']; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                <h3><?php echo $product['name']; ?></h3>
                <p>$<?php echo number_format($product['price'], 2); ?></p>
                
                <form method="POST" action="pages/cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="add_to_cart" style="cursor:pointer; background:#2c3e50; color:white; border:none; padding:10px;">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</main>

</body>
</html>