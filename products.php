<?php
session_start();
include "db_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Ambil data produk dari database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Toko Aluminium</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        header { background: #333; color: #fff; padding: 1rem; text-align: center; }
        nav a { color: #fff; margin: 0 1rem; text-decoration: none; }
        .container { max-width: 1200px; margin: 2rem auto; background: #fff; padding: 1rem; border-radius: 5px; }
        .product { border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; display: flex; }
        .product img { max-width: 150px; margin-right: 1rem; }
        .product-details { flex: 1; }
        .product-details h2 { margin: 0 0 0.5rem 0; }
        .product-details p { margin: 0.5rem 0; }
        .logout { float: right; color: #fff; }
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
            <a href="logout.php" class="logout">Logout (<?php echo htmlspecialchars($_SESSION["user_name"]); ?>)</a>
        </nav>
    </header>
    <div class="container">
        <h2>Daftar Produk</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="product">
                    <img src="images/<?php echo htmlspecialchars($row["gambar"]); ?>" alt="<?php echo htmlspecialchars($row["nama_produk"]); ?>">
                    <div class="product-details">
                        <h2><?php echo htmlspecialchars($row["nama_produk"]); ?></h2>
                        <p><?php echo htmlspecialchars($row["deskripsi"]); ?></p>
                        <p>Harga: Rp <?php echo number_format($row["harga"], 0, ',', '.'); ?></p>
                        <p>Stok: <?php echo htmlspecialchars($row["stok"]); ?></p>
                        <a href="product_detail.php?id=<?php echo $row["id"]; ?>" class="button">Lihat Detail</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Belum ada produk.</p>";
        }
        ?>
    </div>
</body>
</html>
