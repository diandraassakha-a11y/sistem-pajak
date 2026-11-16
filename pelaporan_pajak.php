<?php
include 'config.php';

function sqlQuoted($conn, $value) {
    return "'" . mysqli_real_escape_string($conn, (string)$value) . "'";
}
function runQuery($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
    }
    return $result;
}

if (isset($_POST['simpan'])) {
    $idKewajiban = (int)($_POST['id_kewajiban'] ?? 0);
    $tanggal = $_POST['tanggal_lapor'] ?? '';
    $nilai = $_POST['nilai_pajak'] ?? '';
    $status = $_POST['status_laporan'] ?? 'Draft';
    $sql = "INSERT INTO pelaporan_pajak (id_kewajiban, tanggal_lapor, nilai_pajak, status_laporan)
            VALUES ($idKewajiban, " . sqlQuoted($conn, $tanggal) . ", " . sqlQuoted($conn, $nilai) . ", " . sqlQuoted($conn, $status) . ")";
    runQuery($conn, $sql);
    header("Location: pelaporan_pajak.php");
    exit;
}
if (isset($_POST['update'])) {
    $id = (int)($_POST['id_pelaporan'] ?? 0);
    $idKewajiban = (int)($_POST['id_kewajiban'] ?? 0);
    $tanggal = $_POST['tanggal_lapor'] ?? '';
    $nilai = $_POST['nilai_pajak'] ?? '';
    $status = $_POST['status_laporan'] ?? 'Draft';
    $sql = "UPDATE pelaporan_pajak SET id_kewajiban=$idKewajiban, tanggal_lapor=" . sqlQuoted($conn, $tanggal) . ",
            nilai_pajak=" . sqlQuoted($conn, $nilai) . ", status_laporan=" . sqlQuoted($conn, $status) . "
            WHERE id_pelaporan=$id";
    runQuery($conn, $sql);
    header("Location: pelaporan_pajak.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    runQuery($conn, "DELETE FROM pelaporan_pajak WHERE id_pelaporan=$id");
    header("Location: pelaporan_pajak.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = runQuery($conn, "SELECT * FROM pelaporan_pajak WHERE id_pelaporan=$id");
    $editData = mysqli_fetch_assoc($res) ?: null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pelaporan Pajak | Sistem Perpajakan</title>
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
        .form-control:focus,.form-select:focus{background:var(--surface);color:var(--text);border:2px solid var(--gold);box-shadow:0 0 0 3px #CA8A0440}.form-select option{background:var(--surface);color:var(--text)}
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
    <p class="eyebrow mb-2">Administrasi Pajak · 07</p>
    <h1 class="mb-4">Manajemen Pelaporan Pajak</h1>
    <section class="card mb-4">
        <div class="card-header py-3"><?= $editData ? 'Ubah Pelaporan' : 'Catat Pelaporan' ?></div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?><input type="hidden" name="id_pelaporan" value="<?= (int)$editData['id_pelaporan'] ?>"><?php endif; ?>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Kewajiban Pajak *</label>
                        <select name="id_kewajiban" class="form-select" required>
                            <option value="">-- Pilih Wajib Pajak · Jenis Pajak · Masa --</option>
                            <?php
                            $sql = "SELECT k.id_kewajiban, k.masa_pajak, w.nama_wajib_pajak, j.nama_pajak
                                    FROM kewajiban_pajak k
                                    JOIN wajib_pajak w ON k.id_wajib_pajak=w.id_wajib_pajak
                                    JOIN jenis_pajak j ON k.id_jenis_pajak=j.id_jenis_pajak
                                    ORDER BY w.nama_wajib_pajak, j.nama_pajak, k.masa_pajak";
                            $kewajibanList = runQuery($conn, $sql);
                            while ($k = mysqli_fetch_assoc($kewajibanList)):
                                $selected = ($editData['id_kewajiban'] ?? '') == $k['id_kewajiban'] ? 'selected' : '';
                                $label = $k['nama_wajib_pajak'] . ' · ' . $k['nama_pajak'] . ' · ' . $k['masa_pajak'];
                            ?>
                                <option value="<?= (int)$k['id_kewajiban'] ?>" <?= $selected ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lapor *</label>
                        <input type="date" name="tanggal_lapor" class="form-control" required value="<?= htmlspecialchars($editData['tanggal_lapor'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nilai Pajak *</label>
                        <input type="number" name="nilai_pajak" step="0.01" min="0" class="form-control" required value="<?= htmlspecialchars($editData['nilai_pajak'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Laporan *</label>
                        <select name="status_laporan" class="form-select" required>
                            <option value="Draft" <?= ($editData['status_laporan'] ?? 'Draft') === 'Draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="Disampaikan" <?= ($editData['status_laporan'] ?? '') === 'Disampaikan' ? 'selected' : '' ?>>Disampaikan</option>
                            <option value="Perlu Perbaikan" <?= ($editData['status_laporan'] ?? '') === 'Perlu Perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="<?= $editData ? 'update' : 'simpan' ?>" class="btn btn-primary px-4"><?= $editData ? 'Simpan Perubahan' : 'Catat Pelaporan' ?></button>
                    <?php if ($editData): ?><a href="pelaporan_pajak.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-header py-3">Daftar Pelaporan Pajak</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>ID</th><th>Wajib Pajak</th><th>Jenis Pajak</th><th>Masa Pajak</th><th>Tanggal Lapor</th><th>Nilai Pajak</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php
                $sql = "SELECT p.*, k.masa_pajak, w.nama_wajib_pajak, j.nama_pajak
                        FROM pelaporan_pajak p
                        JOIN kewajiban_pajak k ON p.id_kewajiban=k.id_kewajiban
                        JOIN wajib_pajak w ON k.id_wajib_pajak=w.id_wajib_pajak
                        JOIN jenis_pajak j ON k.id_jenis_pajak=j.id_jenis_pajak
                        ORDER BY p.id_pelaporan DESC";
                $query = runQuery($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= (int)$row['id_pelaporan'] ?></td>
                        <td><?= htmlspecialchars($row['nama_wajib_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['masa_pajak'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lapor'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format((float)$row['nilai_pajak'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['status_laporan'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-nowrap">
                            <a href="?edit=<?= (int)$row['id_pelaporan'] ?>" class="btn btn-outline-warning btn-sm">Ubah</a>
                            <a href="?hapus=<?= (int)$row['id_pelaporan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelaporan ini?')">Hapus</a>
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
