<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Azzahra-Print</title>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/file/bootstrap/css/bootstrap.min.css" />
</head>
<body>
	<div class="container">
		<table align="center" class="table" style="width: 884px;">
			<tbody>
				<tr>
					<td style="width: 100px;" align="center">
						<img src="<?= base_url('assets/file/foto/logo-azzahra.jpg')?>" width="100px" height="100px">
					</td>
					<td style="width: 290px;" align="left">
						<img src="<?= base_url('assets/file/foto/alamat.jpg')?>" width="350px" height="120px">
					</td>
					<td style="width: 247">
						<p style="height: 5px;"><b>No Invoice</b></p>
						<p style="height: 5px;"><b>Tanggal</b></p>
						<p style="height: 5px;"><b>Customer</b></p>
						<p style="height: 5px;"><b>Unit</b></p>
						<p style="height: 5px;"><b>Total</b></p>
					</td>
					<td style="width: 247">
						<p style="height: 5px;"><b>: <?= $data['cos_kode']?></b></p>
						<p style="height: 5px;"><b>: <?= date('d-m-Y')?> &nbsp;<?= date('H:i:s')?></b>
						</p>
						<p style="height: 5px;"><b>: <?= $data['cos_nama']?></b></p>
						<p style="height: 5px;"><b>: <?= $data['cos_tipe']?></b></p>
						<p style="height: 5px;"><b>: <?= "Rp. ".number_format($data['trans_total'], 0).",-"; ?></b></p>
					</td>					
				</tr>				
			</tbody>
		</table>

		<hr style="border-color: #000000; border-top-width: 3px; height: 1px; width: 884px;">
		<h5 class="text-center"><b>FAKTUR PEMBAYARAN</b></h5>		

		<?php
		if ($bayar != '') : ?>
			<table class="table table-striped" align="center" border="0" style="width: 884px;">
				<thead>
					<tr style="background-color: #F8F8FF;">
						<th style="width: 215px;" class="text-center">TANGGAL</th>
						<th style="width: 430px;">DESCRIPTION</th>
						<th style="width: 215px;" class="text-center">TOTAL</th>					
					</tr>
				</thead>
				<tbody>
					<tr style="background-color: white;">
						<td class="text-center">
							<?php echo date('d-m-Y',strtotime($bayar['dtl_tanggal'])) ?>
						</td>
						<td><?= $bayar['dtl_status']?></td>
						<td class="text-center">
							<?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
						</td>
					</tr>				
				</tbody>
			</table>

			<table border="0" align="center" style="width: 884px;">
				<thead>
					<tr>
						<th class="text-center" style="width: 215px;"></th>
						<th style="width: 430px;"></th>
						<th class="text-center" style="width: 215px;">Kasir</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="height: 60px;"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="text-center"></td>
						<td></td>
						<td class="text-center"><u><?= $this->session->userdata('nama')?></u></td>
					</tr>
				</tbody>
			</table><br>
		<?php endif; ?>

		<h6 class="text-left">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<b><i>Dengan rincian sebagai berikut :</i></b></h6>
		<table border="1" align="center" style="width: 884px;">
			<thead>
				<tr>
					<th class="text-center" style="width: 40px;">No</th>
					<th>Tindakan / Barang</th>
					<th class="text-center" style="width: 135px;">SubTotal</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$noo = 0;
				foreach ($barang->result_array() as $key) {
					$noo++
					?>
					<tr>
						<td class="text-center" style="width: 40px;"><?= $noo;?></td>
						<td><?= $key['tdkn_nama'];?></td>
						<td class="text-left" style="width: 135px;">
							<?= "Rp. ".number_format($key['tdkn_subtot'], 0).",-"; ?>
						</td>
					</tr>									
				<?php } ?>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td class="text-right">Total &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_total'], 0).",-"; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<h6 class="text-left">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<b><i>Anda melakukan pembayaran pada :</i></b></h6>
		<table border="1" align="center" style="width: 884px;">
			<thead>
				<tr>
					<th class="text-center" style="width: 40px;">No</th>
					<th class="text-center" style="width: 110px;">Tanggal</th>
					<th>Description</th>
					<th class="text-center" style="width: 135px;">SubTotal</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 0;
				$sum_bayar = 0;
				foreach ($pembayaran->result_array() as $row) {
					$no++
					?>
					<tr>
						<td class="text-center" style="width: 40px;"><?= $no;?></td>
						<td class="text-center" style="width: 110px;">
							<?php echo date('d-m-Y',strtotime($row['dtl_tanggal'])) ?>
						</td>
						<td><?= $row['dtl_status']?>/ <?= $row['dtl_bank']?></td>
						<td class="text-left" style="width: 135px;">
							<?php
							if ($row['dtl_stt_stor'] == 'Menunggu') { ?>
								<div style="background-color: red;">
									<?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
								</div>
							<?php } else { ?>
								<?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
							<?php } ?>
							
						</td>
					</tr>									
				<?php 
				$sum_bayar += $row['dtl_jml_bayar'];
				} ?>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td style="width: 110px;"></td>
					<td class="text-right">Discount &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_discount'], 0).",-"; ?>
					</td>
				</tr>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td style="width: 110px;"></td>
					<td class="text-right">Total Pembayaran &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_total'] - $data['trans_discount'], 0).",-"; ?>
					</td>
				</tr>
			</tbody>
		</table>

		

		<br>

		<p><strong>Potong di sini -----------------------------------------------------------------------------------------------------------------------------------------------</strong></p>

		<table align="center" class="table" style="width: 884px;">
			<tbody>
				<tr>
					<td style="width: 100px;" align="center">
						<img src="<?= base_url('assets/file/foto/logo-azzahra.jpg')?>" width="100px" height="100px">
					</td>
					<td style="width: 290px;" align="left">
						<img src="<?= base_url('assets/file/foto/alamat.jpg')?>" width="350px" height="120px">
					</td>
					<td style="width: 247">
						<p style="height: 5px;"><b>No Invoice</b></p>
						<p style="height: 5px;"><b>Tanggal</b></p>
						<p style="height: 5px;"><b>Customer</b></p>
						<p style="height: 5px;"><b>Unit</b></p>
						<p style="height: 5px;"><b>Total</b></p>
					</td>
					<td style="width: 247">
						<p style="height: 5px;"><b>: <?= $data['cos_kode']?></b></p>
						<p style="height: 5px;"><b>: <?= date('d-m-Y')?> &nbsp;<?= date('H:i:s')?></b>
						</p>
						<p style="height: 5px;"><b>: <?= $data['cos_nama']?></b></p>
						<p style="height: 5px;"><b>: <?= $data['cos_tipe']?></b></p>
						<p style="height: 5px;"><b>: <?= "Rp. ".number_format($data['trans_total'], 0).",-"; ?></b></p>
					</td>						
				</tr>				
			</tbody>
		</table>

		<hr style="border-color: #000000; border-top-width: 3px; height: 1px; width: 884px;">
		<h5 class="text-center"><b>FAKTUR PEMBAYARAN</b></h5>

		<?php
		if ($bayar != '') : ?>
			<table class="table table-striped" align="center" border="0" style="width: 884px;">
				<thead>
					<tr style="background-color: #F8F8FF;">
						<th style="width: 215px;" class="text-center">TANGGAL</th>
						<th style="width: 430px;">DESCRIPTION</th>
						<th style="width: 215px;" class="text-center">TOTAL</th>					
					</tr>
				</thead>
				<tbody>
					<tr style="background-color: white;">
						<td class="text-center">
							<?php echo date('d-m-Y',strtotime($bayar['dtl_tanggal'])) ?>
						</td>
						<td><?= $bayar['dtl_status']?></td>
						<td class="text-center">
							<?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
						</td>
					</tr>				
				</tbody>
			</table>

			<table border="0" align="center" style="width: 884px;">
				<thead>
					<tr>
						<th class="text-center" style="width: 215px;"></th>
						<th style="width: 430px;"></th>
						<th class="text-center" style="width: 215px;">Kasir</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="height: 60px;"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="text-center"></td>
						<td></td>
						<td class="text-center"><u><?= $this->session->userdata('nama')?></u></td>
					</tr>
				</tbody>
			</table><br>
		<?php endif; ?>

		<h6 class="text-left">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<b><i>Dengan rincian sebagai berikut :</i></b></h6>
		<table border="1" align="center" style="width: 884px;">
			<thead>
				<tr>
					<th class="text-center" style="width: 40px;">No</th>
					<th>Tindakan / Barang</th>
					<th class="text-center" style="width: 135px;">SubTotal</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$noo = 0;
				foreach ($barang->result_array() as $key) {
					$noo++
					?>
					<tr>
						<td class="text-center" style="width: 40px;"><?= $noo;?></td>
						<td><?= $key['tdkn_nama'];?></td>
						<td class="text-left" style="width: 135px;">
							<?= "Rp. ".number_format($key['tdkn_subtot'], 0).",-"; ?>
						</td>
					</tr>									
				<?php } ?>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td class="text-right">Total &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_total'], 0).",-"; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<h6 class="text-left">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<b><i>Anda melakukan pembayaran pada :</i></b></h6>
		<table border="1" align="center" style="width: 884px;">
			<thead>
				<tr>
					<th class="text-center" style="width: 40px;">No</th>
					<th class="text-center" style="width: 110px;">Tanggal</th>
					<th>Description</th>
					<th class="text-center" style="width: 135px;">SubTotal</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 0;
				$sum_bayar = 0;
				foreach ($pembayaran->result_array() as $row) {
					$no++
					?>
					<tr>
						<td class="text-center" style="width: 40px;"><?= $no;?></td>
						<td class="text-center" style="width: 110px;">
							<?php echo date('d-m-Y',strtotime($row['dtl_tanggal'])) ?>
						</td>
						<td><?= $row['dtl_status']?>/ <?= $row['dtl_bank']?></td>
						<td class="text-left" style="width: 135px;">
							<?php
							if ($row['dtl_stt_stor'] == 'Menunggu') { ?>
								<div style="background-color: red;">
									<?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
								</div>
							<?php } else { ?>
								<?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
							<?php } ?>
							
						</td>
					</tr>									
				<?php 
				$sum_bayar += $row['dtl_jml_bayar'];
				} ?>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td style="width: 110px;"></td>
					<td class="text-right">Discount &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_discount'], 0).",-"; ?>
					</td>
				</tr>
				<tr>
					<td class="text-center" style="width: 40px;"></td>
					<td style="width: 110px;"></td>
					<td class="text-right">Total Pembayaran &nbsp;</td>
					<td class="text-left" style="width: 135px;">
						<?= "Rp. ".number_format($data['trans_total'] - $data['trans_discount'], 0).",-"; ?>
					</td>
				</tr>
			</tbody>
		</table>

		

	</div>
	


<script src="<?php echo base_url();?>assets/file/bootstrap/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript">
		window.print();
	</script> -->
</body>
</html>