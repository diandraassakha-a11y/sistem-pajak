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
    $idPetugas = (int)($_POST['id_petugas'] ?? 0);
    $tanggal = $_POST['tanggal_pemeriksaan'] ?? '';
    $hasil = $_POST['hasil_pemeriksaan'] ?? '';
    $status = $_POST['status_pemeriksaan'] ?? 'Proses';
    $sql = "INSERT INTO pemeriksaan_pajak (id_wajib_pajak, id_petugas, tanggal_pemeriksaan, hasil_pemeriksaan, status_pemeriksaan)
            VALUES ($idWp, $idPetugas, " . sqlQuoted($conn, $tanggal) . ", " . sqlNullable($conn, $hasil) . ", " . sqlQuoted($conn, $status) . ")";
    runQuery($conn, $sql);
    header("Location: pemeriksaan_pajak.php");
    exit;
}
if (isset($_POST['update'])) {
    $id = (int)($_POST['id_pemeriksaan'] ?? 0);
    $idWp = (int)($_POST['id_wajib_pajak'] ?? 0);
    $idPetugas = (int)($_POST['id_petugas'] ?? 0);
    $tanggal = $_POST['tanggal_pemeriksaan'] ?? '';
    $hasil = $_POST['hasil_pemeriksaan'] ?? '';
    $status = $_POST['status_pemeriksaan'] ?? 'Proses';
    $sql = "UPDATE pemeriksaan_pajak SET id_wajib_pajak=$idWp, id_petugas=$idPetugas,
            tanggal_pemeriksaan=" . sqlQuoted($conn, $tanggal) . ", hasil_pemeriksaan=" . sqlNullable($conn, $hasil) . ",
            status_pemeriksaan=" . sqlQuoted($conn, $status) . " WHERE id_pemeriksaan=$id";
    runQuery($conn, $sql);
    header("Location: pemeriksaan_pajak.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    runQuery($conn, "DELETE FROM pemeriksaan_pajak WHERE id_pemeriksaan=$id");
    header("Location: pemeriksaan_pajak.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = runQuery($conn, "SELECT * FROM pemeriksaan_pajak WHERE id_pemeriksaan=$id");
    $editData = mysqli_fetch_assoc($res) ?: null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemeriksaan Pajak | Sistem Perpajakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Spectral:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#1A0F0A;--surface:#2C1A10;--raised:#3D2517;--gold:#CA8A04;--border:#5C3D2E;--text:#F5E6D3;--muted:#BFA98A}
        body{background:var(--bg);color:var(--text);font-family:'Spectral',serif;line-height:1.7}
        h1,h2,.form-label,.card-header,.table th,.eyebrow{font-family:'Cinzel',serif}
        h1{font-size:clamp(1.7rem,4vw,2.4rem);font-weight:700}.eyebrow{color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-size:.75rem}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:4px;box-shadow:0 2px 8px #CA8A0433}
        .card-header{background:var(--raised);color:var(--gold);border:0;border-top:2px solid var(--gold)}.card-body{padding:24px}
        .form-label{color:var(--text);font-size:.9rem}.form-control,.form-select{min-height:40px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:4px}
        .form-control:focus,.form-select:focus{background:var(--surface);color:var(--text);border:2px solid var(--gold);box-shadow:0 0 0 3px #CA8A0440}.form-select option{background:var(--surface);color:var(--text)}textarea.form-control{min-height:90px}
        .table{--bs-table-color:var(--text);--bs-table-bg:var(--surface);--bs-table-border-color:var(--border);--bs-table-striped-bg:#321f14;--bs-table-striped-color:var(--text);--bs-table-hover-bg:var(--raised);--bs-table-hover-color:var(--text);margin:0}
        .table th{color:var(--gold);background:var(--raised);white-space:nowrap}.table td,.table th{vertical-align:middle}
        .btn{font-family:'Cinzel',serif;border-radius:4px}.btn-primary{background:var(--gold);border-color:#DAA520;color:var(--bg);font-weight:700}.btn-primary:hover{background:#B8780A;color:var(--bg)}
        .btn-outline-secondary{color:var(--muted);border-color:var(--border)}.btn-outline-secondary:hover{background:var(--raised);color:var(--text);border-color:var(--gold)}
        .btn-outline-warning{color:var(--gold);border-color:var(--gold)}.btn-outline-warning:hover{background:var(--gold);color:var(--bg)}
        .btn-danger{background:#991B1B;border-color:#B91C1C;color:var(--text)}.btn-danger:hover{background:#7F1D1D;color:var(--text)}
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Menu</a>
    <p class="eyebrow mb-2">Administrasi Pajak · 09</p>
    <h1 class="mb-4">Manajemen Pemeriksaan Pajak</h1>
    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Pemeriksaan' : 'Catat Pemeriksaan' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?><input type="hidden" name="id_pemeriksaan" value="<?= (int)$editData['id_pemeriksaan'] ?>"><?php endif; ?>
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
                        <label class="form-label">Petugas Pajak *</label>
                        <select name="id_petugas" class="form-select" required>
                            <option value="">-- Pilih Petugas --</option>
                            <?php
                            $petugasList = runQuery($conn, "SELECT id_petugas, nama_petugas, nip, jabatan FROM petugas_pajak ORDER BY nama_petugas");
                            while ($petugas = mysqli_fetch_assoc($petugasList)):
                                $selected = ($editData['id_petugas'] ?? '') == $petugas['id_petugas'] ? 'selected' : '';
                                $label = $petugas['nama_petugas'] . ' · ' . ($petugas['nip'] ?? 'Tanpa NIP') . ' · ' . $petugas['jabatan'];
                            ?>
                                <option value="<?= (int)$petugas['id_petugas'] ?>" <?= $selected ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pemeriksaan *</label>
                        <input type="date" name="tanggal_pemeriksaan" class="form-control" required value="<?= htmlspecialchars($editData['tanggal_pemeriksaan'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Pemeriksaan *</label>
                        <select name="status_pemeriksaan" class="form-select" required>
                            <option value="Proses" <?= ($editData['status_pemeriksaan'] ?? 'Proses') === 'Proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="Selesai" <?= ($editData['status_pemeriksaan'] ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hasil Pemeriksaan</label>
                        <textarea name="hasil_pemeriksaan" class="form-control" rows="3"><?= htmlspecialchars($editData['hasil_pemeriksaan'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4"><?= $editData ? 'Simpan Perubahan' : 'Catat Pemeriksaan' ?></button>
                    <?php if ($editData): ?><a href="pemeriksaan_pajak.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-header py-3">Daftar Pemeriksaan Pajak</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>ID</th><th>Wajib Pajak</th><th>Petugas</th><th>Tanggal</th><th>Hasil</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php
                $sql = "SELECT p.*, w.npwp, w.nama_wajib_pajak, t.nama_petugas, t.nip
                        FROM pemeriksaan_pajak p
                        JOIN wajib_pajak w ON p.id_wajib_pajak=w.id_wajib_pajak
                        JOIN petugas_pajak t ON p.id_petugas=t.id_petugas
                        ORDER BY p.id_pemeriksaan DESC";
                $query = runQuery($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= (int)$row['id_pemeriksaan'] ?></td>
                        <td><?= htmlspecialchars($row['npwp'] . ' - ' . $row['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_petugas'] . ' · ' . ($row['nip'] ?? 'Tanpa NIP'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['tanggal_pemeriksaan'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['hasil_pemeriksaan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['status_pemeriksaan'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-nowrap">
                            <a href="?edit=<?= (int)$row['id_pemeriksaan'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                            <a href="?hapus=<?= (int)$row['id_pemeriksaan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pemeriksaan ini?')">Hapus</a>
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
