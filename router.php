<?php
/**
 * Router untuk PHP Built-in Server
 * Arahkan semua request ke index.php untuk CodeIgniter routing
 */

$file = __DIR__ . $_SERVER['REQUEST_URI'];
$file = str_replace('//', '/', $file);

// Jika file statis ada (css, js, images, fonts, dll), serve langsung
if (is_file($file) && file_exists($file)) {
    // Set content type yang tepat untuk asset statis
    $mime_types = array(
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'eot'  => 'application/vnd.ms-fontobject',
        'ttf'  => 'application/x-font-ttf',
        'otf'  => 'application/x-font-opentype',
        'woff' => 'application/font-woff',
        'woff2'=> 'application/font-woff2',
        'pdf'  => 'application/pdf',
        'txt'  => 'text/plain',
        'html' => 'text/html',
        'xml'  => 'text/xml',
    );
    
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (isset($mime_types[$ext])) {
        header('Content-Type: ' . $mime_types[$ext]);
    }
    
    return false;
}

// Untuk semua request lainnya, arahkan ke index.php
require __DIR__ . '/index.php';

