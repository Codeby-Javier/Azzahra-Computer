<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Buat Mou</h1>
                <p>Isi form Mou & generate PDF</p>
            </div>
        </header>

        <div class="content-area">
            <?php if (isset($table_exists) && !$table_exists): ?>
            <div class="intro-y box overflow-hidden mt-5">
                <div class="px-5 py-10 text-center">
                    <div class="text-red-600 mb-4">
                        <i data-feather="alert-circle" class="w-16 h-16 mx-auto"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Tabel Database Belum Dibuat</h3>
                    <p class="text-gray-600 mb-4">
                        Jalankan SQL di file <strong>mou_database.sql</strong> lalu refresh halaman ini.
                    </p>
                </div>
            </div>
            <?php else: ?>

            <div class="intro-y box p-5 mt-5">
                <div class="mb-4">
                    <h2 class="text-lg font-medium">Form Mou</h2>
                    <p class="text-gray-600 text-sm">Harap isi dengan teliti dan berhati hati</p>
                </div>

                <form id="mouFormPage" method="post" action="<?= site_url('Mou/create') ?>">
                    <input type="hidden" name="items" id="itemsHidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama File / Transaksi *</label>
                            <input type="text" name="file_name" class="input w-full border" placeholder="Contoh: Servis Laptop Asus 12-12-2025" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal *</label>
                            <input type="date" name="tanggal" id="tanggal" class="input w-full border" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi *</label>
                            <select name="lokasi" class="input w-full border" required>
                                <option value="">Pilih Lokasi</option>
                                <option value="Tegal">Tegal</option>
                                <option value="Cibubur">Cibubur</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Customer *</label>
                            <input type="text" name="customer" class="input w-full border" placeholder="Masukkan nama customer" required>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold">Surat Penawaran</h3>
                            <button type="button" id="btnTambahItemPage" class="button text-white bg-theme-1">
                                <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Item
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table" id="itemsTablePage">
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
                                <tbody id="itemsTableBodyPage"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right font-bold">Grand Total:</td>
                                        <td class="font-bold" id="grandTotalPage">Rp. 0,-</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <a href="<?= site_url('Mou')?>" class="button border mr-2">Kembali</a>
                        <button type="submit" class="button text-white bg-theme-1">Simpan & Download PDF</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
const DEFAULT_DATE = '<?= date('Y-m-d') ?>';
const $p = (id) => document.getElementById(id);

function addItemPage() {
    const tbody = $p('itemsTableBodyPage');
    if (!tbody) return;
    const n = tbody.querySelectorAll('tr').length + 1;
    const row = document.createElement('tr');
    row.id = 'itemRowPage' + n;
    row.innerHTML = `
        <td>${n}</td>
        <td><input type="text" class="input w-full border" name="spesifikasi[]" required></td>
        <td><input type="number" class="input w-full border qty-input" step="0.01" min="0" name="qty[]" required onchange="calculateTotalPage(${n})"></td>
        <td><input type="text" class="input w-full border harga-input" name="harga[]" placeholder="0" required onchange="calculateTotalPage(${n})" onkeyup="formatCurrencyPage(this)"></td>
        <td class="total-cell" id="totalPage${n}">Rp. 0,-</td>
        <td><button type="button" class="button border text-red-600" onclick="removeItemPage(${n})"><i data-feather="trash-2" class="w-4 h-4"></i></button></td>
    `;
    tbody.appendChild(row);
    if (typeof feather !== 'undefined') feather.replace();
    updateItemNumbersPage();
}
function removeItemPage(id) {
    const row = $p('itemRowPage' + id);
    if (row) row.remove();
    updateItemNumbersPage();
    calculateGrandTotalPage();
}
function updateItemNumbersPage() {
    const rows = $p('itemsTableBodyPage')?.querySelectorAll('tr') || [];
    rows.forEach((row, idx) => {
        const n = idx + 1;
        row.id = 'itemRowPage' + n;
        const first = row.querySelector('td:first-child'); if (first) first.textContent = n;
        const qty = row.querySelector('.qty-input'); if (qty) qty.setAttribute('onchange', `calculateTotalPage(${n})`);
        const harga = row.querySelector('.harga-input'); if (harga) harga.setAttribute('onchange', `calculateTotalPage(${n})`);
        const total = row.querySelector('.total-cell'); if (total) total.id = 'totalPage' + n;
    });
}
function formatCurrencyPage(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) input.value = parseInt(value, 10).toLocaleString('id-ID');
}
function calculateTotalPage(id) {
    const row = $p('itemRowPage' + id);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input')?.value || '0') || 0;
    const hargaStr = (row.querySelector('.harga-input')?.value || '').replace(/[^\d]/g, '');
    const harga = parseFloat(hargaStr || '0') || 0;
    const total = qty * harga;
    const cell = row.querySelector('.total-cell');
    if (cell) cell.textContent = 'Rp. ' + total.toLocaleString('id-ID') + ',-';
    calculateGrandTotalPage();
}
function calculateGrandTotalPage() {
    const rows = $p('itemsTableBodyPage')?.querySelectorAll('tr') || [];
    let grand = 0;
    rows.forEach(row => {
        const cell = row.querySelector('.total-cell');
        if (cell) {
            const v = parseFloat(cell.textContent.replace(/[^\d]/g, '')) || 0;
            grand += v;
        }
    });
    const el = $p('grandTotalPage'); if (el) el.textContent = 'Rp. ' + grand.toLocaleString('id-ID') + ',-';
}

document.addEventListener('DOMContentLoaded', () => {
    // init
    addItemPage();
    const tgl = $p('tanggal'); if (tgl) tgl.value = DEFAULT_DATE;

    // button add
    const btnAdd = $p('btnTambahItemPage');
    if (btnAdd) btnAdd.onclick = () => { addItemPage(); return false; };

    // form submit: build items JSON lalu submit normal (server akan redirect/download)
    const form = $p('mouFormPage');
    if (form) {
        form.addEventListener('submit', function(e) {
            const items = [];
            const rows = $p('itemsTableBodyPage')?.querySelectorAll('tr') || [];
            rows.forEach(row => {
                const spesifikasi = row.querySelector('input[name="spesifikasi[]"]')?.value;
                const qty = row.querySelector('input[name="qty[]"]')?.value;
                const harga = row.querySelector('input[name="harga[]"]')?.value;
                if (spesifikasi && qty && harga) items.push({ spesifikasi, qty, harga });
            });
            if (!items.length) { e.preventDefault(); alert('Minimal harus ada 1 item'); return; }
            const hidden = $p('itemsHidden');
            if (hidden) hidden.value = JSON.stringify(items);
            // biarkan submit normal; controller akan redirect ke download
        });
    }

    // Pastikan overlay sidebar tidak menutup konten
    const overlay = $p('sidebarOverlay');
    if (overlay) { overlay.style.display = 'none'; overlay.style.pointerEvents = 'none'; }
});
</script>

<style>
.btn-buat-mou {
    display: inline-block !important;
    padding: 12px 24px !important;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    text-decoration: none !important;
}
#sidebarOverlay { display: none !important; pointer-events: none !important; }
.content-area { position: relative; z-index: 2; }
</style>

<?php $this->load->view('Template/footer'); ?>