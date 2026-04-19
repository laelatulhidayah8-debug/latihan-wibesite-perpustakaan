<?php
session_start();

// ===== DATA BUKU (12 buku) =====
$buku_list = [
    ["kode"=>"BK-001","judul"=>"Pemrograman PHP untuk Pemula","kategori"=>"Programming","pengarang"=>"Budi Raharjo","penerbit"=>"Informatika","tahun"=>2023,"harga"=>75000,"stok"=>10],
    ["kode"=>"BK-002","judul"=>"Mastering MySQL Database","kategori"=>"Database","pengarang"=>"Andi Nugroho","penerbit"=>"Graha Ilmu","tahun"=>2022,"harga"=>95000,"stok"=>5],
    ["kode"=>"BK-003","judul"=>"Laravel Framework Advanced","kategori"=>"Programming","pengarang"=>"Siti Aminah","penerbit"=>"Informatika","tahun"=>2024,"harga"=>125000,"stok"=>8],
    ["kode"=>"BK-004","judul"=>"Web Design Principles","kategori"=>"Web Design","pengarang"=>"Dedi Santoso","penerbit"=>"Andi","tahun"=>2023,"harga"=>85000,"stok"=>15],
    ["kode"=>"BK-005","judul"=>"Network Security Fundamentals","kategori"=>"Networking","pengarang"=>"Rina Wijaya","penerbit"=>"Elex Media","tahun"=>2023,"harga"=>110000,"stok"=>3],
    ["kode"=>"BK-006","judul"=>"PHP Web Services","kategori"=>"Programming","pengarang"=>"Budi Raharjo","penerbit"=>"Informatika","tahun"=>2024,"harga"=>80000,"stok"=>12],
    ["kode"=>"BK-007","judul"=>"PostgreSQL Advanced","kategori"=>"Database","pengarang"=>"Ahmad Yani","penerbit"=>"Graha Ilmu","tahun"=>2024,"harga"=>115000,"stok"=>7],
    ["kode"=>"BK-008","judul"=>"JavaScript Modern ES6+","kategori"=>"Programming","pengarang"=>"Siti Aminah","penerbit"=>"Informatika","tahun"=>2023,"harga"=>90000,"stok"=>0],
    ["kode"=>"BK-009","judul"=>"UI/UX Design dengan Figma","kategori"=>"Web Design","pengarang"=>"Maya Putri","penerbit"=>"Andi","tahun"=>2024,"harga"=>105000,"stok"=>6],
    ["kode"=>"BK-010","judul"=>"Python Data Science","kategori"=>"Data Science","pengarang"=>"Rizky Pratama","penerbit"=>"Elex Media","tahun"=>2022,"harga"=>130000,"stok"=>4],
    ["kode"=>"BK-011","judul"=>"Machine Learning dengan Python","kategori"=>"Data Science","pengarang"=>"Rizky Pratama","penerbit"=>"Elex Media","tahun"=>2023,"harga"=>145000,"stok"=>2],
    ["kode"=>"BK-012","judul"=>"Vue.js & REST API","kategori"=>"Programming","pengarang"=>"Dedi Santoso","penerbit"=>"Informatika","tahun"=>2024,"harga"=>99000,"stok"=>9],
];

// ===== Ambil parameter GET =====
$keyword   = trim($_GET['keyword']   ?? '');
$kategori  = trim($_GET['kategori']  ?? '');
$min_harga = $_GET['min_harga'] !== '' ? (int)$_GET['min_harga'] : 0;
$max_harga = $_GET['max_harga'] !== '' ? (int)$_GET['max_harga'] : 999999;
$tahun     = trim($_GET['tahun']     ?? '');
$status    = trim($_GET['status']    ?? 'semua');
$sort      = trim($_GET['sort']      ?? 'judul');
$page      = max(1, (int)($_GET['page'] ?? 1));
$per_page  = 5;

// ===== Validasi =====
$errors = [];
$raw_min = $_GET['min_harga'] ?? '';
$raw_max = $_GET['max_harga'] ?? '';

if ($raw_min !== '' && $raw_max !== '') {
    if ((int)$raw_min > (int)$raw_max) {
        $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum.";
    }
}
if ($tahun !== '' && ($tahun < 1900 || $tahun > date('Y'))) {
    $errors[] = "Tahun terbit harus antara 1900 – " . date('Y') . ".";
}

// ===== Simpan recent searches ke session =====
$is_searching = isset($_GET['keyword']) || isset($_GET['kategori']) || isset($_GET['tahun']);
if ($is_searching && empty($errors) && ($keyword || $kategori || $tahun)) {
    $label = array_filter([$keyword, $kategori, $tahun ? "Tahun:$tahun" : '']);
    $label_str = implode(', ', $label);
    if ($label_str) {
        if (!isset($_SESSION['recent_searches'])) $_SESSION['recent_searches'] = [];
        // Hindari duplikat
        if (!in_array($label_str, $_SESSION['recent_searches'])) {
            array_unshift($_SESSION['recent_searches'], $label_str);
            $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
        }
    }
}

// ===== FILTER =====
$hasil = [];
if (empty($errors)) {
    foreach ($buku_list as $b) {
        // Keyword
        if ($keyword && stripos($b['judul'].$b['pengarang'], $keyword) === false) continue;
        // Kategori
        if ($kategori && $b['kategori'] !== $kategori) continue;
        // Harga
        if ($raw_min !== '' && $b['harga'] < (int)$raw_min) continue;
        if ($raw_max !== '' && $b['harga'] > (int)$raw_max) continue;
        // Tahun
        if ($tahun !== '' && $b['tahun'] != $tahun) continue;
        // Status
        if ($status === 'tersedia' && $b['stok'] <= 0) continue;
        if ($status === 'habis'    && $b['stok'] > 0)  continue;
        $hasil[] = $b;
    }

    // ===== SORTING =====
    usort($hasil, function($a, $b) use ($sort) {
        if ($sort === 'harga_asc')  return $a['harga'] - $b['harga'];
        if ($sort === 'harga_desc') return $b['harga'] - $a['harga'];
        if ($sort === 'tahun')      return $b['tahun']  - $a['tahun'];
        return strcmp($a['judul'], $b['judul']);
    });
}

// ===== PAGINATION =====
$total_hasil  = count($hasil);
$total_pages  = max(1, ceil($total_hasil / $per_page));
$page         = min($page, $total_pages);
$offset       = ($page - 1) * $per_page;
$hasil_page   = array_slice($hasil, $offset, $per_page);

// ===== Helper: highlight keyword =====
function highlight($text, $keyword) {
    if (!$keyword) return htmlspecialchars($text);
    $safe_kw = preg_quote($keyword, '/');
    return preg_replace(
        "/($safe_kw)/i",
        '<mark class="p-0 bg-warning">$1</mark>',
        htmlspecialchars($text)
    );
}

// ===== Helper: format rupiah =====
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// ===== Daftar kategori unik =====
$kategori_list = array_unique(array_column($buku_list, 'kategori'));
sort($kategori_list);

// ===== Warna badge kategori =====
$badge_colors = [
    'Programming' => 'bg-primary',
    'Database'    => 'bg-warning text-dark',
    'Web Design'  => 'bg-info text-dark',
    'Networking'  => 'bg-danger',
    'Data Science'=> 'bg-success',
];

// ===== URL builder =====
function build_url($params_override) {
    $base = array_filter([
        'keyword'   => $_GET['keyword']   ?? '',
        'kategori'  => $_GET['kategori']  ?? '',
        'min_harga' => $_GET['min_harga'] ?? '',
        'max_harga' => $_GET['max_harga'] ?? '',
        'tahun'     => $_GET['tahun']     ?? '',
        'status'    => $_GET['status']    ?? 'semua',
        'sort'      => $_GET['sort']      ?? 'judul',
        'page'      => $_GET['page']      ?? 1,
    ], fn($v) => $v !== '');
    $merged = array_merge($base, $params_override);
    return '?' . http_build_query($merged);
}

// ===== Export CSV =====
if (isset($_GET['export']) && $_GET['export'] === 'csv' && !empty($hasil)) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="hasil_pencarian.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
    fputcsv($out, ['Kode','Judul','Kategori','Pengarang','Penerbit','Tahun','Harga','Stok']);
    foreach ($hasil as $b) {
        fputcsv($out, [$b['kode'],$b['judul'],$b['kategori'],$b['pengarang'],$b['penerbit'],$b['tahun'],$b['harga'],$b['stok']]);
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Buku Lanjutan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        mark { border-radius: 3px; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">

    <h2 class="mb-1"><i class="bi bi-search text-primary"></i> Pencarian Buku Lanjutan</h2>
    <p class="text-muted mb-4">Cari buku berdasarkan berbagai kriteria.</p>

    <!-- ===== RECENT SEARCHES ===== -->
    <?php if (!empty($_SESSION['recent_searches'])): ?>
    <div class="mb-3">
        <small class="text-muted"><i class="bi bi-clock-history"></i> Pencarian terbaru: </small>
        <?php foreach ($_SESSION['recent_searches'] as $rs): ?>
        <span class="badge bg-light text-dark border me-1"><?= htmlspecialchars($rs) ?></span>
        <?php endforeach; ?>
        <a href="?clear_history=1" class="btn btn-sm btn-outline-secondary ms-1">
            <i class="bi bi-trash3"></i> Hapus
        </a>
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['clear_history'])) { $_SESSION['recent_searches'] = []; header('Location: search_advanced.php'); exit; } ?>

    <!-- ===== FORM PENCARIAN ===== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Pencarian</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">
                    <!-- Keyword -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kata Kunci (Judul / Pengarang)</label>
                        <input type="text" name="keyword" class="form-control"
                               placeholder="Masukkan kata kunci..."
                               value="<?= htmlspecialchars($keyword) ?>">
                    </div>
                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            <?php foreach ($kategori_list as $k): ?>
                            <option value="<?= $k ?>" <?= $kategori === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Harga Min -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Minimum (Rp)</label>
                        <input type="number" name="min_harga" class="form-control <?= !empty($errors) && strpos(implode('',$errors),'minimum') !== false ? 'is-invalid' : '' ?>"
                               min="0" placeholder="0"
                               value="<?= htmlspecialchars($raw_min) ?>">
                    </div>
                    <!-- Harga Max -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Maksimum (Rp)</label>
                        <input type="number" name="max_harga" class="form-control"
                               min="0" placeholder="1000000"
                               value="<?= htmlspecialchars($raw_max) ?>">
                    </div>
                    <!-- Tahun -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun Terbit</label>
                        <input type="number" name="tahun" class="form-control"
                               min="1900" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($tahun) ?>">
                        <div class="form-text">1900 – <?= date('Y') ?></div>
                    </div>
                    <!-- Sorting -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="judul"      <?= $sort === 'judul'      ? 'selected' : '' ?>>Judul (A-Z)</option>
                            <option value="harga_asc"  <?= $sort === 'harga_asc'  ? 'selected' : '' ?>>Harga (Termurah)</option>
                            <option value="harga_desc" <?= $sort === 'harga_desc' ? 'selected' : '' ?>>Harga (Termahal)</option>
                            <option value="tahun"      <?= $sort === 'tahun'      ? 'selected' : '' ?>>Tahun (Terbaru)</option>
                        </select>
                    </div>
                    <!-- Status Ketersediaan -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Status Ketersediaan</label><br>
                        <?php foreach (['semua'=>'Semua', 'tersedia'=>'Tersedia', 'habis'=>'Habis'] as $val => $label): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status"
                                   id="status_<?= $val ?>" value="<?= $val ?>"
                                   <?= $status === $val ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_<?= $val ?>"><?= $label ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Tombol -->
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="search_advanced.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    <?php if (!empty($hasil)): ?>
                    <a href="<?= build_url(['export' => 'csv', 'page' => 1]) ?>" class="btn btn-outline-success ms-auto">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== PESAN ERROR VALIDASI ===== -->
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
        <div><i class="bi bi-exclamation-circle-fill"></i> <?= $e ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ===== HASIL PENCARIAN ===== -->
    <?php if (empty($errors)): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> Hasil Pencarian
                <span class="badge bg-primary ms-2"><?= $total_hasil ?> buku ditemukan</span>
            </h5>
            <?php if ($is_searching && $keyword): ?>
            <small>Keyword: "<strong><?= htmlspecialchars($keyword) ?></strong>"</small>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if (empty($hasil)): ?>
            <div class="p-4 text-center text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Tidak ada buku yang sesuai dengan kriteria pencarian.</p>
                <?php if (!$is_searching): ?>
                <small>Gunakan form di atas untuk mencari buku.</small>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil_page as $i => $b):
                            $no = $offset + $i + 1;
                            $badge = $badge_colors[$b['kategori']] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><span class="badge bg-secondary"><?= $b['kode'] ?></span></td>
                            <td><?= highlight($b['judul'], $keyword) ?></td>
                            <td><span class="badge <?= $badge ?>"><?= $b['kategori'] ?></span></td>
                            <td><?= highlight($b['pengarang'], $keyword) ?></td>
                            <td><?= htmlspecialchars($b['penerbit']) ?></td>
                            <td><?= $b['tahun'] ?></td>
                            <td class="fw-semibold"><?= rupiah($b['harga']) ?></td>
                            <td><?= $b['stok'] ?></td>
                            <td>
                                <?php if ($b['stok'] > 5): ?>
                                <span class="badge bg-success">Tersedia</span>
                                <?php elseif ($b['stok'] > 0): ?>
                                <span class="badge bg-warning text-dark">Terbatas</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== PAGINATION ===== -->
        <?php if ($total_pages > 1): ?>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan <?= $offset + 1 ?>–<?= min($offset + $per_page, $total_hasil) ?> dari <?= $total_hasil ?> buku
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= build_url(['page' => $page - 1]) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= build_url(['page' => $p]) ?>"><?= $p ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= build_url(['page' => $page + 1]) ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div><!-- /container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>