## Tugas 1: Eksplorasi Database

Database yang digunakan: `perpustakaan`

### Bagian 1 - Statistik Buku

#### 1.1 Total Buku Seluruhnya
```sql
SELECT COUNT(*) AS total_buku FROM buku;
```
![Total Buku](images/tugas1/01_total_buku.png)

---

#### 1.2 Total Nilai Inventaris (harga × stok)
```sql
SELECT SUM(harga * stok) AS total_nilai_inventaris FROM buku;
```
![Nilai Inventaris](images/tugas1/02_nilai_inventaris.png)

---

#### 1.3 Rata-rata Harga Buku
```sql
SELECT AVG(harga) AS rata_rata_harga FROM buku;
```
![Rata-rata Harga](images/tugas1/03_rata_harga.png)

---

#### 1.4 Buku Termahal
```sql
SELECT judul, harga FROM buku ORDER BY harga DESC LIMIT 1;
```
![Buku Termahal](images/tugas1/04_buku_termahal.png)

---

#### 1.5 Buku dengan Stok Terbanyak
```sql
SELECT judul, stok FROM buku ORDER BY stok DESC LIMIT 1;
```
![Stok Terbanyak](images/tugas1/05_stok_terbanyak.png)

---

### Bagian 2 - Filter dan Pencarian

#### 2.1 Buku Kategori Programming dengan Harga < 100.000
```sql
SELECT * FROM buku WHERE kategori = 'Programming' AND harga < 100000;
```
![Filter Programming](images/tugas1/06_filter_programming.png)

---

#### 2.2 Buku yang Judulnya Mengandung "PHP" atau "MySQL"
```sql
SELECT * FROM buku WHERE judul LIKE '%PHP%' OR judul LIKE '%MySQL%';
```
![Filter PHP MySQL](images/tugas1/07_filter_php_mysql.png)

---

#### 2.3 Buku Terbit Tahun 2024
```sql
SELECT * FROM buku WHERE tahun_terbit = 2024;
```
![Filter 2024](images/tugas1/08_filter_2024.png)

---

#### 2.4 Buku dengan Stok antara 5-10
```sql
SELECT * FROM buku WHERE stok BETWEEN 5 AND 10;
```
![Filter Stok](images/tugas1/09_filter_stok.png)

---

#### 2.5 Buku Pengarang "Budi Raharjo"
```sql
SELECT * FROM buku WHERE pengarang = 'Budi Raharjo';
```
![Filter Pengarang](images/tugas1/10_filter_pengarang.png)

---

### Bagian 3 - Grouping dan Agregasi

#### 3.1 Jumlah Buku per Kategori (dengan Total Stok)
```sql
SELECT kategori, COUNT(*) AS jumlah_buku, SUM(stok) AS total_stok
FROM buku GROUP BY kategori ORDER BY jumlah_buku DESC;
```
![Group Kategori](images/tugas1/11_group_kategori.png)

---

#### 3.2 Rata-rata Harga per Kategori
```sql
SELECT kategori, AVG(harga) AS rata_rata_harga
FROM buku GROUP BY kategori ORDER BY rata_rata_harga DESC;
```
![Avg Harga](images/tugas1/12_avg_harga.png)

---

#### 3.3 Kategori dengan Total Nilai Inventaris Terbesar
```sql
SELECT kategori, SUM(harga * stok) AS total_nilai_inventaris
FROM buku GROUP BY kategori ORDER BY total_nilai_inventaris DESC LIMIT 1;
```
![Inventaris Terbesar](images/tugas1/13_inventaris_terbesar.png)

---

### Bagian 4 - Update Data

#### 4.1 Naikkan Harga Buku Programming sebesar 5%
```sql
UPDATE buku SET harga = harga * 1.05 WHERE kategori = 'Programming';
```
![Update Harga](images/tugas1/14_update_harga.png)

---

#### 4.2 Tambah Stok 10 untuk Buku dengan Stok < 5
```sql
UPDATE buku SET stok = stok + 10 WHERE stok < 5;
```
![Update Stok](images/tugas1/15_update_stok.png)

---

### Bagian 5 - Laporan Khusus

#### 5.1 Daftar Buku yang Perlu Restocking (stok < 5)
```sql
SELECT kode_buku, judul, pengarang, kategori, stok
FROM buku WHERE stok < 5 ORDER BY stok ASC;
```
![Restocking](images/tugas1/16_restocking.png)

---

#### 5.2 Top 5 Buku Termahal
```sql
SELECT judul, pengarang, kategori, CONCAT('Rp ', FORMAT(harga, 0)) AS harga_format
FROM buku ORDER BY harga DESC LIMIT 5;
```
![Top 5 Termahal](images/tugas1/17_top5_termahal.png)

---

## Tugas 2: Desain Database Lengkap

Database yang digunakan: `perpustakaan_v2`

### Struktur Tabel

#### Tabel `kategori_buku`
![Struktur Kategori Buku](images/tugas2/01_struktur_kategori_buku.png)

#### Tabel `penerbit`
![Struktur Penerbit](images/tugas2/02_struktur_penerbit.png)

#### Tabel `rak` *(Bonus)*
![Struktur Rak](images/tugas2/03_struktur_rak.png)

#### Tabel `buku`
![Struktur Buku](images/tugas2/04_struktur_buku.png)

#### Tabel `anggota`
![Struktur Anggota](images/tugas2/05_struktur_anggota.png)

#### Tabel `transaksi`
![Struktur Transaksi](images/tugas2/06_struktur_transaksi.png)

---

### Data di Setiap Tabel

#### Data `kategori_buku`
![Data Kategori Buku](images/tugas2/07_data_kategori_buku.png)

#### Data `penerbit`
![Data Penerbit](images/tugas2/08_data_penerbit.png)

#### Data `rak`
![Data Rak](images/tugas2/09_data_rak.png)

#### Data `buku`
![Data Buku](images/tugas2/10_data_buku.png)

#### Data `anggota`
![Data Anggota](images/tugas2/11_data_anggota.png)

#### Data `transaksi`
![Data Transaksi](images/tugas2/12_data_transaksi.png)

---

### Hasil Query JOIN

#### JOIN Buku dengan Kategori dan Penerbit
```sql
SELECT b.kode_buku, b.judul, k.nama_kategori, b.pengarang,
       p.nama_penerbit, b.tahun_terbit, b.harga, b.stok
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
WHERE b.is_deleted = FALSE;
```
![Join Buku](images/tugas2/13_join_buku_kategori_penerbit.png)

---

#### Jumlah Buku per Kategori
```sql
SELECT k.nama_kategori, COUNT(b.id_buku) AS jumlah_buku, SUM(b.stok) AS total_stok
FROM kategori_buku k
LEFT JOIN buku b ON k.id_kategori = b.id_kategori
GROUP BY k.id_kategori, k.nama_kategori;
```
![Jumlah per Kategori](images/tugas2/14_jumlah_per_kategori.png)

---

#### Jumlah Buku per Penerbit
```sql
SELECT p.nama_penerbit, COUNT(b.id_buku) AS jumlah_buku
FROM penerbit p
LEFT JOIN buku b ON p.id_penerbit = b.id_penerbit
GROUP BY p.id_penerbit, p.nama_penerbit;
```
![Jumlah per Penerbit](images/tugas2/15_jumlah_per_penerbit.png)

---

#### Detail Lengkap Buku (Kategori + Penerbit + Rak)
```sql
SELECT b.kode_buku, b.judul, k.nama_kategori, p.nama_penerbit,
       r.kode_rak, r.lokasi,
       CASE WHEN b.stok = 0 THEN 'Habis'
            WHEN b.stok <= 4 THEN 'Perlu Restock'
            ELSE 'Tersedia' END AS status_stok
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
LEFT JOIN rak r ON b.id_rak = r.id_rak
WHERE b.is_deleted = FALSE;
```
![Detail Lengkap](images/tugas2/16_detail_lengkap.png)

---

## ERD (Entity Relationship Diagram)

Berikut relasi antar tabel pada database `perpustakaan_v2`:

```
kategori_buku  ──┐
                 ├──< buku >──┐
penerbit       ──┘            │
                              ├──< transaksi >── anggota
rak ───────────────────────── ┘
```

**Keterangan relasi:**
- `kategori_buku` → `buku` : One to Many (1 kategori memiliki banyak buku)
- `penerbit` → `buku` : One to Many (1 penerbit menerbitkan banyak buku)
- `rak` → `buku` : One to Many (1 rak menyimpan banyak buku)
- `buku` → `transaksi` : One to Many (1 buku bisa dipinjam berkali-kali)
- `anggota` → `transaksi` : One to Many (1 anggota bisa meminjam banyak buku)



*Tugas Pemrograman Web II - Pertemuan 6 | UIN K.H. Abdurrahman Wahid Pekalongan*
