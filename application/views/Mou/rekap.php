<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>Rekap Mou</h1>
                <p>Ringkasan dan Laporan MOU</p>
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
            <!-- Filter Form -->
            <div class="intro-y box p-5 mt-5">
                <h3 class="text-lg font-medium mb-4">Filter Data</h3>
                <form method="get" action="<?= site_url('Mou/rekap') ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="<?= isset($filters['tanggal_mulai']) ? htmlspecialchars($filters['tanggal_mulai']) : '' ?>">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="<?= isset($filters['tanggal_selesai']) ? htmlspecialchars($filters['tanggal_selesai']) : '' ?>">
                        </div>
                        <div>
                            <label class="form-label">Lokasi</label>
                            <select name="lokasi" class="form-control">
                                <option value="">Semua Lokasi</option>
                                <?php foreach ($lokasi_list as $lok): ?>
                                <option value="<?= htmlspecialchars($lok['lokasi']) ?>" <?= (isset($filters['lokasi']) && $filters['lokasi'] == $lok['lokasi']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lok['lokasi']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Customer</label>
                            <input type="text" name="customer" class="form-control" placeholder="Cari customer..." value="<?= isset($filters['customer']) ? htmlspecialchars($filters['customer']) : '' ?>">
                        </div>
                        <div>
                            <label class="form-label">Karyawan</label>
                            <select name="kry_kode" class="form-control">
                                <option value="">Semua Karyawan</option>
                                <?php foreach ($karyawan_list as $kry): ?>
                                <option value="<?= htmlspecialchars($kry['kry_kode']) ?>" <?= (isset($filters['kry_kode']) && $filters['kry_kode'] == $kry['kry_kode']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kry['kry_nama']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="button text-white bg-theme-1">
                            <i data-feather="filter" class="w-4 h-4 inline-block mr-1"></i> Terapkan Filter
                        </button>
                        <a href="<?= site_url('Mou/rekap') ?>" class="button border">
                            <i data-feather="rotate-ccw" class="w-4 h-4 inline-block mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="flex flex-col md:flex-row gap-4 mt-5">
                <div class="intro-y box p-4 flex-1">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-gray-600 text-sm">Total MOU</h3>
                            <p class="text-2xl font-bold text-theme-1 mt-2"><?= htmlspecialchars($summary['total_mou'] ?? 0) ?></p>
                        </div>
                        <div class="text-theme-1 text-4xl opacity-50">
                            <i data-feather="file-text"></i>
                        </div>
                    </div>
                </div>
                <div class="intro-y box p-4 flex-1">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-gray-600 text-sm">Total Nilai</h3>
                            <p class="text-xl font-bold text-green-600 mt-2">Rp. <?= number_format($summary['total_grand_total'] ?? 0, 0, ',', '.') ?>,-</p>
                        </div>
                        <div class="text-green-600 text-4xl opacity-50">
                            <i data-feather="trending-up"></i>
                        </div>
                    </div>
                </div>
                <div class="intro-y box p-4 flex-1">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-gray-600 text-sm">Rata-rata</h3>
                            <p class="text-xl font-bold text-blue-600 mt-2">Rp. <?= number_format($summary['avg_grand_total'] ?? 0, 0, ',', '.') ?>,-</p>
                        </div>
                        <div class="text-blue-600 text-4xl opacity-50">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-col md:flex-row gap-2 mt-5">
                <a href="<?= site_url('Mou/rekap_excel?' . http_build_query($filters)) ?>" class="button text-white bg-green-600 flex items-center justify-center md:justify-start">
                    <i data-feather="file-text" class="w-4 h-4 inline-block mr-2"></i> Export Excel
                </a>
                <a href="<?= site_url('Mou/rekap_pdf?' . http_build_query($filters)) ?>" class="button text-white bg-red-600 flex items-center justify-center md:justify-start">
                    <i data-feather="file" class="w-4 h-4 inline-block mr-2"></i> Export PDF
                </a>
            </div>

            <!-- Rekap per Lokasi -->
            <div class="intro-y box overflow-hidden mt-5">
                <h3 class="text-lg font-medium p-5 border-b">Rekap per Lokasi</h3>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">Lokasi</th>
                                <th class="border-b-2 whitespace-no-wrap text-right">Jumlah MOU</th>
                                <th class="border-b-2 whitespace-no-wrap text-right">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (is_array($per_lokasi) && count($per_lokasi) > 0): ?>
                                <?php foreach ($per_lokasi as $row): ?>
                                <tr>
                                    <td class="border-b"><?= htmlspecialchars($row['lokasi']) ?></td>
                                    <td class="border-b text-right"><?= htmlspecialchars($row['jumlah_mou']) ?></td>
                                    <td class="border-b text-right">Rp. <?= number_format($row['total_grand_total'], 0, ',', '.') ?>,-</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="border-b text-center py-4 text-gray-500">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekap per Customer -->
            <div class="intro-y box overflow-hidden mt-5">
                <h3 class="text-lg font-medium p-5 border-b">Top 10 Customer</h3>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">Customer</th>
                                <th class="border-b-2 whitespace-no-wrap text-right">Jumlah MOU</th>
                                <th class="border-b-2 whitespace-no-wrap text-right">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (is_array($per_customer) && count($per_customer) > 0): ?>
                                <?php foreach ($per_customer as $row): ?>
                                <tr>
                                    <td class="border-b"><?= htmlspecialchars($row['customer']) ?></td>
                                    <td class="border-b text-right"><?= htmlspecialchars($row['jumlah_mou']) ?></td>
                                    <td class="border-b text-right">Rp. <?= number_format($row['total_grand_total'], 0, ',', '.') ?>,-</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="border-b text-center py-4 text-gray-500">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Rekap MOU -->
            <div class="intro-y box overflow-hidden mt-5">
                <h3 class="text-lg font-medium p-5 border-b">Detail Rekap MOU</h3>
                <div class="overflow-x-auto">
                    <table id="tabelRekapMou" class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">No</th>
                                <th class="border-b-2 whitespace-no-wrap">Tanggal</th>
                                <th class="border-b-2 whitespace-no-wrap">Nama File</th>
                                <th class="border-b-2 whitespace-no-wrap">Customer</th>
                                <th class="border-b-2 whitespace-no-wrap">Lokasi</th>
                                <th class="border-b-2 whitespace-no-wrap text-right">Grand Total</th>
                                <th class="border-b-2 whitespace-no-wrap">Dibuat Oleh</th>
                                <th class="border-b-2 whitespace-no-wrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($detail_list && $detail_list->num_rows() > 0):
                                foreach ($detail_list->result() as $mou):
                            ?>
                            <tr>
                                <td class="border-b"><?= $no++ ?></td>
                                <td class="border-b"><?= date('d/m/Y', strtotime($mou->tanggal)) ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->file_name) ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->customer) ?></td>
                                <td class="border-b"><?= htmlspecialchars($mou->lokasi) ?></td>
                                <td class="border-b text-right">Rp. <?= number_format($mou->grand_total, 0, ',', '.') ?>,-</td>
                                <td class="border-b"><?= htmlspecialchars($mou->kry_nama ?: '-') ?></td>
                                <td class="border-b">
                                    <a href="<?= site_url('Mou/download/' . $mou->mou_id) ?>" class="button button--sm text-white bg-theme-1" target="_blank">
                                        <i data-feather="download" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                            <tr>
                                <td colspan="8" class="border-b text-center py-8">
                                    <div class="text-gray-500">
                                        <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2"></i>
                                        <p>Tidak ada data</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<script>
$(document).ready(function() {
    $('#tabelRekapMou').DataTable({
        "pageLength": 25,
        "order": [[1, "desc"]],
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
    if (typeof feather !== 'undefined') feather.replace();
});
</script>

<?php $this->load->view('Template/footer'); ?>
