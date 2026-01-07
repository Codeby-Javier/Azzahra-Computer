<?php $this->load->view('Template/header'); ?>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Bank Transfer</h1>                
                <p>Transaksi Bank Transfer</p>	
            </div>            
        </header>
<div class="content" style="margin-top: 60px">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>	
    <!-- <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Admin/cus_baru')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Admin/cus_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfirmasi
		            </a>
		            <a href="<?= site_url('Admin/cus_konf_bank')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Bank Transfer 
		            </a>
		            <a href="<?= site_url('Admin/cus_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="play-circle"></i> Di proses 
		            </a>
		        </div>
    		</div>
    	</div> -->
    	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
    		<div class="intro-y datatable-wrapper box p-5 mt-5">
    			<table class="table table-report table-report--bordered display datatable w-full">
    				<thead>
		    			<tr>
		    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
		    				<th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">JML BAYAR</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">BANK</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">SETORAN</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
			    			foreach ($trans->result_array() as $row) :?>
			    				<tr>
				    				<td class="text-center border-b"><?= ++$no; ?></td>
				    				<td class="border-b"><?= $row['cos_nama']; ?></td>
				    				<td class="text-center border-b">
				    					<?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
				    				</td>
				    				<td class="text-center border-b"><?= $row['dtl_bank']; ?></td>
				    				<td class="text-center border-b"><?= $row['dtl_status']; ?></td>
				    				<td class="text-center border-b"><?= $row['dtl_stt_stor']; ?></td>
				    				<td class="text-center">
				    					<div class="flex sm:justify-center items-center">
				    						<a href="#" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white modal-setoran" data-kode="<?= $row['dtl_kode']?>" data-bank="<?= $row['dtl_bank']?>" data-jml_setoran="<?= $row['dtl_jml_bayar']?>">
				    							<i data-feather="database" class="w-4 h-4 mr-2"></i> Setor
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
    
<!-- //modal-setoran -->
<div class="modal" id="setoran">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Konfirmasi Tranfer Bank
            </h2>
        </div>
        <form method="post" action="<?= site_url('Admin/setoran')?>">
        	<div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
	            <div class="col-span-12">
	                <label>Bank Tujuan</label>
	                <input type="hidden" name="kode" id="modal-kode">
	                <input type="text" class="input w-full border mt-2 flex-1" id="modal-bank" disabled="BANK">
	            </div>
	            <div class="col-span-12">
	                <label>Jumlah Transfer</label>
	                <input type="text" class="input w-full border mt-2 flex-1" id="modal-jml-tranfer" placeholder="Masukan Jumlah Transfer" name="jml_tranfer">
	            </div>
	        </div>
	        <div class="px-5 py-3 text-center border-t border-gray-200">
	            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
	            <button type="submit" class="button w-20 bg-theme-1 text-white">Setorkan</button>
	        </div>
        </form>
        
    </div>
</div>
<?php $this->load->view('Template/footer'); ?>