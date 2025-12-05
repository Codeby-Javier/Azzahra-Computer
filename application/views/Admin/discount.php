<?php $this->load->view('Template/header'); ?>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="message-square" class="w-6 h-6 inline-block mr-2"></i>Discount</h1>                
                <p>Data Pengajuan Discount</p>
            </div>            
        </header>

<div class="content" style="margin-top: 50px;">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>	
    <div class="intro-y datatable-wrapper box p-5 mt-5">
    	<table class="table table-report table-report--bordered display datatable w-full">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                    <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">TOTAL</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">DISCOUNT</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php
	    			foreach ($discount->result_array() as $row) : ?>
	    				.<tr>
	    					<td class="text-center border-b"><?= ++$no?></td>
		                    <td class="text-center border-b"><?= $row['cos_kode']?></td>
		                    <td class="border-b">
		                    	<div class="font-medium whitespace-no-wrap">
		                    		<?= $row['cos_nama']?>
		                    	</div>
		                    </td>
		                    <td class="text-center border-b">
		                    	<?= "Rp. ".number_format( $row['trans_total'], 0).",-"; ?>
		                    </td>
		                    <td class="text-center border-b">
		                    	<?= "Rp. ".number_format( $row['trans_discount'], 0).",-"; ?>
		                    </td>
		                    <td class="border-b w-5">
		                        <div class="flex sm:justify-center items-center">
		                            <a class="flex items-center text-theme-9 mr-3" href="<?= site_url('Admin/vocher/'.$row['trans_kode'])?>">
		                            	<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Setujui
		                            </a>
		                            <a class="flex items-center text-theme-6 tombol-hapus" href="<?= site_url('Admin/delete_vocher/'.$row['trans_kode'])?>" data-nama="<?= $row['cos_nama']?>">
		                            	<i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Tolak
		                            </a>
		                        </div>
		                    </td>
		                </tr>
	    			<?php endforeach; ?>    			
    		</tbody>
    	</table>
</div>
<?php $this->load->view('Template/footer'); ?>