<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_order extends CI_Model {

	function get_all_orders()
	{
		$this->db->select('order_list.*, costomer.cos_nama');
		$this->db->from('order_list');
		$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
		$this->db->where_in('order_list.trans_status', array('pending', 'confirm', 'waitingApproval', 'tolak'));
		$this->db->order_by('order_list.trans_tanggal', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	function get_pending_orders()
	{
		$this->db->select('order_list.*, costomer.cos_nama');
		$this->db->from('order_list');
		$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
		$this->db->where('order_list.trans_status', 'pending');
		$this->db->order_by('order_list.trans_tanggal', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	function get_waiting_approval_orders()
	{
		$this->db->select('tindakan.tdkn_kode, tindakan.tdkn_nama, tindakan.tdkn_qty, tindakan.tdkn_ket, order_list.trans_kode, order_list.device, order_list.merek, order_list.seri, order_list.status_garansi, costomer.cos_nama, (SELECT COALESCE(SUM(tdkn_subtot), 0) FROM tindakan WHERE trans_kode = order_list.trans_kode) as total_subtot, (SELECT COALESCE(SUM(dtl_jml_bayar), 0) FROM transaksi_detail WHERE TRIM(trans_kode) = TRIM(order_list.trans_kode)) as total_bayar');
		$this->db->from('order_list');
		$this->db->join('tindakan', 'order_list.trans_kode = tindakan.trans_kode', 'inner');
		$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
		$this->db->where_in('order_list.trans_status', array('waitingApproval', 'itemSubmitted'));
		$this->db->order_by('tindakan.tdkn_kode', 'ASC');
		$query = $this->db->get();
		return $query;
	}

	function get_confirm_orders()
	{
		$this->db->select('order_list.*, costomer.cos_nama, karyawan.kry_nama');
		$this->db->from('order_list');
		$this->db->join('costomer', 'order_list.cos_kode = costomer.id_costomer', 'left');
		$this->db->join('karyawan', 'order_list.kry_kode = karyawan.kry_kode', 'left');
		$this->db->where('order_list.trans_status', 'confirm');
		$this->db->order_by('order_list.trans_tanggal', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	function update_order_status($trans_kode, $data)
	{
		// Check if the row exists first
		$this->db->where('trans_kode', $trans_kode);
		$query = $this->db->get('order_list');
		if ($query->num_rows() == 0) {
			log_message('error', 'Order update failed: trans_kode ' . $trans_kode . ' not found');
			return false;
		}

		// Row exists, proceed with update
		$this->db->where('trans_kode', $trans_kode);
		$result = $this->db->update('order_list', $data);

		if ($result) {
			log_message('info', 'Order status updated successfully for trans_kode: ' . $trans_kode . ', new status: ' . (isset($data['trans_status']) ? $data['trans_status'] : 'N/A'));
			return true;
		} else {
			log_message('error', 'Order update failed for trans_kode: ' . $trans_kode . ', DB error: ' . $this->db->error()['message']);
			return false;
		}
	}

	function get_karyawan_list()
	{
		$this->db->select('kry_kode, kry_nama');
		$this->db->from('karyawan');
		$this->db->where('kry_level', 'Teknisi'); // Only select technicians
		$this->db->where('kry_kode NOT IN (SELECT kry_kode FROM order_list WHERE kry_kode IS NOT NULL AND trans_status NOT IN (\'waitingOrder\', \'jobDone\', \'completed\'))', NULL, FALSE); // Exclude technicians whose kry_kode exists in order_list with trans_status not in waitingOrder, jobDone, completed

		return $this->db->get();
	}

	function get_order_data($trans_kode)
	{
		$this->db->where('trans_kode', $trans_kode);
		return $this->db->get('order_list')->row();
	}

	function insert_transaksi($data)
	{
		return $this->db->insert('transaksi', $data);
	}

	function delete_order($trans_kode)
	{
		$this->db->where('trans_kode', $trans_kode);
		return $this->db->delete('order_list');
	}

	function update_tindakan_subtot($trans_kode, $tdkn_kode, $subtot)
	{
		$this->db->where('trans_kode', $trans_kode);
		$this->db->where('tdkn_kode', $tdkn_kode);
		return $this->db->update('tindakan', array('tdkn_subtot' => $subtot));
	}

}

/* End of file M_order.php */
/* Location: ./application/models/M_order.php */
