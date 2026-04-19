<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php
// ===== Sanitize input =====
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ===== Hitung umur dari tanggal lahir =====
function hitung_umur($tanggal_lahir) {
    $lahir = new DateTime($tanggal_lahir);
    $sekarang = new DateTime();
    return $lahir->diff($sekarang)->y;
}

$errors  = [];
$success = '';

// ===== Keep values =====
$nama         = '';
$email        = '';
$telepon      = '';
$alamat       = '';
$jenis_kelamin = '';
$tanggal_lahir = '';
$pekerjaan    = '';

// ===== Proses POST =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil & sanitasi input
    $nama          = sanitize_input($_POST['nama']          ?? '');
    $email         = sanitize_input($_POST['email']         ?? '');
    $telepon       = sanitize_input($_POST['telepon']       ?? '');
    $alamat        = sanitize_input($_POST['alamat']        ?? '');
    $jenis_kelamin = sanitize_input($_POST['jenis_kelamin'] ?? '');
    $tanggal_lahir = sanitize_input($_POST['tanggal_lahir'] ?? '');
    $pekerjaan     = sanitize_input($_POST['pekerjaan']     ?? '');

    // ===== VALIDASI =====

    // Nama
    if (empty($nama)) {
        $errors['nama'] = "Nama lengkap wajib diisi.";
    } elseif (strlen($nama) < 3) {
        $errors['nama'] = "Nama minimal 3 karakter.";
    }

    // Email
    if (empty($email)) {
        $errors['email'] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid.";
    }

    // Telepon: diawali 08, total 10-13 digit
    if (empty($telepon)) {
        $errors['telepon'] = "Nomor telepon wajib diisi.";
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors['telepon'] = "Format telepon tidak valid. Contoh: 081234567890";
    }

    // Alamat
    if (empty($alamat)) {
        $errors['alamat'] = "Alamat wajib diisi.";
    } elseif (strlen($alamat) < 10) {
        $errors['alamat'] = "Alamat minimal 10 karakter.";
    }

    // Jenis Kelamin
    if (empty($jenis_kelamin)) {
        $errors['jenis_kelamin'] = "Jenis kelamin wajib dipilih.";
    }

    // Tanggal Lahir
    if (empty($tanggal_lahir)) {
        $errors['tanggal_lahir'] = "Tanggal lahir wajib diisi.";
    } else {
        $umur = hitung_umur($tanggal_lahir);
        if ($umur < 10) {
            $errors['tanggal_lahir'] = "Umur minimal 10 tahun (umur saat ini: $umur tahun).";
        }
    }

    // Pekerjaan
    $pekerjaan_valid = ['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'];
    if (empty($pekerjaan)) {
        $errors['pekerjaan'] = "Pekerjaan wajib dipilih.";
    } elseif (!in_array($pekerjaan, $pekerjaan_valid)) {
        $errors['pekerjaan'] = "Pilihan pekerjaan tidak valid.";
    }

    // ===== SUKSES =====
    if (empty($errors)) {
        $success = "Registrasi berhasil! Anggota baru telah ditambahkan.";
    }
}

// Hitung maks tanggal lahir (umur >= 10 tahun)
$max_tgl_lahir = date('Y-m-d', strtotime('-10 years'));
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Header -->
            <div class="text-center mb-4">
                <h2><i class="bi bi-person-plus-fill text-primary"></i> Registrasi Anggota Perpustakaan</h2>
                <p class="text-muted">Isi formulir di bawah ini untuk mendaftar sebagai anggota.</p>
            </div>

            <!-- ===== SUCCESS CARD ===== -->
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <strong><?= $success ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Data Anggota Baru</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-sm-4 fw-semibold text-muted">Nama Lengkap</div>
                        <div class="col-sm-8"><?= $nama ?></div>
                        <div class="col-sm-4 fw-semibold text-muted">Email</div>
                        <div class="col-sm-8"><?= $email ?></div>
                        <div class="col-sm-4 fw-semibold text-muted">Telepon</div>
                        <div class="col-sm-8"><?= $telepon ?></div>
                        <div class="col-sm-4 fw-semibold text-muted">Alamat</div>
                        <div class="col-sm-8"><?= $alamat ?></div>
                        <div class="col-sm-4 fw-semibold text-muted">Jenis Kelamin</div>
                        <div class="col-sm-8"><?= $jenis_kelamin ?></div>
                        <div class="col-sm-4 fw-semibold text-muted">Tanggal Lahir</div>
                        <div class="col-sm-8">
                            <?= date('d F Y', strtotime($tanggal_lahir)) ?>
                            <span class="badge bg-info ms-1"><?= hitung_umur($tanggal_lahir) ?> tahun</span>
                        </div>
                        <div class="col-sm-4 fw-semibold text-muted">Pekerjaan</div>
                        <div class="col-sm-8"><?= $pekerjaan ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ===== FORM REGISTRASI ===== -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus"></i> Form Registrasi Anggota</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="" novalidate>

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : ($nama ? 'is-valid' : '') ?>"
                                   id="nama" name="nama" placeholder="Masukkan nama lengkap"
                                   value="<?= htmlspecialchars($nama) ?>">
                            <div class="form-text">Minimal 3 karakter.</div>
                            <?php if (isset($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ($email ? 'is-valid' : '') ?>"
                                   id="email" name="email" placeholder="contoh@email.com"
                                   value="<?= htmlspecialchars($email) ?>">
                            <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Telepon -->
                        <div class="mb-3">
                            <label for="telepon" class="form-label fw-semibold">
                                Nomor Telepon <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : ($telepon ? 'is-valid' : '') ?>"
                                   id="telepon" name="telepon" placeholder="08xxxxxxxxxx"
                                   value="<?= htmlspecialchars($telepon) ?>">
                            <div class="form-text">Format: 08xxxxxxxxxx (10–13 digit).</div>
                            <?php if (isset($errors['telepon'])): ?>
                            <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold">
                                Alamat <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : ($alamat ? 'is-valid' : '') ?>"
                                      id="alamat" name="alamat" rows="3"
                                      placeholder="Masukkan alamat lengkap (min 10 karakter)"><?= htmlspecialchars($alamat) ?></textarea>
                            <?php if (isset($errors['alamat'])): ?>
                            <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <div class="<?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                           id="laki" value="Laki-laki"
                                           <?= $jenis_kelamin === 'Laki-laki' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="laki">
                                        <i class="bi bi-gender-male text-primary"></i> Laki-laki
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                           id="perempuan" value="Perempuan"
                                           <?= $jenis_kelamin === 'Perempuan' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="perempuan">
                                        <i class="bi bi-gender-female text-danger"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                            <?php if (isset($errors['jenis_kelamin'])): ?>
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle"></i> <?= $errors['jenis_kelamin'] ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label fw-semibold">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control <?= isset($errors['tanggal_lahir']) ? 'is-invalid' : ($tanggal_lahir ? 'is-valid' : '') ?>"
                                   id="tanggal_lahir" name="tanggal_lahir"
                                   max="<?= $max_tgl_lahir ?>"
                                   value="<?= htmlspecialchars($tanggal_lahir) ?>">
                            <div class="form-text">Umur minimal 10 tahun.</div>
                            <?php if (isset($errors['tanggal_lahir'])): ?>
                            <div class="invalid-feedback"><?= $errors['tanggal_lahir'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Pekerjaan -->
                        <div class="mb-3">
                            <label for="pekerjaan" class="form-label fw-semibold">
                                Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= isset($errors['pekerjaan']) ? 'is-invalid' : ($pekerjaan ? 'is-valid' : '') ?>"
                                    id="pekerjaan" name="pekerjaan">
                                <option value="">-- Pilih Pekerjaan --</option>
                                <?php foreach (['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'] as $p): ?>
                                <option value="<?= $p ?>" <?= $pekerjaan === $p ? 'selected' : '' ?>><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['pekerjaan'])): ?>
                            <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Info wajib -->
                        <div class="alert alert-light border mb-3">
                            <small><span class="text-danger">*</span> Field dengan tanda bintang wajib diisi.</small>
                        </div>

                        <!-- Tombol -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Daftarkan Anggota
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                            </button>
                        </div>

                    </form>
                </div><!-- /card-body -->
            </div><!-- /card -->

            <!-- Ringkasan validasi -->
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-3">
                <strong><i class="bi bi-exclamation-triangle-fill"></i> Terdapat <?= count($errors) ?> kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach ($errors as $e): ?>
                    <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

        </div><!-- /col -->
    </div><!-- /row -->
</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>