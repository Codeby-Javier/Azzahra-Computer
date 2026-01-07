<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1> <i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Laporan</h1>
                <p>Daily Report</p>
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
            Laporan Hari Ini
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="button text-white bg-theme-1 shadow-md mr-2">Print</button>
            <div class="dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-gray-700">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                </button>
                <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                    <div class="dropdown-box__content box p-2">
                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Export Word </a>
                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Export PDF </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box overflow-hidden mt-5">
    	<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                <div class="text-xl text-theme-1 font-medium"><?= $cs['kry_nama']?></div>
                <div class="mt-1">Tegal, <?= date('d-F-Y')?></div>
            </div>
        </div>
        <div class="px-5 sm:px-16 py-10 sm:py-20">
	    	<div class="overflow-x-auto">
	    		<table class="table">
	    			<thead>
	    				<tr>
	    					<th class="border-b-2 whitespace-no-wrap">DESCRIPTION</th>
	    					<th class="border-b-2 text-right whitespace-no-wrap">JUMLAH</th>
	    					<th class="border-b-2 text-right whitespace-no-wrap">SUBTOTAL</th>
	    				</tr>
	    			</thead>
	    			<tbody>
	    				<tr>
	    					<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">BANK BCA</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $jml_bca->num_rows();?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_bca ?: 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b ">
	    						<div class="font-medium whitespace-no-wrap">BANK MANDIRI</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $jml_bri->num_rows();?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_bri ?: 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Bank Transfer</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Down Payment</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                	<?= "Rp. ".number_format($dp ?: 0, 0).",-"; ?>
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - <?= $jml_dp->num_rows();?></div>
            </div>
        </div>
    </div>

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
