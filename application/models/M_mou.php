<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mou extends CI_Model {

	// Get all Mou with pagination
	function get_all_mou($limit, $offset)
	{
		// Check if table exists first
		if (!$this->db->table_exists('mou')) {
			// Return empty result
			$this->db->select('*');
			$this->db->from('mou');
			$this->db->where('1 = 0'); // Always false to return empty
			return $this->db->get();
		}
		
		$this->db->select('mou.*, karyawan.kry_nama');
		$this->db->from('mou');
		$this->db->join('karyawan', 'mou.kry_kode = karyawan.kry_kode', 'left');
		$this->db->order_by('mou.created_at', 'DESC');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}

	// Count all Mou
	function count_all_mou()
	{
		// Check if table exists first
		if (!$this->db->table_exists('mou')) {
			return 0;
		}
		$this->db->from('mou');
		return $this->db->count_all_results();
	}

	// Save Mou
	function save_mou($data)
	{
		if (!$this->db->table_exists('mou')) {
			return false;
		}
		return $this->db->insert('mou', $data);
	}

	// Get Mou by ID
	function get_mou_by_id($mou_id)
	{
		if (!$this->db->table_exists('mou')) {
			$this->db->select('*');
			$this->db->from('mou');
			$this->db->where('1 = 0');
			return $this->db->get();
		}
		$this->db->where('mou_id', $mou_id);
		return $this->db->get('mou');
	}

	// Get Mou items by Mou ID
	function get_mou_items($mou_id)
	{
		if (!$this->db->table_exists('mou_items')) {
			$this->db->select('*');
			$this->db->from('mou_items');
			$this->db->where('1 = 0');
			return $this->db->get();
		}
		$this->db->where('mou_id', $mou_id);
		$this->db->order_by('item_no', 'ASC');
		return $this->db->get('mou_items');
	}

	// Delete Mou by ID
	function delete_mou($mou_id)
	{
		if (!$this->db->table_exists('mou')) {
			return false;
		}
		$this->db->where('mou_id', $mou_id);
		return $this->db->delete('mou');
	}

	// Delete Mou items by Mou ID
	function delete_mou_items($mou_id)
	{
		if (!$this->db->table_exists('mou_items')) {
			return false;
		}
		$this->db->where('mou_id', $mou_id);
		return $this->db->delete('mou_items');
	}

	// Get old MOUs (for cleanup)
	function get_old_mou($limit)
	{
		if (!$this->db->table_exists('mou')) {
			$this->db->select('*');
			$this->db->from('mou');
			$this->db->where('1 = 0');
			return $this->db->get();
		}
		$this->db->order_by('mou.created_at', 'ASC');
		$this->db->limit($limit);
		return $this->db->get('mou');
	}
}

/* End of file M_mou.php */
/* Location: ./application/models/M_mou.php */

