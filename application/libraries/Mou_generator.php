<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Mou_generator {
	
	private $temp_dir;
	private $CI;

	public function __construct()
	{
		$this->CI = get_instance();
		$this->temp_dir = APPPATH . 'cache/mou_temp/';
		
		if (!is_dir($this->temp_dir)) {
			@mkdir($this->temp_dir, 0755, true);
		}
	}

	public function __get($var)
	{
		return $this->CI->$var;
	}

	public function generate($mou_id, $file_name, $lokasi, $tanggal, $customer, $items, $grand_total)
	{
		$pdf_path = $this->temp_dir . $mou_id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $file_name) . '.pdf';
		
		if (file_exists($pdf_path)) {
			return $pdf_path;
		}

		try {
			require_once APPPATH . '../vendor/autoload.php';

			$tanggal_formatted = $this->format_tanggal($tanggal);
			$html = $this->generateHTML($file_name, $lokasi, $tanggal_formatted, $customer, $items, $grand_total);

			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			file_put_contents($pdf_path, $dompdf->output());

			if (file_exists($pdf_path)) {
				return $pdf_path;
			}

			return false;

		} catch (Exception $e) {
			log_message('error', 'Mou generator error: ' . $e->getMessage());
			return false;
		}
	}

	private function generateHTML($file_name, $lokasi, $tanggal, $customer, $items, $grand_total)
	{
		$html = '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page {
	margin: 2cm 2.5cm;
}
body {
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
	font-size: 11pt;
	line-height: 1.3;
}
.page {
	width: 100%;
}
.header {
	text-align: center;
	border-bottom: 2px solid #000;
	padding-bottom: 5px;
	margin-bottom: 15px;
}
.company-name {
	font-size: 14pt;
	font-weight: bold;
	color: #4472C4;
	margin: 0 0 3px 0;
}
.company-sub {
	font-size: 9pt;
	margin: 0 0 3px 0;
	font-weight: normal;
}
.company-address {
	font-size: 9pt;
	margin: 0;
	font-weight: bold;
}
.location-date {
	text-align: right;
	font-size: 11pt;
	margin: 10px 0 15px 0;
}
.recipient {
	margin-bottom: 15px;
	font-size: 11pt;
	line-height: 1.4;
}
.title {
	text-align: center;
	font-size: 12pt;
	font-weight: bold;
	margin: 15px 0;
	text-decoration: underline;
}
.intro {
	font-size: 11pt;
	margin: 10px 0;
	text-align: justify;
}
table {
	width: 100%;
	border-collapse: collapse;
	margin: 10px 0;
	font-size: 10pt;
}
table th {
	background-color: #4472C4;
	color: white;
	border: 1px solid #000;
	padding: 6px 4px;
	text-align: center;
	font-weight: bold;
}
table td {
	border: 1px solid #000;
	padding: 6px 4px;
	vertical-align: top;
}
.no-col {
	width: 40px;
	text-align: center;
}
.spec-col {
	text-align: left;
}
.qty-col {
	width: 50px;
	text-align: center;
}
.harga-col {
	width: 100px;
	text-align: center;
}
.total-col {
	width: 120px;
	text-align: center;
}
.grand-total-row {
	background-color: #D9E2F3;
}
.grand-total-label {
	background-color: #4472C4;
	color: white;
	font-weight: bold;
	text-align: center;
}
.terms {
	font-size: 10pt;
	margin: 15px 0;
	line-height: 1.5;
}
.terms-title {
	font-weight: bold;
	margin-bottom: 5px;
}
.terms ol {
	margin: 5px 0;
	padding-left: 20px;
}
.terms li {
	margin: 3px 0;
}
.signature {
	margin-top: 25px;
	font-size: 11pt;
}
.sig-closing {
	margin-bottom: 60px;
}
.sig-name {
	font-weight: bold;
}
</style>
</head>
<body>
<div class="page">
	<div class="header">
		<div class="company-name">CV AZZAHRA COMPUTER</div>
		<div class="company-sub">AUTHORIZED SERVICE CENTER & INFRASTRUKTUR IT</div>
		<div class="company-address">HEAD OFFICE : RUKO CITRALAND TEGAL BLOK B/11, TEGAL</div>
		<div class="company-address">BRANCH OFFICE : RUKO KRANGGAN CIBUBUR, BLOK RT16/27, Telp.(0283)34.09</div>
	</div>

	<div class="location-date">' . htmlspecialchars($lokasi) . ', ' . htmlspecialchars($tanggal) . '</div>

	<div class="recipient">
		Kepada Yth.<br>
		' . htmlspecialchars($customer) . '<br>
		Di Tempat
	</div>

	<div class="title">SURAT PENAWARAN</div>

	<div class="intro">Dengan hormat,<br><br>
	Kami AZZAHRA COMPUTER & SERVICE CENTER TEGAL, mengajukan penawaran harga sebagai berikut:</div>

	<table>
		<thead>
			<tr>
				<th class="no-col">No</th>
				<th class="spec-col">Spesifikasi</th>
				<th class="qty-col">Qty</th>
				<th class="harga-col">Harga</th>
				<th class="total-col">Total harga</th>
			</tr>
		</thead>
		<tbody>';

		$no = 1;
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$qty = isset($item['qty']) ? (float)$item['qty'] : 0;
				$harga = isset($item['harga']) ? (float)$item['harga'] : 0;
				$total = $qty * $harga;

				$html .= '
			<tr>
				<td class="no-col">' . $no . '</td>
				<td class="spec-col">' . htmlspecialchars($item['spesifikasi'] ?? '') . '</td>
				<td class="qty-col">' . intval($qty) . '</td>
				<td class="harga-col">Rp ' . number_format($harga, 0, ',', '.') . '</td>
				<td class="total-col">Rp ' . number_format($total, 0, ',', '.') . '</td>
			</tr>';
				$no++;
			}
		}

		$html .= '
			<tr class="grand-total-row">
				<td colspan="4" style="text-align: right; padding-right: 10px;">Grand Total</td>
				<td class="grand-total-label">Rp ' . number_format($grand_total, 0, ',', '.') . '</td>
			</tr>
		</tbody>
	</table>

	<div class="terms">
		<div class="terms-title">Ketentuan:</div>
		<ol>
			<li>Semua barang diatas inden 1-2 hari</li>
			<li>Harga diatas sudah teremasuk biaya instansi</li>
			<li>Garansi perangkat selama 2 tahun</li>
			<li>Pembayaran min DP 50% dr total biaya</li>
			<li>Pembayaran bisa di transfer ke Rek <b>BCA No.Rek 0470727705 (ferry juanda)</b></li>
		</ol>
	</div>

	<div class="signature">
		<div class="sig-closing">Hormat Kami,</div>
		<div class="sig-name">(Ferry Juanda.ST)</div>
	</div>
</div>
</body>
</html>';

		return $html;
	}

	private function format_tanggal($tanggal)
	{
		if (empty($tanggal)) {
			return date('d F Y');
		}

		$months = array(
			'January' => 'Januari',
			'February' => 'Februari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember',
		);

		try {
			$date = new DateTime($tanggal);
			$format = $date->format('d F Y');
			foreach ($months as $en => $id) {
				$format = str_replace($en, $id, $format);
			}
			return $format;
		} catch (Exception $e) {
			return $tanggal;
		}
	}
}
