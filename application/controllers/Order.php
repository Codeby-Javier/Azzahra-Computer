<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_order');
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

}

/* End of file Order.php */
/* Location: ./application/controllers/Order.php */
