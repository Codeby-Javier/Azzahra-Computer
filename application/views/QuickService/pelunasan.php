<?php $this->load->view('Template/header'); ?>
<?php if (isset($error)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo $error; ?>',
        confirmButtonText: 'OK'
    });
});
</script>
<?php endif; ?>
<div class="content">
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Quick Service
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        	<a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#add-new-costom">
        		Buat Transaksi
        	</a>
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
	                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= isset($proses['cos_nama']) ? $proses['cos_nama'] : 'N/A'; ?></div>
	                        <div class="text-gray-600"><?= isset($proses['cos_kode']) ? $proses['cos_kode'] : 'N/A'; ?></div>
	                    </div>
	                </div>
	                <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
	                    <div class="truncate sm:whitespace-normal flex items-center">
	                    	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= isset($proses['cos_hp']) ? $proses['cos_hp'] : 'N/A'; ?>
	                 </div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= isset($proses['cos_status']) ? $proses['cos_status'] : 'N/A'; ?>
	                 </div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= isset($proses['cos_tipe']) ? $proses['cos_tipe'] : 'N/A'; ?>
	                 </div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium" style="width: 300px;"><?= isset($proses['cos_alamat']) ? $proses['cos_alamat'] : 'N/A'; ?> </textarea>

                            </div>
                        </div>
                    </div>
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start">
					<a data-toggle="tab" data-target="#detail-bayar" href="javascript:;" class="py-4 sm:mr-8 active">Tindakan</a>
					<a data-toggle="tab" data-target="#pembayaran" href="javascript:;" class="py-4 sm:mr-8">Pembayaran</a>
					<a data-toggle="tab" data-target="#konf-unit" href="javascript:;" class="py-4 sm:mr-8">Unit</a>
					<a data-toggle="tab" data-target="#konf-kelket" href="javascript:;" class="py-4 sm:mr-8">Keluhan & Keterangan</a>
				</div>
			</div>
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="detail-bayar">
					<div class="intro-y box col-span-12 lg:col-span-6">
				                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
				                        <h2 class="font-medium text-base mr-auto">
				                            Tambah Tindakan
				                        </h2>
				                    </div>
				                    <div class="p-5">
				                    	<form method="post" action="<?= site_url('QuickService/save_tindakan')?>">
				                    		<input type="hidden" name="trans_kode" value="<?= isset($proses['trans_kode']) ? $proses['trans_kode'] : ''; ?>">
				                    		<div class="grid grid-cols-12 gap-4 row-gap-5">
				                    			<div class="col-span-12 lg:col-span-6">
				                    				<div class="mb-2">Nama Tindakan</div>
				                    				<input type="text" class="input w-full border" name="tdkn_nama" required placeholder="Masukkan nama tindakan">
				                    			</div>
				                    			<div class="col-span-12 lg:col-span-3">
				                    				<div class="mb-2">Qty</div>
				                    				<input type="number" class="input w-full border" name="tdkn_qty" value="1" min="1" required>
				                    			</div>
				                    			<div class="col-span-12 lg:col-span-3">
				                    				<div class="mb-2">Harga</div>
				                    				<input type="text" class="input w-full border" name="tdkn_subtot" id="tdkn_subtot" required placeholder="0">
				                    			</div>
				                    			<div class="col-span-12">
				                    				<div class="mb-2">Keterangan</div>
				                    				<textarea class="input w-full border" name="tdkn_ket" placeholder="Keterangan tindakan"></textarea>
				                    			</div>
				                    			<div class="col-span-12">
				                    				<button type="submit" class="button bg-theme-1 text-white">Tambah Tindakan</button>
				                    			</div>
				                    		</div>
				                    	</form>
				                    </div>
				                </div>

				                <div class="intro-y box col-span-12 lg:col-span-6 mt-5">
				                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
				                        <h2 class="font-medium text-base mr-auto"><br>
				                            Detail Pembayaran
				                        </h2>
				                    </div>
				                    <div class="p-5">
				                    	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
				                    		<div class="col-span-12 lg:col-span-8"><br>
				                    			<div class="overflow-x-auto">
				                    				<table class="table">
				                             	<thead>
				                                     <tr class="bg-gray-700 text-white">
				                                         <th class="whitespace-no-wrap">#</th>
				                                         <th class="whitespace-no-wrap">Tindakan</th>
				                                         <th class="whitespace-no-wrap">Qty</th>
				                                         <th class="whitespace-no-wrap">Subtotal</th>
				                                         <th class="whitespace-no-wrap">Aksi</th>
				                                     </tr>
				                                 </thead>
				                                 <tbody>
				                                 	<?php
				                                 		$no=1;
				                                 		if (isset($data) && is_object($data)) {
				                                 		    foreach ($data->result_array() as $row):?>
				                                 		    	<tr>
				                                 		    		<td class="border-b"><?= $no; ?></td>
				                                 		    		<td class="border-b"><?= $row['tdkn_nama']?></td>
				                                 		    		<td class="border-b"><?= $row['tdkn_qty']?></td>
				                                 		    		<td class="border-b">
				                                 		    			<?= "Rp. ".number_format($row['tdkn_subtot'] ?? 0, 0).",-"; ?>
				                                 		    		</td>
				                                 		    		<td class="border-b">
				                                 		    			<a href="<?= site_url('QuickService/batal_tindakan/'.$row['trans_kode'].'/'.$row['tdkn_kode'])?>" class="text-red-500 hover:text-red-700" onclick="deleteTindakan(event, this)">
				                                 		    				<i class="w-4 h-4" data-feather="trash-2"></i>
				                                 		    			</a>
				                                 		    		</td>
				                                 		    	</tr>
				                                 		    <?php $no++; endforeach;
				                                 		} ?>
				                                 </tbody>
				                             </table>
				                    			</div>

				                    		</div>
                        		<div class="col-span-12 lg:col-span-4 ">
	                                <div class="box p-5 mt-5 bg-gray-200">
	                                	<div class="flex">
	                                	        <div class="mr-auto">Total</div>
	                                	        <div>
	                                	        	<?= "Rp. ".number_format($proses['trans_total'] ?? 0, 0).",-"; ?>
	                                	        </div>
	                                	    </div>
	                                	    <div class="flex mt-4">
	                                	        <div class="mr-auto">Discount</div>
	                                	        <div>
	                                	        	<?= "Rp. ".number_format($proses['trans_discount'] ?? 0, 0).",-"; ?>
	                                	        </div>
	                                	    </div>
	                                	    <div class="flex mt-4">
	                                	        <div class="mr-auto">Down Payment</div>
	                                	        <div>
	                                	        	<?= "Rp. ".number_format($proses['dtl_jml_bayar'] ?? 0, 0).",-"; ?>
	                                	        </div>
	                                	    </div>
	                                	    <div class="flex mt-4">
	                                	        <div class="mr-auto">Kekurangan</div>
	                                	        <div class="text-theme-6">
	                                	        	<?= "Rp. ".number_format(($proses['trans_total'] ?? 0) - ($proses['trans_discount'] ?? 0) - ($proses['dtl_jml_bayar'] ?? 0), 0).",-"; ?>
	                                	        </div>
	                                	    </div>

	                                </div>
                        		</div>
                        	</div>
                        </div>
                    </div>
				</div>
				<div class="tab-content__pane" id="pembayaran">
					<div class="intro-y box col-span-12">
				                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
				                        <h2 class="font-medium text-base mr-auto">
				                            Form Pembayaran
				                        </h2>
				                    </div>
				                    <div class="p-5">
				                    	<div class="grid grid-cols-12 gap-4 row-gap-5">
				                    		<div class="col-span-12 lg:col-span-6">
				                    			<form id="payment-form" method="post" action="#">
				                    				<input type="hidden" name="kode" value="<?= isset($proses['trans_kode']) ? $proses['trans_kode'] : ''; ?>">
				                    				<div class="mb-4">
				                    					<div class="mb-2">Jumlah Bayar</div>
				                    					<input type="text" class="input w-full border" name="lunas" id="lunas" required placeholder="Masukkan jumlah pembayaran">
				                    				</div>
				                    				<div class="mb-4">
				                    					<div class="mb-2">Jenis Bayar</div>
				                    					<select class="input w-full border" name="jenis_bayar" id="jenis_bayar" required>
				                    						<option value="TUNAI">Tunai</option>
				                    						<option value="TRANFER">Transfer</option>
				                    						<option value="QRIS">QRIS</option>
				                    					</select>
				                    				</div>
				                    				<div class="mb-4" id="bank_field" style="display: none;">
				                    					<div class="mb-2">Bank</div>
				                    					<select class="input w-full border" name="bank" id="bank">
				                    						<option value="">Pilih Bank</option>
				                    						<option value="BCA">BCA</option>
				                    						<option value="BRI">BRI</option>
				                    						<option value="MANDIRI">Mandiri</option>
				                    						<option value="BNI">BNI</option>
				                    					</select>
				                    				</div>
				                    				<div class="mb-4" id="account_info" style="display: none;">
				                    					<div class="mb-2">Rekening Transfer</div>
				                    					<p>FERRY JUANDA - 0470727705</p>
				                    				</div>
				                    				<div class="flex gap-2">
				                    					<?php
				                    					$total_tagihan = (isset($proses['trans_total']) ? $proses['trans_total'] : 0) - (isset($proses['trans_discount']) ? $proses['trans_discount'] : 0);
				                    					$sudah_dibayar = isset($proses['dtl_jml_bayar']) ? $proses['dtl_jml_bayar'] : 0;
				                    					$sisa_bayar = $total_tagihan - $sudah_dibayar;
				                    					?>
				                    					<button type="button" id="btn_bayar" class="button bg-theme-1 text-white flex-1">Bayar</button>
				                    					<button type="button" id="btn_pembayaran_selesai" class="button bg-green-500 text-white flex-1" style="display: none;" data-trans-kode="<?= isset($proses['trans_kode']) ? $proses['trans_kode'] : ''; ?>" data-cos-hp="<?= isset($proses['cos_hp']) ? $proses['cos_hp'] : ''; ?>" data-cos-nama="<?= isset($proses['cos_nama']) ? $proses['cos_nama'] : ''; ?>" data-cos-kode="<?= isset($proses['cos_kode']) ? $proses['cos_kode'] : ''; ?>">Pembayaran Selesai</button>
				                    				</div>
				                    				<?php if($sisa_bayar < 0): ?>
				                    				<div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
				                    					<strong>Kembalian: Rp. <?= number_format(abs($sisa_bayar), 0) ?>,-</strong>
				                    				</div>
				                    				<?php endif; ?>
				                    			</form>
				                    		</div>
				                    		<div class="col-span-12 lg:col-span-6">
				                    			<div class="box p-5 bg-gray-100">
				                    				<h3 class="font-medium mb-4">Ringkasan Pembayaran</h3>
				                    				<?php
				                    				$total_tagihan = (isset($proses['trans_total']) ? $proses['trans_total'] : 0) - (isset($proses['trans_discount']) ? $proses['trans_discount'] : 0);
				                    				$sudah_dibayar = isset($proses['dtl_jml_bayar']) ? $proses['dtl_jml_bayar'] : 0;
				                    				$sisa_bayar = $total_tagihan - $sudah_dibayar;
				                    				?>
				                    				<div class="flex justify-between mb-2">
				                    					<span>Total Tagihan:</span>
				                    					<span class="font-medium">Rp. <?= number_format($total_tagihan, 0) ?>,-</span>
				                    				</div>
				                    				<div class="flex justify-between mb-2">
				                    					<span>Sudah Dibayar:</span>
				                    					<span class="font-medium">Rp. <?= number_format($sudah_dibayar, 0) ?>,-</span>
				                    				</div>
				                    				<div class="flex justify-between border-t pt-2">
				                    					<span class="font-medium">Sisa Pembayaran:</span>
				                    					<span class="font-medium <?= $sisa_bayar > 0 ? 'text-red-500' : 'text-green-500' ?>">Rp. <?= number_format($sisa_bayar, 0) ?>,-</span>
				                    				</div>
				                    			</div>
				                    		</div>
				                    	</div>
				                    </div>
				                </div>
				</div>
				<div class="tab-content__pane" id="konf-unit">
					<div class="intro-y box col-span-12 lg:col-span-6">
						<div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Data Unit
                            </h2>
                        </div>
			            <div class="p-5">
			            	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			            		<div class="intro-y col-span-12">
			            	        <div class="mb-2">Status </div>
			            	        	<input type="text" class="input w-full border flex-1" value="<?= isset($proses['cos_status']) ? $proses['cos_status'] : ''; ?>">
			            	     </div>
			            	 <div class="intro-y col-span-12 sm:col-span-6">
			            	        <div class="mb-2">Type </div>
			            	         <input type="text" class="input w-full border flex-1"  value="<?= isset($proses['cos_tipe']) ? $proses['cos_tipe'] : ''; ?>" >
			            	     </div>
			            	    <div class="intro-y col-span-12 sm:col-span-6">
			            	        <div class="mb-2">Model </div>
			            	        <input type="text" class="input w-full border flex-1" name="model" value="<?= isset($proses['cos_model']) ? $proses['cos_model'] : ''; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			            	    </div>
			            	    <div class="intro-y col-span-12 sm:col-span-6">
			            	        <div class="mb-2">No seri </div>
			            	         <input type="text" class="input w-full border flex-1" value="<?= isset($proses['cos_no_seri']) ? $proses['cos_no_seri'] : ''; ?>">
			            	     </div>
			            	    <div class="intro-y col-span-12 sm:col-span-6">
			            	        <div class="mb-2">Password </div>
			            	        <input type="text" class="input w-full border flex-1" value="<?= isset($proses['cos_pswd']) ? $proses['cos_pswd'] : ''; ?>">
			            	    </div>
			            	    <div class="intro-y col-span-12">
			            	        <div class="mb-2">Asesoris</div>
			            	        <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= isset($proses['cos_asesoris']) ? $proses['cos_asesoris'] : ''; ?></textarea>
			            	    </div>
			          </div>
			            </div>

			        </div>
				</div>
				<div class="tab-content__pane" id="konf-kelket">
					<div class="intro-y box col-span-12 lg:col-span-6">
						<div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Keluhan dan Keterangan
                            </h2>
                        </div>
                        <div class="p-5">
                        	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <div class="intro-y col-span-12">
                        <div class="mb-2">Keluhan</div>
                        <textarea class="input w-full border mt-2 flex-1"><?= isset($proses['cos_keluhan']) ? $proses['cos_keluhan'] : ''; ?></textarea>
                    </div>
                    <div class="intro-y col-span-12">
                        <div class="mb-2">Keterangan</div>
                        <textarea class="input w-full border mt-2 flex-1"><?= isset($proses['cos_keterangan']) ? $proses['cos_keterangan'] : ''; ?></textarea>
                    </div>
                </div>
                        </div>
					</div>
	        	</div>
			</div>
    	</div>

    </div>

</div>

<!-- modal QRIS -->
<div class="modal" id="qris-modal">
	<div class="modal__content modal__content--md p-10 intro-y box sm:py-15 mt-2" style="overflow: auto; max-height: 80vh;">
		<div class="text-center">
			<h3 class="font-medium text-lg mb-4">QRIS Payment</h3>
			<img src="<?php echo base_url(); ?>assets/image/my_qris.png" alt="QRIS" style="max-width: 100%; height: auto; max-height: 400px;">
		</div>
		<div class="px-5 py-3 text-right border-t border-gray-200">
			<button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Tutup</button>
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
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#kelket">3</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan dan Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form method="post" action="<?= site_url('QuickService/save_trans')?>">
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
			                    <div class="mb-2">Type </div>
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
			                <div class="intro-y col-span-12">
			                    <label class="flex items-center">
			                        <input type="checkbox" name="is_quick_service" value="1" class="input border mr-2" checked>
			                        <span class="text-sm">Quick Service (Service selesai langsung)</span>
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

	      // Check URL parameter for active tab
	      const urlParams = new URLSearchParams(window.location.search);
	      const activeTab = urlParams.get('tab');
	      if (activeTab === 'pembayaran') {
	          // Activate pembayaran tab
	          $('.nav-tabs a[href="#pembayaran"]').tab('show');
	      }

	      // Handle jenis bayar change
	      const jenisBayarSelect = document.getElementById('jenis_bayar');
	      if (jenisBayarSelect) {
	       jenisBayarSelect.addEventListener('change', function() {
	           const bankField = document.getElementById('bank_field');
	           const accountInfo = document.getElementById('account_info');
	           if (this.value === 'TRANFER') {
	               bankField.style.display = 'block';
	               accountInfo.style.display = 'none';
	           } else if (this.value === 'QRIS') {
	               bankField.style.display = 'none';
	               accountInfo.style.display = 'none';
	               $('#qris-modal').modal('show');
	           } else {
	               bankField.style.display = 'none';
	               accountInfo.style.display = 'none';
	           }
	       });
	      }

	      // Handle bank change
	      const bankSelect = document.getElementById('bank');
	      if (bankSelect) {
	       bankSelect.addEventListener('change', function() {
	           const accountInfo = document.getElementById('account_info');
	           if (this.value) {
	               accountInfo.style.display = 'block';
	           } else {
	               accountInfo.style.display = 'none';
	           }
	       });
	      }
	  
	      // Prevent form submission on Enter key in input fields
	      const paymentForm = document.getElementById('payment-form');
	      if (paymentForm) {
	       paymentForm.addEventListener('keydown', function(e) {
	           if (e.key === 'Enter') {
	               e.preventDefault();
	               return false;
	           }
	       });
	      }
	  
	      // Format Rupiah for input fields
	      function formatRupiah(angka, prefix) {
	       var number_string = angka.replace(/[^,\d]/g, '').toString(),
	        split = number_string.split(','),
	        sisa = split[0].length % 3,
	        rupiah = split[0].substr(0, sisa),
	        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
	  
	       if (ribuan) {
	        separator = sisa ? '.' : '';
	        rupiah += separator + ribuan.join('.');
	       }
	  
	       rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	       return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	      }
	  
	      // Apply Rupiah formatting to Jumlah Bayar input
	      const jumlahBayarInput = document.getElementById('lunas');
	      if (jumlahBayarInput) {
	       jumlahBayarInput.addEventListener('input', function(e) {
	        this.value = formatRupiah(this.value);
	       });
	      }
	  
	      // Apply Rupiah formatting to Harga input in Tindakan tab
	      const hargaInput = document.getElementById('tdkn_subtot');
	      if (hargaInput) {
	       hargaInput.addEventListener('input', function(e) {
	        this.value = formatRupiah(this.value);
	       });
	      }
	  
	      // Handle bayar button - just update UI summary, don't save to database
	      const btnBayar = document.getElementById('btn_bayar');
	      if (btnBayar) {
	       btnBayar.addEventListener('click', function(e) {
	           e.preventDefault();

	           // Validate required fields
	           const lunas = parseFloat(document.getElementById('lunas').value.replace(/\./g, '')) || 0;
	           const jenisBayar = document.getElementById('jenis_bayar').value;
	  
	           if (!lunas || !jenisBayar) {
	               Swal.fire({
	                   icon: 'warning',
	                   title: 'Peringatan',
	                   text: 'Harap isi jumlah bayar dan jenis bayar',
	                   confirmButtonText: 'OK'
	               });
	               return;
	           }
	  
	           // Check if payment is sufficient (can be more for change, but not less)
	           const totalTagihan = <?= $total_tagihan ?? 0 ?>;
	           const sudahDibayar = <?= $sudah_dibayar ?? 0 ?>;
	           const sisaTagihan = totalTagihan - sudahDibayar;
	  
	           if (lunas < sisaTagihan) {
	               Swal.fire({
	                   icon: 'error',
	                   title: 'Pembayaran Tidak Mencukupi',
	                   text: 'Jumlah pembayaran tidak mencukupi. Sisa yang harus dibayar: Rp. ' + sisaTagihan.toLocaleString('id-ID'),
	                   confirmButtonText: 'OK'
	               });
	               return;
	           }
	  
	           // Just update the UI - change button to "Pembayaran Selesai"
	           document.getElementById('btn_bayar').style.display = 'none';
	           document.getElementById('btn_pembayaran_selesai').style.display = 'block';
	  
	           // Disable input fields
	           document.getElementById('lunas').disabled = true;
	           document.getElementById('jenis_bayar').disabled = true;
	           const bankField = document.getElementById('bank');
	           if (bankField) bankField.disabled = true;
	  
	           // Update summary display
	           const newSudahDibayar = sudahDibayar + lunas;
	           const newSisaBayar = totalTagihan - newSudahDibayar;
	  
	           // Update the summary text (this is just visual)
	           const summaryContainer = document.querySelector('.box.p-5.bg-gray-100');
	           if (summaryContainer) {
	               const sudahDibayarEl = summaryContainer.querySelectorAll('.flex.justify-between.mb-2')[1];
	               const sisaPembayaranEl = summaryContainer.querySelector('.flex.justify-between.border-t.pt-2');
	  
	               if (sudahDibayarEl) {
	                   sudahDibayarEl.lastElementChild.textContent = 'Rp. ' + newSudahDibayar.toLocaleString('id-ID') + ',-';
	               }
	  
	               // Add payment method details
	               const jenisBayar = document.getElementById('jenis_bayar').value;
	               const bank = document.getElementById('bank') ? document.getElementById('bank').value : '';
	  
	               let metodeBayarEl = summaryContainer.querySelector('.metode-bayar-row');
	               if (!metodeBayarEl) {
	                   metodeBayarEl = document.createElement('div');
	                   metodeBayarEl.className = 'flex justify-between mb-2 metode-bayar-row';
	                   summaryContainer.insertBefore(metodeBayarEl, sisaPembayaranEl);
	               }
	  
	               let metodeText = jenisBayar;
	               if (jenisBayar === 'TRANFER' && bank) {
	                   metodeText += ' - ' + bank;
	               }
	               metodeBayarEl.innerHTML = '<span>Jenis Bayar:</span><span class="font-medium">' + metodeText + '</span>';
	  
	               // Add or update kembalian in the summary
	               let kembalianEl = summaryContainer.querySelector('.kembalian-row');
	               if (newSisaBayar < 0) {
	                   if (!kembalianEl) {
	                       kembalianEl = document.createElement('div');
	                       kembalianEl.className = 'flex justify-between mb-2 kembalian-row';
	                       summaryContainer.insertBefore(kembalianEl, sisaPembayaranEl);
	                   }
	                   kembalianEl.innerHTML = '<span class="font-medium text-green-600">Kembalian:</span><span class="font-medium text-green-600">Rp. ' + Math.abs(newSisaBayar).toLocaleString('id-ID') + ',-</span>';
	               } else if (kembalianEl) {
	                   kembalianEl.remove();
	               }
	  
	               if (sisaPembayaranEl) {
	                   sisaPembayaranEl.lastElementChild.textContent = 'Rp. ' + Math.max(0, newSisaBayar).toLocaleString('id-ID') + ',-';
	                   sisaPembayaranEl.lastElementChild.className = newSisaBayar > 0 ? 'text-red-500' : 'text-green-500';
	               }
	           }
	  
	           Swal.fire({
	               icon: 'success',
	               title: 'Ringkasan Diperbarui!',
	               text: 'Silakan klik "Pembayaran Selesai" untuk menyelesaikan transaksi',
	               confirmButtonText: 'OK'
	           });
	       });
	      }

	      // Handle pembayaran selesai button - submit form with payment data via AJAX
	      const btnPembayaranSelesai = document.getElementById('btn_pembayaran_selesai');
	      if (btnPembayaranSelesai) {
	       btnPembayaranSelesai.addEventListener('click', function(e) {
	           e.preventDefault();

	           Swal.fire({
	               title: 'Konfirmasi Pembayaran Selesai',
	               text: 'Apakah Anda yakin ingin menyelesaikan pembayaran dan mengirim pesan WhatsApp?',
	               icon: 'question',
	               showCancelButton: true,
	               confirmButtonColor: '#3085d6',
	               cancelButtonColor: '#d33',
	               confirmButtonText: 'Ya, Selesaikan',
	               cancelButtonText: 'Batal'
	           }).then((result) => {
	               if (result.isConfirmed) {
	                   // Clean Rupiah formatting before submission
	                   const jumlahBayarInput = document.getElementById('lunas');
	                   if (jumlahBayarInput) {
	                       jumlahBayarInput.value = jumlahBayarInput.value.replace(/\./g, '');
	                   }

	                   // Enable disabled inputs before submission
	                   document.getElementById('lunas').disabled = false;
	                   document.getElementById('jenis_bayar').disabled = false;
	                   const bankField = document.getElementById('bank');
	                   if (bankField) bankField.disabled = false;

	                   // Submit via AJAX
	                   const paymentForm = document.getElementById('payment-form');
	                   const formData = new FormData(paymentForm);
	                   fetch('<?= site_url('QuickService/pembayaran_selesai') ?>', {
	                       method: 'POST',
	                       headers: {
	                           'X-Requested-With': 'XMLHttpRequest'
	                       },
	                       body: formData
	                   })
	                   .then(response => response.json())
	                   .then(data => {
	                       if (data.status === 'success') {
	                           // Open WA in new tab
	                           const transKode = btnPembayaranSelesai.getAttribute('data-trans-kode');
	                           const hp = btnPembayaranSelesai.getAttribute('data-cos-hp');
	                           const nama = btnPembayaranSelesai.getAttribute('data-cos-nama');
	                           const kode = btnPembayaranSelesai.getAttribute('data-cos-kode');
	                           sendToWA('', '', '', '', nama, 'https://dashboard.azzahracomputertegal.com/Cetak/download/' + transKode, hp, 'service');

	                           // Show success message and redirect
	                           Swal.fire({
	                               icon: 'success',
	                               title: 'Pembayaran Berhasil',
	                               text: 'Pembayaran telah diselesaikan dan WhatsApp terbuka di tab baru',
	                               confirmButtonText: 'OK'
	                           }).then(() => {
	                               window.location.href = '<?= site_url('QuickService/cos_baru') ?>';
	                           });
	                       } else {
	                           throw new Error('Payment failed');
	                       }
	                   })
	                   .catch(error => {
	                       console.error('Error:', error);
	                       Swal.fire({
	                           icon: 'error',
	                           title: 'Error',
	                           text: 'Terjadi kesalahan saat menyimpan pembayaran',
	                           confirmButtonText: 'OK'
	                       });
	                   });
	              }
	           });
	       });
	      }
	  
	      // Clean Rupiah formatting for form submission
	      const forms = document.querySelectorAll('form');
	      forms.forEach(form => {
	       form.addEventListener('submit', function(e) {
	        // Clean jumlah bayar field
	        const jumlahBayarInput = document.getElementById('lunas');
	        if (jumlahBayarInput) {
	         jumlahBayarInput.value = jumlahBayarInput.value.replace(/\./g, '');
	        }
	  
	        // Clean harga field in tindakan form
	        const hargaInput = document.getElementById('tdkn_subtot');
	        if (hargaInput) {
	         hargaInput.value = hargaInput.value.replace(/\./g, '');
	        }
	       });
	      });
	  });

	  // Initialize modal tabs when modal is shown
	  $('#add-new-costom').on('shown.bs.modal', function () {
	      // Reset all tabs and show first tab
	      $('.tab-content__pane').removeClass('active');
	      $('.nav-tabs a').removeClass('active');
	      $('#custom').addClass('active');
	      $('.nav-tabs a[href="#custom"]').addClass('active');
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
	  });

	  // Handle QRIS modal events to allow scrolling
	  $('#qris-modal').on('shown.bs.modal', function () {
	      $('body').css('overflow', 'auto');
	  });
	  $('#qris-modal').on('hidden.bs.modal', function () {
	      $('body').removeClass('modal-open');
	      $('body').css('overflow', '');
	      $('body').css('padding-right', '');
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
	                  window.location.href = '<?= site_url('QuickService/pelunasan') ?>';
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

	  function deleteTindakan(event, link) {
	      event.preventDefault();
	      Swal.fire({
	          title: 'Konfirmasi Hapus',
	          text: 'Yakin ingin menghapus tindakan ini?',
	          icon: 'warning',
	          showCancelButton: true,
	          confirmButtonColor: '#d33',
	          cancelButtonColor: '#3085d6',
	          confirmButtonText: 'Ya, Hapus',
	          cancelButtonText: 'Batal'
	      }).then((result) => {
	          if (result.isConfirmed) {
	              window.location.href = link.href;
	          }
	      });
	  }

	  function sendToWA(status, nota, jumlah, tanggal, nama, link, hp, type = 'payment') {
	      // Format phone number: remove leading 0 and add 62 for Indonesian numbers
	      if (hp.startsWith('0')) {
	          hp = '62' + hp.substring(1);
	      }
	      // Remove any spaces or special characters
	      hp = hp.replace(/\D/g, '');

	      let message = '';
	      if (type === 'payment') {
	          // Get service details
	          let details = '';
	          <?php foreach ($data->result_array() as $key): ?>
	              details += '- <?= $key['tdkn_nama'] ?>\n';
	          <?php endforeach; ?>

	          let transStatus = 'Lunas';

	          message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Detail:\n${details}\nUntuk Status Transaksi nya:\n${transStatus}\n\nDownload aplikasi AzzaService di Playstore lalu Booking Service, Jangan lupa untuk memberikan rating pada aplikasi AzzaService ya! 😊\n\nAnda dapat melihat tanda terima digital di:\n👉 ${link}\n\nTERIMA KASIH`;
	      } else if (type === 'service') {
	          // Get service details
	          let details = '';
	          <?php foreach ($data->result_array() as $key): ?>
	              details += '<?= $key['tdkn_nama'] ?> - Qty: <?= $key['tdkn_qty'] ?> - Rp <?= number_format($key['tdkn_subtot'], 0, ',', '.') ?>,-\n';
	          <?php endforeach; ?>

	          let transStatus = 'Lunas';

	          message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Detail:\n${details}\nUntuk Status Transaksi nya:\n${transStatus}\n\nDownload aplikasi AzzaService di Playstore lalu Booking Service, Jangan lupa untuk memberikan rating pada aplikasi AzzaService ya! 😊${link ? '\n\nAnda dapat melihat tanda terima digital di:\n👉 ' + link : ''}\n\nTERIMA KASIH`;
	      }
	      const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
	      window.open(waUrl, '_blank');
	  }
</script>

<?php $this->load->view('Template/footer'); ?>