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
    $nama = $_POST['nama_objek'] ?? '';
    $kategori = $_POST['kategori_objek'] ?? '';
    $nilai = $_POST['nilai_objek'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $sql = "INSERT INTO objek_pajak (id_wajib_pajak, nama_objek, kategori_objek, nilai_objek, keterangan)
            VALUES ($idWp, " . sqlQuoted($conn, $nama) . ", " . sqlQuoted($conn, $kategori) . ",
                    " . sqlNullable($conn, $nilai) . ", " . sqlNullable($conn, $keterangan) . ")";
    runQuery($conn, $sql);
    header("Location: objek_pajak.php");
    exit;
}
if (isset($_POST['update'])) {
    $id = (int)($_POST['id_objek'] ?? 0);
    $idWp = (int)($_POST['id_wajib_pajak'] ?? 0);
    $nama = $_POST['nama_objek'] ?? '';
    $kategori = $_POST['kategori_objek'] ?? '';
    $nilai = $_POST['nilai_objek'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $sql = "UPDATE objek_pajak SET id_wajib_pajak=$idWp, nama_objek=" . sqlQuoted($conn, $nama) . ",
            kategori_objek=" . sqlQuoted($conn, $kategori) . ", nilai_objek=" . sqlNullable($conn, $nilai) . ",
            keterangan=" . sqlNullable($conn, $keterangan) . " WHERE id_objek=$id";
    runQuery($conn, $sql);
    header("Location: objek_pajak.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    runQuery($conn, "DELETE FROM objek_pajak WHERE id_objek=$id");
    header("Location: objek_pajak.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = runQuery($conn, "SELECT * FROM objek_pajak WHERE id_objek=$id");
    $editData = mysqli_fetch_assoc($res) ?: null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Objek Pajak | Sistem Perpajakan</title>
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
    <p class="eyebrow mb-2">Data Induk · 05</p>
    <h1 class="mb-4">Manajemen Objek Pajak</h1>
    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Objek Pajak' : 'Daftarkan Objek Pajak' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?><input type="hidden" name="id_objek" value="<?= (int)$editData['id_objek'] ?>"><?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Wajib Pajak *</label>
                        <select name="id_wajib_pajak" class="form-select" required>
                            <option value="">-- Pilih Wajib Pajak --</option>
                            <?php
                            $wp = runQuery($conn, "SELECT id_wajib_pajak, npwp, nama_wajib_pajak FROM wajib_pajak ORDER BY nama_wajib_pajak");
                            while ($w = mysqli_fetch_assoc($wp)):
                                $selected = ($editData['id_wajib_pajak'] ?? '') == $w['id_wajib_pajak'] ? 'selected' : '';
                            ?>
                                <option value="<?= (int)$w['id_wajib_pajak'] ?>" <?= $selected ?>><?= htmlspecialchars($w['npwp'] . ' - ' . $w['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Objek *</label>
                        <input type="text" name="nama_objek" maxlength="150" class="form-control" required value="<?= htmlspecialchars($editData['nama_objek'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori Objek *</label>
                        <input type="text" name="kategori_objek" maxlength="100" class="form-control" required value="<?= htmlspecialchars($editData['kategori_objek'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nilai Objek</label>
                        <input type="number" name="nilai_objek" step="0.01" min="0" class="form-control" value="<?= htmlspecialchars($editData['nilai_objek'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"><?= htmlspecialchars($editData['keterangan'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4"><?= $editData ? 'Simpan Perubahan' : 'Daftarkan' ?></button>
                    <?php if ($editData): ?><a href="objek_pajak.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-header py-3">Daftar Objek Pajak</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>ID</th><th>Wajib Pajak</th><th>Nama Objek</th><th>Kategori</th><th>Nilai</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php
                $sql = "SELECT objek_pajak.*, wajib_pajak.npwp, wajib_pajak.nama_wajib_pajak
                        FROM objek_pajak JOIN wajib_pajak ON objek_pajak.id_wajib_pajak=wajib_pajak.id_wajib_pajak
                        ORDER BY objek_pajak.id_objek DESC";
                $query = runQuery($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= (int)$row['id_objek'] ?></td>
                        <td><?= htmlspecialchars($row['npwp'] . ' - ' . $row['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_objek'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['kategori_objek'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $row['nilai_objek'] === null ? '-' : number_format((float)$row['nilai_objek'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['keterangan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-nowrap">
                            <a href="?edit=<?= (int)$row['id_objek'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                            <a href="?hapus=<?= (int)$row['id_objek'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus objek pajak ini?')">Hapus</a>
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
