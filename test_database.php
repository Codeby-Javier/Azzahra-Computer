<?php
/**
 * Test Database Connection and Tables
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'azzahra';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "✓ Connected to database\n\n";

// Test tables
$tables = ['absensi', 'kpi', 'laporan_mingguan', 'arsip', 'karyawan'];

echo "Checking tables:\n";
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        // Count records
        $count_result = $conn->query("SELECT COUNT(*) as total FROM $table");
        $count = $count_result->fetch_assoc()['total'];
        echo "✓ Table '$table' exists ($count records)\n";
    } else {
        echo "✗ Table '$table' NOT found\n";
    }
}

echo "\n";

// Test insert absensi
echo "Testing absensi insert...\n";
$test_date = date('Y-m-d');
$test_query = "INSERT INTO absensi (tanggal, id_karyawan, nama_karyawan, posisi, status, jam_masuk, jam_pulang, keterangan) 
               VALUES ('$test_date', 'TEST001', 'Test User', 'Staff', 'HADIR', '08:00:00', '17:00:00', 'Test data')
               ON DUPLICATE KEY UPDATE status='HADIR'";

if ($conn->query($test_query)) {
    echo "✓ Absensi insert successful\n";
} else {
    echo "✗ Absensi insert failed: " . $conn->error . "\n";
}

// Test select absensi
$select_result = $conn->query("SELECT * FROM absensi WHERE tanggal = '$test_date' LIMIT 5");
echo "✓ Found " . $select_result->num_rows . " absensi records for today\n";

echo "\n";

// Test insert KPI
echo "Testing KPI insert...\n";
$test_periode = date('Y-m');
$test_kpi = "INSERT INTO kpi (id_karyawan, nama_karyawan, posisi, status_kerja, periode, siklus, kedisiplinan, kualitas_kerja, produktivitas, kerja_tim, total, rata_rata, kategori, catatan)
             VALUES ('TEST001', 'Test User', 'Staff', 'Karyawan', '$test_periode', 'bulanan', 4, 4, 4, 4, 16, 4.00, 'Baik', 'Test KPI')
             ON DUPLICATE KEY UPDATE rata_rata=4.00";

if ($conn->query($test_kpi)) {
    echo "✓ KPI insert successful\n";
} else {
    echo "✗ KPI insert failed: " . $conn->error . "\n";
}

// Test select KPI
$kpi_result = $conn->query("SELECT * FROM kpi WHERE periode = '$test_periode' LIMIT 5");
echo "✓ Found " . $kpi_result->num_rows . " KPI records for this month\n";

echo "\n";

// Test insert arsip
echo "Testing arsip insert...\n";
$test_arsip = "INSERT INTO arsip (tipe, nama, tanggal, no_hp, tipe_detail, kerusakan, alamat)
               VALUES ('Laptop', 'Test Customer', '$test_date', '08123456789', 'Lenovo IdeaPad', 'Layar rusak', 'Jakarta')";

if ($conn->query($test_arsip)) {
    echo "✓ Arsip insert successful\n";
} else {
    echo "✗ Arsip insert failed: " . $conn->error . "\n";
}

// Test select arsip
$arsip_result = $conn->query("SELECT * FROM arsip LIMIT 5");
echo "✓ Found " . $arsip_result->num_rows . " arsip records\n";

$conn->close();

echo "\n=== All tests completed ===\n";
?>
