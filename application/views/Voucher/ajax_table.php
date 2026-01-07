<?php if ($voucher->num_rows() > 0): ?>
    <div class="intro-y w-full">
        <table class="table table-report -mt-2 w-full min-w-full">
            <thead>
                <tr>
                    <th class="whitespace-nowrap text-center" style="width: 50px;">NO</th>
                    <th class="whitespace-nowrap text-center" style="width: 70px;">GAMBAR</th>
                    <th class="whitespace-nowrap" style="width: 120px;">VOUCHER CODE</th>
                    <th class="whitespace-nowrap" style="width: 150px;">DESCRIPTION</th>
                    <th class="whitespace-nowrap text-center" style="width: 80px;">DISCOUNT</th>
                    <th class="whitespace-nowrap text-center" style="width: 90px;">START DATE</th>
                    <th class="whitespace-nowrap text-center" style="width: 90px;">END DATE</th>
                    <th class="whitespace-nowrap text-center" style="width: 70px;">STATUS</th>
                    <th class="text-center whitespace-nowrap" style="width: 100px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = $offset + 1;

                foreach ($voucher->result() as $row):
                ?>
                <tr class="intro-x" id="row-<?= $row->voucher_id ?>">
                    <td class="text-center"><?= $no++ ?></td>

                    <!-- GAMBAR -->
                    <td class="text-center">
                        <?php if ($row->voucher_gambar): ?>
                            <?php $image_url = $this->config->item('voucher_image_url') . $row->voucher_gambar; ?>
                            <img
                                src="<?= $image_url ?>"
                                alt="<?= htmlspecialchars($row->voucher_code) ?>"
                                class="w-12 h-12 object-cover rounded-lg mx-auto border-2 border-gray-200 shadow-sm hover:border-blue-500 transition-all cursor-pointer"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mx-auto\'><i data-feather=\'image\' class=\'w-5 h-5 text-gray-400\'></i></div>'; feather.replace();"
                                loading="lazy"
                                onclick="showImageModal('<?= $image_url ?>', '<?= htmlspecialchars($row->voucher_code) ?>')"
                                title="Klik untuk memperbesar"
                            >
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto border-2 border-gray-200">
                                <i data-feather="image" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- VOUCHER CODE -->
                    <td>
                        <div class="font-medium truncate text-theme-1" style="max-width: 110px;" title="<?= htmlspecialchars($row->voucher_code) ?>">
                            <?= htmlspecialchars($row->voucher_code) ?>
                        </div>
                    </td>

                    <!-- DESCRIPTION -->
                    <td>
                        <div class="text-gray-600 text-xs truncate" style="max-width: 140px;" title="<?= htmlspecialchars($row->description) ?>">
                            <?= $row->description ? htmlspecialchars($row->description) : '-' ?>
                        </div>
                    </td>

                    <!-- DISCOUNT -->
                    <td class="text-center">
                        <div class="text-theme-9 font-medium whitespace-nowrap">
                            <?= $row->discount_percent ?>%
                        </div>
                    </td>

                    <!-- START DATE -->
                    <td class="text-center">
                        <div class="text-gray-600 whitespace-nowrap">
                            <?= date('d/m/Y', strtotime($row->start_date)) ?>
                        </div>
                    </td>

                    <!-- END DATE -->
                    <td class="text-center">
                        <div class="text-gray-600 whitespace-nowrap">
                            <?= date('d/m/Y', strtotime($row->end_date)) ?>
                        </div>
                    </td>

                    <!-- STATUS -->
                    <td class="text-center">
                        <span class="px-2 py-1 rounded text-xs <?php echo $row->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?= ucfirst($row->status) ?>
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="table-report__action">
                        <div class="flex justify-center items-center gap-1">
                            <button
                                class="flex items-center px-2 py-1 text-xs detail-btn"
                                data-id="<?= $row->voucher_id ?>"
                                data-code="<?= htmlspecialchars($row->voucher_code) ?>"
                                data-description="<?= htmlspecialchars($row->description) ?>"
                                data-discount="<?= $row->discount_percent ?>"
                                data-start="<?= date('d/m/Y', strtotime($row->start_date)) ?>"
                                data-end="<?= date('d/m/Y', strtotime($row->end_date)) ?>"
                                data-max="<?= $row->max_usage ?>"
                                data-status="<?= $row->status ?>"
                                title="Detail">
                                <i data-feather="eye" class="w-3 h-3"></i>
                            </button>
                            <a
                                class="flex items-center px-2 py-1 text-xs"
                                href="<?= site_url('Voucher/edit/'.$row->voucher_id) ?>"
                                title="Edit">
                                <i data-feather="edit" class="w-3 h-3"></i>
                            </a>
                            <button
                                class="flex items-center px-2 py-1 text-xs text-red-600 delete-btn"
                                data-id="<?= $row->voucher_id ?>"
                                data-name="<?= htmlspecialchars($row->voucher_code) ?>"
                                title="Delete">
                                <i data-feather="trash-2" class="w-3 h-3"></i>
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
            <h3 class="text-lg font-medium mt-5">Tidak ada voucher ditemukan</h3>
            <p class="text-gray-600 mt-2">Belum ada voucher yang ditambahkan atau tidak ditemukan dengan kata kunci tersebut</p>
            <a href="<?= site_url('Voucher/add') ?>" class="button inline-block bg-theme-1 text-white mt-5">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Tambah Voucher
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
            <span class="font-medium"><?= number_format($total_records, 0, ',', '.') ?></span> voucher
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

// Function: Show Image Modal
function showImageModal(imageUrl, voucherCode) {
    Swal.fire({
        title: `<strong>${voucherCode}</strong>`,
        imageUrl: imageUrl,
        imageAlt: voucherCode,
        showCloseButton: true,
        showConfirmButton: false,
        width: '600px',
        customClass: {
            popup: 'rounded-lg'
        }
    });
}

// Detail button handler
document.querySelectorAll('.detail-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const voucherId = this.getAttribute('data-id');
        const voucherCode = this.getAttribute('data-code');
        const description = this.getAttribute('data-description');
        const discountPercent = this.getAttribute('data-discount');
        const startDate = this.getAttribute('data-start');
        const endDate = this.getAttribute('data-end');
        const maxUsage = this.getAttribute('data-max');
        const status = this.getAttribute('data-status');

        Swal.fire({
            title: '<strong>' + voucherCode + '</strong>',
            icon: 'info',
            html: `
                <div class="text-left">
                    <table class="w-full">
                        <tr>
                            <td class="py-2 font-medium">Voucher Code</td>
                            <td class="py-2">: ${voucherCode}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Description</td>
                            <td class="py-2">: ${description || '-'}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Discount Percent</td>
                            <td class="py-2 font-bold text-green-600">: ${discountPercent}%</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Start Date</td>
                            <td class="py-2">: ${startDate}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">End Date</td>
                            <td class="py-2">: ${endDate}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Max Usage</td>
                            <td class="py-2">: ${maxUsage}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Status</td>
                            <td class="py-2">: <span class="px-2 py-1 rounded text-xs ${status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${status}</span></td>
                        </tr>
                    </table>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Edit Voucher',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#1e40af',
            width: '700px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url("Voucher/edit/") ?>' + voucherId;
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
            html: `Voucher <strong>${name}</strong> akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('<?= site_url("Voucher/delete/") ?>' + id, {
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
                    text: 'Voucher berhasil dihapus.',
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

/* Compact table styles */
.table-report th {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 0.25rem;
}

.table-report td {
    font-size: 0.8rem;
    padding: 0.5rem 0.25rem;
}

/* Gallery grid responsive */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    /* Make table more compact on small screens */
    .table-report th,
    .table-report td {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
    }

    /* Hide less important columns on very small screens */
    .table-report th:nth-child(4),
    .table-report td:nth-child(4) {
        display: none;
    }
}

@media (max-width: 480px) {
    .table-report th,
    .table-report td {
        padding: 0.25rem;
        font-size: 0.75rem;
    }

    /* Hide more columns on very small screens */
    .table-report th:nth-child(5),
    .table-report td:nth-child(5),
    .table-report th:nth-child(6),
    .table-report td:nth-child(6) {
        display: none;
    }
}
</style>