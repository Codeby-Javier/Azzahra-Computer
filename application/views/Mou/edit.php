<?php $this->load->view('Template/header'); ?>

<header class="page-header">
    <div class="header-title">
        <h1><i data-feather="edit"></i> Edit MOU</h1>
        <p>Edit data Surat Penawaran Kerjasama</p>
    </div>
</header>

<div class="content-area">
    <div class="intro-y box">
        <div class="p-5">
            <form id="mouForm">
                <input type="hidden" id="mou_id" value="<?= $mou['mou_id'] ?>">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama File / Transaksi *</label>
                    <input type="text" name="file_name" id="file_name" class="input w-full border"
                        placeholder="Contoh: Servis Laptop Asus 12-12-2025"
                        value="<?= htmlspecialchars($mou['file_name']) ?>" required>
                    <small class="text-gray-500">Nama ini akan digunakan sebagai nama file PDF yang diunduh</small>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi *</label>
                    <select name="lokasi" id="lokasi" class="input w-full border" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="Tegal" <?= $mou['lokasi'] == 'Tegal' ? 'selected' : '' ?>>Tegal</option>
                        <option value="Cibubur" <?= $mou['lokasi'] == 'Cibubur' ? 'selected' : '' ?>>Cibubur</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" id="tanggal" class="input w-full border"
                        value="<?= $mou['tanggal'] ?>" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Customer *</label>
                    <input type="text" name="customer" id="customer" class="input w-full border"
                        placeholder="Masukkan nama customer" value="<?= htmlspecialchars($mou['customer']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Detail Item</label>
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
                                <?php $n = 1;
                                foreach ($items as $item): ?>
                                    <tr id="itemRow<?= $n ?>">
                                        <td><?= $n ?></td>
                                        <td><input type="text" class="input w-full border" name="spesifikasi[]"
                                                value="<?= htmlspecialchars($item['spesifikasi']) ?>" required></td>
                                        <td><input type="number" class="input w-full border qty-input" step="0.01" min="0"
                                                name="qty[]" value="<?= $item['qty'] ?>" required
                                                onchange="calculateTotal(<?= $n ?>)"></td>
                                        <td><input type="text" class="input w-full border harga-input" name="harga[]"
                                                value="<?= number_format($item['harga'], 0, ',', '.') ?>" required
                                                onchange="calculateTotal(<?= $n ?>)" onkeyup="formatCurrency(this)"></td>
                                        <td class="total-cell" id="total<?= $n ?>">Rp.
                                            <?= number_format($item['total'], 0, ',', '.') ?>,-</td>
                                        <td><button type="button" class="button border text-red-600"
                                                onclick="removeItem(<?= $n ?>)"><i data-feather="trash-2"
                                                    class="w-4 h-4"></i></button></td>
                                    </tr>
                                    <?php $n++; endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-bold">Grand Total:</td>
                                    <td class="font-bold" id="grandTotal">Rp.
                                        <?= number_format($mou['grand_total'], 0, ',', '.') ?>,-</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" id="btnTambahItem" class="button text-white bg-theme-1 mt-2"
                        onclick="addItem(); return false;">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Item
                    </button>
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <a href="<?= site_url('Mou') ?>" class="button border">Batal</a>
                    <button type="submit" id="btnSave" class="button text-white bg-theme-1">
                        <i data-feather="save" class="w-4 h-4 mr-1" style="display: inline; vertical-align: middle;"></i>
                        <span style="vertical-align: middle;">Simpan</span>
                    </button>
                    <button type="button" id="btnDownloadPdf" class="button text-white bg-theme-9" style="display: none;">
                        <i data-feather="download" class="w-4 h-4 mr-1" style="display: inline; vertical-align: middle;"></i>
                        <span style="vertical-align: middle;">Download PDF</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</main>
</div>

<script>
    const $ = (id) => document.getElementById(id);
    let rowCount = <?= count($items) ?>;

    function addItem() {
        rowCount++;
        const n = rowCount;
        const tbody = $('itemsTableBody');
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
            const itemNoCell = row.querySelector('td:first-child');
            if (itemNoCell) itemNoCell.textContent = n;
        });
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) input.value = parseInt(value, 10).toLocaleString('id-ID');
    }

    function calculateTotal(id) {
        const row = document.getElementById('itemRow' + id); // use standard getElement since ID might vary if reordered but we use unique IDs, actually let's stick to simple ID
        // wait I used `itemRow + n` in addItem, but `removeItem` and `calculateTotal` use id.
        // If I delete row 2, row 3 becomes row 2 visually but ID stays 3? 
        // `updateItemNumbers` only updates the displayed number (first cell).
        // So ID remains constant `itemRow{n}` if I don't re-id.
        // But `addItem` increments `rowCount`.
        // It's safer to just traverse DOM for grand total.

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

    document.addEventListener('DOMContentLoaded', () => {
        const form = $('mouForm');
        const btnDownloadPdf = $('btnDownloadPdf');
        let dataSaved = false;

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const mouId = $('mou_id').value;
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
                submitBtn.innerHTML = '<i data-feather="loader" class="w-4 h-4 mr-1 animate-spin" style="display: inline; vertical-align: middle;"></i><span style="vertical-align: middle;">Menyimpan...</span>';
                if (typeof feather !== 'undefined') feather.replace();

                fetch('<?= site_url("Mou/update/") ?>' + mouId, { method: 'POST', body: formData })
                    .then(response => {
                        // Cek apakah response OK
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.text();
                    })
                    .then(text => {
                        // Debug: log response
                        console.log('Response:', text);
                        
                        // Parse JSON
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            console.error('JSON Parse Error:', e);
                            console.error('Response text:', text);
                            throw new Error('Response bukan JSON valid. Cek console untuk detail.');
                        }

                        if (data.status === 'success') {
                            dataSaved = true;
                            // Tampilkan tombol download PDF
                            if (btnDownloadPdf) {
                                btnDownloadPdf.style.display = 'inline-flex';
                                btnDownloadPdf.onclick = () => {
                                    window.open('<?= site_url("Mou/download/") ?>' + mouId, '_blank');
                                };
                                if (typeof feather !== 'undefined') feather.replace();
                            }

                            const msg = 'Data berhasil disimpan! Silakan klik tombol "Download PDF" untuk mengunduh PDF dengan data terbaru.';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: msg,
                                    confirmButtonColor: '#1e40af',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                alert(msg);
                            }

                            // Disable form inputs setelah save
                            const inputs = form.querySelectorAll('input, select, button[type="button"]');
                            inputs.forEach(input => {
                                if (input.id !== 'btnDownloadPdf') {
                                    input.disabled = true;
                                }
                            });
                            submitBtn.style.display = 'none';
                        } else {
                            const msg = data.message || 'Gagal update Mou';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ icon: 'error', title: 'Error!', text: msg, confirmButtonColor: '#dc2626' });
                            } else {
                                alert(msg);
                            }
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            if (typeof feather !== 'undefined') feather.replace();
                        }
                    })
                    .catch(err => {
                        console.error('Fetch Error:', err);
                        const errorMsg = err.message || 'Terjadi kesalahan sistem';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg, confirmButtonColor: '#dc2626' });
                        } else {
                            alert(errorMsg);
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        if (typeof feather !== 'undefined') feather.replace();
                    });
            });
        }

        // Recalc initial total just in case
        calculateGrandTotal();
    });
</script>

<style>
    #btnDownloadPdf {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        animation: slideIn 0.3s ease-out;
    }

    #btnDownloadPdf:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

<?php $this->load->view('Template/footer'); ?>