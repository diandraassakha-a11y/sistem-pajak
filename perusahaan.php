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
    $idWp = (int)($_POST['id_wajib_pajak'] ?? 0);
    $nama = $_POST['nama_pt'] ?? '';
    $bentuk = $_POST['bentuk_badan_usaha'] ?? '';
    $bidang = $_POST['bidang_usaha'] ?? '';
    $tanggal = $_POST['tanggal_berdiri'] ?? '';
    $sql = "INSERT INTO perusahaan (id_wajib_pajak, nama_pt, bentuk_badan_usaha, bidang_usaha, tanggal_berdiri)
            VALUES ($idWp, " . sqlQuoted($conn, $nama) . ", " . sqlQuoted($conn, $bentuk) . ",
                    " . sqlNullable($conn, $bidang) . ", " . sqlNullable($conn, $tanggal) . ")";
    runQuery($conn, $sql);
    header("Location: perusahaan.php");
    exit;
}
if (isset($_POST['update'])) {
    $id = (int)($_POST['id_perusahaan'] ?? 0);
    $idWp = (int)($_POST['id_wajib_pajak'] ?? 0);
    $nama = $_POST['nama_pt'] ?? '';
    $bentuk = $_POST['bentuk_badan_usaha'] ?? '';
    $bidang = $_POST['bidang_usaha'] ?? '';
    $tanggal = $_POST['tanggal_berdiri'] ?? '';
    $sql = "UPDATE perusahaan SET id_wajib_pajak=$idWp, nama_pt=" . sqlQuoted($conn, $nama) . ",
            bentuk_badan_usaha=" . sqlQuoted($conn, $bentuk) . ", bidang_usaha=" . sqlNullable($conn, $bidang) . ",
            tanggal_berdiri=" . sqlNullable($conn, $tanggal) . " WHERE id_perusahaan=$id";
    runQuery($conn, $sql);
    header("Location: perusahaan.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    runQuery($conn, "DELETE FROM perusahaan WHERE id_perusahaan=$id");
    header("Location: perusahaan.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = runQuery($conn, "SELECT * FROM perusahaan WHERE id_perusahaan=$id");
    $editData = mysqli_fetch_assoc($res) ?: null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perusahaan | Sistem Perpajakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Spectral:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#1A0F0A;--surface:#2C1A10;--raised:#3D2517;--gold:#CA8A04;--border:#5C3D2E;--text:#F5E6D3;--muted:#BFA98A}
        body{background:var(--bg);color:var(--text);font-family:'Spectral',serif;line-height:1.7}
        h1,h2,.form-label,.card-header,.table th,.eyebrow{font-family:'Cinzel',serif}
        h1{font-size:clamp(1.7rem,4vw,2.4rem);font-weight:700}.eyebrow{color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-size:.75rem}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:4px;box-shadow:0 2px 8px #CA8A0433}
        .card-header{background:var(--raised);color:var(--gold);border:0;border-top:2px solid var(--gold)}
        .card-body{padding:24px}.form-label{color:var(--text);font-size:.9rem}
        .form-control,.form-select{min-height:40px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:4px}
        .form-control:focus,.form-select:focus{background:var(--surface);color:var(--text);border:2px solid var(--gold);box-shadow:0 0 0 3px #CA8A0440}
        .form-select option{background:var(--surface);color:var(--text)}textarea.form-control{min-height:90px}
        .table{--bs-table-color:var(--text);--bs-table-bg:var(--surface);--bs-table-border-color:var(--border);--bs-table-striped-bg:#321f14;--bs-table-striped-color:var(--text);--bs-table-hover-bg:var(--raised);--bs-table-hover-color:var(--text);margin:0}
        .table th{color:var(--gold);background:var(--raised);white-space:nowrap}.table td,.table th{vertical-align:middle}
        .btn{font-family:'Cinzel',serif;border-radius:4px}.btn-primary{background:var(--gold);border-color:#DAA520;color:var(--bg);font-weight:700}
        .btn-primary:hover{background:#B8780A;color:var(--bg)}.btn-outline-secondary{color:var(--muted);border-color:var(--border)}
        .btn-outline-secondary:hover{background:var(--raised);color:var(--text);border-color:var(--gold)}
        .btn-outline-warning{color:var(--gold);border-color:var(--gold)}.btn-outline-warning:hover{background:var(--gold);color:var(--bg)}
        .btn-danger{background:#991B1B;border-color:#B91C1C;color:var(--text)}.btn-danger:hover{background:#7F1D1D;color:var(--text)}
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Menu</a>
    <p class="eyebrow mb-2">Data Induk · 03</p>
    <h1 class="mb-4">Manajemen Perusahaan</h1>
    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Perusahaan' : 'Daftarkan Perusahaan' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?><input type="hidden" name="id_perusahaan" value="<?= (int)$editData['id_perusahaan'] ?>"><?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Wajib Pajak Badan *</label>
                        <select name="id_wajib_pajak" class="form-select" required>
                            <option value="">-- Pilih Wajib Pajak Badan --</option>
                            <?php
                            $wp = runQuery($conn, "SELECT id_wajib_pajak, npwp, nama_wajib_pajak FROM wajib_pajak WHERE jenis_wajib_pajak='Badan' ORDER BY nama_wajib_pajak");
                            while ($w = mysqli_fetch_assoc($wp)):
                                $selected = ($editData['id_wajib_pajak'] ?? '') == $w['id_wajib_pajak'] ? 'selected' : '';
                            ?>
                                <option value="<?= (int)$w['id_wajib_pajak'] ?>" <?= $selected ?>><?= htmlspecialchars($w['npwp'] . ' - ' . $w['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama PT / Perusahaan *</label>
                        <input type="text" name="nama_pt" maxlength="150" class="form-control" required value="<?= htmlspecialchars($editData['nama_pt'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bentuk Badan Usaha *</label>
                        <input type="text" name="bentuk_badan_usaha" maxlength="50" class="form-control" required value="<?= htmlspecialchars($editData['bentuk_badan_usaha'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" maxlength="100" class="form-control" value="<?= htmlspecialchars($editData['bidang_usaha'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berdiri</label>
                        <input type="date" name="tanggal_berdiri" class="form-control" value="<?= htmlspecialchars($editData['tanggal_berdiri'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4"><?= $editData ? 'Simpan Perubahan' : 'Daftarkan' ?></button>
                    <?php if ($editData): ?><a href="perusahaan.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-header py-3">Daftar Perusahaan</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>ID</th><th>NPWP / Wajib Pajak</th><th>Perusahaan</th><th>Bentuk</th><th>Bidang Usaha</th><th>Tanggal Berdiri</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php
                $sql = "SELECT perusahaan.*, wajib_pajak.npwp, wajib_pajak.nama_wajib_pajak
                        FROM perusahaan JOIN wajib_pajak ON perusahaan.id_wajib_pajak=wajib_pajak.id_wajib_pajak
                        ORDER BY perusahaan.id_perusahaan DESC";
                $query = runQuery($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= (int)$row['id_perusahaan'] ?></td>
                        <td><?= htmlspecialchars($row['npwp'] . ' - ' . $row['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_pt'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['bentuk_badan_usaha'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['bidang_usaha'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['tanggal_berdiri'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-nowrap">
                            <a href="?edit=<?= (int)$row['id_perusahaan'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                            <a href="?hapus=<?= (int)$row['id_perusahaan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data perusahaan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div></div>
    </section>
</main>
</body>
</html>
