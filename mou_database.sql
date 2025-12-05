-- Database table for Mou
-- Run this SQL to create the necessary tables
-- IMPORTANT: Run this SQL in your database (phpMyAdmin or MySQL client)

CREATE TABLE IF NOT EXISTS `mou` (
  `mou_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `customer` varchar(255) NOT NULL,
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kry_kode` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`mou_id`),
  KEY `kry_kode` (`kry_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `mou_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `mou_id` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `spesifikasi` text NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`item_id`),
  KEY `mou_id` (`mou_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

