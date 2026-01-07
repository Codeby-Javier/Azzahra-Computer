<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_customer');
		$this->load->library('pagination');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Admin') {
	    		$url=base_url();
	      		redirect($url);
	    	}
	    }
	}

	public function index()
	{
		$search = $this->input->get('search');
		$per_page = 10;
		$total_rows = $this->M_customer->GetAllCount($search);
		$total_pages = ceil($total_rows / $per_page);
		$current_page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
		$offset = ($current_page - 1) * $per_page;

		$custom = $this->M_customer->GetAll($per_page, $offset, $search);
		if ($custom === false) {
			$custom = array(); // handle error
		}

		// Generate pagination HTML
		$pagination = $this->generate_pagination($current_page, $total_pages, $search);

		$data = array(
			'title' 	=> 'Customer',
			'custom'	=> $custom,
			'pagination' => $pagination,
			'no'		=> $offset
			 );
		$this->load->view('Costomer/customer',$data);
	}

	public function ajax_search()
	{
		$search = $this->input->post('search');
		$page = $this->input->post('page') ? (int)$this->input->post('page') : 1;
		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		$total_rows = $this->M_customer->GetAllCount($search);
		$total_pages = ceil($total_rows / $per_page);

		$custom = $this->M_customer->GetAll($per_page, $offset, $search);

		// Generate table rows HTML
		$table_html = '';
		$no_urut = $offset + 1;
		foreach ($custom->result_array() as $row) {
			$table_html .= '<tr>';
			$table_html .= '<td class="text-center border-b">' . $no_urut++ . '</td>';
			$table_html .= '<td class="text-center border-b">' . $row['id_costomer'] . '</td>';
			$table_html .= '<td class="border-b">';
			$table_html .= '<div class="font-medium whitespace-no-wrap">' . $row['cos_nama'] . '</div>';
			$table_html .= '<div class="text-gray-600 text-xs whitespace-no-wrap">STATUS : ' . $row['trans_status'] . '</div>';
			$table_html .= '</td>';
			$table_html .= '<td class="border-b">' . $row['cos_alamat'] . '</td>';
			$table_html .= '<td class="text-center border-b">' . $row['cos_hp'] . '</td>';
			$table_html .= '<td class="border-b">';
			$table_html .= '<div class="font-medium whitespace-no-wrap">' . date('d-m-Y', strtotime($row['cos_tanggal'])) . '</div>';
			$table_html .= '<div class="text-gray-600 text-xs whitespace-no-wrap">JAM : ' . $row['cos_jam'] . '</div>';
			$table_html .= '</td>';
			$table_html .= '<td class="border-b w-5">';
			$table_html .= '<div class="flex sm:justify-center items-center">';
			$table_html .= '<a class="flex items-center mr-3" href="' . site_url('Customer/edit/' . $row['id_costomer']) . '"><i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit</a>';
			$table_html .= '<a class="flex items-center mr-3 text-theme-6 tombol-hapus" href="' . site_url('Customer/delete/' . $row['id_costomer']) . '" data-nama="' . $row['cos_nama'] . '"><i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete</a>';
			$table_html .= '<a class="flex items-center text-theme-1" href="' . site_url('Customer/histori/' . $row['trans_kode']) . '"><i data-feather="align-justify" class="w-4 h-4 mr-1"></i> Detail</a>';
			$table_html .= '</div>';
			$table_html .= '</td>';
			$table_html .= '</tr>';
		}

		// Generate pagination HTML
		$pagination_html = $this->generate_pagination($page, $total_pages, $search, true);

		echo json_encode(array(
			'table' => $table_html,
			'pagination' => $pagination_html
		));
	}

	private function generate_pagination($current_page, $total_pages, $search = '', $ajax = false)
	{
		if ($total_pages <= 1) return '';

		$query = !empty($search) ? '?search=' . urlencode($search) : '';

		$html = '<div class="pagination">';

		// First
		if ($current_page > 1) {
			if ($ajax) {
				$html .= '<a href="javascript:void(0)" onclick="loadPage(1)" class="pagination-btn pagination-first">First</a>';
			} else {
				$html .= '<a href="' . base_url('Customer/index/1' . $query) . '" class="pagination-btn pagination-first">First</a>';
			}
		}

		// Previous
		if ($current_page > 1) {
			$prev_page = $current_page - 1;
			if ($ajax) {
				$html .= '<a href="javascript:void(0)" onclick="loadPage(' . $prev_page . ')" class="pagination-btn pagination-prev">&laquo; Prev</a>';
			} else {
				$html .= '<a href="' . base_url('Customer/index/' . $prev_page . $query) . '" class="pagination-btn pagination-prev">&laquo; Prev</a>';
			}
		}

		// Page numbers
		$start = max(1, $current_page - 2);
		$end = min($total_pages, $current_page + 2);

		for ($i = $start; $i <= $end; $i++) {
			if ($i == $current_page) {
				$html .= '<strong class="pagination-link active">' . $i . '</strong>';
			} else {
				if ($ajax) {
					$html .= '<a href="javascript:void(0)" onclick="loadPage(' . $i . ')" class="pagination-link">' . $i . '</a>';
				} else {
					$html .= '<a href="' . base_url('Customer/index/' . $i . $query) . '" class="pagination-link">' . $i . '</a>';
				}
			}
		}

		// Next
		if ($current_page < $total_pages) {
			$next_page = $current_page + 1;
			if ($ajax) {
				$html .= '<a href="javascript:void(0)" onclick="loadPage(' . $next_page . ')" class="pagination-btn pagination-next">Next &raquo;</a>';
			} else {
				$html .= '<a href="' . base_url('Customer/index/' . $next_page . $query) . '" class="pagination-btn pagination-next">Next &raquo;</a>';
			}
		}

		// Last
		if ($current_page < $total_pages) {
			if ($ajax) {
				$html .= '<a href="javascript:void(0)" onclick="loadPage(' . $total_pages . ')" class="pagination-btn pagination-last">Last</a>';
			} else {
				$html .= '<a href="' . base_url('Customer/index/' . $total_pages . $query) . '" class="pagination-btn pagination-last">Last</a>';
			}
		}

		$html .= '</div>';
		return $html;
	}
	function edit()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Customer',
			'data' 		=> $this->M_customer->edit($kode)->row_array(), 
		);
		$this->load->view('Costomer/edit',$data);
	}
	function update()
	{
		$kode = $this->input->post('kode');

		$data = array(
			'cos_nama' 		 => $this->input->post('nama'),
			'cos_alamat' 	 => $this->input->post('alamat'),
			'cos_hp' 		 => $this->input->post('tlp'),
			'cos_tipe' 		 => $this->input->post('type'),
			'cos_model' 	 => $this->input->post('model'),
			'cos_no_seri' 	 => $this->input->post('seri'),
			'cos_asesoris' 	 => $this->input->post('asesoris'),
			'cos_status' 	 => $this->input->post('status'),
			'cos_pswd' 		 => $this->input->post('pswd'),
			'cos_keluhan' 	 => $this->input->post('keluhan'),
			'cos_keterangan' => $this->input->post('ket'),
		);
		$this->M_customer->update($kode,$data);

		if ($this->session->userdata('level') == 'Admin') {

			$this->session->set_flashdata('sukses', 'DI SIMPAN');
			redirect('Customer','refresh');
		}elseif ($this->session->userdata('level') == 'Customer Service') {
			redirect('Service','refresh');
		}else{
			redirect('Auth','refresh');
		}
	}
	function delete()
	{
		$kode = $this->uri->segment(3);

		$this->M_customer->delete($kode);

		if ($this->session->userdata('level') == 'Admin') {

			$this->session->set_flashdata('sukses', 'DI HAPUS');
			redirect('Customer','refresh');
		}elseif ($this->session->userdata('level') == 'Customer Service') {
			redirect('Service','refresh');
		}else{
			redirect('Auth','refresh');
		}
	}
	function histori()
	{
		$kode = $this->uri->segment(3);

		$proses = $this->M_customer->konfirmasi($kode)->row_array();
		if ($proses === null) {
			$proses = array(
				'cos_nama' => '',
				'cos_kode' => '',
				'cos_hp' => '',
				'cos_status' => '',
				'cos_tipe' => '',
				'cos_alamat' => '',
				'cos_model' => '',
				'cos_no_seri' => '',
				'cos_pswd' => '',
				'cos_asesoris' => '',
				'cos_keluhan' => '',
				'cos_keterangan' => ''
			);
		}

		$hist_trans = $this->M_customer->histori_transaksi($kode)->row_array();
		if ($hist_trans === null) {
			$hist_trans = array('trans_discount' => 0, 'trans_total' => 0);
		}

		$data = array(
				'title'  	 => 'Customer',
				'proses' 	 => $proses,
				'data'	 	 => $this->M_customer->GetTindakanBy($kode),
				'custom' 	 => $this->M_customer->histori($kode),
				'hist_trans' => $hist_trans
			);
		$this->load->view('Costomer/histori', $data);
	}

}

/* End of file Customer.php */
/* Location: ./application/controllers/Customer.php */