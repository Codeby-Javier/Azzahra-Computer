<?php
/**
 * Router untuk PHP Built-in Server
 * Mengarahkan semua request ke index.php untuk CodeIgniter
 */

// Jika file yang diminta benar-benar ada (static files), serve langsung
$requested_file = __DIR__ . $_SERVER["REQUEST_URI"];
$requested_file = str_replace('\\', '/', $requested_file);

// Jika request adalah untuk file atau folder yang benar-benar ada
if (file_exists($requested_file) && is_file($requested_file)) {
    return false; // Let the server handle this request
}

// Jika request adalah untuk folder yang benar-benar ada
if (is_dir($requested_file)) {
    return false;
}

// Untuk semua request lainnya, arahkan ke index.php (CodeIgniter routing)
require_once __DIR__ . '/index.php';
