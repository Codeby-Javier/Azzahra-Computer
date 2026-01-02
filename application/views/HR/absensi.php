<?php $this->load->view('Template/header'); ?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="clock" class="w-10 h-10 inline-block mr-2"></i>
                Absensi Karyawan
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Kelola kehadiran harian karyawan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalAbsen">
                <i data-feather="plus-circle"></i> Input Absensi
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter & Export Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter & Export</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="" method="GET" class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $selected_date; ?>"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-8 text-md-right">
                        <a href="<?= site_url('HR/export_absensi_csv?periode=' . $selected_date . '&tipe=harian'); ?>"
                            class="btn btn-outline">
                            <i data-feather="download"></i> Download CSV
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Absensi - <?= date('d F Y', strtotime($selected_date)); ?></h3>
            </div>
            <div style="padding: 1.5rem;">
                <?php if ($this->session->flashdata('sukses')): ?>
                    <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                        <i data-feather="check-circle" class="mr-2"></i>
                        <span><?= $this->session->flashdata('sukses'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('gagal')): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                        <i data-feather="alert-circle" class="mr-2"></i>
                        <span><?= $this->session->flashdata('gagal'); ?></span>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="tableAbsensi" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Karyawan</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Posisi</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Status</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Masuk</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Pulang</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Keterangan</th>
                                <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($absensi_list)):
                                foreach ($absensi_list as $row):
                                    $status_class = '';
                                    $status_text = strtoupper($row['status']);
                                    switch ($status_text) {
                                        case 'HADIR':
                                            $status_class = 'badge-success';
                                            break;
                                        case 'IZIN':
                                            $status_class = 'badge-info';
                                            break;
                                        case 'CUTI':
                                            $status_class = 'badge-info';
                                            break;
                                        case 'SAKIT':
                                            $status_class = 'badge-warning';
                                            break;
                                        case 'TELAT':
                                            $status_class = 'badge-secondary';
                                            break;
                                        case 'ALPA':
                                            $status_class = 'badge-danger';
                                            break;
                                        default:
                                            $status_class = 'badge-light';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama_karyawan']); ?>
                                        </td>
                                        <td class="text-muted"><?= htmlspecialchars($row['posisi']); ?></td>
                                        <td><span class="badge <?= $status_class; ?> px-3 py-1"><?= $status_text; ?></span></td>
                                        <td><?= $row['jam_masuk'] ? date('H:i', strtotime($row['jam_masuk'])) : '-'; ?></td>
                                        <td><?= $row['jam_pulang'] ? date('H:i', strtotime($row['jam_pulang'])) : '-'; ?></td>
                                        <td class="text-muted small"><?= htmlspecialchars($row['keterangan']); ?></td>
                                        <td class="text-right">
                                            <a href="<?= site_url('HR/delete_absensi/' . $row['absensi_id']); ?>"
                                                class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data absensi untuk tanggal ini</p>
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

<!-- Modal Input Absensi -->
<div class="modal" id="modalAbsen">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Input Absensi Harian</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/save_absensi'); ?>" method="POST">
            <input type="hidden" name="tanggal" value="<?= $selected_date; ?>">

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
                <label class="font-medium text-sm">Status Kehadiran <span class="text-red-500">*</span></label>
                <select name="status" id="statusSelect" class="form-control w-full mt-1" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Telat">Telat</option>
                    <option value="Alpa">Alpa</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3" id="jamRow">
                <div>
                    <label class="font-medium text-sm">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control w-full mt-1" value="08:00">
                </div>
                <div>
                    <label class="font-medium text-sm">Jam Pulang</label>
                    <input type="time" name="jam_pulang" class="form-control w-full mt-1" value="17:00">
                </div>
            </div>

            <div class="mb-4">
                <label class="font-medium text-sm">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control w-full mt-1" rows="2"
                    placeholder="Contoh: Izin sakit perut..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Initialize DataTable
        $('#tableAbsensi').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan"
            },
            order: [[1, 'asc']]
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
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Toggle Jam Input based on Status
        $('#statusSelect').change(function () {
            const val = $(this).val();
            if (val === 'Hadir' || val === 'Telat') {
                $('#jamRow').show();
                $('input[name="jam_masuk"]').prop('required', true);
            } else {
                $('#jamRow').hide();
                $('input[name="jam_masuk"]').prop('required', false);
            }
        });

        // Trigger on page load
        $('#statusSelect').trigger('change');

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