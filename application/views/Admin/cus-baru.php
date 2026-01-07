<?php $this->load->view('Template/header'); ?>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="user-plus" class="w-6 h-6 inline-block mr-2"></i>Transaksi</h1>                
                <p>Transaksi Baru</p>
            </div>            
        </header>
<div class="content" style="margin-top: 60px;">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>	
    <!-- <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Admin/cus_baru')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Admin/cus_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfirmasi
		            </a>
		            <a href="<?= site_url('Admin/cus_konf_bank')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
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
		    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
		                    <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
		                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
			    			foreach ($trans->result_array() as $row) :?>
			    				<tr>
				    				<td class="text-center border-b"><?= ++$no; ?></td>
				    				<td class="text-center border-b"><?= $row['trans_kode']; ?></td>
				    				<td class="border-b"><?= $row['cos_nama'] ?: 'N/A'; ?></td>
				    				<td class="border-b"><?= $row['cos_alamat'] ?: 'N/A'; ?></td>
				    				<td class="text-center border-b"><?= $row['cos_hp'] ?: 'N/A'; ?></td>
				    				<td class="text-center">
				    					<div class="flex sm:justify-center items-center">
				    						<a href="<?= site_url('Admin/cus_detail/'.$row['trans_kode'])?>" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-7 text-white">
				    							<i data-feather="align-justify" class="w-4 h-4 mr-2"></i> Detail
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
<?php $this->load->view('Template/footer'); ?>