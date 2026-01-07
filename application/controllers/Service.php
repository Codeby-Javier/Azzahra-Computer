<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_service');
		$this->load->model('M_order');
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
		$data = array(
				'title' 	 	 => 'Dashboard',
				'cos_baru' 		 => $this->M_service->ds_cos_baru(),
				'cos_proses' 	 => $this->M_service->ds_cos_proses(),
				'cos_knf'	 	 => $this->M_service->ds_cos_knf(),
				'cos_pelunasan'	 => $this->M_service->ds_cos_pelunasan(),
				'Dp_NonTunai'	 => $this->M_service->ds_dp_NonTunai(),
				'Dp_Tunai'	 	 => $this->M_service->ds_dp_Tunai(),
				'bca'	 		 => $this->M_service->ds_bca(),
				'bri'	 		 => $this->M_service->ds_bri()				
			);
		$this->load->view('Service/dashboard', $data);
	}
	function cos_baru()
	{
		$data = array(
				'title' => 'Customer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_baru()
			);
		$this->load->view('Costomer/cos-baru', $data);
	}
	
	function cos_proses()
	{
		$data = array(
				'title' => 'Customer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_proses()
			);
		$this->load->view('Costomer/cos-proses', $data);
	}
	function cos_konf()
	{
		$data = array(
				'title' => 'Customer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_konf()
			);
		$this->load->view('Costomer/cos-konf', $data);
	}
	function cos_pelunasan()
	{
		$data = array(
				'title' => 'Customer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_pelunasan()
			);
		$this->load->view('Costomer/cos-pelunasan', $data);
	}
	function cos_lunas()
	{
		$data = array(
				'title' => 'Customer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_lunas()
			);
		$this->load->view('Costomer/cos-lunas', $data);
	}
	function cos_histori()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'title'  	 => 'Customer',
				'proses' 	 => $this->M_service->konfirmasi($kode)->row_array(),
				'data'	 	 => $this->M_service->GetTindakanBy($kode),
				'custom' 	 => $this->M_service->histori($kode),
				'hist_trans' => $this->M_service->histori_transaksi($kode)->row_array()
			);
		$this->load->view('Service/histori', $data);
	}

	//transaksi
	function save_trans()
	{
		log_message('info', 'Starting save_trans process');

		$date_prefix = 'TTS' . date('ymd');
		$this->db->select('MAX(CAST(SUBSTRING(id_costomer, 10, 3) AS UNSIGNED)) as max_num');
		$this->db->from('costomer');
		$this->db->like('id_costomer', $date_prefix, 'after');
		$query = $this->db->get();
		$result = $query->row();
		$next_num = ($result->max_num ? $result->max_num : 0) + 1;
		$id_costomer = $date_prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);

		log_message('info', 'Generated id_costomer: ' . $id_costomer);

		$customer = array(
					'id_costomer' 	 => $id_costomer,
					'cos_nama' 		 => $this->input->post('nama'),
					'username' 		 => $id_costomer,
					'password' 		 => password_hash($id_costomer, PASSWORD_DEFAULT),
					'cos_tgl_lahir'  => $this->input->post('cos_tgl_lahir'),
					'cos_alamat' 	 => $this->input->post('alamat'),
					'cos_hp' 		 => $this->input->post('tlp'),
					'cos_tipe' 		 => $this->input->post('type'),
					'cos_model' 	 => $this->input->post('model'),
					'cos_no_seri' 	 => $this->input->post('seri'),
					'cos_asesoris' 	 => $this->input->post('asesoris'),
						'cos_status' 	 => $this->input->post('status'),
						'cos_pswd_type'  => $this->input->post('pswd_type'),
						'cos_pswd' 		 => $this->input->post('pswd_type') === 'text' ? $this->input->post('pswd') : ($this->input->post('pswd_type') === 'pattern_desc' ? $this->input->post('pswd_desc') : ($this->input->post('pswd_type') === 'pattern_canvas' ? '' : '')),
						'cos_pswd_canvas' => $this->input->post('pswd_canvas'),
						'cos_keluhan' 	 => $this->input->post('keluhan'),
					'cos_keterangan' => $this->input->post('ket'),
					'cos_tanggal'	 => date('Y-m-d'),
					'cos_jam' 		 => date('H:i:s'),
					'cos_poin' 		 => 0,
					'cos_gambar' 	 => null,
					'created_at' 	 => date('Y-m-d H:i:s'),
					'updated_at' 	 => date('Y-m-d H:i:s')
				);

		$customer_result = $this->M_service->save_custom($customer);
		log_message('info', 'Customer insert result: ' . ($customer_result ? 'success' : 'failed'));

		// Generate trans_kode: TRDDMMYYNNN
		$date_prefix_trans = 'TR' . date('dmy');
		// Find max from transaksi
		$this->db->select_max('trans_kode');
		$this->db->from('transaksi');
		$this->db->like('trans_kode', $date_prefix_trans, 'after');
		$query_trans = $this->db->get();
		$result_trans = $query_trans->row();
		$max_trans = $result_trans->trans_kode ? substr($result_trans->trans_kode, -3) : '000';
		log_message('info', 'Max trans_kode from transaksi: ' . $result_trans->trans_kode . ', processed max_trans: ' . $max_trans);

		// Find max from order_list
		$this->db->select_max('trans_kode');
		$this->db->from('order_list');
		$this->db->like('trans_kode', $date_prefix_trans, 'after');
		$query_order = $this->db->get();
		$result_order = $query_order->row();
		$max_order = $result_order->trans_kode ? substr($result_order->trans_kode, -3) : '000';
		log_message('info', 'Max trans_kode from order_list: ' . $result_order->trans_kode . ', processed max_order: ' . $max_order);

		$next_num_trans = max((int)$max_trans, (int)$max_order) + 1;
		$trans_kode = $date_prefix_trans . str_pad($next_num_trans, 3, '0', STR_PAD_LEFT);
		log_message('info', 'Generated trans_kode: ' . $trans_kode);

		$is_quick_service = $this->input->post('is_quick_service') ? true : false;

		$trans = array(
				'trans_kode'	 => $trans_kode,
				'cos_kode' 		 => $id_costomer,
				'kry_kode' 		 => $this->session->userdata('kode'),
				'trans_total' 	 => '0',
				'trans_discount' => '0',
				'trans_status' 	 => $is_quick_service ? 'Pelunasan' : 'Baru',
				'trans_tanggal'  => date('Y-m-d'),
			);

		$trans_result = $this->M_service->save_trans($trans);
		log_message('info', 'Transaksi insert result: ' . ($trans_result ? 'success' : 'failed'));

		// If quick service, add default tindakan
		if ($is_quick_service) {
			$tindakan_quick = array(
				'trans_kode' 	=> $trans_kode,
				'tdkn_nama' 	=> 'SERVICE',
				'tdkn_ket' 		=> 'Service - ' . $this->input->post('keluhan'),
				'tdkn_qty' 		=> 1,
				'tdkn_subtot'  	=> 0,
				'tdkn_tanggal' 	=> date('Y-m-d'),
				'tdkn_jam'		=> date('H:i:s')
			);
			$this->M_service->save_tindakan(array($tindakan_quick));
			log_message('info', 'Quick service tindakan added');
		}

		// Insert into order_list
		$order_list = array(
			'trans_kode' 	 => $trans_kode,
			'cos_kode' 		 => $id_costomer,
			'kry_kode' 		 => null,
			'trans_total' 	 => 0,
			'trans_discount' => 0,
			'trans_tanggal'  => date('Y-m-d'),
			'trans_status' 	 => 'itemSubmitted',
			'merek' 		 => $this->input->post('type'),
			'device' 		 => $this->input->post('device'),
			'status_garansi' => $this->input->post('status'),
			'seri' 			 => $this->input->post('seri'),
			'ket_keluhan' 	 => $this->input->post('keluhan'),
			'email' 		 => 'example@gmail.com',
			'alamat' 		 => $this->input->post('alamat'),
			'created_at' 	 => date('Y-m-d H:i:s')
		);
		$order_list_result = $this->M_service->save_order_list($order_list);
		log_message('info', 'Order_list insert result: ' . ($order_list_result ? 'success' : 'failed'));

		// Insert into order_part_marking
		$marking_data = array(
			'trans_kode' => $trans_kode,
			'is_ordered' => 'no'
		);
		$marking_result = $this->M_order->insert_order_part_marking($marking_data);
		log_message('info', 'Order_part_marking insert result: ' . ($marking_result ? 'success' : 'failed'));

		if ($this->input->is_ajax_request()) {
			echo json_encode(['status' => 'success']);
			exit;
		} else {
			$this->session->set_flashdata('sukses', 'DI TAMBAHKAN');
			redirect('Service/cos_baru','refresh');
		}

	}
	function update_trans()
	{
		$kode = $this->uri->segment(3);

		$customer = array(
					'cos_nama' 		 => $this->input->post('nama'),
					'cos_alamat' 	 => $this->input->post('alamat'),
					'cos_hp' 		 => $this->input->post('tlp'),
					'cos_tipe' 		 => $this->input->post('type'),
					'cos_model' 	 => $this->input->post('model'),
					'cos_no_seri' 	 => $this->input->post('seri'),
					'cos_asesoris' 	 => $this->input->post('asesoris'),
						'cos_status' 	 => $this->input->post('status'),
						'cos_pswd_type'  => $this->input->post('pswd_type'),
						'cos_pswd' 		 => $this->input->post('pswd_type') === 'text' ? $this->input->post('pswd') : ($this->input->post('pswd_type') === 'pattern_desc' ? $this->input->post('pswd_desc') : ''),
						'cos_pswd_canvas' => $this->input->post('pswd_canvas'),
						'cos_keluhan' 	 => $this->input->post('keluhan'),
					'cos_keterangan' => $this->input->post('ket'),
					'cos_tanggal'	 => date('Y-m-d'),
					'cos_jam' 		 => date('H:i:s')
				);
		$trans = array( 
				'kry_kode' 		 => $this->session->userdata('kode'),
				'trans_tanggal'	 => date('Y-m-d')
			);

		$this->M_service->update_custom($customer,$kode);
		$this->M_service->update_trans($trans,$kode);

		$this->session->set_flashdata('sukses', 'DI PERBAHARUI');
		redirect('Service/cos_baru','refresh');
	}
	function save_tindakan()
	{
		$kode 	  = json_decode($_POST['no'][0]);
		$tindakan = json_decode($_POST['tindakan'][0]);
		$ket 	  = json_decode($_POST['ket'][0]);
		$qty	  = json_decode($_POST['qty'][0]);
		$subtot	  = json_decode($_POST['subtot'][0]);
		$kd_trans = $this->input->post('tras_kode');

		$data  = array();
		$index = 0;
		foreach ($kode as $cek) {
			array_push($data, array(
				'trans_kode' 	=> $this->input->post('tras_kode'),
				'tdkn_nama' 	=> $tindakan[$index],
				'tdkn_ket' 		=> $ket[$index],
				'tdkn_qty' 		=> $qty[$index],
				'tdkn_subtot'  	=> $subtot[$index],
				'tdkn_tanggal' 	=> date('Y-m-d'),
				'tdkn_jam'		=> date('H:i:s')
			));
			$index++;
		}
		log_message('info', 'Data to insert into tindakan: ' . json_encode($data));
		$result = $this->M_service->save_tindakan($data);
		log_message('info', 'Insert result: ' . $result);
		if (!empty($data) && $result !== false && $result > 0) {
			$status = array(
				'trans_status' => 'Diproses',
			);
			$this->M_service->status($status,$kd_trans);

			// Update created_at in order_list
			$this->db->where('trans_kode', $kd_trans);
			$this->db->update('order_list', array('created_at' => date('Y-m-d H:i:s')));

			// Get cos_kode from transaksi and update trans_status in order_list to 'waitingApproval'
			$this->db->select('cos_kode');
			$this->db->from('transaksi');
			$this->db->where('trans_kode', $kd_trans);
			$query = $this->db->get();
			$result = $query->row();
			if ($result) {
				$cos_kode = $result->cos_kode;
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'waitingApproval'));
			}

			$this->session->set_flashdata('sukses', 'DATA BERHASIL DI PROSES');
		} else {
			log_message('error', 'Failed to insert tindakan data');
			$this->session->set_flashdata('gagal', 'Gagal menyimpan data tindakan');
		}
		redirect('Service/cos_proses','refresh');

	}

	//pembatalan transaksi
	function batal_transaksi()
	{
		$kode = $this->uri->segment(3);

		$up_btl_tdkn = array('tdkn_subtot' => '0', );
		$this->M_service->up_btl_detail($kode,$up_btl_tdkn);

		$save_tdkn = array(
			'trans_kode'  => $kode,
			'tdkn_nama'	  => 'PENGECEKAN UNIT',
			'tdkn_ket'    => 'PEMBATALAN TRANSAKSI',
			'tdkn_qty' 	  => '1',
			'tdkn_subtot' => '50000',
			'tdkn_tanggal'=> date('Y-m-d'),
			'tdkn_jam' 	  => date('H:i:s')
		);
		$this->M_service->save_btl_detail($save_tdkn);

		$trans = array(
				'trans_total' 	 => '50000',
				'trans_discount' => '0',
				'trans_status'   => 'Cencel' 
			);
		$this->M_service->up_btl_trans($kode,$trans);

		$this->session->set_flashdata('sukses', 'DI BATALKAN SILAHKAN KE KASIR');
		redirect('Service/cos_konf','refresh');
	}

	//return pembayaran
	function return_pembayaran()
	{
		$kode = $this->uri->segment(3);

		$up_ret_tdkn = array('tdkn_subtot' => '0', );
		$this->M_service->up_ret_detail($kode,$up_ret_tdkn);

		$save_tdkn = array(
			'trans_kode'  => $kode,
			'tdkn_nama'	  => 'PENGECEKAN UNIT',
			'tdkn_ket'    => 'RETURN PEMBAYARAN',
			'tdkn_qty' 	  => '1',
			'tdkn_subtot' => '50000',
			'tdkn_tanggal'=> date('Y-m-d'),
			'tdkn_jam' 	  => date('H:i:s')
		);
		$this->M_service->save_ret_detail($save_tdkn);

		$trans = array(
				'trans_total' 	 => '50000',
				'trans_discount' => '0',
				'trans_status'   => 'Return' 
			);
		$this->M_service->up_ret_trans($kode,$trans);

		$this->session->set_flashdata('sukses', 'DI RETURN PEMBAYARANNYA SILAHKAN KE KASIR');
		redirect('Service/pelunasan/'.$kode,'refresh');
	}

	//action
	function proses()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'title'  => 'Customer',
				'proses' => $this->M_service->proses($kode)->row_array()
			);
		$this->load->view('Service/proses', $data);

	}
	function konfirmasi()
	{
		$kode = $this->uri->segment(3);

		$proses = $this->M_service->konfirmasi($kode)->row_array();
		if (!$proses) {
			$this->session->set_flashdata('gagal', 'Data tidak ditemukan');
			redirect('Service/cos_proses');
		}

		// Update follow up jika belum
		if ($proses['trans_status'] == 'Konfirmasi' && (empty($proses['last_follow_up']) || $proses['follow_up_count'] === null)) {
			$this->db->where('trans_kode', $kode);
			$this->db->update('transaksi', array('last_follow_up' => date('Y-m-d H:i:s'), 'follow_up_count' => 0));
		}

		$vocher = $this->M_service->GetVocherBy($kode)->row_array();
		if (!$vocher) {
			$vocher = array('voc_status' => '0');
		}

		$data = array(
				'title'  => 'Customer',
				'proses' => $proses,
				'data'	 => $this->M_service->GetTindakanBy($kode),
				'vocher' => $vocher
			);
		$this->load->view('Service/konfirmasi', $data);

	}
	function batal_tindakan()
	{
		$kode_trans = $this->uri->segment(3);
		$kode_tdkn  = $this->uri->segment(4);

		$trans = $this->db->get_where('transaksi', array('trans_kode' => $kode_trans))->row_array();
		$tdkn  = $this->db->get_where('tindakan', array('tdkn_kode' => $kode_tdkn))->row_array();

		$up_trans = array('trans_total' => $trans['trans_total'] - $tdkn['tdkn_subtot'], );
		$this->M_service->up_knf_trans($kode_trans,$up_trans);

		$this->db->delete('tindakan', array('tdkn_kode' => $kode_tdkn));

		$this->session->set_flashdata('sukses', 'TINDAKAN DIHAPUS');
		redirect('Service/konfirmasi/'.$kode_trans,'refresh');
	}
	function vocer()
	{
		$kode = $this->input->post('kode');
		$data = array(
				'trans_kode' => $kode, 
				'voc_jumlah' => str_replace('.', '', $this->input->post('vocer')),
				'voc_tanggal'=> date('Y-m-d'),
				'voc_jam'	 => date('H:i:s'),
				'voc_status' => 'ON'
			);
		$this->M_service->vocer($data);

		$this->session->set_flashdata('sukses', 'DI AJUKAN');
		redirect('Service/konfirmasi/'.$kode,'refresh');
	}
	function save_dp()
	{
		$kode = $this->input->post('kode');

		$detail = array(
				'trans_kode'		=> $kode,
				'kry_kode' 			=> $this->session->userdata('kode'),
				'dtl_jml_bayar' 	=> str_replace('.', '', $this->input->post('dp')),
				'dtl_jenis_bayar' 	=> 'TRANFER',
				'dtl_bank' 			=> $this->input->post('bank'),
				'dtl_status' 		=> 'DP',
				'dtl_tanggal' 		=> date('Y-m-d'),
				'dtl_jam' 			=> date('H:i:s'),
				'dtl_stt_stor'		=> 'Menunggu'
			);
		$this->M_service->save_dp($detail);

		$trans = array('trans_status' => 'Pelunasan', );
		$this->M_service->update_trans($trans,$kode);

		// Update order_list status to 'repairing' when DP is paid (only if order_list exists)
		$this->db->select('cos_kode');
		$this->db->from('transaksi');
		$this->db->where('trans_kode', $kode);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {
			$cos_kode = $result->cos_kode;
			// Check if order_list exists for this cos_kode
			$this->db->where('cos_kode', $cos_kode);
			$order_exists = $this->db->get('order_list')->num_rows();
			if ($order_exists > 0) {
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'repairing'));
			}
		}

		$this->session->set_flashdata('sukses', 'DI SIMPAN');
		redirect('Service/cos_konf','refresh');
	}
	function pelunasan()
	{
		$kode = $this->uri->segment(3);

		if (!$kode) {
			redirect('Service/cos_pelunasan');
		}

		$proses = $this->M_service->pelunasan($kode)->row_array();
		if (!$proses) {
			$this->session->set_flashdata('gagal', 'Data tidak ditemukan');
			redirect('Service/cos_pelunasan');
		}

		$data = array(
				'title'  => 'Customer',
				'proses' => $proses,
				'data'	 => $this->M_service->GetTindakanBy($proses['trans_kode'])
			);
		$this->load->view('Service/pelunasan', $data);
	}

	//print

	function print_tts()
	{
		$kode = $this->uri->segment(3);

		$row = $this->M_service->printe($kode)->row_array();
		if (!$row) {
			$this->session->set_flashdata('gagal', 'Data tidak ditemukan');
			redirect('Service/cos_baru');
		}
		$data = array('data' => $row);
		$this->load->view('Service/print-tts',$data);
	}

	//pembayaran
	function pembayaran()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'dp') {
			$custom = $this->M_service->get_customers_by_status('Pelunasan');
		} elseif ($segment == 'lunas') {
			$custom = $this->M_service->get_customers_by_status('Lunas');
		} else {
			$custom = $this->M_service->GetCustom();
		}
		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $custom,
			'no'		=> $segment,
			'lap_bayar' => $this->M_service->lap_bayar(),
			'role'		=> 'cs',
			'filter'	=> $segment
			 );
		$this->load->view('Kasir/pembayaran',$data);
	}
	function cari()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $this->M_service->Histori($kode),
			'no'		=> $this->uri->segment(3),
			'trans'		=> $this->M_service->trans($kode)->row_array(),
			'bayar'		=> $this->M_service->Histori($kode)->row_array(),
			'tindakan'	=> $this->M_service->tindakan($kode),
			'lap_bayar' => $this->M_service->lap_bayar(),
			'vocher' 	=> $this->M_service->GetVocherBy($kode)->row_array(),
			'role'		=> 'cs'
			 );
		$this->load->view('Kasir/cari',$data);

	}
	function vocer_cari()
	{
		$kode = $this->input->post('kode');

		$data = array(
				'trans_kode' => $kode,
				'voc_jumlah' => str_replace('.', '', $this->input->post('vocer')),
				'voc_tanggal'=> date('Y-m-d'),
				'voc_jam'	 => date('H:i:s'),
				'voc_status' => 'ON'
			);
		$this->M_service->vocer($data);

		$this->session->set_flashdata('sukses', 'DI AJUKAN');
		redirect('Service/cari/'.$kode,'refresh');
	}
	function pelunasan_save()
	{
		$kode = $this->input->post('kode');

		$custom = $this->db->get_where('transaksi',array('trans_kode' => $kode))->row_array();

		if ($custom['trans_status'] == 'Lunas') {
			$this->session->set_flashdata('gagal', 'Customer ini sudah melakukan pelunasan');
			redirect('Service/cari/'.$kode,'refresh');
		} else {
			$detail = array(
					'trans_kode'		=> $kode,
					'kry_kode' 			=> $this->session->userdata('kode'),
					'dtl_jml_bayar' 	=> $this->input->post('lunas'),
					'dtl_jenis_bayar' 	=> 'TUNAI',
					'dtl_bank' 			=> '-',
					'dtl_status' 		=> 'PELUNASAN',
					'dtl_tanggal' 		=> date('Y-m-d'),
					'dtl_jam' 			=> date('H:i:s'),
					'dtl_stt_stor'		=> 'Disetorkan'
				);
			$this->M_service->save_dp($detail);

			$trans = array('trans_status' => 'Lunas', );
			$this->M_service->update_trans($trans,$kode);

			// Update order_list status to 'repairing' if exists and not already completed
			$this->db->select('cos_kode');
			$this->db->from('transaksi');
			$this->db->where('trans_kode', $kode);
			$query = $this->db->get();
			$result = $query->row();
			if ($result) {
				$cos_kode = $result->cos_kode;
				// Check if order_list exists for this cos_kode
				$this->db->where('cos_kode', $cos_kode);
				$order_exists = $this->db->get('order_list')->num_rows();
				if ($order_exists > 0) {
					// Get current status
					$this->db->select('trans_status');
					$this->db->where('cos_kode', $cos_kode);
					$current_status = $this->db->get('order_list')->row()->trans_status;
					// Only update if not already 'service_completed'
					if ($current_status != 'service_completed') {
						$this->db->where('cos_kode', $cos_kode);
						$this->db->update('order_list', array('trans_status' => 'repairing'));
					}
				} else {
					// BAYAR LUNAS LANGSUNG: Buat order_list baru dengan status repairing jika belum ada
					// Ambil data dari transaksi dan costomer
					$this->db->select('transaksi.*, costomer.cos_nama, costomer.cos_alamat, costomer.cos_hp');
					$this->db->from('transaksi');
					$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
					$this->db->where('transaksi.trans_kode', $kode);
					$trans_data = $this->db->get()->row();

					if ($trans_data) {
						$order_data = array(
							'trans_kode' => $kode,
							'cos_kode' => $trans_data->cos_kode,
							'kry_kode' => null,
							'trans_total' => $trans_data->trans_total,
							'trans_discount' => $trans_data->trans_discount ?? 0,
							'trans_tanggal' => $trans_data->trans_tanggal,
							'trans_status' => 'repairing',
							'merek' => '', // Kosong dulu, bisa diupdate nanti
							'device' => '',
							'status_garansi' => '',
							'seri' => '',
							'ket_keluhan' => '',
							'email' => '',
							'alamat' => $trans_data->cos_alamat ?? '',
							'created_at' => date('Y-m-d H:i:s')
						);
						$this->db->insert('order_list', $order_data);
						log_message('info', 'Created order_list for direct Lunas payment: ' . $kode);
					}
				}
			}

			$this->session->set_flashdata('sukses', 'DI LUNASI');
			redirect('Service/cari/'.$kode,'refresh');
		}

	}
	function save_dp_cari()
	{
	 	$kode = $this->input->post('kode');

		$detail = array(
				'trans_kode'		=> $kode,
				'kry_kode' 			=> $this->session->userdata('kode'),
				'dtl_jml_bayar' 	=> str_replace('.', '', $this->input->post('dp')),
				'dtl_jenis_bayar' 	=> 'TUNAI',
				'dtl_bank' 			=> '-',
				'dtl_status' 		=> 'DP',
				'dtl_tanggal' 		=> date('Y-m-d'),
				'dtl_jam' 			=> date('H:i:s'),
				'dtl_stt_stor'		=> 'Disetorkan'
			);
		$this->M_service->save_dp($detail);

		$trans = array('trans_status' => 'Pelunasan', );
		$this->M_service->update_trans($trans,$kode);

		// Update order_list status to 'repairing' when DP is paid (only if order_list exists)
		$this->db->select('cos_kode');
		$this->db->from('transaksi');
		$this->db->where('trans_kode', $kode);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {
			$cos_kode = $result->cos_kode;
			// Check if order_list exists for this cos_kode
			$this->db->where('cos_kode', $cos_kode);
			$order_exists = $this->db->get('order_list')->num_rows();
			if ($order_exists > 0) {
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'repairing'));
			}
		}

		$this->session->set_flashdata('sukses', 'DI SIMPAN');
		redirect('Service/cari/'.$kode,'refresh');
	}

	//follow up konfirmasi
	function check_follow_up()
	{
		$this->db->select('transaksi.*, costomer.cos_nama, costomer.cos_hp');
		$this->db->from('transaksi');
		$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
		$this->db->where('trans_status', 'Konfirmasi');
		$this->db->where('TIMESTAMPDIFF(DAY, last_follow_up, NOW()) >', 3);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			// Kirim WA reminder
			$message = "SALAM SATU HATI,\n\nHALO {$row->cos_nama},\n\nKami masih menunggu konfirmasi DEAL atau TIDAK untuk servis unit Anda. Silakan hubungi kami atau balas pesan ini.\n\nTerima Kasih.";
			// Asumsi ada method sendWA, atau gunakan API
			// Untuk sekarang, log saja
			log_message('info', 'Follow up sent to ' . $row->cos_hp . ': ' . $message);
			// Update last_follow_up dan increment count
			$this->db->where('trans_kode', $row->trans_kode);
			$this->db->update('transaksi', array('last_follow_up' => date('Y-m-d H:i:s'), 'follow_up_count' => $row->follow_up_count + 1));
		}
	}

	function send_follow_up_manual($kode)
	{
		$this->db->select('transaksi.*, costomer.cos_nama, costomer.cos_hp');
		$this->db->from('transaksi');
		$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
		$this->db->where('trans_kode', $kode);
		$row = $this->db->get()->row();
		if ($row) {
			// Update count
			$this->db->where('trans_kode', $kode);
			$this->db->update('transaksi', array('last_follow_up' => date('Y-m-d H:i:s'), 'follow_up_count' => $row->follow_up_count + 1));
			log_message('info', 'Manual follow up sent for ' . $kode);
			$this->session->set_flashdata('sukses', 'Follow up dikirim dan count diupdate.');
		} else {
			$this->session->set_flashdata('gagal', 'Data tidak ditemukan.');
		}
		$redirect = $this->input->get('redirect');
		if ($redirect == 'cos_konf') {
			redirect('Service/cos_konf');
		} else {
			redirect('Service/konfirmasi/'.$kode);
		}
	}

	function get_tindakan_ajax()
	{
		$trans_kode = $this->input->post('trans_kode');
		$tindakan = $this->M_service->GetTindakanBy($trans_kode)->result_array();
		echo json_encode($tindakan);
	}

	//laporan

	function laporan()
	{
		$kode = $this->session->userdata('kode');
		$data = array(
				'title'   => 'Laporan',
				'cs'	  => $this->M_service->cs_laporan($kode)->row_array(),
				'dp'	  => $this->M_service->ds_dp_NonTunai(),
				'jml_dp'  => $this->M_service->jml_dp(),
				'tot_bca' => $this->M_service->tot_bca(),
				'jml_bca' => $this->M_service->jml_bca(),
				'tot_bri' => $this->M_service->tot_bri(),
				'jml_bri' => $this->M_service->jml_bri()
			);
		$this->load->view('Service/laporan',$data);
	}

	private function processCanvasImage($imgData) {
		if (!$imgData) return '';

		$img = str_replace('data:image/png;base64,', '', $imgData);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);

		$uploadDir = './uploads/pola/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}

		$fileName = 'pola_' . time() . '.png';
		file_put_contents($uploadDir . $fileName, $data);

		return $fileName;
	}

}

/* End of file Service.php */
/* Location: ./application/controllers/Service.php */