-- ============================================================
-- TUGAS 1: EKSPLORASI DATABASE DENGAN QUERY
-- Nama    : Laelatul Hidayah
-- NIM     : 60324009
-- Kelas   : INF-A - Pemrograman Web II
-- ============================================================

USE perpustakaan;

-- ============================================================
-- BAGIAN 1: STATISTIK BUKU (5 Query)
-- ============================================================

-- 1.1 Total buku seluruhnya
SELECT COUNT(*) AS total_buku
FROM buku;

-- 1.2 Total nilai inventaris (sum harga × stok)
SELECT SUM(harga * stok) AS total_nilai_inventaris
FROM buku;

-- 1.3 Rata-rata harga buku
SELECT AVG(harga) AS rata_rata_harga
FROM buku;

-- 1.4 Buku termahal (tampilkan judul dan harga)
SELECT judul, harga
FROM buku
ORDER BY harga DESC
LIMIT 1;

-- 1.5 Buku dengan stok terbanyak
SELECT judul, stok
FROM buku
ORDER BY stok DESC
LIMIT 1;


-- ============================================================
-- BAGIAN 2: FILTER DAN PENCARIAN (5 Query)
-- ============================================================

-- 2.1 Semua buku kategori Programming yang harga < 100.000
SELECT *
FROM buku
WHERE kategori = 'Programming'
  AND harga < 100000;

-- 2.2 Buku yang judulnya mengandung kata "PHP" atau "MySQL"
SELECT *
FROM buku
WHERE judul LIKE '%PHP%'
   OR judul LIKE '%MySQL%';

-- 2.3 Buku yang terbit tahun 2024
SELECT *
FROM buku
WHERE tahun_terbit = 2024;

-- 2.4 Buku yang stoknya antara 5-10
SELECT *
FROM buku
WHERE stok BETWEEN 5 AND 10;

-- 2.5 Buku yang pengarangnya "Budi Raharjo"
SELECT *
FROM buku
WHERE pengarang = 'Budi Raharjo';


-- ============================================================
-- BAGIAN 3: GROUPING DAN AGREGASI (3 Query)
-- ============================================================

-- 3.1 Jumlah buku per kategori (dengan total stok per kategori)
SELECT kategori,
       COUNT(*)   AS jumlah_buku,
       SUM(stok)  AS total_stok
FROM buku
GROUP BY kategori
ORDER BY jumlah_buku DESC;

-- 3.2 Rata-rata harga per kategori
SELECT kategori,
       AVG(harga) AS rata_rata_harga
FROM buku
GROUP BY kategori
ORDER BY rata_rata_harga DESC;

-- 3.3 Kategori dengan total nilai inventaris terbesar
SELECT kategori,
       SUM(harga * stok) AS total_nilai_inventaris
FROM buku
GROUP BY kategori
ORDER BY total_nilai_inventaris DESC
LIMIT 1;


-- ============================================================
-- BAGIAN 4: UPDATE DATA (2 Query)
-- ============================================================

-- 4.1 Naikkan harga semua buku kategori Programming sebesar 5%
UPDATE buku
SET harga = harga * 1.05
WHERE kategori = 'Programming';

-- Verifikasi hasil update 4.1
SELECT judul, kategori, harga
FROM buku
WHERE kategori = 'Programming';

-- 4.2 Tambah stok 10 untuk semua buku yang stoknya < 5
UPDATE buku
SET stok = stok + 10
WHERE stok < 5;

-- Verifikasi hasil update 4.2
SELECT judul, stok
FROM buku
ORDER BY stok ASC;


-- ============================================================
-- BAGIAN 5: LAPORAN KHUSUS (2 Query)
-- ============================================================

-- 5.1 Daftar buku yang perlu restocking (stok < 5)
SELECT kode_buku,
       judul,
       pengarang,
       kategori,
       stok
FROM buku
WHERE stok < 5
ORDER BY stok ASC;

-- 5.2 Top 5 buku termahal
SELECT judul,
       pengarang,
       kategori,
       CONCAT('Rp ', FORMAT(harga, 0)) AS harga_format
FROM buku
ORDER BY harga DESC
LIMIT 5;
