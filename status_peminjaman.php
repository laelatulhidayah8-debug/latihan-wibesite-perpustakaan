<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Status Peminjaman Anggota</h1>

    <?php
    // ======================
    // DATA ANGGOTA
    $nama_anggota = "Budi Santoso";
    $total_pinjaman = 2;
    $buku_terlambat = 1;
    $hari_keterlambatan = 5;

    // ======================
    // HITUNG DENDA
    // ======================
    $denda = 0;
    if ($buku_terlambat > 0) {
        $denda = $buku_terlambat * $hari_keterlambatan * 1000;
        if ($denda > 50000) {
            $denda = 50000;
        }
    }

    // ======================
    // IF ELSEIF ELSE (STATUS)
    // ======================
    if ($buku_terlambat > 0) {
        $status = "Tidak bisa meminjam (ada buku terlambat)";
        $alert = "danger";
    } elseif ($total_pinjaman >= 3) {
        $status = "Tidak bisa meminjam (maksimal 3 buku)";
        $alert = "warning";
    } else {
        $status = "Boleh meminjam buku";
        $alert = "success";
    }

    // ======================
    // SWITCH (LEVEL MEMBER)
    // ======================
    switch (true) {
        case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
            $level = "Bronze";
            break;
        case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
            $level = "Silver";
            break;
        default:
            $level = "Gold";
            break;
    }
    ?>

    <!-- INFORMASI ANGGOTA -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            Informasi Anggota
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> <?= $nama_anggota ?></p>
            <p><strong>Total Pinjaman:</strong> <?= $total_pinjaman ?></p>
            <p><strong>Buku Terlambat:</strong> <?= $buku_terlambat ?></p>
        </div>
    </div>

    <!-- STATUS PEMINJAMAN -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            Status Peminjaman
        </div>
        <div class="card-body">
            <div class="alert alert-<?= $alert ?>">
                <?= $status ?>
            </div>
        </div>
    </div>

    <!-- DENDA & PERINGATAN -->
    <?php if ($buku_terlambat > 0): ?>
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">
                Peringatan Keterlambatan
            </div>
            <div class="card-body">
                <p><strong>Peringatan:</strong> Anda memiliki keterlambatan pengembalian buku.</p>
                <p><strong>Total Denda:</strong> Rp <?= number_format($denda,0,',','.') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- LEVEL MEMBER -->
    <div class="card">
        <div class="card-header bg-success text-white">
            Level Member
        </div>
        <div class="card-body">
            <h5><?= $level ?></h5>
        </div>
    </div>

</div>

</body>
</html>