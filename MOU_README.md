# 🚀 FITUR BUAT MOU - QUICK START

## Setup Cepat (5 Menit!)

### 1. Jalankan Setup Script
```bash
# Double klik file ini:
setup_mou.bat
```

### 2. Import Database
- Buka phpMyAdmin
- Import file: `mou_database.sql`

### 3. Akses Setup Wizard
```
http://localhost:8000/mou_setup.php
```

### 4. Mulai Gunakan!
```
http://localhost:8000/Mou
```
Klik tombol **"Buat Mou"** → Isi form → Download PDF ✅

---

## 📚 Dokumentasi Lengkap

| File | Keterangan |
|------|------------|
| **PANDUAN_MOU.md** | 📖 Panduan lengkap (BACA INI DULU!) |
| **mou_setup.php** | 🔧 Setup wizard (akses via browser) |
| **MOU_TEMPLATE_WORD.md** | 📝 Cara buat template Word (opsional) |
| **TEMPLATE_WORD_EXAMPLE.txt** | 📄 Contoh template Word |
| **setup_mou.bat** | ⚡ Setup otomatis Windows |

---

## ⚙️ Konfigurasi Cepat

Edit file: `application/config/mou_config.php`

```php
// Path ke template Word (OPSIONAL - sistem punya template default)
$config['mou_word_template_path'] = 'C:/Path/Ke/Template.docx';
```

**CATATAN PENTING:** Template Word adalah OPSIONAL! Sistem sudah punya template HTML yang bagus dan otomatis digunakan.

---

## 🎯 Cara Menggunakan

1. Login sebagai **Customer Service**
2. Buka menu **Mou**
3. Klik **"Buat Mou"**
4. Isi form:
   - Nama File: `Servis Laptop Asus 12-12-2025`
   - Lokasi: `Tegal` atau `Cibubur`
   - Tanggal: Pilih tanggal
   - Customer: `PT. ABC`
   - Tambah item-item penawaran
5. Klik **"Simpan & Download PDF"**
6. PDF otomatis terunduh! ✅

---

## 🏢 Deploy ke Komputer Lain

**Super Mudah!**

1. Copy project ke komputer baru
2. Import database (`mou_database.sql`)
3. Akses: `http://localhost:8000/mou_setup.php`
4. Done! 🎉

---

## 🔧 Troubleshooting

### Problem: Tabel database belum dibuat
**Fix:** Import file `mou_database.sql` via phpMyAdmin

### Problem: PDF tidak generate
**Fix:** Sistem akan otomatis pakai template HTML (tetap jalan!)

### Problem: Tombol tidak berfungsi
**Fix:** Refresh dengan `Ctrl+F5`, clear cache browser

**Troubleshooting lengkap:** Lihat file `PANDUAN_MOU.md`

---

## 📞 Bantuan

- **Error logs:** `application/logs/log-[tanggal].php`
- **Setup wizard:** `http://localhost:8000/mou_setup.php`
- **Panduan lengkap:** `PANDUAN_MOU.md`

---

## ✅ Checklist

- [ ] Setup script sudah dijalankan
- [ ] Database sudah diimport
- [ ] Setup wizard sudah diakses
- [ ] Test buat MOU berhasil
- [ ] PDF berhasil didownload

---

**Sistem siap digunakan! 🎉**

*Untuk panduan detail, buka: `PANDUAN_MOU.md`*
