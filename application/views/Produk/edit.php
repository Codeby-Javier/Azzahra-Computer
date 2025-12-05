<?php $this->load->view('Template/header'); ?>
<!-- BEGIN: Content -->
<div class="content" style="margin-top: -60px;">
    <!-- BEGIN: Top Bar -->
    <div class="intro-y flex items-center">
        <h2 class="text-lg font-medium mr-auto">
            Edit Produk
        </h2>
        <a href="<?= site_url('Produk') ?>" class="btn btn-outline-secondary shadow-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left w-4 h-4 mr-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>
    </div>
    <!-- END: Top Bar -->
    
    <!-- BEGIN: Alert Messages -->
    <?php if($this->session->flashdata('error')): ?>
    <div class="intro-y mt-5">
        <div class="alert alert-danger show mb-2" role="alert">
            <div class="flex items-center">
                <i data-feather="alert-circle" class="w-5 h-5 mr-2"></i>
                <div class="font-medium"><?= $this->session->flashdata('error') ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('sukses')): ?>
    <div class="intro-y mt-5">
        <div class="alert alert-success show mb-2" role="alert">
            <div class="flex items-center">
                <i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
                <div class="font-medium"><?= $this->session->flashdata('sukses') ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- END: Alert Messages -->

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Form Edit -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <form action="<?= site_url('Produk/update') ?>" method="POST" id="form-edit" autocomplete="off" enctype="multipart/form-data">
                <!-- Hidden fields -->
                <input type="hidden" name="kode_barang_lama" value="<?= $produk->kode_barang ?>">
                <input type="hidden" name="images_data" id="images_data" value="">
                
                <!-- BEGIN: Form Box -->
                <div class="intro-y box">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Informasi Produk
                        </h2>
                    </div>
                    
                    <!-- Form Fields -->
                    <div id="input" class="p-5">
                        <div class="preview">
                            <!-- ========== GAMBAR MANAGER ========== -->
                            <div class="mb-6">
                                <label class="form-label font-medium flex items-center justify-between mb-3">
                                    <span>
                                        <i data-feather="image" class="w-4 h-4 inline mr-2"></i>
                                        Galeri Produk
                                    </span>
                                    <span class="text-xs text-gray-500 font-normal">
                                        <span id="images-count">0</span>/5 gambar
                                    </span>
                                </label>
                                
                                <!-- Grid Gambar -->
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4" id="images-grid">
                                    <?php 
                                    $existingImages = array_filter(array_map('trim', explode(',', $produk->gambar)));
                                    $laravel_image_url = $this->config->item('laravel_image_url');
                                    
                                    foreach ($existingImages as $index => $image): 
                                    ?>
                                    <div class="image-item relative" data-image-name="<?= htmlspecialchars($image) ?>">
                                        <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-gray-300 transition-all cursor-pointer bg-gray-100">
                                            <img
                                                src="<?= $laravel_image_url . $image ?>"
                                                alt="Image <?= $index + 1 ?>"
                                                class="w-full h-full object-cover transition-all"
                                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZTVlN2ViIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Ob3QgRm91bmQ8L3RleHQ+PC9zdmc+'"
                                            >
                                            
                                            <!-- Overlay Actions -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 transition-all flex items-center justify-center">
                                                <div class="flex gap-1">
                                                    <!-- Button Edit/Replace -->
                                                    <button
                                                        type="button"
                                                        class="btn-edit-image bg-blue-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-blue-700 transition-all flex items-center"
                                                        onclick="editImage(this)"
                                                        title="Ganti gambar ini"
                                                    >
                                                        <i data-feather="edit-2" class="w-3 h-3 mr-1"></i>
                                                        Ganti
                                                    </button>

                                                    <!-- Button Delete -->
                                                    <button
                                                        type="button"
                                                        class="btn-remove-existing-image bg-red-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-red-700 transition-all shadow-md flex items-center"
                                                        onclick="deleteImage(this)"
                                                        title="Hapus gambar ini"
                                                    >
                                                        <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Badge Index -->
                                            <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded shadow-lg font-semibold">
                                                <?= $index + 1 ?>
                                            </div>
                                            
                                            <!-- Badge Primary (jika gambar pertama) -->
                                            <?php if ($index === 0): ?>
                                            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg font-semibold">
                                                UTAMA
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Filename -->
                                        <div class="text-xs text-gray-600 mt-2 text-center truncate" title="<?= $image ?>">
                                            <?= substr($image, 0, 15) ?>...
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <!-- Button Add New (max 5) -->
                                    <div class="image-item-add" id="add-image-btn">
                                        <div class="relative aspect-square rounded-lg border-2 border-dashed border-gray-400 hover:border-blue-500 transition-all cursor-pointer bg-gray-50 hover:bg-blue-50 flex items-center justify-center group" onclick="triggerImageUpload()">
                                            <div class="text-center">
                                                <i data-feather="plus-circle" class="w-10 h-10 mx-auto text-gray-400 group-hover:text-blue-600 transition-all"></i>
                                                <div class="text-xs text-gray-500 group-hover:text-blue-600 mt-2 font-medium">
                                                    Tambah Gambar
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hidden File Input -->
                                <input 
                                    type="file" 
                                    id="image-upload-input" 
                                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                    style="display: none;"
                                    onchange="handleImageUpload(this)"
                                >
                                
                                <!-- Info -->
                                <div class="mt-3 text-xs text-gray-500 flex items-start">
                                    <i data-feather="info" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <strong>Tips:</strong> Klik <strong class="text-blue-600">ikon edit</strong> untuk mengganti gambar | 
                                        Klik <strong class="text-red-600">ikon hapus</strong> untuk menghapus | 
                                        Gambar pertama adalah thumbnail utama | 
                                        Format: JPG, PNG, GIF, WEBP (max 2MB)
                                    </div>
                                </div>
                            </div>

                            <!-- Kode Barang -->
                            <div class="mb-5">
                                <label for="kode_barang" class="form-label font-medium">
                                    Kode Barang <span class="text-red-600">*</span>
                                </label>
                                <input 
                                    id="kode_barang" 
                                    name="kode_barang" 
                                    type="text" 
                                    class="form-control w-full border border-gray-300" 
                                    placeholder="Masukkan kode barang"
                                    value="<?= htmlspecialchars($produk->kode_barang) ?>"
                                    required
                                >
                            </div>

                            <!-- Nama Produk -->
                            <div class="mb-5">
                                <label for="nama_produk" class="form-label font-medium">
                                    Nama Produk <span class="text-red-600">*</span>
                                </label>
                                <input 
                                    id="nama_produk" 
                                    name="nama_produk" 
                                    type="text" 
                                    class="form-control w-full border border-gray-300" 
                                    placeholder="Masukkan nama produk"
                                    value="<?= htmlspecialchars($produk->nama_produk) ?>"
                                    required
                                >
                            </div>

                            <!-- Harga -->
                            <div class="mb-5">
                                <label for="harga" class="form-label font-medium">
                                    Harga <span class="text-red-600">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text bg-gray-100 border border-gray-300">Rp</div>
                                    <input 
                                        id="harga" 
                                        name="harga" 
                                        type="text" 
                                        class="form-control border border-gray-300" 
                                        placeholder="0"
                                        value="<?= number_format($produk->harga, 0, ',', '.') ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-5">
                                <label for="deskripsi" class="form-label font-medium">
                                    Deskripsi Produk
                                </label>
                                <textarea 
                                    id="deskripsi" 
                                    name="deskripsi" 
                                    class="form-control w-full border border-gray-300" 
                                    rows="6" 
                                    placeholder="Masukkan deskripsi produk (opsional)"
                                ><?= htmlspecialchars($produk->deskripsi) ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                   <!-- Footer Actions -->
                    <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5 bg-gray-50">
                        <button type="button" class="btn btn-outline-secondary w-32 mr-2" onclick="window.location.href='<?= site_url('Produk') ?>'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x w-4 h-4 mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Batal
                        </button>
                        <button type="button" class="btn btn-outline-danger w-32 mr-2" id="btn-reset">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw w-4 h-4 mr-1"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-warning w-32" id="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save w-4 h-4 mr-1"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Update
                        </button>
                    </div>
                </div>
                <!-- END: Form Box -->
            </form>
        </div>
        <!-- END: Form Edit -->

        <!-- BEGIN: Info Sidebar -->
        <div class="intro-y col-span-12 lg:col-span-4">
            <!-- Informasi Produk -->
            <div class="intro-y box p-5 mb-5">
                <div class="flex items-center border-b border-gray-200 pb-3 mb-3">
                    <div class="font-medium text-base">Informasi</div>
                    <i data-feather="file-text" class="w-4 h-4 ml-auto text-theme-1"></i>
                </div>
                <div class="text-gray-600 text-xs space-y-3">
                    <div class="flex items-start">
                        <i data-feather="hash" class="w-4 h-4 mr-2 text-theme-1 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-medium text-gray-700">Kode Barang</div>
                            <div class="mt-1 font-semibold text-theme-1"><?= $produk->kode_barang ?></div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i data-feather="image" class="w-4 h-4 mr-2 text-theme-1 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-medium text-gray-700">Total Gambar</div>
                            <div class="mt-1 font-semibold text-theme-9" id="sidebar-image-count"><?= count($existingImages) ?> foto</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Box -->
            <div class="intro-y box p-5">
                <div class="flex items-center border-b border-gray-200 pb-3 mb-3">
                    <div class="font-medium text-base">Panduan Edit Gambar</div>
                    <i data-feather="help-circle" class="w-4 h-4 ml-auto text-theme-1"></i>
                </div>
                <div class="text-gray-600 text-xs space-y-3">
                    <div class="flex items-start">
                        <div class="bg-blue-100 rounded-full p-1 mr-2 flex-shrink-0">
                            <i data-feather="edit-2" class="w-3 h-3 text-blue-600"></i>
                        </div>
                        <div>
                            <strong>Ganti Gambar:</strong> Klik ikon edit pada gambar yang ingin diganti
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-red-100 rounded-full p-1 mr-2 flex-shrink-0">
                            <i data-feather="trash-2" class="w-3 h-3 text-red-600"></i>
                        </div>
                        <div>
                            <strong>Hapus Gambar:</strong> Klik ikon hapus untuk menghapus gambar
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-green-100 rounded-full p-1 mr-2 flex-shrink-0">
                            <i data-feather="plus-circle" class="w-3 h-3 text-green-600"></i>
                        </div>
                        <div>
                            <strong>Tambah Gambar:</strong> Klik tombol "+" untuk menambah gambar baru
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-yellow-100 rounded-full p-1 mr-2 flex-shrink-0">
                            <i data-feather="star" class="w-3 h-3 text-yellow-600"></i>
                        </div>
                        <div>
                            <strong>Gambar Utama:</strong> Gambar pertama otomatis jadi thumbnail utama
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded p-2 mt-3">
                        <div class="text-blue-800 font-semibold mb-1">Spesifikasi:</div>
                        <ul class="list-disc list-inside text-blue-700">
                            <li>Format: JPG, PNG, GIF, WEBP</li>
                            <li>Ukuran max: 2MB per file</li>
                            <li>Maksimal: 5 gambar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Info Sidebar -->
    </div>
</div>
<!-- END: Content -->

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ========== GLOBAL VARIABLES ==========
let currentEditingImage = null;
let imagesData = [];

document.addEventListener('DOMContentLoaded', function() {
    // Init Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize images data dari existing images
    initializeImagesData();
    updateImagesCount();
    updateAddButtonVisibility();

    // Format harga
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', formatHarga);
    formatHarga();
});

// ========== INITIALIZE IMAGES DATA ==========
function initializeImagesData() {
    const imageItems = document.querySelectorAll('.image-item');
    imagesData = [];
    
    imageItems.forEach((item, index) => {
        const imageName = item.getAttribute('data-image-name');
        if (imageName) {
            imagesData.push({
                type: 'existing', // existing | new | replaced
                oldName: imageName,
                newName: imageName,
                file: null,
                action: 'keep' // keep | delete | replace
            });
        }
    });
}

// ========== TRIGGER IMAGE UPLOAD (Add New) ==========
function triggerImageUpload() {
    if (imagesData.length >= 5) {
        Swal.fire({
            icon: 'warning',
            title: 'Maksimal 5 Gambar',
            text: 'Hapus gambar yang ada terlebih dahulu jika ingin menambah gambar baru'
        });
        return;
    }
    
    currentEditingImage = null; // Flag untuk add, bukan replace
    document.getElementById('image-upload-input').click();
}

// ========== EDIT IMAGE (Replace) ==========
function editImage(button) {
    const imageItem = button.closest('.image-item');
    const imageName = imageItem.getAttribute('data-image-name');
    
    Swal.fire({
        title: 'Ganti Gambar?',
        text: 'Pilih gambar baru untuk menggantikan gambar ini',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Pilih Gambar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            currentEditingImage = imageName;
            document.getElementById('image-upload-input').click();
        }
    });
}

// ========== DELETE IMAGE ==========
function deleteImage(button) {
    const imageItem = button.closest('.image-item');
    const imageName = imageItem.getAttribute('data-image-name');
    const imageIndex = Array.from(imageItem.parentElement.children).indexOf(imageItem) + 1;
    
    Swal.fire({
        title: 'Hapus Gambar?',
        html: `Gambar <strong>#${imageIndex}</strong> akan dihapus permanen`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Update imagesData
            const dataIndex = imagesData.findIndex(img => img.newName === imageName || img.oldName === imageName);
            if (dataIndex !== -1) {
                if (imagesData[dataIndex].type === 'existing' || imagesData[dataIndex].type === 'replaced') {
                    imagesData[dataIndex].action = 'delete';
                } else {
                    imagesData.splice(dataIndex, 1);
                }
            }
            
            // Remove from DOM dengan animasi
            imageItem.style.transition = 'all 0.3s ease';
            imageItem.style.opacity = '0';
            imageItem.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                imageItem.remove();
                updateImagesCount();
                updateAddButtonVisibility();
                reorderImageBadges();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Gambar Dihapus',
                    text: 'Perubahan akan disimpan saat Anda klik Update',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 300);
        }
    });
}

// ========== HANDLE IMAGE UPLOAD ==========
function handleImageUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi tipe file
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Format Tidak Didukung',
            text: 'Hanya file JPG, PNG, GIF, dan WEBP yang diizinkan'
        });
        input.value = '';
        return;
    }
    
    // Validasi ukuran file (max 2MB)
    if (file.size > 2048000) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: `Ukuran file: ${(file.size / 1024 / 1024).toFixed(2)} MB. Maksimal 2MB`
        });
        input.value = '';
        return;
    }
    
    // Preview & Add/Replace
    const reader = new FileReader();
    reader.onload = function(e) {
        if (currentEditingImage) {
            // REPLACE MODE
            replaceImagePreview(currentEditingImage, file, e.target.result);
        } else {
            // ADD NEW MODE
            addNewImagePreview(file, e.target.result);
        }
        
        input.value = ''; // Reset input
        currentEditingImage = null;
    };
    reader.readAsDataURL(file);
}

// ========== REPLACE IMAGE PREVIEW ==========
function replaceImagePreview(oldImageName, file, previewUrl) {
    const imageItem = document.querySelector(`.image-item[data-image-name="${oldImageName}"]`);
    if (!imageItem) return;
    
    // Update gambar
    const img = imageItem.querySelector('img');
    img.src = previewUrl;
    
    // Update badge
    const badges = imageItem.querySelectorAll('.absolute');
    badges.forEach(badge => {
        if (badge.classList.contains('bg-blue-600')) {
            badge.classList.remove('bg-blue-600');
            badge.classList.add('bg-orange-600');
            badge.innerHTML = '<i data-feather="refresh-cw" class="w-3 h-3 inline mr-1"></i>DIGANTI';
            feather.replace();
        }
    });
    
    // Update imagesData
    const dataIndex = imagesData.findIndex(img => img.newName === oldImageName || img.oldName === oldImageName);
    if (dataIndex !== -1) {
        imagesData[dataIndex] = {
            type: 'replaced',
            oldName: oldImageName,
            newName: 'new_' + Date.now() + '_' + file.name,
            file: file,
            action: 'replace'
        };
    }
    
    Swal.fire({
        icon: 'success',
        title: 'Gambar Diganti',
        text: 'Preview ditampilkan. Klik Update untuk menyimpan',
        timer: 2000,
        showConfirmButton: false
    });
}

// ========== ADD NEW IMAGE PREVIEW ==========
function addNewImagePreview(file, previewUrl) {
    const grid = document.getElementById('images-grid');
    const addBtn = document.getElementById('add-image-btn');
    
    const newIndex = document.querySelectorAll('.image-item').length + 1;
    const tempName = 'new_' + Date.now() + '_' + file.name;
    
    const newItem = document.createElement('div');
    newItem.className = 'image-item relative group';
    newItem.setAttribute('data-image-name', tempName);
    newItem.innerHTML = `
        <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-green-400 hover:border-green-600 transition-all cursor-pointer bg-gray-100">
            <img
                src="${previewUrl}"
                alt="New Image"
                class="w-full h-full object-cover transition-all"
            >
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-black bg-opacity-0 transition-all flex items-center justify-center">
                <div class="flex gap-1">
                    <button
                        type="button"
                        class="btn-edit-image bg-blue-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-blue-700 transition-all flex items-center"
                        onclick="editImage(this)"
                        title="Ganti gambar ini"
                    >
                        <i data-feather="edit-2" class="w-3 h-3 mr-1"></i>
                        Ganti
                    </button>

                    <button
                        type="button"
                        class="btn-delete-image bg-red-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-red-700 transition-all flex items-center"
                        onclick="deleteImage(this)"
                        title="Hapus gambar ini"
                    >
                        <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                        Hapus
                    </button>
                </div>
            </div>
            
            <!-- Badge Index -->
            <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg font-semibold">
                ${newIndex}
            </div>
            
            <!-- Badge NEW -->
            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg font-semibold">
                BARU
            </div>
        </div>
        
        <div class="text-xs text-gray-600 mt-2 text-center truncate" title="${file.name}">
            ${file.name.substring(0, 15)}...
        </div>
    `;
    
    grid.insertBefore(newItem, addBtn);
    
    // Update imagesData
    imagesData.push({
        type: 'new',
        oldName: null,
        newName: tempName,
        file: file,
        action: 'add'
    });
    
    updateImagesCount();
    updateAddButtonVisibility();

    feather.replace();
}

// ========== UPDATE IMAGES COUNT ==========
function updateImagesCount() {
    const activeImages = imagesData.filter(img => img.action !== 'delete').length;
    document.getElementById('images-count').textContent = activeImages;
    document.getElementById('sidebar-image-count').textContent = activeImages + ' foto';
}

// ========== UPDATE ADD BUTTON VISIBILITY ==========
function updateAddButtonVisibility() {
    const addBtn = document.getElementById('add-image-btn');
    const activeImages = imagesData.filter(img => img.action !== 'delete').length;
    
    if (activeImages >= 5) {
        addBtn.style.display = 'none';
    } else {
        addBtn.style.display = 'block';
    }
}

// ========== REORDER IMAGE BADGES ==========
function reorderImageBadges() {
    const imageItems = document.querySelectorAll('.image-item');
    imageItems.forEach((item, index) => {
        const indexBadge = item.querySelector('.bg-blue-600, .bg-orange-600, .bg-green-600');
        if (indexBadge && !indexBadge.innerHTML.includes('data-feather')) {
            indexBadge.textContent = index + 1;
        }
        
        // Update badge UTAMA hanya untuk index 0
        const primaryBadge = item.querySelector('.bg-green-600:not(.bg-orange-600):nth-child(2)');
        if (index === 0 && !primaryBadge) {
            const badgeContainer = item.querySelector('.relative.aspect-square');
            const newBadge = document.createElement('div');
            newBadge.className = 'absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg font-semibold';
            newBadge.textContent = 'UTAMA';
            badgeContainer.appendChild(newBadge);
        } else if (index !== 0 && primaryBadge) {
            primaryBadge.remove();
        }
    });
}

// ========== FORMAT HARGA ==========
function formatHarga() {
    const input = document.getElementById('harga');
    let value = input.value.replace(/\./g, '').replace(/\D/g, '');
    if (value) {
        input.value = parseInt(value).toLocaleString('id-ID');
    }
}

// ========== FORM SUBMIT ==========
document.getElementById('form-edit').addEventListener('submit', function(e) {
    e.preventDefault();

    // Prepare FormData
    const formData = new FormData(this);

    // Add images data
    imagesData.forEach((img, index) => {
        if (img.action === 'add' || img.action === 'replace') {
            formData.append('images[]', img.file);
            formData.append('images_info[]', JSON.stringify({
                type: img.type,
                oldName: img.oldName,
                newName: img.newName,
                action: img.action
            }));
        }
    });

    // Add images to delete
    const toDelete = imagesData.filter(img => img.action === 'delete').map(img => img.oldName);
    formData.append('delete_images', JSON.stringify(toDelete));

    // Add images to keep (existing yang tidak dihapus/diganti)
    const toKeep = imagesData.filter(img => img.action === 'keep').map(img => img.newName);
    formData.append('keep_images', JSON.stringify(toKeep));

    // Show loading
    const btnSubmit = document.getElementById('btn-submit');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<svg class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';

    // Submit using fetch
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = 'Update';

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
                window.location.href = '<?= site_url('Produk') ?>';
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
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = 'Update';
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
</script>

<style>
/* Form Control */
.form-control {
    display: block;
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    line-height: 1.5715;
    color: #1f2937;
    background-color: #ffffff;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem;
    transition: all 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #3b82f6 !important;
    outline: 0;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

/* Input Group */
.input-group {
    display: flex;
    align-items: stretch;
}

.input-group-text {
    display: flex;
    align-items: center;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4b5563;
    background-color: #f3f4f6;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem 0 0 0.375rem;
}

.input-group > .form-control {
    border-radius: 0 0.375rem 0.375rem 0 !important;
    margin-left: -1px;
}

/* Button Warning */
.btn-warning {
    color: #ffffff;
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.4);
}

/* Image Grid */
.aspect-square {
    aspect-ratio: 1 / 1;
}

.image-item {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Spinner */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}


/* ========== BUTTON STYLING (LEBIH JELAS) ========== */

/* Base Button */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

/* Button Outline Secondary */
.btn-outline-secondary {
    color: #475569;
    background-color: #ffffff;
    border-color: #cbd5e1;
}

.btn-outline-secondary:hover {
    color: #ffffff;
    background-color: #64748b;
    border-color: #64748b;
}

/* Button Outline Danger */
.btn-outline-danger {
    color: #dc2626;
    background-color: #ffffff;
    border-color: #fecaca;
}

.btn-outline-danger:hover {
    color: #ffffff;
    background-color: #dc2626;
    border-color: #dc2626;
}

/* Button Warning (Orange Gradient) */
.btn-warning {
    color: #ffffff;
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    border-color: #f59e0b;
    box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);
    font-weight: 700;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
    border-color: #d97706;
    box-shadow: 0 6px 12px rgba(245, 158, 11, 0.5);
}

/* Button Remove Image */
.btn-remove-existing-image {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(220, 38, 38, 0.3);
}

.btn-remove-existing-image:hover {
    background-color: #dc2626 !important;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.4);
}

/* Icon di dalam button */
.btn svg,
.btn i {
    flex-shrink: 0;
}

/* Hover effect untuk existing image */
.existing-image-item {
    transition: all 0.3s ease;
}

.existing-image-item:hover {
    transform: translateY(-4px);
}

.existing-image-item img {
    transition: all 0.3s ease;
}

.existing-image-item:hover img {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Overlay button container */
.existing-image-item .absolute.inset-0 {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom hover effects for image containers */
.image-item .aspect-square:hover {
    background-color: rgba(0, 0, 0, 0.7);
    border-color: #3b82f6;
}

.image-item .aspect-square:hover img {
    filter: brightness(0.5);
}

.image-item .aspect-square .btn-edit-image,
.image-item .aspect-square .btn-delete-image,
.image-item .aspect-square .btn-remove-existing-image {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-item .aspect-square:hover .btn-edit-image,
.image-item .aspect-square:hover .btn-delete-image,
.image-item .aspect-square:hover .btn-remove-existing-image {
    opacity: 1;
}
</style>

<?php $this->load->view('Template/footer'); ?>