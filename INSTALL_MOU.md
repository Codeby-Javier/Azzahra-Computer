# Instruksi Instalasi Fitur Mou

## Langkah 1: Buat Tabel Database

1. Buka phpMyAdmin atau tool database management Anda
2. Pilih database `azzahra`
3. Klik tab "SQL"
4. Copy dan paste isi file `mou_database.sql` ke textarea SQL
5. Klik "Go" atau "Execute"

**Atau jalankan SQL berikut:**

```sql
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
```

## Langkah 2: Setup Word Template Path

1. Buka file: `application/config/mou_config.php`
2. Edit baris 27, ganti path dengan lokasi file Word template Anda:
   ```php
   $config['mou_word_template_path'] = 'C:/Users/acer/Downloads/Mou Surat Penawaran.docx';
   ```
3. Pastikan:
   - File Word template ada di lokasi tersebut
   - File memiliki ekstensi `.docx` (bukan `.doc`)
   - Aplikasi memiliki permission untuk membaca file

## Langkah 3: Verifikasi

1. Login sebagai Customer Service
2. Klik menu "Mou" di sidebar
3. Jika tabel belum dibuat, akan muncul pesan error dengan instruksi
4. Jika sudah dibuat, akan muncul halaman list Mou (kosong jika belum ada data)

## Troubleshooting

### Error: "Table 'azzahra.mou' doesn't exist"
**Solusi:** Jalankan SQL dari Langkah 1 di atas

### Error: "Word template not found"
**Solusi:** 
- Pastikan path di `mou_config.php` benar
- Pastikan file Word ada di lokasi tersebut
- Gunakan forward slash (`/`) untuk path Windows

### Error: "Permission denied"
**Solusi:**
- Pastikan folder `application/cache/mou_temp/` dapat ditulis
- Set permission folder menjadi 755 atau 777 (Linux)

### PDF tidak ter-generate
**Solusi:**
- Cek log di `application/logs/` untuk detail error
- Pastikan Dompdf library sudah terinstall (sudah ada di project ini)
- Sistem akan otomatis menggunakan HTML fallback jika PhpWord tidak tersedia

## Catatan

- File Word template akan tetap kosong (hanya sebagai template)
- PDF yang dihasilkan akan memiliki nama sesuai dengan "Nama File" yang diisi saat membuat Mou
- Semua data Mou tersimpan di database dan dapat di-download ulang kapan saja
- Jika PhpWord tidak terinstall, sistem otomatis menggunakan HTML fallback (tetap menghasilkan PDF)



