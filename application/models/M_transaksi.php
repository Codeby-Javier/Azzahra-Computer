<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transaksi extends CI_Model {

	function save_transaksi($data)
	{
		return $this->db->insert('transaksi', $data);
	}

	function save_order_list($data)
	{
		return $this->db->insert('transaksi', $data);
	}

	function generate_trans_kode()
	{
		$this->db->select('MAX(trans_kode) as max_kode');
		$query = $this->db->get('transaksi');
		$result = $query->row();
		$max_kode = $result->max_kode;

		if ($max_kode) {
			$next_kode = (int)$max_kode + 1;
		} else {
			$next_kode = 1;
		}

		return $next_kode;
	}



}

/* End of file M_transaksi.php */
/* Location: ./application/models/M_transaksi.php */
