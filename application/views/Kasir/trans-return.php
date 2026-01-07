<?php $this->load->view('Template/header'); ?>
<div class="content">
    <div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
    <div class="gagal" data-gagal="<?php echo $this->session->flashdata('gagal');?>"></div>
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
                            <a href="<?= site_url('Kasir/cari/'.$lap['trans_kode'])?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> 
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
                <div class="col-span-12 sm:col-span-4 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base text-white">Down Payment (DP)</div>
                    <div class="text-theme-25">
                        <?php
                        $dp = $this->db->get_where('transaksi_detail', array('dtl_jenis_bayar'=>'TUNAI','dtl_status'=>'DP'));
                        ?>
                        <?= $dp->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base text-white">Pelunasan</div>
                    <div class="text-theme-25">
                        <?php
                        $lunasan = $this->db->get_where('transaksi_detail', array('dtl_jenis_bayar'=>'TUNAI','dtl_status'=>'PELUNASAN'));
                        ?>
                        <?= $lunasan->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base text-white">Jumlah Customer</div>
                    <div class="text-theme-25">
                        <?= $dp->num_rows() + $lunasan->num_rows() ?> Customer
                    </div>
                </div>
            </div>
            <div class="intro-y datatable-wrapper box p-5 mt-5">
                <h2 class="text-lg font-medium mr-auto">
                    Histori Pembayaran
                </h2>
            	<table class="table table-report table-report--bordered display datatable w-full">
            		<thead>
		    			<tr>
		    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
		    				<th class="border-b-2 text-center whitespace-no-wrap">TOTAL BAYAR</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">JENIS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTION</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
                        $no = 0;
		    			foreach ($custom->result_array() as $row) :
                            $no++;
                         ?>
		    				<tr class="cursor-pointer zoom-in">
                                <td class="text-center border-b"><?= $no?></td>
                                <td class="text-center border-b">
                                    <?php
                                    if ($row['dtl_stt_stor'] == 'Menunggu') { ?>
                                        <div class="text-theme-6">
                                            <?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
                                        </div>
                                    <?php } else { ?>
                                        <?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
                                    <?php } ?>
                                    
                                </td>
                                <td class="text-center border-b">
                                    <?= $row['dtl_jenis_bayar']?>                                
                                </td>
                                <td class="text-center border-b"><?= $row['dtl_status']?></td>
                                <td class="border-b">
                                    <div class="font-medium whitespace-no-wrap">
                                        <?php echo date('d-m-Y',strtotime($row['dtl_tanggal'])) ?>
                                    </div>
                                    <div class="text-gray-600 text-xs whitespace-no-wrap">
                                        JAM : <?= $row['dtl_jam']?>
                                    </div>                              
                                </td>
                                <td class="text-center border-b">
                                    <div class="flex sm:justify-center items-center">
                                        <a href="<?= site_url('Cetak/print_2/'.$row['trans_kode'])?>" target="_blanck" class="button px-2 mr-1 mb-2 bg-theme-6 text-white">
                                            <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
                                        </a>
                                    </div>                                    
                                </td>
                            </tr>
		    				.
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
                    	<a data-toggle="tab" data-target="#return" href="javascript:;" class="flex-1 py-2 rounded-md text-center ">Return Pembayaran</a>                   	
                    </div>
                </div>
            </div>
            <div class="tab-content">
            	<div class="tab-content__pane active" id="details">
                    <div class="box p-5 mt-5">
                        <div class="flex items-center border-b pb-5">
                            <div class="">
                                <div class="text-gray-600">Invoice</div>
                                <div><?= $trans['cos_kode']?></div>
                            </div>
                            <i data-feather="credit-card" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Nama Customer</div>
                                <div><?= $trans['cos_nama']?></div>
                            </div>
                            <i data-feather="user" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Model Unit</div>
                                <div><?= $trans['cos_model']?></div>
                            </div>
                            <i data-feather="monitor" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Alamat</div>
                                <div><p><?= $trans['cos_alamat']?></p></div>
                            </div>
                            <i data-feather="map-pin" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center pt-5">
                            <div class="">
                                <div class="text-gray-600">Tanggal dan waktu</div>
                                <div>
                                    <?php echo date('d-m-Y',strtotime($trans['cos_tanggal'])) ?> 
                                    <?= $trans['cos_jam']?> 
                                </div>
                            </div>
                            <i data-feather="clock" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="return">
                    <div class="pos__ticket box p-2 mt-5">
                        <?php
                        foreach ($tindakan->result_array() as $key ) {?>
                            <a href="javascript:;" class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                                <div class="pos__ticket__item-name truncate mr-1"><?= $key['tdkn_nama']?></div>
                                <div class="text-gray-600">x <?= $key['tdkn_qty']?></div>
                                <div class="ml-auto">
                                    <?= "Rp. ".number_format($key['tdkn_subtot'], 0).",-"; ?>
                                </div>
                            </a>
                        <?php } ?>
                        
                    </div>
                    <form method="post" action="<?= site_url('Kasir/bayar_return')?>">
                        <div class="box p-5 mt-5">
                            <div class="flex">
                                <div class="mr-auto">Total</div>
                                <div>
                                    <?= "Rp. ".number_format($trans['trans_total'], 0).",-"; ?>
                                </div>
                            </div>
                            <div class="flex mt-4">
                                <div class="mr-auto">Discount</div>
                                <div class="text-theme-6">
                                    <?= "Rp. ".number_format($trans['trans_discount'], 0).",-"; ?> 
                                </div>
                            </div>
                            <?php
                            if ($bayar['dtl_stt_stor'] == 'Menunggu') { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div class="text-theme-6"> Menunggu * &nbsp;</div>
                                    <div>
                                        <?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Return Pembayaran</div>
                                    <div class="font-medium text-base">
                                        Rp. 0,-
                                        <input type="hidden" name="lunas" value="0">
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div>
                                        <?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Return Pembayaran</div>
                                    <div class="font-medium text-base">
                                        <?= "Rp. ".number_format($trans['trans_total'] - $trans['trans_discount'] - $bayar['dtl_jml_bayar'], 0).",-"; ?>
                                        <input type="hidden" name="lunas" value="<?= $bayar['dtl_jml_bayar'] - $trans['trans_total'] ?>">
                                    </div>
                                </div>
                            <?php } ?>
                            
                        </div>
                        <div class="flex mt-5">
                            <input type="hidden" name="kode" value="<?= $trans['trans_kode']?>">
                            <button class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 500px;">Return Pembayaran</button>
                        </div>
                    </form>
                    
                </div>               
            </div>
        </div>
        <!-- END: Ticket -->
    </div>
</div>
<?php $this->load->view('Template/footer'); ?>