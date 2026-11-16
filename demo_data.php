<?php

$markerTable = mysqli_query($conn, "CREATE TABLE IF NOT EXISTS demo_data_state (
    seed_key VARCHAR(50) NOT NULL PRIMARY KEY,
    seeded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
if ($markerTable === false) {
    die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}

$seedState = mysqli_query($conn, "SELECT seed_key FROM demo_data_state WHERE seed_key='initial_demo_data'");
if ($seedState === false) {
    die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}
if (mysqli_num_rows($seedState) > 0) {
    return;
}

function seedDemoTableIfEmpty($conn, $table, $sql) {
    $countResult = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM `$table`");
    if ($countResult === false) {
        die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
    }

    $count = mysqli_fetch_assoc($countResult);
    if ((int)$count['jumlah'] > 0) {
        return;
    }

    if (mysqli_query($conn, $sql) === false) {
        die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
    }
}

seedDemoTableIfEmpty($conn, 'petugas_pajak', "INSERT INTO petugas_pajak
    (nama_petugas, nip, jabatan, email, no_telepon) VALUES
    ('Andi Pratama', '198501012010011001', 'Account Representative', 'andi.pratama@example.test', '081200000101'),
    ('Siti Rahmawati', '199002022015022002', 'Pemeriksa Pajak', 'siti.rahmawati@example.test', '081200000102')");

seedDemoTableIfEmpty($conn, 'wajib_pajak', "INSERT INTO wajib_pajak
    (npwp, nama_wajib_pajak, jenis_wajib_pajak, alamat, email, no_telepon) VALUES
    ('12.345.678.9-012.000', 'Budi Santoso', 'Orang Pribadi', 'Jl. Melati No. 10, Bandung', 'budi.santoso@example.test', '081200000201'),
    ('23.456.789.0-123.000', 'Maya Putri', 'Orang Pribadi', 'Jl. Kenanga No. 5, Jakarta', 'maya.putri@example.test', '081200000202'),
    ('01.234.567.8-901.000', 'PT Contoh Nusantara', 'Badan', 'Jl. Sudirman No. 25, Jakarta', 'pajak@contoh-nusantara.example.test', '0215550101')");

seedDemoTableIfEmpty($conn, 'jenis_pajak', "INSERT INTO jenis_pajak
    (kode_pajak, nama_pajak, deskripsi) VALUES
    ('PPH21', 'Pajak Penghasilan Pasal 21', 'Contoh pajak atas gaji, upah, honorarium, dan penghasilan lain orang pribadi.'),
    ('PPH23', 'Pajak Penghasilan Pasal 23', 'Contoh pajak atas penghasilan dari modal, jasa, atau hadiah tertentu.'),
    ('PPHBADAN', 'Pajak Penghasilan Badan', 'Contoh pajak atas penghasilan yang diterima atau diperoleh badan usaha.'),
    ('PPN', 'Pajak Pertambahan Nilai', 'Contoh pajak atas konsumsi barang dan jasa kena pajak.')");

seedDemoTableIfEmpty($conn, 'perusahaan', "INSERT INTO perusahaan
    (id_wajib_pajak, nama_pt, bentuk_badan_usaha, bidang_usaha, tanggal_berdiri)
    SELECT id_wajib_pajak, 'PT Contoh Nusantara', 'Perseroan Terbatas', 'Perdagangan umum', '2018-03-15'
    FROM wajib_pajak
    WHERE npwp='01.234.567.8-901.000' AND jenis_wajib_pajak='Badan'
    LIMIT 1");

seedDemoTableIfEmpty($conn, 'objek_pajak', "INSERT INTO objek_pajak
    (id_wajib_pajak, nama_objek, kategori_objek, nilai_objek, keterangan)
    SELECT id_wajib_pajak, 'Kantor PT Contoh Nusantara', 'Tanah dan Bangunan', 1500000000.00,
           'Contoh aset usaha yang dilaporkan oleh wajib pajak badan.'
    FROM wajib_pajak WHERE npwp='01.234.567.8-901.000' LIMIT 1");

seedDemoTableIfEmpty($conn, 'kewajiban_pajak', "INSERT INTO kewajiban_pajak
    (id_wajib_pajak, id_jenis_pajak, masa_pajak, status_kewajiban)
    SELECT w.id_wajib_pajak, j.id_jenis_pajak, '10/2026', 'Aktif'
    FROM wajib_pajak w
    JOIN jenis_pajak j ON j.kode_pajak='PPH21'
    WHERE w.npwp='12.345.678.9-012.000'
    LIMIT 1");

seedDemoTableIfEmpty($conn, 'pelaporan_pajak', "INSERT INTO pelaporan_pajak
    (id_kewajiban, tanggal_lapor, nilai_pajak, status_laporan)
    SELECT k.id_kewajiban, '2026-11-05', 1250000.00, 'Disampaikan'
    FROM kewajiban_pajak k
    JOIN wajib_pajak w ON w.id_wajib_pajak=k.id_wajib_pajak
    JOIN jenis_pajak j ON j.id_jenis_pajak=k.id_jenis_pajak
    WHERE w.npwp='12.345.678.9-012.000' AND j.kode_pajak='PPH21'
    LIMIT 1");

seedDemoTableIfEmpty($conn, 'pembayaran_pajak', "INSERT INTO pembayaran_pajak
    (id_pelaporan, tanggal_bayar, jumlah_bayar, metode_bayar, nomor_bukti, status_bayar)
    SELECT p.id_pelaporan, '2026-11-10 10:30:00', p.nilai_pajak, 'Transfer Bank',
           'BPN-DEMO-20261110-001', 'Berhasil'
    FROM pelaporan_pajak p
    JOIN kewajiban_pajak k ON k.id_kewajiban=p.id_kewajiban
    JOIN wajib_pajak w ON w.id_wajib_pajak=k.id_wajib_pajak
    WHERE w.npwp='12.345.678.9-012.000'
    LIMIT 1");

seedDemoTableIfEmpty($conn, 'pemeriksaan_pajak', "INSERT INTO pemeriksaan_pajak
    (id_wajib_pajak, id_petugas, tanggal_pemeriksaan, hasil_pemeriksaan, status_pemeriksaan)
    SELECT w.id_wajib_pajak, t.id_petugas, '2026-11-12',
           'Contoh pemeriksaan dokumen dan kesesuaian pelaporan pajak.', 'Proses'
    FROM wajib_pajak w
    CROSS JOIN petugas_pajak t
    WHERE w.npwp='01.234.567.8-901.000'
      AND t.nip='198501012010011001'
    LIMIT 1");

if (mysqli_query($conn, "INSERT INTO demo_data_state (seed_key) VALUES ('initial_demo_data')") === false) {
    die('Operasi database gagal: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}
