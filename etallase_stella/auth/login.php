<?php
session_start();
require_once '../config/kuis_db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];
        header('Location: ../index.php');
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Login - Etalase Digital Stella</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f7f7f7; color: #292929; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: #fff; padding: 30px; border-radius: 8px; width: 100%; max-width: 380px; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08); border-top: 4px solid #E60000; }
        .logo { display: block; width: 100px; height: 100px; object-fit: contain; margin: 0 auto 14px; }
        h2 { text-align: center; margin: 0 0 22px; }
        input { width: 100%; padding: 11px 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        input:focus { border-color: #E60000; outline: 2px solid rgba(230, 0, 0, 0.15); }
        button { width: 100%; padding: 11px; margin-top: 8px; background: #E60000; color: #fff; border: none; border-radius: 4px; font-size: 15px; font-weight: 600; cursor: pointer; }
        button:hover { background: #c90000; }
        .register-link { color: #d00000; font-weight: 600; text-decoration: none; }
        .register-link:hover { text-decoration: underline; }
        @media (max-width: 420px) { .card { padding: 25px 20px; } }
    </style>
</head>
<body>
<div class="card">
    <img class="logo" src="../assets/telkom-logo.svg" alt="Logo Telkom">
    <h2>Login Etalase</h2>
    <?php if($error): ?><p style="color:red; font-size:14px;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p style="font-size:12px; text-align:center; margin:18px 0 0;">Siswa baru? <a class="register-link" href="register.php">Daftar Akun</a></p>
</div>
</body>
</html>