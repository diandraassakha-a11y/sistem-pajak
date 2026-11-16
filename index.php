<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Perpajakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Spectral:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#1A0F0A;--surface:#2C1A10;--raised:#3D2517;--gold:#CA8A04;--border:#5C3D2E;--text:#F5E6D3;--muted:#BFA98A}
        body{min-height:100vh;background:radial-gradient(ellipse at top,#3D2517 0%,var(--bg) 62%);color:var(--text);font-family:'Spectral',serif;line-height:1.7}
        h1,h2,.eyebrow,.menu-link{font-family:'Cinzel',serif}
        h1{font-size:clamp(2rem,5vw,3rem);font-weight:700;color:var(--text)}
        .eyebrow{color:var(--gold);letter-spacing:3px;text-transform:uppercase;font-size:.75rem}
        .intro{max-width:720px;color:var(--muted);font-size:1.1rem}
        .menu-card{height:100%;background:var(--surface);border:1px solid var(--border);border-top:2px solid var(--gold);border-radius:4px;box-shadow:0 2px 8px #CA8A0433;transition:box-shadow .3s ease,transform .3s ease}
        .menu-card:hover{box-shadow:0 4px 16px #CA8A0440;transform:translateY(-2px)}
        .menu-link{height:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:20px 22px;color:var(--text);text-decoration:none;font-weight:600}
        .menu-link:hover{color:var(--gold)}
        .menu-number{font-size:.75rem;letter-spacing:1px;color:var(--gold)}
        .arrow{color:var(--gold);font-size:1.2rem}
        .section-title{font-size:1.15rem;color:var(--gold);letter-spacing:1px}
    </style>
</head>
<body>
<main class="container py-5">
    <header class="mb-5">
        <p class="eyebrow mb-2">Portal Administrasi</p>
        <h1 class="mb-3">Sistem Informasi Perpajakan</h1>
        <p class="intro mb-0">Kelola data wajib pajak, administrasi, pelaporan, pembayaran, dan pemeriksaan dalam satu ruang kerja.</p>
    </header>
    <section class="mb-5">
        <h2 class="section-title mb-3">Data Induk</h2>
        <div class="row g-3">
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="petugas_pajak.php"><span><span class="menu-number">01</span><br>Petugas Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="wajib_pajak.php"><span><span class="menu-number">02</span><br>Wajib Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="perusahaan.php"><span><span class="menu-number">03</span><br>Perusahaan</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="jenis_pajak.php"><span><span class="menu-number">04</span><br>Jenis Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="objek_pajak.php"><span><span class="menu-number">05</span><br>Objek Pajak</span><span class="arrow">&rarr;</span></a></article></div>
        </div>
    </section>
    <section>
        <h2 class="section-title mb-3">Administrasi Pajak</h2>
        <div class="row g-3">
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="kewajiban_pajak.php"><span><span class="menu-number">06</span><br>Kewajiban Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="pelaporan_pajak.php"><span><span class="menu-number">07</span><br>Pelaporan Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="pembayaran_pajak.php"><span><span class="menu-number">08</span><br>Pembayaran Pajak</span><span class="arrow">&rarr;</span></a></article></div>
            <div class="col-md-6 col-lg-3"><article class="menu-card"><a class="menu-link" href="pemeriksaan_pajak.php"><span><span class="menu-number">09</span><br>Pemeriksaan Pajak</span><span class="arrow">&rarr;</span></a></article></div>
        </div>
    </section>
</main>
</body>
</html>
