-- ============================================
-- TABEL KARYAWAN - WAJIB UNTUK HR MODULE
-- ============================================
-- File ini berisi tabel karyawan yang WAJIB ada
-- untuk fitur input data HR (Absensi, KPI, Arsip)
-- ============================================

-- 1. Buat Tabel Karyawan (menggunakan kry_level untuk jabatan)
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

-- 2. Insert Data Karyawan Sample (8 Karyawan)
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

-- 3. Verifikasi Data
SELECT 'Tabel karyawan berhasil dibuat!' as status;
SELECT COUNT(*) as total_karyawan FROM karyawan;
SELECT kry_kode, kry_nama, kry_level FROM karyawan ORDER BY kry_kode;