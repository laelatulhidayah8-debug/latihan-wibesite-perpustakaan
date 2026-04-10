<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<?php
// ========== DATA ANGGOTA PERPUSTAKAAN ==========
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

// ========== HITUNG STATISTIK ==========
$total_anggota   = count($anggota_list);
$total_aktif     = 0;
$total_non_aktif = 0;
$total_pinjaman  = 0;
$anggota_teraktif = $anggota_list[0];

foreach ($anggota_list as $a) {
    if ($a["status"] === "Aktif") {
        $total_aktif++;
    } else {
        $total_non_aktif++;
    }
    $total_pinjaman += $a["total_pinjaman"];
    if ($a["total_pinjaman"] > $anggota_teraktif["total_pinjaman"]) {
        $anggota_teraktif = $a;
    }
}

$persen_aktif     = round(($total_aktif     / $total_anggota) * 100, 1);
$persen_non_aktif = round(($total_non_aktif / $total_anggota) * 100, 1);
$rata_pinjaman    = round($total_pinjaman / $total_anggota, 1);

// ========== FILTER BY STATUS ==========
$anggota_aktif     = array_filter($anggota_list, fn($a) => $a["status"] === "Aktif");
$anggota_non_aktif = array_filter($anggota_list, fn($a) => $a["status"] === "Non-Aktif");
?>

<div class="container mt-5">
    <h1 class="mb-4"><i class="bi bi-people-fill"></i> Array Anggota Perpustakaan</h1>

    <!-- ===== STATISTIK CARDS ===== -->
    <div class="row mb-4 g-3">
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-primary h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-people fs-2"></i>
                    <h3 class="fw-bold"><?= $total_anggota ?></h3>
                    <p class="mb-0">Total Anggota</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-success h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-person-check fs-2"></i>
                    <h3 class="fw-bold"><?= $total_aktif ?></h3>
                    <p class="mb-0">Anggota Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-secondary h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-person-x fs-2"></i>
                    <h3 class="fw-bold"><?= $total_non_aktif ?></h3>
                    <p class="mb-0">Non-Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-info h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-percent fs-2"></i>
                    <h3 class="fw-bold"><?= $persen_aktif ?>%</h3>
                    <p class="mb-0">% Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-warning h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-book fs-2"></i>
                    <h3 class="fw-bold"><?= $rata_pinjaman ?></h3>
                    <p class="mb-0">Rata-rata Pinjaman</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card text-white bg-danger h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-trophy fs-2"></i>
                    <p class="mb-0 fw-bold"><?= $anggota_teraktif["nama"] ?></p>
                    <small><?= $anggota_teraktif["total_pinjaman"] ?> pinjaman</small>
                    <p class="mb-0">Teraktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABEL SEMUA ANGGOTA ===== -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Semua Anggota</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>ID</th><th>Nama</th><th>Email</th>
                        <th>Telepon</th><th>Alamat</th><th>Tgl Daftar</th>
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
                        <td><?= $a["tanggal_daftar"] ?></td>
                        <td>
                            <span class="badge <?= $a["status"] === "Aktif" ? "bg-success" : "bg-danger" ?>">
                                <?= $a["status"] ?>
                            </span>
                        </td>
                        <td><span class="badge bg-primary"><?= $a["total_pinjaman"] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== FILTER: AKTIF ===== -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Anggota Aktif (<?= $total_aktif ?>)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($anggota_aktif as $a): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $a["nama"] ?> <small class="text-muted"><?= $a["alamat"] ?></small>
                        <span class="badge bg-primary rounded-pill"><?= $a["total_pinjaman"] ?> pinjaman</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-person-x"></i> Anggota Non-Aktif (<?= $total_non_aktif ?>)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($anggota_non_aktif as $a): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $a["nama"] ?> <small class="text-muted"><?= $a["alamat"] ?></small>
                        <span class="badge bg-secondary rounded-pill"><?= $a["total_pinjaman"] ?> pinjaman</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>