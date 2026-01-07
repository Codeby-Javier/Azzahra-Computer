<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

	//customer
	
	function baru()
	{
		$this->db->select('*');
	    $this->db->from('transaksi');
	    $this->db->join('costomer','costomer.id_costomer=transaksi.cos_kode', 'left');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Baru');
	    $query = $this->db->get();
	    return $query;
	}
	function konf()
	{
		$this->db->select('*');
	    $this->db->from('transaksi');
	    $this->db->join('costomer','costomer.id_costomer=transaksi.cos_kode', 'left');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Diproses');
	    $query = $this->db->get();
	    return $query;
	}
	function cus_konf_bank()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $this->db->where('transaksi_detail.dtl_jenis_bayar', 'TRANFER');
	    $this->db->where('transaksi_detail.dtl_stt_stor', 'Menunggu');	    
	    $query = $this->db->get();
	    return $query;
	}
	function bca()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi_detail.dtl_jenis_bayar', 'TRANFER');
	    $this->db->where('transaksi_detail.dtl_bank', 'BCA');
	    $this->db->where('transaksi_detail.dtl_stt_stor', 'Menunggu');
	    $query = $this->db->get();
	    return $query;
	}
	function mandiri()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi_detail.dtl_jenis_bayar', 'TRANFER');
	    $this->db->where('transaksi_detail.dtl_bank', 'MANDIRI');
	    $this->db->where('transaksi_detail.dtl_stt_stor', 'Menunggu');
	    $query = $this->db->get();
	    return $query;
	}
	function bri()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi_detail.dtl_jenis_bayar', 'TRANFER');
	    $this->db->where('transaksi_detail.dtl_bank', 'BRI');
	    $this->db->where('transaksi_detail.dtl_stt_stor', 'Menunggu');
	    $query = $this->db->get();
	    return $query;
	}
	function tunai()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->where('transaksi_detail.dtl_jenis_bayar', 'TUNAI');
	    $query = $this->db->get();
	    return $query;
	}
	function total_bca()
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		return $this->db->get()->row()->total;
	}
	function total_mandiri()
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		return $this->db->get()->row()->total;
	}
	function total_bri()
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		return $this->db->get()->row()->total;
	}
	function total_tunai()
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		return $this->db->get()->row()->total;
	}
	function total_voucher()
	{
		$this->db->select('COALESCE(SUM(voc_jumlah), 0) as total');
		$this->db->from('vocer');
		$this->db->where('voc_status', 'ON');
		return $this->db->get()->row()->total;
	}
	function customer_baru()
	{
		$this->db->select('*');
	    $this->db->from('order_list');
	    $this->db->join('costomer','order_list.cos_kode=costomer.id_costomer');
	    $this->db->join('karyawan','order_list.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('order_list.created_at >=', date('Y-m-d') . ' 00:00:00');
	    $this->db->where('order_list.created_at <=', date('Y-m-d') . ' 23:59:59');
	    $query = $this->db->get();
	    return $query;
	}
	function users_baru()
	{
		$this->db->where('created_at >=', date('Y-m-d') . ' 00:00:00');
		$this->db->where('created_at <=', date('Y-m-d') . ' 23:59:59');
	    $query = $this->db->get('costomer');
	    return $query;
	}
	function cus_proses()
	{
		$this->db->select('*');
	    $this->db->from('transaksi');
	    $this->db->join('costomer','costomer.id_costomer=transaksi.cos_kode', 'left');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode', 'left');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $query = $this->db->get();
	    return $query;
	}
	//vocher discount
	function discount()
	{
		$this->db->select('*');
	    $this->db->from('vocer');
	    $this->db->join('transaksi','vocer.trans_kode=transaksi.trans_kode');
	    $this->db->join('costomer','transaksi.cos_kode=costomer.id_costomer');
	    $this->db->where('vocer.voc_status', 'ON');
	    $query = $this->db->get();
	    return $query;

		// $this->db->where('voc_status', 'ON');
		// return $this->db->get('vocer');
	}
	//actions
	function konfirmasi($kode)
	{
		$this->db->select('*');
	    $this->db->from('transaksi');
	    $this->db->join('costomer','costomer.id_costomer=transaksi.cos_kode', 'left');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function GetTindakanBy($kode)
	{
		$this->db->select('tindakan.*, karyawan.kry_nama');
		$this->db->from('tindakan');
		$this->db->join('order_list', 'tindakan.trans_kode = order_list.trans_kode', 'left');
		$this->db->join('karyawan', 'order_list.kry_kode = karyawan.kry_kode', 'left');
		$this->db->where('tindakan.trans_kode', $kode);
		return $this->db->get();
	}
	function update_konf($data)
	{
		return $this->db->update_batch('tindakan', $data, 'tdkn_kode');
	}
	function total($kd_trans)
	{
		$this->db->select('SUM(tdkn_subtot) as total');
		$this->db->from('tindakan');
		$this->db->where('trans_kode', $kd_trans);
		return $this->db->get()->row()->total;
	}
	function status($status,$kd_trans)
	{
		$this->db->where('trans_kode', $kd_trans);
		return $this->db->update('transaksi', $status);
	}
	function vocher($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
	    $this->db->join('vocer','transaksi.trans_kode=vocer.trans_kode');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function update_vocher($kode,$vocher)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('vocer', $vocher);
	}
	function update_discount_trans($kode,$trans)
	{
	 	$this->db->where('trans_kode', $kode);
	 	return $this->db->update('transaksi', $trans);
	}
	function setoran($kode,$setoran)
	{
		$this->db->where('dtl_kode', $kode);
		return $this->db->update('transaksi_detail', $setoran);
	}

	//laporan
	function jml_DP_bca($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function tot_DP_bca($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function jml_DP_bca_blm_stor($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function jml_DP_bri($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function tot_DP_bri($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function jml_DP_tunai($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function tot_DP_tunai($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function jml_Lunas_tunai($tgl_awal,$tgl_akhir)
	{
	 	$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function tot_Lunas_tunai($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function tot_tranfer($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function jml_tranfer($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function blm_setor($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function jml_setor($tgl_awal,$tgl_akhir)
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function jml_tunai($tgl_awal,$tgl_akhir)
	{
	 	$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get('transaksi_detail');
	}
	function tot_tunai($tgl_awal,$tgl_akhir)
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_tanggal >=', $tgl_awal);
		$this->db->where('dtl_tanggal <=', $tgl_akhir);
		return $this->db->get()->row()->total;
	}
	function weekly_stats($days = 7)
	{
		$this->db->select('DATE(costomer.cos_tanggal) as day, COUNT(*) as total');
		$this->db->from('transaksi');
		$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
		$this->db->where('costomer.cos_tanggal >=', date('Y-m-d', strtotime("-{$days} days")));
		$this->db->group_by('DATE(costomer.cos_tanggal)');
		$this->db->order_by('DATE(costomer.cos_tanggal)', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function weekly_revenue($days = 7)
	{
		$this->db->select('DATE(dtl_tanggal) as day, COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_tanggal >=', date('Y-m-d', strtotime("-{$days} days")));
		$this->db->where_in('dtl_status', ['DP', 'PELUNASAN']);
		$this->db->group_by('DATE(dtl_tanggal)');
		$this->db->order_by('DATE(dtl_tanggal)', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function weekly_customers($days = 7)
	{
		$this->db->select('DATE(order_list.created_at) as day, COUNT(*) as total');
		$this->db->from('order_list');
		$this->db->where('order_list.created_at >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
		$this->db->where('order_list.created_at <=', date('Y-m-d H:i:s'));
		$this->db->group_by('DATE(order_list.created_at)');
		$this->db->order_by('DATE(order_list.created_at)', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function weekly_technician_productivity($days = 7)
	{
		$this->db->select('DATE(tdkn_tanggal) as day, COUNT(tindakan.tdkn_kode) as total');
		$this->db->from('tindakan');
		$this->db->join('order_list', 'tindakan.trans_kode = order_list.trans_kode');
		// Count individual tindakan records instead of distinct trans_kode
		$this->db->where('tdkn_tanggal >=', date('Y-m-d', strtotime("-{$days} days")));
		$this->db->group_by('DATE(tdkn_tanggal)');
		$this->db->order_by('DATE(tdkn_tanggal)', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function technician_productivity_details($period = '7D')
	{
		$this->db->select('COALESCE(karyawan.kry_nama, CONCAT("ID Teknisi: ", COALESCE(order_list.kry_kode, "Belum Ditugaskan"))) as technician_name, COUNT(tindakan.tdkn_kode) as services_completed, MAX(DATE(order_list.trans_tanggal)) as date');
		$this->db->from('tindakan');
		$this->db->join('order_list', 'tindakan.trans_kode = order_list.trans_kode');
		$this->db->join('karyawan', 'order_list.kry_kode = karyawan.kry_kode', 'left');
		// Count individual tindakan records instead of distinct trans_kode
		if (strtolower($period) !== 'all') {
			$days = 7;
			if ($period == '1M') $days = 30;
			elseif ($period == '1Y') $days = 365;
			$this->db->where('order_list.trans_tanggal >=', date('Y-m-d', strtotime("-{$days} days")));
		}
		$this->db->group_by('COALESCE(order_list.kry_kode, "unassigned")');
		$this->db->order_by('services_completed', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function revenue_today()
	{
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as total');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		$this->db->where_in('dtl_status', ['DP', 'PELUNASAN']);
		return $this->db->get()->row()->total;
	}
	function service_completion_rate()
	{
		// Total completed services (status 'Lunas')
		$this->db->where('trans_status', 'Lunas');
		return $this->db->count_all_results('transaksi');
	}
	function total_customers()
	{
		return $this->db->count_all('costomer');
	}
	function dp_pending()
	{
		$this->db->where('trans_status', 'Pelunasan');
		return $this->db->count_all_results('transaksi');
	}
	function bank_percentages()
	{
		// Get total transfer payments by bank
		$this->db->select('dtl_bank, COUNT(*) as count');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->group_by('dtl_bank');
		$query = $this->db->get();
		$results = $query->result_array();

		// Calculate total transfers
		$total_transfers = 0;
		foreach ($results as $row) {
			$total_transfers += $row['count'];
		}

		// Calculate percentages
		$percentages = [];
		if ($total_transfers > 0) {
			foreach ($results as $row) {
				$percentages[$row['dtl_bank']] = round(($row['count'] / $total_transfers) * 100, 1);
			}
		}

		return $percentages;
	}
	function tunai_percentage()
	{
		// Total tunai payments
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$tunai_count = $this->db->count_all_results('transaksi_detail');

		// Total transfer payments
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$transfer_count = $this->db->count_all_results('transaksi_detail');

		$total_payments = $tunai_count + $transfer_count;

		if ($total_payments > 0) {
			return round(($tunai_count / $total_payments) * 100, 1);
		}
		return 0;
	}
	function voucher_usage_percentage()
	{
		// Total vouchers used (voc_status = 'OFF' means used)
		$this->db->where('voc_status', 'OFF');
		$used_count = $this->db->count_all_results('vocer');

		// Total vouchers
		$total_vouchers = $this->db->count_all('vocer');

		if ($total_vouchers > 0) {
			return round(($used_count / $total_vouchers) * 100, 1);
		}
		return 0;
	}
	function total_pending_transfers()
	{
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		return $this->db->count_all_results('transaksi_detail');
	}
	function weekly_service_completion()
	{
		// Return dummy data with variation like original
		return [
			['day' => date('Y-m-d', strtotime('-6 days')), 'total' => 85],
			['day' => date('Y-m-d', strtotime('-5 days')), 'total' => 92],
			['day' => date('Y-m-d', strtotime('-4 days')), 'total' => 78],
			['day' => date('Y-m-d', strtotime('-3 days')), 'total' => 95],
			['day' => date('Y-m-d', strtotime('-2 days')), 'total' => 88],
			['day' => date('Y-m-d', strtotime('-1 days')), 'total' => 96],
			['day' => date('Y-m-d'), 'total' => 90]
		];
	}

}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */