<?php $this->load->view('Template/header'); ?>

<!-- Layout Container -->
<div class="w-full" style="position: relative; overflow-x: hidden;">

    <!-- Header Section -->
    <header class="page-header"
        style="position: relative; margin-top: 0px; margin-bottom: 0px; padding-bottom: 30px; z-index: 10;">
        <div class="header-title">
            <h1><i data-feather="archive"></i> Arsip Dokumen</h1>
            <p>Database arsip perbaikan Dreame & Laptop</p>
        </div>
    </header>

    <!-- Content Section -->
    <div class="content" style="position: relative; z-index: 5; top: -50px;">

        <!-- TABLE 1: DREAME -->
        <div class="intro-y box mt-5">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Arsip Dokumen Dreamé
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0 gap-2">
                    <button class="button text-white bg-theme-1 shadow-md"
                        onclick="openModalArsip('dreame', 'add')">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Tambah Arsip
                    </button>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <form action="<?= site_url('HR/arsip') ?>" method="GET" class="grid grid-cols-12 gap-3">
                    <input type="hidden" name="tipe" value="Dreame">
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Tipe Periode</label>
                        <select name="siklus_dreame" id="siklusDreame" class="input w-full border" onchange="changeSiklusDreame(this.value)">
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Periode</label>
                        <input type="date" name="periode_harian" id="periodeDreameHarian" class="input w-full border" style="display:none;">
                        <input type="week" name="periode_mingguan" id="periodeDreameMingguan" class="input w-full border" style="display:none;">
                        <input type="month" name="periode_bulanan" id="periodeDreameBulanan" class="input w-full border" value="<?= date('Y-m') ?>" style="display:block;">
                        <input type="number" name="periode_tahunan" id="periodeDreameTahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 flex items-end gap-2">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md flex-1">
                            <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                        </button>
                        <button type="button" onclick="exportArsipDreame('pdf')" class="button bg-theme-6 text-white shadow-md flex-1">
                            <i data-feather="file" class="w-4 h-4 inline mr-1"></i> PDF
                        </button>
                        <button type="button" onclick="exportArsipDreame('csv')" class="button bg-theme-9 text-white shadow-md flex-1">
                            <i data-feather="file-text" class="w-4 h-4 inline mr-1"></i> CSV
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-5" id="responsive-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table" style="min-width: 1000px;"> <!-- Ensure horizontal scroll triggers -->
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="whitespace-nowrap">Nama</th>
                                    <th class="whitespace-nowrap">Tanggal</th>
                                    <th class="whitespace-nowrap">No HP</th>
                                    <th class="whitespace-nowrap">Tipe</th>
                                    <th class="whitespace-nowrap">Kerusakan</th>
                                    <th class="whitespace-nowrap">Alamat</th>
                                    <th class="whitespace-nowrap text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($arsip_dreame)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-600 py-4">Tidak ada data arsip Dreamé.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($arsip_dreame as $row): ?>
                                        <tr class="bg-white border-b hover:bg-gray-100">
                                            <td class="font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                            <td><?= htmlspecialchars(isset($row['tipe_detail']) ? $row['tipe_detail'] : $row['tipe']) ?></td>
                                            <td><?= htmlspecialchars($row['kerusakan']) ?></td>
                                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                                            <td class="table-report__action w-56">
                                                <div class="flex justify-center items-center gap-2">
                                                    <button class="flex items-center text-theme-1"
                                                        onclick='editArsip(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                        <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit </button>
                                                    <a class="flex items-center text-theme-6"
                                                        href="<?= site_url('HR/delete_arsip/' . $row['arsip_id']) ?>"
                                                        onclick="return confirm('Yakin hapus arsip ini?')">
                                                        <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE 2: LAPTOP -->
        <div class="intro-y box mt-5 mb-5">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Arsip Dokumen Laptop
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0 gap-2">
                    <button class="button text-white bg-theme-1 shadow-md"
                        onclick="openModalArsip('laptop', 'add')">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Tambah Arsip
                    </button>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <form action="<?= site_url('HR/arsip') ?>" method="GET" class="grid grid-cols-12 gap-3">
                    <input type="hidden" name="tipe" value="Laptop">
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Tipe Periode</label>
                        <select name="siklus_laptop" id="siklusLaptop" class="input w-full border" onchange="changeSiklusLaptop(this.value)">
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Periode</label>
                        <input type="date" name="periode_harian" id="periodeLaptopHarian" class="input w-full border" style="display:none;">
                        <input type="week" name="periode_mingguan" id="periodeLaptopMingguan" class="input w-full border" style="display:none;">
                        <input type="month" name="periode_bulanan" id="periodeLaptopBulanan" class="input w-full border" value="<?= date('Y-m') ?>" style="display:block;">
                        <input type="number" name="periode_tahunan" id="periodeLaptopTahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 flex items-end gap-2">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md flex-1">
                            <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                        </button>
                        <button type="button" onclick="exportArsipLaptop('pdf')" class="button bg-theme-6 text-white shadow-md flex-1">
                            <i data-feather="file" class="w-4 h-4 inline mr-1"></i> PDF
                        </button>
                        <button type="button" onclick="exportArsipLaptop('csv')" class="button bg-theme-9 text-white shadow-md flex-1">
                            <i data-feather="file-text" class="w-4 h-4 inline mr-1"></i> CSV
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-5" id="responsive-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table" style="min-width: 1000px;">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="whitespace-nowrap">Nama</th>
                                    <th class="whitespace-nowrap">Tanggal</th>
                                    <th class="whitespace-nowrap">No HP</th>
                                    <th class="whitespace-nowrap">Tipe</th>
                                    <th class="whitespace-nowrap">Kerusakan</th>
                                    <th class="whitespace-nowrap">Alamat</th>
                                    <th class="whitespace-nowrap text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($arsip_laptop)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-600 py-4">Tidak ada data arsip Laptop.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($arsip_laptop as $row): ?>
                                        <tr class="bg-white border-b hover:bg-gray-100">
                                            <td class="font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                            <td><?= htmlspecialchars(isset($row['tipe_detail']) ? $row['tipe_detail'] : $row['tipe']) ?></td>
                                            <td><?= htmlspecialchars($row['kerusakan']) ?></td>
                                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                                            <td class="table-report__action w-56">
                                                <div class="flex justify-center items-center gap-2">
                                                    <button class="flex items-center text-theme-1"
                                                        onclick='editArsip(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                        <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit </button>
                                                    <a class="flex items-center text-theme-6"
                                                        href="<?= site_url('HR/delete_arsip/' . $row['arsip_id']) ?>"
                                                        onclick="return confirm('Yakin hapus arsip ini?')">
                                                        <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>

<!-- Modal Form -->
<dialog id="arsipModal" class="rounded-lg shadow-lg p-0" style="max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
    <div class="p-6">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Arsip</h2>
        
        <form id="arsipForm" method="POST">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="form-label font-bold">Nama Pelanggan</label>
                    <input type="text" id="inputNama" name="nama" class="input w-full border" required>
                </div>
                <div>
                    <label class="form-label font-bold">Tanggal</label>
                    <input type="date" id="inputTanggal" name="tanggal" class="input w-full border" required>
                </div>
                <div>
                    <label class="form-label font-bold">No HP</label>
                    <input type="text" id="inputNoHP" name="no_hp" class="input w-full border" required>
                </div>
                <div>
                    <label class="form-label font-bold">Tipe Detail</label>
                    <input type="text" id="inputTipeDetail" name="tipe_detail" class="input w-full border" placeholder="cth: Dreame L10 Prime" required>
                </div>
                <div>
                    <label class="form-label font-bold">Kerusakan</label>
                    <textarea id="inputKerusakan" name="kerusakan" class="input w-full border" rows="3" required></textarea>
                </div>
                <div>
                    <label class="form-label font-bold">Alamat</label>
                    <textarea id="inputAlamat" name="alamat" class="input w-full border" rows="3" required></textarea>
                </div>
            </div>

            <div class="flex gap-2 mt-6 justify-end">
                <button type="button" class="button bg-gray-200 text-gray-600" onclick="document.getElementById('arsipModal').close()">Batal</button>
                <button type="submit" class="button text-white bg-theme-1">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function openModalArsip(type, mode) {
    const modal = document.getElementById('arsipModal');
    const form = document.getElementById('arsipForm');
    const title = document.getElementById('modalTitle');

    title.textContent = 'Tambah Arsip ' + (type === 'dreame' ? 'Dreame' : 'Laptop');
    form.action = '<?= site_url('HR/add_arsip_') ?>' + type;
    
    // Clear form
    document.getElementById('inputNama').value = '';
    document.getElementById('inputTanggal').value = '<?= date('Y-m-d') ?>';
    document.getElementById('inputNoHP').value = '';
    document.getElementById('inputTipeDetail').value = '';
    document.getElementById('inputKerusakan').value = '';
    document.getElementById('inputAlamat').value = '';

    modal.showModal();
}

function editArsip(data) {
    const modal = document.getElementById('arsipModal');
    const form = document.getElementById('arsipForm');
    const title = document.getElementById('modalTitle');

    title.textContent = 'Edit Arsip';
    form.action = '<?= site_url('HR/edit_arsip/') ?>' + data.arsip_id;
    
    // Fill form with data
    document.getElementById('inputNama').value = data.nama;
    document.getElementById('inputTanggal').value = data.tanggal;
    document.getElementById('inputNoHP').value = data.no_hp;
    document.getElementById('inputTipeDetail').value = data.tipe_detail || data.tipe;
    document.getElementById('inputKerusakan').value = data.kerusakan;
    document.getElementById('inputAlamat').value = data.alamat;

    modal.showModal();
}

function changeSiklusDreame(val) {
    document.getElementById('periodeDreameHarian').style.display = 'none';
    document.getElementById('periodeDreameMingguan').style.display = 'none';
    document.getElementById('periodeDreameBulanan').style.display = 'none';
    document.getElementById('periodeDreameTahunan').style.display = 'none';
    
    if (val === 'harian') {
        document.getElementById('periodeDreameHarian').style.display = 'block';
    } else if (val === 'mingguan') {
        document.getElementById('periodeDreameMingguan').style.display = 'block';
    } else if (val === 'bulanan') {
        document.getElementById('periodeDreameBulanan').style.display = 'block';
    } else if (val === 'tahunan') {
        document.getElementById('periodeDreameTahunan').style.display = 'block';
    }
}

function changeSiklusLaptop(val) {
    document.getElementById('periodeLaptopHarian').style.display = 'none';
    document.getElementById('periodeLaptopMingguan').style.display = 'none';
    document.getElementById('periodeLaptopBulanan').style.display = 'none';
    document.getElementById('periodeLaptopTahunan').style.display = 'none';
    
    if (val === 'harian') {
        document.getElementById('periodeLaptopHarian').style.display = 'block';
    } else if (val === 'mingguan') {
        document.getElementById('periodeLaptopMingguan').style.display = 'block';
    } else if (val === 'bulanan') {
        document.getElementById('periodeLaptopBulanan').style.display = 'block';
    } else if (val === 'tahunan') {
        document.getElementById('periodeLaptopTahunan').style.display = 'block';
    }
}

function exportArsipDreame(format) {
    var siklus = document.getElementById('siklusDreame').value;
    var periode = '';
    
    if (siklus === 'harian') {
        periode = document.getElementById('periodeDreameHarian').value;
    } else if (siklus === 'mingguan') {
        periode = document.getElementById('periodeDreameMingguan').value;
    } else if (siklus === 'bulanan') {
        periode = document.getElementById('periodeDreameBulanan').value;
    } else if (siklus === 'tahunan') {
        periode = document.getElementById('periodeDreameTahunan').value;
    }
    
    var url = '<?= site_url('HR/export_arsip/') ?>' + format + '?tipe=Dreame&siklus=' + siklus + '&periode=' + periode;
    window.location.href = url;
}

function exportArsipLaptop(format) {
    var siklus = document.getElementById('siklusLaptop').value;
    var periode = '';
    
    if (siklus === 'harian') {
        periode = document.getElementById('periodeLaptopHarian').value;
    } else if (siklus === 'mingguan') {
        periode = document.getElementById('periodeLaptopMingguan').value;
    } else if (siklus === 'bulanan') {
        periode = document.getElementById('periodeLaptopBulanan').value;
    } else if (siklus === 'tahunan') {
        periode = document.getElementById('periodeLaptopTahunan').value;
    }
    
    var url = '<?= site_url('HR/export_arsip/') ?>' + format + '?tipe=Laptop&siklus=' + siklus + '&periode=' + periode;
    window.location.href = url;
}
</script>

<?php $this->load->view('Template/footer'); ?>