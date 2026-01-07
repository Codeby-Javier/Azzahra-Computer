-- HR Database Tables for KPI and Arsip

-- KPI Table
CREATE TABLE IF NOT EXISTS `kpi` (
  `kpi_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `status_kerja` varchar(50) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `siklus` varchar(20) NOT NULL DEFAULT 'bulanan',
  `kedisiplinan` int(11) NOT NULL DEFAULT 0,
  `kualitas_kerja` int(11) NOT NULL DEFAULT 0,
  `produktivitas` int(11) NOT NULL DEFAULT 0,
  `kerja_tim` int(11) NOT NULL DEFAULT 0,
  `total` int(11) NOT NULL DEFAULT 0,
  `rata_rata` decimal(5,2) NOT NULL DEFAULT 0.00,
  `kategori` varchar(50) NOT NULL DEFAULT 'Kurang',
  `catatan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kpi_id`),
  KEY `id_karyawan` (`id_karyawan`),
  KEY `periode` (`periode`),
  KEY `siklus` (`siklus`),
  UNIQUE KEY `unique_kpi` (`id_karyawan`, `periode`, `siklus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Laporan Mingguan Table
CREATE TABLE IF NOT EXISTS `laporan_mingguan` (
  `laporan_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `target_mingguan` text,
  `tugas_dilakukan` text,
  `hasil` text,
  `kendala` text,
  `solusi` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`laporan_id`),
  KEY `id_karyawan` (`id_karyawan`),
  KEY `periode` (`periode`),
  UNIQUE KEY `unique_laporan` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- KPI Aggregation View for automatic calculation
CREATE OR REPLACE VIEW `kpi_mingguan_view` AS
SELECT 
  id_karyawan,
  nama_karyawan,
  posisi,
  CONCAT(YEAR(STR_TO_DATE(periode, '%Y-%m-%d')), '-W', LPAD(WEEK(STR_TO_DATE(periode, '%Y-%m-%d'), 1), 2, '0')) as periode_mingguan,
  AVG(kedisiplinan) as avg_kedisiplinan,
  AVG(kualitas_kerja) as avg_kualitas_kerja,
  AVG(produktivitas) as avg_produktivitas,
  AVG(kerja_tim) as avg_kerja_tim,
  AVG(rata_rata) as avg_rata_rata
FROM kpi
WHERE siklus = 'harian'
GROUP BY id_karyawan, nama_karyawan, posisi, periode_mingguan;

CREATE OR REPLACE VIEW `kpi_bulanan_view` AS
SELECT 
  id_karyawan,
  nama_karyawan,
  posisi,
  DATE_FORMAT(STR_TO_DATE(periode, '%Y-%m-%d'), '%Y-%m') as periode_bulanan,
  AVG(kedisiplinan) as avg_kedisiplinan,
  AVG(kualitas_kerja) as avg_kualitas_kerja,
  AVG(produktivitas) as avg_produktivitas,
  AVG(kerja_tim) as avg_kerja_tim,
  AVG(rata_rata) as avg_rata_rata
FROM kpi
WHERE siklus = 'harian'
GROUP BY id_karyawan, nama_karyawan, posisi, periode_bulanan;

CREATE OR REPLACE VIEW `kpi_tahunan_view` AS
SELECT 
  id_karyawan,
  nama_karyawan,
  posisi,
  YEAR(STR_TO_DATE(periode, '%Y-%m-%d')) as periode_tahunan,
  AVG(kedisiplinan) as avg_kedisiplinan,
  AVG(kualitas_kerja) as avg_kualitas_kerja,
  AVG(produktivitas) as avg_produktivitas,
  AVG(kerja_tim) as avg_kerja_tim,
  AVG(rata_rata) as avg_rata_rata
FROM kpi
WHERE siklus = 'harian'
GROUP BY id_karyawan, nama_karyawan, posisi, periode_tahunan;

-- Arsip Table
CREATE TABLE IF NOT EXISTS `arsip` (
  `arsip_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `no_hp` varchar(20),
  `tipe_detail` varchar(100),
  `kerusakan` text,
  `alamat` varchar(255),
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`arsip_id`),
  KEY `tipe` (`tipe`),
  KEY `tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Absensi Table
CREATE TABLE IF NOT EXISTS `absensi` (
  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `jam_masuk` time,
  `jam_pulang` time,
  `keterangan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`absensi_id`),
  KEY `tanggal` (`tanggal`),
  KEY `id_karyawan` (`id_karyawan`),
  UNIQUE KEY `unique_absensi` (`tanggal`, `id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Karyawan Table (menggunakan kry_level untuk jabatan)
CREATE TABLE IF NOT EXISTS `karyawan` (
  `kry_kode` varchar(20) NOT NULL,
  `kry_nama` varchar(100) NOT NULL,
  `kry_level` varchar(50) NOT NULL,
  `kry_email` varchar(100),
  `kry_telp` varchar(20),
  `kry_alamat` text,
  `kry_status` varchar(20) DEFAULT 'Aktif',
  `kry_tgl_masuk` date,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kry_kode`),
  KEY `kry_nama` (`kry_nama`),
  KEY `kry_level` (`kry_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Data Karyawan (untuk testing)
INSERT INTO `karyawan` (`kry_kode`, `kry_nama`, `kry_level`, `kry_email`, `kry_telp`, `kry_status`, `kry_tgl_masuk`) VALUES
('KRY001', 'Ahmad Fauzi', 'Teknisi Senior', 'ahmad.fauzi@azzahra.com', '081234567890', 'Aktif', '2020-01-15'),
('KRY002', 'Siti Nurhaliza', 'Customer Service', 'siti.nur@azzahra.com', '081234567891', 'Aktif', '2020-03-20'),
('KRY003', 'Budi Santoso', 'Teknisi', 'budi.santoso@azzahra.com', '081234567892', 'Aktif', '2021-06-10'),
('KRY004', 'Dewi Lestari', 'Admin', 'dewi.lestari@azzahra.com', '081234567893', 'Aktif', '2021-08-05'),
('KRY005', 'Eko Prasetyo', 'Teknisi', 'eko.prasetyo@azzahra.com', '081234567894', 'Aktif', '2022-02-14'),
('KRY006', 'Fitri Handayani', 'Marketing', 'fitri.handayani@azzahra.com', '081234567895', 'Aktif', '2022-05-20'),
('KRY007', 'Gunawan Wijaya', 'Supervisor', 'gunawan.wijaya@azzahra.com', '081234567896', 'Aktif', '2019-11-01'),
('KRY008', 'Hani Safitri', 'Customer Service', 'hani.safitri@azzahra.com', '081234567897', 'Aktif', '2023-01-10')
ON DUPLICATE KEY UPDATE kry_nama=VALUES(kry_nama);

