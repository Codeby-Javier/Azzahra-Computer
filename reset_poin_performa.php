<?php
// Jalankan script ini sekali saja untuk mengosongkan tabel poin_performa
define('BASEPATH', true);

$mysqli = new mysqli('localhost', 'root', '', 'cv_azzahra');
if ($mysqli->connect_errno) {
    die('Koneksi gagal: ' . $mysqli->connect_error);
}
$mysqli->query('DELETE FROM poin_performa');
$mysqli->close();
echo 'Tabel poin_performa berhasil dikosongkan.';
