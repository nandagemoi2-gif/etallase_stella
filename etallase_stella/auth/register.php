<?php
session_start();
require_once '../config/kuis_db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (:nama, :email, :password, 'siswa')");
        $stmt->execute([':nama' => $nama, ':email' => $email, ':password' => $password]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = "Email sudah terdaftar atau terjadi kesalahan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Register Siswa - Etalase Digital Stella</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 25px 30px; border-radius: 8px; width: 320px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card h2 { margin: 0 0 22px; }
        input { width: 100%; padding: 12px; margin: 0 0 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input:focus { border-color: #cc0000; outline: none; box-shadow: 0 0 0 2px rgba(204,0,0,0.12); }
        button { width: 100%; padding: 12px; margin-top: 2px; background: #cc0000; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #b30000; }
        .card p { margin: 18px 0 0; text-align: center; }
    </style>
</head>
<body>
<div class="card">
    <h2>Registrasi Siswa</h2>
    <?php if($error): ?><p style="color:red; font-size:14px;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>
    <p style="font-size:12px;">Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body>
</html>
