<?php $this->load->view('Template/header'); ?>

<style>
    .content-area { margin-top: 4rem; padding: 2rem; }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="file-text" class="w-10 h-10 inline-block mr-2"></i>
                Laporan Mingguan
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Input laporan kinerja mingguan karyawan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalLaporan">
                <i data-feather="plus-circle"></i> Input Laporan
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter Periode</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="" method="GET" class="flex items-end gap-4">
                    <div>
                        <label class="form-label font-medium text-sm">Periode Mingguan</label>
                        <input type="week" name="periode" class="form-control mt-1" 
                            value="<?= $selected_periode; ?>" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Laporan Mingguan</h3>
            </div>
            <div style="padding: 1.5rem;">
                <?php if ($this->session->flashdata('sukses')): ?>
                    <div class="alert alert-success mb-3">
                        <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                        <?= $this->session->flashdata('sukses'); ?>
                    </div>
                <?php endif; ?>

                <div class="overflow-x-auto">
                    <table id="tableLaporan" class="table table-hover w-100">
                        <thead class="bg-gray-100">
                            <tr>
                                <th>Karyawan</th>
                                <th>Periode</th>
                                <th>Target</th>
                                <th>Tugas & Hasil</th>
                                <th>Kendala & Solusi</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($laporan_list)): foreach ($laporan_list as $row): ?>
                                <tr>
                                    <td>
                                        <div class="font-medium"><?= htmlspecialchars($row['nama_karyawan']); ?></div>
                                        <div class="text-gray-500 text-xs"><?= htmlspecialchars($row['posisi']); ?></div>
                                    </td>
                                    <td><?= $row['periode']; ?></td>
                                    <td class="text-sm"><?= htmlspecialchars($row['target_mingguan']); ?></td>
                                    <td class="text-sm">
                                        <div><strong>Tugas:</strong> <?= htmlspecialchars($row['tugas_dilakukan']); ?></div>
                                        <div class="mt-1"><strong>Hasil:</strong> <?= htmlspecialchars($row['hasil']); ?></div>
                                    </td>
                                    <td class="text-sm">
                                        <div class="text-red-600"><strong>Kendala:</strong> <?= htmlspecialchars($row['kendala']); ?></div>
                                        <div class="text-green-600 mt-1"><strong>Solusi:</strong> <?= htmlspecialchars($row['solusi']); ?></div>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= site_url('HR/delete_laporan_mingguan/' . $row['laporan_id']); ?>"
                                            class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                            <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada laporan untuk periode ini</p>
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

<!-- Modal Input Laporan Mingguan -->
<div class="modal" id="modalLaporan">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Input Laporan Mingguan</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/save_laporan_mingguan'); ?>" method="POST">
            <input type="hidden" name="periode" value="<?= $selected_periode; ?>">

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

            <div class="mb-3">
                <label class="font-medium text-sm">Target Mingguan <span class="text-red-500">*</span></label>
                <textarea name="target_mingguan" class="form-control w-full mt-1" rows="2" 
                    placeholder="Target yang harus dicapai minggu ini..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="font-medium text-sm">Tugas yang Dilakukan <span class="text-red-500">*</span></label>
                <textarea name="tugas_dilakukan" class="form-control w-full mt-1" rows="2" 
                    placeholder="Tugas-tugas yang sudah dikerjakan..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="font-medium text-sm">Hasil <span class="text-red-500">*</span></label>
                <textarea name="hasil" class="form-control w-full mt-1" rows="2" 
                    placeholder="Hasil dari tugas yang dikerjakan..." required></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="font-medium text-sm">Kendala</label>
                    <textarea name="kendala" class="form-control w-full mt-1" rows="2" 
                        placeholder="Kendala yang dihadapi..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Solusi</label>
                    <textarea name="solusi" class="form-control w-full mt-1" rows="2" 
                        placeholder="Solusi untuk mengatasi kendala..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
$(document).ready(function() {
    feather.replace();
    
    $('#tableLaporan').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data"
        }
    });

    $('.onclick-confirm').on('click', function(e) {
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
});
</script>