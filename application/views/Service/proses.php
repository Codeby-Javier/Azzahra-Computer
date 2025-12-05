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
        	<a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#edit-costom-<?= $proses['cos_kode'];?>">
        		Edit Data
        	</a>
        </div>
    </div>
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Service/cos_baru')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Service/cos_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
		            </a>
		            <a href="<?= site_url('Service/cos_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfermasi
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
    		<div class="intro-y box px-5 pt-5 mt-5">
				<div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
					<div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
	                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
	                        <img alt="Azzahra" class="rounded-full" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-14.jpg">
	                        <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
	                    </div>
	                    <div class="ml-5">
	                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= $proses['cos_nama']; ?></div>
	                        <div class="text-gray-600"><?= $proses['cos_kode']; ?></div>
	                    </div>
	                </div>
	                <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
	                    <div class="truncate sm:whitespace-normal flex items-center"> 
	                    	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= (strlen($proses['cos_hp']) > 8) ? substr($proses['cos_hp'],0,4)."xxxx".substr($proses['cos_hp'],-4) : ((strlen($proses['cos_hp']) > 4) ? substr($proses['cos_hp'],0,2)."xxxx".substr($proses['cos_hp'],-2) : $proses['cos_hp']); ?>
	                    	<!-- <?= $proses['cos_hp']; ?> --> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $proses['cos_status']; ?> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $proses['cos_tipe']; ?> 
	                	</div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Keluhan</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium text-theme-6" style="width: 300px;"><?= $proses['cos_keluhan']?> </textarea>
                            	
                            </div>
                        </div>
                    </div>                    
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
					<a data-toggle="tab" data-target="#input-data" href="javascript:;" class="py-4 sm:mr-8 active">Input Proses</a>  
				</div>
			</div>
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="input-data">
					<div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Tindakan Teknisi
                            </h2>
                        </div>
                        <div class="p-5">
                        	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                        		<div class="intro-y col-span-12 sm:col-span-4">
				                    <div class="mb-2">Masukan tindakan teknisi</div>
				                    <input type="text" class="input w-full border flex-1" placeholder="Tindakan teknisi" id="tindakan">
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-5">
				                    <div class="mb-2">Keterangan</div>
				                    <textarea class="input w-full border flex-1" id="area"></textarea>
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-1">
				                    <div class="mb-2"> &nbsp;</div>
				                    <input type="text" class="input w-full border flex-1" placeholder="Qty" id="qty">
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-2">
				                	<div class="mb-2"> &nbsp;</div>
				                    <button class="button px-2 mr-1 mb-2 bg-theme-6 text-white"> <span class="w-5 h-5 flex items-center justify-center" id="tbl-add"> 
				                    	<i data-feather="plus" class="w-4 h-4"></i> </span> 
				                    </button>
				                </div>

                        	</div>
                            <div class="mt-5">
                            	<form method="post" action="<?= site_url('Service/save_tindakan')?>" onsubmit="prepareFormData(event)">
                            		<table class="table" id="tbl-trans">
	                                	<thead>
	                                        <tr class="bg-gray-700 text-white">
	                                            <th class="whitespace-no-wrap">#</th>
	                                            <th class="whitespace-no-wrap">Tindakan</th>
	                                            <th class="whitespace-no-wrap">Qty</th>
	                                            <th class="whitespace-no-wrap">Keterangan</th>
	                                            <th class="whitespace-no-wrap">Subtotal</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody id="tbl">
	                                    	
	                                    </tbody>
	                                </table>
                            		<input type="text" name="tras_kode" value="<?= $proses['trans_kode']?>" hidden>
                            		<button type="submit" class="button w-40 mx-auto justify-center block bg-gray-200 text-gray-600 mt-5" onclick="prepareFormData(event)">Simpan</button>
                            	</form>
                                
                            </div>
                        </div>
                    </div>
				</div>
			</div>
    	</div>
    	
    </div>    
</div>

<!-- modal tambah cotomer-->
<div class="modal" id="edit-costom-<?= $proses['cos_kode'];?>">
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
        <form method="post" action="<?= site_url('Service/update_trans/'.$proses['cos_kode'])?>">
        	<div class="tab-content">
	        	<div class="tab-content__pane active" id="custom">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Customer</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Nama</div>
			                    <input type="text" class="input w-full border flex-1" name="nama" value="<?= $proses['cos_nama']; ?>" required oninvalid="this.setCustomValidity('Nama customer tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan nama customer">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No Tlep</div>
			                    <input type="number" class="input w-full border flex-1" name="tlp" value="<?= $proses['cos_hp']; ?>"required oninvalid="this.setCustomValidity('No tlep customer tidak boleh kosong ?')" oninput="setCustomValidity('')" placeholder="Masukan no tlep customer">
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Alamat</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_alamat']; ?></textarea>
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
						             <option value="<?= $proses['cos_status']; ?>"><?= $proses['cos_status']; ?></option>
						             <option value="CID">CID</option>
						             <option value="IW">IW</option>
						             <option value="OOW">OOW</option>
						         </select>
			                </div>
				            <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Type </div>
			                    <input type="text" class="input w-full border flex-1" name="type" value="<?= $proses['cos_tipe']; ?>" required oninvalid="this.setCustomValidity('Type unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan type unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Model </div>
			                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $proses['cos_model']; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No seri </div>
			                    <input type="text" class="input w-full border flex-1" name="seri" value="<?= $proses['cos_no_seri']; ?>" required oninvalid="this.setCustomValidity('No seri unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Password </div>
			                    <input type="text" class="input w-full border flex-1" name="pswd" value="<?= $proses['cos_pswd']; ?>" required oninvalid="this.setCustomValidity('Password unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Asesoris</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $proses['cos_asesoris']; ?></textarea>
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
			                    <textarea class="input w-full border mt-2 flex-1" name="keluhan" required oninvalid="this.setCustomValidity('Keluhan tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_keluhan']; ?></textarea>
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Keterangan</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="ket" required oninvalid="this.setCustomValidity('Keterangan tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_keterangan']; ?></textarea>
			                </div>
			            </div>
			        </div>
			        <div class="px-5 py-3 text-right border-t border-gray-200">
			            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
			            <button type="submit" class="button w-20 bg-theme-1 text-white">Update</button>
			        </div>
	        	</div>
	        </div>
        </form>
        

	</div>
</div>

<script>
	  // Add row to table
	  document.getElementById('tbl-add').addEventListener('click', function() {
	      const tindakan = document.getElementById('tindakan').value.trim();
	      const area = document.getElementById('area').value.trim();
	      const qty = document.getElementById('qty').value.trim();

	      if (!tindakan || !qty) {
	          alert('Tindakan dan Qty harus diisi');
	          return;
	      }

	      const table = document.getElementById('tbl');
	      const rowCount = table.rows.length + 1;
	      const row = table.insertRow();
	      row.innerHTML = `
	          <td class="border-b">${rowCount}</td>
	          <td class="border-b">${tindakan}</td>
	          <td class="border-b">${qty}</td>
	          <td class="border-b">${area}</td>
	          <td class="border-b">0</td>
	      `;

	      // Clear inputs
	      document.getElementById('tindakan').value = '';
	      document.getElementById('area').value = '';
	      document.getElementById('qty').value = '';
	  });

	  // Prepare form data from table
	  function prepareFormData(event) {
	      const table = document.getElementById('tbl');
	      const rows = table.querySelectorAll('tr');
	      const no = [];
	      const tindakan = [];
	      const ket = [];
	      const qty = [];
	      const subtot = [];

	      rows.forEach((row, index) => {
	          const cells = row.querySelectorAll('td');
	          if (cells.length >= 5) {
	              no.push(index + 1);
	              tindakan.push(cells[1].textContent.trim() || 'Tindakan');
	              qty.push(cells[2].textContent.trim() || '1');
	              ket.push(cells[3].textContent.trim());
	              subtot.push(cells[4].textContent.trim().replace(/[^\d]/g, '') || '0');
	          }
	      });

	      const form = event.target.closest('form');
	      addHiddenInput(form, 'no', no);
	      addHiddenInput(form, 'tindakan', tindakan);
	      addHiddenInput(form, 'ket', ket);
	      addHiddenInput(form, 'qty', qty);
	      addHiddenInput(form, 'subtot', subtot);
	  }

	  function addHiddenInput(form, name, value) {
	      const input = document.createElement('input');
	      input.type = 'hidden';
	      input.name = name + '[]';
	      input.value = JSON.stringify(value);
	      form.appendChild(input);
	  }

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
</script>

<?php $this->load->view('Template/footer'); ?>