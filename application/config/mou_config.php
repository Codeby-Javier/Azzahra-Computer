<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Mou Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi untuk path file Word template Mou

| Cara Setting:
| 1. Copy file Word template "Mou Surat Penawaran" ke folder yang diinginkan
| 2. Isi path lengkap ke file Word template di bawah ini
| 3. Contoh untuk Windows: 'C:/Users/acer/Downloads/Mou Surat Penawaran.docx'
| 4. Contoh untuk Linux: '/home/user/Documents/Mou Surat Penawaran.docx'
| 5. Pastikan file Word memiliki ekstensi .docx (bukan .doc)
|
| CATATAN PENTING:
| - Gunakan forward slash (/) untuk path, bukan backslash (\)
| - Untuk Windows, bisa juga menggunakan backslash dengan double (\\)
| - Pastikan aplikasi memiliki permission untuk membaca file tersebut
| - File Word template harus memiliki placeholder: <<Lokasi>>, <<Tanggal>>,
|   <<Customer>>, <<No>>, <<Spesifikasi>>, <<Qty>>, <<Harga>>, <<Total>>, <<Grand>>
|
*/

$config['mou_word_template_path'] = 'C:/Users/acer/Downloads/Mou Surat Penawaran.docx';

/*
|--------------------------------------------------------------------------
| Temporary Directory
|--------------------------------------------------------------------------
|
| Folder untuk menyimpan file temporary saat proses generate PDF
| Default: application/cache/mou_temp/
|
*/
$config['mou_temp_dir'] = APPPATH . 'cache/mou_temp/';

/*
|--------------------------------------------------------------------------
| Google Docs Template (opsional)
|--------------------------------------------------------------------------
| Jika ingin memakai Google Docs sebagai sumber template, isi URL di sini.
| Contoh: publish link (bukan edit) agar dapat diakses publik.
*/
$config['mou_google_doc_url'] = 'https://docs.google.com/document/d/1KvjxxkdEage-hmIxQ9sRosmbtPogZyU5/edit?usp=sharing';