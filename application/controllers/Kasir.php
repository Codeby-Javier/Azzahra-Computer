<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kasir');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Kasir') {
	    		$url=base_url();
	      		redirect($url);
	    	}	    	
	    }
	}

	public function index()
	{
		$data = array(
			'title' 	=> 'Customer',
			'custom'	=> $this->M_kasir->GetCustom(),
			'no'		=> $this->uri->segment(3)
			 );
		$this->load->view('Kasir/customer',$data);
	}
	//Pembayaran
	function pembayaran()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'dp') {
			$this->db->select('*');
			$this->db->from('costomer');
			$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
			$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
			$this->db->where('transaksi.trans_status', 'Pelunasan');
			$custom = $this->db->get();
		} elseif ($segment == 'lunas') {
			$this->db->select('*');
			$this->db->from('costomer');
			$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
			$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
			$this->db->where('transaksi.trans_status', 'Lunas');
			$custom = $this->db->get();
		} else {
			$custom = $this->M_kasir->GetCustom();
		}
		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $custom,
			'no'		=> $segment,
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'role'		=> 'kasir',
			'filter'	=> $segment
			 );
		$this->load->view('Kasir/pembayaran',$data);
	}
	function cari()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $this->M_kasir->Histori($kode),
			'no'		=> $this->uri->segment(3),
			'trans'		=> $this->M_kasir->trans($kode)->row_array(),
			'bayar'		=> $this->M_kasir->Histori($kode)->row_array(),
			'tindakan'	=> $this->M_kasir->tindakan($kode),
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'vocher' 	=> $this->M_kasir->GetVocherBy($kode)->row_array(),
			'role'		=> 'kasir'
			 );
		$this->load->view('Kasir/cari',$data);

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
		$this->M_kasir->vocer($data);

		$this->session->set_flashdata('sukses', 'DI AJUKAN');
		redirect('Kasir/cari/'.$kode,'refresh');
	}
	function pelunasan()
	{
		$kode = $this->input->post('kode');

		$custom = $this->db->get_where('transaksi',array('trans_kode' => $kode))->row_array();

		if ($custom['trans_status'] == 'Lunas') {
			$this->session->set_flashdata('gagal', 'Customer ini sudah melakukan pelunasan');
			redirect('Kasir/cari/'.$kode,'refresh');
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
			$this->M_kasir->pelunasan($detail);

			$trans = array('trans_status' => 'Lunas', );
			$this->M_kasir->update_trans($trans,$kode);

			$this->session->set_flashdata('sukses', 'DI LUNASI');
			redirect('Kasir/cari/'.$kode,'refresh');
		}	

	}
	function save_dp()
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
		$this->M_kasir->save_dp($detail);

		$trans = array('trans_status' => 'Pelunasan', );
		$this->M_kasir->update_trans($trans,$kode);

		$this->session->set_flashdata('sukses', 'DI SIMPAN');
		redirect('Kasir/cari/'.$kode,'refresh');
	}

	//return transaksi
	function trans_return()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Return Pembayaran',
			'custom'	=> $this->M_kasir->Histori($kode),
			'no'		=> $this->uri->segment(3),
			'trans'		=> $this->M_kasir->trans($kode)->row_array(),
			'bayar'		=> $this->M_kasir->Histori($kode)->row_array(),
			'tindakan'	=> $this->M_kasir->tindakan($kode),
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'vocher' 	=> $this->M_kasir->GetVocherBy($kode)->row_array()
			 );
		$this->load->view('Kasir/trans-return',$data);
	}
	function bayar_return()
	{
		$kode = $this->input->post('kode');

		$custom = $this->db->get_where('transaksi',array('trans_kode' => $kode))->row_array();

		$data = $this->db->get_where('transaksi_detail', array(
															'trans_kode' => $kode,
															'dtl_status' => 'DP',
															)
									)->row_array();
		$kd_dtl 	= $data['dtl_kode'];
		$jml_return = $data['dtl_jml_bayar'];

		if ($custom['trans_status'] == 'Lunas') {
			$this->session->set_flashdata('gagal', 'Customer ini sudah melakukan pelunasan');
			redirect('Kasir/trans_return/'.$kode,'refresh');
		} else {
			$returnn = array('dtl_jenis_bayar' => 'RETURN', );
			$this->M_kasir->returnn($kd_dtl,$returnn);

			$detail = array(
					'trans_kode'		=> $kode,
					'kry_kode' 			=> $this->session->userdata('kode'), 
					'dtl_jml_bayar' 	=> '50000',
					'dtl_jenis_bayar' 	=> 'TUNAI',
					'dtl_bank' 			=> '-',
					'dtl_status' 		=> 'PELUNASAN',
					'dtl_tanggal' 		=> date('Y-m-d'),
					'dtl_jam' 			=> date('H:i:s'),
					'dtl_stt_stor'		=> 'Disetorkan'
				);
			$this->M_kasir->pelunasan($detail);

			$save_ret = array(
				'trans_kode'  => $kode,
				'dtl_kode' 	  => $kd_dtl,
				'ret_jml' 	  => $jml_return,
				'ret_tanggal' => date('Y-m-d'),
				'ret_jam' 	  => date('H:i:s'),
			);
			$this->M_kasir->save_ret($save_ret);

			$trans = array('trans_status' => 'Lunas', );
			$this->M_kasir->update_trans($trans,$kode);

			$this->session->set_flashdata('sukses', 'DI LUNASI DAN KEMBALIKAN DP YANG SUDAH DI BAYARKAN');
			redirect('Kasir/trans_return/'.$kode,'refresh');
		}
		
	}

	//print
	function cetak()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'data' 		 => $this->M_kasir->cetak($kode)->row_array(),
				'bayar' 	 => $this->M_kasir->bayar($kode)->row_array(),
				'pembayaran' => $this->M_kasir->cetak_pembayaran($kode),
				'barang'	 => $this->M_kasir->barang($kode)
			);
		$this->load->view('Kasir/print-pembayaran',$data);
	}
	function cetak_return()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'data' 		 => $this->M_kasir->cetak($kode)->row_array(),
				'bayar' 	 => $this->M_kasir->bayar($kode)->row_array(),
				'pembayaran' => $this->M_kasir->cetak_pembayaran($kode),
				'barang'	 => $this->M_kasir->barang($kode)
			);
		$this->load->view('Kasir/print-return',$data);
	}

	//laporan
	function laporan()
	{
		$kode = $this->session->userdata('kode');
		$data = array(
				'title'   	 => 'Laporan',
				'kasir'	  	 => $this->M_kasir->ks_laporan($kode)->row_array(),
				'dp'	  	 => $this->M_kasir->DP_Tunai(),
				'sum_dp'  	 => $this->M_kasir->Sum_DP_Tunai(),
				'lunas'	  	 => $this->M_kasir->Lunas_Tunai(),
				'sum_lunas'  => $this->M_kasir->Sum_Lunas_Tunai(),
				'return'	 => $this->M_kasir->jml_return(),
				'sum_return' => $this->M_kasir->sum_return(),
			);
		$this->load->view('Kasir/laporan',$data);
	}


}

/* End of file Kasir.php */
/* Location: ./application/controllers/Kasir.php */