<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuickService extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_service');
		$this->load->model('M_quick_service');
		$this->load->model('M_order');
		if($this->session->userdata('masuk') != TRUE){
		     if ($this->input->is_ajax_request()) {
		       echo json_encode(['error' => 'Session expired']);
		       exit;
		     } else {
		       $url=base_url();
		       redirect($url);
		     }
		   }else{
		   	if (!in_array($this->session->userdata('level'), ['Customer Service', 'Kasir'])) {
		   		if ($this->input->is_ajax_request()) {
		   			echo json_encode(['error' => 'Unauthorized']);
		   			exit;
		   		} else {
		   			$url=base_url();
		      		redirect($url);
		   		}
		   	}
		   }
	}

	public function index()
	{
		$data = array(
				'title' 	 	 => 'Quick Service Dashboard',
				'cos_baru' 		 => $this->M_service->ds_cos_baru(),
				'cos_proses' 	 => $this->M_service->ds_cos_proses(),
				'cos_knf'	 	 => $this->M_service->ds_cos_knf(),
				'cos_pelunasan'	 => $this->M_service->ds_cos_pelunasan(),
				'Dp_NonTunai'	 => $this->M_service->ds_dp_NonTunai(),
				'Dp_Tunai'	 	 => $this->M_service->ds_dp_Tunai(),
				'bca'	 		 => $this->M_service->ds_bca(),
				'bri'	 		 => $this->M_service->ds_bri()
			);
		$this->load->view('QuickService/dashboard', $data);
	}
	function cos_baru()
	{
		$data = array(
				'title' => 'Quick Service',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_quick_service->cos_baru($this->session->userdata('kode'))
			);
		$this->load->view('QuickService/cos-baru', $data);
	}
	function cos_proses()
	{
		$data = array(
				'title' => 'Quick Service',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_proses()
			);
		$this->load->view('QuickService/cos-proses', $data);
	}
	function cos_konf()
	{
		$data = array(
				'title' => 'Quick Service',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_konf()
			);
		$this->load->view('QuickService/cos-konf', $data);
	}
	function cos_pelunasan()
	{
		$data = array(
				'title' => 'Quick Service',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_pelunasan()
			);
		$this->load->view('QuickService/cos-pelunasan', $data);
	}
	function cos_lunas()
	{
		$data = array(
				'title' => 'Quick Service',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_service->cos_lunas()
			);
		$this->load->view('QuickService/cos-lunas', $data);
	}

	function ajax_cos_lunas()
	{
		$draw = intval($this->input->get('draw'));
		$start = intval($this->input->get('start'));
		$length = intval($this->input->get('length'));
		$search = $this->input->get('search')['value'];
		$order_column = $this->input->get('order')[0]['column'];
		$order_dir = $this->input->get('order')[0]['dir'];

		$columns = array(
			0 => 'transaksi.trans_kode',
			1 => 'costomer.cos_nama',
			2 => 'costomer.cos_alamat',
			3 => 'costomer.cos_hp',
			4 => 'costomer.cos_hp'
		);

		$this->db->select('costomer.*, transaksi.trans_kode');
		$this->db->from('costomer');
		$this->db->join('transaksi', 'costomer.id_costomer = transaksi.cos_kode');
		$this->db->join('karyawan', 'transaksi.kry_kode = karyawan.kry_kode');
		$this->db->where('transaksi.trans_status', 'Lunas');

		$totalRecords = $this->db->count_all_results('', false);

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('costomer.cos_nama', $search);
			$this->db->or_like('costomer.cos_alamat', $search);
			$this->db->or_like('costomer.cos_hp', $search);
			$this->db->or_like('transaksi.trans_kode', $search);
			$this->db->group_end();
		}

		$totalFiltered = $this->db->count_all_results('', false);

		$this->db->order_by($columns[$order_column], $order_dir);
		$this->db->limit($length, $start);
		$query = $this->db->get();
		$data = array();

		$no = $start + 1;
		foreach ($query->result() as $row) {
			$hp = $row->cos_hp;
			$masked_hp = substr($hp, 0, -4) . 'XXXX';

			$actions = '<div class="flex sm:justify-center items-center">
							<a href="' . site_url('QuickService/print_tts/' . $row->id_costomer) . '" target="_blank" class="button px-2 mr-1 mb-2 bg-theme-6 text-white">
								<span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
							</a>
							<a href="javascript:;" onclick="sendToWA(\'' . site_url('Cetak/print_tts/' . $row->trans_kode) . '\', \'' . $row->cos_hp . '\', \'' . $row->cos_nama . '\', \'' . $row->id_costomer . '\', \'' . $row->trans_kode . '\')" class="button px-2 mr-1 mb-2 bg-green-500 text-white">
								<span class="w-5 h-5 flex items-center justify-center"> <i data-feather="message-circle" class="w-4 h-4"></i> </span>
							</a>
						</div>';

			$data[] = array(
				'no' => $no++,
				'invoice' => $row->trans_kode,
				'nama' => $row->cos_nama,
				'alamat' => $row->cos_alamat,
				'hp' => $masked_hp,
				'actions' => $actions
			);
		}

		$response = array(
			"draw" => $draw,
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalFiltered,
			"data" => $data
		);

		echo json_encode($response);
	}

	function ajax_cos_lunas_original()
	{
		log_message('info', 'ajax_cos_lunas_original called');
		$draw = intval($this->input->get('draw'));
		$start = intval($this->input->get('start'));
		$length = intval($this->input->get('length'));
		$search = $this->input->get('search')['value'];
		$order_column = $this->input->get('order')[0]['column'];
		$order_dir = $this->input->get('order')[0]['dir'];

		$columns = array(
			0 => 'transaksi.trans_kode',
			1 => 'costomer.cos_nama',
			2 => 'costomer.cos_alamat',
			3 => 'costomer.cos_hp',
			4 => 'costomer.cos_tanggal',
			5 => 'costomer.cos_jam'
		);

		$this->db->select('costomer.*, transaksi.trans_kode, transaksi.trans_status');
		$this->db->from('costomer');
		$this->db->join('transaksi', 'costomer.id_costomer = transaksi.cos_kode');
		$this->db->where('transaksi.trans_status', 'Lunas');
		$this->db->group_by('costomer.id_costomer');

		$totalRecords = $this->db->count_all_results('', false);

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('costomer.cos_nama', $search);
			$this->db->or_like('costomer.cos_alamat', $search);
			$this->db->or_like('costomer.cos_hp', $search);
			$this->db->or_like('transaksi.trans_kode', $search);
			$this->db->group_end();
		}

		$totalFiltered = $this->db->count_all_results('', false);

		$this->db->order_by($columns[$order_column], $order_dir);
		$this->db->limit($length, $start);
		$query = $this->db->get();
		log_message('info', 'ajax_cos_lunas_original query num_rows: ' . $query->num_rows());
		$data = array();

		$no = $start + 1;
		foreach ($query->result() as $row) {
			$nama = '<div class="font-medium whitespace-no-wrap">' . $row->cos_nama . '</div><div class="text-gray-600 text-xs whitespace-no-wrap">STATUS : ' . $row->trans_status . '</div>';
			$tanggal = '<div class="font-medium whitespace-no-wrap">' . date('d-m-Y', strtotime($row->cos_tanggal)) . '</div><div class="text-gray-600 text-xs whitespace-no-wrap">JAM : ' . $row->cos_jam . '</div>';
			$actions = '<a href="' . site_url('Service/cos_histori/' . $row->trans_kode) . '" ><button class="button px-2 mr-1 mb-2 bg-theme-1 text-white"><span class="w-5 h-5 flex items-center justify-center"> <i data-feather="hard-drive" class="w-4 h-4"></i> </span></button></a>';

			$data[] = array(
				'no' => $no++,
				'invoice' => $row->cos_kode,
				'nama' => $nama,
				'alamat' => $row->cos_alamat,
				'hp' => $row->cos_hp,
				'tanggal' => $tanggal,
				'actions' => $actions
			);
		}

		$response = array(
			"draw" => $draw,
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalFiltered,
			"data" => $data
		);

		echo json_encode($response);
	}
	function cos_histori()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'title'  	 => 'Quick Service',
				'proses' 	 => $this->M_service->konfirmasi($kode)->row_array(),
				'data'	 	 => $this->M_service->GetTindakanBy($kode),
				'custom' 	 => $this->M_service->histori($kode),
				'hist_trans' => $this->M_service->histori_transaksi($kode)->row_array()
			);
		$this->load->view('QuickService/histori', $data);
	}

	//transaksi
	function save_trans()
	{
		log_message('info', 'Starting save_trans process in QuickService');

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
						'cos_pswd' 		 => $this->input->post('pswd_type') === 'text' ? $this->input->post('pswd') : ($this->input->post('pswd_type') === 'pattern_desc' ? $this->input->post('pswd_desc') : ''),
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
				'cos_kode' 		 => $id_costomer,
				'kry_kode' 		 => $this->session->userdata('kode'),
				'trans_total' 	 => '0',
				'trans_discount' => '0',
				'trans_status' 	 => $is_quick_service ? 'Pelunasan' : 'Baru',
				'trans_tanggal'  => date('Y-m-d'),
			);

		$trans_result = $this->M_service->save_trans($trans);
		log_message('info', 'Transaksi insert result: ' . ($trans_result ? 'success' : 'failed'));

		// Insert into order_list
		$order_list = array(
			'trans_kode' 	 => $trans_kode,
			'cos_kode' 		 => $id_costomer,
			'kry_kode' 		 => null,
			'trans_total' 	 => 0,
			'trans_discount' => 0,
			'trans_tanggal'  => date('Y-m-d'),
			'trans_status' 	 => 'QS',
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
			redirect('QuickService/cos_baru','refresh');
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
						'cos_pswd' 		 => $this->input->post('pswd_type') === 'text' ? $this->input->post('pswd') : ($this->input->post('pswd_type') === 'pattern_desc' ? $this->input->post('pswd_desc') : ($this->input->post('pswd_type') === 'pattern_canvas' ? '' : '')),
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
		redirect('QuickService/cos_baru','refresh');
	}
	function save_tindakan()
	{
		$trans_kode = $this->input->post('trans_kode');
		$tdkn_nama = $this->input->post('tdkn_nama');
		$tdkn_qty = $this->input->post('tdkn_qty');
		$tdkn_subtot = $this->input->post('tdkn_subtot');
		$tdkn_ket = $this->input->post('tdkn_ket');

		$data = array(
			'trans_kode' 	=> $trans_kode,
			'tdkn_nama' 	=> $tdkn_nama,
			'tdkn_ket' 		=> $tdkn_ket,
			'tdkn_qty' 		=> $tdkn_qty,
			'tdkn_subtot'  	=> $tdkn_subtot,
			'tdkn_tanggal' 	=> date('Y-m-d'),
			'tdkn_jam'		=> date('H:i:s')
		);

		log_message('info', 'Data to insert into tindakan: ' . json_encode($data));
		$result = $this->M_service->save_tindakan(array($data));
		log_message('info', 'Insert result: ' . $result);

		if ($result !== false && $result > 0) {
			// Update transaction total
			$this->db->select_sum('tdkn_subtot');
			$this->db->from('tindakan');
			$this->db->where('trans_kode', $trans_kode);
			$query = $this->db->get();
			$total = $query->row()->tdkn_subtot ?? 0;

			$this->db->where('trans_kode', $trans_kode);
			$this->db->update('transaksi', array('trans_total' => $total));

			$this->session->set_flashdata('sukses', 'Tindakan berhasil ditambahkan');
		} else {
			log_message('error', 'Failed to insert tindakan data');
			$this->session->set_flashdata('gagal', 'Gagal menambahkan tindakan');
		}
		redirect('QuickService/pelunasan/'.$trans_kode,'refresh');
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
		redirect('QuickService/cos_konf','refresh');
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
		redirect('QuickService/pelunasan/'.$kode,'refresh');
	}

	//action
	function proses()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'title'  => 'Quick Service',
				'proses' => $this->M_service->konfirmasi($kode)->row_array()
			);
		$this->load->view('QuickService/proses', $data);

	}
	function konfirmasi()
	{
		$kode = $this->uri->segment(3);

		$proses = $this->M_service->konfirmasi($kode)->row_array();
		if (!$proses) {
			$this->session->set_flashdata('gagal', 'Data tidak ditemukan');
			redirect('QuickService/cos_proses');
		}

		$vocher = $this->M_service->GetVocherBy($kode)->row_array();
		if (!$vocher) {
			$vocher = array('voc_status' => '0');
		}

		$data = array(
				'title'  => 'Quick Service',
				'proses' => $proses,
				'data'	 => $this->M_service->GetTindakanBy($kode),
				'vocher' => $vocher
			);
		$this->load->view('QuickService/konfirmasi', $data);

	}
	function batal_tindakan()
	{
		$kode_trans = $this->uri->segment(3);
		$kode_tdkn  = $this->uri->segment(4);

		$trans = $this->db->get_where('transaksi', array('trans_kode' => $kode_trans))->row_array();
		$tdkn  = $this->db->get_where('tindakan', array('tdkn_kode' => $kode_tdkn))->row_array();

		$this->db->where('trans_kode', $kode_trans);
		$this->db->set('trans_total', 'trans_total - ' . $tdkn['tdkn_subtot'], FALSE);
		$this->db->update('transaksi');

		$this->db->delete('tindakan', array('tdkn_kode' => $kode_tdkn));

		$this->session->set_flashdata('sukses', 'Tindakan berhasil dihapus');
		redirect('QuickService/pelunasan/'.$kode_trans,'refresh');
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
		redirect('QuickService/konfirmasi/'.$kode,'refresh');
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
		redirect('QuickService/cos_konf','refresh');
	}
	function pelunasan()
	{
		$kode = $this->uri->segment(3);

		// Find by trans_kode, join with costomer via cos_kode = id_costomer
		$this->db->select('costomer.*, transaksi.*, karyawan.*, COALESCE(transaksi_detail.dtl_jml_bayar, 0) as dtl_jml_bayar');
		$this->db->from('transaksi');
		$this->db->join('costomer','transaksi.cos_kode=costomer.id_costomer');
		$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
		$this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode AND transaksi_detail.dtl_status="DP"', 'left');
		$this->db->where('transaksi.trans_kode', $kode);
		$query = $this->db->get();
		$proses = $query->row_array();

		if (!$proses) {
			$data = array(
				'title'  => 'Quick Service',
				'proses' => array(),
				'error'  => 'Data transaksi tidak ditemukan'
			);
			$this->load->view('QuickService/pelunasan', $data);
			return;
		}
		$data = array(
				'title'  => 'Quick Service',
				'proses' => $proses,
				'data'	 => $this->M_service->GetTindakanBy($proses['trans_kode'])
			);
		$this->load->view('QuickService/pelunasan', $data);
	}

	//print

	function print_tts()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'data' => $this->M_service->printe($kode)->row_array(),
			);
		$this->load->view('QuickService/print-tts',$data);
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
			'title' 	=> 'Quick Service',
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
			'title' 	=> 'Quick Service',
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
		redirect('QuickService/cari/'.$kode,'refresh');
	}

	function pembayaran_selesai()
	{
		$trans_kode = $this->input->post('kode'); // Changed from 'trans_kode' to 'kode'
		$lunas = $this->input->post('lunas');
		$jenis_bayar = $this->input->post('jenis_bayar');
		$bank = $this->input->post('bank') ?? '-';

		// Get customer data for WhatsApp
		$proses = $this->M_service->pelunasan($trans_kode)->row_array();

		// Save payment details to database
		$detail = array(
				'trans_kode'		=> $trans_kode,
				'kry_kode' 			=> $this->session->userdata('kode'),
				'dtl_jml_bayar' 	=> $lunas,
				'dtl_jenis_bayar' 	=> $jenis_bayar,
				'dtl_bank' 			=> $bank,
				'dtl_status' 		=> 'PELUNASAN',
				'dtl_tanggal' 		=> date('Y-m-d'),
				'dtl_jam' 			=> date('H:i:s'),
				'dtl_stt_stor'		=> 'Disetorkan'
			);
		$this->M_service->save_dp($detail);
		$dtl_kode = $this->db->insert_id();
		log_message('info', 'Inserted dtl_kode: ' . $dtl_kode . ', trans_kode: ' . $trans_kode);

		// Update transaction status to Lunas
		$trans = array('trans_status' => 'Lunas');
		$this->M_service->update_trans($trans, $trans_kode);

		// CEK: Apakah sudah ada order_list untuk cos_kode ini?
		$this->db->select('cos_kode');
		$this->db->from('transaksi');
		$this->db->where('trans_kode', $trans_kode);
		$cos_kode = $this->db->get()->row()->cos_kode;

		$this->db->where('cos_kode', $cos_kode);
		$order_exists = $this->db->get('order_list')->num_rows();

		if ($order_exists == 0) {
			// BAYAR LUNAS LANGSUNG: Buat order_list baru dengan status repairing dan trans_kode TR baru
			// Ambil data dari transaksi dan costomer
			$this->db->select('transaksi.*, costomer.cos_nama, costomer.cos_alamat, costomer.cos_hp');
			$this->db->from('transaksi');
			$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
			$this->db->where('transaksi.trans_kode', $trans_kode);
			$trans_data = $this->db->get()->row();

			if ($trans_data) {
				// Generate new trans_kode for order_list (TRDDMMYYNNN)
				$date_prefix = 'TR' . date('dmy');
				$this->db->select('MAX(CAST(SUBSTRING(trans_kode, -3) AS UNSIGNED)) as max_num');
				$this->db->from('order_list');
				$this->db->like('trans_kode', $date_prefix, 'after');
				$query = $this->db->get();
				$result = $query->row();
				$next_num = ($result->max_num ? $result->max_num : 0) + 1;
				$new_trans_kode = $date_prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);

				$order_data = array(
					'trans_kode' => $new_trans_kode,
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
				log_message('info', 'Created order_list for direct Lunas payment with new TR trans_kode: ' . $new_trans_kode . ' (original trans_kode: ' . $trans_kode . ')');
			}
		} else {
			// Jika sudah ada order_list, update trans_status saja
			$this->db->where('cos_kode', $cos_kode);
			$this->db->update('order_list', array('trans_status' => 'repairing'));
			log_message('info', 'Updated order_list trans_status to repairing for cos_kode: ' . $cos_kode);
		}

		if ($this->input->is_ajax_request()) {
			echo json_encode(['status' => 'success']);
			exit;
		} else {
			// Process phone number for WhatsApp
			$hp = $proses['cos_hp'];
			if (substr($hp, 0, 1) == '0') {
				$hp = '62' . substr($hp, 1);
			}
			$hp = preg_replace('/\D/', '', $hp);

			// Get service details
			$tindakan = $this->M_service->GetTindakanBy($trans_kode)->result_array();
			$details = '';
			foreach ($tindakan as $key) {
				$details .= $key['tdkn_nama'] . ' - Qty: ' . $key['tdkn_qty'] . ' - Rp ' . number_format($key['tdkn_subtot'], 0, ',', '.') . ',-\n';
			}

			$transStatus = 'Lunas';

			// Create WhatsApp message
			$message = "SALAM SATU HATI,\n\nHALO {$proses['cos_nama']},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Detail:\n{$details}Untuk Status Transaksi nya:\n{$transStatus}\n\nDownload aplikasi AzzaService di Playstore lalu Booking Service, Jangan lupa untuk memberikan rating pada aplikasi AzzaService ya! ðŸ˜Š\n\nAnda dapat melihat tanda terima digital di:\nðŸ‘‰ https://dashboard.azzahracomputertegal.com/Cetak/download/{$trans_kode}\n\nTERIMA KASIH";

			// Create WhatsApp URL
			$wa_url = "https://wa.me/{$hp}?text=" . urlencode($message);

			// Redirect directly to WhatsApp
			redirect($wa_url);
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
		redirect('QuickService/cari/'.$kode,'refresh');
	}

	//laporan

	function laporan()
	{
		$kode = $this->session->userdata('kode');
		$data = array(
				'title'   => 'Quick Service',
				'cs'	  => $this->M_service->cs_laporan($kode)->row_array(),
				'dp'	  => $this->M_service->ds_dp_NonTunai(),
				'jml_dp'  => $this->M_service->jml_dp(),
				'tot_bca' => $this->M_service->tot_bca(),
				'jml_bca' => $this->M_service->jml_bca(),
				'tot_bri' => $this->M_service->tot_bri(),
				'jml_bri' => $this->M_service->jml_bri()
			);
		$this->load->view('QuickService/laporan',$data);
	}



}

/* End of file QuickService.php */
/* Location: ./application/controllers/QuickService.php */