<?php $this->load->view('Template/header'); ?>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Data Laporan</h1>                
                <p>Laporan Hari ini</p>
            </div>            
        </header>
<div class="content mt-5">
    <div class="intro-y box overflow-hidden mt-5">
        <div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                <div class="text-xl text-theme-1 font-medium">Azzahra Computer Tegal</div>
                <div class="mt-1">Tegal, <?= date('d-F-Y')?></div>
            </div>
        </div>
        <div class="px-5 sm:px-16 py-5">
            <form action="<?= site_url('Export/lap_perhari_excel')?>" method="post">
                <div class="flex justify-end">
                    <button type="submit" class="button text-white bg-theme-1 shadow-md mr-2 flex">
                        <i data-feather="file-text" class="mr-2"></i>Export to Excel
                    </button>
                </div>
            </form>
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
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK BCA</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $jml_DP_bca->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($tot_DP_bca ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK MANDIRI</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $jml_DP_bri->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($tot_DP_bri ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT TUNAI</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $jml_DP_tunai->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($tot_DP_tunai ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
                            </td>
                            <td class="text-right border-b w-32"><?= $jml_lns_tunai->num_rows();?></td>
                            <td class="text-right border-b w-32">
                                <?= "Rp. ".number_format($tot_lns_tunai ?? 0, 0).",-"; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Down Payment</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Bank Transfer</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    <?= "Rp. ".number_format($tot_DP_bca + $tot_DP_bri, 0).",-"; ?>
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - <?= $jml_DP_bca->num_rows() + $jml_DP_bri->num_rows() ;?></div>
            </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Pembayaran Tunai</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Down Payment & Pelunasan</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    <?= "Rp. ".number_format($tot_DP_tunai + $tot_lns_tunai, 0).",-"; ?>
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - <?= $jml_DP_tunai->num_rows() + $jml_lns_tunai->num_rows() ;?></div>
            </div>
        </div>
</div>
<?php $this->load->view('Template/footer'); ?>