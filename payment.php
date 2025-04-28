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

$product_id = intval($_GET["id"]);
$user_id = $_SESSION["user_id"];

// Ambil data produk
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jumlah = intval($_POST["jumlah"]);
    $nomor_transaksi = $_POST["nomor_transaksi"];
    $tanggal_transaksi = $_POST["tanggal_transaksi"];
    $kontak_pembeli = $_POST["kontak_pembeli"];

    if ($jumlah <= 0 || empty($nomor_transaksi) || empty($tanggal_transaksi) || empty($kontak_pembeli)) {
        $message = "Semua field harus diisi dengan benar.";
    } elseif ($jumlah > $product["stok"]) {
        $message = "Jumlah melebihi stok yang tersedia.";
    } else {
        $harga_total = $jumlah * $product["harga"];

        // Insert transaksi
        $sql = "INSERT INTO transactions (nomor_transaksi, tanggal_transaksi, product_id, jumlah, harga_total, kontak_pembeli) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiis", $nomor_transaksi, $tanggal_transaksi, $product_id, $jumlah, $harga_total, $kontak_pembeli);

        if ($stmt->execute()) {
            // Update stok produk
            $new_stok = $product["stok"] - $jumlah;
            $sql_update = "UPDATE products SET stok = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $new_stok, $product_id);
            $stmt_update->execute();

            $message = "Transaksi berhasil dicatat. Silakan lakukan pembayaran via transfer bank.";
        } else {
            $message = "Terjadi kesalahan saat mencatat transaksi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - <?php echo htmlspecialchars($product["nama_produk"]); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 500px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 5px; }
        input[type="number"], input[type="text"], input[type="date"] {
            width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 3px;
        }
        button { background: #333; color: #fff; padding: 0.7rem; border: none; width: 100%; border-radius: 3px; cursor: pointer; }
        button:hover { background: #555; }
        .message { color: red; margin-bottom: 1rem; }
        a { text-decoration: none; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pembayaran Produk: <?php echo htmlspecialchars($product["nama_produk"]); ?></h2>
        <?php if ($message) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form method="POST" action="payment.php?id=<?php echo $product_id; ?>">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1" max="<?php echo $product["stok"]; ?>" required>
            <label>Nomor Transaksi</label>
            <input type="text" name="nomor_transaksi" required>
            <label>Tanggal Transaksi</label>
            <input type="date" name="tanggal_transaksi" required>
            <label>Kontak Pembeli</label>
            <input type="text" name="kontak_pembeli" required>
            <button type="submit">Bayar</button>
        </form>
        <p>Silakan lakukan pembayaran via transfer bank ke rekening kami.</p>
        <p>Setelah melakukan transfer, simpan bukti pembayaran dan nomor transaksi.</p>
        <p><a href="products.php">Kembali ke Produk</a></p>
    </div>
</body>
</html>
