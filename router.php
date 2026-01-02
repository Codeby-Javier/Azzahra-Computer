<?php
/**
 * Router untuk PHP Built-in Server
 * Digunakan oleh easy_run.bat
 */

// Dapatkan URI yang diminta
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Jika file statis ada, serve langsung
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    // Cek apakah file PHP
    if (pathinfo($uri, PATHINFO_EXTENSION) === 'php') {
        // Jangan serve file PHP langsung kecuali index.php
        if (basename($uri) !== 'index.php') {
            return false;
        }
    }
    
    // Serve file statis (CSS, JS, images, dll)
    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
        'pdf'  => 'application/pdf',
    ];
    
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
        readfile(__DIR__ . $uri);
        return true;
    }
    
    // Untuk file lain, biarkan PHP handle
    return false;
}

// Semua request lain diarahkan ke index.php (CodeIgniter)
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
