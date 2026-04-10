<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<?php
// ===== Include library functions =====
require_once 'functions_anggota.php';

// ===== Data Anggota =====
$anggota_list = [
    [
        "id"             => "AGT-001",
        "nama"           => "Budi Santoso",
        "email"          => "budi@email.com",
        "telepon"        => "081234567890",
        "alamat"         => "Jakarta",
        "tanggal_daftar" => "2024-01-15",
        "status"         => "Aktif",
        "total_pinjaman" => 5
    ],
    [
        "id"             => "AGT-002",
        "nama"           => "Siti Rahayu",
        "email"          => "siti@email.com",
        "telepon"        => "082345678901",
        "alamat"         => "Bandung",
        "tanggal_daftar" => "2024-02-20",
        "status"         => "Aktif",
        "total_pinjaman" => 12
    ],
    [
        "id"             => "AGT-003",
        "nama"           => "Ahmad Fauzi",
        "email"          => "ahmad@email.com",
        "telepon"        => "083456789012",
        "alamat"         => "Surabaya",
        "tanggal_daftar" => "2023-11-05",
        "status"         => "Non-Aktif",
        "total_pinjaman" => 3
    ],
    [
        "id"             => "AGT-004",
        "nama"           => "Dewi Lestari",
        "email"          => "dewi@email.com",
        "telepon"        => "084567890123",
        "alamat"         => "Yogyakarta",
        "tanggal_daftar" => "2024-03-10",
        "status"         => "Aktif",
        "total_pinjaman" => 8
    ],
    [
        "id"             => "AGT-005",
        "nama"           => "Rizky Pratama",
        "email"          => "rizky@email.com",
        "telepon"        => "085678901234",
        "alamat"         => "Semarang",
        "tanggal_daftar" => "2023-09-18",
        "status"         => "Non-Aktif",
        "total_pinjaman" => 1
    ],
    [
        "id"             => "AGT-006",
        "nama"           => "Laila Fitriani",
        "email"          => "laila@email.com",
        "telepon"        => "086789012345",
        "alamat"         => "Malang",
        "tanggal_daftar" => "2024-04-01",
        "status"         => "Aktif",
        "total_pinjaman" => 15
    ],
];

// ===== Panggil functions =====
$total_anggota   = hitung_total_anggota($anggota_list);
$total_aktif     = hitung_anggota_aktif($anggota_list);
$total_non_aktif = $total_anggota - $total_aktif;
$persen_aktif    = round(($total_aktif / $total_anggota) * 100, 1);
$rata_pinjaman   = hitung_rata_rata_pinjaman($anggota_list);
$teraktif        = cari_anggota_teraktif($anggota_list);
$aktif_list      = filter_by_status($anggota_list, "Aktif");
$non_aktif_list  = filter_by_status($anggota_list, "Non-Aktif");

// BONUS: sort & search
$sorted_list     = sort_by_nama($anggota_list);
$keyword_search  = "a"; // contoh pencarian
$search_result   = search_by_nama($anggota_list, $keyword_search);
?>

<div class="container mt-5">
    <h1 class="mb-1"><i class="bi bi-people"></i> Sistem Anggota Perpustakaan</h1>
    <p class="text-muted mb-4">Sistem manajemen anggota menggunakan Array &amp; Function PHP</p>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Info:</strong> Halaman ini menggunakan function dari file <code>functions_anggota.php</code>
    </div>

    <!-- ===== DASHBOARD STATISTIK ===== -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-primary text-center h-100">
                <div class="card-body">
                    <i class="bi bi-people fs-2"></i>
                    <h3 class="fw-bold"><?= $total_anggota ?></h3>
                    <p class="mb-0 small">Total Anggota</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-success text-center h-100">
                <div class="card-body">
                    <i class="bi bi-person-check fs-2"></i>
                    <h3 class="fw-bold"><?= $total_aktif ?></h3>
                    <p class="mb-0 small">Anggota Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-danger text-center h-100">
                <div class="card-body">
                    <i class="bi bi-person-x fs-2"></i>
                    <h3 class="fw-bold"><?= $total_non_aktif ?></h3>
                    <p class="mb-0 small">Non-Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-info text-center h-100">
                <div class="card-body">
                    <i class="bi bi-percent fs-2"></i>
                    <h3 class="fw-bold"><?= $persen_aktif ?>%</h3>
                    <p class="mb-0 small">% Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-warning text-center h-100">
                <div class="card-body">
                    <i class="bi bi-book fs-2"></i>
                    <h3 class="fw-bold"><?= $rata_pinjaman ?></h3>
                    <p class="mb-0 small">Rata-rata Pinjaman</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-white bg-secondary text-center h-100">
                <div class="card-body">
                    <i class="bi bi-trophy fs-2"></i>
                    <p class="fw-bold mb-0 small"><?= $teraktif["nama"] ?></p>
                    <small><?= $teraktif["total_pinjaman"] ?> pinjaman</small>
                    <p class="mb-0 small">Teraktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABEL SEMUA ANGGOTA ===== -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Anggota</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>ID</th><th>Nama</th><th>Email</th>
                        <th>Telepon</th><th>Alamat</th><th>Tanggal Daftar</th>
                        <th>Status</th><th>Total Pinjaman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anggota_list as $i => $a): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge bg-secondary"><?= $a["id"] ?></span></td>
                        <td><?= $a["nama"] ?></td>
                        <td><?= $a["email"] ?></td>
                        <td><?= $a["telepon"] ?></td>
                        <td><?= $a["alamat"] ?></td>
                        <td><?= format_tanggal_indo($a["tanggal_daftar"]) ?></td>
                        <td>
                            <span class="badge <?= $a["status"] === "Aktif" ? "bg-success" : "bg-danger" ?>">
                                <?= $a["status"] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary"><?= $a["total_pinjaman"] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== ANGGOTA TERAKTIF ===== -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-trophy-fill"></i> Anggota Teraktif</h5>
        </div>
        <div class="card-body">
            <?php if ($teraktif): ?>
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width:64px;height:64px;font-size:2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <div class="col">
                    <h4 class="mb-1"><?= $teraktif["nama"] ?></h4>
                    <p class="mb-0 text-muted"><?= $teraktif["id"] ?> &bull; <?= $teraktif["email"] ?></p>
                    <p class="mb-0 text-muted"><?= $teraktif["alamat"] ?> &bull; Terdaftar: <?= format_tanggal_indo($teraktif["tanggal_daftar"]) ?></p>
                </div>
                <div class="col-auto text-center">
                    <h2 class="text-success fw-bold mb-0"><?= $teraktif["total_pinjaman"] ?></h2>
                    <small class="text-muted">Total Pinjaman</small>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== FILTER AKTIF & NON-AKTIF ===== -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Anggota Aktif (<?= count($aktif_list) ?>)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($aktif_list as $a): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= $a["nama"] ?></strong><br>
                            <small class="text-muted"><?= $a["email"] ?></small>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?= $a["total_pinjaman"] ?> pinjaman</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-person-x"></i> Anggota Non-Aktif (<?= count($non_aktif_list) ?>)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($non_aktif_list as $a): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= $a["nama"] ?></strong><br>
                            <small class="text-muted"><?= $a["email"] ?></small>
                        </div>
                        <span class="badge bg-secondary rounded-pill"><?= $a["total_pinjaman"] ?> pinjaman</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- ===== BONUS: SORT & SEARCH ===== -->
    <div class="row mb-5 g-3">
        <!-- Sort A-Z -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-sort-alpha-down"></i> Anggota (Sort A-Z)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($sorted_list as $i => $a): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?= $i+1 ?>. <?= $a["nama"] ?></span>
                        <span class="badge <?= $a["status"] === "Aktif" ? "bg-success" : "bg-danger" ?>">
                            <?= $a["status"] ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- Search by nama -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-search"></i> Hasil Pencarian: "<?= htmlspecialchars($keyword_search) ?>"
                        <span class="badge bg-light text-dark"><?= count($search_result) ?> ditemukan</span>
                    </h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (empty($search_result)): ?>
                    <li class="list-group-item text-muted">Tidak ditemukan.</li>
                    <?php else: ?>
                        <?php foreach ($search_result as $a): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong><?= $a["nama"] ?></strong>
                                <span class="badge bg-secondary ms-1"><?= $a["id"] ?></span><br>
                                <small class="text-muted"><?= $a["alamat"] ?></small>
                            </div>
                            <span class="badge bg-primary rounded-pill"><?= $a["total_pinjaman"] ?> pinjaman</span>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>