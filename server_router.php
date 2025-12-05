<?php
// Router script for PHP built-in server to work with CodeIgniter
// This handles URL rewriting for CodeIgniter

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// No base path needed for built-in server
// Server runs from root directory

// If the file exists, serve it directly
if (file_exists(__DIR__ . $path) && is_file(__DIR__ . $path)) {
    return false; // Serve the file as-is
}

// For all other requests, route to CodeIgniter's index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
?>