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

	// Get Rekap MOU with filters
	function get_rekap_mou($filters)
	{
		if (!$this->db->table_exists('mou')) {
			$this->db->select('*');
			$this->db->from('mou');
			$this->db->where('1 = 0');
			return $this->db->get();
		}

		$this->db->select('mou.*, karyawan.kry_nama');
		$this->db->from('mou');
		$this->db->join('karyawan', 'mou.kry_kode = karyawan.kry_kode', 'left');

		// Apply filters
		if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
			$this->db->where('mou.tanggal BETWEEN "' . $filters['tanggal_mulai'] . '" AND "' . $filters['tanggal_selesai'] . '"', null, false);
		}
		if (!empty($filters['lokasi'])) {
			$this->db->where('mou.lokasi', $filters['lokasi']);
		}
		if (!empty($filters['customer'])) {
			$this->db->like('mou.customer', $filters['customer']);
		}
		if (!empty($filters['kry_kode'])) {
			$this->db->where('mou.kry_kode', $filters['kry_kode']);
		}

		$this->db->order_by('mou.tanggal', 'DESC');
		$this->db->order_by('mou.created_at', 'DESC');
		return $this->db->get();
	}

	// Get Summary for Rekap MOU
	function get_rekap_summary($filters)
	{
		if (!$this->db->table_exists('mou')) {
			return array(
				'total_mou' => 0,
				'total_grand_total' => 0,
				'avg_grand_total' => 0
			);
		}

		$this->db->select('COUNT(*) as total_mou, SUM(grand_total) as total_grand_total, AVG(grand_total) as avg_grand_total');
		$this->db->from('mou');

		// Apply filters
		if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
			$this->db->where('mou.tanggal BETWEEN "' . $filters['tanggal_mulai'] . '" AND "' . $filters['tanggal_selesai'] . '"', null, false);
		}
		if (!empty($filters['lokasi'])) {
			$this->db->where('mou.lokasi', $filters['lokasi']);
		}
		if (!empty($filters['customer'])) {
			$this->db->like('mou.customer', $filters['customer']);
		}
		if (!empty($filters['kry_kode'])) {
			$this->db->where('mou.kry_kode', $filters['kry_kode']);
		}

		return $this->db->get()->row_array();
	}

	// Get Rekap per Lokasi
	function get_rekap_per_lokasi($filters)
	{
		if (!$this->db->table_exists('mou')) {
			return array();
		}

		$this->db->select('lokasi, COUNT(*) as jumlah_mou, SUM(grand_total) as total_grand_total');
		$this->db->from('mou');

		// Apply filters
		if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
			$this->db->where('mou.tanggal BETWEEN "' . $filters['tanggal_mulai'] . '" AND "' . $filters['tanggal_selesai'] . '"', null, false);
		}
		if (!empty($filters['lokasi'])) {
			$this->db->where('mou.lokasi', $filters['lokasi']);
		}
		if (!empty($filters['customer'])) {
			$this->db->like('mou.customer', $filters['customer']);
		}
		if (!empty($filters['kry_kode'])) {
			$this->db->where('mou.kry_kode', $filters['kry_kode']);
		}

		$this->db->group_by('lokasi');
		$this->db->order_by('total_grand_total', 'DESC');
		return $this->db->get()->result_array();
	}

	// Get Rekap per Customer (Top 10)
	function get_rekap_per_customer($filters)
	{
		if (!$this->db->table_exists('mou')) {
			return array();
		}

		$this->db->select('customer, COUNT(*) as jumlah_mou, SUM(grand_total) as total_grand_total');
		$this->db->from('mou');

		// Apply filters
		if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_selesai'])) {
			$this->db->where('mou.tanggal BETWEEN "' . $filters['tanggal_mulai'] . '" AND "' . $filters['tanggal_selesai'] . '"', null, false);
		}
		if (!empty($filters['lokasi'])) {
			$this->db->where('mou.lokasi', $filters['lokasi']);
		}
		if (!empty($filters['kry_kode'])) {
			$this->db->where('mou.kry_kode', $filters['kry_kode']);
		}

		$this->db->group_by('customer');
		$this->db->order_by('total_grand_total', 'DESC');
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	// Get Distinct Lokasi
	function get_distinct_lokasi()
	{
		if (!$this->db->table_exists('mou')) {
			return array();
		}

		$this->db->distinct();
		$this->db->select('lokasi');
		$this->db->from('mou');
		$this->db->order_by('lokasi', 'ASC');
		return $this->db->get()->result_array();
	}

	// Get All Karyawan
	function get_all_karyawan()
	{
		if (!$this->db->table_exists('karyawan')) {
			return array();
		}

		$this->db->select('kry_kode, kry_nama');
		$this->db->from('karyawan');
		$this->db->order_by('kry_nama', 'ASC');
		return $this->db->get()->result_array();
	}
}

/* End of file M_mou.php */
/* Location: ./application/models/M_mou.php */