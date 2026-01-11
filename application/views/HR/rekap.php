<?php $this->load->view('Template/header'); 

// Initialize variables for rekap_performa
$selected_bulan = isset($selected_bulan) ? $selected_bulan : date('Y-m');
$selected_tipe = isset($selected_tipe) ? $selected_tipe : '';
$rekap_karyawan = isset($rekap_karyawan) ? $rekap_karyawan : [];
$rekap_magang = isset($rekap_magang) ? $rekap_magang : [];
?>

<style>
    .tab-container {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .tab-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        margin-right: 0.5rem;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .tab-button:hover {
        color: #374151;
        background-color: #f9fafb;
    }
    
    .tab-button.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        background-color: #eff6ff;
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease-in;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>

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

        <!-- Tab Navigation -->
        <div class="intro-y box mt-5">
            <div class="tab-container" style="padding: 0 1.25rem;">
                <button class="tab-button active" onclick="switchTab('kpi')">
                    <i data-feather="award" class="inline w-4 h-4 mr-2"></i>
                    Rekap Penilaian Kinerja
                </button>
                <button class="tab-button" onclick="switchTab('laporan')">
                    <i data-feather="book-open" class="inline w-4 h-4 mr-2"></i>
                    Laporan Kinerja Mingguan
                </button>
                <button class="tab-button" onclick="switchTab('poin')">
                    <i data-feather="star" class="inline w-4 h-4 mr-2"></i>
                    Poin Performa Mingguan
                </button>
                <button class="tab-button" onclick="switchTab('performa')">
                    <i data-feather="trending-up" class="inline w-4 h-4 mr-2"></i>
                    Rekap Performa Bulanan
                </button>
            </div>

            <!-- TAB 1: REKAP PENILAIAN KINERJA (KPI) -->
            <div id="tab-kpi" class="tab-content active">
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
                        
                        <div class="col-span-12 sm:col-span-2">
                            <label class="form-label font-bold">Periode</label>
                            <input type="date" name="periode_harian" id="periodeKPIHarian" class="input w-full border" value="<?= date('Y-m-d') ?>" style="display:none;">
                            <input type="week" name="periode_mingguan" id="periodeKPIMingguan" class="input w-full border" style="display:none;">
                            <input type="month" name="periode_kpi" id="periodeKPIBulanan" class="input w-full border" value="<?= $selected_periode ?>" style="display:block;">
                            <input type="number" name="periode_tahunan" id="periodeKPITahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                        </div>
                        
                        <div class="col-span-12 sm:col-span-7 flex items-end gap-2 justify-end">
                            <button type="submit" class="button text-white bg-blue-600 shadow-md px-6 py-2 font-semibold rounded hover:bg-blue-700 w-32">
                                <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                            </button>
                            <button type="button" onclick="exportKPI('pdf')" class="button bg-red-600 text-white shadow-md px-6 py-2 font-semibold rounded hover:bg-red-700 w-28">
                                <i data-feather="file-pdf" class="w-4 h-4 inline mr-1"></i> PDF
                            </button>
                            <button type="button" onclick="exportKPI('csv')" class="button bg-green-600 text-white shadow-md px-6 py-2 font-semibold rounded hover:bg-green-700 w-28">
                                <i data-feather="download" class="w-4 h-4 inline mr-1"></i> CSV
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

            <!-- TAB 2: LAPORAN KINERJA MINGGUAN (ARSIP) -->
            <div id="tab-laporan" class="tab-content">
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
                        
                        <div class="col-span-12 sm:col-span-2">
                            <label class="form-label font-bold">Periode</label>
                            <input type="week" name="periode_arsip_mingguan" id="periodeArsipMingguan" class="input w-full border" value="<?= date('Y') ?>-W<?= date('W') ?>" style="display:block;">
                            <input type="month" name="periode_arsip_bulanan" id="periodeArsipBulanan" class="input w-full border" style="display:none;">
                            <input type="number" name="periode_arsip_tahunan" id="periodeArsipTahunan" class="input w-full border" placeholder="YYYY" min="2020" max="2099" style="display:none;">
                        </div>
                        
                        <div class="col-span-12 sm:col-span-7 flex items-end gap-2 justify-end">
                            <button type="submit" class="button text-white bg-blue-600 shadow-md px-6 py-2 font-semibold rounded hover:bg-blue-700 w-32">
                                <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                            </button>
                            <button type="button" onclick="exportArsipLaporan('pdf')" class="button bg-red-600 text-white shadow-md px-6 py-2 font-semibold rounded hover:bg-red-700 w-28">
                                <i data-feather="file-pdf" class="w-4 h-4 inline mr-1"></i> PDF
                            </button>
                            <button type="button" onclick="exportArsipLaporan('csv')" class="button bg-green-600 text-white shadow-md px-6 py-2 font-semibold rounded hover:bg-green-700 w-28">
                                <i data-feather="download" class="w-4 h-4 inline mr-1"></i> CSV
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

            <!-- TAB 3: POIN PERFORMA MINGGUAN -->
            <div id="tab-poin" class="tab-content">
                <div class="p-5 border-b border-gray-200 bg-gray-50">
                    <form action="<?= site_url('HR/rekap') ?>" method="GET" class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 sm:col-span-3">
                            <label class="form-label font-bold">Tipe Karyawan</label>
                            <select name="tipe_performa" class="input w-full border">
                                <option value="" <?= ($selected_tipe_performa == '') ? 'selected' : '' ?>>Semua</option>
                                <option value="Karyawan" <?= ($selected_tipe_performa == 'Karyawan') ? 'selected' : '' ?>>Karyawan</option>
                                <option value="Magang" <?= ($selected_tipe_performa == 'Magang') ? 'selected' : '' ?>>Magang</option>
                            </select>
                        </div>
                        
                        <div class="col-span-12 sm:col-span-2">
                            <label class="form-label font-bold">Periode</label>
                            <input type="week" name="periode_performa" class="input w-full border" value="<?= $selected_periode_performa ?>">
                        </div>
                        
                        <div class="col-span-12 sm:col-span-7 flex items-end gap-2 justify-end">
                            <button type="submit" class="button text-white bg-blue-600 shadow-md px-6 py-2 font-semibold rounded hover:bg-blue-700 w-32">
                                <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
                <div class="p-5">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="whitespace-nowrap">Nama Karyawan</th>
                                        <th class="whitespace-nowrap">Posisi</th>
                                        <th class="whitespace-nowrap">Tipe</th>
                                        <th class="text-center whitespace-nowrap">Total Poin</th>
                                        <th class="text-center whitespace-nowrap">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($poin_performa_list)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-600 py-4">Belum ada data poin performa.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($poin_performa_list as $row): ?>
                                            <tr class="bg-white border-b">
                                                <td class="font-medium"><?= $row['nama_karyawan'] ?></td>
                                                <td class="text-gray-600"><?= $row['posisi'] ?></td>
                                                <td><span class="rounded px-2 py-1 text-xs bg-blue-100 text-blue-600"><?= $row['tipe_karyawan'] ?></span></td>
                                                <td class="text-center font-bold text-theme-1"><?= $row['total_poin'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $badge = 'bg-gray-200 text-gray-600';
                                                    $tipe = $row['tipe_karyawan'];
                                                    $total = $row['total_poin'];
                                                    if ($tipe === 'Karyawan') {
                                                        if ($total >= 80) { $badge = 'bg-green-100 text-green-600'; $label = 'Excellent'; }
                                                        elseif ($total >= 70) { $badge = 'bg-blue-100 text-blue-600'; $label = 'Good'; }
                                                        elseif ($total >= 60) { $badge = 'bg-yellow-100 text-yellow-600'; $label = 'Adequate'; }
                                                        else { $badge = 'bg-red-100 text-red-600'; $label = 'Below'; }
                                                    } else {
                                                        if ($total >= 90) { $badge = 'bg-green-100 text-green-600'; $label = 'Excellent'; }
                                                        elseif ($total >= 75) { $badge = 'bg-blue-100 text-blue-600'; $label = 'Good'; }
                                                        elseif ($total >= 60) { $badge = 'bg-yellow-100 text-yellow-600'; $label = 'Adequate'; }
                                                        else { $badge = 'bg-red-100 text-red-600'; $label = 'Below'; }
                                                    }
                                                    ?>
                                                    <span class="rounded px-2 py-1 text-xs <?= $badge ?>"><?= $label ?></span>
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

            <!-- TAB 4: REKAP PERFORMA BULANAN -->
            <div id="tab-performa" class="tab-content">
                <div class="p-5 border-b border-gray-200 bg-gray-50">
                    <form method="get" class="grid grid-cols-12 gap-3 items-end">
                        <input type="hidden" name="tab" value="performa">
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block font-bold text-sm mb-2">Bulan</label>
                            <input type="month" name="bulan" class="input border w-full" value="<?= $selected_bulan ?>">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block font-bold text-sm mb-2">Tipe</label>
                            <select name="tipe" class="input border w-full">
                                <option value="">Semua</option>
                                <option value="Karyawan" <?= $selected_tipe == 'Karyawan' ? 'selected' : '' ?>>Karyawan</option>
                                <option value="Magang" <?= $selected_tipe == 'Magang' ? 'selected' : '' ?>>Magang</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-6 flex items-end gap-2 justify-end">
                            <button type="submit" class="button bg-blue-600 text-white px-6 py-2 font-semibold rounded shadow-md hover:shadow-lg hover:bg-blue-700 w-32">
                                <i data-feather="filter" class="w-4 h-4 inline mr-1"></i> Filter
                            </button>
                            <a href="<?= site_url('HR/export_rekap_pdf?bulan=' . $selected_bulan . '&tipe=' . $selected_tipe) ?>" class="button bg-red-600 text-white px-6 py-2 font-semibold rounded shadow-md hover:shadow-lg hover:bg-red-700 w-28">
                                <i data-feather="file-pdf" class="w-4 h-4 inline mr-1"></i> PDF
                            </a>
                            <a href="<?= site_url('HR/export_rekap_performa_csv?bulan=' . $selected_bulan . '&tipe=' . $selected_tipe) ?>" class="button bg-green-600 text-white px-6 py-2 font-semibold rounded shadow-md hover:shadow-lg hover:bg-green-700 w-28">
                                <i data-feather="download" class="w-4 h-4 inline mr-1"></i> CSV
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-12 gap-5 p-5">
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i data-feather="users" class="w-6 h-6 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 text-sm">Total Karyawan</div>
                                    <div class="text-2xl font-bold"><?= count($rekap_karyawan) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i data-feather="user-plus" class="w-6 h-6 text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 text-sm">Total Magang</div>
                                    <div class="text-2xl font-bold"><?= count($rekap_magang) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                    <i data-feather="star" class="w-6 h-6 text-yellow-600"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 text-sm">Top Performer</div>
                                    <div class="text-2xl font-bold">
                                        <?php 
                                        $top = 0;
                                        foreach (array_merge($rekap_karyawan, $rekap_magang) as $r) {
                                            if ($r['level_performa'] == 'Top Performer') $top++;
                                        }
                                        echo $top;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i data-feather="trending-up" class="w-6 h-6 text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 text-sm">Rata-rata Poin</div>
                                    <div class="text-2xl font-bold">
                                        <?php 
                                        $all_data = array_merge($rekap_karyawan, $rekap_magang);
                                        $avg = 0;
                                        if (count($all_data) > 0) {
                                            $total = 0;
                                            foreach ($all_data as $d) $total += $d['rata_rata_poin'];
                                            $avg = number_format($total / count($all_data), 1);
                                        }
                                        echo $avg;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ranking Karyawan -->
                <?php if (!empty($rekap_karyawan)): ?>
                <div class="box p-5 m-5 mb-5">
                    <h3 class="text-lg font-medium mb-4">
                        <i data-feather="award" class="w-5 h-5 inline-block mr-2 text-blue-600"></i>
                        Ranking Karyawan - <?= date('F Y', strtotime($selected_bulan . '-01')) ?>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="table table-report w-full">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border-b-2 text-center">RANK</th>
                                    <th class="border-b-2">NAMA</th>
                                    <th class="border-b-2 text-center">POSISI</th>
                                    <th class="border-b-2 text-center">TOTAL POIN</th>
                                    <th class="border-b-2 text-center">MINGGU</th>
                                    <th class="border-b-2 text-center">RATA-RATA</th>
                                    <th class="border-b-2 text-center">LEVEL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rekap_karyawan as $r): ?>
                                <tr class="<?= $r['ranking'] <= 3 ? 'bg-yellow-50' : '' ?>">
                                    <td class="text-center border-b">
                                        <?php if ($r['ranking'] == 1): ?>
                                            <span class="text-2xl">🥇</span>
                                        <?php elseif ($r['ranking'] == 2): ?>
                                            <span class="text-2xl">🥈</span>
                                        <?php elseif ($r['ranking'] == 3): ?>
                                            <span class="text-2xl">🥉</span>
                                        <?php else: ?>
                                            <span class="font-bold"><?= $r['ranking'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border-b font-medium"><?= $r['nama_karyawan'] ?></td>
                                    <td class="text-center border-b"><?= $r['posisi'] ?></td>
                                    <td class="text-center border-b font-bold text-lg"><?= $r['total_poin_bulan'] ?></td>
                                    <td class="text-center border-b"><?= $r['jumlah_minggu'] ?> minggu</td>
                                    <td class="text-center border-b"><?= number_format($r['rata_rata_poin'], 1) ?></td>
                                    <td class="text-center border-b">
                                        <span class="rounded px-3 py-1 text-sm font-medium
                                            <?php 
                                            if ($r['level_performa'] == 'Top Performer') echo 'bg-green-100 text-green-700';
                                            elseif ($r['level_performa'] == 'High Performer') echo 'bg-blue-100 text-blue-700';
                                            elseif ($r['level_performa'] == 'Average') echo 'bg-yellow-100 text-yellow-700';
                                            else echo 'bg-red-100 text-red-700';
                                            ?>
                                        ">
                                            <?= $r['level_performa'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ranking Magang -->
                <?php if (!empty($rekap_magang)): ?>
                <div class="box p-5 m-5 mb-5">
                    <h3 class="text-lg font-medium mb-4">
                        <i data-feather="award" class="w-5 h-5 inline-block mr-2 text-purple-600"></i>
                        Ranking Peserta Magang - <?= date('F Y', strtotime($selected_bulan . '-01')) ?>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="table table-report w-full">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border-b-2 text-center">RANK</th>
                                    <th class="border-b-2">NAMA</th>
                                    <th class="border-b-2 text-center">POSISI</th>
                                    <th class="border-b-2 text-center">TOTAL POIN</th>
                                    <th class="border-b-2 text-center">MINGGU</th>
                                    <th class="border-b-2 text-center">RATA-RATA</th>
                                    <th class="border-b-2 text-center">LEVEL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rekap_magang as $r): ?>
                                <tr class="<?= $r['ranking'] <= 3 ? 'bg-yellow-50' : '' ?>">
                                    <td class="text-center border-b">
                                        <?php if ($r['ranking'] == 1): ?>
                                            <span class="text-2xl">🥇</span>
                                        <?php elseif ($r['ranking'] == 2): ?>
                                            <span class="text-2xl">🥈</span>
                                        <?php elseif ($r['ranking'] == 3): ?>
                                            <span class="text-2xl">🥉</span>
                                        <?php else: ?>
                                            <span class="font-bold"><?= $r['ranking'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border-b font-medium"><?= $r['nama_karyawan'] ?></td>
                                    <td class="text-center border-b"><?= $r['posisi'] ?></td>
                                    <td class="text-center border-b font-bold text-lg"><?= $r['total_poin_bulan'] ?></td>
                                    <td class="text-center border-b"><?= $r['jumlah_minggu'] ?> minggu</td>
                                    <td class="text-center border-b"><?= number_format($r['rata_rata_poin'], 1) ?></td>
                                    <td class="text-center border-b">
                                        <span class="rounded px-3 py-1 text-sm font-medium
                                            <?php 
                                            if ($r['level_performa'] == 'Top Performer') echo 'bg-green-100 text-green-700';
                                            elseif ($r['level_performa'] == 'High Performer') echo 'bg-blue-100 text-blue-700';
                                            elseif ($r['level_performa'] == 'Average') echo 'bg-yellow-100 text-yellow-700';
                                            else echo 'bg-red-100 text-red-700';
                                            ?>
                                        ">
                                            <?= $r['level_performa'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabName).classList.add('active');
        
        // Add active class to clicked button
        event.target.closest('.tab-button').classList.add('active');
        
        // Reinitialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

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
