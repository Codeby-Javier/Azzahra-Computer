<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_service extends CI_Model {

	//dashboard
	function ds_cos_baru()
	{
		$this->db->where('trans_status', 'Baru');
		return $this->db->get('transaksi');
	}
	
	function ds_cos_proses()
	{
		$this->db->where('trans_status', 'Diproses');
		return $this->db->get('transaksi');	
	}
	function ds_cos_knf()
	{
		$this->db->where('trans_status', 'Konfirmasi');
		return $this->db->get('transaksi');
	}
	function ds_cos_pelunasan()
	{
		$this->db->where('trans_status', 'Pelunasan');
		return $this->db->get('transaksi');
	}
	function ds_dp_NonTunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_dp_Tunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_bca()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_bri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}

	//transaksi
	function save_custom($customer)
	{
		return $this->db->insert('costomer', $customer);
	}
	function save_trans($trans)
	{
		return $this->db->insert('transaksi', $trans);
	}
	function save_order_list($order_list)
	{
		return $this->db->insert('order_list', $order_list);
	}
	function update_custom($customer,$kode)
	{
		$this->db->where('id_costomer', $kode);
		return $this->db->update('costomer', $customer);
	}
	function update_trans($trans,$kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}
	function save_tindakan($data)
	{
		return $this->db->insert_batch('tindakan', $data);
	}
	function status($status,$kd_trans)
	{
		$this->db->where('trans_kode', $kd_trans);
		return $this->db->update('transaksi', $status);
	}
	//liat
	function cos_baru()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Baru');
	    $this->db->group_by('costomer.id_costomer');
	    $query = $this->db->get();
	    return $query;
	}
	
	function cos_proses()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where_in('transaksi.trans_status', ['Diproses', 'Pelunasan']);
	    $query = $this->db->get();
	    return $query;
	}
	function cos_konf()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Konfirmasi');
	    $query = $this->db->get();
	    return $query;
	}
	function cos_pelunasan()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->join('order_list','transaksi.cos_kode=order_list.cos_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $query = $this->db->get();
	    return $query;
	}

	function cos_lunas()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Lunas');
	    $query = $this->db->get();
	    return $query;
	}
	function all()
	{
		return $this->db->get('costomer');
	}
	//action
	function proses($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.cos_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function konfirmasi($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}

	//batal teransaksi
	function up_btl_detail($kode,$up_btl_tdkn)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('tindakan', $up_btl_tdkn);
	}
	function save_btl_detail($save_tdkn)
	{
		return $this->db->insert('tindakan', $save_tdkn);
	}
	function up_btl_trans($kode,$trans)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}

	//return pembayaran
	function up_ret_detail($kode,$up_ret_tdkn)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('tindakan', $up_ret_tdkn);
	}
	function save_ret_detail($save_tdkn)
	{
		return $this->db->insert('tindakan', $save_tdkn);
	}
	function up_ret_trans($kode,$trans)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}
	

	function up_knf_trans($kode_trans,$up_trans)
	{
		$this->db->where('trans_kode', $kode_trans);
		return $this->db->update('transaksi', $up_trans);
	}
	function up_knf_tdkn($kode_tdkn,$up_tdkn)
	{
		$this->db->where('tdkn_kode', $kode_tdkn);
		return $this->db->update('tindakan', $up_tdkn);
	}
	function GetTindakanBy($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('tindakan');
	}
	function vocer($data)
	{
		return $this->db->insert('vocer', $data);
	}
	function GetVocherBy($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('vocer');
	}
	function save_dp($detail)
	{
		return $this->db->insert('transaksi_detail', $detail);
	}
	function pelunasan($kode)
	{
		$this->db->select('costomer.*, transaksi.*, karyawan.*, COALESCE(transaksi_detail.dtl_jml_bayar, 0) as dtl_jml_bayar');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode AND transaksi_detail.dtl_status="DP"', 'left');
	    $this->db->where('costomer.id_costomer', $kode);
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $query = $this->db->get();
	    return $query;
	}
	function histori($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}
	function histori_transaksi($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi');
	}
	function GetCustom()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status !=', 'Lunas');
	    $query = $this->db->get();
	    return $query;
	}
	function trans($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function tindakan($kode)
	{
	 	$this->db->where('trans_kode', $kode);
	 	return $this->db->get('tindakan');
	}
	function lap_bayar()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi_detail.dtl_tanggal', date('Y-m-d'));
	    $query = $this->db->get();
	    return $query;
	}

	//print

	function printe($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->where('costomer.id_costomer', $kode);
	    $query = $this->db->get();
	    return $query;
	}

	//laporan

	function cs_laporan($kode)
	{
		$this->db->where('kry_kode', $kode);
		return $this->db->get('karyawan');
	}
	function jml_dp()
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_detail');
	}
	function tot_bca()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function jml_bca()
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_detail');
	}
	function tot_bri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function jml_bri()
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_detail');
	}

	function get_customers_by_status($status)
	{
		$this->db->select('*');
		$this->db->from('costomer');
		$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
		$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
		$this->db->where('transaksi.trans_status', $status);
		$query = $this->db->get();
		return $query;
	}
}

/* End of file M_service.php */
/* Location: ./application/models/M_service.php */