<?php
session_start();
include "db_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET["id"]);

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?php echo htmlspecialchars($product["nama_produk"]); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        header { background: #333; color: #fff; padding: 1rem; text-align: center; }
        nav a { color: #fff; margin: 0 1rem; text-decoration: none; }
        .container { max-width: 800px; margin: 2rem auto; background: #fff; padding: 1rem; border-radius: 5px; }
        .product-detail { display: flex; }
        .product-detail img { max-width: 300px; margin-right: 1rem; }
        .product-info { flex: 1; }
        a.button {
            background: #333; color: #fff; padding: 0.5rem 1rem; text-decoration: none; border-radius: 3px;
        }
        a.button:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Toko Aluminium</h1>
        <nav>
            <a href="products.php">Produk</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="product-detail">
            <img src="images/<?php echo htmlspecialchars($product["gambar"]); ?>" alt="<?php echo htmlspecialchars($product["nama_produk"]); ?>">
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product["nama_produk"]); ?></h2>
                <p><?php echo htmlspecialchars($product["deskripsi"]); ?></p>
                <p>Harga: Rp <?php echo number_format($product["harga"], 0, ',', '.'); ?></p>
                <p>Stok: <?php echo htmlspecialchars($product["stok"]); ?></p>
                <a href="payment.php?id=<?php echo $product["id"]; ?>" class="button">Bayar via Transfer</a>
            </div>
        </div>
    </div>
</body>
</html>
