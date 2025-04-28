<?php
session_start();
include "db_config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $message = "Email dan password harus diisi.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["nama"];
            header("Location: products.php");
            exit();
        } else {
            $message = "Email atau password salah.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Aluminium</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 5px; }
        input[type="email"], input[type="password"] {
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
        <h2>Login</h2>
        <?php if ($message) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form method="POST" action="login.php">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
