<?php $this->load->view('Template/header'); ?>
<div class="content">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
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
		            <a href="<?= site_url('Service/cos_baru')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Service/cos_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
		            </a>
		            <a href="<?= site_url('Service/cos_konf')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
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
<!-- <?= $row['cos_hp']; ?> -->
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $proses['cos_status']; ?> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $proses['cos_tipe']; ?> 
	                	</div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium" style="width: 300px;"><?= $proses['cos_alamat']?> </textarea>
                            	
                            </div>
                        </div>
                    </div>                    
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
					<a data-toggle="tab" data-target="#konfirmasi" href="javascript:;" class="py-4 sm:mr-8 active">Konfirmasi</a> 
					<a data-toggle="tab" data-target="#konf-unit" href="javascript:;" class="py-4 sm:mr-8">Unit</a>
					<a data-toggle="tab" data-target="#konf-kelket" href="javascript:;" class="py-4 sm:mr-8">Keluhan & Keterangan</a> 
				</div>
			</div>
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="konfirmasi">
					<div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Down Payment
                            </h2>
                        </div>
                        <div class="p-5">
                        	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                        		<div class="col-span-12 lg:col-span-8"><br>
                        			<div class="intro-y datatable-wrapper box p-5 mt-5">
                        				<table class="table table-report table-report--bordered display datatable w-full">
		                                	<thead>
		                                        <tr class="bg-gray-700 text-white">
		                                            <th class="text-center whitespace-no-wrap">#</th>
		                                            <th class="whitespace-no-wrap">Tindakan</th>
		                                            <th class="whitespace-no-wrap">Qty</th>
		                                            <th class="whitespace-no-wrap">Subtotal</th>
		                                            <th class="text-center whitespace-no-wrap">Actions</th>
		                                            <th class="whitespace-no-wrap">Keterangan</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                    	<?php
		                                    		$no=1;
			                                    	foreach ($data->result_array() as $row):?>
			                                    		<tr>
			                                    			<td class="border-b"><?= $no; ?></td>
			                                    			<td class="border-b"><?= $row['tdkn_nama']?></td>
			                                    			<td class="border-b"><?= $row['tdkn_qty']?></td>
			                                    			<td class="border-b">
			                                    				<?= "Rp. ".number_format($row['tdkn_subtot'], 0).",-"; ?>
			                                    			</td>
			                                    			<td class="text-center border-b">
			                                    				<a href="<?= site_url('Service/batal_tindakan/'.$row['trans_kode'].'/'.$row['tdkn_kode'])?>" class="tombol-batal-tindakan" data-nama="<?= $row['tdkn_nama']?>">
			                                    					<button class="button px-2 mr-1 mb-2 bg-theme-6 text-white">
			                                    		        <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="trash-2" class="w-4 h-4"></i> </span>
			                                    		    </button>
			                                    				</a>
			                                    			</td>
			                                    			<td class="border-b">
			                                    				<?= $row['tdkn_ket']?>
			                                    			</td>
			                                    		</tr>
			                                    	<?php $no++; endforeach;?>	
		                                    </tbody>
		                                </table>
		                                <div class="flex mt-5">
						                    <a href="<?= site_url('Service/batal_transaksi/'.$proses['trans_kode'])?>" class="tombol-batal" data-nama="<?= $proses['cos_nama']; ?>">
						                       <button class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 520px;">Pembatalan Transaksi</button> 
						                    </a>					                    
						                </div>
                        			</div>
                        			
                        		</div>
                        		<div class="col-span-12 lg:col-span-4 ">
                        			<form method="post" action="<?= site_url('Service/vocer')?>">
                        				<div class="box flex p-5 mt-5">
                        					<div class="w-full relative text-gray-700">
                        						<input type="hidden" name="kode" value="<?= $proses['trans_kode']?>">
		                                        <input type="text" name="vocer" class="input input--lg w-full bg-gray-200 pr-10 placeholder-theme-13" required placeholder="Jumlah Discount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
		                                        <i class="w-4 h-4 hidden sm:absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
		                                    </div>
		                                    <button type="submit" class="button  text-white bg-theme-1 ml-2">Discount</button>                                   
	                                	</div>
                        			</form>
                        			
	                                <div class="box p-5 mt-5 bg-gray-200">
	                                	<form method="post" action="<?= site_url('Service/save_dp')?>">
	                                		<input type="hidden" name="kode" value="<?= $proses['trans_kode']?>">
	                                		<div class="flex">
		                                        <div class="mr-auto">Total</div>
		                                        <div>
		                                        	<?= "Rp. ".number_format($proses['trans_total'], 0).",-"; ?>	
		                                        </div>
		                                    </div>
		                                    <div class="flex mt-4">
		                                        <div class="mr-auto">Discount</div>
		                                        <div class="text-theme-9"> <?= $vocher['voc_status']?> &nbsp;</div>
		                                        <div class="text-theme-6">
		                                        	<?= "Rp. ".number_format($proses['trans_discount'], 0).",-"; ?>		
		                                        </div>
		                                    </div>
		                                    <div class="flex mt-4">
		                                        <div class="mr-auto">Bank</div>
		                                        <div>
		                                        	<div class="sm:mt-2"> 
		                                        		<select class="input input--sm w-full  pr-10 placeholder-theme-13" name="bank" required="">
												            <option value="BCA">BCA</option>
												            <option value="MANDIRI">MANDIRI</option>
												            <option value="BRI">BRI</option>
												            <option value="QRIS">QRIS/E-WALLET</option>
												        </select> 
												     </div>
		                                        </div>
		                                    </div>
		                                    <div class="flex mt-4">
		                                    	<div class="mr-auto" id="reg">
		                                    		FERRY JUANDA
		                                    	</div>
		                                    	<div class="text-theme-1" id="noreg">
		                                    		0470727705
		                                    	</div>
		                                    </div>
		                                    <div class="flex mt-4 pt-4 border-t border-gray-200">
		                                        <div class="mr-auto font-medium text-base">
		                                        	<input type="text" name="dp" class="input input--lg w-full pr-10 placeholder-theme-13" required placeholder="Jumlah DP" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
		                                        </div>
		                                    </div>
		                                    <button type="submit" class="button w-40 mx-auto justify-center block bg-theme-9 text-gray-900 mt-5">Simpan DP</button>
		                                </form>
	                                    
	                                    
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
				                    	<input type="text" class="input w-full border flex-1" value="<?= $proses['cos_status']; ?>">
					                </div>
					            <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Type </div>
					                    <input type="text" class="input w-full border flex-1"  value="<?= $proses['cos_tipe']; ?>" >
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Model </div>
				                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $proses['cos_model']; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">No seri </div>
					                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_no_seri']; ?>">
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Password </div>
				                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_pswd']; ?>">
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Aksesoris</div>
				                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $proses['cos_asesoris']; ?></textarea>
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
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keluhan']; ?></textarea>
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Keterangan</div>
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keterangan']; ?></textarea>
				                </div>
				            </div>
                        </div>
					</div>
	        	</div>
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
			                    <div class="mb-2">Aksesoris</div>
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


<?php $this->load->view('Template/footer'); ?>