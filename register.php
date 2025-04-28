<?php
session_start();
include "db_config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $telepon = $_POST["telepon"];
    $alamat = $_POST["alamat"];
    $email = $_POST["email"];
    $password = $_POST["password"]; // disimpan tanpa hash sesuai permintaan

    // Validasi sederhana
    if (empty($nama) || empty($telepon) || empty($alamat) || empty($email) || empty($password)) {
        $message = "Semua field harus diisi.";
    } else {
        // Cek apakah email sudah terdaftar
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Email sudah terdaftar.";
        } else {
            // Insert data user baru
            $sql = "INSERT INTO users (nama, telepon, alamat, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nama, $telepon, $alamat, $email, $password);
            if ($stmt->execute()) {
                $message = "Registrasi berhasil. Silakan login.";
            } else {
                $message = "Terjadi kesalahan saat registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Toko Aluminium</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 5px; }
        input[type="text"], input[type="email"], input[type="password"] {
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
        <h2>Registrasi</h2>
        <?php if ($message) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form method="POST" action="register.php">
            <label>Nama</label>
            <input type="text" name="nama" required>
            <label>Nomor Telepon</label>
            <input type="text" name="telepon" required>
            <label>Alamat</label>
            <input type="text" name="alamat" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
