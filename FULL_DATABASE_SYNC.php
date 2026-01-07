<?php
echo "=== FULL DATABASE SYNCHRONIZATION ===\n";
echo "Analyzing all models and creating complete database structure...\n\n";

$conn = new mysqli('localhost', 'root', '', 'cv_azzahra');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. DISABLE ONLY_FULL_GROUP_BY
echo "=== STEP 1: FIXING SQL MODE ===\n";
$conn->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
$conn->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
echo "✓ SQL mode ONLY_FULL_GROUP_BY disabled\n\n";

// 2. DROP AND RECREATE ALL TABLES
echo "=== STEP 2: CREATING COMPLETE DATABASE STRUCTURE ===\n";

$tables_sql = [];

// COSTOMER TABLE
$tables_sql['costomer'] = "CREATE TABLE IF NOT EXISTS `costomer` (
  `id_costomer` varchar(20) NOT NULL,
  `cos_nama` varchar(100) NOT NULL,
  `cos_alamat` text,
  `cos_hp` varchar(20),
  `cos_tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_costomer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// KARYAWAN TABLE
$tables_sql['karyawan'] = "CREATE TABLE IF NOT EXISTS `karyawan` (
  `kry_kode` varchar(20) NOT NULL,
  `kry_username` varchar(50) DEFAULT NULL,
  `kry_pswd` varchar(255) DEFAULT NULL,
  `kry_nama` varchar(100) NOT NULL,
  `kry_level` varchar(50) NOT NULL,
  `kry_email` varchar(100),
  `kry_telp` varchar(20),
  `kry_alamat` text,
  `kry_status` varchar(20) DEFAULT 'Aktif',
  `kry_tgl_masuk` date,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kry_kode`),
  UNIQUE KEY `kry_username` (`kry_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TRANSAKSI TABLE
$tables_sql['transaksi'] = "CREATE TABLE IF NOT EXISTS `transaksi` (
  `trans_kode` varchar(20) NOT NULL,
  `cos_kode` varchar(20) NOT NULL,
  `kry_kode` varchar(20) DEFAULT NULL,
  `trans_tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `cos_tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `cos_jam` time DEFAULT NULL,
  `trans_total` decimal(15,2) DEFAULT 0.00,
  `trans_status` varchar(30) DEFAULT 'Baru',
  `trans_kerusakan` text,
  `trans_keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`trans_kode`),
  KEY `cos_kode` (`cos_kode`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TRANSAKSI_DETAIL TABLE
$tables_sql['transaksi_detail'] = "CREATE TABLE IF NOT EXISTS `transaksi_detail` (
  `dtl_id` int(11) NOT NULL AUTO_INCREMENT,
  `dtl_kode` varchar(20) DEFAULT NULL,
  `trans_kode` varchar(20) NOT NULL,
  `dtl_tanggal` date NOT NULL,
  `dtl_jenis_bayar` varchar(20) NOT NULL,
  `dtl_bank` varchar(50) DEFAULT NULL,
  `dtl_jml_bayar` decimal(15,2) DEFAULT 0.00,
  `dtl_status` varchar(20) DEFAULT 'DP',
  `dtl_stt_stor` varchar(20) DEFAULT 'Menunggu',
  `dtl_bukti` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dtl_id`),
  KEY `trans_kode` (`trans_kode`),
  KEY `dtl_kode` (`dtl_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TRANSAKSI_RETURN TABLE
$tables_sql['transaksi_return'] = "CREATE TABLE IF NOT EXISTS `transaksi_return` (
  `ret_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `ret_tanggal` date NOT NULL,
  `ret_jml` decimal(15,2) DEFAULT 0.00,
  `ret_keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ret_id`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TINDAKAN TABLE
$tables_sql['tindakan'] = "CREATE TABLE IF NOT EXISTS `tindakan` (
  `tdkn_kode` varchar(20) NOT NULL,
  `trans_kode` varchar(20) NOT NULL,
  `tdkn_nama` varchar(255) NOT NULL,
  `tdkn_qty` int(11) DEFAULT 1,
  `tdkn_harga` decimal(15,2) DEFAULT 0.00,
  `tdkn_subtot` decimal(15,2) DEFAULT 0.00,
  `tdkn_ket` text,
  `tdkn_tanggal` date DEFAULT NULL,
  `tdkn_status` varchar(20) DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tdkn_kode`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// ORDER_LIST TABLE
$tables_sql['order_list'] = "CREATE TABLE IF NOT EXISTS `order_list` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `cos_kode` varchar(20) NOT NULL,
  `trans_kode` varchar(20) DEFAULT NULL,
  `kry_kode` varchar(20) DEFAULT NULL,
  `device` varchar(100) DEFAULT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `seri` varchar(100) DEFAULT NULL,
  `status_garansi` varchar(50) DEFAULT NULL,
  `trans_status` varchar(50) DEFAULT 'pending',
  `trans_total` decimal(15,2) DEFAULT 0.00,
  `trans_tanggal` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `cos_kode` (`cos_kode`),
  KEY `trans_kode` (`trans_kode`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// ORDER_PART_MARKING TABLE
$tables_sql['order_part_marking'] = "CREATE TABLE IF NOT EXISTS `order_part_marking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_kode` varchar(20) NOT NULL,
  `marking_data` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// VOCER TABLE
$tables_sql['vocer'] = "CREATE TABLE IF NOT EXISTS `vocer` (
  `voc_kode` varchar(20) NOT NULL,
  `trans_kode` varchar(20) NOT NULL,
  `voc_jumlah` decimal(15,2) DEFAULT 0.00,
  `voc_status` varchar(10) DEFAULT 'ON',
  `voc_tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voc_kode`),
  KEY `trans_kode` (`trans_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// VOUCHER TABLE
$tables_sql['voucher'] = "CREATE TABLE IF NOT EXISTS `voucher` (
  `voucher_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(50) NOT NULL,
  `voucher_name` varchar(100) NOT NULL,
  `voucher_type` varchar(20) DEFAULT 'percentage',
  `voucher_value` decimal(15,2) DEFAULT 0.00,
  `voucher_status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`),
  UNIQUE KEY `voucher_code` (`voucher_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// PRODUK TABLE
$tables_sql['produk'] = "CREATE TABLE IF NOT EXISTS `produk` (
  `produk_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
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

// DISCOUNT TABLE
$tables_sql['discount'] = "CREATE TABLE IF NOT EXISTS `discount` (
  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_kode` varchar(20) NOT NULL,
  `disc_nama` varchar(100) NOT NULL,
  `disc_persen` decimal(5,2) DEFAULT 0.00,
  `disc_nominal` decimal(15,2) DEFAULT 0.00,
  `disc_status` varchar(20) DEFAULT 'Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`disc_id`),
  UNIQUE KEY `disc_kode` (`disc_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// USERS TABLE
$tables_sql['users'] = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `level` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// HR TABLES
$tables_sql['absensi'] = "CREATE TABLE IF NOT EXISTS `absensi` (
  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`absensi_id`),
  UNIQUE KEY `unique_absensi` (`tanggal`, `id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$tables_sql['kpi'] = "CREATE TABLE IF NOT EXISTS `kpi` (
  `kpi_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `status_kerja` varchar(50) NOT NULL,
  `periode` varchar(20) NOT NULL,
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

$tables_sql['laporan_mingguan'] = "CREATE TABLE IF NOT EXISTS `laporan_mingguan` (
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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`laporan_id`),
  UNIQUE KEY `unique_laporan` (`id_karyawan`, `periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$tables_sql['arsip'] = "CREATE TABLE IF NOT EXISTS `arsip` (
  `arsip_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tipe_detail` varchar(100) DEFAULT NULL,
  `kerusakan` text,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`arsip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$tables_sql['mou'] = "CREATE TABLE IF NOT EXISTS `mou` (
  `mou_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `customer` varchar(255) NOT NULL,
  `grand_total` decimal(15,2) DEFAULT 0.00,
  `kry_kode` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mou_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$tables_sql['mou_items'] = "CREATE TABLE IF NOT EXISTS `mou_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `mou_id` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `spesifikasi` text NOT NULL,
  `qty` decimal(10,2) DEFAULT 0.00,
  `harga` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`item_id`),
  KEY `mou_id` (`mou_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Execute all table creations
foreach ($tables_sql as $table_name => $sql) {
    // Drop table first to recreate with correct structure
    $conn->query("DROP TABLE IF EXISTS `$table_name`");
    
    if ($conn->query($sql)) {
        echo "✓ Table '$table_name' created\n";
    } else {
        echo "✗ Error creating '$table_name': " . $conn->error . "\n";
    }
}

// 3. CREATE VIEWS
echo "\n=== STEP 3: CREATING VIEWS ===\n";

$views_sql = [
    "CREATE OR REPLACE VIEW `kpi_mingguan_view` AS
    SELECT id_karyawan, nama_karyawan, posisi,
    CONCAT(YEAR(STR_TO_DATE(periode, '%Y-%m-%d')), '-W', LPAD(WEEK(STR_TO_DATE(periode, '%Y-%m-%d'), 1), 2, '0')) as periode_mingguan,
    AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
    AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
    FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_mingguan",
    
    "CREATE OR REPLACE VIEW `kpi_bulanan_view` AS
    SELECT id_karyawan, nama_karyawan, posisi,
    DATE_FORMAT(STR_TO_DATE(periode, '%Y-%m-%d'), '%Y-%m') as periode_bulanan,
    AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
    AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
    FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_bulanan",
    
    "CREATE OR REPLACE VIEW `kpi_tahunan_view` AS
    SELECT id_karyawan, nama_karyawan, posisi,
    YEAR(STR_TO_DATE(periode, '%Y-%m-%d')) as periode_tahunan,
    AVG(kedisiplinan) as avg_kedisiplinan, AVG(kualitas_kerja) as avg_kualitas_kerja,
    AVG(produktivitas) as avg_produktivitas, AVG(kerja_tim) as avg_kerja_tim, AVG(rata_rata) as avg_rata_rata
    FROM kpi WHERE siklus = 'harian' GROUP BY id_karyawan, nama_karyawan, posisi, periode_tahunan"
];

foreach ($views_sql as $view_sql) {
    if ($conn->query($view_sql)) {
        echo "✓ View created\n";
    } else {
        echo "✗ Error: " . $conn->error . "\n";
    }
}

// 4. INSERT SAMPLE KARYAWAN DATA
echo "\n=== STEP 4: INSERTING SAMPLE DATA ===\n";

$sample_karyawan = "INSERT INTO `karyawan` (`kry_kode`, `kry_username`, `kry_pswd`, `kry_nama`, `kry_level`, `kry_status`, `kry_tgl_masuk`) VALUES
('ADM001', 'admin2', '" . password_hash('admin', PASSWORD_DEFAULT) . "', 'Administrator', 'Admin', 'Aktif', CURDATE()),
('KRY001', 'kry001', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Ahmad Fauzi', 'Teknisi Senior', 'Aktif', '2020-01-15'),
('KRY002', 'kry002', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Siti Nurhaliza', 'Customer Service', 'Aktif', '2020-03-20'),
('KRY003', 'kry003', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Budi Santoso', 'Teknisi', 'Aktif', '2021-06-10'),
('KRY004', 'kry004', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Dewi Lestari', 'Admin', 'Aktif', '2021-08-05'),
('KRY005', 'kry005', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Eko Prasetyo', 'Teknisi', 'Aktif', '2022-02-14'),
('KRY006', 'kry006', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Fitri Handayani', 'Kasir', 'Aktif', '2022-05-20'),
('KRY007', 'kry007', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Gunawan Wijaya', 'HR', 'Aktif', '2019-11-01'),
('KRY008', 'kry008', '" . password_hash('12345', PASSWORD_DEFAULT) . "', 'Hani Safitri', 'Customer Service', 'Aktif', '2023-01-10')";

if ($conn->query($sample_karyawan)) {
    echo "✓ Sample karyawan data inserted (9 employees)\n";
} else {
    echo "✗ Error: " . $conn->error . "\n";
}

// 5. VERIFY ALL TABLES
echo "\n=== STEP 5: VERIFICATION ===\n";

$result = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

echo "Total tables created: " . count($tables) . "\n";
foreach ($tables as $table) {
    $count_result = $conn->query("SELECT COUNT(*) as cnt FROM `$table`");
    if ($count_result) {
        $count = $count_result->fetch_assoc()['cnt'];
        echo "  ✓ $table ($count records)\n";
    } else {
        echo "  ✓ $table (view)\n";
    }
}

// 6. VERIFY ADMIN USER
echo "\n=== STEP 6: ADMIN USER CHECK ===\n";
$admin_check = $conn->query("SELECT kry_kode, kry_username, kry_nama, kry_level FROM karyawan WHERE kry_username = 'admin2'");
if ($admin_check && $admin_check->num_rows > 0) {
    $admin = $admin_check->fetch_assoc();
    echo "✓ Admin user ready:\n";
    echo "  Username: {$admin['kry_username']}\n";
    echo "  Password: admin\n";
    echo "  Name: {$admin['kry_nama']}\n";
    echo "  Level: {$admin['kry_level']}\n";
}

$conn->close();

echo "\n" . str_repeat("=", 50) . "\n";
echo "DATABASE SYNCHRONIZATION COMPLETE!\n";
echo str_repeat("=", 50) . "\n";
echo "\nLogin with:\n";
echo "  Username: admin2\n";
echo "  Password: admin\n";
echo "\nAll tables have been created with correct structure.\n";
echo "SQL mode ONLY_FULL_GROUP_BY has been disabled.\n";
?>
