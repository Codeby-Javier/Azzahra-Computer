<?php $this->load->view('Template/header'); ?>
<div class="content">
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Costomer
        </h2>
    </div>
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Admin/cus_baru')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Admin/cus_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfermasi
		            </a>
		            <a href="<?= site_url('Admin/cus_konf_bank')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Bank Transfer 
		            </a>
		            <a href="<?= site_url('Admin/cus_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="play-circle"></i> Di peroses 
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
	                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= $proses['cos_nama'] ?: 'N/A'; ?></div>
	                        <div class="text-gray-600"><?= $proses['cos_kode'] ?: 'N/A'; ?></div>
	                    </div>
	                </div>
	                <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
	                    <div class="truncate sm:whitespace-normal flex items-center">
	                    	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= $proses['cos_hp'] ?: 'N/A'; ?>
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $proses['cos_status'] ?: 'N/A'; ?>
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $proses['cos_tipe'] ?: 'N/A'; ?>
	                	</div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium" style="width: 300px;"><?= $proses['cos_alamat'] ?: 'N/A'; ?> </textarea>

                            </div>
                        </div>
                    </div>                    
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start">
					<a data-toggle="tab" data-target="#unit" href="javascript:;" class="py-4 sm:mr-8 active">Unit</a>
					<a data-toggle="tab" data-target="#kelket" href="javascript:;" class="py-4 sm:mr-8">Keluhan & Keterangan</a> 
				</div>
			</div>
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="unit">
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
				                    	<input type="text" class="input w-full border flex-1" value="<?= $proses['cos_status'] ?: 'N/A'; ?>">
					                </div>
					            <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Type </div>
					                    <input type="text" class="input w-full border flex-1"  value="<?= $proses['cos_tipe'] ?: 'N/A'; ?>" >
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Model </div>
				                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $proses['cos_model'] ?: 'N/A'; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">No seri </div>
					                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_no_seri'] ?: 'N/A'; ?>">
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Password </div>
				                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_pswd'] ?: 'N/A'; ?>">
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Asesoris</div>
				                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $proses['cos_asesoris'] ?: 'N/A'; ?></textarea>
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
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keluhan'] ?: 'N/A'; ?></textarea>
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Keterangan</div>
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keterangan'] ?: 'N/A'; ?></textarea>
				                </div>
				            </div>
                        </div>
					</div>
	        	</div>
			</div>
    	</div>
    	
</div>
<?php $this->load->view('Template/footer'); ?>