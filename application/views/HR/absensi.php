<?php $this->load->view('Template/header'); ?>

<!-- Layout Container -->
<div class="w-full" style="position: relative; overflow-x: hidden;">

    <!-- Header Section -->
    <header class="page-header" style="position: relative; margin-top: 0px; margin-bottom: 0px; padding-bottom: 30px; z-index: 10;">
        <div class="header-title">
            <h1><i data-feather="clock"></i> Absensi Karyawan</h1>
            <p>Kelola data kehadiran harian</p>
        </div>
        <div class="header-actions">
            <select id="periodeType" class="input border mr-2" style="width: 120px;">
                <option value="harian">Harian</option>
                <option value="mingguan">Mingguan</option>
                <option value="bulanan" selected>Bulanan</option>
            </select>
            <input type="text" id="periodeValue" class="input border mr-2" style="width: 150px;" value="<?= date('Y-m') ?>">
            <a href="#" onclick="exportAbsensi('csv'); return false;" class="btn btn-primary">
                <i data-feather="download" class="w-4 h-4"></i> CSV
            </a>
            <a href="#" onclick="exportAbsensi('pdf'); return false;" class="btn btn-primary">
                <i data-feather="download" class="w-4 h-4"></i> PDF
            </a>
        </div>
    </header>

    <!-- Content Section -->
    <!-- Pull content UP significantly to close the gap -->
    <div class="content" style="position: relative; z-index: 5; top: -50px;">


    <?php if ($this->session->flashdata('sukses')): ?>
        <div class="alert alert-success show mb-2 mt-2" role="alert"><?= $this->session->flashdata('sukses'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('gagal')): ?>
        <div class="alert alert-danger show mb-2 mt-2" role="alert"><?= $this->session->flashdata('gagal'); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- FORM INPUT -->
        <div class="col-span-12 lg:col-span-4 intro-y">
            <div class="box">
                <div class="flex items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">Input Absensi Harian</h2>
                </div>
                <div class="p-5">
                    <form action="<?= site_url('HR/save_absensi') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="input w-full border" value="<?= $selected_date ?>"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Karyawan</label>
                            <select name="id_karyawan" class="input w-full border" required>
                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($karyawan_list as $k): ?>
                                    <option value="<?= $k->kry_kode ?>"><?= $k->kry_nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="input w-full border" id="statusAbsensi" required>
                                <option value="HADIR">HADIR</option>
                                <option value="IZIN">IZIN</option>
                                <option value="CUTI">CUTI</option>
                                <option value="TELAT">TELAT</option>
                                <option value="ALPA">ALPA</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-3 jam-input">
                            <div>
                                <label class="form-label">Masuk</label>
                                <input type="time" name="jam_masuk" class="input w-full border" value="08:00">
                            </div>
                            <div>
                                <label class="form-label">Pulang</label>
                                <input type="time" name="jam_pulang" class="input w-full border" value="17:00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="input w-full border" rows="3"
                                placeholder="Alasan..."></textarea>
                        </div>
                        <div class="text-right mt-5">
                            <button type="submit" class="button w-24 bg-theme-1 text-white shadow-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLE LIST -->
        <div class="col-span-12 lg:col-span-8 intro-y">
            <div class="box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Daftar Absensi: <?= date('d M Y', strtotime($selected_date)) ?>
                    </h2>
                    <form action="<?= site_url('HR/absensi') ?>" method="GET"
                        class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
                        <input type="date" name="tanggal" value="<?= $selected_date ?>" class="input w-40 border mr-2">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md">Filter</button>
                    </form>
                </div>
                <div class="p-5" id="responsive-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="whitespace-nowrap">Nama</th>
                                        <th class="whitespace-nowrap">Posisi</th>
                                        <th class="whitespace-nowrap text-center">Status</th>
                                        <th class="whitespace-nowrap text-center">Waktu</th>
                                        <th class="whitespace-nowrap">Ket</th>
                                        <th class="whitespace-nowrap text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($absensi_list)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-600 py-4">Belum ada data absensi.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($absensi_list as $row): ?>
                                            <tr class="bg-white border-b">
                                                <td class="font-medium"><?= $row['nama_karyawan'] ?></td>
                                                <td class="text-gray-600"><?= $row['posisi'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $cls = 'bg-gray-200 text-gray-600';
                                                    if ($row['status'] == 'HADIR')
                                                        $cls = 'bg-theme-9 text-white';
                                                    elseif ($row['status'] == 'TELAT')
                                                        $cls = 'bg-theme-12 text-white';
                                                    elseif ($row['status'] == 'ALPA')
                                                        $cls = 'bg-theme-6 text-white';
                                                    elseif ($row['status'] == 'IZIN')
                                                        $cls = 'bg-theme-1 text-white';
                                                    ?>
                                                    <div class="rounded px-2 py-1 text-xs <?= $cls ?> w-fit mx-auto">
                                                        <?= $row['status'] ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($row['status'] == 'HADIR' || $row['status'] == 'TELAT'): ?>
                                                        <?= substr($row['jam_masuk'], 0, 5) ?> -
                                                        <?= substr($row['jam_pulang'], 0, 5) ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-xs text-gray-600"><?= $row['keterangan'] ?: '-' ?></td>
                                                <td class="text-center">
                                                    <div class="flex justify-center gap-1">
                                                        <button class="button px-2 py-1 bg-theme-1 text-white text-xs rounded shadow-md" 
                                                            onclick='editAbsensi(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                                            <i data-feather="edit-2" class="w-3 h-3"></i>
                                                        </button>
                                                        <a href="<?= site_url('HR/delete_absensi/' . $row['tanggal'] . '/' . $row['id_karyawan']) ?>" 
                                                            class="button px-2 py-1 bg-theme-6 text-white text-xs rounded shadow-md"
                                                            onclick="return confirm('Yakin hapus data absensi ini?')">
                                                            <i data-feather="trash-2" class="w-3 h-3"></i>
                                                        </a>
                                                    </div>
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
    </div>
</div>
</main>
</div>

<!-- Simple Script to toggle time inputs based on status -->
<script>
    document.getElementById('statusAbsensi').addEventListener('change', function () {
        var status = this.value;
        var jamInputs = document.querySelectorAll('.jam-input input');

        if (status === 'IZIN' || status === 'CUTI' || status === 'ALPA') {
            jamInputs.forEach(input => {
                input.value = '';
                input.disabled = true;
            });
        } else {
            jamInputs.forEach(input => {
                input.disabled = false;
                if (!input.value) {
                    if (input.name === 'jam_masuk') input.value = '08:00';
                    if (input.name === 'jam_pulang') input.value = '17:00';
                }
            });
        }
    });
</script>

<script>
    function editAbsensi(data) {
        // Populate inputs
        document.querySelector('input[name="tanggal"]').value = data.tanggal;
        document.querySelector('select[name="id_karyawan"]').value = data.id_karyawan;
        
        var statusSelect = document.querySelector('select[name="status"]');
        statusSelect.value = data.status;
        // Trigger change event to update time inputs state
        statusSelect.dispatchEvent(new Event('change'));

        document.querySelector('input[name="jam_masuk"]').value = data.jam_masuk;
        document.querySelector('input[name="jam_pulang"]').value = data.jam_pulang;
        document.querySelector('textarea[name="keterangan"]').value = data.keterangan;

        // Scroll to form
        document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
    }

    function exportAbsensi(format) {
        var tipe = document.getElementById('periodeType').value;
        var periode = document.getElementById('periodeValue').value;
        
        var url = '<?= site_url('HR/export_absensi_') ?>' + format + '?tipe=' + tipe + '&periode=' + periode;
        window.location.href = url;
    }

    // Update periode input based on type
    document.getElementById('periodeType').addEventListener('change', function() {
        var type = this.value;
        var input = document.getElementById('periodeValue');
        
        if (type === 'harian') {
            input.type = 'date';
            input.value = '<?= date('Y-m-d') ?>';
        } else if (type === 'mingguan') {
            input.type = 'week';
            input.value = '<?= date('Y') ?>-W<?= date('W') ?>';
        } else {
            input.type = 'month';
            input.value = '<?= date('Y-m') ?>';
        }
    });
</script>

<?php $this->load->view('Template/footer'); ?>