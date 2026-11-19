<?php
require_once 'config/kuis_db.php';
require_once 'middleware/auth_check.php';

check_login();

// Ambil semua karya beserta nama pembuat
$stmt = $pdo->query("SELECT projects.*, users.nama AS pembuat 
                     FROM projects 
                     JOIN users ON projects.user_id = users.id 
                     ORDER BY projects.created_at DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Etalase Digital Stella</title>
    <style>
        * { box-sizing: border-box; }
        body { min-height: 100vh; display: flex; flex-direction: column; font-family: Arial, sans-serif; background: #f7f7f7; color: #292929; margin: 0; }
        .header { display: flex; justify-content: space-between; align-items: center; gap: 20px; background: #fff; border-bottom: 4px solid #E60000; padding: 16px max(24px, calc((100% - 1160px) / 2)); }
        .header-brand { display: flex; align-items: center; gap: 10px; }
        .header-logo { width: 40px; height: 40px; object-fit: contain; }
        .header h2 { color: #c90000; margin: 0; font-size: 22px; }
        .header-actions { display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 8px; }
        .header-actions .greeting { margin-right: 6px; }
        .content { width: 100%; max-width: 1160px; flex: 1; margin: 0 auto; padding: 30px 24px; }
        .section-heading { border-left: 4px solid #E60000; padding-left: 14px; margin-bottom: 20px; }
        .section-heading h3 { margin: 0 0 4px; font-size: 20px; }
        .section-heading p { color: #666; margin: 0; font-size: 14px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .sample-section { margin-top: 32px; }
        .sample-section h3 { margin: 0 0 14px; font-size: 17px; }
        .sample-card { background: #fff; border: 1px solid #e5e5e5; border-top: 3px solid #E60000; border-radius: 6px; padding: 18px; }
        .sample-card h4 { margin: 0 0 8px; font-size: 16px; }
        .sample-card p { margin: 0; color: #666; font-size: 14px; }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-body { padding: 15px; }
        .btn { text-decoration: none; padding: 9px 13px; border-radius: 4px; color: white; display: inline-block; font-size: 13px; font-weight: 600; }
        .btn-primary { background: #E60000; } .btn-primary:hover { background: #c90000; }
        .btn-warning { background: #ffc107; color: #292929; } .btn-danger { background: #bd161b; }
        .project-link { color: #c90000; font-weight: 600; text-decoration: none; }
        .project-link:hover { text-decoration: underline; }
        .empty-state { background: #fff; border: 1px solid #e5e5e5; border-radius: 6px; padding: 24px; color: #666; }
        .footer { background: #fff; border-top: 1px solid #e6e6e6; padding: 16px 20px; color: #666; text-align: center; font-size: 13px; }
        @media (max-width: 700px) { .header { align-items: flex-start; flex-direction: column; padding: 18px 20px; } .header-actions { justify-content: flex-start; } .content { padding: 24px 20px; } }
    </style>
</head>
<body>
<div class="header">
    <div class="header-brand">
        <img class="header-logo" src="assets/telkom-logo.svg" alt="Logo Telkom">
        <h2>Etalase Digital Stella</h2>
    </div>
    <div class="header-actions">
        <span class="greeting">Halo, <b><?= htmlspecialchars($_SESSION['nama']) ?></b> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
        <a href="project_tambah.php" class="btn btn-primary">+ Tambah Karya</a>
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
            <a href="admin/users.php" class="btn btn-warning">Kelola Users</a>
        <?php endif; ?>
        <a href="auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<main class="content">
<div class="section-heading">
    <h3>Daftar Karya</h3>
    <p>Jelajahi karya dan project terbaru dari komunitas.</p>
</div>

<?php if (empty($projects)): ?>
    <p class="empty-state">Belum ada karya yang ditampilkan.</p>
<?php else: ?>
<div class="grid">
    <?php foreach($projects as $p): ?>
        <div class="card">
            <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="Karya">
            <div class="card-body">
                <h3><?= htmlspecialchars($p['nama_project']) ?></h3>
                <p><small>Oleh: <b><?= htmlspecialchars($p['pembuat']) ?></b></small></p>
                <p><?= htmlspecialchars($p['deskripsi']) ?></p>
                <p><a class="project-link" href="<?= htmlspecialchars($p['link_project']) ?>" target="_blank" rel="noopener noreferrer">Lihat Project Tautan &rarr;</a></p>
                
                <!-- Hak Akses Edit/Hapus: Owner / Admin / Super Admin -->
                <?php if($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $p['user_id']): ?>
                    <a href="project_edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="project_hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger">Hapus</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<section class="sample-section" aria-label="Contoh kartu karya">
    <h3>Contoh Kartu</h3>
    <div class="grid">
        <article class="sample-card">
            <h4>Judul Karya Contoh</h4>
            <p>Deskripsi Singkat</p>
        </article>
        <article class="sample-card">
            <h4>Judul Karya Contoh</h4>
            <p>Deskripsi Singkat</p>
        </article>
    </div>
</section>
</main>
<footer class="footer">&copy; 2026 Etalase Digital Stella</footer>
</body>
</html>
