<?php $this->load->view('Template/header'); ?>

<!-- Main Container to Manage Flow -->
<div class="w-full" style="position: relative; top: 0; left: 0; z-index: 1;">

    <!-- HEADER SECTION -->
    <!-- Sangat Rapat: margin-top 5px, margin-bottom 0 -->
    <header class="page-header"
        style="position: relative; margin-top: 5px; margin-bottom: 0px; padding-bottom: 30px; z-index: 10;">
        <div class="header-title">
            <h1><i data-feather="home"></i> Dashboard Overview</h1>
            <p>Ringkasan statistik dan kinerja HR</p>
        </div>
    </header>

    <!-- CONTENT SECTION -->
    <!-- Content starts naturally after relative header -->
    <div class="content" style="position: relative; z-index: 5; top: -50px;">

        <!-- Absensi Chart -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Komposisi Absensi Hari Ini</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px; position: relative;">
                        <canvas id="absensiChart"></canvas>
                    </div>

                    <div class="mt-4 border-top pt-3">
                        <h6 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Detail Kehadiran</h6>
                        <?php foreach ($detail_absensi as $status => $names): ?>
                            <?php if (!empty($names)):
                                // Match badge colors with chart colors (case-insensitive)
                                $status_lower = strtolower($status);
                                $badge_color = '#6b7280'; // default gray
                        
                                // Green for Hadir
                                if (strpos($status_lower, 'hadir') !== false) {
                                    $badge_color = '#10B981'; // Success Green - same as chart
                                }
                                // Yellow/Orange for Izin/Cuti
                                elseif (strpos($status_lower, 'izin') !== false || strpos($status_lower, 'cuti') !== false) {
                                    $badge_color = '#F59E0B'; // Warning Orange - same as chart
                                }
                                // Red for Sakit/Telat/Alpha
                                elseif (
                                    strpos($status_lower, 'sakit') !== false ||
                                    strpos($status_lower, 'telat') !== false ||
                                    strpos($status_lower, 'alpha') !== false ||
                                    strpos($status_lower, 'tidak') !== false
                                ) {
                                    $badge_color = '#EF4444'; // Danger Red - same as chart
                                }
                                ?>
                                <div class="mb-2 d-flex align-items-center">
                                    <span class="badge rounded-pill px-3 py-1"
                                        style="font-size: 0.75rem; background-color: <?= $badge_color ?>; color: white; font-weight: 600;">
                                        <?= strtoupper($status) ?>
                                    </span>
                                    <span class="text-sm text-gray-700 ms-2"><?= implode(', ', $names) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (empty(array_filter($detail_absensi))): ?>
                            <p class="text-xs text-gray-400 italic">Belum ada data absensi hari ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Table/List (Using simple table for overview) -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">KPI Karyawan (Periode Ini)</h5>
                    <a href="<?= site_url('HR/kpi') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Score</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kpi_data)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Belum ada data KPI bulan ini</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $limit = 0;
                                    foreach ($kpi_data as $k):
                                        if ($limit >= 5)
                                            break; ?>
                                        <tr>
                                            <td><?= $k['nama_karyawan'] ?></td>
                                            <td><?= $k['posisi'] ?></td>
                                            <td><span
                                                    class="badge bg-primary rounded-pill"><?= number_format($k['rata_rata'], 2) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $badge_class = 'bg-secondary';
                                                if ($k['kategori'] == 'Sangat Baik')
                                                    $badge_class = 'bg-success';
                                                elseif ($k['kategori'] == 'Baik')
                                                    $badge_class = 'bg-info';
                                                elseif ($k['kategori'] == 'Cukup')
                                                    $badge_class = 'bg-warning';
                                                elseif ($k['kategori'] == 'Kurang')
                                                    $badge_class = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $badge_class ?>"><?= $k['kategori'] ?></span>
                                            </td>
                                        </tr>
                                        <?php $limit++; endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</main>
</div>

<!-- SCRIPTS FOR CHARTS -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Absensi Donut Chart
        var ctxAbsensi = document.getElementById('absensiChart').getContext('2d');
        var absensiData = <?= $chart_absensi ?>; // [Hadir, Izin, Telat]

        new Chart(ctxAbsensi, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin/Cuti', 'Telat'],
                datasets: [{
                    data: absensiData,
                    backgroundColor: [
                        '#10B981', // Success Green
                        '#F59E0B', // Warning Orange
                        '#EF4444'  // Danger Red
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

<?php $this->load->view('Template/footer'); ?>