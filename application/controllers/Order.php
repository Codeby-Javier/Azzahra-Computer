<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_order');
		$this->load->library('pagination');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Admin' && $this->session->userdata('level') != 'Customer Service') {
	    		$url=base_url();
	      		redirect($url);
	    	}
	    }
	}

	public function index($filter = 'pending')
	{
		$data = array(
			'title' => 'Order',
			'filter' => $filter,
			'no' => $this->uri->segment(4) ? $this->uri->segment(4) : 0
		);

		if ($filter == 'pending') {
			$this->db->select('tindakan.tdkn_kode, tindakan.tdkn_nama, tindakan.tdkn_qty, tindakan.tdkn_ket, order_list.trans_kode, order_list.device, order_list.merek, order_list.seri, order_list.status_garansi, costomer.cos_nama, (SELECT COALESCE(SUM(tdkn_subtot), 0) FROM tindakan WHERE trans_kode = order_list.trans_kode) as total_subtot, (SELECT COALESCE(SUM(dtl_jml_bayar), 0) FROM transaksi_detail WHERE trans_kode = order_list.trans_kode) as total_bayar');
			$this->db->from('order_list');
			$this->db->join('tindakan', 'order_list.trans_kode = tindakan.trans_kode', 'inner');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->join('transaksi_detail', 'order_list.trans_kode = transaksi_detail.trans_kode', 'left');
			$this->db->where('order_list.trans_status', 'waitingOrder');
			$this->db->order_by('tindakan.tdkn_kode', 'ASC');
			$data['orders'] = $this->db->get();
			$data['table_title'] = 'Pending Orders';
			$data['karyawan'] = $this->M_order->get_karyawan_list();
			$data['show_modal'] = true;
		} elseif ($filter == 'waiting') {
			$data['orders'] = $this->M_order->get_waiting_approval_orders();
			$data['table_title'] = 'Waiting Approval Orders';
			$data['show_modal'] = false;
		} elseif ($filter == 'confirm') {
			$this->db->select('order_list.*, costomer.cos_nama, order_list.ket_keluhan as keluhan');
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'pending');
			$this->db->order_by('order_list.trans_tanggal', 'DESC');
			$data['orders'] = $this->db->get();
			$data['table_title'] = 'Confirm Orders';
			$data['karyawan'] = $this->M_order->get_karyawan_list();
			$data['show_modal'] = false;
		} elseif ($filter == 'repairing') {
			$search = $this->input->get('search');
			$page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
			$per_page = 10;
			$offset = $page;

			$where_search1 = '';
			$where_search2 = '';
			if (!empty($search)) {
				$search_escaped = $this->db->escape_like_str($search);
				$where_search1 = "(order_list.trans_kode LIKE '%$search_escaped%' OR order_list.cos_kode LIKE '%$search_escaped%' OR costomer.cos_nama LIKE '%$search_escaped%' OR COALESCE(order_list.device, '') LIKE '%$search_escaped%' OR costomer.cos_tipe LIKE '%$search_escaped%' OR costomer.cos_keluhan LIKE '%$search_escaped%')";
				$where_search2 = "(transaksi.trans_kode LIKE '%$search_escaped%' OR transaksi.cos_kode LIKE '%$search_escaped%' OR costomer.cos_nama LIKE '%$search_escaped%' OR costomer.cos_model LIKE '%$search_escaped%' OR costomer.cos_tipe LIKE '%$search_escaped%' OR costomer.cos_keluhan LIKE '%$search_escaped%')";
			}

			// Get count for order_list
			$this->db->select("order_list.trans_kode");
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->join('transaksi', 'order_list.trans_kode = transaksi.trans_kode', 'left');
			$this->db->where('order_list.trans_status', 'repairing');
			if (!empty($where_search1)) {
				$this->db->where($where_search1, NULL, FALSE);
			}
			$query1_count = $this->db->get_compiled_select();
			$query1_count = "SELECT COUNT(*) as count FROM ($query1_count) as q1";
			$count1 = $this->db->query($query1_count)->row()->count;

			// Get count for transaksi
			$this->db->select("transaksi.trans_kode");
			$this->db->from('transaksi');
			$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
			$this->db->where('transaksi.trans_status', 'Pelunasan');
			$this->db->where('NOT EXISTS (SELECT 1 FROM order_list WHERE order_list.cos_kode = transaksi.cos_kode)', NULL, FALSE);
			if (!empty($where_search2)) {
				$this->db->where($where_search2, NULL, FALSE);
			}
			$query2_count = $this->db->get_compiled_select();
			$query2_count = "SELECT COUNT(*) as count FROM ($query2_count) as q2";
			$count2 = $this->db->query($query2_count)->row()->count;

			$total_rows = $count1 + $count2;

			// Get orders from order_list with status 'repairing'
			$this->db->select("order_list.trans_kode, order_list.cos_kode, order_list.trans_total, order_list.trans_tanggal, costomer.cos_nama, costomer.cos_hp, transaksi.trans_status as payment_status, 'order_list' as source_table, COALESCE(order_list.device, '-') as device, costomer.cos_tipe as merek, order_list.seri, costomer.cos_keluhan as ket_keluhan");
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->join('transaksi', 'order_list.trans_kode = transaksi.trans_kode', 'left');
			$this->db->where('order_list.trans_status', 'repairing');
			if (!empty($where_search1)) {
				$this->db->where($where_search1, NULL, FALSE);
			}
			$query1 = $this->db->get_compiled_select();

			// Get legacy orders from transaksi with status 'Pelunasan' that don't have order_list entry
			$this->db->select("transaksi.trans_kode, transaksi.cos_kode, transaksi.trans_total, transaksi.trans_tanggal, costomer.cos_nama, costomer.cos_hp, transaksi.trans_status as payment_status, 'transaksi' as source_table, '-' as device, costomer.cos_tipe as merek, costomer.cos_no_seri as seri, costomer.cos_keluhan as ket_keluhan");
			$this->db->from('transaksi');
			$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
			$this->db->where('transaksi.trans_status', 'Pelunasan');
			$this->db->where('NOT EXISTS (SELECT 1 FROM order_list WHERE order_list.cos_kode = transaksi.cos_kode)', NULL, FALSE);
			if (!empty($where_search2)) {
				$this->db->where($where_search2, NULL, FALSE);
			}
			$query2 = $this->db->get_compiled_select();

			// Combine both queries with LIMIT and OFFSET
			$combined_query = "($query1) UNION ($query2) ORDER BY trans_tanggal DESC LIMIT $per_page OFFSET $offset";
			$data['orders'] = $this->db->query($combined_query);
			$data['table_title'] = 'Repairing Orders';
			$data['show_modal'] = false;
			$data['total_repairing'] = $total_rows;
			$data['search'] = $search;

			// Pagination config
			$config['base_url'] = site_url('Order/index/repairing');
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'page';
			$config['reuse_query_string'] = TRUE;

			// Styling
			$config['full_tag_open'] = '<div class="flex justify-center items-center space-x-2 mt-6">';
			$config['full_tag_close'] = '</div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['first_tag_close'] = '</span>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['last_tag_close'] = '</span>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['next_tag_close'] = '</span>';
			$config['prev_link'] = 'Prev';
			$config['prev_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['prev_tag_close'] = '</span>';
			$config['cur_tag_open'] = '<span class="px-3 py-2 mx-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['cur_tag_close'] = '</span>';
			$config['num_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['num_tag_close'] = '</span>';
			$config['attributes'] = array('class' => 'px-1 mx-1 text-gray-700 hover:text-gray-900 transition-colors duration-200 font-medium inline-block');

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
		} elseif ($filter == 'completed') {
			$search = $this->input->get('search');
			$page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
			$per_page = 10;

			$where_search = '';
			if (!empty($search)) {
				$search_escaped = $this->db->escape_like_str($search);
				$where_search = "(order_list.trans_kode LIKE '%$search_escaped%' OR order_list.cos_kode LIKE '%$search_escaped%' OR costomer.cos_nama LIKE '%$search_escaped%' OR COALESCE(order_list.device, '') LIKE '%$search_escaped%' OR COALESCE(order_list.merek, '') LIKE '%$search_escaped%' OR COALESCE(order_list.ket_keluhan, '') LIKE '%$search_escaped%')";
			}

			// Get total rows
			$this->db->select("COUNT(*) as count");
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'service_completed');
			if (!empty($where_search)) {
				$this->db->where($where_search, NULL, FALSE);
			}
			$total_rows = $this->db->get()->row()->count;

			// Get orders with limit
			$this->db->select('order_list.*, costomer.cos_nama, costomer.cos_hp');
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'service_completed');
			if (!empty($where_search)) {
				$this->db->where($where_search, NULL, FALSE);
			}
			$this->db->order_by('order_list.trans_tanggal', 'DESC');
			$this->db->limit($per_page, $page);
			$data['orders'] = $this->db->get();
			$data['table_title'] = 'Completed Orders';
			$data['show_modal'] = false;
			$data['search'] = $search;

			// Pagination config
			$config['base_url'] = site_url('Order/index/completed');
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'page';
			$config['reuse_query_string'] = TRUE;

			// Styling with purple theme
			$config['full_tag_open'] = '<div class="flex justify-center items-center space-x-2 mt-6">';
			$config['full_tag_close'] = '</div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['first_tag_close'] = '</span>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['last_tag_close'] = '</span>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['next_tag_close'] = '</span>';
			$config['prev_link'] = 'Prev';
			$config['prev_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['prev_tag_close'] = '</span>';
			$config['cur_tag_open'] = '<span class="px-3 py-2 mx-1 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['cur_tag_close'] = '</span>';
			$config['num_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['num_tag_close'] = '</span>';
			$config['attributes'] = array('class' => 'px-1 mx-1 text-gray-700 hover:text-gray-900 transition-colors duration-200 font-medium inline-block');

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
		} elseif ($filter == 'failed') {
			$search = $this->input->get('search');
			$page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
			$per_page = 10;

			$where_search = '';
			if (!empty($search)) {
				$search_escaped = $this->db->escape_like_str($search);
				$where_search = "(order_list.trans_kode LIKE '%$search_escaped%' OR order_list.cos_kode LIKE '%$search_escaped%' OR costomer.cos_nama LIKE '%$search_escaped%' OR COALESCE(order_list.device, '') LIKE '%$search_escaped%' OR COALESCE(order_list.merek, '') LIKE '%$search_escaped%' OR COALESCE(order_list.ket_keluhan, '') LIKE '%$search_escaped%')";
			}

			// Get total rows
			$this->db->select("COUNT(*) as count");
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'service_failed');
			if (!empty($where_search)) {
				$this->db->where($where_search, NULL, FALSE);
			}
			$total_rows = $this->db->get()->row()->count;

			// Get orders with limit
			$this->db->select('order_list.*, costomer.cos_nama, costomer.cos_hp');
			$this->db->from('order_list');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'service_failed');
			if (!empty($where_search)) {
				$this->db->where($where_search, NULL, FALSE);
			}
			$this->db->order_by('order_list.trans_tanggal', 'DESC');
			$this->db->limit($per_page, $page);
			$data['orders'] = $this->db->get();
			$data['table_title'] = 'Failed Orders';
			$data['show_modal'] = false;
			$data['search'] = $search;

			// Pagination config
			$config['base_url'] = site_url('Order/index/failed');
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'page';
			$config['reuse_query_string'] = TRUE;

			// Styling with red theme
			$config['full_tag_open'] = '<div class="flex justify-center items-center space-x-2 mt-6">';
			$config['full_tag_close'] = '</div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['first_tag_close'] = '</span>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['last_tag_close'] = '</span>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['next_tag_close'] = '</span>';
			$config['prev_link'] = 'Prev';
			$config['prev_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['prev_tag_close'] = '</span>';
			$config['cur_tag_open'] = '<span class="px-3 py-2 mx-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['cur_tag_close'] = '</span>';
			$config['num_tag_open'] = '<span class="px-3 py-2 mx-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 font-medium inline-block">';
			$config['num_tag_close'] = '</span>';
			$config['attributes'] = array('class' => 'px-1 mx-1 text-gray-700 hover:text-gray-900 transition-colors duration-200 font-medium inline-block');

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
		} elseif ($filter == 'orderpart') {
			$search = $this->input->get('search');
			$data['search'] = $search;

			// Always filter for is_ordered = 'yes'
			$this->db->select('order_list.trans_kode, order_list.cos_kode, order_list.device, order_list.merek, order_list.seri, order_list.ket_keluhan, costomer.cos_nama, order_part_marking.is_ordered, order_part_marking.rma_number, order_part_marking.end_warranty_date');
			$this->db->from('order_part_marking');
			$this->db->join('order_list', 'order_part_marking.trans_kode = order_list.trans_kode', 'inner');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_part_marking.is_ordered', 'yes');

			if (!empty($search)) {
				// Search by cos_kode, trans_kode, or cos_nama
				$this->db->group_start()
					->like('order_list.cos_kode', $search)
					->or_like('order_list.trans_kode', $search)
					->or_like('costomer.cos_nama', $search)
				->group_end();
			}

			$this->db->order_by('order_list.trans_tanggal', 'DESC');
			$data['orders'] = $this->db->get();

			$data['table_title'] = 'Order Part Status';
			$data['show_modal'] = false;
		} else {
			// default to pending
			$this->db->select('tindakan.tdkn_kode, tindakan.tdkn_nama, tindakan.tdkn_qty, tindakan.tdkn_ket, order_list.trans_kode, order_list.device, order_list.merek, order_list.seri, order_list.status_garansi, costomer.cos_nama, (SELECT COALESCE(SUM(tdkn_subtot), 0) FROM tindakan WHERE trans_kode = order_list.trans_kode) as total_subtot, (SELECT COALESCE(SUM(dtl_jml_bayar), 0) FROM transaksi_detail WHERE TRIM(trans_kode) = TRIM(order_list.trans_kode)) as total_bayar');
			$this->db->from('order_list');
			$this->db->join('tindakan', 'order_list.trans_kode = tindakan.trans_kode', 'inner');
			$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('order_list.trans_status', 'waitingOrder');
			$this->db->order_by('tindakan.tdkn_kode', 'ASC');
			$data['orders'] = $this->db->get();
			$data['table_title'] = 'Pending Orders';
			$data['karyawan'] = $this->M_order->get_karyawan_list();
			$data['show_modal'] = true;
		}

		// Get today's orders for notification (only those needing confirmation)
		$this->db->select('order_list.trans_kode, order_list.trans_status, costomer.cos_nama, order_list.created_at');
		$this->db->from('order_list');
		$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
		$this->db->where('DATE(order_list.created_at)', date('Y-m-d'));
		$this->db->where_in('order_list.trans_status', array('waitingOrder', 'waitingApproval', 'pending'));
		$this->db->order_by('order_list.created_at', 'DESC');
		$data['today_orders'] = $this->db->get()->result_array();

		// Load the same view for both Admin and Customer Service
		$this->load->view('CSdanAdmin/order', $data);
	}

	public function update_status()
	{
		$trans_kode = $this->input->post('trans_kode');
		$status = $this->input->post('status');

		$data = array('trans_status' => $status);
		$this->M_order->update_order_status($trans_kode, $data);

		// Remove flashdata to prevent popup on refresh
		// $this->session->set_flashdata('sukses', 'Status berhasil diupdate');
		redirect('Order','refresh');
	}

	public function confirm_order()
	{
		$trans_kode = trim($this->input->post('trans_kode'));
		$kry_kode   = $this->input->post('kry_kode');

		if (!$trans_kode || !$kry_kode) {
			$this->session->set_flashdata('gagal', 'Transaksi atau karyawan belum dipilih.');
			return redirect('Order/index/pending');
		}

		$data = array(
			'trans_status' => 'confirm', // lowercase, match model
			'kry_kode'     => $kry_kode
		);

		$res = $this->M_order->update_order_status($trans_kode, $data);
		if ($res) {
			$this->session->set_flashdata('sukses', 'Order berhasil dikonfirmasi.');
			return redirect('Order/index/confirm');
		} else {
			$this->session->set_flashdata('gagal', 'Gagal mengkonfirmasi order. Pastikan trans_kode valid.');
			return redirect('Order/index/pending');
		}
	}

	public function approve_order()
	{
		log_message('info', 'approve_order method called');
		log_message('info', 'POST data: ' . json_encode($this->input->post()));

		$trans_kode = trim($this->input->post('trans_kode'));
		$tdkn_kode = trim($this->input->post('tdkn_kode'));
		$subtot = str_replace('.', '', trim($this->input->post('subtot')));

		if (!$trans_kode) {
			log_message('error', 'trans_kode is empty');
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!$tdkn_kode) {
			log_message('error', 'tdkn_kode is empty');
			$this->session->set_flashdata('gagal', 'tdkn_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!is_numeric($subtot)) {
			log_message('error', 'subtot is not numeric');
			$this->session->set_flashdata('gagal', 'Harga total harus berupa angka.');
			return redirect('Order/index/waiting');
		}

		log_message('info', 'Processing approve_order for trans_kode: ' . $trans_kode . ', tdkn_kode: ' . $tdkn_kode . ', subtot: ' . $subtot);

		// Update tindakan subtot
		$res_subtot = $this->M_order->update_tindakan_subtot($trans_kode, $tdkn_kode, $subtot);
		if (!$res_subtot) {
			log_message('error', 'Failed to update tindakan subtot for trans_kode: ' . $trans_kode . ', tdkn_kode: ' . $tdkn_kode);
			$this->session->set_flashdata('gagal', 'Gagal mengupdate harga total tindakan.');
			return redirect('Order/index/waiting');
		}

		// Update order_list trans_total
		$this->db->where('trans_kode', $trans_kode);
		$this->db->update('order_list', array('trans_total' => $subtot));

		// Update order status to approved
		$data = array('trans_status' => 'approved');
		$res_status = $this->M_order->update_order_status($trans_kode, $data);

		if ($res_status) {
			log_message('info', 'Order status successfully updated to approved for trans_kode: ' . $trans_kode);
			$this->session->set_flashdata('sukses', 'Order berhasil disetujui dengan harga total diperbarui.');
		} else {
			log_message('error', 'Failed to update order status for trans_kode: ' . $trans_kode);
			$this->session->set_flashdata('gagal', 'Gagal mengupdate status order (trans_kode tidak cocok?).');
		}
		return redirect('Order/index/waiting');
	}

	public function update_subtot()
	{
		log_message('info', 'update_subtot method called');
		log_message('info', 'POST data: ' . json_encode($this->input->post()));

		$trans_kode = trim($this->input->post('trans_kode'));
		$tdkn_kode = trim($this->input->post('tdkn_kode'));
		$subtot = trim($this->input->post('subtot'));

		if (!$trans_kode) {
			log_message('error', 'trans_kode is empty');
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!$tdkn_kode) {
			log_message('error', 'tdkn_kode is empty');
			$this->session->set_flashdata('gagal', 'tdkn_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!is_numeric($subtot)) {
			log_message('error', 'subtot is not numeric');
			$this->session->set_flashdata('gagal', 'Harga total harus berupa angka.');
			return redirect('Order/index/waiting');
		}

		log_message('info', 'Updating subtot for trans_kode: ' . $trans_kode . ', tdkn_kode: ' . $tdkn_kode . ', subtot: ' . $subtot);

		$res = $this->M_order->update_tindakan_subtot($trans_kode, $tdkn_kode, $subtot);

		if ($res) {
			log_message('info', 'Subtot updated successfully for trans_kode: ' . $trans_kode);
			$this->session->set_flashdata('sukses', 'Harga total berhasil diperbarui.');
		} else {
			log_message('error', 'Failed to update subtot for trans_kode: ' . $trans_kode);
			$this->session->set_flashdata('gagal', 'Gagal memperbarui harga total.');
		}
		return redirect('Order/index/waiting');
	}

	public function inform_unavailable()
	{
		$trans_kode = trim($this->input->post('trans_kode'));
		$tdkn_kode = trim($this->input->post('tdkn_kode'));
		$subtot = str_replace('.', '', trim($this->input->post('subtot')));

		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!$tdkn_kode) {
			$this->session->set_flashdata('gagal', 'tdkn_kode tidak ditemukan.');
			return redirect('Order/index/waiting');
		}

		if (!is_numeric($subtot)) {
			$this->session->set_flashdata('gagal', 'Harga total harus berupa angka.');
			return redirect('Order/index/waiting');
		}

		// Update tindakan subtot
		$res_subtot = $this->M_order->update_tindakan_subtot($trans_kode, $tdkn_kode, $subtot);
		if (!$res_subtot) {
			$this->session->set_flashdata('gagal', 'Gagal mengupdate harga total tindakan.');
			return redirect('Order/index/waiting');
		}

		// Update order_list trans_total
		$this->db->where('trans_kode', $trans_kode);
		$this->db->update('order_list', array('trans_total' => $subtot));

		// Update order status to waitingOrder
		$data = array('trans_status' => 'waitingOrder');
		$res = $this->M_order->update_order_status($trans_kode, $data);
		if (!$res) {
			log_message('error', 'Failed to update order status for trans_kode: ' . $trans_kode);
			$this->session->set_flashdata('gagal', 'Gagal mengupdate status order.');
			return redirect('Order/index/waiting');
		}

		// Send JSON message that item is not available
		$this->session->set_flashdata('sukses', 'Orderan berhasil di submit.');
		return redirect('Order/index/waiting');
	}

	public function update_trans_total()
	{
		$trans_kode = $this->input->post('trans_kode');
		$sisa = $this->input->post('sisa');

		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/pending');
		}

		$this->db->where('trans_kode', $trans_kode);
		$res = $this->db->update('order_list', array('trans_total' => $sisa, 'trans_status' => 'approved'));

		if ($res) {
			$this->session->set_flashdata('sukses', 'Trans total dan status berhasil diupdate.');
		} else {
			$this->session->set_flashdata('gagal', 'Gagal mengupdate trans total dan status.');
		}
		return redirect('Order/index/pending');
	}

	public function reject_order($trans_kode = null)
	{
		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/pending');
		}

		$data = array('trans_status' => 'tolak');
		$res  = $this->M_order->update_order_status($trans_kode, $data);

		if ($res) {
			$this->session->set_flashdata('sukses', 'Order berhasil ditolak.');
		} else {
			$this->session->set_flashdata('gagal', 'Gagal menolak order (trans_kode tidak cocok?).');
		}
		return redirect('Order/index/pending');
	}

	public function service_complete()
	{
		$trans_kode = trim($this->input->post('trans_kode'));

		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/repairing');
		}

		// Check if order_list exists for this trans_kode
		$this->db->where('trans_kode', $trans_kode);
		$order_exists = $this->db->get('order_list')->num_rows();

		if ($order_exists > 0) {
			// Update existing order_list
			$data = array('trans_status' => 'service_completed');
			$res = $this->M_order->update_order_status($trans_kode, $data);
		} else {
			// Create new order_list entry for legacy data
			// Get data from transaksi and costomer
			$this->db->select('transaksi.*, costomer.cos_tipe as merek, costomer.cos_model as device, costomer.cos_status as status_garansi, costomer.cos_no_seri as seri, costomer.cos_keluhan as ket_keluhan, costomer.cos_alamat');
			$this->db->from('transaksi');
			$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('transaksi.trans_kode', $trans_kode);
			$legacy_data = $this->db->get()->row();

			if ($legacy_data) {
				// Generate new trans_kode for order_list (TRDDMMYYNNN)
				$date_prefix = 'TR' . date('dmy');
				$this->db->select('MAX(CAST(SUBSTRING(trans_kode, -3) AS UNSIGNED)) as max_num');
				$this->db->from('order_list');
				$this->db->like('trans_kode', $date_prefix, 'after');
				$query = $this->db->get();
				$result = $query->row();
				$next_num = ($result->max_num ? $result->max_num : 0) + 1;
				$new_trans_kode = $date_prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);

				// Insert new order_list
				$order_data = array(
					'trans_kode' => $new_trans_kode,
					'cos_kode' => $legacy_data->cos_kode,
					'kry_kode' => null,
					'trans_total' => $legacy_data->trans_total,
					'trans_discount' => $legacy_data->trans_discount ?? 0,
					'trans_tanggal' => $legacy_data->trans_tanggal,
					'trans_status' => 'service_completed',
					'merek' => $legacy_data->merek,
					'device' => $legacy_data->device,
					'status_garansi' => $legacy_data->status_garansi,
					'seri' => $legacy_data->seri,
					'ket_keluhan' => $legacy_data->ket_keluhan,
					'email' => 'legacy@example.com',
					'alamat' => $legacy_data->cos_alamat ?? '',
					'created_at' => date('Y-m-d H:i:s')
				);

				$res = $this->db->insert('order_list', $order_data);
			} else {
				$this->session->set_flashdata('gagal', 'Data legacy tidak ditemukan.');
				return redirect('Order/index/repairing');
			}
		}

		if ($res) {
			$this->session->set_flashdata('sukses', 'Service berhasil ditandai sebagai selesai.');
			return redirect('Order/index/completed');
		} else {
			$this->session->set_flashdata('gagal', 'Gagal mengupdate status service.');
			return redirect('Order/index/repairing');
		}
	}

	public function service_failed()
	{
		$trans_kode = trim($this->input->post('trans_kode'));

		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order/index/repairing');
		}

		// Check if order_list exists for this trans_kode
		$this->db->where('trans_kode', $trans_kode);
		$order_exists = $this->db->get('order_list')->num_rows();

		if ($order_exists > 0) {
			// Update existing order_list
			$data = array('trans_status' => 'service_failed');
			$res = $this->M_order->update_order_status($trans_kode, $data);
		} else {
			// Create new order_list entry for legacy data
			// Get data from transaksi and costomer
			$this->db->select('transaksi.*, costomer.cos_tipe as merek, costomer.cos_model as device, costomer.cos_status as status_garansi, costomer.cos_no_seri as seri, costomer.cos_keluhan as ket_keluhan, costomer.cos_alamat');
			$this->db->from('transaksi');
			$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer', 'left');
			$this->db->where('transaksi.trans_kode', $trans_kode);
			$legacy_data = $this->db->get()->row();

			if ($legacy_data) {
				// Generate new trans_kode for order_list (TRDDMMYYNNN)
				$date_prefix = 'TR' . date('dmy');
				$this->db->select('MAX(CAST(SUBSTRING(trans_kode, -3) AS UNSIGNED)) as max_num');
				$this->db->from('order_list');
				$this->db->like('trans_kode', $date_prefix, 'after');
				$query = $this->db->get();
				$result = $query->row();
				$next_num = ($result->max_num ? $result->max_num : 0) + 1;
				$new_trans_kode = $date_prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);

				// Insert new order_list
				$order_data = array(
					'trans_kode' => $new_trans_kode,
					'cos_kode' => $legacy_data->cos_kode,
					'kry_kode' => null,
					'trans_total' => $legacy_data->trans_total,
					'trans_discount' => $legacy_data->trans_discount ?? 0,
					'trans_tanggal' => $legacy_data->trans_tanggal,
					'trans_status' => 'service_failed',
					'merek' => $legacy_data->merek,
					'device' => $legacy_data->device,
					'status_garansi' => $legacy_data->status_garansi,
					'seri' => $legacy_data->seri,
					'ket_keluhan' => $legacy_data->ket_keluhan,
					'email' => 'legacy@example.com',
					'alamat' => $legacy_data->cos_alamat ?? '',
					'created_at' => date('Y-m-d H:i:s')
				);

				$res = $this->db->insert('order_list', $order_data);
			} else {
				$this->session->set_flashdata('gagal', 'Data legacy tidak ditemukan.');
				return redirect('Order/index/repairing');
			}
		}

		if ($res) {
			$this->session->set_flashdata('sukses', 'Service berhasil ditandai sebagai gagal.');
			return redirect('Order/index/failed');
		} else {
			$this->session->set_flashdata('gagal', 'Gagal mengupdate status service.');
			return redirect('Order/index/repairing');
		}
	}

	public function update_part_marking()
	{
		$trans_kode = $this->input->post('trans_kode');
		$rma_number = $this->input->post('rma_number');
		$end_warranty_date = $this->input->post('end_warranty_date');
		$is_ordered = $this->input->post('is_ordered');

		if (!$trans_kode) {
			$this->session->set_flashdata('gagal', 'trans_kode tidak ditemukan.');
			return redirect('Order');
		}

		$data = array();
		if (!empty($rma_number)) $data['rma_number'] = $rma_number;
		if (!empty($end_warranty_date)) $data['end_warranty_date'] = $end_warranty_date;
		if (!empty($is_ordered)) $data['is_ordered'] = $is_ordered;

		if (!empty($data)) {
			$res = $this->M_order->update_order_part_marking($trans_kode, $data);
			if ($res) {
				$this->session->set_flashdata('sukses', 'Data part marking berhasil diupdate.');
			} else {
				$this->session->set_flashdata('gagal', 'Gagal mengupdate data part marking.');
			}
		} else {
			$this->session->set_flashdata('gagal', 'Tidak ada data yang diupdate.');
		}

		redirect('Order');
	}


}

/* End of file Order.php */
/* Location: ./application/controllers/Order.php */
