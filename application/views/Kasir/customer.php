<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>Data Customer</h1>
                <p>Manage customer data</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
	<div class="login" data-login="<?php echo $this->session->flashdata('login');?>"></div>
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Customer
        </h2>
    </div>
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
	    			foreach ($custom->result_array() as $row) : ?>
	    				<tr>
	    					<td class="text-center border-b"><?= ++$no?></td>
		                    <td class="text-center border-b"><?= $row['cos_kode']?></td>
		                    <td class="border-b">
		                    	<div class="font-medium whitespace-no-wrap">
		                    		<?= $row['cos_nama']?>
		                    	</div>
		                        <div class="text-gray-600 text-xs whitespace-no-wrap">
		                        	<?php
		                        	if ($row['trans_status'] == 'Cencel' || $row['trans_status'] == 'Return') { ?>
		                        		<div class="text-theme-6">
		                        			STATUS : <?= $row['trans_status']?>
		                        		</div>
		                        	<?php } else { ?>
		                        		STATUS : <?= $row['trans_status']?>
		                        	<?php }?>
		                        	
		                        </div>
		                    </td>
		                    <td class="border-b"><?= $row['cos_alamat']?></td>
		                    <td class="text-center border-b"><?= $row['cos_hp']?></td>
		                    <td class="border-b">
		                    	<div class="font-medium whitespace-no-wrap">
		                    		<?php echo date('d-m-Y',strtotime($row['cos_tanggal'])) ?>
		                    	</div>
		                        <div class="text-gray-600 text-xs whitespace-no-wrap">
		                        	JAM : <?= $row['cos_jam']?>
		                        </div>		                    	
		                    </td>
		                    <td class="border-b w-5">
		                        <div class="flex sm:justify-center items-center">
		                        	<?php
		                        	if ($row['trans_status'] == 'Return') { ?>
		                        		<a href="<?= site_url('Kasir/trans_return/'.$row['trans_kode'])?>" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
			    							<i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Return
			    						</a>
		                        	<?php } else { ?>
		                        		<a href="<?= site_url('Kasir/cari/'.$row['trans_kode'])?>" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white">
			    							<i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Bayar
			    						</a>
		                        	<?php } ?>		                            
		                        </div>
		                    </td>
		                </tr>
	    			<?php endforeach; ?>    			
    		</tbody>
    	</table>
       </div>
   </main>
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

</script>

<?php $this->load->view('Template/footer'); ?>