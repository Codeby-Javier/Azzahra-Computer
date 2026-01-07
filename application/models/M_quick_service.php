<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_quick_service extends CI_Model {

	// Method to get transactions with status Pelunasan for Quick Service
	function cos_baru($kry_kode = null)
	{
		$this->db->select('transaksi.*, costomer.*, karyawan.*');
	    $this->db->from('transaksi');
	    $this->db->join('costomer','transaksi.cos_kode=costomer.id_costomer');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    if ($kry_kode) {
	        $this->db->where('transaksi.kry_kode', $kry_kode);
	    }
	    $query = $this->db->get();
	    return $query;
	}
}