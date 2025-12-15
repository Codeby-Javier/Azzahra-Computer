<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cetak extends CI_Model {

	function trans($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode', 'left');
	    $this->db->where('costomer.id_costomer', $kode);
	    $this->db->or_where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function bayar($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}

	function barang($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('tindakan');
	}	

	function pembayaran($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}
	//return pembayaran
	function trans_reurn($kode)
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

}

/* End of file M_cetak.php */
/* Location: ./application/models/M_cetak.php */