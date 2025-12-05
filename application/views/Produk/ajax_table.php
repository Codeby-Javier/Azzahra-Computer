<?php if ($produk->num_rows() > 0): ?>
    <div class="intro-y overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap text-center" width="80">NO</th>
                    <th class="whitespace-nowrap text-center" width="100">GAMBAR</th>
                    <th class="whitespace-nowrap">KODE BARANG</th>
                    <th class="whitespace-nowrap">NAMA PRODUK</th>
                    <th class="whitespace-nowrap text-right" width="150">HARGA</th>
                    <th class="whitespace-nowrap">DESKRIPSI</th>
                    <th class="text-center whitespace-nowrap" width="120">AKSI</th>
                </tr>
            </thead>
            <tbody>                
                <?php 
                $no = $offset + 1;
                $laravel_image_url = $this->config->item('laravel_image_url');
                
                foreach ($produk->result() as $row): 
                    // Handle multiple images (separated by comma)
                    $gambarArray = array_filter(array_map('trim', explode(',', $row->gambar)));
                    $firstImage = !empty($gambarArray) ? $gambarArray[0] : null;
                ?>               
                <tr class="intro-x" id="row-<?= $row->kode_barang ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    
                    <!-- GAMBAR - TAMPILKAN GAMBAR PERTAMA -->
                    <td class="text-center">
                        <?php if ($firstImage): ?>
                            <img 
                                src="<?= $laravel_image_url . $firstImage ?>" 
                                alt="<?= htmlspecialchars($row->nama_produk) ?>" 
                                class="w-14 h-14 object-cover rounded-lg mx-auto border-2 border-gray-200 shadow-sm hover:border-blue-500 transition-all cursor-pointer"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center mx-auto\'><i data-feather=\'image\' class=\'w-6 h-6 text-gray-400\'></i></div>'; feather.replace();"
                                loading="lazy"
                                onclick="showImageGallery('<?= htmlspecialchars(json_encode($gambarArray)) ?>', '<?= htmlspecialchars($row->nama_produk) ?>')"
                                title="Klik untuk lihat semua gambar (<?= count($gambarArray) ?> foto)"
                            >
                            <?php if (count($gambarArray) > 1): ?>
                                <div class="text-xs text-gray-500 mt-1">+<?= count($gambarArray) - 1 ?> foto</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto border-2 border-gray-200">
                                <i data-feather="image" class="w-7 h-7 text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    
                    <!-- KODE BARANG -->
                    <td>
                        <div class="font-medium whitespace-nowrap text-theme-1">
                            <?= htmlspecialchars($row->kode_barang) ?>
                        </div>
                    </td>
                    
                    <!-- NAMA PRODUK -->
                    <td>
                        <div class="font-medium whitespace-nowrap">
                            <?= htmlspecialchars($row->nama_produk) ?>
                        </div>
                    </td>
                    
                    <!-- HARGA -->
                    <td class="text-right">
                        <div class="text-theme-9 font-medium whitespace-nowrap">
                            Rp <?= number_format($row->harga, 0, ',', '.') ?>
                        </div>
                    </td>
                    
                    <!-- DESKRIPSI -->
                    <td>
                        <div class="text-gray-600 text-xs max-w-xs truncate" title="<?= htmlspecialchars($row->deskripsi) ?>">
                            <?= $row->deskripsi ? htmlspecialchars($row->deskripsi) : '-' ?>
                        </div>
                    </td>
                    
                    <!-- AKSI -->
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <button 
                                class="flex items-center mr-3 detail-btn" 
                                data-kode="<?= $row->kode_barang ?>"
                                data-nama="<?= htmlspecialchars($row->nama_produk) ?>"
                                data-harga="<?= $row->harga ?>"
                                data-deskripsi="<?= htmlspecialchars($row->deskripsi) ?>"
                                data-gambar='<?= htmlspecialchars(json_encode($gambarArray)) ?>'>
                                <i data-feather="eye" class="w-4 h-4 mr-1"></i> Detail
                            </button>
                            <a
                                class="flex items-center mr-3"
                                href="<?= site_url('Produk/edit/'.rawurlencode($row->kode_barang)) ?>">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <button 
                                class="flex items-center text-theme-6 delete-btn" 
                                data-id="<?= $row->kode_barang ?>" 
                                data-name="<?= htmlspecialchars($row->nama_produk) ?>">
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="intro-y box px-5 py-10" id="empty-state">
        <div class="text-center">
            <div class="flex justify-center">
                <i data-feather="inbox" class="w-16 h-16 text-theme-13"></i>
            </div>
            <h3 class="text-lg font-medium mt-5">Tidak ada produk ditemukan</h3>
            <p class="text-gray-600 mt-2">Belum ada produk yang ditambahkan atau tidak ditemukan dengan kata kunci tersebut</p>
            <a href="<?= site_url('Produk/add') ?>" class="button inline-block bg-theme-1 text-white mt-5">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Tambah Produk
            </a>
        </div>
    </div>
<?php endif; ?>
<!-- Pagination Info -->
<?php if ($pagination && $total_records > 0): ?>
<div class="pagination-info-wrapper intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-3">
    <div class="w-full sm:w-auto sm:mr-auto">
        <div class="text-gray-600">
            Menampilkan 
            <span class="font-medium"><?= $offset + 1 ?></span> - 
            <span class="font-medium"><?= min($offset + $per_page, $total_records) ?></span> dari 
            <span class="font-medium"><?= number_format($total_records, 0, ',', '.') ?></span> produk
        </div>
    </div>
    <div class="w-full sm:w-auto mt-3 sm:mt-0 ml-5">
        <div class="pagination-wrapper-custom">
            <?= $pagination ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
console.log('Script loaded'); // Debug

// Config Laravel Image URL
const laravelImageUrl = '<?= $this->config->item('laravel_image_url') ?>';

// Function: Show Image Gallery
function showImageGallery(imagesJson, productName) {
    try {
        const images = typeof imagesJson === 'string' ? JSON.parse(imagesJson) : imagesJson;
        
        if (!images || images.length === 0) {
            Swal.fire('Tidak ada gambar', 'Produk ini belum memiliki gambar', 'info');
            return;
        }

        // Build image gallery HTML
        let galleryHtml = '<div class="grid grid-cols-2 gap-4">';
        
        images.forEach((img, index) => {
            const fullUrl = laravelImageUrl + img;
            galleryHtml += `
                <div class="relative group">
                    <img 
                        src="${fullUrl}" 
                        alt="${productName} - ${index + 1}"
                        class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-all cursor-pointer"
                        onclick="window.open('${fullUrl}', '_blank')"
                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5HYW1iYXIgVGlkYWsgRGl0ZW11a2FuPC90ZXh0Pjwvc3ZnPg=='"
                    >
                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                        ${index + 1}/${images.length}
                    </div>
                </div>
            `;
        });
        
        galleryHtml += '</div>';
        galleryHtml += '<p class="text-xs text-gray-500 mt-4 text-center">Klik gambar untuk memperbesar</p>';

        Swal.fire({
            title: `<strong>${productName}</strong>`,
            html: galleryHtml,
            width: '800px',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-lg'
            }
        });

    } catch (e) {
        console.error('Error parsing images:', e);
        Swal.fire('Error', 'Gagal memuat gambar produk', 'error');
    }
}

// Detail button handler
document.querySelectorAll('.detail-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const kodeBarang = this.getAttribute('data-kode');
        const namaProduk = this.getAttribute('data-nama');
        const harga = this.getAttribute('data-harga');
        const deskripsi = this.getAttribute('data-deskripsi');
        const gambarJson = this.getAttribute('data-gambar');
        
        let gambarHtml = '';
        try {
            const gambarArray = JSON.parse(gambarJson);
            if (gambarArray && gambarArray.length > 0) {
                gambarHtml = '<div class="mb-4"><strong>Gambar Produk:</strong><br>';
                gambarHtml += '<div class="flex gap-2 mt-2 flex-wrap">';
                gambarArray.forEach((img, index) => {
                    gambarHtml += `
                        <img 
                            src="${laravelImageUrl}${img}" 
                            alt="${namaProduk} - ${index + 1}"
                            class="w-20 h-20 object-cover rounded border border-gray-300 cursor-pointer hover:border-blue-500"
                            onclick="window.open('${laravelImageUrl}${img}', '_blank')"
                            onerror="this.style.display='none'"
                        >
                    `;
                });
                gambarHtml += '</div></div>';
            }
        } catch (e) {
            console.error('Error parsing gambar:', e);
        }
        
        Swal.fire({
            title: '<strong>' + namaProduk + '</strong>',
            icon: 'info',
            html: `
                <div class="text-left">
                    ${gambarHtml}
                    <table class="w-full">
                        <tr>
                            <td class="py-2 font-medium">Kode Barang</td>
                            <td class="py-2">: ${kodeBarang}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Nama Produk</td>
                            <td class="py-2">: ${namaProduk}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Harga</td>
                            <td class="py-2 font-bold text-green-600">: Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium align-top">Deskripsi</td>
                            <td class="py-2">: ${deskripsi || '-'}</td>
                        </tr>
                    </table>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Edit Produk',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#1e40af',
            width: '700px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url("Produk/edit/") ?>' + encodeURIComponent(kodeBarang);
            }
        });
    });
});

// Delete button handler
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Produk <strong>${name}</strong> akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('<?= site_url("Produk/delete/") ?>' + encodeURIComponent(id), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: 'Produk berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                setTimeout(() => {
                    loadData(currentPage, currentSearch);
                }, 1000);
            }
        });
    });
});

// Re-init feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>

<style>
/* Hover effect untuk gambar */
.table-report tbody tr img {
    transition: all 0.3s ease;
}

.table-report tbody tr:hover img {
    transform: scale(1.05);
}

/* Gallery grid responsive */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}
</style>