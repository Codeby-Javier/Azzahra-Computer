
<div class="content">
    <!-- Custom Page Header -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <div class="flex items-center mb-2">
                    <div class="bg-orange-100 rounded-full p-3 mr-4">
                        <i data-feather="edit-2" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Voucher Discount</h1>
                        <p class="text-gray-600 mt-1">Perbarui informasi voucher diskon</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?= site_url('Voucher') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-500 mt-4">
            <a href="<?= site_url('Admin') ?>" class="hover:text-orange-600 text-gray-500">Dashboard</a>
            <i data-feather="chevron-right" class="w-4 h-4 mx-2 text-gray-400"></i>
            <a href="<?= site_url('Voucher') ?>" class="hover:text-orange-600 text-gray-500">Voucher Discount</a>
            <i data-feather="chevron-right" class="w-4 h-4 mx-2 text-gray-400"></i>
            <span class="text-orange-600 font-medium">Edit Voucher</span>
        </div>
    </div>

    <!-- Voucher Info Banner -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i data-feather="tag" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        <?= htmlspecialchars($voucher->voucher_code) ?>
                    </h3>
                    <p class="text-gray-600">
                        Diskon <?= $voucher->discount_percent ?>% •
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php echo $voucher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?= ucfirst($voucher->status) ?>
                        </span>
                    </p>
                </div>
            </div>
            <div class="text-right text-sm text-gray-500">
                <div>ID: #<?= $voucher->voucher_id ?></div>
                <div>Dibuat: <?= date('d/m/Y', strtotime($voucher->start_date)) ?></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Main Form -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <!-- Basic Information Card -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h3 class="font-medium text-base mr-auto flex items-center">
                        <i data-feather="info" class="w-5 h-5 mr-2 text-theme-1"></i>
                        Informasi Dasar
                    </h3>
                </div>

                <div class="p-5">
                    <form action="<?= site_url('Voucher/update') ?>" method="POST" enctype="multipart/form-data" id="voucher-form">
                        <input type="hidden" name="voucher_id" value="<?= $voucher->voucher_id ?>">
                        <input type="hidden" name="old_voucher_code" value="<?= htmlspecialchars($voucher->voucher_code) ?>">

                        <div class="grid grid-cols-12 gap-4">
                            <!-- Voucher Code -->
                            <div class="col-span-12 sm:col-span-6">
                                <label for="voucher_code" class="form-label font-medium text-gray-700 mb-2 block">
                                    Kode Voucher <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-feather="tag" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input
                                        id="voucher_code"
                                        name="voucher_code"
                                        type="text"
                                        class="form-control pl-10"
                                        placeholder="Masukkan kode voucher unik"
                                        value="<?= htmlspecialchars($voucher->voucher_code) ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Discount Percent -->
                            <div class="col-span-12 sm:col-span-6">
                                <label for="discount_percent" class="form-label font-medium text-gray-700 mb-2 block">
                                    Persentase Diskon <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        id="discount_percent"
                                        name="discount_percent"
                                        type="number"
                                        class="form-control pr-12"
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        value="<?= $voucher->discount_percent ?>"
                                        required
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-12">
                                <label for="description" class="form-label font-medium text-gray-700 mb-2 block">
                                    Deskripsi Voucher
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Jelaskan detail voucher ini (opsional)"
                                ><?= htmlspecialchars($voucher->description) ?></textarea>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Validity Period Card -->
            <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h3 class="font-medium text-base mr-auto flex items-center">
                        <i data-feather="calendar" class="w-5 h-5 mr-2 text-theme-1"></i>
                        Periode Berlaku
                    </h3>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Start Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="start_date" class="form-label font-medium text-gray-700 mb-2 block">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="calendar" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="date"
                                    class="form-control pl-10"
                                    value="<?= date('Y-m-d', strtotime($voucher->start_date)) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="end_date" class="form-label font-medium text-gray-700 mb-2 block">
                                Tanggal Berakhir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="calendar" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input
                                    id="end_date"
                                    name="end_date"
                                    type="date"
                                    class="form-control pl-10"
                                    value="<?= date('Y-m-d', strtotime($voucher->end_date)) ?>"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h3 class="font-medium text-base mr-auto flex items-center">
                        <i data-feather="settings" class="w-5 h-5 mr-2 text-theme-1"></i>
                        Pengaturan & Media
                    </h3>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Max Usage -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="max_usage" class="form-label font-medium text-gray-700 mb-2 block">
                                Maksimal Penggunaan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="users" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input
                                    id="max_usage"
                                    name="max_usage"
                                    type="number"
                                    class="form-control pl-10"
                                    placeholder="1"
                                    min="1"
                                    value="<?= $voucher->max_usage ?>"
                                >
                            </div>
                            <small class="text-gray-500 mt-1 block">Kosongkan untuk unlimited</small>
                        </div>

                        <!-- Status -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="status" class="form-label font-medium text-gray-700 mb-2 block">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" <?= $voucher->status == 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= $voucher->status == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-span-12">
                            <label class="form-label font-medium text-gray-700 mb-2 block">
                                Gambar Voucher
                            </label>

                            <!-- Current Image Display -->
                            <?php if ($voucher->voucher_gambar): ?>
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <?php $image_url = $this->config->item('voucher_image_url') . $voucher->voucher_gambar; ?>
                                            <img src="<?= $image_url ?>" alt="Current Image" class="w-16 h-16 object-cover rounded-lg mr-3 border">
                                            <div>
                                                <div class="font-medium text-gray-900">Gambar Saat Ini</div>
                                                <div class="text-sm text-gray-500">Klik untuk melihat gambar penuh</div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="showCurrentImage('<?= $image_url ?>')"
                                                class="btn btn-outline-primary btn-sm">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- New Image Upload -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-theme-1 transition-colors">
                                <div class="text-center">
                                    <i data-feather="image" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                                    <div class="text-gray-600 mb-2">
                                        <?= $voucher->voucher_gambar ? 'Ganti gambar voucher' : 'Pilih gambar untuk voucher' ?>
                                    </div>
                                    <input
                                        id="voucher_gambar"
                                        name="voucher_gambar"
                                        type="file"
                                        class="hidden"
                                        accept="image/*"
                                        onchange="previewImage(this)"
                                    >
                                    <button type="button" onclick="document.getElementById('voucher_gambar').click()"
                                            class="btn btn-outline-primary">
                                        <i data-feather="upload" class="w-4 h-4 mr-2"></i>
                                        <?= $voucher->voucher_gambar ? 'Ganti Gambar' : 'Pilih Gambar' ?>
                                    </button>
                                    <div id="image-preview" class="mt-3 hidden">
                                        <img id="preview-img" src="" alt="Preview" class="max-w-xs mx-auto rounded-lg shadow-md">
                                        <div class="text-sm text-gray-600 mt-2">Preview gambar baru</div>
                                    </div>
                                </div>
                            </div>
                            <small class="text-gray-500 mt-2 block">
                                Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG, GIF. Maksimal 2MB
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end mt-6">
                <div class="flex gap-4">
                    <!-- Cancel Button -->
                    <a href="<?= site_url('Voucher') ?>" class="group relative bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-8 py-3 rounded-xl transition-all duration-300 flex items-center font-semibold shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-3 text-gray-500 group-hover:text-gray-600">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        <span class="relative z-10">Batal</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>

                    <!-- Update Button -->
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl transition-all duration-300 flex items-center font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Update Voucher
                    </button>
                </div>
            </div>
        </div>
    </form>

        <!-- Sidebar Info -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Voucher Stats Card -->
            <div class="intro-y box p-5">
                <div class="flex items-center border-b border-gray-200 pb-3 mb-4">
                    <div class="font-medium text-base flex items-center">
                        <i data-feather="bar-chart-2" class="w-5 h-5 mr-2 text-theme-1"></i>
                        Statistik Voucher
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Penggunaan</span>
                        <span class="font-semibold text-theme-1">
                            <?php
                            // This would need a usage tracking table, for now show placeholder
                            echo "0";
                            ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Sisa Kuota</span>
                        <span class="font-semibold text-green-600">
                            <?php
                            echo $voucher->max_usage ? $voucher->max_usage : '∞';
                            ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            <?php echo $voucher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?= ucfirst($voucher->status) ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Berlaku Hingga</span>
                        <span class="font-semibold text-orange-600">
                            <?= date('d/m/Y', strtotime($voucher->end_date)) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="intro-y box p-5 mt-5">
                <div class="flex items-center border-b border-gray-200 pb-3 mb-4">
                    <div class="font-medium text-base flex items-center">
                        <i data-feather="help-circle" class="w-5 h-5 mr-2 text-theme-1"></i>
                        Tips Edit
                    </div>
                </div>
                <div class="text-gray-600 text-sm space-y-3">
                    <div class="flex items-start">
                        <i data-feather="alert-circle" class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0 mt-0.5"></i>
                        <div>Mengubah kode voucher dapat mempengaruhi penggunaan yang sudah ada</div>
                    </div>
                    <div class="flex items-start">
                        <i data-feather="check-circle" class="w-4 h-4 mr-2 text-green-500 flex-shrink-0 mt-0.5"></i>
                        <div>Gambar baru akan menggantikan gambar lama</div>
                    </div>
                    <div class="flex items-start">
                        <i data-feather="info" class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0 mt-0.5"></i>
                        <div>Perubahan akan langsung aktif setelah disimpan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Tidak Didukung',
                text: 'Hanya file JPG, PNG, dan GIF yang diizinkan'
            });
            input.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2048000) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal 2MB'
            });
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Show current image in modal
function showCurrentImage(imageUrl) {
    Swal.fire({
        title: 'Gambar Voucher Saat Ini',
        imageUrl: imageUrl,
        imageAlt: 'Current Voucher Image',
        showCloseButton: true,
        showConfirmButton: false,
        width: '600px',
        customClass: {
            popup: 'rounded-lg'
        }
    });
}

// Set minimum start date to today
document.getElementById('start_date').min = new Date().toISOString().split('T')[0];

// Set end date minimum to start date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});

// Form validation and submission
document.getElementById('voucher-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (startDate < today) {
        Swal.fire({
            icon: 'error',
            title: 'Tanggal Tidak Valid',
            text: 'Tanggal mulai tidak boleh kurang dari hari ini',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc2626',
            customClass: {
                confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium'
            }
        });
        return;
    }

    if (endDate <= startDate) {
        Swal.fire({
            icon: 'error',
            title: 'Tanggal Tidak Valid',
            text: 'Tanggal berakhir harus setelah tanggal mulai',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc2626',
            customClass: {
                confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium'
            }
        });
        return;
    }

    // Submit via AJAX
    const formData = new FormData(this);

    fetch('<?= site_url('Voucher/update') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium'
                }
            }).then(() => {
                window.location.href = '<?= site_url('Voucher') ?>';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc2626',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengirim data',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc2626',
            customClass: {
                confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium'
            }
        });
    });
});

// Initialize feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>