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
        cursor: pointer;
    }

    .nav-pills .nav-link.active {
        background-color: #6366f1;
        color: white;
    }

    .nav-pills .nav-link:not(.active) {
        background-color: #f3f4f6;
        color: #374151;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="archive" class="w-10 h-10 inline-block mr-2"></i>
                Arsip Dokumen
            </h1>
            <p class="page-subtitle">
                <i data-feather="folder"></i>
                Manajemen dokumen garansi Dreame & Laptop
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-primary" id="btnAddDreame" data-toggle="modal" data-target="#modalAddDreame">
                <i data-feather="plus-circle"></i> Tambah Dreame
            </a>
            <a href="javascript:;" class="btn btn-primary" id="btnAddLaptop" data-toggle="modal" data-target="#modalAddLaptop" style="display: none;">
                <i data-feather="plus-circle"></i> Tambah Laptop
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <?php if ($this->session->flashdata('sukses')): ?>
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i data-feather="check-circle" class="mr-2"></i>
                <span><?= $this->session->flashdata('sukses'); ?></span>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div style="padding: 1rem;">
                <ul class="nav nav-pills" id="arsipTab">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="switchTab('dreame')">
                            <i data-feather="package" style="width: 16px; height: 16px;"></i> Dreame
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('laptop')">
                            <i data-feather="monitor" style="width: 16px; height: 16px;"></i> Laptop
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="arsipTabContent">
            <!-- TAB DREAME -->
            <div class="tab-pane active" id="dreame">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Data Arsip Dreame</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="table-responsive">
                            <table id="tableDreame" class="table table-hover w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Tanggal</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Customer</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">No HP</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Tipe</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Kerusakan</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Alamat</th>
                                        <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($arsip_dreame)):
                                        foreach ($arsip_dreame as $row): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                                <td class="font-weight-bold"><?= htmlspecialchars($row['nama']); ?></td>
                                                <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                                <td><span class="badge badge-light"><?= htmlspecialchars($row['tipe_detail']); ?></span></td>
                                                <td class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['kerusakan']); ?>">
                                                    <?= htmlspecialchars($row['kerusakan']); ?>
                                                </td>
                                                <td class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['alamat']); ?>">
                                                    <?= htmlspecialchars($row['alamat']); ?>
                                                </td>
                                                <td class="text-right">
                                                    <a href="javascript:;" class="btn btn-sm btn-outline-primary btn-edit"
                                                        data-id="<?= $row['arsip_id']; ?>"
                                                        data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                                        data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                                        data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                                        data-tipe="<?= htmlspecialchars($row['tipe_detail']); ?>"
                                                        data-rusak="<?= htmlspecialchars($row['kerusakan']); ?>"
                                                        data-tgl="<?= $row['tanggal']; ?>" title="Edit">
                                                        <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <a href="<?= site_url('HR/delete_arsip/' . $row['arsip_id']); ?>"
                                                        class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                                <p class="mt-2">Belum ada data arsip Dreame</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB LAPTOP -->
            <div class="tab-pane" id="laptop">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Data Arsip Laptop</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="table-responsive">
                            <table id="tableLaptop" class="table table-hover w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Tanggal</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Customer</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">No HP</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Tipe</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Kerusakan</th>
                                        <th class="border-0" style="font-weight: 600; color: #4b5563;">Alamat</th>
                                        <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($arsip_laptop)):
                                        foreach ($arsip_laptop as $row): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                                <td class="font-weight-bold"><?= htmlspecialchars($row['nama']); ?></td>
                                                <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                                <td><span class="badge badge-light"><?= htmlspecialchars($row['tipe_detail']); ?></span></td>
                                                <td class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['kerusakan']); ?>">
                                                    <?= htmlspecialchars($row['kerusakan']); ?>
                                                </td>
                                                <td class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['alamat']); ?>">
                                                    <?= htmlspecialchars($row['alamat']); ?>
                                                </td>
                                                <td class="text-right">
                                                    <a href="javascript:;" class="btn btn-sm btn-outline-primary btn-edit"
                                                        data-id="<?= $row['arsip_id']; ?>"
                                                        data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                                        data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                                        data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                                        data-tipe="<?= htmlspecialchars($row['tipe_detail']); ?>"
                                                        data-rusak="<?= htmlspecialchars($row['kerusakan']); ?>"
                                                        data-tgl="<?= $row['tanggal']; ?>" title="Edit">
                                                        <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <a href="<?= site_url('HR/delete_arsip/' . $row['arsip_id']); ?>"
                                                        class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                                <p class="mt-2">Belum ada data arsip Laptop</p>
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

    </div>
</div>

<!-- Modal Add Dreame -->
<div class="modal" id="modalAddDreame">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Tambah Arsip Dreame</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/add_arsip_dreame'); ?>" method="POST">
            <div class="mb-3">
                <label class="font-medium text-sm">Nama Customer <span class="text-red-500">*</span></label>
                <input type="text" name="nama" class="form-control w-full mt-1" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="font-medium text-sm">No HP</label>
                    <input type="text" name="no_hp" class="form-control w-full mt-1">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" class="form-control w-full mt-1" value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Tipe Dreame</label>
                <input type="text" name="tipe_detail" class="form-control w-full mt-1" placeholder="Contoh: Dreame V10">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Kerusakan</label>
                <textarea name="kerusakan" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="font-medium text-sm">Alamat</label>
                <textarea name="alamat" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Laptop -->
<div class="modal" id="modalAddLaptop">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Tambah Arsip Laptop</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/add_arsip_laptop'); ?>" method="POST">
            <div class="mb-3">
                <label class="font-medium text-sm">Nama Customer <span class="text-red-500">*</span></label>
                <input type="text" name="nama" class="form-control w-full mt-1" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="font-medium text-sm">No HP</label>
                    <input type="text" name="no_hp" class="form-control w-full mt-1">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" class="form-control w-full mt-1" value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Tipe Laptop</label>
                <input type="text" name="tipe_detail" class="form-control w-full mt-1" placeholder="Contoh: Asus ROG Strix">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Kerusakan</label>
                <textarea name="kerusakan" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="font-medium text-sm">Alamat</label>
                <textarea name="alamat" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Arsip -->
<div class="modal" id="modalEditArsip">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Edit Arsip</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form id="formEditArsip" action="" method="POST">
            <div class="mb-3">
                <label class="font-medium text-sm">Nama Customer <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="edit_nama" class="form-control w-full mt-1" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="font-medium text-sm">No HP</label>
                    <input type="text" name="no_hp" id="edit_hp" class="form-control w-full mt-1">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="edit_tgl" class="form-control w-full mt-1" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Tipe Unit</label>
                <input type="text" name="tipe_detail" id="edit_tipe" class="form-control w-full mt-1">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Kerusakan</label>
                <textarea name="kerusakan" id="edit_rusak" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="font-medium text-sm">Alamat</label>
                <textarea name="alamat" id="edit_alamat" class="form-control w-full mt-1" rows="2"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
$(document).ready(function() {
    feather.replace();

    // Initialize DataTables
    $('#tableDreame').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data"
        }
    });

    $('#tableLaptop').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data"
        }
    });

    // Delete Confirmation
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

    // Edit button - open modal with data using template's modal system
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const hp = $(this).data('hp');
        const alamat = $(this).data('alamat');
        const tipe = $(this).data('tipe');
        const rusak = $(this).data('rusak');
        const tgl = $(this).data('tgl');

        $('#edit_nama').val(nama);
        $('#edit_hp').val(hp);
        $('#edit_alamat').val(alamat);
        $('#edit_tipe').val(tipe);
        $('#edit_rusak').val(rusak);
        $('#edit_tgl').val(tgl);

        $('#formEditArsip').attr('action', '<?= site_url('HR/edit_arsip/'); ?>' + id);

        // Show modal using template's modal system (same as data-toggle="modal")
        $('#modalEditArsip').modal('show');
    });

    // Fix: Ensure body scroll is restored when any modal is closed
    $('[data-dismiss="modal"]').on('click', function() {
        setTimeout(function() {
            if (!$('.modal.show').length) {
                $('body').removeClass('overflow-y-hidden').css('padding-right', '');
            }
        }, 300);
    });

    // Fix: Also restore scroll when clicking outside modal
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

// Tab switching function
function switchTab(tabName) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(function(pane) {
        pane.classList.remove('active');
    });
    
    // Remove active from all nav links
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.classList.remove('active');
    });
    
    // Show selected tab pane
    document.getElementById(tabName).classList.add('active');
    
    // Add active to clicked nav link
    event.target.closest('.nav-link').classList.add('active');
    
    // Toggle add buttons based on active tab
    if (tabName === 'dreame') {
        document.getElementById('btnAddDreame').style.display = 'inline-flex';
        document.getElementById('btnAddLaptop').style.display = 'none';
    } else {
        document.getElementById('btnAddDreame').style.display = 'none';
        document.getElementById('btnAddLaptop').style.display = 'inline-flex';
    }
    
    // Re-init feather icons
    feather.replace();
}
</script>
