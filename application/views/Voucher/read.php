
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="percent" class="w-6 h-6 inline-block mr-2"></i>Voucher Discount</h1>
                <p>Management Data Voucher Discount</p>
            </div>
            <div class="header-section mt-5">
                <a href="<?= site_url('Voucher/add') ?>" class="button text-white bg-theme-1 shadow-md mr-2 hover:bg-theme-2 transition-all">
                Tambah Voucher
            </a>
            </div>
        </header>

<!-- BEGIN: Content -->
<div class="content mt-5">

    <!-- BEGIN: Alert Messages -->
    <?php
    $sukses_msg = $this->session->flashdata('sukses');
    $error_msg = $this->session->flashdata('error');
    if($sukses_msg):
    ?>
    <div id="success-alert" class="bg-green-500 text-white p-4 rounded-lg shadow-lg mb-4 flex items-center justify-between animate-slideDown">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium text-lg"><?= $sukses_msg ?></span>
        </div>
        <button class="text-green-200 hover:text-white transition-colors" onclick="closeAlert('success-alert')">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>
        function closeAlert(id) {
            const alert = document.getElementById(id);
            if (alert) {
                alert.style.animation = 'slideUp 0.3s ease-out';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }

        setTimeout(function() {
            closeAlert('success-alert');
        }, 3000);
    </script>
    <?php
    $this->session->unset_userdata('sukses');
    endif;
    ?>

    <?php if($error_msg): ?>
    <div id="error-alert" class="bg-red-500 text-white p-4 rounded-lg shadow-lg mb-4 flex items-center justify-between animate-slideDown">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 011.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium text-lg"><?= $error_msg ?></span>
        </div>
        <button class="text-red-200 hover:text-white transition-colors" onclick="closeAlert('error-alert')">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <?php
    $this->session->unset_userdata('error');
    endif;
    ?>
    <!-- END: Alert Messages -->

    <!-- BEGIN: Search Box -->
    <div class="intro-y box p-3 sm:p-5 mt-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <div class="xl:flex sm:mr-auto w-full sm:w-auto">
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0 w-full sm:w-auto">
                    <div class="relative text-gray-700 dark:text-gray-300 w-full sm:w-48 md:w-56 lg:w-64 xl:w-72">
                        <input
                            type="text"
                            id="search-input"
                            class="input w-full box pr-10 placeholder-theme-13 text-sm sm:text-base"
                            placeholder="Cari voucher..."
                            autocomplete="off"
                        >
                        <i data-feather="search" class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" id="search-icon"></i>
                        <div class="hidden w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" id="search-spinner">
                            <svg class="animate-spin h-4 w-4 text-theme-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Search Box -->

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 mt-5" style="position: relative;">
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="hidden absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-md" style="min-height: 400px;">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-12 w-12 text-theme-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-3 text-gray-600 text-sm font-medium">Memuat data...</p>
            </div>
        </div>

        <!-- Table Container with Responsive Wrapper -->
        <div class="w-full overflow-x-auto overflow-y-hidden">
            <div id="table-container" class="min-w-full"></div>
        </div>
    </div>
    <!-- END: Data List -->

    <!-- BEGIN: Pagination -->
    <div id="pagination-container" class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center sm:justify-between mt-3 w-full"></div>
    <!-- END: Pagination -->
</div>
<!-- END: Content -->
<script>
let searchTimeout;
let currentSearch = '';
let currentPage = 0;

// Load data pertama kali
document.addEventListener('DOMContentLoaded', function() {
    loadData(0, '');

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Search input handler
document.getElementById('search-input').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    currentSearch = this.value;

    // Show spinner
    document.getElementById('search-icon').classList.add('hidden');
    document.getElementById('search-spinner').classList.remove('hidden');

    searchTimeout = setTimeout(function() {
        loadData(0, currentSearch);
    }, 500);
});

// Fungsi load data
function loadData(page = 0, search = '') {
    currentPage = page;
    const url = '<?= site_url('Voucher/ajax_search') ?>?search=' + encodeURIComponent(search) + '&page=' + page;

    const loadingOverlay = document.getElementById('loading-overlay');
    const tableContainer = document.getElementById('table-container');
    const paginationContainer = document.getElementById('pagination-container');

    // Show loading
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }

    fetch(url)
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

            // Extract table
            const table = doc.querySelector('table');
            const emptyState = doc.querySelector('#empty-state');

            if (table) {
                tableContainer.innerHTML = table.outerHTML;
            } else if (emptyState) {
                tableContainer.innerHTML = emptyState.outerHTML;
            }

            // Extract pagination
            const paginationInfo = doc.querySelector('.pagination-info-wrapper');
            if (paginationInfo) {
                paginationContainer.innerHTML = paginationInfo.outerHTML;
            } else {
                paginationContainer.innerHTML = '';
            }

            // Extract modal (PENTING!)
            const modal = doc.querySelector('#detail-modal');
            if (modal) {
                // Remove old modal if exists
                const oldModal = document.getElementById('detail-modal');
                if (oldModal) {
                    oldModal.remove();
                }
                // Append new modal to body
                document.body.appendChild(modal);
                console.log('Modal appended to body'); // Debug
            }

            // Hide loading
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }

            // Hide search spinner
            document.getElementById('search-spinner').classList.add('hidden');
            document.getElementById('search-icon').classList.remove('hidden');

            // PENTING: Attach events SETELAH DOM di-update
            setTimeout(() => {
                attachPaginationEvents();
                attachDetailEvents(); // TAMBAHKAN INI!
                attachDeleteEvents(); // TAMBAHKAN INI!

                // Re-init feather
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            document.getElementById('search-spinner').classList.add('hidden');
            document.getElementById('search-icon').classList.remove('hidden');

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat data. Silakan refresh halaman.',
            });
        });
}

// Attach pagination events
function attachPaginationEvents() {
    console.log('Attaching pagination events...'); // Debug

    const paginationLinks = document.querySelectorAll('.pagination .page-link[data-page]');
    console.log('Found pagination links:', paginationLinks.length); // Debug

    paginationLinks.forEach((link, index) => {
        const newLink = link.cloneNode(true);
        link.parentNode.replaceChild(newLink, link);

        newLink.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const page = parseInt(this.getAttribute('data-page'));
            console.log('Clicked page:', page); // Debug

            if (!isNaN(page)) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(() => {
                    loadData(page, currentSearch);
                }, 300);
            }
        });
    });

    const paginationWrapper = document.querySelector('.pagination-wrapper-custom');
    if (paginationWrapper) {
        console.log('Pagination wrapper found'); // Debug

        const newWrapper = paginationWrapper.cloneNode(true);
        paginationWrapper.parentNode.replaceChild(newWrapper, paginationWrapper);

        newWrapper.addEventListener('click', function(e) {
            const target = e.target.closest('.page-link[data-page]');

            if (target) {
                e.preventDefault();
                e.stopPropagation();

                const page = parseInt(target.getAttribute('data-page'));
                console.log('Delegation clicked page:', page); // Debug

                if (!isNaN(page)) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    setTimeout(() => {
                        loadData(page, currentSearch);
                    }, 300);
                }
            }
        });
    }
}

// ========== FUNCTION BARU: Attach Detail Events ==========
function attachDetailEvents() {
    console.log('Attaching detail events...'); // Debug

    const detailButtons = document.querySelectorAll('.detail-btn');
    console.log('Found detail buttons:', detailButtons.length); // Debug

    detailButtons.forEach((btn, index) => {
        // Remove old listeners dengan clone
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);

        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Detail button clicked!'); // Debug

            const voucherId = this.getAttribute('data-id');
            const voucherCode = this.getAttribute('data-code');
            const description = this.getAttribute('data-description');
            const discountPercent = this.getAttribute('data-discount');
            const startDate = this.getAttribute('data-start');
            const endDate = this.getAttribute('data-end');
            const maxUsage = this.getAttribute('data-max');
            const status = this.getAttribute('data-status');

            console.log('Voucher data:', { voucherId, voucherCode, description, discountPercent, startDate, endDate, maxUsage, status }); // Debug

            // Show modal dengan SweetAlert2 (PALING AMAN)
            Swal.fire({
                title: '<strong>' + voucherCode + '</strong>',
                icon: 'info',
                html: `
                    <div class="text-left p-4">
                        <table class="w-full table-auto">
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700 w-1/3">Voucher Code</td>
                                <td class="py-3 text-gray-900">: ${voucherCode}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700">Description</td>
                                <td class="py-3 text-gray-900">: ${description || '-'}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700">Discount Percent</td>
                                <td class="py-3 font-bold text-green-600 text-lg">: ${discountPercent}%</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700">Start Date</td>
                                <td class="py-3 text-gray-900">: ${startDate}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700">End Date</td>
                                <td class="py-3 text-gray-900">: ${endDate}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 font-semibold text-gray-700">Max Usage</td>
                                <td class="py-3 text-gray-900">: ${maxUsage}</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold text-gray-700">Status</td>
                                <td class="py-3 text-gray-900">: <span class="px-2 py-1 rounded text-xs ${status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${status}</span></td>
                            </tr>
                        </table>
                    </div>
                `,
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Tutup',
                cancelButtonColor: '#6b7280',
                width: '600px',
                customClass: {
                    popup: 'rounded-lg',
                    cancelButton: 'btn btn-secondary'
                }
            });
        });
    });
}

// ========== FUNCTION BARU: Attach Delete Events ==========
function attachDeleteEvents() {
    console.log('Attaching delete events...'); // Debug

    const deleteButtons = document.querySelectorAll('.delete-btn');
    console.log('Found delete buttons:', deleteButtons.length); // Debug

    deleteButtons.forEach((btn, index) => {
        // Remove old listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);

        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Delete button clicked!'); // Debug

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
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            confirmButton: 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium'
                        }
                    }).then(() => {
                        loadData(currentPage, currentSearch);
                    });
                }
            });
        });
    });
}
</script>

<style>

/* Modal Animation */
.modal.show {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Modal backdrop blur */
.modal-backdrop {
    backdrop-filter: blur(4px);
}

/* Hover effect untuk info boxes di modal */
.modal-body .border:hover {
    border-color: #1e40af;
    transform: translateY(-2px);
}
/* Loading overlay animation */
#loading-overlay {
    backdrop-filter: blur(3px);
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Pagination styling PENTING! */
.pagination-wrapper-custom {
    display: inline-block;
}

.pagination-wrapper-custom .pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination-wrapper-custom .pagination li {
    display: inline-block;
}

.pagination-wrapper-custom .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 14px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    cursor: pointer !important; /* PENTING */
    transition: all 0.2s;
    text-decoration: none;
    pointer-events: auto !important; /* PENTING */
    user-select: none;
}

.pagination-wrapper-custom .page-link:hover:not(.active) {
    background: #1e40af;
    color: white;
    border-color: #1e40af;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(30, 64, 175, 0.3);
}

.pagination-wrapper-custom .page-link.active {
    background: #1e40af;
    color: white;
    border-color: #1e40af;
    cursor: default !important;
    pointer-events: none;
}

/* Force clickable */
.pagination-wrapper-custom a,
.pagination-wrapper-custom a.page-link {
    pointer-events: auto !important;
    cursor: pointer !important;
}

/* Table animations */
.table-report {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Alert animations */
.animate-slideDown {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

/* Debug - tambahkan border merah untuk cek area klik */
.pagination-wrapper-custom .page-link:hover {
    outline: 2px solid red; /* Hapus ini setelah berhasil */
}

/* Responsive Table Container */
#table-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

#table-container table {
    min-width: 700px; /* Reduced minimum width to fit better */
    width: 100%;
    table-layout: fixed; /* Fixed table layout for better control */
    font-size: 0.875rem; /* Smaller font by default */
}

#table-container th,
#table-container td {
    padding: 0.5rem 0.25rem; /* Reduced padding */
    vertical-align: middle;
}

/* Responsive adjustments for different screen sizes */
@media (max-width: 640px) {
    .intro-y.box.p-3 {
        padding: 0.75rem;
    }

    #table-container table {
        font-size: 0.875rem;
    }

    #table-container th,
    #table-container td {
        padding: 0.5rem;
    }
}

@media (max-width: 480px) {
    .intro-y.box.p-3 {
        padding: 0.5rem;
    }

    #table-container table {
        font-size: 0.75rem;
    }

    #table-container th,
    #table-container td {
        padding: 0.25rem;
    }
}

/* Ensure search input doesn't overflow */
#search-input {
    max-width: 100%;
    box-sizing: border-box;
}

/* Responsive search container */
@media (max-width: 640px) {
    .xl\\:flex.sm\\:mr-auto {
        margin-right: 0;
        width: 100%;
    }

    .sm\\:flex.items-center.sm\\:mr-4 {
        margin-right: 0;
        width: 100%;
    }
}
</style>

