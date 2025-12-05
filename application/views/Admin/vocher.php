<?php $this->load->view('Template/header'); ?>
<div class="content">
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Pengajuan Discount
        </h2>
    </div>
    <div class="intro-y box px-5 pt-5 mt-5">
		<div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
			<div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Azzahra" class="rounded-full" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-14.jpg">
                    <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= $vocher['cos_nama']; ?></div>
                    <div class="text-gray-600"><?= $vocher['cos_kode']; ?></div>
                </div>
            </div>
            <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="truncate sm:whitespace-normal flex items-center"> 
                	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= $vocher['cos_hp']; ?> 
            	</div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $vocher['cos_status']; ?> 
            	</div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $vocher['cos_tipe']; ?> 
            	</div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-80 flex">
                    	<textarea class="ml-3 font-medium" style="width: 300px;"><?= $vocher['cos_alamat']?> </textarea>
                    	
                    </div>
                </div>
            </div>                    
		</div>
		<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
			<a data-toggle="tab" data-target="#tindakan" href="javascript:;" class="py-4 sm:mr-8 active">Tindakan</a> 
			<a data-toggle="tab" data-target="#unit" href="javascript:;" class="py-4 sm:mr-8">Unit</a>
			<a data-toggle="tab" data-target="#kelket" href="javascript:;" class="py-4 sm:mr-8">Keluhan & Keterangan</a> 
		</div>
	</div>
	<div class="intro-y tab-content mt-5">
		<div class="tab-content__pane active" id="tindakan">
			<div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto"><br>
                        Detail Tindakan
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                		<div class="col-span-12 lg:col-span-8">
                			<div class="overflow-x-auto"><br>
                				<table class="table">
                                	<thead>
                                        <tr class="bg-gray-700 text-white">
                                            <th class="whitespace-no-wrap">#</th>
                                            <th class="whitespace-no-wrap">Tindakan</th>
                                            <th class="whitespace-no-wrap">Qty</th>
                                            <th class="whitespace-no-wrap">Subtotal</th>
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
	                                    		</tr>	
	                                    	<?php $no++; endforeach;?>	
                                    </tbody>
                                </table>
                			</div>
                			
                		</div>
                		<div class="col-span-12 lg:col-span-4 ">                			
                            <div class="box p-5 mt-5 bg-gray-200">
                            	<form method="post" action="<?= site_url('Admin/save_vocher')?>">
                            		<input type="hidden" name="kode" value="<?= $vocher['trans_kode']?>">
                            		<div class="flex">
                                        <div class="mr-auto">Total</div>
                                        <div>
                                        	<?= "Rp. ".number_format($vocher['trans_total'], 0).",-"; ?>	
                                        </div>
                                    </div>
                                    <div class="flex mt-4">
                                        <div class="mr-auto">Discount</div>
                                        <div class="text-theme-6">
                                        	<?= "Rp. ".number_format($vocher['trans_discount'], 0).",-"; ?>		
                                        </div>
                                    </div>
                                    <div class="flex mt-4">
                                        <div class="mr-auto">Jumlah Diajukan</div>
                                        <div>
                                        	<?= "Rp. ".number_format($vocher['voc_jumlah'], 0).",-"; ?>
                                        </div>
                                    </div>
                                    <div class="flex mt-4 pt-4 border-t border-gray-200">
                                        <div class="mr-auto font-medium text-base">
                                        	<input type="text" name="vocher" class="input input--lg w-full pr-10 placeholder-theme-13" required placeholder="Masukan jumlah vocher discount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" style="width: 350px;">
                                        </div>
                                    </div>
                                    <button type="submit" class="button w-40 mx-auto justify-center block bg-theme-9 text-gray-900 mt-5">Buat Vocher</button>
                            	</form>
                                
                                
                            </div>
                		</div>
                	</div>
                </div>
            </div>
		</div>
		<div class="tab-content__pane" id="unit">
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
		                    	<input type="text" class="input w-full border flex-1" value="<?= $vocher['cos_status']; ?>">
			                </div>
			            <div class="intro-y col-span-12 sm:col-span-6">
		                    <div class="mb-2">Type </div>
			                    <input type="text" class="input w-full border flex-1"  value="<?= $vocher['cos_tipe']; ?>" >
			                </div>
		                <div class="intro-y col-span-12 sm:col-span-6">
		                    <div class="mb-2">Model </div>
		                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $vocher['cos_model']; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
		                </div>
		                <div class="intro-y col-span-12 sm:col-span-6">
		                    <div class="mb-2">No seri </div>
			                    <input type="text" class="input w-full border flex-1" value="<?= $vocher['cos_no_seri']; ?>">
			                </div>
		                <div class="intro-y col-span-12 sm:col-span-6">
		                    <div class="mb-2">Password </div>
		                    <input type="text" class="input w-full border flex-1" value="<?= $vocher['cos_pswd']; ?>">
		                </div>
		                <div class="intro-y col-span-12">
		                    <div class="mb-2">Asesoris</div>
		                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $vocher['cos_asesoris']; ?></textarea>
		                </div>
			        </div>
	            </div>
	            			            
	        </div>
		</div>
		<div class="tab-content__pane" id="kelket">
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
		                    <textarea class="input w-full border mt-2 flex-1"><?= $vocher['cos_keluhan']; ?></textarea>
		                </div>
		                <div class="intro-y col-span-12">
		                    <div class="mb-2">Keterangan</div>
		                    <textarea class="input w-full border mt-2 flex-1"><?= $vocher['cos_keterangan']; ?></textarea>
		                </div>
		            </div>
                </div>
			</div>
    	</div>
</div>
<?php $this->load->view('Template/footer'); ?>