<?php
/**
 * COMPLETE DATABASE FIX - Memperbaiki semua tabel agar sesuai dengan controller
 * Jalankan file ini di browser: http://localhost/Azzahra-Computer-main/COMPLETE_DB_FIX.php
 */

echo "<pre>";
echo "=== COMPLETE DATABASE FIX FOR CV_AZZAHRA ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$conn = new mysqli('localhost', 'root', '', 'cv_azzahra');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. DISABLE ONLY_FULL_GROUP_BY
echo "=== STEP 1: FIXING SQL MODE ===\n";
$conn->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
$conn->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
echo "✓ SQL mode fixed\n\n";

// 2. DROP ALL EXISTING TABLES
echo "=== STEP 2: DROPPING EXISTING TABLES ===\n";
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$result = $conn->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
while ($row = $result->fetch_array()) {
    $conn->query("DROP TABLE IF EXISTS `{$row[0]}`");
    echo "Dropped: {$row[0]}\n";
}
// Drop views too
$views = ['kpi_mingguan_view', 'kpi_bulanan_view', 'kpi_tahunan_view'];
foreach ($views as $view) {
    $conn->query("DROP VIEW IF EXISTS `$view`");
}
$conn->query("SET FOREIGN_KEY_CHECKS = 1");
echo "✓ All tables dropped\n\n";

echo "=== STEP 3: CREATING ALL TABLES WITH COMPLETE STRUCTURE ===\n";

// ============================================
// KARYAWAN TABLE - Complete with all columns
// ============================================
$sql = "CREATE TABLE `karyawan` (
  `kry_kode` varchar(20) NOT NULL,
  `kry_nik` varchar(50) DEFAULT NULL,
  `kry_username` varchar(50) DEFAULT NULL,
  `kry_pswd` varchar(255) DEFAULT NULL,
  `kry_nama` varchar(100) NOT NULL,
  `kry_tempat` varchar(100) DEFAULT NULL,
  `kry_tgl_lahir` date DEFAULT NULL,
  `kry_alamat` text,
  `kry_tlp` varchar(20) DEFAULT NULL,
  `kry_email` varchar(100) DEFAULT NULL,
  `kry_level` varchar(50) NOT NULL,
  `kry_status` varchar(20) DEFAULT 'Aktif',
  `kry_tgl_masuk` date DEFAULT NULL,
  `kry_tgl_keluar` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kry_kode`),
  UNIQUE KEY `kry_nik` (`kry_nik`),
  UNIQUE KEY `kry_username` (`kry_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ karyawan\n"; else echo "✗ karyawan: {$conn->error}\n";

// ============================================
// COSTOMER TABLE - Complete with ALL columns from Service.php save_trans()
// ============================================
$sql = "CREATE TABLE `costomer` (
  `id_costomer` varchar(20) NOT NULL,
  `cos_nama` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `cos_tgl_lahir` date DEFAULT NULL,
  `cos_alamat` text,
  `cos_hp` varchar(20) DEFAULT NULL,
  `cos_tipe` varchar(100) DEFAULT NULL,
  `cos_model` varchar(100) DEFAULT NULL,
  `cos_no_seri` varchar(100) DEFAULT NULL,
  `cos_asesoris` text,
  `cos_status` varchar(20) DEFAULT NULL,
  `cos_pswd_type` varchar(20) DEFAULT 'text',
  `cos_pswd` varchar(255) DEFAULT NULL,
  `cos_pswd_canvas` text,
  `cos_keluhan` text,
  `cos_keterangan` text,
  `cos_tanggal` date DEFAULT NULL,
  `cos_jam` time DEFAULT NULL,
  `cos_poin` int(11) DEFAULT 0,
  `cos_gambar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_costomer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ costomer\n"; else echo "✗ costomer: {$conn->error}\n";

// ============================================
// TRANSAKSI TABLE - Complete with ALL columns
// ============================================
$sql = "CREATE TABLE `transaksi` (
  `trans_kode` varchar(20) NOT NULL,
  `cos_kode` varchar(20) NOT NULL,
  `kry_kode` varchar(20) DEFAULT NULL,
  `trans_tanggal` date DEFAULT NULL,
  `trans_total` decimal(15,2) DEFAULT 0.00,
  `trans_discount` decimal(15,2) DEFAULT 0.00,
  `trans_status` varchar(30) DEFAULT 'Baru',
  `trans_kerusakan` text,
  `trans_keterangan` text,
  `last_follow_up` datetime DEFAULT NULL,
  `follow_up_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`trans_kode`),
  KEY `cos_kode` (`cos_kode`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ transaksi\n"; else echo "✗ transaksi: {$conn->error}\n";

// ============================================
// TRANSAKSI_DETAIL TABLE - Complete
// ============================================
$sql = "CREATE TABLE `transaksi_detail` (
  `dtl_id` int(11) NOT NULL AUTO_INCREMENT,
  `dtl_kode` varchar(20) DEFAULT NULL,
  `trans_kode` varchar(20) NOT NULL,
  `kry_kode` varchar(20) DEFAULT NULL,
  `dtl_tanggal` date DEFAULT NULL,
  `dtl_jam` time DEFAULT NULL,
  `dtl_jenis_bayar` varchar(20) DEFAULT NULL,
  `dtl_bank` varchar(50) DEFAULT NULL,
  `dtl_jml_bayar` decimal(15,2) DEFAULT 0.00,
  `dtl_status` varchar(20) DEFAULT 'DP',
  `dtl_stt_stor` varchar(20) DEFAULT 'Menunggu',
  `dtl_bukti` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dtl_id`),
  KEY `trans_kode` (`trans_kode`),
  KEY `dtl_kode` (`dtl_kode`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ transaksi_detail\n"; else echo "✗ transaksi_detail: {$conn->error}\n";

// ============================================
// TRANSAKSI_RETURN TABLE
// ============================================
$sql = "CREATE TABLE `transaksi_return` (
  `ret_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `dtl_kode` varchar(20) DEFAULT NULL,
  `ret_jml` decimal(15,2) DEFAULT 0.00,
  `ret_tanggal` date DEFAULT NULL,
  `ret_jam` time DEFAULT NULL,
  `ret_keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ret_id`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ transaksi_return\n"; else echo "✗ transaksi_return: {$conn->error}\n";

// ============================================
// TINDAKAN TABLE - Complete with tdkn_jam
// ============================================
$sql = "CREATE TABLE `tindakan` (
  `tdkn_kode` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `tdkn_nama` varchar(255) DEFAULT NULL,
  `tdkn_ket` text,
  `tdkn_qty` int(11) DEFAULT 1,
  `tdkn_harga` decimal(15,2) DEFAULT 0.00,
  `tdkn_subtot` decimal(15,2) DEFAULT 0.00,
  `tdkn_tanggal` date DEFAULT NULL,
  `tdkn_jam` time DEFAULT NULL,
  `tdkn_status` varchar(20) DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tdkn_kode`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ tindakan\n"; else echo "✗ tindakan: {$conn->error}\n";

// ============================================
// ORDER_LIST TABLE - Complete with ALL columns from Service.php
// ============================================
$sql = "CREATE TABLE `order_list` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) DEFAULT NULL,
  `cos_kode` varchar(20) NOT NULL,
  `kry_kode` varchar(20) DEFAULT NULL,
  `trans_total` decimal(15,2) DEFAULT 0.00,
  `trans_discount` decimal(15,2) DEFAULT 0.00,
  `trans_tanggal` date DEFAULT NULL,
  `trans_status` varchar(50) DEFAULT 'itemSubmitted',
  `merek` varchar(100) DEFAULT NULL,
  `device` varchar(100) DEFAULT NULL,
  `status_garansi` varchar(50) DEFAULT NULL,
  `seri` varchar(100) DEFAULT NULL,
  `ket_keluhan` text,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `cos_kode` (`cos_kode`),
  KEY `trans_kode` (`trans_kode`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ order_list\n"; else echo "✗ order_list: {$conn->error}\n";

// ============================================
// ORDER_PART_MARKING TABLE
// ============================================
$sql = "CREATE TABLE `order_part_marking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `is_ordered` varchar(10) DEFAULT 'no',
  `marking_data` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ order_part_marking\n"; else echo "✗ order_part_marking: {$conn->error}\n";

// ============================================
// VOCER TABLE - Complete with voc_jam
// ============================================
$sql = "CREATE TABLE `vocer` (
  `voc_kode` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `voc_jumlah` decimal(15,2) DEFAULT 0.00,
  `voc_status` varchar(10) DEFAULT 'ON',
  `voc_tanggal` date DEFAULT NULL,
  `voc_jam` time DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voc_kode`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ vocer\n"; else echo "✗ vocer: {$conn->error}\n";


// ============================================
// VOUCHER TABLE
// ============================================
$sql = "CREATE TABLE `voucher` (
  `voucher_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(50) NOT NULL,
  `voucher_name` varchar(100) DEFAULT NULL,
  `voucher_type` varchar(20) DEFAULT 'percentage',
  `voucher_value` decimal(15,2) DEFAULT 0.00,
  `voucher_status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`),
  UNIQUE KEY `voucher_code` (`voucher_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ voucher\n"; else echo "✗ voucher: {$conn->error}\n";

// ============================================
// PRODUK TABLE
// ============================================
$sql = "CREATE TABLE `produk` (
  `produk_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `harga_beli` decimal(15,2) DEFAULT 0.00,
  `harga_jual` decimal(15,2) DEFAULT 0.00,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(20) DEFAULT 'pcs',
  `deskripsi` text,
  `status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`produk_id`),
  UNIQUE KEY `kode_barang` (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ produk\n"; else echo "✗ produk: {$conn->error}\n";

// ============================================
// DISCOUNT TABLE
// ============================================
$sql = "CREATE TABLE `discount` (
  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_kode` varchar(20) NOT NULL,
  `disc_nama` varchar(100) DEFAULT NULL,
  `disc_persen` decimal(5,2) DEFAULT 0.00,
  `disc_nominal` decimal(15,2) DEFAULT 0.00,
  `disc_status` varchar(20) DEFAULT 'Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`disc_id`),
  UNIQUE KEY `disc_kode` (`disc_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ discount\n"; else echo "✗ discount: {$conn->error}\n";

// ============================================
// USERS TABLE
// ============================================
$sql = "CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ users\n"; else echo "✗ users: {$conn->error}\n";

// ============================================
// ABSENSI TABLE
// ============================================
$sql = "CREATE TABLE `absensi` (
  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`absensi_id`),
  UNIQUE KEY `unique_absensi` (`tanggal`, `id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ absensi\n"; else echo "✗ absensi: {$conn->error}\n";

// ============================================
// KPI TABLE
// ============================================
$sql = "CREATE TABLE `kpi` (
  `kpi_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `status_kerja` varchar(50) DEFAULT NULL,
  `periode` varchar(20) DEFAULT NULL,
  `siklus` varchar(20) DEFAULT 'bulanan',
  `kedisiplinan` int(11) DEFAULT 0,
  `kualitas_kerja` int(11) DEFAULT 0,
  `produktivitas` int(11) DEFAULT 0,
  `kerja_tim` int(11) DEFAULT 0,
  `total` int(11) DEFAULT 0,
  `rata_rata` decimal(5,2) DEFAULT 0.00,
  `kategori` varchar(50) DEFAULT 'Kurang',
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kpi_id`),
  UNIQUE KEY `unique_kpi` (`id_karyawan`, `periode`, `siklus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ kpi\n"; else echo "✗ kpi: {$conn->error}\n";

// ============================================
// LAPORAN_MINGGUAN TABLE
// ============================================
$sql = "CREATE TABLE `laporan_mingguan` (
  `laporan_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `periode` varchar(20) DEFAULT NULL,
  `target_mingguan` text,
  `tugas_dilakukan` text,
  `hasil` text,
  `kendala` text,
  `solusi` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`laporan_id`),
  UNIQUE KEY `unique_laporan` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ laporan_mingguan\n"; else echo "✗ laporan_mingguan: {$conn->error}\n";

// ============================================
// ARSIP TABLE
// ============================================
$sql = "CREATE TABLE `arsip` (
  `arsip_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tipe_detail` varchar(100) DEFAULT NULL,
  `kerusakan` text,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`arsip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ arsip\n"; else echo "✗ arsip: {$conn->error}\n";

// ============================================
// MOU TABLE
// ============================================
$sql = "CREATE TABLE `mou` (
  `mou_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `grand_total` decimal(15,2) DEFAULT 0.00,
  `kry_kode` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mou_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ mou\n"; else echo "✗ mou: {$conn->error}\n";

// ============================================
// MOU_ITEMS TABLE
// ============================================
$sql = "CREATE TABLE `mou_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `mou_id` int(11) NOT NULL,
  `item_no` int(11) DEFAULT NULL,
  `spesifikasi` text,
  `qty` decimal(10,2) DEFAULT 0.00,
  `harga` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`item_id`),
  KEY `mou_id` (`mou_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if ($conn->query($sql)) echo "✓ mou_items\n"; else echo "✗ mou_items: {$conn->error}\n";

echo "\n=== STEP 4: CREATING VIEWS ===\n";

$sql = "CREATE VIEW `kpi_mingguan_view` AS
SELECT id_karyawan, nama_karyawan, posisi,
CONCAT(YEAR(STR_TO_DATE(periode, '%Y-%m-%d')), '-W', LPAD(WEEK(STR_TO_DATE(periode, '%Y-%m-%d'), 1), 2, '0')) as periode_mingguan,
AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_mingguan";
if ($conn->query($sql)) echo "✓ kpi_mingguan_view\n"; else echo "✗ kpi_mingguan_view: {$conn->error}\n";

$sql = "CREATE VIEW `kpi_bulanan_view` AS
SELECT id_karyawan, nama_karyawan, posisi,
DATE_FORMAT(STR_TO_DATE(periode, '%Y-%m-%d'), '%Y-%m') as periode_bulanan,
AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_bulanan";
if ($conn->query($sql)) echo "✓ kpi_bulanan_view\n"; else echo "✗ kpi_bulanan_view: {$conn->error}\n";

$sql = "CREATE VIEW `kpi_tahunan_view` AS
SELECT id_karyawan, nama_karyawan, posisi,
YEAR(STR_TO_DATE(periode, '%Y-%m-%d')) as periode_tahunan,
AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_tahunan";
if ($conn->query($sql)) echo "✓ kpi_tahunan_view\n"; else echo "✗ kpi_tahunan_view: {$conn->error}\n";

echo "\n=== STEP 5: INSERTING SAMPLE DATA ===\n";

// Insert admin and sample karyawan
$password_admin = password_hash('admin', PASSWORD_DEFAULT);
$password_default = password_hash('12345', PASSWORD_DEFAULT);

$sql = "INSERT INTO `karyawan` (`kry_kode`, `kry_nik`, `kry_username`, `kry_pswd`, `kry_nama`, `kry_tempat`, `kry_tgl_lahir`, `kry_alamat`, `kry_tlp`, `kry_level`, `kry_status`, `kry_tgl_masuk`) VALUES
('ADM001', '1234567890123456', 'admin2', '$password_admin', 'Administrator', 'Tegal', '1990-01-01', 'Jl. Admin No. 1', '081234567890', 'Admin', 'Aktif', CURDATE()),
('KRY001', '3328010101900001', 'kry001', '$password_default', 'Ahmad Fauzi', 'Tegal', '1990-05-15', 'Jl. Teknisi No. 1', '081234567891', 'Teknisi Senior', 'Aktif', '2020-01-15'),
('KRY002', '3328010101910002', 'kry002', '$password_default', 'Siti Nurhaliza', 'Tegal', '1991-03-20', 'Jl. CS No. 2', '081234567892', 'Customer Service', 'Aktif', '2020-03-20'),
('KRY003', '3328010101920003', 'kry003', '$password_default', 'Budi Santoso', 'Tegal', '1992-06-10', 'Jl. Teknisi No. 3', '081234567893', 'Teknisi', 'Aktif', '2021-06-10'),
('KRY004', '3328010101930004', 'kry004', '$password_default', 'Dewi Lestari', 'Tegal', '1993-08-05', 'Jl. Admin No. 4', '081234567894', 'Admin', 'Aktif', '2021-08-05'),
('KRY005', '3328010101940005', 'kry005', '$password_default', 'Eko Prasetyo', 'Tegal', '1994-02-14', 'Jl. Teknisi No. 5', '081234567895', 'Teknisi', 'Aktif', '2022-02-14'),
('KRY006', '3328010101950006', 'kry006', '$password_default', 'Fitri Handayani', 'Tegal', '1995-05-20', 'Jl. Kasir No. 6', '081234567896', 'Kasir', 'Aktif', '2022-05-20'),
('KRY007', '3328010101960007', 'kry007', '$password_default', 'Gunawan Wijaya', 'Tegal', '1988-11-01', 'Jl. HR No. 7', '081234567897', 'HR', 'Aktif', '2019-11-01'),
('KRY008', '3328010101970008', 'kry008', '$password_default', 'Hani Safitri', 'Tegal', '1997-01-10', 'Jl. CS No. 8', '081234567898', 'Customer Service', 'Aktif', '2023-01-10')";

if ($conn->query($sql)) {
    echo "✓ Sample karyawan data inserted (9 employees)\n";
} else {
    echo "✗ Error inserting karyawan: {$conn->error}\n";
}

echo "\n=== STEP 6: VERIFICATION ===\n";

$result = $conn->query("SHOW TABLES");
$count = 0;
echo "\nTables created:\n";
while ($row = $result->fetch_array()) {
    $count++;
    $table = $row[0];
    $cnt_result = $conn->query("SELECT COUNT(*) as cnt FROM `$table`");
    if ($cnt_result) {
        $cnt = $cnt_result->fetch_assoc()['cnt'];
        echo "  $count. $table ($cnt records)\n";
    } else {
        echo "  $count. $table (view)\n";
    }
}
echo "\nTotal: $count tables/views\n";

echo "\n=== STEP 7: COLUMN VERIFICATION ===\n";

// Verify costomer columns
$result = $conn->query("DESCRIBE costomer");
echo "\nCOSTOMER table columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

// Verify transaksi columns
$result = $conn->query("DESCRIBE transaksi");
echo "\nTRANSAKSI table columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

// Verify order_list columns
$result = $conn->query("DESCRIBE order_list");
echo "\nORDER_LIST table columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

// Verify tindakan columns
$result = $conn->query("DESCRIBE tindakan");
echo "\nTINDAKAN table columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

echo "\n=== STEP 8: LOGIN CREDENTIALS ===\n";
echo "\n┌─────────────────────────────────────────────────────────────┐\n";
echo "│                    LOGIN CREDENTIALS                        │\n";
echo "├─────────────────────────────────────────────────────────────┤\n";
echo "│ ADMIN:                                                      │\n";
echo "│   Username: admin2                                          │\n";
echo "│   Password: admin                                           │\n";
echo "├─────────────────────────────────────────────────────────────┤\n";
echo "│ KARYAWAN (password: 12345):                                 │\n";
echo "│   kry001 - Ahmad Fauzi (Teknisi Senior)                     │\n";
echo "│   kry002 - Siti Nurhaliza (Customer Service)                │\n";
echo "│   kry003 - Budi Santoso (Teknisi)                           │\n";
echo "│   kry004 - Dewi Lestari (Admin)                             │\n";
echo "│   kry005 - Eko Prasetyo (Teknisi)                           │\n";
echo "│   kry006 - Fitri Handayani (Kasir)                          │\n";
echo "│   kry007 - Gunawan Wijaya (HR)                              │\n";
echo "│   kry008 - Hani Safitri (Customer Service)                  │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$conn->close();

echo "\n" . str_repeat("=", 60) . "\n";
echo "       DATABASE FIX COMPLETE!\n";
echo str_repeat("=", 60) . "\n";
echo "\nAll tables have been recreated with COMPLETE structure.\n";
echo "CRUD operations should now work correctly.\n";
echo "</pre>";
?>
