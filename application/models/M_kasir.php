<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kasir extends CI_Model {

	function GetCustom()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi.trans_status !=', 'Lunas');
	    $query = $this->db->get();
	    return $query;
	}
	function trans($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    //$this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function tindakan($kode)
	{
	 	$this->db->where('trans_kode', $kode);
	 	return $this->db->get('tindakan');
	}
	function Histori($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
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
	function pelunasan($detail)
	{
		return $this->db->insert('transaksi_detail', $detail);
	}
	function save_dp($detail)
	{
		return $this->db->insert('transaksi_detail', $detail);
	}
	function update_trans($trans,$kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}

	//returnn
	function returnn($kd_dtl,$returnn)
	{
		$this->db->where('dtl_kode', $kd_dtl);
		return $this->db->update('transaksi_detail', $returnn);
	}
	function save_ret($save_ret)
	{
		return $this->db->insert('transaksi_return', $save_ret);
	}


	//printt
	function cetak($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function barang($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('tindakan');
	}
	function bayar($kode)
	{
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}
	function cetak_pembayaran($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}

	//laporan

	function lap_bayar()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi_detail.dtl_tanggal', date('Y-m-d'));
	    $query = $this->db->get();
	    return $query;
	}
	function ks_laporan($kode)
	{
	 	$this->db->where('kry_kode', $kode);
		return $this->db->get('karyawan');
	}
	function DP_Tunai()
	{
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_detail');
	}
	function Sum_DP_Tunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function Lunas_Tunai()
	{
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_detail');
	}
	function Sum_Lunas_Tunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function jml_return()
	{
		$this->db->where('ret_tanggal', date('Y-m-d'));
		return $this->db->get('transaksi_return');
	}
	function sum_return()
	{
		$this->db->select('SUM(ret_jml) as total');
		$this->db->from('transaksi_return');
		$this->db->where('ret_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}

}

/* End of file M_kasir.php */
/* Location: ./application/models/M_kasir.php */