<?php
session_start();
include "db_config.php";

// Untuk kesederhanaan, tidak ada autentikasi admin khusus
// Pastikan hanya user yang login bisa akses halaman ini
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Ambil data transaksi dengan join produk
$sql = "SELECT t.nomor_transaksi, t.tanggal_transaksi, p.nama_produk, t.jumlah, t.harga_total, p.stok, t.kontak_pembeli
        FROM transactions t
        JOIN products p ON t.product_id = p.id
        ORDER BY t.tanggal_transaksi DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Toko Aluminium</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        header { background: #333; color: #fff; padding: 1rem; text-align: center; }
        nav a { color: #fff; margin: 0 1rem; text-decoration: none; }
        .container { max-width: 1200px; margin: 2rem auto; background: #fff; padding: 1rem; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        th { background: #333; color: #fff; }
        .logout { float: right; color: #fff; }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <a href="products.php">Produk</a>
            <a href="logout.php" class="logout">Logout (<?php echo htmlspecialchars($_SESSION["user_name"]); ?>)</a>
        </nav>
    </header>
    <div class="container">
        <h2>Data Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Nomor Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Total</th>
                    <th>Stok Produk</th>
                    <th>Kontak Pembeli</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["nomor_transaksi"]); ?></td>
                            <td><?php echo htmlspecialchars($row["tanggal_transaksi"]); ?></td>
                            <td><?php echo htmlspecialchars($row["nama_produk"]); ?></td>
                            <td><?php echo htmlspecialchars($row["jumlah"]); ?></td>
                            <td>Rp <?php echo number_format($row["harga_total"], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($row["stok"]); ?></td>
                            <td><?php echo htmlspecialchars($row["kontak_pembeli"]); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="7">Belum ada transaksi.</td></tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
