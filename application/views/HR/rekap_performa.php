<?php 
// Initialize variables with defaults
$selected_bulan = isset($selected_bulan) ? $selected_bulan : date('Y-m');
$selected_tipe = isset($selected_tipe) ? $selected_tipe : '';
$rekap_karyawan = isset($rekap_karyawan) ? $rekap_karyawan : [];
$rekap_magang = isset($rekap_magang) ? $rekap_magang : [];
$chart_karyawan = isset($chart_karyawan) ? $chart_karyawan : [];
$chart_magang = isset($chart_magang) ? $chart_magang : [];
$kpi_list = isset($kpi_list) ? $kpi_list : [];

$this->load->view('Template/header'); 
?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }
    .page-header {
        padding: 1rem 2rem;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }
    .header-title h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
    }
    .header-title p {
        color: #6b7280;
        font-size: 0.875rem;
    }
</style>

<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>Rekap Performa Bulanan</h1>
        <p>Ranking & evaluasi performa bulanan karyawan & peserta magang</p>
    </div>
</header>

<div class="content-area">
    <div class="sukses" data-sukses="<?= $this->session->flashdata('sukses'); ?>"></div>
    <div class="gagal" data-gagal="<?= $this->session->flashdata('gagal'); ?>"></div>

    <!-- Filter & Actions -->
    <div class="intro-y box p-5 mb-5">
        <div class="flex flex-wrap items-end gap-4">
            <form method="get" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="mb-2 block">Bulan</label>
                    <input type="month" name="bulan" class="input border" value="<?= $selected_bulan ?>">
                </div>
                <div>
                    <label class="mb-2 block">Tipe</label>
                    <select name="tipe" class="input border">
                        <option value="">Semua</option>
                        <option value="Karyawan" <?= $selected_tipe == 'Karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="Magang" <?= $selected_tipe == 'Magang' ? 'selected' : '' ?>>Magang</option>
                    </select>
                </div>
                <button type="submit" class="button bg-theme-1 text-white">
                    <i data-feather="filter" class="w-4 h-4 mr-1"></i> Filter
                </button>
            </form>
            
            <form action="<?= site_url('HR/generate_rekap') ?>" method="post" class="ml-auto">
                <input type="hidden" name="bulan" value="<?= $selected_bulan ?>">
                <button type="submit" class="button bg-theme-9 text-white" onclick="return confirm('Generate rekap untuk bulan <?= $selected_bulan ?>?')">
                    <i data-feather="refresh-cw" class="w-4 h-4 mr-1"></i> Generate Rekap
                </button>
            </form>
            
            <a href="<?= site_url('HR/export_rekap_performa_csv?bulan=' . $selected_bulan . '&tipe=' . $selected_tipe) ?>" class="button bg-green-600 text-white">
                <i data-feather="download" class="w-4 h-4 mr-1"></i> Export CSV
            </a>
            
            <a href="<?= site_url('HR/poin_performa') ?>" class="button bg-theme-6 text-white">
                <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Input Poin
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-12 gap-5 mb-5">
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
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
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
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
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
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
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <i data-feather="trending-up" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Rata-rata KPI</div>
                        <div class="text-2xl font-bold">
                            <?php 
                            $avg = 0;
                            if (count($kpi_list) > 0) {
                                $total = 0;
                                foreach ($kpi_list as $k) $total += $k['rata_rata'];
                                $avg = number_format($total / count($kpi_list), 2);
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
    <div class="intro-y box p-5 mb-5">
        <h3 class="text-lg font-medium mb-4">
            <i data-feather="award" class="w-5 h-5 inline-block mr-2 text-blue-600"></i>
            Ranking Karyawan - <?= date('F Y', strtotime($selected_bulan . '-01')) ?>
        </h3>
        <div class="overflow-x-auto">
            <table class="table table-report w-full">
                <thead>
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
                            <?php 
                            $level_class = 'bg-gray-200 text-gray-700';
                            if ($r['level_performa'] == 'Top Performer') $level_class = 'bg-yellow-200 text-yellow-700';
                            elseif ($r['level_performa'] == 'Advanced') $level_class = 'bg-green-200 text-green-700';
                            elseif ($r['level_performa'] == 'Intermediate') $level_class = 'bg-blue-200 text-blue-700';
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs <?= $level_class ?>"><?= $r['level_performa'] ?></span>
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
    <div class="intro-y box p-5 mb-5">
        <h3 class="text-lg font-medium mb-4">
            <i data-feather="award" class="w-5 h-5 inline-block mr-2 text-purple-600"></i>
            Ranking Peserta Magang - <?= date('F Y', strtotime($selected_bulan . '-01')) ?>
        </h3>
        <div class="overflow-x-auto">
            <table class="table table-report w-full">
                <thead>
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
                    <tr class="<?= $r['ranking'] <= 3 ? 'bg-purple-50' : '' ?>">
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
                            <?php 
                            $level_class = 'bg-gray-200 text-gray-700';
                            if ($r['level_performa'] == 'Top Performer') $level_class = 'bg-yellow-200 text-yellow-700';
                            elseif ($r['level_performa'] == 'Advanced') $level_class = 'bg-green-200 text-green-700';
                            elseif ($r['level_performa'] == 'Intermediate') $level_class = 'bg-blue-200 text-blue-700';
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs <?= $level_class ?>"><?= $r['level_performa'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chart Section -->
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12 lg:col-span-6 intro-y">
            <div class="box p-5">
                <h3 class="font-medium mb-4">Grafik Performa Karyawan</h3>
                <?php if (!empty($chart_karyawan)): ?>
                <canvas id="chartKaryawan" height="200"></canvas>
                <?php else: ?>
                <p class="text-gray-500 text-center py-10">Belum ada data performa karyawan</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 intro-y">
            <div class="box p-5">
                <h3 class="font-medium mb-4">Grafik Performa Magang</h3>
                <?php if (!empty($chart_magang)): ?>
                <canvas id="chartMagang" height="200"></canvas>
                <?php else: ?>
                <p class="text-gray-500 text-center py-10">Belum ada data performa magang</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info Briefing -->
    <div class="intro-y box p-5 mt-5 bg-blue-50 border border-blue-200">
        <h3 class="font-medium text-blue-800 mb-2">
            <i data-feather="info" class="w-5 h-5 inline-block mr-2"></i>
            Catatan Briefing Evaluasi
        </h3>
        <p class="text-blue-700 text-sm">
            Setiap bulan akan diadakan briefing evaluasi untuk menyampaikan karyawan dan peserta magang dengan penilaian terbaik. 
            Selanjutnya akan diberikan reward sebagai bentuk apresiasi dan motivasi agar performa ke depannya dapat terus meningkat.
        </p>
        <?php 
        $all_rekap = array_merge($rekap_karyawan, $rekap_magang);
        if (!empty($all_rekap)):
            usort($all_rekap, function($a, $b) { return $b['total_poin_bulan'] - $a['total_poin_bulan']; });
            $top3 = array_slice($all_rekap, 0, 3);
        ?>
        <div class="mt-3">
            <strong class="text-blue-800">Top 3 Bulan Ini:</strong>
            <ul class="list-disc list-inside text-blue-700 text-sm mt-1">
                <?php foreach ($top3 as $idx => $t): ?>
                <li><?= ($idx + 1) ?>. <?= $t['nama_karyawan'] ?> (<?= $t['tipe_karyawan'] ?>) - <?= $t['total_poin_bulan'] ?> poin</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php else: ?>
        <div class="mt-3">
            <p class="text-blue-600 text-sm italic">Belum ada data rekap untuk bulan ini. Silakan generate rekap terlebih dahulu.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
if (typeof feather !== 'undefined') feather.replace();

<?php if (!empty($chart_karyawan)): ?>
// Chart Karyawan
var ctxK = document.getElementById('chartKaryawan').getContext('2d');
new Chart(ctxK, {
    type: 'bar',
    data: {
        labels: [<?php foreach ($chart_karyawan as $c) echo "'" . $c['nama_karyawan'] . "',"; ?>],
        datasets: [{
            label: 'Total Poin',
            data: [<?php foreach ($chart_karyawan as $c) echo $c['total_poin_bulan'] . ","; ?>],
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
<?php endif; ?>

<?php if (!empty($chart_magang)): ?>
// Chart Magang
var ctxM = document.getElementById('chartMagang').getContext('2d');
new Chart(ctxM, {
    type: 'bar',
    data: {
        labels: [<?php foreach ($chart_magang as $c) echo "'" . $c['nama_karyawan'] . "',"; ?>],
        datasets: [{
            label: 'Total Poin',
            data: [<?php foreach ($chart_magang as $c) echo $c['total_poin_bulan'] . ","; ?>],
            backgroundColor: 'rgba(147, 51, 234, 0.7)',
            borderColor: 'rgba(147, 51, 234, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
<?php endif; ?>
</script>

<?php $this->load->view('Template/footer'); ?>
