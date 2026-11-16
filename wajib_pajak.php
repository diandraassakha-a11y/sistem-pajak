<?php
include 'config.php';

function sqlQuoted($conn, $value) {
    return "'" . mysqli_real_escape_string($conn, (string)$value) . "'";
}

function sqlNullable($conn, $value) {
    return $value === '' ? 'NULL' : sqlQuoted($conn, $value);
}

function runQuery($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
    }
    return $result;
}

if (isset($_POST['simpan'])) {
    $npwp = $_POST['npwp'] ?? '';
    $nama = $_POST['nama_wajib_pajak'] ?? '';
    $jenis = $_POST['jenis_wajib_pajak'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email = $_POST['email'] ?? '';
    $telp = $_POST['no_telepon'] ?? '';

    $sql = "INSERT INTO wajib_pajak (npwp, nama_wajib_pajak, jenis_wajib_pajak, alamat, email, no_telepon)
            VALUES (" . sqlQuoted($conn, $npwp) . ", " . sqlQuoted($conn, $nama) . ", " . sqlQuoted($conn, $jenis) . ",
                    " . sqlNullable($conn, $alamat) . ", " . sqlNullable($conn, $email) . ", " . sqlNullable($conn, $telp) . ")";
    runQuery($conn, $sql);
    header("Location: wajib_pajak.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = (int)($_POST['id_wajib_pajak'] ?? 0);
    $npwp = $_POST['npwp'] ?? '';
    $nama = $_POST['nama_wajib_pajak'] ?? '';
    $jenis = $_POST['jenis_wajib_pajak'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email = $_POST['email'] ?? '';
    $telp = $_POST['no_telepon'] ?? '';

    $sql = "UPDATE wajib_pajak SET
            npwp=" . sqlQuoted($conn, $npwp) . ",
            nama_wajib_pajak=" . sqlQuoted($conn, $nama) . ",
            jenis_wajib_pajak=" . sqlQuoted($conn, $jenis) . ",
            alamat=" . sqlNullable($conn, $alamat) . ",
            email=" . sqlNullable($conn, $email) . ",
            no_telepon=" . sqlNullable($conn, $telp) . "
            WHERE id_wajib_pajak=$id";
    runQuery($conn, $sql);
    header("Location: wajib_pajak.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    runQuery($conn, "DELETE FROM wajib_pajak WHERE id_wajib_pajak=$id");
    header("Location: wajib_pajak.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = runQuery($conn, "SELECT * FROM wajib_pajak WHERE id_wajib_pajak=$id");
    $editData = mysqli_fetch_assoc($res) ?: null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wajib Pajak | Sistem Perpajakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Spectral:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#1A0F0A;--surface:#2C1A10;--raised:#3D2517;--gold:#CA8A04;--border:#5C3D2E;--text:#F5E6D3;--muted:#BFA98A}
        body{background:var(--bg);color:var(--text);font-family:'Spectral',serif;font-size:16px;line-height:1.7}
        h1,h2,h3,h4,.form-label,.card-header,.table thead th,.eyebrow{font-family:'Cinzel',serif}
        h1{font-size:clamp(1.7rem,4vw,2.4rem);font-weight:700;color:var(--text)}
        .eyebrow{color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-size:.75rem}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:4px;box-shadow:0 2px 8px #CA8A0433}
        .card-header{background:var(--raised);color:var(--gold);border-bottom:1px solid var(--border);border-top:2px solid var(--gold);font-weight:600}
        .card-body{padding:24px}
        .form-label{color:var(--text);font-size:.9rem;margin-bottom:6px}
        .form-control,.form-select{min-height:40px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:4px}
        .form-control:focus,.form-select:focus{background:var(--surface);color:var(--text);border:2px solid var(--gold);box-shadow:0 0 0 3px #CA8A0440}
        .form-control::placeholder{color:var(--muted)}
        textarea.form-control{min-height:90px}
        .form-select option{background:var(--surface);color:var(--text)}
        .table{--bs-table-color:var(--text);--bs-table-bg:var(--surface);--bs-table-border-color:var(--border);--bs-table-striped-bg:#321f14;--bs-table-striped-color:var(--text);--bs-table-hover-bg:var(--raised);--bs-table-hover-color:var(--text);margin-bottom:0}
        .table thead th{color:var(--gold);background:var(--raised);white-space:nowrap;letter-spacing:.5px}
        .table td,.table th{vertical-align:middle}
        .btn{border-radius:4px;font-family:'Cinzel',serif;font-size:.82rem}
        .btn-primary{background:var(--gold);border-color:#DAA520;color:var(--bg);font-weight:700}
        .btn-primary:hover{background:#B8780A;border-color:var(--gold);color:var(--bg)}
        .btn-outline-secondary{color:var(--muted);border-color:var(--border)}
        .btn-outline-secondary:hover{background:var(--raised);color:var(--text);border-color:var(--gold)}
        .btn-outline-warning{color:var(--gold);border-color:var(--gold)}
        .btn-outline-warning:hover{background:#CA8A04;color:var(--bg)}
        .btn-danger{background:#991B1B;border-color:#B91C1C;color:var(--text)}
        .btn-danger:hover{background:#7F1D1D;border-color:#B91C1C;color:var(--text)}
        .table-responsive{border-radius:4px}
        .text-muted{color:var(--muted)!important}
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Menu</a>
    <p class="eyebrow mb-2">Data Induk · 02</p>
    <h1 class="mb-4">Manajemen Wajib Pajak</h1>

    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Wajib Pajak' : 'Daftarkan Wajib Pajak' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_wajib_pajak" value="<?= (int)$editData['id_wajib_pajak'] ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">NPWP *</label>
                        <input type="text" name="npwp" maxlength="30" class="form-control" required value="<?= htmlspecialchars($editData['npwp'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Wajib Pajak *</label>
                        <input type="text" name="nama_wajib_pajak" maxlength="150" class="form-control" required value="<?= htmlspecialchars($editData['nama_wajib_pajak'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Wajib Pajak *</label>
                        <select name="jenis_wajib_pajak" class="form-select" required>
                            <option value="Orang Pribadi" <?= ($editData['jenis_wajib_pajak'] ?? '') === 'Orang Pribadi' ? 'selected' : '' ?>>Orang Pribadi</option>
                            <option value="Badan" <?= ($editData['jenis_wajib_pajak'] ?? '') === 'Badan' ? 'selected' : '' ?>>Badan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" maxlength="100" class="form-control" value="<?= htmlspecialchars($editData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" maxlength="20" class="form-control" value="<?= htmlspecialchars($editData['no_telepon'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($editData['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4"><?= $editData ? 'Simpan Perubahan' : 'Daftarkan' ?></button>
                    <?php if ($editData): ?><a href="wajib_pajak.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-header py-3">Daftar Wajib Pajak</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>ID</th><th>NPWP</th><th>Nama Wajib Pajak</th><th>Jenis</th><th>Alamat</th><th>Kontak</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php
                    $query = runQuery($conn, "SELECT * FROM wajib_pajak ORDER BY id_wajib_pajak DESC");
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                        <tr>
                            <td><?= (int)$row['id_wajib_pajak'] ?></td>
                            <td><?= htmlspecialchars($row['npwp'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['jenis_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['alamat'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars($row['no_telepon'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-nowrap">
                                <a href="?edit=<?= (int)$row['id_wajib_pajak'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                                <a href="?hapus=<?= (int)$row['id_wajib_pajak'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data wajib pajak ini?')">Hapus</a>
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
