<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>
                    <i data-feather="home" class="w-6 h-6 inline-block mr-2"></i>Dashboard</h1>
                <p>Welcome back, here's your business overview</p>
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
	<div class="grid grid-cols-12 gap-6">
        <!-- <div class="login" data-login="<?php echo $this->session->flashdata('login');?>"></div> -->
        <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        General Report
                    </h2>
                    <a href="<?= site_url('Service')?>" class="ml-auto flex text-theme-1"> <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data </a>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                	<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                        	<a href="<?= site_url('Service/cos_baru')?>">
                        		<div class="box p-5">
	                                <div class="flex">
	                                    <i data-feather="user-plus" class="report-box__icon text-theme-10"></i> 
	                                    <div class="ml-auto">
	                                        <div class="report-box__indicator bg-theme-10 tooltip cursor-pointer" title="Transaksi Baru - <?= $cos_baru->num_rows();?>"> </div>
	                                    </div>
	                                </div>
	                                <div class="text-3xl font-bold leading-8 mt-6"><?= $cos_baru->num_rows();?></div>
	                                <div class="text-base text-gray-600 mt-1">Transaksi Baru</div>
	                            </div>
                        	</a>
                            
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                        	<a href="<?= site_url('Service/cos_proses')?>">
	                        	<div class="box p-5">
	                                <div class="flex">
	                                    <i data-feather="user-check" class="report-box__icon text-theme-9"></i> 
	                                    <div class="ml-auto">
	                                        <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="Transaksi diproses - <?= $cos_proses->num_rows();?>"></div>
	                                    </div>
	                                </div>
	                                <div class="text-3xl font-bold leading-8 mt-6"><?= $cos_proses->num_rows();?></div>
	                                <div class="text-base text-gray-600 mt-1">Transaksi diproses</div>
	                            </div>	
                        	</a>
                            
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="<?= site_url('Service/cos_konf')?>">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-feather="phone-outgoing" class="report-box__icon text-theme-13"></i> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator bg-theme-13 tooltip cursor-pointer" title="Konfirmasi - <?= $cos_knf->num_rows();?>"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6"><?= $cos_knf->num_rows();?></div>
                                    <div class="text-base text-gray-600 mt-1">Konfirmasi</div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="<?= site_url('Service/cos_pelunasan')?>">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-feather="credit-card" class="report-box__icon text-theme-6"></i> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator bg-theme-6 tooltip cursor-pointer" title="Pelunasan - <?= $cos_pelunasan->num_rows();?>"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6"><?= $cos_pelunasan->num_rows();?></div>
                                    <div class="text-base text-gray-600 mt-1">Pelunasan</div>
                                </div>
                            </a>                            
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                	<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="<?= site_url('Service/laporan')?>">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-feather="credit-card" class="report-box__icon text-theme-11"></i> 
                                        <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-11 tooltip cursor-pointer" title="DP Non Tunai - <?= "Rp. ".number_format($Dp_NonTunai ?: 0, 0).",-"; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">
                                        <?= "Rp. ".number_format($Dp_NonTunai ?: 0, 0).",-"; ?>
                                    </div>
                                    <div class="text-base text-gray-600 mt-1">DP Non Tunai</div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <i data-feather="dollar-sign" class="report-box__icon text-theme-10"></i> 
                                    <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-10 tooltip cursor-pointer" title="DP Tunai - <?= "Rp. ".number_format($Dp_Tunai ?: 0, 0).",-"; ?>"></div>
                                    </div>
                                </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">
                                        <?= "Rp. ".number_format($Dp_Tunai ?: 0, 0).",-"; ?>
                                    </div>
                                <div class="text-base text-gray-600 mt-1">DP Tunai</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="<?= site_url('Service/laporan')?>">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-feather="layers" class="report-box__icon text-theme-1"></i> 
                                        <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-1 tooltip cursor-pointer" title="BANK BCA - <?= "Rp. ".number_format($bca ?: 0, 0).",-"; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">
                                        <?= "Rp. ".number_format($bca ?: 0, 0).",-"; ?>
                                    </div>
                                    <div class="text-base text-gray-600 mt-1">BANK BCA</div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="<?= site_url('Service/laporan')?>">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-feather="layers" class="report-box__icon text-theme-13"></i> 
                                        <div class="ml-auto">
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">
                                        <?= "Rp. ".number_format($bri ?: 0, 0).",-"; ?>
                                    </div>
                                    <div class="text-base text-gray-600 mt-1">BANK MANDIRI</div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: General Report -->          
            
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