<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customer extends CI_Model {

	function GetAll($limit = null, $offset = null, $search = '')
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    if (!empty($search)) {
	        $this->db->group_start();
	        $this->db->like('cos_nama', $search);
	        $this->db->or_like('cos_alamat', $search);
	        $this->db->or_like('cos_hp', $search);
	        $this->db->or_like('trans_status', $search);
	        $this->db->group_end();
	    }
	    if ($limit !== null && $offset !== null) {
	        $this->db->limit($limit, $offset);
	    } elseif ($limit !== null) {
	        $this->db->limit($limit);
	    }
	    $query = $this->db->get();
	    return $query;
	}

	function GetAllCount($search = '')
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    if (!empty($search)) {
	        $this->db->group_start();
	        $this->db->like('cos_nama', $search);
	        $this->db->or_like('cos_alamat', $search);
	        $this->db->or_like('cos_hp', $search);
	        $this->db->or_like('trans_status', $search);
	        $this->db->group_end();
	    }
	    return $this->db->count_all_results();
	}

	var $table = 'costomer';
	var $column_order = array('trans_kode','cos_nama','cos_alamat','cos_hp','cos_tanggal','cos_jam',null); //set column field database for datatable orderable
	var $column_search = array('trans_kode','cos_nama','cos_alamat','cos_hp','trans_status'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('trans_kode' => 'asc'); // default order

	private function _get_datatables_query()
	{
		$this->db->select('costomer.*, transaksi.trans_kode, transaksi.trans_status, transaksi.cos_tanggal, transaksi.cos_jam, karyawan.kry_nama');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');

		$i = 0;

		if(isset($_POST['search']) && $_POST['search']['value']) {
			foreach ($this->column_search as $item) // loop column
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
				$i++;
			}
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    return $this->db->count_all_results();
	}
	function edit($kode)
	{
		 $this->db->where('id_costomer', $kode);
		 return $this->db->get('costomer');
	}
	function update($kode,$data)
	{
		$this->db->where('id_costomer', $kode);
		return $this->db->update('costomer', $data);
	}
	function delete($kode)
	{
		$this->db->where('id_costomer', $kode);
		return $this->db->delete('costomer');
	}
	function konfirmasi($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function GetTindakanBy($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('tindakan');
	}
	function histori($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}
	function histori_transaksi($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi');
	}
}

/* End of file M_customer.php */
/* Location: ./application/models/M_customer.php */