<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

	function change_pswd($kode,$update)
	{
		$this->db->where('kry_kode', $kode);
		return $this->db->update('karyawan', $update);
	}

}

/* End of file M_auth.php */
/* Location: ./application/models/M_auth.php */