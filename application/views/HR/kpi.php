<?php $this->load->view('Template/header'); ?>

<!-- Header -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>KPI Karyawan</h1>
        <p>Penilaian Kinerja Karyawan (Key Performance Indicator)</p>
    </div>
</header>

<!-- Content -->
<div class="content-area">
    <!-- Form Input Laporan Mingguan -->
    <div class="intro-y box overflow-hidden mt-8 mb-5" id="formInputLaporanMingguan">
        <div class="flex items-center p-5 border-b border-gray-200 bg-theme-1 text-white">
            <h2 class="font-medium text-base mr-auto">Input Laporan Kinerja Mingguan</h2>
        </div>
        <div class="p-5">
            <form action="<?= site_url('HR/save_laporan_mingguan') ?>" method="POST">
                <div class="grid grid-cols-12 gap-4">
                    <!-- Row 1: Karyawan & Periode -->
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Karyawan</label>
                        <select name="id_karyawan" class="input w-full border" required>
                            <option value="">Pilih Karyawan</option>
                            <?php foreach ($karyawan_list as $k): ?>
                                <option value="<?= $k->kry_kode ?>"><?= $k->kry_nama ?> - <?= $k->kry_jabatan ?? 'Staff' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Periode (Minggu)</label>
                        <input type="week" name="periode" class="input w-full border" value="<?= date('Y') ?>-W<?= date('W') ?>" required>
                    </div>

                    <!-- Row 2: Target & Tugas (2 kolom) -->
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Target Mingguan</label>
                        <textarea name="target_mingguan" class="input w-full border" rows="3" placeholder="Apa target yang ingin dicapai minggu ini?" required></textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Tugas Dilakukan</label>
                        <textarea name="tugas_dilakukan" class="input w-full border" rows="3" placeholder="Tugas apa saja yang sudah dilakukan?" required></textarea>
                    </div>

                    <!-- Row 3: Hasil & Kendala (2 kolom) -->
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Hasil</label>
                        <textarea name="hasil" class="input w-full border" rows="3" placeholder="Apa hasil yang dicapai?" required></textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Kendala</label>
                        <textarea name="kendala" class="input w-full border" rows="3" placeholder="Kendala apa yang dihadapi?" required></textarea>
                    </div>

                    <!-- Row 4: Solusi (full width) -->
                    <div class="col-span-12">
                        <label class="form-label font-bold">Solusi</label>
                        <textarea name="solusi" class="input w-full border" rows="3" placeholder="Solusi untuk mengatasi kendala" required></textarea>
                    </div>

                    <div class="col-span-12 text-right">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md w-full sm:w-auto px-6">
                            <i data-feather="save" class="w-4 h-4 inline mr-1"></i> Simpan Laporan Mingguan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Input Form Section -->
    <div class="intro-y box overflow-hidden mt-5 mb-5" id="formInputKPI">
        <div class="flex items-center p-5 border-b border-gray-200 bg-theme-1 text-white">
            <h2 class="font-medium text-base mr-auto">Input Penilaian Kinerja</h2>
        </div>
        <div class="p-5">
            <form action="<?= site_url('HR/save_kpi') ?>" method="POST">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Siklus Penilaian</label>
                        <select name="siklus" id="siklusSelect" class="input w-full border"
                            onchange="changeSiklus(this.value)" required>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="harian">Harian</option>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Periode</label>
                        <!-- Inputs for different cycles -->
                        <input type="month" name="periode_bulanan" id="input-bulanan" class="input w-full border"
                            value="<?= date('Y-m') ?>">
                        <input type="week" name="periode_mingguan" id="input-mingguan" class="input w-full border"
                            style="display:none;">
                        <input type="date" name="periode_harian" id="input-harian" class="input w-full border"
                            style="display:none;">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label font-bold">Karyawan</label>
                        <select name="id_karyawan" class="input w-full border" required>
                            <option value="">Pilih Karyawan</option>
                            <?php foreach ($karyawan_list as $k): ?>
                                <option value="<?= $k->kry_kode ?>"><?= $k->kry_nama ?> - <?= $k->kry_jabatan ?? 'Staff' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label class="form-label font-bold">Kedisiplinan (1-5)</label>
                        <input type="number" name="kedisiplinan" class="input w-full border" min="1" max="5" value="3"
                            required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label class="form-label font-bold">Kualitas Kerja (1-5)</label>
                        <input type="number" name="kualitas_kerja" class="input w-full border" min="1" max="5" value="3"
                            required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label class="form-label font-bold">Produktivitas (1-5)</label>
                        <input type="number" name="produktivitas" class="input w-full border" min="1" max="5" value="3"
                            required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label class="form-label font-bold">Kerja Tim (1-5)</label>
                        <input type="number" name="kerja_tim" class="input w-full border" min="1" max="5" value="3"
                            required>
                    </div>

                    <div class="col-span-12">
                        <label class="form-label font-bold">Catatan Evaluasi</label>
                        <textarea name="catatan" class="input w-full border" rows="3"
                            placeholder="Catatan kinerja..."></textarea>
                    </div>

                    <div class="col-span-12 text-right">
                        <button type="submit" class="button text-white bg-theme-1 shadow-md w-full sm:w-auto px-6">
                            <i data-feather="save" class="w-4 h-4 inline mr-1"></i> Simpan KPI
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</main>
</div>

<script>
    // Switch KPI Input Period
    function changeSiklus(val) {
        document.getElementById('input-bulanan').style.display = 'none';
        document.getElementById('input-mingguan').style.display = 'none';
        document.getElementById('input-harian').style.display = 'none';

        if (val === 'bulanan') {
            document.getElementById('input-bulanan').style.display = 'block';
        } else if (val === 'mingguan') {
            document.getElementById('input-mingguan').style.display = 'block';
        } else {
            document.getElementById('input-harian').style.display = 'block';
        }
    }

    // Switch Filter Period - for recap section
    function changeSiklusFilter(val) {
        document.getElementById('periode_bulanan').style.display = 'none';
        document.getElementById('periode_mingguan').style.display = 'none';
        document.getElementById('periode_harian').style.display = 'none';
        document.getElementById('periode_tahunan').style.display = 'none';

        if (val === 'bulanan') {
            document.getElementById('periode_bulanan').style.display = 'block';
            document.getElementById('periode_bulanan').required = true;
        } else if (val === 'mingguan') {
            document.getElementById('periode_mingguan').style.display = 'block';
            document.getElementById('periode_mingguan').required = true;
        } else if (val === 'harian') {
            document.getElementById('periode_harian').style.display = 'block';
            document.getElementById('periode_harian').required = true;
        } else if (val === 'tahunan') {
            document.getElementById('periode_tahunan').style.display = 'block';
            document.getElementById('periode_tahunan').required = true;
        }
    }

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize filter on page load
    document.addEventListener('DOMContentLoaded', function() {
        const siklus = '<?= $selected_siklus ?? 'bulanan' ?>';
        document.getElementById('filterSiklus').value = siklus;
        changeSiklusFilter(siklus);
        
        // Set input form siklus too
        const inputSiklus = document.getElementById('siklusSelect');
        if (inputSiklus) {
            inputSiklus.value = 'bulanan'; // Default input to bulanan
            changeSiklus('bulanan');
        }
    });

    // Close dialog when clicking backdrop
    document.querySelectorAll('.kpi-dialog').forEach(function (dialog) {
        dialog.addEventListener('click', function (e) {
            if (e.target === dialog) {
                dialog.close();
            }
        });
    });
</script>



<?php $this->load->view('Template/footer'); ?>