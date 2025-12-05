<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mou extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_mou');
		$this->load->config('mou_config');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Customer Service') {
	    		$url=base_url();
	      		redirect($url);
	    	}	    	
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
				'links' => ''
			);
			$this->load->view('Mou/index', $data);
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

		$data = array(
			'title' => 'Mou',
			'table_exists' => true,
			'mou_list' => $this->M_mou->get_all_mou($config['per_page'], $page),
			'links' => $this->pagination->create_links()
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
}

/* End of file Mou.php */
/* Location: ./application/controllers/Mou.php */

