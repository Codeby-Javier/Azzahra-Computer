<?php $this->load->view('Template/header'); ?>

<!-- Layout Container -->
<div class="w-full" style="position: relative; overflow-x: hidden;">

    <!-- Header Section -->
    <header class="page-header" style="position: relative; margin-top: 0px; margin-bottom: 0px; padding-bottom: 30px; z-index: 10;">
        <div class="header-title">
            <h1><i data-feather="file-text"></i> Rekap Performa HR</h1>
            <p>Laporan kinerja dan rekapitulasi mingguan</p>
        </div>
    </header>

    <!-- Content Section -->
    <div class="content" style="position: relative; z-index: 5; top: -50px;">

        <!-- REKAP PENILAIAN -->
        <div class="intro-y box mt-5">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Rekap Penilaian Kinerja
                </h2>
            </div>
            
            <!-- Filter Section -->
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <form action="<?= site_url('HR/rekap') ?>" method="GET" class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Tipe Periode</label>
                        <select name="siklus_kpi" id="siklusKPI" class="input w-full border" onchange="changeSiklusKPI(this.value)">
                            <option value="harian" <?= ($selected_siklus == 'harian') ? 'selected' : '' ?>>Harian</option>
                            <option value="mingguan" <?= ($selected_siklus == 'mingguan') ? 'selected' : '' ?>>Mingguan</option>
                            <option value="bulanan" <?= ($selected_siklus == 'bulanan') ? 'selected' : '' ?>>Bulanan</option>
                            <option value="tahunan" <?= ($selected_siklus == 'tahunan') ? 'selected' : '' ?>>Tahunan</option>
                        </select>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Periode</label>
                        <input type="date" name="periode_harian" id="periodeKPIHarian" class="input w-full border" value="<?= date('Y-m-d') ?>" style="display:none;">
                        <input type="week" name="periode_mingguan" id="periodeKPIMingguan" class="input w-full border" style="display:none;">
                        <input type="month" name="periode_bulanan" id="periodeKPIBulanan" class="input w-full border" value="<?= $selected_periode ?>" style="display:block;">
                        <input type="number" name="periode_tahunan" id="periodeKPITahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 flex items-end gap-2">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md flex-1">
                            <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                        </button>
                        <button type="button" onclick="exportKPI('pdf')" class="button bg-theme-6 text-white shadow-md flex-1">
                            <i data-feather="file" class="w-4 h-4 inline mr-1"></i> PDF
                        </button>
                        <button type="button" onclick="exportKPI('csv')" class="button bg-theme-9 text-white shadow-md flex-1">
                            <i data-feather="file-text" class="w-4 h-4 inline mr-1"></i> CSV
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-5" id="responsive-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th rowspan="2" class="whitespace-nowrap">Nama Karyawan</th>
                                    <th rowspan="2" class="whitespace-nowrap">Posisi</th>
                                    <th colspan="4" class="text-center whitespace-nowrap">Aspek Penilaian</th>
                                    <th rowspan="2" class="text-center whitespace-nowrap">Total</th>
                                    <th rowspan="2" class="text-center whitespace-nowrap">Rata²</th>
                                    <th rowspan="2" class="whitespace-nowrap">Kategori</th>
                                </tr>
                                <tr>
                                    <th class="text-center text-gray-600 text-xs">Disiplin</th>
                                    <th class="text-center text-gray-600 text-xs">Kualitas</th>
                                    <th class="text-center text-gray-600 text-xs">Prod</th>
                                    <th class="text-center text-gray-600 text-xs">Team</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kpi_list)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-gray-600 py-4">Belum ada data rekap.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($kpi_list as $row): ?>
                                        <tr class="bg-white border-b">
                                            <td class="font-medium"><?= $row['nama_karyawan'] ?></td>
                                            <td class="text-gray-600"><?= $row['posisi'] ?></td>
                                            <td class="text-center"><?= $row['kedisiplinan'] ?></td>
                                            <td class="text-center"><?= $row['kualitas_kerja'] ?></td>
                                            <td class="text-center"><?= $row['produktivitas'] ?></td>
                                            <td class="text-center"><?= $row['kerja_tim'] ?></td>
                                            <td class="text-center font-bold text-theme-1"><?= $row['total'] ?></td>
                                            <td class="text-center font-bold"><?= number_format($row['rata_rata'], 2) ?></td>
                                            <td>
                                                <?php
                                                $badge_cls = 'bg-gray-200 text-gray-600';
                                                if ($row['kategori'] == 'Sangat Baik' || $row['kategori'] == 'Baik')
                                                    $badge_cls = 'bg-theme-9 text-white';
                                                elseif ($row['kategori'] == 'Cukup')
                                                    $badge_cls = 'bg-theme-12 text-white';
                                                elseif ($row['kategori'] == 'Kurang')
                                                    $badge_cls = 'bg-theme-6 text-white';
                                                ?>
                                                <span
                                                    class="rounded px-2 py-1 text-xs <?= $badge_cls ?>"><?= $row['kategori'] ?></span>
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

        <!-- LAPORAN MINGGUAN -->
        <div class="intro-y box mt-5 mb-5">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Laporan Kinerja Mingguan (Arsip)
                </h2>
            </div>
            
            <!-- Filter Section -->
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <form action="<?= site_url('HR/rekap') ?>" method="GET" class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Tipe Periode</label>
                        <select name="siklus_arsip" id="siklusArsip" class="input w-full border" onchange="changeSiklusArsip(this.value)">
                            <option value="mingguan" selected>Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="form-label font-bold">Periode</label>
                        <input type="week" name="periode_arsip_mingguan" id="periodeArsipMingguan" class="input w-full border" value="<?= date('Y') ?>-W<?= date('W') ?>" style="display:block;">
                        <input type="month" name="periode_arsip_bulanan" id="periodeArsipBulanan" class="input w-full border" style="display:none;">
                        <input type="number" name="periode_arsip_tahunan" id="periodeArsipTahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 flex items-end gap-2">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md flex-1">
                            <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                        </button>
                        <button type="button" onclick="exportArsipLaporan('pdf')" class="button bg-theme-6 text-white shadow-md flex-1">
                            <i data-feather="file" class="w-4 h-4 inline mr-1"></i> PDF
                        </button>
                        <button type="button" onclick="exportArsipLaporan('csv')" class="button bg-theme-9 text-white shadow-md flex-1">
                            <i data-feather="file-text" class="w-4 h-4 inline mr-1"></i> CSV
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-5" id="responsive-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="whitespace-nowrap">Karyawan</th>
                                    <th class="whitespace-nowrap">Periode</th>
                                    <th class="whitespace-nowrap">Target</th>
                                    <th class="whitespace-nowrap">Realisasi</th>
                                    <th class="whitespace-nowrap">Kendala & Solusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($laporan_list)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-600 py-4">Tidak ada laporan mingguan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($laporan_list as $lap): ?>
                                        <tr class="bg-white border-b">
                                            <td class="font-medium">
                                                <?= $lap['nama_karyawan'] ?>
                                                <div class="text-gray-600 text-xs"><?= $lap['posisi'] ?></div>
                                            </td>
                                            <td>
                                                <?php
                                                // Format periode to readable date range
                                                if (strpos($lap['periode'], '-W') !== false) {
                                                    list($year, $week_part) = explode('-W', $lap['periode']);
                                                    $week_num = intval($week_part);
                                                    $start_date = date('Y-m-d', strtotime($year . 'W' . str_pad($week_num, 2, '0', STR_PAD_LEFT)));
                                                    $end_date = date('Y-m-d', strtotime($start_date . ' +6 days'));
                                                    echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));
                                                } else {
                                                    echo $lap['periode'];
                                                }
                                                ?>
                                            </td>
                                            <td><?= $lap['target_mingguan'] ?></td>
                                            <td>
                                                <div class="font-medium">Tugas:</div> <?= $lap['tugas_dilakukan'] ?>
                                                <div class="font-medium mt-1">Hasil:</div> <?= $lap['hasil'] ?>
                                            </td>
                                            <td>
                                                <div class="text-theme-6">Kendala:</div> <?= $lap['kendala'] ?>
                                                <div class="text-theme-9 mt-1">Solusi:</div> <?= $lap['solusi'] ?>
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

<script>
function changeSiklusKPI(val) {
    document.getElementById('periodeKPIHarian').style.display = 'none';
    document.getElementById('periodeKPIMingguan').style.display = 'none';
    document.getElementById('periodeKPIBulanan').style.display = 'none';
    document.getElementById('periodeKPITahunan').style.display = 'none';
    
    if (val === 'harian') {
        document.getElementById('periodeKPIHarian').style.display = 'block';
    } else if (val === 'mingguan') {
        document.getElementById('periodeKPIMingguan').style.display = 'block';
    } else if (val === 'bulanan') {
        document.getElementById('periodeKPIBulanan').style.display = 'block';
    } else if (val === 'tahunan') {
        document.getElementById('periodeKPITahunan').style.display = 'block';
    }
}

function changeSiklusArsip(val) {
    document.getElementById('periodeArsipMingguan').style.display = 'none';
    document.getElementById('periodeArsipBulanan').style.display = 'none';
    document.getElementById('periodeArsipTahunan').style.display = 'none';
    
    if (val === 'mingguan') {
        document.getElementById('periodeArsipMingguan').style.display = 'block';
    } else if (val === 'bulanan') {
        document.getElementById('periodeArsipBulanan').style.display = 'block';
    } else if (val === 'tahunan') {
        document.getElementById('periodeArsipTahunan').style.display = 'block';
    }
}

function exportKPI(format) {
    var siklus = document.getElementById('siklusKPI').value;
    var periode = '';
    
    if (siklus === 'harian') {
        periode = document.getElementById('periodeKPIHarian').value;
    } else if (siklus === 'mingguan') {
        periode = document.getElementById('periodeKPIMingguan').value;
    } else if (siklus === 'bulanan') {
        periode = document.getElementById('periodeKPIBulanan').value;
    } else if (siklus === 'tahunan') {
        periode = document.getElementById('periodeKPITahunan').value;
    }
    
    var url = '<?= site_url('HR/export_kpi_') ?>' + format + '?siklus=' + siklus + '&periode=' + periode;
    window.location.href = url;
}

function exportArsipLaporan(format) {
    var siklus = document.getElementById('siklusArsip').value;
    var periode = '';
    
    if (siklus === 'mingguan') {
        periode = document.getElementById('periodeArsipMingguan').value;
    } else if (siklus === 'bulanan') {
        periode = document.getElementById('periodeArsipBulanan').value;
    } else if (siklus === 'tahunan') {
        periode = document.getElementById('periodeArsipTahunan').value;
    }
    
    var url = '<?= site_url('HR/export_laporan_mingguan_') ?>' + format + '?siklus=' + siklus + '&periode=' + periode;
    window.location.href = url;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    var siklusKPI = '<?= $selected_siklus ?? 'bulanan' ?>';
    document.getElementById('siklusKPI').value = siklusKPI;
    changeSiklusKPI(siklusKPI);
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>

<?php $this->load->view('Template/footer'); ?>