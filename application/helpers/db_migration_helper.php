<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database Migration Helper
 * Memastikan semua tabel yang diperlukan sudah ada
 * Note: Tabel karyawan sudah ada dengan struktur berbeda, jadi skip
 */

if (!function_exists('ensure_hr_tables')) {
    function ensure_hr_tables()
    {
        $CI = &get_instance();
        
        // Karyawan table already exists with different structure - skip creation
        // The existing table has: kry_kode, kry_nama, kry_level, etc.
        
        // Check and create absensi table
        if (!$CI->db->table_exists('absensi')) {
            $CI->db->query("
                CREATE TABLE IF NOT EXISTS `absensi` (
                  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
                  `tanggal` date NOT NULL,
                  `id_karyawan` varchar(20) NOT NULL,
                  `nama_karyawan` varchar(100) NOT NULL,
                  `posisi` varchar(50) NOT NULL,
                  `status` varchar(20) NOT NULL,
                  `jam_masuk` time,
                  `jam_pulang` time,
                  `keterangan` text,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`absensi_id`),
                  KEY `tanggal` (`tanggal`),
                  KEY `id_karyawan` (`id_karyawan`),
                  UNIQUE KEY `unique_absensi` (`tanggal`, `id_karyawan`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
        
        // Check and create kpi table
        if (!$CI->db->table_exists('kpi')) {
            $CI->db->query("
                CREATE TABLE IF NOT EXISTS `kpi` (
                  `kpi_id` int(11) NOT NULL AUTO_INCREMENT,
                  `id_karyawan` varchar(20) NOT NULL,
                  `nama_karyawan` varchar(100) NOT NULL,
                  `posisi` varchar(50) NOT NULL,
                  `status_kerja` varchar(50) NOT NULL,
                  `periode` varchar(20) NOT NULL,
                  `siklus` varchar(20) NOT NULL DEFAULT 'bulanan',
                  `kedisiplinan` int(11) NOT NULL DEFAULT 0,
                  `kualitas_kerja` int(11) NOT NULL DEFAULT 0,
                  `produktivitas` int(11) NOT NULL DEFAULT 0,
                  `kerja_tim` int(11) NOT NULL DEFAULT 0,
                  `total` int(11) NOT NULL DEFAULT 0,
                  `rata_rata` decimal(5,2) NOT NULL DEFAULT 0.00,
                  `kategori` varchar(50) NOT NULL DEFAULT 'Kurang',
                  `catatan` text,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`kpi_id`),
                  KEY `id_karyawan` (`id_karyawan`),
                  KEY `periode` (`periode`),
                  KEY `siklus` (`siklus`),
                  UNIQUE KEY `unique_kpi` (`id_karyawan`, `periode`, `siklus`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
        
        // Check and create laporan_mingguan table
        if (!$CI->db->table_exists('laporan_mingguan')) {
            $CI->db->query("
                CREATE TABLE IF NOT EXISTS `laporan_mingguan` (
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
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`laporan_id`),
                  KEY `id_karyawan` (`id_karyawan`),
                  KEY `periode` (`periode`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
        
        // Check and create arsip table
        if (!$CI->db->table_exists('arsip')) {
            $CI->db->query("
                CREATE TABLE IF NOT EXISTS `arsip` (
                  `arsip_id` int(11) NOT NULL AUTO_INCREMENT,
                  `tipe` varchar(50) NOT NULL,
                  `nama` varchar(100) NOT NULL,
                  `tanggal` date NOT NULL,
                  `no_hp` varchar(20),
                  `tipe_detail` varchar(100),
                  `kerusakan` text,
                  `alamat` varchar(255),
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`arsip_id`),
                  KEY `tipe` (`tipe`),
                  KEY `tanggal` (`tanggal`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
        
        return true;
    }
}