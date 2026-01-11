<?php $this->load->view('Template/header'); ?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }
    
    /* Simplify select - remove extra padding */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 35px !important;
        padding: 8px 35px 8px 12px !important;
        background-color: white;
        line-height: 1.2;
        height: 38px;
    }
    
    select.form-control option {
        padding: 0;
        line-height: 1.2;
    }
    
    /* Kompak form */
    #filterForm {
        gap: 3rem;
        margin: 0;
        row-gap: 1.5rem;
    }
    
    #filterForm .col-md-4 {
        margin-bottom: 0 !important;
        padding: 0 1.5rem;
    }
    
    #filterForm label {
        margin-bottom: 1rem !important;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    /* Button styling */
    #filterForm button {
        background-color: #2563eb !important;
        color: white !important;
        border: none !important;
        padding: 12px 24px !important;
        font-weight: 500;
        cursor: pointer;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1rem;
        border-radius: 4px;
    }
    
    #filterForm button:hover {
        background-color: #1d4ed8 !important;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="clock" class="w-10 h-10 inline-block mr-2"></i>
                Poin Performa
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Penilaian performa mingguan karyawan & peserta magang
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalPoinPerforma">
                <i data-feather="plus-circle"></i> Input Poin Performa
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter Data Poin Performa</h3>
            </div>
            <div style="padding: 2rem 2.5rem;">
                <form method="GET" id="filterForm" class="row align-items-end" style="margin: 0;">
                    <div class="col-md-4">
                        <label class="font-medium text-sm d-block mb-2">Periode Minggu</label>
                        <input type="week" name="periode" class="form-control w-full" value="<?= $selected_periode; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="font-medium text-sm d-block mb-2">Tipe Karyawan</label>
                        <select name="tipe" class="form-control w-full">
                            <option value="">Semua Tipe</option>
                            <option value="Karyawan" <?= $selected_tipe === 'Karyawan' ? 'selected' : ''; ?>>Karyawan</option>
                            <option value="Magang" <?= $selected_tipe === 'Magang' ? 'selected' : ''; ?>>Peserta Magang</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i data-feather="search" style="width: 16px; height: 16px; display: inline-block; margin-right: 5px;"></i>
                            Cari Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Poin Performa - Periode <?= $selected_periode; ?></h3>
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

                <?php if (!empty($poin_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="tablePoinPerforma">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Karyawan</th>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">Posisi</th>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">Tipe</th>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">Total Poin</th>
                                    <th class="border-0" style="font-weight: 600; color: #4b5563;">Status</th>
                                    <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($poin_list as $row):
                                    // Tentukan status berdasarkan total poin
                                    $status = '';
                                    $status_class = '';
                                    $total = intval($row['total_poin']);
                                    
                                    if ($row['tipe_karyawan'] === 'Karyawan') {
                                        if ($total >= 80) { $status = 'Excellent'; $status_class = 'badge-success'; }
                                        elseif ($total >= 70) { $status = 'Good'; $status_class = 'badge-info'; }
                                        elseif ($total >= 60) { $status = 'Adequate'; $status_class = 'badge-warning'; }
                                        else { $status = 'Below Target'; $status_class = 'badge-danger'; }
                                    } else {
                                        if ($total >= 90) { $status = 'Excellent'; $status_class = 'badge-success'; }
                                        elseif ($total >= 75) { $status = 'Good'; $status_class = 'badge-info'; }
                                        elseif ($total >= 60) { $status = 'Adequate'; $status_class = 'badge-warning'; }
                                        else { $status = 'Below Target'; $status_class = 'badge-danger'; }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row['posisi']); ?></td>
                                        <td><span class="badge badge-light"><?= $row['tipe_karyawan']; ?></span></td>
                                        <td class="font-weight-bold text-dark"><?= $row['total_poin']; ?> poin</td>
                                        <td><span class="badge <?= $status_class; ?> px-3 py-1"><?= $status; ?></span></td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-outline-primary" title="Lihat Detail" onclick="viewDetail(<?= htmlspecialchars(json_encode($row)); ?>)">
                                                <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                                            </button>
                                            <a href="javascript:;" class="btn btn-sm btn-outline-warning" title="Edit" onclick="editPoin(<?= $row['poin_id']; ?>)">
                                                <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                            </a>
                                            <a href="<?= site_url('HR/delete_poin_performa/' . $row['poin_id']); ?>" class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i data-feather="inbox" style="width: 64px; height: 64px; color: #ccc;"></i>
                        <p class="mt-3 text-muted">Belum ada data poin performa untuk periode ini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Modal Detail Poin Performa -->
<div class="modal" id="modalDetailPoin">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 90vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Detail Poin Performa</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        
        <div id="detailContent">
            <!-- Konten akan diisi via JavaScript -->
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t">
            <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Tutup</a>
        </div>
    </div>
</div>

<!-- Modal Input Poin Performa -->
<div class="modal" id="modalPoinPerforma">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 90vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Input Poin Performa</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/save_poin_performa'); ?>" method="POST" id="formPoinPerforma">
            <input type="hidden" name="poin_id" id="inputPoinId" value="">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Karyawan <span class="text-red-500">*</span></label>
                    <select name="id_karyawan" id="selectKaryawan" class="form-control w-full mt-1" required>
                        <option value="">-- Pilih Karyawan --</option>
                        <?php if (!empty($karyawan_list)):
                            foreach ($karyawan_list as $k): ?>
                                <option value="<?= $k->kry_kode; ?>" data-tipe="<?= $k->kry_level; ?>"><?= $k->kry_nama; ?> (<?= $k->kry_level; ?>)</option>
                            <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Periode Minggu <span class="text-red-500">*</span></label>
                    <input type="week" name="periode_minggu" id="inputPeriode" class="form-control w-full mt-1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Tipe Karyawan <span class="text-red-500">*</span></label>
                    <select name="tipe_karyawan" id="selectTipeKaryawan" class="form-control w-full mt-1" required disabled>
                        <option value="">-- Akan otomatis terisi --</option>
                        <option value="Karyawan">Karyawan</option>
                        <option value="Magang">Peserta Magang</option>
                    </select>
                </div>
            </div>

            <!-- Poin Performa Karyawan -->
            <div id="poinKaryawan" style="display: none;">
                <h4 class="mb-3 font-medium border-bottom pb-2">Poin Performa Karyawan</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Hasil Kerja (Max 20 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="hasil_kerja" class="form-control w-full mt-1 poin-input" min="0" max="20" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Pencapaian Target (Max 20 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="pencapaian_target" class="form-control w-full mt-1 poin-input" min="0" max="20" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Kualitas Kerja (Max 15 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="kualitas_kerja" class="form-control w-full mt-1 poin-input" min="0" max="15" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Disiplin (Max 15 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="disiplin" class="form-control w-full mt-1 poin-input" min="0" max="15" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Tanggung Jawab (Max 10 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="tanggung_jawab" class="form-control w-full mt-1 poin-input" min="0" max="10" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Produktivitas Layanan (Max 10 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="produktivitas_layanan" class="form-control w-full mt-1 poin-input" min="0" max="10" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Kepatuhan SOP (Max 5 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="kepatuhan_sop" class="form-control w-full mt-1 poin-input" min="0" max="5" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Minim Komplain (Max 5 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="minim_komplain" class="form-control w-full mt-1 poin-input" min="0" max="5" value="0">
                    </div>
                </div>
                <div class="alert alert-info mb-3">
                    <strong>Total Poin: <span id="totalKaryawan">0</span> / 100</strong>
                </div>
            </div>

            <!-- Poin Performa Magang -->
            <div id="poinMagang" style="display: none;">
                <h4 class="mb-3 font-medium border-bottom pb-2">Poin Performa Peserta Magang</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Proses Belajar (Max 25 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="proses_belajar" class="form-control w-full mt-1 poin-input-magang" min="0" max="25" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Tugas Dijalankan (Max 25 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="tugas_dijalankan" class="form-control w-full mt-1 poin-input-magang" min="0" max="25" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Sikap (Max 20 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="sikap" class="form-control w-full mt-1 poin-input-magang" min="0" max="20" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-medium text-sm">Kedisiplinan (Max 15 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="kedisiplinan" class="form-control w-full mt-1 poin-input-magang" min="0" max="15" value="0">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="font-medium text-sm">Kepatuhan SOP Magang (Max 15 poin) <span class="text-red-500">*</span></label>
                        <input type="number" name="kepatuhan_sop_magang" class="form-control w-full mt-1 poin-input-magang" min="0" max="15" value="0">
                    </div>
                </div>
                <div class="alert alert-info mb-3">
                    <strong>Total Poin: <span id="totalMagang">0</span> / 100</strong>
                </div>
            </div>

            <div class="mb-4">
                <label class="font-medium text-sm">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-control w-full mt-1" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    <i data-feather="save" style="width: 16px; height: 16px; display: inline-block; margin-right: 5px;"></i>
                    Simpan Poin Performa
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

        // Set default periode to current week
        setCurrentWeek();

        // Auto-submit filter form ketika periode atau tipe berubah
        $('#filterForm input[name="periode"], #filterForm select[name="tipe"]').on('change', function() {
            // Manual convert for week input
            const periodeVal = $('#filterForm input[name="periode"]').val();
            if (periodeVal) {
                // Format: 2026-W02 sudah benar dari input type="week"
                $('#filterForm').submit();
            }
        });

        // Handle Tipe Karyawan change
        $('#selectTipeKaryawan').change(function() {
            const tipe = $(this).val();
            if (tipe === 'Karyawan') {
                $('#poinKaryawan').show();
                $('#poinMagang').hide();
                $('#poinKaryawan input[type="number"]').prop('required', true);
                $('#poinMagang input[type="number"]').prop('required', false);
            } else if (tipe === 'Magang') {
                $('#poinKaryawan').hide();
                $('#poinMagang').show();
                $('#poinKaryawan input[type="number"]').prop('required', false);
                $('#poinMagang input[type="number"]').prop('required', true);
            } else {
                $('#poinKaryawan').hide();
                $('#poinMagang').hide();
            }
        });

        // Auto-fill tipe when karyawan is selected
        $('#selectKaryawan').change(function() {
            const selectedOption = $(this).find('option:selected');
            const tipe = selectedOption.data('tipe');
            if (tipe) {
                if (tipe.toLowerCase().includes('magang') || tipe.toLowerCase().includes('intern')) {
                    $('#selectTipeKaryawan').val('Magang').prop('disabled', false);
                } else {
                    $('#selectTipeKaryawan').val('Karyawan').prop('disabled', false);
                }
                $('#selectTipeKaryawan').trigger('change');
            }
        });

        // Calculate total poin for Karyawan
        $(document).on('change', '.poin-input', function() {
            let total = 0;
            $('.poin-input').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#totalKaryawan').text(total);
        });

        // Calculate total poin for Magang
        $(document).on('change', '.poin-input-magang', function() {
            let total = 0;
            $('.poin-input-magang').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#totalMagang').text(total);
        });

        // Form submission
        $('#formPoinPerforma').on('submit', function(e) {
            e.preventDefault();
            
            const tipe = $('#selectTipeKaryawan').val();
            if (!tipe) {
                Swal.fire({
                    title: 'Error',
                    text: 'Silakan pilih tipe karyawan terlebih dahulu!',
                    icon: 'error'
                });
                return;
            }

            this.submit();
        });

        // Initialize DataTable
        $('#tablePoinPerforma').DataTable({
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
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });

        // Delete Confirmation
        $(document).on('click', '.onclick-confirm', function (e) {
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

        // Reset form when modal is closed
        $('#modalPoinPerforma').on('hidden.bs.modal', function () {
            resetForm();
        });
    });

    function setCurrentWeek() {
        const today = new Date();
        const year = today.getFullYear();
        const week = Math.ceil((today.getDate() + 6 - today.getDay()) / 7);
        const weekStr = String(week).padStart(2, '0');
        $('#inputPeriode').val(year + '-W' + weekStr);
    }

    function resetForm() {
        $('#formPoinPerforma')[0].reset();
        $('#inputPoinId').val('');
        $('#poinKaryawan').hide();
        $('#poinMagang').hide();
        $('#totalKaryawan').text('0');
        $('#totalMagang').text('0');
        $('#selectTipeKaryawan').val('').prop('disabled', true);
        setCurrentWeek();
        document.querySelector('.modal-title').textContent = 'Input Poin Performa';
    }

    function viewDetail(data) {
        const tipe = data.tipe_karyawan;
        let content = `
            <div class="mb-4">
                <h4 class="font-weight-bold mb-3">Contoh: ${data.nama_karyawan} (${tipe})</h4>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Hasil</th>
                                <th class="text-right">Poin</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        if (tipe === 'Karyawan') {
            const components = [
                { label: 'Hasil Kerja', value: parseInt(data.hasil_kerja) || 0 },
                { label: 'Pencapaian Target', value: parseInt(data.pencapaian_target) || 0 },
                { label: 'Kualitas Kerja', value: parseInt(data.kualitas_kerja) || 0 },
                { label: 'Disiplin', value: parseInt(data.disiplin) || 0 },
                { label: 'Tanggung Jawab', value: parseInt(data.tanggung_jawab) || 0 },
                { label: 'Produktivitas Layanan', value: parseInt(data.produktivitas_layanan) || 0 },
                { label: 'Kepatuhan SOP', value: parseInt(data.kepatuhan_sop) || 0 },
                { label: 'Minim Komplain', value: parseInt(data.minim_komplain) || 0 }
            ];
            
            let total = 0;
            components.forEach(comp => {
                content += `<tr><td>${comp.label}</td><td>Baik</td><td class="text-right">+${comp.value}</td></tr>`;
                total += comp.value;
            });
            content += `<tr class="font-weight-bold"><td colspan="2">Total Poin Mingguan</td><td class="text-right">${total} poin</td></tr>`;
        } else {
            const components = [
                { label: 'Proses Belajar', value: parseInt(data.proses_belajar) || 0 },
                { label: 'Tugas Dijalankan', value: parseInt(data.tugas_dijalankan) || 0 },
                { label: 'Sikap', value: parseInt(data.sikap) || 0 },
                { label: 'Kedisiplinan', value: parseInt(data.kedisiplinan) || 0 },
                { label: 'Kepatuhan SOP', value: parseInt(data.kepatuhan_sop_magang) || 0 }
            ];
            
            let total = 0;
            components.forEach(comp => {
                content += `<tr><td>${comp.label}</td><td>Baik</td><td class="text-right">+${comp.value}</td></tr>`;
                total += comp.value;
            });
            content += `<tr class="font-weight-bold"><td colspan="2">Total Poin Mingguan</td><td class="text-right">${total} poin</td></tr>`;
        }

        content += `
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info">
                    <strong>Status:</strong> ${getStatusText(data.total_poin, tipe)}
                </div>
                ${data.catatan ? `<div class="alert alert-light"><strong>Catatan:</strong> ${data.catatan}</div>` : ''}
            </div>
        `;

        $('#detailContent').html(content);
        $('#modalDetailPoin').modal('show');
        feather.replace();
    }

    function editPoin(poinId) {
        // Fetch poin data via AJAX
        $.ajax({
            url: '<?= site_url('HR/get_poin_performa/') ?>' + poinId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    const poin = data.data;
                    
                    // Fill form with data
                    $('#inputPoinId').val(poin.poin_id);
                    $('#selectKaryawan').val(poin.id_karyawan);
                    $('#inputPeriode').val(convertToWeekFormat(poin.periode_minggu));
                    $('#selectTipeKaryawan').val(poin.tipe_karyawan).prop('disabled', false);
                    $('#selectTipeKaryawan').trigger('change');
                    
                    // Fill form values
                    if (poin.tipe_karyawan === 'Karyawan') {
                        $('[name="hasil_kerja"]').val(poin.hasil_kerja || 0);
                        $('[name="pencapaian_target"]').val(poin.pencapaian_target || 0);
                        $('[name="kualitas_kerja"]').val(poin.kualitas_kerja || 0);
                        $('[name="disiplin"]').val(poin.disiplin || 0);
                        $('[name="tanggung_jawab"]').val(poin.tanggung_jawab || 0);
                        $('[name="produktivitas_layanan"]').val(poin.produktivitas_layanan || 0);
                        $('[name="kepatuhan_sop"]').val(poin.kepatuhan_sop || 0);
                        $('[name="minim_komplain"]').val(poin.minim_komplain || 0);
                        $('.poin-input').trigger('change');
                    } else {
                        $('[name="proses_belajar"]').val(poin.proses_belajar || 0);
                        $('[name="tugas_dijalankan"]').val(poin.tugas_dijalankan || 0);
                        $('[name="sikap"]').val(poin.sikap || 0);
                        $('[name="kedisiplinan"]').val(poin.kedisiplinan || 0);
                        $('[name="kepatuhan_sop_magang"]').val(poin.kepatuhan_sop_magang || 0);
                        $('.poin-input-magang').trigger('change');
                    }
                    
                    $('[name="catatan"]').val(poin.catatan || '');
                    
                    // Update modal title
                    document.querySelector('#modalPoinPerforma .modal-title').textContent = 'Edit Poin Performa';
                    
                    // Show modal
                    $('#modalPoinPerforma').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Gagal memuat data',
                        icon: 'error'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memuat data',
                    icon: 'error'
                });
            }
        });
    }

    function getStatusText(totalPoin, tipe) {
        totalPoin = parseInt(totalPoin);
        if (tipe === 'Karyawan') {
            if (totalPoin >= 80) return 'Excellent';
            if (totalPoin >= 70) return 'Good';
            if (totalPoin >= 60) return 'Adequate';
            return 'Below Target';
        } else {
            if (totalPoin >= 90) return 'Excellent';
            if (totalPoin >= 75) return 'Good';
            if (totalPoin >= 60) return 'Adequate';
            return 'Below Target';
        }
    }

    function convertToWeekFormat(periodeString) {
        // Convert 'YYYY-WXX' to 'YYYY-WXX' format for week input
        return periodeString;
    }
</script>