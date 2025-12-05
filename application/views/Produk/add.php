<div class="content">
    <div class="flex items-center mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mr-auto">Tambah Produk</h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-100">
                <form action="<?= site_url('Produk/save') ?>" method="POST" enctype="multipart/form-data" id="produk-form">
                    <div class="mb-6">
                        <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">Kode Barang *</label>
                        <input
                            id="kode_barang"
                            name="kode_barang"
                            type="text"
                            class="form-control border border-gray-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            value="<?= $kode_barang ?>"
                        >
                        <small class="text-gray-500 text-xs mt-1 block">Masukkan kode barang unik</small>
                    </div>

                    <div class="mb-6">
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk *</label>
                        <input
                            id="nama_produk"
                            name="nama_produk"
                            type="text"
                            class="form-control border border-gray-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Masukkan nama produk"
                            required
                        >
                    </div>

                    <div class="mb-6">
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga *</label>
                        <input
                            id="harga"
                            name="harga"
                            type="text"
                            class="form-control border border-gray-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Masukkan harga"
                            oninput="formatHarga(this)"
                            required
                        >
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            class="form-control border border-gray-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none"
                            rows="4"
                            placeholder="Masukkan deskripsi produk"
                        ></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                        <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition duration-200">
                            <i data-feather="upload" class="w-10 h-10 text-gray-400 mx-auto mb-3"></i>
                            <p class="text-gray-700 font-medium mb-1">Klik untuk memilih gambar atau seret ke sini</p>
                            <p class="text-sm text-gray-500">Format: JPG, PNG, GIF, WebP (Max 2MB per gambar)</p>
                        </div>
                        <input
                            id="gambar"
                            name="gambar[]"
                            type="file"
                            class="hidden"
                            multiple
                            accept="image/*"
                        >
                        <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i data-feather="save" class="w-5 h-5 mr-2"></i>
                            Simpan
                        </button>
                        <a href="<?= site_url('Produk') ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i data-feather="x" class="w-5 h-5 mr-2"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatHarga(input) {
            let value = input.value.replace(/[^\d]/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }

        let selectedFiles = [];

        // Upload area click
        document.getElementById('upload-area').addEventListener('click', function() {
            document.getElementById('gambar').click();
        });

        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function highlight(e) {
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = Array.from(dt.files);
            selectedFiles = selectedFiles.concat(files);
            updateInputFiles();
            updatePreviews();
        }

        // Image selection
        document.getElementById('gambar').addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles = selectedFiles.concat(newFiles);
            updateInputFiles();
            updatePreviews();
        });

        function updateInputFiles() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            document.getElementById('gambar').files = dt.files;
        }

        function updatePreviews() {
            const previews = document.getElementById('image-previews');
            previews.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <div class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs cursor-pointer hover:bg-red-600 z-10" onclick="removePreview(${index})" title="Hapus gambar">×</div>
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-600 mt-1 truncate">${file.name}</p>
                        `;
                        previews.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removePreview(index) {
            selectedFiles.splice(index, 1);
            updateInputFiles();
            updatePreviews();
        }

        // Form submission with AJAX
        document.getElementById('produk-form').addEventListener('submit', function(e) {
            e.preventDefault();

            console.log('Form submission started');
            const formData = new FormData(this);

            // Log form data for debugging
            for (let [key, value] of formData.entries()) {
                if (key === 'gambar[]') {
                    console.log(key + ': ' + (value.name || 'no file'));
                } else {
                    console.log(key + ': ' + value);
                }
            }

            fetch('<?= site_url('Produk/save') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    console.log('Success: Product saved successfully');
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
                    console.error('Error from server:', data.message);
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
                console.error('Fetch error:', error);
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>