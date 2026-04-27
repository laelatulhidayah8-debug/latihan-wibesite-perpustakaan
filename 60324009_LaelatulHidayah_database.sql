-- ============================================================
-- TUGAS 2: DESAIN DATABASE LENGKAP
-- Nama    : Laelatul Hidayah
-- NIM     : 60324009
-- Kelas   : INF-A - Pemrograman Web II
-- File    : 60324009_LaelatulHidayah_database.sql
-- ============================================================

-- Hapus dan buat ulang database
DROP DATABASE IF EXISTS perpustakaan_v2;
CREATE DATABASE perpustakaan_v2
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE perpustakaan_v2;

-- ============================================================
-- DDL: BUAT SEMUA TABEL
-- ============================================================

-- -------------------------------------------------------------
-- Tabel: kategori_buku
-- -------------------------------------------------------------
CREATE TABLE kategori_buku (
    id_kategori   INT           AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50)   NOT NULL UNIQUE,
    deskripsi     TEXT,
    is_deleted    BOOLEAN       DEFAULT FALSE,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------
-- Tabel: penerbit
-- -------------------------------------------------------------
CREATE TABLE penerbit (
    id_penerbit   INT           AUTO_INCREMENT PRIMARY KEY,
    nama_penerbit VARCHAR(100)  NOT NULL,
    alamat        TEXT,
    telepon       VARCHAR(15),
    email         VARCHAR(100),
    is_deleted    BOOLEAN       DEFAULT FALSE,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------
-- Tabel: rak  (BONUS)
-- -------------------------------------------------------------
CREATE TABLE rak (
    id_rak     INT          AUTO_INCREMENT PRIMARY KEY,
    kode_rak   VARCHAR(10)  NOT NULL UNIQUE,
    lokasi     VARCHAR(100),
    kapasitas  INT          DEFAULT 50,
    is_deleted BOOLEAN      DEFAULT FALSE,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------
-- Tabel: buku  (versi baru dengan FK)
-- -------------------------------------------------------------
CREATE TABLE buku (
    id_buku      INT            AUTO_INCREMENT PRIMARY KEY,
    kode_buku    VARCHAR(10)    NOT NULL UNIQUE,
    judul        VARCHAR(200)   NOT NULL,
    id_kategori  INT            NOT NULL,
    pengarang    VARCHAR(100),
    id_penerbit  INT            NOT NULL,
    tahun_terbit INT(11),
    isbn         VARCHAR(20),
    harga        DECIMAL(10,2)  NOT NULL DEFAULT 0,
    stok         INT(11)        NOT NULL DEFAULT 0,
    id_rak       INT,
    deskripsi    TEXT,
    is_deleted   BOOLEAN        DEFAULT FALSE,
    created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_buku(id_kategori),
    FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit),
    FOREIGN KEY (id_rak)      REFERENCES rak(id_rak)
);

-- -------------------------------------------------------------
-- Tabel: anggota
-- -------------------------------------------------------------
CREATE TABLE anggota (
    id_anggota     INT           AUTO_INCREMENT PRIMARY KEY,
    kode_anggota   VARCHAR(10)   NOT NULL UNIQUE,
    nama           VARCHAR(100)  NOT NULL,
    email          VARCHAR(100)  UNIQUE NOT NULL NULL,
    telepon        VARCHAR(15),
    alamat         VARCHAR(200),
    tanggal_lahir  DATE,
    jenis_kelamin  ENUM('Laki-laki','Perempuan') NOT NULL,
    pekerjaan      VARCHAR(50),
    tanggal_daftar DATE,
    status         ENUM('Aktif','Nonaktif')      DEFAULT 'Aktif',
    is_deleted     BOOLEAN                       DEFAULT FALSE,
    created_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
                                 ON UPDATE CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------
-- Tabel: transaksi
-- -------------------------------------------------------------
CREATE TABLE transaksi (
    id_transaksi       INT            AUTO_INCREMENT PRIMARY KEY,
    id_buku            INT            NOT NULL,
    id_anggota         INT            NOT NULL,
    tanggal_pinjam     DATE           NOT NULL,
    tanggal_kembali    DATE,
    tanggal_harus_kembali DATE        NOT NULL,
    status             ENUM('Dipinjam','Dikembalikan','Terlambat') DEFAULT 'Dipinjam',
    denda              DECIMAL(10,2)  DEFAULT 0,
    is_deleted         BOOLEAN        DEFAULT FALSE,
    created_at         TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
                                      ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_buku)     REFERENCES buku(id_buku),
    FOREIGN KEY (id_anggota)  REFERENCES anggota(id_anggota)
);


-- ============================================================
-- DML: INSERT DATA SAMPLE
-- ============================================================

-- -------------------------------------------------------------
-- Data: kategori_buku  (minimal 5)
-- -------------------------------------------------------------
INSERT INTO kategori_buku (nama_kategori, deskripsi) VALUES
('Programming',  'Buku-buku tentang pemrograman dan pengembangan perangkat lunak'),
('Database',     'Buku-buku tentang sistem basis data dan SQL'),
('Web Design',   'Buku-buku tentang desain dan pengembangan antarmuka web'),
('Networking',   'Buku-buku tentang jaringan komputer dan keamanan'),
('Informatika',  'Buku-buku umum ilmu komputer dan informatika');

-- -------------------------------------------------------------
-- Data: penerbit  (minimal 5)
-- -------------------------------------------------------------
INSERT INTO penerbit (nama_penerbit, alamat, telepon, email) VALUES
('Informatika',  'Jl. Merdeka No. 1, Bandung',       '022-1234567', 'info@penerbit-informatika.com'),
('Graha Ilmu',   'Jl. Cempaka No. 5, Yogyakarta',    '0274-654321', 'cs@graha-ilmu.com'),
('Erlangga',     'Jl. Ahmad Yani No. 10, Jakarta',   '021-9876543', 'info@erlangga.co.id'),
('Andi Offset',  'Jl. Beo No. 38-40, Yogyakarta',    '0274-565655', 'cs@andipublisher.com'),
('Elex Media',   'Jl. Palmerah Barat No. 29, Jakarta','021-5365966', 'info@elexmedia.co.id');

-- -------------------------------------------------------------
-- Data: rak  (BONUS)
-- -------------------------------------------------------------
INSERT INTO rak (kode_rak, lokasi, kapasitas) VALUES
('RAK-A1', 'Lantai 1 - Baris A, Rak 1', 40),
('RAK-A2', 'Lantai 1 - Baris A, Rak 2', 40),
('RAK-B1', 'Lantai 1 - Baris B, Rak 1', 50),
('RAK-B2', 'Lantai 1 - Baris B, Rak 2', 50),
('RAK-C1', 'Lantai 2 - Baris C, Rak 1', 35);

-- -------------------------------------------------------------
-- Data: buku  (minimal 15, dengan FK yang benar)
-- id_kategori: 1=Programming, 2=Database, 3=Web Design, 4=Networking, 5=Informatika
-- id_penerbit: 1=Informatika, 2=Graha Ilmu, 3=Erlangga, 4=Andi Offset, 5=Elex Media
-- -------------------------------------------------------------
INSERT INTO buku
  (kode_buku, judul, id_kategori, pengarang, id_penerbit, tahun_terbit, isbn, harga, stok, id_rak, deskripsi)
VALUES
-- Programming
('BK-001', 'Pemrograman PHP untuk Pemula',          1, 'Budi Raharjo',   1, 2023, '978-602-1234-55-1',  75000.00, 10, 1,
 'Buku panduan lengkap belajar PHP dari dasar hingga mahir'),
('BK-003', 'Laravel Framework Advanced',            1, 'Siti Aminah',    1, 2024, '978-602-1234-56-3', 125000.00,  8, 1,
 'Teknik advanced development dengan Laravel framework'),
('BK-006', 'PHP Web Services',                      1, 'Budi Raharjo',   1, 2024, '978-602-1234-56-6',  90000.00, 12, 1,
 'Membangun RESTful API dengan PHP'),
('BK-008', 'JavaScript Modern',                     1, 'Siti Aminah',    5, 2023, '978-602-1234-56-8',  80000.00,  0, 1,
 'JavaScript ES6+ untuk web development modern'),
('BK-011', 'React Native Development',              1, 'Ahmad Yani',     5, 2024, '978-602-1234-56-11',105000.00, 6, 2,
 'Membangun aplikasi mobile cross-platform dengan React Native'),
-- Database
('BK-002', 'Mastering MySQL Database',              2, 'Andi Nugroho',   2, 2022, '978-602-1234-55-2',  95000.00,  5, 2,
 'Panduan komprehensif administrasi dan optimasi MySQL'),
('BK-007', 'PostgreSQL Advanced',                   2, 'Ahmad Yani',     2, 2024, '978-602-1234-56-7', 115000.00,  7, 2,
 'Teknik advanced PostgreSQL untuk enterprise'),
('BK-012', 'MongoDB untuk Developer',               2, 'Andi Nugroho',   4, 2023, '978-602-1234-56-12', 88000.00, 9, 2,
 'Panduan lengkap NoSQL database dengan MongoDB'),
-- Web Design
('BK-004', 'Web Design Principles',                 3, 'Dedi Santoso',   4, 2023, '978-602-1234-55-4',  65000.00, 15, 3,
 'Prinsip dan best practice dalam desain web modern'),
('BK-013', 'UI/UX Design dengan Figma',             3, 'Rina Wijaya',    3, 2024, '978-602-1234-56-13', 72000.00, 11, 3,
 'Panduan lengkap mendesain UI/UX menggunakan Figma'),
-- Networking
('BK-005', 'Network Security Fundamentals',         4, 'Rina Wijaya',    3, 2023, '978-602-1234-55-5', 110000.00,  3, 4,
 'Dasar-dasar keamanan jaringan komputer'),
('BK-014', 'Cisco Networking Essentials',           4, 'Dedi Santoso',   2, 2022, '978-602-1234-56-14', 98000.00,  4, 4,
 'Konfigurasi jaringan menggunakan perangkat Cisco'),
-- Informatika
('BK-009', 'Algoritma dan Struktur Data',           5, 'Siti Aminah',    1, 2022, '978-602-1234-56-9',  85000.00, 14, 5,
 'Konsep algoritma dan struktur data untuk pemula'),
('BK-010', 'Kecerdasan Buatan Dasar',               5, 'Budi Raharjo',   4, 2023, '978-602-1234-56-10', 92000.00,  8, 5,
 'Pengenalan konsep Artificial Intelligence dan Machine Learning'),
('BK-015', 'Python untuk Data Science',             5, 'Ahmad Yani',     5, 2024, '978-602-1234-56-15',118000.00,  6, 5,
 'Analisis data dan visualisasi menggunakan Python');

-- -------------------------------------------------------------
-- Data: anggota
-- -------------------------------------------------------------
INSERT INTO anggota
  (kode_anggota, nama, email, telepon, alamat, tanggal_lahir, jenis_kelamin, pekerjaan, tanggal_daftar, status)
VALUES
('AGT-001','Budi Santoso',  'budi.santoso@email.com',  '081234567890','Jl. Merdeka No. 10, Jakarta',  '1995-05-15','Laki-laki',  'Mahasiswa','2024-01-10','Aktif'),
('AGT-002','Siti Nurhaliza','siti.nur@email.com',       '081234567891','Jl. Sudirman No. 25, Bandung', '1998-08-20','Perempuan',  'Pegawai',  '2024-01-15','Aktif'),
('AGT-003','Ahmad Dhani',   'ahmad.dhani@email.com',    '081234567892','Jl. Gatot Subroto No. 5, Surabaya','1992-03-10','Laki-laki','Pegawai', '2024-02-01','Aktif'),
('AGT-004','Dewi Lestari',  'dewi.lestari@email.com',   '081234567893','Jl. Ahmad Yani No. 30, Yogyakarta','2000-12-05','Perempuan','Mahasiswa','2024-02-10','Aktif'),
('AGT-005','Rizky Fabian',  'rizky.fab@email.com',      '081234567894','Jl. Diponegoro No. 15, Semarang',  '1997-07-18','Laki-laki','Pelajar',  '2024-02-15','Nonaktif');

-- -------------------------------------------------------------
-- Data: transaksi
-- -------------------------------------------------------------
INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, tanggal_kembali, tanggal_harus_kembali, status) VALUES
(1, 1, '2024-02-01', '2024-02-08', '2024-02-08', 'Dikembalikan'),
(2, 2, '2024-02-03', NULL,         '2024-02-10', 'Dipinjam'),
(3, 1, '2024-01-25', '2024-02-01', '2024-02-01', 'Dikembalikan');


-- ============================================================
-- QUERY JOIN DAN LAPORAN
-- ============================================================

-- Q1: JOIN - Tampilkan buku dengan nama kategori dan penerbit
SELECT
    b.kode_buku,
    b.judul,
    k.nama_kategori,
    b.pengarang,
    p.nama_penerbit,
    b.tahun_terbit,
    CONCAT('Rp ', FORMAT(b.harga, 0)) AS harga,
    b.stok
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit       p ON b.id_penerbit = p.id_penerbit
WHERE b.is_deleted = FALSE
ORDER BY k.nama_kategori, b.judul;

-- Q2: Jumlah buku per kategori
SELECT
    k.nama_kategori,
    COUNT(b.id_buku) AS jumlah_buku,
    SUM(b.stok)      AS total_stok
FROM kategori_buku k
LEFT JOIN buku b ON k.id_kategori = b.id_kategori
               AND b.is_deleted = FALSE
GROUP BY k.id_kategori, k.nama_kategori
ORDER BY jumlah_buku DESC;

-- Q3: Jumlah buku per penerbit
SELECT
    p.nama_penerbit,
    COUNT(b.id_buku)          AS jumlah_buku,
    SUM(b.harga * b.stok)     AS total_nilai_inventaris
FROM penerbit p
LEFT JOIN buku b ON p.id_penerbit = b.id_penerbit
                AND b.is_deleted = FALSE
GROUP BY p.id_penerbit, p.nama_penerbit
ORDER BY jumlah_buku DESC;

-- Q4: Detail lengkap buku (kategori + penerbit + rak)
SELECT
    b.kode_buku                           AS 'Kode',
    b.judul                               AS 'Judul Buku',
    k.nama_kategori                       AS 'Kategori',
    b.pengarang                           AS 'Pengarang',
    p.nama_penerbit                       AS 'Penerbit',
    b.tahun_terbit                        AS 'Tahun',
    b.isbn                                AS 'ISBN',
    CONCAT('Rp ', FORMAT(b.harga, 0))     AS 'Harga',
    b.stok                                AS 'Stok',
    r.kode_rak                            AS 'Rak',
    r.lokasi                              AS 'Lokasi Rak',
    CASE
        WHEN b.stok  = 0 THEN 'Habis'
        WHEN b.stok <= 4 THEN 'Perlu Restock'
        ELSE 'Tersedia'
    END                                   AS 'Status Stok'
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit       p ON b.id_penerbit = p.id_penerbit
LEFT JOIN rak       r ON b.id_rak      = r.id_rak
WHERE b.is_deleted = FALSE
ORDER BY k.nama_kategori, b.judul;


-- ============================================================
-- BONUS: STORED PROCEDURES
-- ============================================================

DELIMITER $$

-- Procedure: Tambah buku baru
CREATE PROCEDURE sp_tambah_buku(
    IN p_kode_buku    VARCHAR(10),
    IN p_judul        VARCHAR(200),
    IN p_id_kategori  INT,
    IN p_pengarang    VARCHAR(100),
    IN p_id_penerbit  INT,
    IN p_tahun_terbit INT,
    IN p_isbn         VARCHAR(20),
    IN p_harga        DECIMAL(10,2),
    IN p_stok         INT
)
BEGIN
    INSERT INTO buku (kode_buku, judul, id_kategori, pengarang, id_penerbit,
                      tahun_terbit, isbn, harga, stok)
    VALUES (p_kode_buku, p_judul, p_id_kategori, p_pengarang, p_id_penerbit,
            p_tahun_terbit, p_isbn, p_harga, p_stok);
    SELECT LAST_INSERT_ID() AS id_buku_baru;
END$$

-- Procedure: Soft delete buku
CREATE PROCEDURE sp_hapus_buku(IN p_id_buku INT)
BEGIN
    UPDATE buku SET is_deleted = TRUE WHERE id_buku = p_id_buku;
    SELECT ROW_COUNT() AS baris_terpengaruh;
END$$

-- Procedure: Laporan stok rendah
CREATE PROCEDURE sp_laporan_stok_rendah(IN p_batas_stok INT)
BEGIN
    SELECT b.kode_buku, b.judul, k.nama_kategori, b.stok,
           CONCAT('Rp ', FORMAT(b.harga, 0)) AS harga
    FROM buku b
    JOIN kategori_buku k ON b.id_kategori = k.id_kategori
    WHERE b.stok <= p_batas_stok
      AND b.is_deleted = FALSE
    ORDER BY b.stok ASC;
END$$

DELIMITER ;

-- Contoh pemanggilan stored procedure:
-- CALL sp_laporan_stok_rendah(5);
