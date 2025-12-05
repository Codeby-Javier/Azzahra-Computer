<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_export extends CI_Model {

	function getCustomer()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $query = $this->db->get();
	    return $query;
	}

}

/* End of file M_export.php */
/* Location: ./application/models/M_export.php */