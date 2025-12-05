<?php
// Simple cleanup script - Hapus semua MOU kecuali 2 terbaru
// Run: php cleanup.php

// Bootstrap CodeIgniter
$_SERVER['CI_ENV'] = 'development';
define('ENVIRONMENT', 'development');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');

require_once BASEPATH . 'core/Common.php';
require_once APPPATH . 'config/database.php';

// Create DB connection manually
$db_config = $db['default'];
$conn = new mysqli(
    $db_config['hostname'],
    $db_config['username'],
    $db_config['password'],
    $db_config['database']
);

if ($conn->connect_error) {
    die("❌ Koneksi database gagal: " . $conn->connect_error . "\n");
}

echo "🔌 Koneksi database berhasil\n";

// Get total count
$result = $conn->query("SELECT COUNT(*) as total FROM mou");
$total = $result->fetch_assoc()['total'];
$keep = 2;

echo "📊 Total data MOU: $total\n";
echo "📌 Akan menyisakan: $keep data terbaru\n\n";

if ($total > $keep) {
    $delete_count = $total - $keep;
    
    // Get old MOUs to delete
    $query = "SELECT mou_id, file_name, created_at FROM mou ORDER BY created_at ASC LIMIT $delete_count";
    $result = $conn->query($query);
    $old_mous = [];
    
    while ($row = $result->fetch_assoc()) {
        $old_mous[] = $row;
    }
    
    // Delete each one
    $deleted = 0;
    $pdf_deleted = 0;
    $cache_dir = __DIR__ . '/application/cache/mou_temp/';
    
    foreach ($old_mous as $mou) {
        $mou_id = $mou['mou_id'];
        
        // Delete items first
        $conn->query("DELETE FROM mou_items WHERE mou_id = $mou_id");
        
        // Delete MOU
        $conn->query("DELETE FROM mou WHERE mou_id = $mou_id");
        
        // Delete PDF cache files
        if (is_dir($cache_dir)) {
            $files = glob($cache_dir . $mou_id . '_*.pdf');
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    $pdf_deleted++;
                }
            }
        }
        
        $deleted++;
        echo "🗑️  Hapus: {$mou['file_name']} (ID: $mou_id)\n";
    }
    
    echo "\n✅ Berhasil hapus $deleted data MOU lama\n";
    echo "📄 Berhasil hapus $pdf_deleted file PDF cache\n";
    echo "💾 Tersisa 2 data terbaru\n";
} else {
    echo "ℹ️  Data sudah sesuai atau kurang dari 2 record. Tidak ada yang dihapus.\n";
}

// Show remaining
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 DATA MOU YANG TERSISA:\n";
echo str_repeat("=", 60) . "\n";

$result = $conn->query("SELECT mou_id, file_name, customer, lokasi, grand_total, created_at FROM mou ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        echo "$i. {$row['file_name']}\n";
        echo "   Customer: {$row['customer']}\n";
        echo "   Lokasi: {$row['lokasi']}\n";
        echo "   Grand Total: Rp " . number_format($row['grand_total'], 0, ',', '.') . "\n";
        echo "   Dibuat: {$row['created_at']}\n";
        echo "   ---\n";
        $i++;
    }
} else {
    echo "Tidak ada data MOU\n";
}

$conn->close();
echo "\n✅ Cleanup selesai!\n";
?>
