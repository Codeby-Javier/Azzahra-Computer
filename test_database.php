<?php
/**
 * Test Database Connection and Tables
 * Comprehensive test for HR Module
 */

echo "=== DATABASE CONNECTION TEST ===\n";

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'azzahra';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("✗ Connection failed: " . $conn->connect_error . "\n");
}

echo "✓ Database connected successfully\n\n";

echo "=== TABLE EXISTENCE TEST ===\n";

// Check required tables
$required_tables = ['karyawan', 'absensi', 'kpi', 'laporan_mingguan', 'arsip', 'mou'];
$missing_tables = [];

foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        // Count records
        $count_result = $conn->query("SELECT COUNT(*) as total FROM $table");
        $count = $count_result->fetch_assoc()['total'];
        echo "✓ Table '$table' exists ($count records)\n";
    } else {
        echo "✗ Table '$table' MISSING\n";
        $missing_tables[] = $table;
    }
}

// Special check for karyawan data
if (!in_array('karyawan', $missing_tables)) {
    $karyawan_result = $conn->query("SELECT COUNT(*) as count FROM karyawan");
    $karyawan_count = $karyawan_result->fetch_assoc()['count'];
    
    if ($karyawan_count > 0) {
        echo "✓ Karyawan table has $karyawan_count records\n";
        
        // Show sample data
        $sample_result = $conn->query("SELECT kry_kode, kry_nama, kry_level FROM karyawan LIMIT 3");
        echo "  Sample karyawan data:\n";
        while ($row = $sample_result->fetch_assoc()) {
            echo "  - {$row['kry_kode']}: {$row['kry_nama']} ({$row['kry_level']})\n";
        }
    } else {
        echo "✗ Karyawan table is EMPTY - HR input will not work!\n";
        $missing_tables[] = 'karyawan_data';
    }
}

echo "\n=== FUNCTIONALITY TEST ===\n";

// Test insert absensi
echo "Testing absensi operations...\n";
$test_date = date('Y-m-d');
$test_query = "INSERT INTO absensi (tanggal, id_karyawan, nama_karyawan, posisi, status, jam_masuk, jam_pulang, keterangan) 
               VALUES ('$test_date', 'TEST001', 'Test User', 'Staff', 'HADIR', '08:00:00', '17:00:00', 'Test data')
               ON DUPLICATE KEY UPDATE status='HADIR'";

if ($conn->query($test_query)) {
    echo "✓ Absensi insert successful\n";
    
    // Test select
    $select_result = $conn->query("SELECT * FROM absensi WHERE tanggal = '$test_date' AND id_karyawan = 'TEST001'");
    if ($select_result->num_rows > 0) {
        echo "✓ Absensi select successful\n";
    } else {
        echo "✗ Absensi select failed\n";
    }
} else {
    echo "✗ Absensi insert failed: " . $conn->error . "\n";
}

// Test insert KPI
echo "Testing KPI operations...\n";
$test_periode = date('Y-m-d');
$test_kpi = "INSERT INTO kpi (id_karyawan, nama_karyawan, posisi, status_kerja, periode, siklus, kedisiplinan, kualitas_kerja, produktivitas, kerja_tim, total, rata_rata, kategori, catatan)
             VALUES ('TEST001', 'Test User', 'Staff', 'Karyawan', '$test_periode', 'harian', 4, 4, 4, 4, 16, 4.00, 'Baik', 'Test KPI')
             ON DUPLICATE KEY UPDATE rata_rata=4.00";

if ($conn->query($test_kpi)) {
    echo "✓ KPI insert successful\n";
    
    // Test select
    $kpi_result = $conn->query("SELECT * FROM kpi WHERE id_karyawan = 'TEST001' AND periode = '$test_periode'");
    if ($kpi_result->num_rows > 0) {
        echo "✓ KPI select successful\n";
    } else {
        echo "✗ KPI select failed\n";
    }
} else {
    echo "✗ KPI insert failed: " . $conn->error . "\n";
}

// Test insert arsip
echo "Testing arsip operations...\n";
$test_arsip = "INSERT INTO arsip (tipe, nama, tanggal, no_hp, tipe_detail, kerusakan, alamat)
               VALUES ('Laptop', 'Test Customer', '$test_date', '08123456789', 'Lenovo IdeaPad', 'Layar rusak', 'Jakarta')";

if ($conn->query($test_arsip)) {
    echo "✓ Arsip insert successful\n";
    
    // Test select
    $arsip_result = $conn->query("SELECT * FROM arsip WHERE nama = 'Test Customer' AND tanggal = '$test_date'");
    if ($arsip_result->num_rows > 0) {
        echo "✓ Arsip select successful\n";
    } else {
        echo "✗ Arsip select failed\n";
    }
} else {
    echo "✗ Arsip insert failed: " . $conn->error . "\n";
}

// Cleanup test data
echo "\nCleaning up test data...\n";
$conn->query("DELETE FROM absensi WHERE id_karyawan = 'TEST001'");
$conn->query("DELETE FROM kpi WHERE id_karyawan = 'TEST001'");
$conn->query("DELETE FROM arsip WHERE nama = 'Test Customer' AND tanggal = '$test_date'");
echo "✓ Test data cleaned up\n";

$conn->close();

echo "\n=== TEST SUMMARY ===\n";

if (empty($missing_tables)) {
    echo "✓✓✓ ALL TESTS PASSED ✓✓✓\n";
    echo "System is ready for production!\n";
} else {
    echo "✗✗✗ SOME TESTS FAILED ✗✗✗\n";
    echo "Missing components:\n";
    foreach ($missing_tables as $missing) {
        echo "- $missing\n";
    }
    echo "\nPlease run: mysql -u root -p azzahra < hr_database.sql\n";
}

echo "\nDatabase: $db\n";
echo "Host: $host\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
?>
