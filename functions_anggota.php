<?php
// ========== LIBRARY FUNCTIONS ANGGOTA PERPUSTAKAAN ==========

// 1. Hitung total anggota
function hitung_total_anggota($anggota_list) {
    return count($anggota_list);
}

// 2. Hitung anggota aktif
function hitung_anggota_aktif($anggota_list) {
    $count = 0;
    foreach ($anggota_list as $a) {
        if ($a["status"] === "Aktif") $count++;
    }
    return $count;
}

// 3. Hitung rata-rata pinjaman
function hitung_rata_rata_pinjaman($anggota_list) {
    if (count($anggota_list) === 0) return 0;
    $total = 0;
    foreach ($anggota_list as $a) {
        $total += $a["total_pinjaman"];
    }
    return round($total / count($anggota_list), 1);
}

// 4. Cari anggota by ID
function cari_anggota_by_id($anggota_list, $id) {
    foreach ($anggota_list as $a) {
        if ($a["id"] === $id) return $a;
    }
    return null;
}

// 5. Cari anggota teraktif (total_pinjaman tertinggi)
function cari_anggota_teraktif($anggota_list) {
    if (empty($anggota_list)) return null;
    $teraktif = $anggota_list[0];
    foreach ($anggota_list as $a) {
        if ($a["total_pinjaman"] > $teraktif["total_pinjaman"]) {
            $teraktif = $a;
        }
    }
    return $teraktif;
}

// 6. Filter by status
function filter_by_status($anggota_list, $status) {
    $hasil = [];
    foreach ($anggota_list as $a) {
        if ($a["status"] === $status) $hasil[] = $a;
    }
    return $hasil;
}

// 7. Validasi email
function validasi_email($email) {
    if (empty($email)) return false;
    if (strpos($email, '@') === false) return false;
    if (strpos($email, '.') === false) return false;
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 8. Format tanggal Indonesia
function format_tanggal_indo($tanggal) {
    $bulan = [
        1  => "Januari", 2  => "Februari", 3  => "Maret",
        4  => "April",   5  => "Mei",       6  => "Juni",
        7  => "Juli",    8  => "Agustus",   9  => "September",
        10 => "Oktober", 11 => "November",  12 => "Desember"
    ];
    $parts = explode("-", $tanggal); // [yyyy, mm, dd]
    return (int)$parts[2] . " " . $bulan[(int)$parts[1]] . " " . $parts[0];
}

// BONUS: Sort anggota by nama A-Z
function sort_by_nama($anggota_list) {
    usort($anggota_list, fn($a, $b) => strcmp($a["nama"], $b["nama"]));
    return $anggota_list;
}

// BONUS: Search anggota by nama (partial match, case-insensitive)
function search_by_nama($anggota_list, $keyword) {
    $hasil = [];
    foreach ($anggota_list as $a) {
        if (stripos($a["nama"], $keyword) !== false) {
            $hasil[] = $a;
        }
    }
    return $hasil;
}
?>