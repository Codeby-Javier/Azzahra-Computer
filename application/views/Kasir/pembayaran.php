<?php $this->load->view('Template/header'); ?>
<?php if (!isset($role)) $role = 'kasir'; ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">                
                <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Pembayaran</h1>
                <p>Manage payment data</p>
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
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Pembayaran
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2">Pembayaran hari ini
            </a> 
            <div class="pos-dropdown dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-gray-700">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="chevron-down"></i> </span>
                </button>
                <div class="pos-dropdown__dropdown-box dropdown-box mt-10 absolute top-0 right-0 z-20">
                    <div class="dropdown-box__content box p-2">
                         <?php
                        foreach ($lap_bayar->result_array() as $lap) :?>
                            <a href="<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/cari/'.$lap['trans_kode'])?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                                <i data-feather="activity" class="w-4 h-4 mr-2"></i> 
                                <span class="truncate"><?= $lap['cos_kode'];?> - <?= $lap['cos_nama'];?></span> 
                            </a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="grid grid-cols-12 gap-5 mt-5">
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran/dp')?>'">
                    <div class="font-medium text-base text-white">Down Payment (DP)</div>
                    <div class="text-theme-25">
                        <?php
                        $dp = $this->db->get_where('transaksi', array('trans_status'=>'Pelunasan'));
                        ?>
                        <?= $dp->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran/lunas')?>'">
                    <div class="font-medium text-base text-white">Lunas</div>
                    <div class="text-theme-25">
                        <?php
                        $lunas = $this->db->get_where('transaksi', array('trans_status'=>'Lunas'));
                        ?>
                        <?= $lunas->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran')?>'">
                    <div class="font-medium text-base text-white">Jumlah Customer</div>
                    <div class="text-theme-25">
                        <?= $dp->num_rows() + $lunas->num_rows() ?> Customer
                    </div>
                </div>
            </div>
            <div class="intro-y datatable-wrapper box p-5 mt-5">
                <h2 class="text-lg font-medium mr-auto">
                    <?php
                    $title_text = 'Data Customer';
                    if (isset($filter)) {
                        if ($filter == 'dp') $title_text = 'Data Customer - Down Payment';
                        elseif ($filter == 'lunas') $title_text = 'Data Customer - Lunas';
                    }
                    echo $title_text;
                    ?>
                </h2>
            	<table class="table table-report table-report--bordered display datatable w-full">
            		<thead>
         <tr>
          <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
          <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
            	         <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">MODEL UNIT</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
         </tr>
        </thead>
        <tbody>
         <?php
         $no = 0;
         foreach ($custom->result_array() as $row) : ?>
		    				<tr class="cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/cari/'.$row['trans_kode'])?>'">
		    					<td class="text-center border-b"><?= ++$no?></td>
		    			              <td class="text-center border-b">
		    			              	<?= $row['cos_kode']?>
		    			              </td>
		    			              <td class="border-b">
		    			              	<div class="font-medium whitespace-no-wrap">
		    			              		<?= $row['cos_nama']?>
		    			              	</div>
		    			                  <div class="text-gray-600 text-xs whitespace-no-wrap">
		    			                  	STATUS : <?= $row['trans_status']?>
		    			                  </div>
		    			              </td>
		    			              <td class="text-center border-b"><?= $row['cos_model']?></td>
		    			              <td class="text-center border-b"><?= $row['cos_status']?></td>
		    			              <td class="border-b">
		    			              	<div class="font-medium whitespace-no-wrap">
		    			              		<?php echo date('d-m-Y',strtotime($row['cos_tanggal'])) ?>
		    			              	</div>
		    			                  <div class="text-gray-600 text-xs whitespace-no-wrap">
		    			                  	JAM : <?= $row['cos_jam']?>
		    			                  </div>
		    			              </td>
		    			          </tr>
		    				
		    			<?php endforeach; ?>    			
		    		</tbody>
            	</table>
            </div>
        </div>
        <!-- END: Item List -->
        <!-- BEGIN: Ticket -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y pr-1">
                <div class="box p-2">
                    <div class="pos__tabs nav-tabs justify-center flex"> 
                    	<a data-toggle="tab" data-target="#details" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Detail</a>
                    	<a data-toggle="tab" data-target="#pelunasan" href="javascript:;" class="flex-1 py-2 rounded-md text-center ">Pelunasan</a> 
                    	<a data-toggle="tab" data-target="#dp" href="javascript:;" class="flex-1 py-2 rounded-md text-center">DP</a>                    	
                    </div>
                </div>
            </div>
            <div class="tab-content">
            	<div class="tab-content__pane active" id="details">
                    <div class="box p-5 mt-5">
                        <div class="flex items-center border-b pb-5">
                            <div class="">
                                <div class="text-gray-600">Invoice</div>
                                <div></div>
                            </div>
                            <i data-feather="credit-card" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Nama Customer</div>
                                <div></div>
                            </div>
                            <i data-feather="user" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Model Unit</div>
                                <div></div>
                            </div>
                            <i data-feather="monitor" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Alamat</div>
                                <div></div>
                            </div>
                            <i data-feather="map-pin" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center pt-5">
                            <div class="">
                                <div class="text-gray-600">Tanggal dan waktu</div>
                                <div></div>
                            </div>
                            <i data-feather="clock" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="pelunasan">
                    <div class="box p-5 mt-5">
                        <div class="flex">
                            <div class="mr-auto">Total</div>
                            <div>Rp.0</div>
                        </div>
                        <div class="flex mt-4">
                            <div class="mr-auto">Discount</div>
                            <div class="text-theme-6">Rp.20</div>
                        </div>
                        <div class="flex mt-4">
                            <div class="mr-auto">Down Payment</div>
                            <div>Rp.0</div>
                        </div>
                        <div class="flex mt-4 pt-4 border-t border-gray-200">
                            <div class="mr-auto font-medium text-base">Pelunasan</div>
                            <div class="font-medium text-base">Rp.0</div>
                        </div>
                    </div>
                    <div class="flex mt-5">
                        <button class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 500px;">Bayar Pelunasan</button>
                    </div>
                </div>
                <div class="tab-content__pane" id="dp">
                	<div class="box flex p-5 mt-5">
                        <div class="w-full relative text-gray-700">
                            <input type="text" class="input input--lg w-full bg-gray-200 pr-10 placeholder-theme-13" placeholder="Jumlah Discount">
                            <i class="w-4 h-4 sm:absolute my-auto inset-y-0 mr-3 right-0" data-feather="send"></i> 
                        </div>
                        <button class="button text-white bg-theme-1 ml-2">Kirim</button>
                    </div>
                    <div class="box p-5 mt-5">
                        <div class="flex">
                            <div class="mr-auto">Total</div>
                            <div>Rp.0</div>
                        </div>
                        <div class="flex mt-4">
                            <div class="mr-auto">Discount</div>
                            <div class="text-theme-6">Rp.20</div>
                        </div>
                        <div class="flex mt-4">
                            <div class="mr-auto">Down Payment</div>
                            <div>Rp.0</div>
                        </div>
                        <div class="flex mt-4 pt-4 border-t border-gray-200">
                            <div class="mr-auto font-medium text-base">Pelunasan</div>
                            <div class="font-medium text-base">Rp.0</div>
                        </div>
                    </div>
                    <div class="flex mt-5">
                        <button class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 500px;">Bayar Pelunasan</button>
                    </div>
                </div>                
            </div>
        </div>
        <!-- END: Ticket -->
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