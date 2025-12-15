<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mou extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_mou');
		$this->load->config('mou_config');
		if ($this->session->userdata('masuk') != TRUE) {
			$url = base_url();
			redirect($url);
		}

		// Cek role - hanya Admin dan Customer Service yang boleh akses
		$level = $this->session->userdata('level');
		if (!in_array($level, array('Admin', 'Customer Service'))) {
			$this->session->set_flashdata('gagal', 'Anda tidak memiliki akses ke fitur MOU');
			redirect('Auth'); // Atau dashboard masing-masing jika perlu, tapi Auth safe fallback
		}
	}

	public function index()
	{
		// Jika tabel belum ada, tampilkan instruksi
		if (!$this->db->table_exists('mou')) {
			$data = array(
				'title' => 'Mou',
				'table_exists' => false,
				'mou_list' => null,
				'links' => '',
				'show_rekap' => false
			);
			$this->load->view('Mou/index', $data);
			return;
		}

		// Cek apakah menampilkan rekap atau daftar mou
		$view = $this->input->get('view');

		if ($view === 'rekap') {
			// Tampilkan rekap MOU
			$this->show_rekap_in_index();
			return;
		}

		// Pagination
		$this->load->library('pagination');

		$config['base_url'] = site_url('Mou/index');
		$config['total_rows'] = $this->M_mou->count_all_mou();
		$config['per_page'] = 25;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="pagination"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$level = $this->session->userdata('level');
		$show_rekap_button = ($level === 'Admin');

		$data = array(
			'title' => 'Mou',
			'table_exists' => true,
			'mou_list' => $this->M_mou->get_all_mou($config['per_page'], $page),
			'links' => $this->pagination->create_links(),
			'show_rekap' => false,
			'show_rekap_button' => $show_rekap_button
		);

		$this->load->view('Mou/index', $data);
	}

	// Function untuk menampilkan rekap di halaman index
	private function show_rekap_in_index()
	{
		// Get filters from GET
		$tanggal_mulai = $this->input->get('tanggal_mulai');
		$tanggal_selesai = $this->input->get('tanggal_selesai');
		$lokasi = $this->input->get('lokasi');
		$customer = $this->input->get('customer');
		$kry_kode = $this->input->get('kry_kode');

		// Build filters array
		$filters = array(
			'tanggal_mulai' => $tanggal_mulai,
			'tanggal_selesai' => $tanggal_selesai,
			'lokasi' => $lokasi,
			'customer' => $customer,
			'kry_kode' => $kry_kode
		);

		// Get data from model
		$detail_list = $this->M_mou->get_rekap_mou($filters);
		$summary = $this->M_mou->get_rekap_summary($filters);
		$per_lokasi = $this->M_mou->get_rekap_per_lokasi($filters);
		$per_customer = $this->M_mou->get_rekap_per_customer($filters);
		$lokasi_list = $this->M_mou->get_distinct_lokasi();
		$karyawan_list = $this->M_mou->get_all_karyawan();

		// Prepare data
		$data = array(
			'title' => 'Rekap MOU',
			'table_exists' => true,
			'show_rekap' => true,
			'filters' => $filters,
			'detail_list' => $detail_list,
			'summary' => $summary,
			'per_lokasi' => $per_lokasi,
			'per_customer' => $per_customer,
			'lokasi_list' => $lokasi_list,
			'karyawan_list' => $karyawan_list
		);

		$this->load->view('Mou/index', $data);
	}

	// Halaman form (tanpa modal)
	public function create_form()
	{
		$table_exists = $this->db->table_exists('mou');
		$data = array(
			'title' => 'Buat Mou',
			'table_exists' => $table_exists,
			'google_doc_url' => $this->config->item('mou_google_doc_url')
		);
		$this->load->view('Mou/create', $data);
	}

	public function create()
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Tabel database belum dibuat. Silakan jalankan SQL dari file mou_database.sql']);
				return;
			}
			$this->session->set_flashdata('gagal', 'Tabel database belum dibuat. Jalankan SQL mou_database.sql');
			redirect('Mou/create_form');
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('file_name', 'Nama File', 'required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('customer', 'Customer', 'required');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => validation_errors()]);
			} else {
				$this->session->set_flashdata('gagal', validation_errors());
				redirect('Mou/create_form');
			}
			return;
		}

		// Get form data
		$file_name = $this->input->post('file_name');
		$lokasi = $this->input->post('lokasi');
		$tanggal = $this->input->post('tanggal');
		$customer = $this->input->post('customer');
		$items = json_decode($this->input->post('items'), true);

		if (empty($items) || count($items) == 0) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Minimal harus ada 1 item']);
			} else {
				$this->session->set_flashdata('gagal', 'Minimal harus ada 1 item');
				redirect('Mou/create_form');
			}
			return;
		}

		// Calculate grand total
		$grand_total = 0;
		$items_processed = array();
		foreach ($items as $item) {
			$qty = floatval($item['qty']);
			// Remove all dots and commas from price string to get raw number
			$harga = floatval(str_replace(['.', ','], '', $item['harga']));
			$total = $qty * $harga;
			$grand_total += $total;

			// Store processed items with clean numeric values
			$items_processed[] = array(
				'spesifikasi' => $item['spesifikasi'],
				'qty' => $qty,
				'harga' => $harga
			);
		}

		// Save to database
		$mou_data = array(
			'file_name' => $file_name,
			'lokasi' => $lokasi,
			'tanggal' => $tanggal,
			'customer' => $customer,
			'grand_total' => $grand_total,
			'kry_kode' => $this->session->userdata('kode'),
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->trans_start();

		$this->M_mou->save_mou($mou_data);
		$mou_id = $this->db->insert_id();

		// Save items
		$item_no = 1;
		foreach ($items_processed as $item) {
			$qty = $item['qty'];
			$harga = $item['harga'];
			$total = $qty * $harga;

			$item_data = array(
				'mou_id' => $mou_id,
				'item_no' => $item_no,
				'spesifikasi' => $item['spesifikasi'],
				'qty' => $qty,
				'harga' => $harga,
				'total' => $total
			);
			$this->db->insert('mou_items', $item_data);
			$item_no++;
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
			} else {
				$this->session->set_flashdata('gagal', 'Gagal menyimpan data');
				redirect('Mou/create_form');
			}
			return;
		}

		// Generate PDF
		$this->load->library('Mou_generator');
		$pdf_path = $this->mou_generator->generate($mou_id, $file_name, $lokasi, $tanggal, $customer, $items_processed, $grand_total);

		if ($pdf_path) {
			if ($this->input->is_ajax_request()) {
				echo json_encode([
					'status' => 'success',
					'message' => 'Mou berhasil dibuat',
					'pdf_url' => base_url('Mou/download/' . $mou_id)
				]);
			} else {
				$this->session->set_flashdata('sukses', 'Mou berhasil dibuat');
				redirect('Mou/download/' . $mou_id);
			}
		} else {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Gagal generate PDF']);
			} else {
				$this->session->set_flashdata('gagal', 'Gagal generate PDF');
				redirect('Mou/create_form');
			}
		}
	}

	public function edit($mou_id)
	{
		if (!$this->db->table_exists('mou')) {
			redirect('Mou');
		}

		$mou = $this->M_mou->get_mou_by_id($mou_id)->row_array();
		if (!$mou) {
			$this->session->set_flashdata('gagal', 'Data MOU tidak ditemukan');
			redirect('Mou');
		}

		$items = $this->M_mou->get_mou_items($mou_id)->result_array();

		$data = array(
			'title' => 'Edit Mou',
			'mou' => $mou,
			'items' => $items,
			'google_doc_url' => $this->config->item('mou_google_doc_url')
		);

		$this->load->view('Mou/edit', $data);
	}

	public function update($mou_id)
	{
		// Disable error display untuk AJAX
		$is_ajax = $this->input->is_ajax_request() || $this->input->method() === 'post';
		
		if ($is_ajax) {
			// Clear any output buffer
			if (ob_get_level()) {
				ob_clean();
			}
			header('Content-Type: application/json; charset=utf-8');
		}

		if (!$this->db->table_exists('mou')) {
			if ($is_ajax) {
				echo json_encode(['status' => 'error', 'message' => 'Tabel database tidak ditemukan']);
				exit;
			}
			redirect('Mou');
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('file_name', 'Nama File', 'required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('customer', 'Customer', 'required');

		if ($this->form_validation->run() == FALSE) {
			$is_ajax = $this->input->is_ajax_request() || $this->input->method() === 'post';
			if ($is_ajax) {
				echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
				exit;
			} else {
				$this->session->set_flashdata('gagal', validation_errors());
				redirect('Mou/edit/' . $mou_id);
			}
		}

		// Get form data
		$file_name = $this->input->post('file_name');
		$lokasi = $this->input->post('lokasi');
		$tanggal = $this->input->post('tanggal');
		$customer = $this->input->post('customer');
		$items = json_decode($this->input->post('items'), true);

		if (empty($items) || count($items) == 0) {
			$is_ajax = $this->input->is_ajax_request() || $this->input->method() === 'post';
			if ($is_ajax) {
				echo json_encode(['status' => 'error', 'message' => 'Minimal harus ada 1 item']);
				exit;
			} else {
				$this->session->set_flashdata('gagal', 'Minimal harus ada 1 item');
				redirect('Mou/edit/' . $mou_id);
			}
		}

		// Calculate grand total and process items
		$grand_total = 0;
		$items_processed = array();
		foreach ($items as $item) {
			$qty = floatval($item['qty']);
			// Remove dots/commas for price logic
			$harga_raw = preg_replace('/[^\d]/', '', $item['harga']);
			$harga = floatval($harga_raw);

			$total = $qty * $harga;
			$grand_total += $total;

			$items_processed[] = array(
				'spesifikasi' => $item['spesifikasi'],
				'qty' => $qty,
				'harga' => $harga
			);
		}

		// Update Database
		$this->db->trans_start();

		// Update main table
		$update_data = [
			'file_name' => $file_name,
			'lokasi' => $lokasi,
			'tanggal' => $tanggal,
			'customer' => $customer,
			'grand_total' => $grand_total
		];
		$this->db->where('mou_id', $mou_id);
		$this->db->update('mou', $update_data);

		// Delete old items
		$this->db->delete('mou_items', ['mou_id' => $mou_id]);

		// Insert new items
		$item_no = 1;
		foreach ($items_processed as $item) {
			$item_data = array(
				'mou_id' => $mou_id,
				'item_no' => $item_no,
				'spesifikasi' => $item['spesifikasi'],
				'qty' => $item['qty'],
				'harga' => $item['harga'],
				'total' => $item['qty'] * $item['harga']
			);
			$this->db->insert('mou_items', $item_data);
			$item_no++;
		}

		$this->db->trans_complete();

		$is_ajax = $this->input->is_ajax_request() || $this->input->method() === 'post';

		if ($this->db->trans_status() === FALSE) {
			if ($is_ajax) {
				echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data ke database']);
				exit;
			} else {
				$this->session->set_flashdata('gagal', 'Gagal mengupdate data');
				redirect('Mou/edit/' . $mou_id);
			}
		}

		// Hapus PDF lama agar saat download akan di-generate ulang dengan data terbaru
		$files = glob(APPPATH . 'cache/mou_temp/' . $mou_id . '_*.pdf');
		foreach ($files as $file) {
			if (is_file($file)) {
				@unlink($file);
			}
		}

		// Hanya return success, PDF akan di-generate saat user klik download
		if ($is_ajax) {
			echo json_encode([
				'status' => 'success',
				'message' => 'Data MOU berhasil disimpan',
				'mou_id' => $mou_id
			]);
			exit;
		} else {
			$this->session->set_flashdata('sukses', 'Data MOU berhasil disimpan');
			redirect('Mou/edit/' . $mou_id);
		}
	}

	public function download($mou_id)
	{
		$mou = $this->M_mou->get_mou_by_id($mou_id)->row_array();
		if (!$mou) {
			show_404();
			return;
		}

		$safe_filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $mou['file_name']);
		$pdf_path = APPPATH . 'cache/mou_temp/' . $mou_id . '_' . $safe_filename . '.pdf';

		if (!file_exists($pdf_path)) {
			// Regenerate if not exists
			$items = $this->M_mou->get_mou_items($mou_id)->result_array();
			$items_formatted = array();
			foreach ($items as $item) {
				$items_formatted[] = array(
					'spesifikasi' => $item['spesifikasi'],
					'qty' => $item['qty'],
					'harga' => $item['harga']
				);
			}

			$this->load->library('Mou_generator');
			$pdf_path = $this->mou_generator->generate($mou_id, $mou['file_name'], $mou['lokasi'], $mou['tanggal'], $mou['customer'], $items_formatted, $mou['grand_total']);
		}

		if (file_exists($pdf_path)) {
			$download_filename = $mou['file_name'] . '.pdf';
			// Sanitize filename for download
			$download_filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $download_filename);

			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="' . $download_filename . '"');
			header('Content-Length: ' . filesize($pdf_path));
			readfile($pdf_path);
			exit;
		} else {
			show_error('File PDF tidak ditemukan. Silakan coba buat ulang Mou.');
		}
	}

	// Delete Mou by ID
	public function delete($mou_id)
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Tabel database tidak ditemukan']);
			} else {
				$this->session->set_flashdata('gagal', 'Tabel database tidak ditemukan');
				redirect('Mou');
			}
			return;
		}

		$mou = $this->M_mou->get_mou_by_id($mou_id)->row_array();
		if (!$mou) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Data MOU tidak ditemukan']);
			} else {
				$this->session->set_flashdata('gagal', 'Data MOU tidak ditemukan');
				redirect('Mou');
			}
			return;
		}

		// Delete PDF file from cache
		$safe_filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $mou['file_name']);
		$pdf_path = APPPATH . 'cache/mou_temp/' . $mou_id . '_' . $safe_filename . '.pdf';
		if (file_exists($pdf_path)) {
			@unlink($pdf_path);
		}

		// Delete from database (transaction)
		$this->db->trans_start();

		// Delete items first (foreign key)
		$this->M_mou->delete_mou_items($mou_id);

		// Delete mou record
		$this->M_mou->delete_mou($mou_id);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
			} else {
				$this->session->set_flashdata('gagal', 'Gagal menghapus data');
				redirect('Mou');
			}
			return;
		}

		if ($this->input->is_ajax_request()) {
			echo json_encode(['status' => 'success', 'message' => 'Data MOU berhasil dihapus']);
		} else {
			$this->session->set_flashdata('sukses', 'Data MOU berhasil dihapus');
			redirect('Mou');
		}
	}

	// Cleanup old MOU data, keep only last N records
	public function cleanup_old_data($keep_count = 2)
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			echo json_encode(['status' => 'error', 'message' => 'Tabel database tidak ditemukan']);
			return;
		}

		// Get total count
		$total_count = $this->M_mou->count_all_mou();

		if ($total_count <= $keep_count) {
			echo json_encode(['status' => 'info', 'message' => 'Data sudah sesuai atau kurang dari ' . $keep_count . ' record']);
			return;
		}

		// Get MOUs to delete (all except the latest $keep_count)
		$mous_to_delete = $this->M_mou->get_old_mou($total_count - $keep_count);

		$deleted_count = 0;
		foreach ($mous_to_delete->result_array() as $mou) {
			// Delete PDF file from cache
			$safe_filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $mou['file_name']);
			$pdf_path = APPPATH . 'cache/mou_temp/' . $mou['mou_id'] . '_' . $safe_filename . '.pdf';
			if (file_exists($pdf_path)) {
				@unlink($pdf_path);
			}

			// Delete from database
			$this->db->trans_start();
			$this->M_mou->delete_mou_items($mou['mou_id']);
			$this->M_mou->delete_mou($mou['mou_id']);
			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$deleted_count++;
			}
		}

		echo json_encode([
			'status' => 'success',
			'message' => 'Berhasil menghapus ' . $deleted_count . ' data MOU lama. Tersisa ' . $keep_count . ' data terbaru.',
			'deleted_count' => $deleted_count,
			'remaining_count' => $keep_count
		]);
	}

	// Rekap MOU Page (redirect to index with view=rekap)
	public function rekap()
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			redirect('Mou/index');
		}

		// Redirect to index with view=rekap and preserve filters
		$query_string = $this->input->server('QUERY_STRING');
		redirect('Mou/index?view=rekap' . ($query_string ? '&' . $query_string : ''));
	}

	// Export Rekap MOU to Excel
	public function rekap_excel()
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			show_error('Tabel database tidak ditemukan');
			return;
		}

		// Get filters from GET
		$tanggal_mulai = $this->input->get('tanggal_mulai');
		$tanggal_selesai = $this->input->get('tanggal_selesai');
		$lokasi = $this->input->get('lokasi');
		$customer = $this->input->get('customer');
		$kry_kode = $this->input->get('kry_kode');

		// Build filters array
		$filters = array(
			'tanggal_mulai' => $tanggal_mulai,
			'tanggal_selesai' => $tanggal_selesai,
			'lokasi' => $lokasi,
			'customer' => $customer,
			'kry_kode' => $kry_kode
		);

		// Get data from model
		$detail_list = $this->M_mou->get_rekap_mou($filters);

		// Load Excel library
		$this->load->library('Excel');

		// Get PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();

		// Set title
		$sheet->setCellValue('A1', 'REKAP DATA MOU');
		$sheet->mergeCells('A1:G1');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// Set headers
		$headers = array('No', 'Tanggal', 'Nama File', 'Customer', 'Lokasi', 'Grand Total', 'Dibuat Oleh');
		$col = 'A';
		foreach ($headers as $header) {
			$sheet->setCellValue($col . '3', $header);
			$sheet->getStyle($col . '3')->getFont()->setBold(true);
			$sheet->getStyle($col . '3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');
			$col++;
		}

		// Set column widths
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(12);
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->getColumnDimension('G')->setWidth(15);

		// Fill data
		$row = 4;
		$no = 1;
		foreach ($detail_list->result() as $mou) {
			$sheet->setCellValue('A' . $row, $no);
			$sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($mou->tanggal)));
			$sheet->setCellValue('C' . $row, $mou->file_name);
			$sheet->setCellValue('D' . $row, $mou->customer);
			$sheet->setCellValue('E' . $row, $mou->lokasi);
			$sheet->setCellValue('F' . $row, $mou->grand_total);
			$sheet->setCellValue('G' . $row, $mou->kry_nama);

			// Format grand_total as currency
			$sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

			$row++;
			$no++;
		}

		// Set filename
		$filename = 'rekap_mou_' . date('Ymd') . '.xlsx';

		// Output
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$writer->save('php://output');
		exit;
	}

	// Export Rekap MOU to PDF
	public function rekap_pdf()
	{
		// Check if table exists
		if (!$this->db->table_exists('mou')) {
			show_error('Tabel database tidak ditemukan');
			return;
		}

		// Get filters from GET
		$tanggal_mulai = $this->input->get('tanggal_mulai');
		$tanggal_selesai = $this->input->get('tanggal_selesai');
		$lokasi = $this->input->get('lokasi');
		$customer = $this->input->get('customer');
		$kry_kode = $this->input->get('kry_kode');

		// Build filters array
		$filters = array(
			'tanggal_mulai' => $tanggal_mulai,
			'tanggal_selesai' => $tanggal_selesai,
			'lokasi' => $lokasi,
			'customer' => $customer,
			'kry_kode' => $kry_kode
		);

		// Get data from model
		$detail_list = $this->M_mou->get_rekap_mou($filters);
		$summary = $this->M_mou->get_rekap_summary($filters);

		// Build HTML
		$html = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<style>
				body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
				.header { text-align: center; margin-bottom: 20px; }
				.header h2 { margin: 0; color: #333; }
				.header p { margin: 5px 0; color: #666; }
				.summary { margin-bottom: 20px; }
				.summary-item { display: inline-block; margin-right: 30px; }
				.summary-label { font-weight: bold; }
				.summary-value { color: #0066cc; }
				table { width: 100%; border-collapse: collapse; margin-top: 10px; }
				table th { background-color: #333; color: white; padding: 8px; text-align: left; border: 1px solid #ddd; }
				table td { padding: 8px; border: 1px solid #ddd; }
				table tr:nth-child(even) { background-color: #f9f9f9; }
				.text-right { text-align: right; }
				.footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
			</style>
		</head>
		<body>
			<div class="header">
				<h2>CV AZZAHRA COMPUTER</h2>
				<p>REKAP DATA MOU</p>';

		if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
			$html .= '<p>Periode: ' . date('d/m/Y', strtotime($filters['tanggal_mulai'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_selesai'])) . '</p>';
		}

		$html .= '</div>

			<div class="summary">
				<div class="summary-item">
					<span class="summary-label">Total MOU:</span>
					<span class="summary-value">' . ($summary['total_mou'] ?? 0) . '</span>
				</div>
				<div class="summary-item">
					<span class="summary-label">Total Nilai:</span>
					<span class="summary-value">Rp. ' . number_format($summary['total_grand_total'] ?? 0, 0, ',', '.') . ',-</span>
				</div>
				<div class="summary-item">
					<span class="summary-label">Rata-rata:</span>
					<span class="summary-value">Rp. ' . number_format($summary['avg_grand_total'] ?? 0, 0, ',', '.') . ',-</span>
				</div>
			</div>

			<table>
				<thead>
					<tr>
						<th style="width: 5%;">No</th>
						<th style="width: 12%;">Tanggal</th>
						<th style="width: 20%;">Nama File</th>
						<th style="width: 20%;">Customer</th>
						<th style="width: 12%;">Lokasi</th>
						<th style="width: 15%; text-align: right;">Grand Total</th>
						<th style="width: 16%;">Dibuat Oleh</th>
					</tr>
				</thead>
				<tbody>';

		$no = 1;
		foreach ($detail_list->result() as $mou) {
			$html .= '<tr>
				<td>' . $no . '</td>
				<td>' . date('d/m/Y', strtotime($mou->tanggal)) . '</td>
				<td>' . htmlspecialchars($mou->file_name) . '</td>
				<td>' . htmlspecialchars($mou->customer) . '</td>
				<td>' . htmlspecialchars($mou->lokasi) . '</td>
				<td class="text-right">Rp. ' . number_format($mou->grand_total, 0, ',', '.') . ',-</td>
				<td>' . htmlspecialchars($mou->kry_nama) . '</td>
			</tr>';
			$no++;
		}

		$html .= '
				</tbody>
			</table>

			<div class="footer">
				<p>Generated on ' . date('d/m/Y H:i:s') . '</p>
			</div>
		</body>
		</html>';

		// Load Dompdf
		require_once APPPATH . '../vendor/autoload.php';

		$dompdf = new \Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();

		// Output
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="rekap_mou_' . date('Ymd') . '.pdf"');
		echo $dompdf->output();
		exit;
	}
}

/* End of file Mou.php */
/* Location: ./application/controllers/Mou.php */