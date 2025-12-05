<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>Laporan Hari Ini</h1>
                <p>Daily report</p>
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
    </div>
    <div class="intro-y box overflow-hidden mt-5">
    	<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                <div class="text-xl text-theme-1 font-medium"><?= $kasir['kry_nama']?></div>
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
	    						<div class="font-medium whitespace-no-wrap">Down Payment</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $dp->num_rows();?></td>
	    					<td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($sum_dp ?? 0, 0).",-"; ?>
	    					</td>
	    				</tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">Pelunasan</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $lunas->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($sum_lunas ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">Return Pembayaran</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $return->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($sum_return ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600"><?= $kasir['kry_nama']?></div>
                <div class="text-lg text-theme-1 font-medium mt-2">TTD</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total yang di setorkan</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                	<?= "Rp. ".number_format($sum_dp + $sum_lunas - $sum_return, 0).",-"; ?>
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - <?= $dp->num_rows() + $lunas->num_rows();?></div>
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