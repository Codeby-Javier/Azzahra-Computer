<?php $this->load->view('Template/header'); ?>
<div class="content-area">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
	<!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>
     <i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Quick Service</h1>
                <p>Manage quick service data</p>
            </div>
            <div class="header-actions">
                <!--<div class="search-input-wrapper">-->
                <!--    <i data-feather="search" class="search-icon"></i>-->
                <!--    <input type="text" class="search-input" placeholder="Search...">-->
                <!--</div>-->
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>
	<div class="intro-y flex flex-col sm:flex-row items-center ">
	       <h2 class="text-lg font-medium mr-auto">
	           Data Quick Service
	       </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        	<a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#add-new-costom">
        		Buat Transaksi
        	</a>
        </div>
    </div>
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
    			          <a href="<?= site_url('QuickService/cos_baru')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
    			          	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
    			          </a>
    			          <a href="<?= site_url('QuickService/cos_lunas')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
    			          	<i class="w-4 h-4 mr-2" data-feather="users"></i> Customer
    			          </a>
    			      </div>
    		</div>
    	</div>
    	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
    		<div class="intro-y datatable-wrapper box p-5 mt-5">
    			<table class="table table-report table-report--bordered display datatable w-full">
    				<thead>
    			   			<tr>
    			   				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    			   				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
    			                   <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
    			                   <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
    			                   <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
    			                   <th class="border-b-2 whitespace-no-wrap">TANGGAL</th>
    			                   <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			   			</tr>
    			   		</thead>
    			   		<tbody>
    			   			<?php
    			    			foreach ($trans->result_array() as $row) :?>
    			    				<tr>
    				    				<td class="text-center border-b"><?= ++$no; ?></td>
    				    				<td class="text-center border-b"><?= $row['cos_kode']; ?></td>
    				    				<td class="border-b">
    				    					<div class="font-medium whitespace-no-wrap">
    				                    		<?= $row['cos_nama']?>
    				                    	</div>
    				                        <div class="text-gray-600 text-xs whitespace-no-wrap">
    				                        	STATUS : <?= $row['trans_status']?>
    				                        </div>
    				    				</td>
    				    				<td class="border-b"><?= $row['cos_alamat']; ?></td>
    				    				<td class="text-center border-b">
    				    					<?= $row['cos_hp']; ?>
    				    				</td>
    				    				<td class="border-b">
    				    					<div class="font-medium whitespace-no-wrap">
    				                    		 <?php echo date('d-m-Y',strtotime($row['cos_tanggal'])) ?>
    				                    	</div>
    				                        <div class="text-gray-600 text-xs whitespace-no-wrap">
    				                        	 JAM : <?= $row['cos_jam']?>
    				                        </div>
    				    				</td>
    				    				<td class="text-center border-b">
    				    					<a href="<?= site_url('Service/cos_histori/'.$row['trans_kode'])?>" >
    				    							<button class="button px-2 mr-1 mb-2 bg-theme-1 text-white">
    			                                        <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="hard-drive" class="w-4 h-4"></i> </span>
    			                                    </button>
    				    					</a>
    				    				</td>
    				    			</tr>
    			    			<?php endforeach; ?>
    			   		</tbody>
    			</table>
    		</div>
    	</div>
    	
    </div>
    
</div>

<!-- modal tambah cotomer-->
<div class="modal flex items-center justify-center" id="add-new-costom" style="z-index: 1050;">
	<div class="modal__content modal__content--xl p-10 intro-y box sm:py-15">
		<div class="nav-tabs wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10 ">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1 active" data-toggle="tab" data-target="#custom">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Customer</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#unit">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Unit</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#kelket">3</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan dan Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form id="transForm" method="post" action="<?= site_url('QuickService/save_trans')?>" onsubmit="return submitTransForm(this);">
        	<div class="tab-content">
	        	<div class="tab-content__pane active" id="custom">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Customer</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Nama</div>
			                    <input type="text" class="input w-full border flex-1" name="nama"  required oninvalid="this.setCustomValidity('Nama customer tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan nama customer">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No Tlep</div>
			                    <input type="number" class="input w-full border flex-1" name="tlp" required oninvalid="this.setCustomValidity('No tlep customer tidak boleh kosong ?')" oninput="setCustomValidity('')" placeholder="Masukan no tlep customer">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Alamat</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"></textarea>
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Tanggal Lahir</div>
			                    <input type="date" class="input w-full border flex-1" name="cos_tgl_lahir" required oninvalid="this.setCustomValidity('Tanggal lahir tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			            </div>
			        </div>
	        	</div>
	        	<div class="tab-content__pane" id="unit">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Unit</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			            	<div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Status </div>
			                    <select class="input w-full border flex-1" name="status" required oninvalid="this.setCustomValidity('Setatus unit tidak boleh kosong ?')" oninput="setCustomValidity('')">
						             <option value="">-</option>
						             <option value="CID">CID</option>
						             <option value="IW">IW</option>
						             <option value="OOW">OOW</option>
						         </select>
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Device</div>
			                    <input type="text" class="input w-full border flex-1" name="device" required oninvalid="this.setCustomValidity('Device tidak boleh kosong ?')" oninput="setCustomValidity('')" placeholder="Masukan device">
			                </div>
				            <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Merk </div>
			                    <input type="text" class="input w-full border flex-1" name="type"  required oninvalid="this.setCustomValidity('Type unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan type unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Model </div>
			                    <input type="text" class="input w-full border flex-1" name="model"  required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No seri </div>
			                    <input type="text" class="input w-full border flex-1" name="seri"  required oninvalid="this.setCustomValidity('No seri unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Tipe Password</div>
			                    <div class="flex items-center space-x-4">
			                        <label class="flex items-center">
			                            <input type="radio" name="pswd_type" value="text" checked class="mr-2">
			                            Text
			                        </label>
			                        <label class="flex items-center">
			                            <input type="radio" name="pswd_type" value="pattern_desc" class="mr-2">
			                            Pola (Deskripsi)
			                        </label>
			                        <label class="flex items-center">
			                            <input type="radio" name="pswd_type" value="pattern_canvas" class="mr-2">
			                            Pola (Canvas)
			                        </label>
			                    </div>
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6 pswd_text" id="pswd_text">
			                    <div class="mb-2">Password Text</div>
			                    <input type="text" class="input w-full border flex-1" name="pswd" placeholder="Masukan password text">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6 pswd_pattern_desc" id="pswd_pattern_desc" style="display: none;">
			                    <div class="mb-2">Deskripsi Pola Password</div>
			                    <textarea class="input w-full border flex-1" name="pswd_desc" placeholder="Deskripsikan pola password, misalnya: L ke kanan bawah"></textarea>
			                </div>
			                <div class="intro-y col-span-12 pswd_pattern_canvas" id="pswd_pattern_canvas" style="display: none;">
			                    <div class="mb-2">Gambar Pola Password</div>
			                    <canvas id="patternCanvas" width="300" height="200" class="border border-gray-300"></canvas>
			                    <div class="mt-2">
			                        <button type="button" id="clearCanvas" class="button bg-gray-500 text-white mr-2">Clear</button>
			                        <button type="button" id="saveCanvas" class="button bg-blue-500 text-white">Simpan Pola</button>
			                    </div>
			                    <input type="hidden" name="pswd_canvas" id="pswd_canvas">
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Asesoris</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"></textarea>
			                </div>
				        </div>
			        </div>
	        	</div>
	        	<div class="tab-content__pane" id="kelket">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Keluhan dan Keterangan</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Keluhan</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="keluhan" required oninvalid="this.setCustomValidity('Keluhan tidak boleh kosong ?')" oninput="setCustomValidity('')"></textarea>
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Keterangan</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="ket" required oninvalid="this.setCustomValidity('Keterangan tidak boleh kosong ?')" oninput="setCustomValidity('')"></textarea>
			                </div>
			                <div class="intro-y col-span-12">
			                    <label class="flex items-center">
			                        <input type="checkbox" name="is_quick_service" value="1" class="input border mr-2" checked>
			                        <span class="text-sm">Quick Service </span>
			                    </label>
			                </div>
			            </div>
			        </div>
			        <div class="px-5 py-3 text-right border-t border-gray-200">
			            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
			            <button type="submit" class="button w-20 bg-theme-1 text-white">Simpan</button>
			        </div>
	        	</div>
	        </div>
        </form>
 </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
	   // Initialize Feather Icons
	   if (typeof feather !== 'undefined') {
	       feather.replace();
	   }

	   // Toggle Sidebar
	   function toggleSidebar() {
	       const sidebar = document.getElementById('sidebar');
	       sidebar.classList.toggle('collapsed');
	       localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
	   }

	   // Toggle Mobile Sidebar
	   function toggleMobileSidebar() {
	       const sidebar = document.getElementById('sidebar');
	       const overlay = document.getElementById('sidebarOverlay');
	       sidebar.classList.toggle('mobile-active');
	       overlay.classList.toggle('active');
	   }

	   // Remember sidebar state
	   window.addEventListener('DOMContentLoaded', () => {
	       const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
	       if (isCollapsed && window.innerWidth > 1024) {
	           document.getElementById('sidebar').classList.add('collapsed');
	       }
	   });

	   // Initialize modal tabs when modal is shown
	   $('#add-new-costom').on('shown.bs.modal', function () {
	       // Reset all tabs and show first tab
	       $('.tab-content__pane').removeClass('active');
	       $('.nav-tabs a').removeClass('active');
	       $('#custom').addClass('active');
	       $('.nav-tabs a[href="#custom"]').addClass('active');
	       // Reset password fields
	       setTimeout(function() {
	           togglePasswordFields();
	       }, 100);
	   });

	   // Reset modal when hidden
	   $('#add-new-costom').on('hidden.bs.modal', function () {
	       // Reset to first tab for next opening
	       $('.tab-content__pane').removeClass('active');
	       $('.nav-tabs a').removeClass('active');
	       $('#custom').addClass('active');
	       $('.nav-tabs a[href="#custom"]').addClass('active');

	       // Reset form
	       $('#add-new-costom form')[0].reset();
	       // Reset canvas
	       clearCanvas();
	       // Reset password fields to default (text)
	       $('input[name="pswd_type"][value="text"]').prop('checked', true);
	       togglePasswordFields();
	   });

	   // Submit form via AJAX
	   function submitTransForm(form) {
	       var formData = new FormData(form);
	       fetch(form.action, {
	           method: 'POST',
	           headers: {
	               'X-Requested-With': 'XMLHttpRequest'
	           },
	           body: formData
	       })
	       .then(response => response.json())
	       .then(data => {
	           if (data.status === 'success') {
	               // Close modal
	               $('#add-new-costom').modal('hide');
	               // Show SweetAlert2 success popup
	               Swal.fire({
	                   icon: 'success',
	                   title: 'Berhasil!',
	                   text: 'DATA BERHASIL DI TAMBAHKAN',
	                   confirmButtonText: 'OK'
	               }).then(() => {
	                   // Redirect after popup is closed
	                   window.location.href = '<?= site_url('QuickService/cos_lunas') ?>';
	               });
	           } else {
	               Swal.fire({
	                   icon: 'error',
	                   title: 'Error',
	                   text: 'Terjadi kesalahan',
	                   confirmButtonText: 'OK'
	               });
	           }
	       })
	       .catch(error => {
	           console.error('Error:', error);
	           Swal.fire({
	               icon: 'error',
	               title: 'Error',
	               text: 'Terjadi kesalahan',
	               confirmButtonText: 'OK'
	           });
	       });
	       return false; // Prevent default submit
	   }

	   function sendToWA(pdfLink, hp, nama, kode, trans_kode) {
	       if (hp.startsWith('0')) {
	           hp = '62' + hp.substring(1);
	       }
	       hp = hp.replace(/\D/g, '');
	       let details = '';
	       let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Mengecek Transaksi Anda Silahkan Download aplikasi AzzaService di Playstore lalu Login menggunakan akun dengan username: ${kode}, password : ${kode},\n\nJangan lupa untuk memberikan rating pada aplikasi AzzaService ya! 😊\n\nAnda dapat melihat tanda terima di:\n👉 https://dashboard.azzahracomputertegal.com/Cetak/print_tts/${trans_kode}\n\nTERIMA KASIH`;
	       const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
	       window.open(waUrl, '_blank');
	   }

	</script>

	<script>
	document.addEventListener("DOMContentLoaded", function () {
	    const radioButtons = document.querySelectorAll("input[name='pswd_type']");
	    const textField = document.getElementById("pswd_text");
	    const descField = document.getElementById("pswd_pattern_desc");
	    const canvasField = document.getElementById("pswd_pattern_canvas");

	    radioButtons.forEach(rb => {
	        rb.addEventListener("change", function () {
	            textField.style.display = "none";
	            descField.style.display = "none";
	            canvasField.style.display = "none";

	            if (this.value === "text") textField.style.display = "block";
	            if (this.value === "pattern_desc") descField.style.display = "block";
	            if (this.value === "pattern_canvas") canvasField.style.display = "block";
	        });
	    });

	    // CANVAS DRAWING
	    const canvas = document.getElementById("patternCanvas");
	    if (canvas) {
	        const ctx = canvas.getContext("2d");
	        let drawing = false;

	        // Draw default pattern dots
	        function drawDots() {
	            ctx.fillStyle = '#000';
	            const dotRadius = 5;
	            const positions = [
	                {x: 50, y: 50}, {x: 150, y: 50}, {x: 250, y: 50},
	                {x: 50, y: 100}, {x: 150, y: 100}, {x: 250, y: 100},
	                {x: 50, y: 150}, {x: 150, y: 150}, {x: 250, y: 150}
	            ];
	            positions.forEach(pos => {
	                ctx.beginPath();
	                ctx.arc(pos.x, pos.y, dotRadius, 0, 2 * Math.PI);
	                ctx.fill();
	            });
	        }

	        // Draw dots initially
	        drawDots();

	        canvas.addEventListener("mousedown", (e) => {
	            drawing = true;
	            ctx.beginPath();
	            const rect = canvas.getBoundingClientRect();
	            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
	        });
	        canvas.addEventListener("mouseup", () => {
	            drawing = false;
	        });
	        canvas.addEventListener("mouseleave", () => {
	            drawing = false;
	        });
	        canvas.addEventListener("mousemove", draw);

	        // Ensure drawing stops when mouse is released anywhere
	        document.addEventListener("mouseup", () => {
	            drawing = false;
	        });

	        function draw(e) {
	            if (!drawing) return;
	            const rect = canvas.getBoundingClientRect();
	            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
	            ctx.stroke();
	        }

	        document.getElementById("clearCanvas").onclick = () => {
	            ctx.clearRect(0, 0, canvas.width, canvas.height);
	            ctx.beginPath(); // Reset path
	            drawDots(); // Redraw dots
	        };

	        document.getElementById("saveCanvas").onclick = () => {
	            const dataURL = canvas.toDataURL("image/png");
	            document.getElementById("pswd_canvas").value = dataURL;
	            Swal.fire({
	                icon: 'success',
	                title: 'Berhasil!',
	                text: 'Pola berhasil disimpan!',
	                confirmButtonText: 'OK'
	            });
	        };
	    }
	});
	</script>

	<script>
	function togglePasswordFields() {
	    const radioButtons = document.querySelectorAll("input[name='pswd_type']");
	    const textField = document.getElementById("pswd_text");
	    const descField = document.getElementById("pswd_pattern_desc");
	    const canvasField = document.getElementById("pswd_pattern_canvas");

	    if (textField && descField && canvasField) {
	        textField.style.display = "none";
	        descField.style.display = "none";
	        canvasField.style.display = "none";

	        radioButtons.forEach(rb => {
	            if (rb.checked) {
	                if (rb.value === "text") textField.style.display = "block";
	                if (rb.value === "pattern_desc") descField.style.display = "block";
	                if (rb.value === "pattern_canvas") canvasField.style.display = "block";
	            }
	        });
	    }
	}

	function clearCanvas() {
	    const canvas = document.getElementById("patternCanvas");
	    if (canvas) {
	        const ctx = canvas.getContext("2d");
	        ctx.clearRect(0, 0, canvas.width, canvas.height);
	        // Redraw dots
	        ctx.fillStyle = '#000';
	        const dotRadius = 5;
	        const positions = [
	            {x: 50, y: 50}, {x: 150, y: 50}, {x: 250, y: 50},
	            {x: 50, y: 100}, {x: 150, y: 100}, {x: 250, y: 100},
	            {x: 50, y: 150}, {x: 150, y: 150}, {x: 250, y: 150}
	        ];
	        positions.forEach(pos => {
	            ctx.beginPath();
	            ctx.arc(pos.x, pos.y, dotRadius, 0, 2 * Math.PI);
	            ctx.fill();
	        });
	    }
	}

	<?php if ($this->session->userdata('send_wa')): ?>
	    <?php $wa_data = $this->session->userdata('send_wa'); $this->session->unset_userdata('send_wa'); ?>
	    window.addEventListener('load', function() {
	        Swal.fire({
	            title: 'DATA',
	            text: 'BERHASIL PEMBAYARAN SELESAI DAN NOTIFIKASI WHATSAPP TELAH DIKIRIM',
	            icon: 'success'
	        }).then(() => {
	            sendToWA(null, '<?php echo $wa_data['hp']; ?>', '<?php echo $wa_data['nama']; ?>', '<?php echo $wa_data['kode']; ?>', '<?php echo $wa_data['trans_kode']; ?>');
	        });
	    });
	<?php endif; ?>
	</script>

<?php $this->load->view('Template/footer'); ?>