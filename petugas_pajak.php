<?php
include 'config.php';

// 1. PROSES CREATE (TAMBAH DATA)
if (isset($_POST['simpan'])) {
    $nama    = $_POST['nama_petugas'];
    $nip     = !empty($_POST['nip']) ? "'".$_POST['nip']."'" : "NULL";
    $jabatan = $_POST['jabatan'];
    $email   = $_POST['email'];
    $telp    = $_POST['no_telepon'];

    mysqli_query($conn, "INSERT INTO petugas_pajak (nama_petugas, nip, jabatan, email, no_telepon) 
                         VALUES ('$nama', $nip, '$jabatan', '$email', '$telp')");
    header("Location: petugas_pajak.php");
    exit;
}

// 2. PROSES UPDATE (UBAH DATA)
if (isset($_POST['update'])) {
    $id      = $_POST['id_petugas'];
    $nama    = $_POST['nama_petugas'];
    $nip     = !empty($_POST['nip']) ? "'".$_POST['nip']."'" : "NULL";
    $jabatan = $_POST['jabatan'];
    $email   = $_POST['email'];
    $telp    = $_POST['no_telepon'];

    mysqli_query($conn, "UPDATE petugas_pajak SET 
                         nama_petugas='$nama', nip=$nip, jabatan='$jabatan', email='$email', no_telepon='$telp' 
                         WHERE id_petugas='$id'");
    header("Location: petugas_pajak.php");
    exit;
}

// 3. PROSES DELETE (HAPUS DATA)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM petugas_pajak WHERE id_petugas='$id'");
    header("Location: petugas_pajak.php");
    exit;
}

// AMBIL DATA UNTUK FORM EDIT (JIKA KLIK TOMBOL EDIT)
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM petugas_pajak WHERE id_petugas='$id'");
    $editData = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petugas Pajak | Sistem Perpajakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Spectral:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#1A0F0A;--surface:#2C1A10;--raised:#3D2517;--gold:#CA8A04;--border:#5C3D2E;--text:#F5E6D3;--muted:#BFA98A}
        body{background:var(--bg);color:var(--text);font-family:'Spectral',serif;line-height:1.7}
        h1,h2,.form-label,.card-header,.table th,.eyebrow{font-family:'Cinzel',serif}
        h1{font-size:clamp(1.7rem,4vw,2.4rem);font-weight:700}
        .eyebrow{color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-size:.75rem}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:4px;box-shadow:0 2px 8px #CA8A0433}
        .card-header{background:var(--raised);color:var(--gold);border:0;border-top:2px solid var(--gold)}
        .card-body{padding:24px}
        .form-label{color:var(--text);font-size:.9rem}
        .form-control{min-height:40px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:4px}
        .form-control:focus{background:var(--surface);color:var(--text);border:2px solid var(--gold);box-shadow:0 0 0 3px #CA8A0440}
        .table{--bs-table-color:var(--text);--bs-table-bg:var(--surface);--bs-table-border-color:var(--border);--bs-table-striped-bg:#321f14;--bs-table-striped-color:var(--text);--bs-table-hover-bg:var(--raised);--bs-table-hover-color:var(--text);margin:0}
        .table th{color:var(--gold);background:var(--raised);white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .btn{font-family:'Cinzel',serif;border-radius:4px}
        .btn-primary{background:var(--gold);border-color:#DAA520;color:var(--bg);font-weight:700}
        .btn-primary:hover{background:#B8780A;color:var(--bg)}
        .btn-outline-secondary{color:var(--muted);border-color:var(--border)}
        .btn-outline-secondary:hover{background:var(--raised);color:var(--text);border-color:var(--gold)}
        .btn-outline-warning{color:var(--gold);border-color:var(--gold)}
        .btn-outline-warning:hover{background:var(--gold);color:var(--bg)}
        .btn-danger{background:#991B1B;border-color:#B91C1C;color:var(--text)}
        .btn-danger:hover{background:#7F1D1D;color:var(--text)}
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Menu</a>
    <p class="eyebrow mb-2">Data Induk · 01</p>
    <h1 class="mb-4">Manajemen Petugas Pajak</h1>

    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Petugas Pajak' : 'Daftarkan Petugas Pajak' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_petugas" value="<?= (int)$editData['id_petugas'] ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama Petugas *</label>
                        <input type="text" name="nama_petugas" class="form-control" required value="<?= htmlspecialchars($editData['nama_petugas'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($editData['nip'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jabatan *</label>
                        <input type="text" name="jabatan" class="form-control" required value="<?= htmlspecialchars($editData['jabatan'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($editData['no_telepon'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4">
                        <?= $editData ? 'Simpan Perubahan' : 'Daftarkan' ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="petugas_pajak.php" class="btn btn-outline-secondary ms-2">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-header py-3">Daftar Petugas Pajak</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Petugas</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM petugas_pajak ORDER BY id_petugas DESC");
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                        <tr>
                            <td><?= (int)$row['id_petugas'] ?></td>
                            <td><?= htmlspecialchars($row['nama_petugas'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nip'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['jabatan'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['no_telepon'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-nowrap">
                                <a href="?edit=<?= (int)$row['id_petugas'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                                <a href="?hapus=<?= (int)$row['id_petugas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data petugas ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
</body>
</html>