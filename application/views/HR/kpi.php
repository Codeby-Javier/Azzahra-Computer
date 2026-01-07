<?php $this->load->view('Template/header'); ?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }

    .nav-pills .nav-link {
        border-radius: 0.375rem;
        padding: 0.5rem 1.5rem;
        margin-right: 0.5rem;
        transition: all 0.2s;
    }

    .nav-pills .nav-link.active {
        background-color: #6366f1;
        color: white;
    }

    .nav-pills .nav-link:not(.active) {
        background-color: #f3f4f6;
        color: #374151;
    }

    .nav-pills .nav-link:not(.active):hover {
        background-color: #e5e7eb;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="bar-chart-2" class="w-10 h-10 inline-block mr-2"></i>
                KPI Karyawan
            </h1>
            <p class="page-subtitle">
                <i data-feather="trending-up"></i>
                Evaluasi & analisis kinerja karyawan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <?php if ($selected_siklus == 'harian'): ?>
                <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalKPI">
                    <i data-feather="plus-circle"></i> Input KPI
                </a>
            <?php else: ?>
                <div class="badge badge-info px-3 py-2" style="font-size: 0.875rem;">
                    <i data-feather="info" style="width: 16px; height: 16px;"></i>
                    Data Auto-Calculated
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div style="padding: 1rem;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Cycle Tabs -->
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a href="?siklus=harian"
                                class="nav-link <?= $selected_siklus == 'harian' ? 'active' : ''; ?>">Harian</a>
                        </li>
                        <li class="nav-item">
                            <a href="?siklus=mingguan"
                                class="nav-link <?= $selected_siklus == 'mingguan' ? 'active' : ''; ?>">Mingguan</a>
                        </li>
                        <li class="nav-item">
                            <a href="?siklus=bulanan"
                                class="nav-link <?= $selected_siklus == 'bulanan' ? 'active' : ''; ?>">Bulanan</a>
                        </li>
                        <li class="nav-item">
                            <a href="?siklus=tahunan"
                                class="nav-link <?= $selected_siklus == 'tahunan' ? 'active' : ''; ?>">Tahunan</a>
                        </li>
                    </ul>

                    <!-- Period Picker -->
                    <form action="" method="GET" class="d-inline-flex align-items-center mt-2 mt-md-0">
                        <input type="hidden" name="siklus" value="<?= $selected_siklus; ?>">
                        <i data-feather="calendar" class="text-muted mx-2"></i>
                        <?php if ($selected_siklus == 'harian'): ?>
                            <input type="date" name="periode_harian" class="form-control" value="<?= $selected_periode; ?>"
                                onchange="this.form.submit()">
                        <?php elseif ($selected_siklus == 'mingguan'): ?>
                            <input type="week" name="periode_mingguan" class="form-control"
                                value="<?= $selected_periode; ?>" onchange="this.form.submit()">
                        <?php elseif ($selected_siklus == 'bulanan'): ?>
                            <input type="month" name="periode_bulanan" class="form-control"
                                value="<?= $selected_periode; ?>" onchange="this.form.submit()">
                        <?php else: ?>
                            <select name="periode_tahunan" class="form-control" onchange="this.form.submit()">
                                <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?= $y; ?>" <?= $selected_periode == $y ? 'selected' : ''; ?>><?= $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Radar Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Analisis Kompetensi</h3>
                </div>
                <div class="chart-container">
                    <canvas id="kpiRadarChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="activity-card">
                <div class="chart-header">
                    <h3 class="chart-title">Ringkasan KPI</h3>
                </div>
                <div class="activity-list">
                    <?php
                    $total_all = 0;
                    $count = 0;
                    if (!empty($kpi_list)) {
                        foreach ($kpi_list as $k) {
                            $total_all += $k['rata_rata'];
                            $count++;
                        }
                    }
                    $avg_all = $count > 0 ? number_format($total_all / $count, 2) : 0;
                    $max_score = $count > 0 ? number_format(max(array_column($kpi_list, 'rata_rata')), 2) : 0;
                    ?>
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i data-feather="users"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Total Karyawan</div>
                            <div class="activity-description"><?= $count; ?> karyawan dinilai</div>
                        </div>
                        <div class="activity-time"><?= ucfirst($selected_siklus); ?></div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i data-feather="trending-up"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Rata-rata Organisasi</div>
                            <div class="activity-description"><?= $avg_all; ?> / 5.0</div>
                        </div>
                        <div class="activity-time">Average</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i data-feather="award"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Skor Tertinggi</div>
                            <div class="activity-description"><?= $max_score; ?> / 5.0</div>
                        </div>
                        <div class="activity-time">Best</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="section-header" style="margin-top: 2rem;">
            <h2 class="section-title">Detail KPI Karyawan</h2>
            <div class="chart-filters">
                <a href="<?= site_url('HR/export_rekap_csv?periode=' . $selected_periode); ?>" class="btn btn-outline">
                    <i data-feather="file-text"></i> CSV
                </a>
                <a href="<?= site_url('HR/export_rekap_pdf?periode=' . $selected_periode); ?>" class="btn btn-outline">
                    <i data-feather="printer"></i> PDF
                </a>
            </div>
        </div>

        <div class="chart-card">
            <div style="padding: 1.5rem;">
                <?php if ($this->session->flashdata('sukses')): ?>
                    <div class="alert alert-success d-flex align-items-center mb-3">
                        <i data-feather="check-circle" class="mr-2"></i>
                        <span><?= $this->session->flashdata('sukses'); ?></span>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="tableKPI" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Posisi</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Disiplin</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Kualitas</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Produktivitas
                                </th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Teamwork</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Rata-rata
                                </th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Status</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Catatan</th>
                                <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kpi_list)):
                                foreach ($kpi_list as $row):
                                    $avg = floatval($row['rata_rata']);
                                    $badge = 'badge-secondary';
                                    $kategori = 'Kurang';
                                    if ($avg >= 4.5) {
                                        $badge = 'badge-success';
                                        $kategori = 'Sangat Baik';
                                    } elseif ($avg >= 3.5) {
                                        $badge = 'badge-info';
                                        $kategori = 'Baik';
                                    } elseif ($avg >= 2.5) {
                                        $badge = 'badge-warning';
                                        $kategori = 'Cukup';
                                    } else {
                                        $badge = 'badge-danger';
                                        $kategori = 'Kurang';
                                    }
                                    ?>
                                    <tr>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama_karyawan']); ?>
                                        </td>
                                        <td class="text-muted small"><?= htmlspecialchars($row['posisi']); ?></td>
                                        <td class="text-center"><?= number_format($row['kedisiplinan'], 1); ?></td>
                                        <td class="text-center"><?= number_format($row['kualitas_kerja'], 1); ?></td>
                                        <td class="text-center"><?= number_format($row['produktivitas'], 1); ?></td>
                                        <td class="text-center"><?= number_format($row['kerja_tim'], 1); ?></td>
                                        <td class="text-center font-weight-bold" style="font-size: 1.1em;">
                                            <?= number_format($avg, 2); ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $badge; ?> px-2 py-1"><?= $kategori; ?></span>
                                        </td>
                                        <td class="text-muted small text-truncate" style="max-width: 150px;"
                                            title="<?= htmlspecialchars($row['catatan']); ?>">
                                            <?= htmlspecialchars($row['catatan']); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if (isset($row['kpi_id']) && $selected_siklus == 'harian'): ?>
                                                <a href="<?= site_url('HR/delete_kpi/' . $row['kpi_id']); ?>"
                                                    class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data KPI untuk periode ini</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Input KPI -->
<div class="modal" id="modalKPI">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Input KPI Harian</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/save_kpi'); ?>" method="POST">
            <input type="hidden" name="siklus" value="harian">
            <input type="hidden" name="periode_harian"
                value="<?= isset($selected_periode) ? $selected_periode : date('Y-m-d'); ?>">

            <div class="mb-3">
                <label class="font-medium text-sm">Karyawan <span class="text-red-500">*</span></label>
                <select name="id_karyawan" class="form-control w-full mt-1" required>
                    <option value="">-- Pilih Karyawan --</option>
                    <?php if (!empty($karyawan_list)):
                        foreach ($karyawan_list as $k): ?>
                            <option value="<?= $k->kry_kode; ?>"><?= $k->kry_nama; ?> - <?= $k->kry_level; ?></option>
                        <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="font-medium text-sm">Kedisiplinan (1-5)</label>
                    <div class="flex items-center mt-1">
                        <input type="range" class="flex-grow mr-2" min="1" max="5" step="1"
                            id="rgDisiplin" name="kedisiplinan" value="3"
                            oninput="valDisiplin.value = rgDisiplin.value">
                        <output id="valDisiplin" class="font-bold text-center w-8">3</output>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Kualitas Kerja (1-5)</label>
                    <div class="flex items-center mt-1">
                        <input type="range" class="flex-grow mr-2" min="1" max="5" step="1"
                            id="rgKualitas" name="kualitas_kerja" value="3"
                            oninput="valKualitas.value = rgKualitas.value">
                        <output id="valKualitas" class="font-bold text-center w-8">3</output>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Produktivitas (1-5)</label>
                    <div class="flex items-center mt-1">
                        <input type="range" class="flex-grow mr-2" min="1" max="5" step="1"
                            id="rgProd" name="produktivitas" value="3" oninput="valProd.value = rgProd.value">
                        <output id="valProd" class="font-bold text-center w-8">3</output>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Kerja Tim (1-5)</label>
                    <div class="flex items-center mt-1">
                        <input type="range" class="flex-grow mr-2" min="1" max="5" step="1"
                            id="rgTeam" name="kerja_tim" value="3" oninput="valTeam.value = rgTeam.value">
                        <output id="valTeam" class="font-bold text-center w-8">3</output>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="font-medium text-sm">Catatan Penilaian</label>
                <textarea name="catatan" class="form-control w-full mt-1" rows="2"
                    placeholder="Pencapaian khusus atau area yang perlu ditingkatkan..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize Feather Icons
        feather.replace();

        // Initialize DataTable
        $('#tableKPI').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data"
            },
            order: [[6, 'desc']] // Sort by rata-rata descending
        });

        // Delete Confirmation
        $('.onclick-confirm').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Hapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = href;
            });
        });

        // Fix: Ensure body scroll is restored when modal is closed
        $('[data-dismiss="modal"]').on('click', function() {
            setTimeout(function() {
                if (!$('.modal.show').length) {
                    $('body').removeClass('overflow-y-hidden').css('padding-right', '');
                }
            }, 300);
        });

        $('.modal').on('click', function(e) {
            if (e.target === this) {
                setTimeout(function() {
                    if (!$('.modal.show').length) {
                        $('body').removeClass('overflow-y-hidden').css('padding-right', '');
                    }
                }, 300);
            }
        });

        // KPI Radar Chart
        const ctx = document.getElementById('kpiRadarChart').getContext('2d');
        <?php
        $c_dis = 0;
        $c_kua = 0;
        $c_prod = 0;
        $c_team = 0;
        $count = count($kpi_list);
        if ($count > 0) {
            foreach ($kpi_list as $row) {
                $c_dis += $row['kedisiplinan'];
                $c_kua += $row['kualitas_kerja'];
                $c_prod += $row['produktivitas'];
                $c_team += $row['kerja_tim'];
            }
            $c_dis /= $count;
            $c_kua /= $count;
            $c_prod /= $count;
            $c_team /= $count;
        }
        ?>
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Kedisiplinan', 'Kualitas Kerja', 'Produktivitas', 'Kerja Tim'],
                datasets: [{
                    label: 'Rata-rata Organisasi',
                    data: [<?= number_format($c_dis, 2); ?>, <?= number_format($c_kua, 2); ?>, <?= number_format($c_prod, 2); ?>, <?= number_format($c_team, 2); ?>],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: '#3B82F6',
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3B82F6'
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    r: {
                        ticks: {
                            max: 5,
                            min: 0,
                            stepSize: 1
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>