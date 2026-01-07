<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_karyawan extends CI_Model {

	function GetAll()
	{
		return $this->db->get('karyawan');
	}
	function save($data)
	{
		return $this->db->insert('karyawan', $data);
	}
	function update($kode,$data)
	{
		$this->db->where('kry_kode', $kode);
		return $this->db->update('karyawan', $data);
	}
	function delete($kode)
	{
		 $this->db->where('kry_kode', $kode);
		 return $this->db->delete('karyawan');
	}

}

/* End of file M_karyawan.php */
/* Location: ./application/models/M_karyawan.php */