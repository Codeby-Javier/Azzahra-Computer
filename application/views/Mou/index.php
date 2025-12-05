<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Mou</h1>
                <p>Riwayat Pembuatan Mou</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            <?php if (isset($table_exists) && !$table_exists): ?>
            <div class="intro-y box overflow-hidden mt-5">
                <div class="px-5 py-10 text-center">
                    <div class="text-red-600 mb-4">
                        <i data-feather="alert-circle" class="w-16 h-16 mx-auto"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Tabel Database Belum Dibuat</h3>
                    <p class="text-gray-600 mb-4">
                        Silakan jalankan SQL berikut di database Anda untuk membuat tabel yang diperlukan:
                    </p>
                    <div class="bg-gray-100 p-4 rounded text-left mb-4" style="max-width: 800px; margin: 0 auto; max-height: 400px; overflow-y: auto;">
                        <pre class="text-sm overflow-x-auto" style="white-space: pre-wrap; word-wrap: break-word;"><?php 
                        $sql_file = APPPATH . '../mou_database.sql';
                        if (file_exists($sql_file)) {
                            echo htmlspecialchars(file_get_contents($sql_file));
                        } else {
                            echo "CREATE TABLE IF NOT EXISTS `mou` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
                        }
                        ?></pre>
                    </div>
                    <p class="text-gray-600 text-sm">
                        <strong>Cara menjalankan:</strong><br>
                        1. Buka phpMyAdmin<br>
                        2. Pilih database <strong>azzahra</strong><br>
                        3. Klik tab <strong>SQL</strong><br>
                        4. Copy SQL di atas dan paste ke textarea<br>
                        5. Klik <strong>Go</strong> atau <strong>Execute</strong><br>
                        6. Refresh halaman ini setelah selesai
                    </p>
                </div>
            </div>
            <?php else: ?>
            <div class="intro-y flex flex-col sm:flex-row items-center justify-between mt-8">
                <h2 class="text-lg font-medium">
                    Daftar Mou
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <a href="<?= site_url('Mou/create_form');?>" class="btn-buat-mou" style="cursor: pointer !important; position: relative !important; z-index: 9999 !important; pointer-events: auto !important; text-decoration: none;">
                        Buat Mou
                    </a>
                </div>
            </div>

            <div class="intro-y box overflow-hidden mt-5">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">No</th>
                                <th class="border-b-2 whitespace-no-wrap">Nama File</th>
                                <th class="border-b-2 whitespace-no-wrap">Customer</th>
                                <th class="border-b-2 whitespace-no-wrap">Lokasi</th>
                                <th class="border-b-2 whitespace-no-wrap">Tanggal</th>
                                <th class="border-b-2 whitespace-no-wrap">Grand Total</th>
                                <th class="border-b-2 whitespace-no-wrap">Dibuat Oleh</th>
                                <th class="border-b-2 whitespace-no-wrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = ($this->uri->segment(3) ? $this->uri->segment(3) : 0) + 1;
                            if ($mou_list && $mou_list->num_rows() > 0): 
                                foreach ($mou_list->result() as $mou): 
                            ?>
                            <tr>
                                <td class="border-b"><?= $no++ ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->file_name) ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->customer) ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->lokasi) ?></td>
                                <td class="border-b"><?= date('d/m/Y', strtotime($mou->tanggal)) ?></td>
                                <td class="border-b">Rp. <?= number_format($mou->grand_total, 0, ',', '.') ?>,-</td>
                                <td class="border-b"><?= htmlspecialchars($mou->kry_nama ?: '-') ?></td>
                                <td class="border-b">
                                    <a href="<?= site_url('Mou/download/' . $mou->mou_id) ?>" class="button button--sm text-white bg-theme-1" style="display: inline-block; white-space: nowrap;" target="_blank">
                                        <i data-feather="download" class="w-4 h-4" style="display: inline; margin-right: 4px; vertical-align: middle;"></i><span style="vertical-align: middle;">Download</span>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                            <tr>
                                <td colspan="8" class="text-center border-b py-8">
                                    <div class="text-gray-500">
                                        <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2"></i>
                                        <p>Tidak ada data Mou</p>
                                        <p class="text-sm mt-2">Klik tombol "Buat Mou" untuk membuat Mou baru</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($links) && !empty($links)): ?>
                <div class="px-5 py-3 border-t">
                    <?= $links ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<!-- Modal Create Mou -->
<div class="modal" id="createMouModal" style="display: none;">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="text-lg font-medium">Buat Mou Baru</h2>
            <button type="button" class="close" id="btnCloseModal" onclick="closeCreateModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="mouForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama File / Transaksi *</label>
                    <input type="text" name="file_name" id="file_name" class="input w-full border" 
                           placeholder="Contoh: Servis Laptop Asus 12-12-2025" required>
                    <small class="text-gray-500">Nama ini akan digunakan sebagai nama file PDF yang diunduh</small>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi *</label>
                    <select name="lokasi" id="lokasi" class="input w-full border" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="Tegal">Tegal</option>
                        <option value="Cibubur">Cibubur</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" id="tanggal" class="input w-full border" 
                           value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Customer *</label>
                    <input type="text" name="customer" id="customer" class="input w-full border" 
                           placeholder="Masukkan nama customer" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Surat Penawaran</label>
                    <div class="overflow-x-auto">
                        <table class="table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Spesifikasi</th>
                                    <th>Qty</th>
                                    <th>Harga (IDR)</th>
                                    <th>Total (IDR)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-bold">Grand Total:</td>
                                    <td class="font-bold" id="grandTotal">Rp. 0,-</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" id="btnTambahItem" class="button text-white bg-theme-1 mt-2" onclick="addItem(); return false;">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Item
                    </button>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" id="btnBatalMou" class="button border mr-2" onclick="closeCreateModal(); return false;">Batal</button>
                    <button type="submit" class="button text-white bg-theme-1">Simpan & Download PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const DEFAULT_DATE = '<?= date('Y-m-d') ?>';
const $ = (id) => document.getElementById(id);

// ---------- MODAL ----------
function openCreateModal() {
    const modal = $('createMouModal');
    if (!modal) return false;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';

    const form = $('mouForm');
    if (form) form.reset();
    const tgl = $('tanggal'); if (tgl) tgl.value = DEFAULT_DATE;
    const body = $('itemsTableBody'); if (body) body.innerHTML = '';
    const gt = $('grandTotal'); if (gt) gt.textContent = 'Rp. 0,-';

    addItem();
    if (typeof feather !== 'undefined') setTimeout(() => feather.replace(), 50);
    return false;
}

function closeCreateModal() {
    const modal = $('createMouModal');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
}

// ---------- ITEMS ----------
function addItem() {
    const tbody = $('itemsTableBody');
    if (!tbody) return;
    const n = tbody.querySelectorAll('tr').length + 1;
    const row = document.createElement('tr');
    row.id = 'itemRow' + n;
    row.innerHTML = `
        <td>${n}</td>
        <td><input type="text" class="input w-full border" name="spesifikasi[]" required></td>
        <td><input type="number" class="input w-full border qty-input" step="0.01" min="0" name="qty[]" required onchange="calculateTotal(${n})"></td>
        <td><input type="text" class="input w-full border harga-input" name="harga[]" placeholder="0" required onchange="calculateTotal(${n})" onkeyup="formatCurrency(this)"></td>
        <td class="total-cell" id="total${n}">Rp. 0,-</td>
        <td><button type="button" class="button border text-red-600" onclick="removeItem(${n})"><i data-feather="trash-2" class="w-4 h-4"></i></button></td>
    `;
    tbody.appendChild(row);
    if (typeof feather !== 'undefined') feather.replace();
    updateItemNumbers();
}

function removeItem(id) {
    const row = $('itemRow' + id);
    if (row) row.remove();
    updateItemNumbers();
    calculateGrandTotal();
}

function updateItemNumbers() {
    const rows = $('itemsTableBody')?.querySelectorAll('tr') || [];
    rows.forEach((row, idx) => {
        const n = idx + 1;
        row.id = 'itemRow' + n;
        const first = row.querySelector('td:first-child'); if (first) first.textContent = n;
        const qty = row.querySelector('.qty-input'); if (qty) qty.setAttribute('onchange', `calculateTotal(${n})`);
        const harga = row.querySelector('.harga-input'); if (harga) harga.setAttribute('onchange', `calculateTotal(${n})`);
        const total = row.querySelector('.total-cell'); if (total) total.id = 'total' + n;
    });
}

function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) input.value = parseInt(value, 10).toLocaleString('id-ID');
}

function calculateTotal(id) {
    const row = $('itemRow' + id);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input')?.value || '0') || 0;
    const hargaStr = (row.querySelector('.harga-input')?.value || '').replace(/[^\d]/g, '');
    const harga = parseFloat(hargaStr || '0') || 0;
    const total = qty * harga;
    const cell = row.querySelector('.total-cell');
    if (cell) cell.textContent = 'Rp. ' + total.toLocaleString('id-ID') + ',-';
    calculateGrandTotal();
}

function calculateGrandTotal() {
    const rows = $('itemsTableBody')?.querySelectorAll('tr') || [];
    let grand = 0;
    rows.forEach(row => {
        const cell = row.querySelector('.total-cell');
        if (cell) {
            const v = parseFloat(cell.textContent.replace(/[^\d]/g, '')) || 0;
            grand += v;
        }
    });
    const el = $('grandTotal'); if (el) el.textContent = 'Rp. ' + grand.toLocaleString('id-ID') + ',-';
}

// ---------- FORM & BINDINGS ----------
document.addEventListener('DOMContentLoaded', () => {
    const form = $('mouForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const items = [];
            const rows = $('itemsTableBody')?.querySelectorAll('tr') || [];
            rows.forEach(row => {
                const spesifikasi = row.querySelector('input[name="spesifikasi[]"]')?.value;
                const qty = row.querySelector('input[name="qty[]"]')?.value;
                const harga = row.querySelector('input[name="harga[]"]')?.value;
                if (spesifikasi && qty && harga) items.push({ spesifikasi, qty, harga });
            });
            if (!items.length) { alert('Minimal harus ada 1 item'); return; }
            formData.append('items', JSON.stringify(items));

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Memproses...';

            fetch('<?= site_url("Mou/create") ?>', { method: 'POST', body: formData })
                .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error(t || 'Gagal'); }))
                .then(data => {
                    if (data.status === 'success') {
                        const done = () => { if (data.pdf_url) window.open(data.pdf_url, '_blank'); closeCreateModal(); location.reload(); };
                        if (typeof Swal !== 'undefined') Swal.fire({icon:'success',title:'Berhasil!',text:'Mou berhasil dibuat!',confirmButtonColor:'#1e40af'}).then(done);
                        else { alert('Mou berhasil dibuat!'); done(); }
                    } else {
                        const msg = data.message || 'Gagal membuat Mou';
                        if (typeof Swal !== 'undefined') Swal.fire({icon:'error',title:'Error!',text:msg,confirmButtonColor:'#dc2626'}); else alert(msg);
                    }
                })
                .catch(err => {
                    const msg = err.message || 'Terjadi kesalahan saat membuat Mou';
                    if (typeof Swal !== 'undefined') Swal.fire({icon:'error',title:'Error!',text:msg,confirmButtonColor:'#dc2626'}); else alert(msg);
                })
                .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = originalText; });
        });
    }

    const btnBuatMou = $('btnBuatMou'); if (btnBuatMou) btnBuatMou.onclick = () => { openCreateModal(); return false; };
    const btnCloseModal = $('btnCloseModal'); if (btnCloseModal) btnCloseModal.onclick = () => { closeCreateModal(); return false; };
    const btnBatalMou = $('btnBatalMou'); if (btnBatalMou) btnBatalMou.onclick = () => { closeCreateModal(); return false; };
    const btnTambahItem = $('btnTambahItem'); if (btnTambahItem) btnTambahItem.onclick = () => { addItem(); return false; };

    document.addEventListener('click', (ev) => {
        const modal = $('createMouModal');
        if (modal && ev.target === modal) closeCreateModal();
    });
    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') closeCreateModal();
    });

    // Pastikan overlay sidebar tidak menutup konten
    const overlay = $('sidebarOverlay');
    if (overlay) { overlay.style.display = 'none'; overlay.style.pointerEvents = 'none'; }
});
</script>

<style>
#sidebarOverlay {
    display: none !important;
    pointer-events: none !important;
    opacity: 0 !important;
}
.btn-buat-mou {
    z-index: 99999 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
}

/* Tombol Buat Mou - Lebar, 1 baris */
.btn-buat-mou {
    display: inline-block !important;
    width: auto !important;
    min-width: 150px !important;
    padding: 12px 24px !important;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    text-align: center !important;
    border: none !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
    white-space: nowrap !important;
    line-height: 1.5 !important;
    position: relative !important;
    z-index: 999 !important;
    pointer-events: auto !important;
    user-select: none !important;
}

.btn-buat-mou:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-buat-mou:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
}

.btn-buat-mou:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background-color: #fefefe;
    margin: 3% auto;
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideDown 0.3s ease;
    max-width: 900px;
    width: 90%;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px 12px 0 0;
}

.modal-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.close {
    color: #64748b;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.close:hover,
.close:focus {
    color: #1e293b;
    background-color: #e2e8f0;
}

.modal-body {
    padding: 24px;
    max-height: calc(90vh - 120px);
    overflow-y: auto;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<?php $this->load->view('Template/footer'); ?>

