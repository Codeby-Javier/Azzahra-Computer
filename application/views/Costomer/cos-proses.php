<?php $this->load->view('Template/header'); ?>
<div class="content">
	<?php
	// Get flashdata and immediately clear it to prevent re-showing on refresh
	$suksesMsg = $this->session->flashdata('sukses');
	$gagalMsg = $this->session->flashdata('gagal');
	$this->session->set_flashdata('sukses', '');
	$this->session->set_flashdata('gagal', '');
	?>
	<div class="sukses" data-sukses="<?php echo $suksesMsg; ?>"></div>
	<div class="gagal" data-gagal="<?php echo $gagalMsg; ?>"></div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Costomer
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
		            <a href="<?= site_url('Service/cos_baru')?>" class="flex items-center px-3 py-2 mt-2 rounded-md ">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Service/cos_proses')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium"> 
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
		            </a>
		            <a href="<?= site_url('Service/cos_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
		            </a>
		            <a href="<?= site_url('Service/cos_pelunasan')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
		            </a>
		            <a href="<?= site_url('Service/cos_lunas')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
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
		                    <th class="border-b-2 text-center whitespace-no-wrap">UNIT</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
			    			foreach ($trans->result_array() as $row) :?>
			    				<tr>
				    				<td class="text-center border-b"><?= ++$no; ?></td>
<td class="text-center border-b"><?= $row['cos_kode']; ?></td>
<td class="border-b"><?= $row['cos_nama']; ?></td>
<td class="border-b"><?= $row['cos_alamat']; ?></td>
<td class="text-center border-b">
    <?php 
    $hp = $row['cos_hp']; 
    // Ambil hanya angka
    $hp_wa = preg_replace('/\D/', '', $hp);  

    // Jika nomor diawali 0, ubah ke 62
    if (substr($hp_wa, 0, 1) == '0') {
        $hp_wa = '62' . substr($hp_wa, 1);
    }
?>
<td class="text-center border-b">
    <a href="https://wa.me/<?= $hp_wa ?>" target="_blank" class="button bg-green-500 text-white px-3 py-1 rounded">
        Chat User
    </a>
</td>



				    				<td class="text-center">
				    					<div class="flex sm:justify-center items-center">
				    						<a href="#" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-9 text-white">
				    							<i data-feather="watch" class="w-4 h-4 mr-2"></i> <?= $row['cos_tipe']?>
				    						</a>
				    						<a href="<?= site_url('Service/print_tts/'.$row['trans_kode'])?>" target="_blanck" class="button px-2 mr-1 mb-2 bg-theme-6 text-white">
				    							<span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
				    						</a>
				    						<a href="javascript:;" onclick="sendToWA('<?= site_url('Cetak/print_tts/'.$row['trans_kode']) ?>', '<?= $row['cos_hp'] ?>', '<?= $row['cos_nama'] ?>', '<?= $row['cos_kode'] ?>', '<?= $row['trans_kode'] ?>')" class="button px-2 mr-1 mb-2 bg-green-500 text-white">
				    							<span class="w-5 h-5 flex items-center justify-center"> <i data-feather="message-circle" class="w-4 h-4"></i> </span>
				    						</a>
				    					</div>
				    					
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
<div class="modal" id="add-new-costom">
	<div class="modal__content modal__content--xl p-10 intro-y box sm:py-15 mt-2">
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
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#kelket">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan dan Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form method="post" action="<?= site_url('Service/save_trans')?>">
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
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Alamat</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"></textarea>
			                </div>
			            </div>
			        </div>
	        	</div>
	        	<div class="tab-content__pane" id="unit">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Unit</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			            	<div class="intro-y col-span-12">
			                    <div class="mb-2">Status </div>
			                    <select class="input w-full border flex-1" name="status" required oninvalid="this.setCustomValidity('Setatus unit tidak boleh kosong ?')" oninput="setCustomValidity('')">
						             <option value="">-</option>
						             <option value="CID">CID</option>
						             <option value="IW">IW</option>
						             <option value="OOW">OOW</option>
						         </select>
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
			                    <div class="mb-2">Password </div>
			                    <input type="text" class="input w-full border flex-1" name="pswd"  required oninvalid="this.setCustomValidity('Password unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
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

<script>
	  // Handle flashdata alerts
	  document.addEventListener('DOMContentLoaded', function() {
	      var suksesMsg = document.querySelector('.sukses')?.getAttribute('data-sukses') || '';
	      var gagalMsg = document.querySelector('.gagal')?.getAttribute('data-gagal') || '';

	      if (typeof Swal !== 'undefined') {
	          if (gagalMsg) {
	              Swal.fire({
	                  icon: 'error',
	                  title: 'Gagal',
	                  text: gagalMsg,
	                  confirmButtonColor: '#EF4444'
	              });
	              document.querySelector('.gagal').setAttribute('data-gagal', '');
	          } else if (suksesMsg) {
	              Swal.fire({
	                  icon: 'success',
	                  title: 'Berhasil',
	                  text: suksesMsg,
	                  timer: 1600,
	                  showConfirmButton: false
	              });
	              document.querySelector('.sukses').setAttribute('data-sukses', '');
	          }
	      } else {
	          if (gagalMsg) {
	              alert(gagalMsg);
	              document.querySelector('.gagal').setAttribute('data-gagal', '');
	          } else if (suksesMsg) {
	              alert(suksesMsg);
	              document.querySelector('.sukses').setAttribute('data-sukses', '');
	          }
	      }
	  });

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

<?php $this->load->view('Template/footer'); ?>