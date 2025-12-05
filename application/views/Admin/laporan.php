<?php $this->load->view('Template/header'); ?>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Data Laporan</h1>                
                <p>Laporan per tanggal</p>
            </div>            
        </header>
	<div class="content" style="margin-top: 50px">	
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
        	<form action="<?= site_url('Export/lap_excel')?>" method="post">
	            <div class="intro-y flex flex-col-reverse sm:flex-row items-center">           	
            		<div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700" data-feather="calendar"></i> 
	                    <input type="date" class="input w-full sm:w-64 box px-10 text-gray-700 placeholder-theme-13" placeholder="Search tanggal" name="tgl_awal" required="" value="<?= $tgl_awal?>">                    
	                </div>
	                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    Sampai                    
	                </div>
	                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700" data-feather="calendar"></i> 
	                    <input type="date" class="input w-full sm:w-64 box px-10 text-gray-700 placeholder-theme-13" placeholder="Search tanggal" name="tgl_akhir" required="" value="<?= $tgl_akhir?>">
	                    
	                </div>
	                <div class="w-full sm:w-auto flex">
						<input type="hidden" name="tgl_1" value="<?= $tgl_awal?>">
		        		<input type="hidden" name="tgl_2" value="<?= $tgl_akhir?>">
	                    <button type="submit" class="button text-white bg-theme-1 shadow-md mr-2 flex"><i data-feather="file-text"></i> &nbsp;Export to Excel</button>
	                </div>	            	                
	            </div>
            </form>		        	
            <div class="intro-y inbox box mt-5">
            	<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
		            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
		            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
		                <div class="text-xl text-theme-1 font-medium">Azzahra Computer Tegal</div>
		                <div class="mt-1">Tanggal, <?php echo date('d-m-Y',strtotime($tgl_awal)) ?> Sampai <?php echo date('d-m-Y',strtotime($tgl_akhir)) ?></div>
		            </div>
		        </div>
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
	                                <div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BCA</div>
	                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
	                            </td>
	                            <td class="text-right border-b w-32"><?= $jml_DP_bca->num_rows();?></td>
	                            <td class="text-right border-b w-32">
	                                <?= "Rp. ".number_format($tot_DP_bca, 0).",-"; ?>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td class="border-b">
	                                <div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BRI</div>
	                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
	                            </td>
	                            <td class="text-right border-b w-32"><?= $jml_DP_bri->num_rows();?></td>
	                            <td class="text-right border-b w-32">
	                                <?= "Rp. ".number_format($tot_DP_bri, 0).",-"; ?>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td class="border-b">
	                                <div class="font-medium whitespace-no-wrap">DOWN PATMENT TUNAI</div>
	                            </td>
	                            <td class="text-right border-b w-32"><?= $jml_DP_tunai->num_rows();?></td>
	                            <td class="text-right border-b w-32">
	                                <?= "Rp. ".number_format($tot_DP_tunai, 0).",-"; ?>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td class="border-b">
	                                <div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
	                            </td>
	                            <td class="text-right border-b w-32"><?= $jml_lns_tunai->num_rows();?></td>
	                            <td class="text-right border-b w-32">
	                                <?= "Rp. ".number_format($tot_lns_tunai, 0).",-"; ?>
	                            </td>
	                        </tr>
	                    </tbody>
	                </table>
	            </div>
	            <div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
		            <div class="text-center sm:text-left mt-10 sm:mt-0">
		                <div class="text-base text-gray-600">Bank Transfer</div>
		                <div class="text-lg text-theme-1 font-medium mt-2">Down Payment</div>
		                <div class="mt-1">Azzahra Computer Tegal</div>
		            </div>
		            <div class="text-center sm:text-right sm:ml-auto">
		                <div class="text-base text-gray-600">Total</div>
		                <div class="text-xl text-theme-1 font-medium mt-2">
		                    <?= "Rp. ".number_format($tot_tranfer, 0).",-"; ?>
		                </div>
		                <div class="mt-1 tetx-xs">Dengan Total Customer - <?= $jml_setor->num_rows(); ?></div>
		            </div>
		        </div>
	            <div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
		            <div class="text-center sm:text-left mt-10 sm:mt-0">
		                <div class="text-base text-gray-600">Bank Transfer</div>
		                <div class="text-lg text-theme-1 font-medium mt-2">Yang Belum disetorkan</div>
		                <div class="mt-1">Azzahra Computer Tegal</div>
		            </div>
		            <div class="text-center sm:text-right sm:ml-auto">
		                <div class="text-base text-gray-600">Total</div>
		                <div class="text-xl text-theme-1 font-medium mt-2">
		                    <?= "Rp. ".number_format($blm_setor, 0).",-"; ?>
		                </div>
		                <div class="mt-1 tetx-xs">Dengan Total Customer - <?= $jml_setor->num_rows(); ?></div>
		            </div>
		        </div>
		        <div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
		            <div class="text-center sm:text-left mt-10 sm:mt-0">
		                <div class="text-base text-gray-600">Pembayaran Tunai</div>
		                <div class="text-lg text-theme-1 font-medium mt-2">DP dan Pelunasan</div>
		                <div class="mt-1">Azzahra Computer Tegal</div>
		            </div>
		            <div class="text-center sm:text-right sm:ml-auto">
		                <div class="text-base text-gray-600">Total</div>
		                <div class="text-xl text-theme-1 font-medium mt-2">
		                    <?= "Rp. ".number_format($tot_tunai, 0).",-"; ?>
		                </div>
		                <div class="mt-1 tetx-xs">Dengan Total Customer - <?= $jml_tunai->num_rows(); ?></div>
		            </div>
		        </div>
		      
            </div>
        </div>
</div>
<?php $this->load->view('Template/footer'); ?>