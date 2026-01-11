<?php
// Test direct database insert
$config = include('application/config/database.php');
$db_config = $config['default'];

$conn = mysqli_connect($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die('Connection error: ' . $conn->connect_error);
}

echo "Database connected OK\n";

// Check current data
$check = $conn->query('SELECT COUNT(*) as cnt FROM poin_performa WHERE periode_minggu = "2026-W02"');
$row = $check->fetch_assoc();
echo "Current data count for 2026-W02: " . $row['cnt'] . "\n\n";

// Delete old data
$conn->query('DELETE FROM poin_performa WHERE periode_minggu = "2026-W02"');
echo "Old data deleted\n";

// Insert test data
$periode = '2026-W02';
$bulan = '2026-01';

$data = [
    [
        'id_karyawan' => 'K001',
        'nama_karyawan' => 'Ahmad Fauzi',
        'posisi' => 'Senior Developer',
        'tipe_karyawan' => 'Karyawan',
        'periode_minggu' => $periode,
        'bulan' => $bulan,
        'hasil_kerja' => 20,
        'pencapaian_target' => 18,
        'kualitas_kerja' => 15,
        'disiplin' => 12,
        'tanggung_jawab' => 10,
        'produktivitas_layanan' => 8,
        'kepatuhan_sop' => 5,
        'minim_komplain' => 4,
        'total_poin' => 92,
        'catatan' => 'Performa bagus'
    ],
    [
        'id_karyawan' => 'K002',
        'nama_karyawan' => 'Budi Santoso',
        'posisi' => 'Developer',
        'tipe_karyawan' => 'Karyawan',
        'periode_minggu' => $periode,
        'bulan' => $bulan,
        'hasil_kerja' => 18,
        'pencapaian_target' => 16,
        'kualitas_kerja' => 14,
        'disiplin' => 13,
        'tanggung_jawab' => 9,
        'produktivitas_layanan' => 8,
        'kepatuhan_sop' => 4,
        'minim_komplain' => 3,
        'total_poin' => 85,
        'catatan' => 'Cukup baik'
    ],
    [
        'id_karyawan' => 'M001',
        'nama_karyawan' => 'Siti Nurhaliza',
        'posisi' => 'IT Trainee',
        'tipe_karyawan' => 'Magang',
        'periode_minggu' => $periode,
        'bulan' => $bulan,
        'proses_belajar' => 22,
        'tugas_dijalankan' => 20,
        'sikap' => 18,
        'kedisiplinan' => 14,
        'kepatuhan_sop_magang' => 13,
        'total_poin' => 87,
        'catatan' => 'Bagus sekali'
    ],
    [
        'id_karyawan' => 'M002',
        'nama_karyawan' => 'Rina Wijaya',
        'posisi' => 'Admin Trainee',
        'tipe_karyawan' => 'Magang',
        'periode_minggu' => $periode,
        'bulan' => $bulan,
        'proses_belajar' => 20,
        'tugas_dijalankan' => 18,
        'sikap' => 16,
        'kedisiplinan' => 12,
        'kepatuhan_sop_magang' => 11,
        'total_poin' => 77,
        'catatan' => 'Cukup baik'
    ]
];

$success = 0;
foreach ($data as $record) {
    $cols = implode(', ', array_keys($record));
    $vals = implode("', '", array_values($record));
    $sql = "INSERT INTO poin_performa ($cols) VALUES ('$vals')";
    
    if ($conn->query($sql)) {
        $success++;
        echo "✓ " . $record['nama_karyawan'] . " inserted\n";
    } else {
        echo "✗ " . $record['nama_karyawan'] . " failed: " . $conn->error . "\n";
    }
}

echo "\n" . $success . " / 4 records inserted successfully\n";

// Verify final count
$final = $conn->query('SELECT COUNT(*) as cnt FROM poin_performa WHERE periode_minggu = "2026-W02"');
$row = $final->fetch_assoc();
echo "Final data count for 2026-W02: " . $row['cnt'] . "\n";

$conn->close();
?>
