<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_admin');
		$this->load->model('M_order');
		$this->load->model('M_customer');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Admin') {
	    		$url=base_url();
	      		redirect($url);
	    	}
	    }
	}

	public function index()
	{
		// Clear any leftover flashdata
		$this->session->unset_userdata(['sukses', 'error']);

		$data = array(
			'title' 	=> 'Dashboard',
			'konf'		=> $this->M_admin->konf(),
			'discount'	=> $this->M_admin->discount(),
			'bca'		=> $this->M_admin->bca(),
			'mandiri'	=> $this->M_admin->mandiri(),
			'bri'		=> $this->M_admin->bri(),
			'tunai'		=> $this->M_admin->tunai(),
			'total_bca'	=> $this->M_admin->total_bca(),
			'total_mandiri'	=> $this->M_admin->total_mandiri(),
			'total_bri'	=> $this->M_admin->total_bri(),
			'total_tunai'	=> $this->M_admin->total_tunai(),
			'total_voucher'	=> $this->M_admin->total_voucher(),
			'baru'		=> $this->M_admin->customer_baru(),
			'users_baru'	=> $this->M_admin->users_baru(),
			'weekly_revenue'	=> $this->M_admin->weekly_revenue(),
			'weekly_transactions'	=> $this->M_admin->weekly_stats(),
			'weekly_customers'	=> $this->M_admin->weekly_customers(),
			'weekly_productivity'	=> $this->M_admin->weekly_technician_productivity(),
			'weekly_completion'	=> $this->M_admin->weekly_service_completion(),
			'technician_details'	=> $this->M_admin->technician_productivity_details('7D'),
			'revenue_today'	=> $this->M_admin->revenue_today(),
			'service_completion_rate'	=> $this->M_admin->service_completion_rate(),
			'total_customers'	=> $this->M_admin->total_customers(),
			'dp_pending'	=> $this->M_admin->dp_pending(),
			'bank_percentages'	=> $this->M_admin->bank_percentages(),
			'tunai_percentage'	=> $this->M_admin->tunai_percentage(),
			'voucher_usage_percentage'	=> $this->M_admin->voucher_usage_percentage(),
			'total_pending_transfers'	=> $this->M_admin->total_pending_transfers()
			 );
		$this->load->view('Admin/dashboard',$data);
	}
	//customer
	function customer()
	{
		$data = array(
			'title' 	=> 'Customer',
			'custom'	=> $this->M_admin->GetAll_custom(),
			'no'		=> $this->uri->segment(3)
			 );
		$this->load->view('Costomer/customer',$data);
	}
	function cus_baru()
	{
		$data = array(
				'title' => 'Transaksi-Baru',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_admin->baru()
			);
		$this->load->view('Admin/cus-baru', $data);
	}
	function cus_konf()
	{
		$data = array(
				'title' => 'Transaksi-Konfirmasi',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_admin->konf()
			);
		$this->load->view('Admin/cus-konf', $data);
	}


	function cus_konf_bank()
	{
		$data = array(
				'title' => 'Transaksi-Bank Transfer',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_admin->cus_konf_bank()
			);
		$this->load->view('Admin/cus_konf_bank', $data);
	}
	function cus_proses()
	{
		$data = array(
				'title' => 'Transaksi-Proses',
				'no'	=> $this->uri->segment(3),
				'trans'	=> $this->M_admin->cus_proses()
			);
		$this->load->view('Admin/cus_proses', $data);
	}
	function cus_detail()
	{
		$kode = $this->uri->segment(3);

		$proses = $this->M_admin->konfirmasi($kode)->row_array();
		if (!$proses) {
			$proses = [];
		}

		$data = array(
				'title'  => 'Transaksi-Detail',
				'proses' => $proses,
				'data'	 => $this->M_admin->GetTindakanBy($kode)
			);
		$this->load->view('Admin/cus-detail', $data);
	}
	//vocher discount
	function discount()
	{
		$data = array(
				'title' 	=> 'Discount',
				'no'		=> $this->uri->segment(3),
				'discount'	=> $this->M_admin->discount()
			);
		$this->load->view('Admin/discount', $data);
	}
	//actions
	function konfirmasi()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'title'  => 'Customer-Konfirmasi',
				'proses' => $this->M_admin->konfirmasi($kode)->row_array(),
				'data'	 => $this->M_admin->GetTindakanBy($kode)
			);
		$this->load->view('Admin/konfirmasi', $data);
	}
	function update_konf()
	{
		$kode 	  = $_POST['no'];
		$kd_tdkn  = $_POST['tdkn'];
		$tindakan = $_POST['tindakan'];
		$ket	  = $_POST['ket'];
		$qty	  = $_POST['qty'];
		$subtot	  = $_POST['subtot'];
		$kd_trans = $this->input->post('tras_kode');

		$data  = array();
		$index = 0;
		foreach ($kode as $cek) {
			array_push($data, array(
				'tdkn_kode' 	=> $kd_tdkn[$index],
				'tdkn_nama' 	=> $tindakan[$index],
				'tdkn_ket' 		=> $ket[$index],
				'tdkn_qty' 		=> $qty[$index],
				'tdkn_subtot'  	=> str_replace('.', '', $subtot[$index]),
				'tdkn_tanggal' 	=> date('Y-m-d'),
				'tdkn_jam'		=> date('H:i:s')
			));
			$index++;
		}
		$this->M_admin->update_konf($data);

		$total = $this->M_admin->total($kd_trans);

		$status = array(
				'trans_total'  => $total,
				'trans_status' => 'Konfirmasi',
			);
		$this->M_admin->status($status,$kd_trans);

		// Update created_at in order_list
		$this->db->where('trans_kode', $kd_trans);
		$this->db->update('order_list', array('created_at' => date('Y-m-d H:i:s')));

		// Get cos_kode from transaksi and update trans_status and trans_total in order_list to 'waitingOrder' and $total
		$this->db->select('cos_kode');
		$this->db->from('transaksi');
		$this->db->where('trans_kode', $kd_trans);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {
			$cos_kode = $result->cos_kode;
			$this->db->where('cos_kode', $cos_kode);
			$this->db->update('order_list', array('trans_status' => 'waitingOrder', 'trans_total' => $total));
		}

		$this->session->set_flashdata('sukses', 'DI KONFIRMASI');
		redirect('Admin/cus_konf','refresh');

	}
	function vocher()
	{
		$kode = $this->uri->segment(3);
		$data = array(
				'title'  => 'Discount',
				'vocher' => $this->M_admin->vocher($kode)->row_array(),
				'data'	 => $this->M_admin->GetTindakanBy($kode)
			);
		$this->load->view('Admin/vocher', $data);
	}
	function save_vocher()
	{
		$kode = $this->input->post('kode');		

		$vocher = array(
				'voc_jumlah' => str_replace('.', '', $this->input->post('vocher')), 
				'voc_status' => 'Approv',
			);
		$this->M_admin->update_vocher($kode,$vocher);

		$trans = array(
				'trans_discount' => str_replace('.', '', $this->input->post('vocher')),  
			);
		$this->M_admin->update_discount_trans($kode,$trans);

		$this->session->set_flashdata('sukses', 'DI SIMPAN');
		redirect('Admin/discount','refresh');
	}
	function delete_vocher()
	{
		$kode = $this->uri->segment(3);

		$vocher = array( 
				'voc_status' => 'OFF',
			);
		$this->M_admin->update_vocher($kode,$vocher);

		$this->session->set_flashdata('sukses', 'DI BATALKAN');
		redirect('Admin/discount','refresh');
	}
	function setoran()
	{
		$kode = $this->input->post('kode');

		$setoran = array(
				'dtl_jml_bayar' => $this->input->post('jml_tranfer'),
				'dtl_stt_stor' 	=> 'Disetorkan', 
			);
		$this->M_admin->setoran($kode,$setoran);

		$this->session->set_flashdata('sukses', 'DI SETORKAN');
		redirect('Admin/cus_konf_bank','refresh');
	}
	//laporan
	function lap_perhari()
	{
		$kode = $this->session->userdata('kode');
		$tgl_awal  = date('Y-m-d');
		$tgl_akhir = date('Y-m-d');

		$data = array(
				'title'     	=> 'Laporan',
				'jml_DP_bca'   	=> $this->M_admin->jml_DP_bca($tgl_awal,$tgl_akhir),
				'tot_DP_bca'   	=> $this->M_admin->tot_DP_bca($tgl_awal,$tgl_akhir),
				'jml_DP_bri'   	=> $this->M_admin->jml_DP_bri($tgl_awal,$tgl_akhir),
				'tot_DP_bri'   	=> $this->M_admin->tot_DP_bri($tgl_awal,$tgl_akhir),
				'jml_DP_tunai' 	=> $this->M_admin->jml_DP_tunai($tgl_awal,$tgl_akhir),
				'tot_DP_tunai' 	=> $this->M_admin->tot_DP_tunai($tgl_awal,$tgl_akhir),
				'jml_lns_tunai' => $this->M_admin->jml_Lunas_tunai($tgl_awal,$tgl_akhir),
				'tot_lns_tunai' => $this->M_admin->tot_Lunas_tunai($tgl_awal,$tgl_akhir)
			);
		$this->load->view('Admin/lap-perhari',$data);
	}
	function laporan()
	{
		$tgl_awal  = date('Y-m-d');
		$tgl_akhir = date('Y-m-d');

		$data = array(
				'title' 		=> 'Laporan', 
				'jml_DP_bca'   	=> $this->M_admin->jml_DP_bca($tgl_awal,$tgl_akhir),
				'tot_DP_bca'   	=> $this->M_admin->tot_DP_bca($tgl_awal,$tgl_akhir),
				'jml_DP_bri'   	=> $this->M_admin->jml_DP_bri($tgl_awal,$tgl_akhir),
				'tot_DP_bri'   	=> $this->M_admin->tot_DP_bri($tgl_awal,$tgl_akhir),
				'jml_DP_tunai' 	=> $this->M_admin->jml_DP_tunai($tgl_awal,$tgl_akhir),
				'tot_DP_tunai' 	=> $this->M_admin->tot_DP_tunai($tgl_awal,$tgl_akhir),
				'jml_lns_tunai' => $this->M_admin->jml_Lunas_tunai($tgl_awal,$tgl_akhir),
				'tot_lns_tunai' => $this->M_admin->tot_Lunas_tunai($tgl_awal,$tgl_akhir),
				'jml_tranfer'	=> $this->M_admin->jml_tranfer($tgl_awal,$tgl_akhir),
				'tot_tranfer'	=> $this->M_admin->tot_tranfer($tgl_awal,$tgl_akhir),
				'jml_setor'		=> $this->M_admin->jml_setor($tgl_awal,$tgl_akhir),
				'blm_setor'		=> $this->M_admin->blm_setor($tgl_awal,$tgl_akhir),
				'jml_tunai'		=> $this->M_admin->jml_tunai($tgl_awal,$tgl_akhir),
				'tot_tunai'		=> $this->M_admin->tot_tunai($tgl_awal,$tgl_akhir),
				'tgl_awal'		=> $tgl_awal,
				'tgl_akhir'		=> $tgl_akhir
			);
		$this->load->view('Admin/laporan',$data);
	}
	function Search_laporan()
	{
		$tgl_awal  = $this->input->post('tgl_awal');
		$tgl_akhir = $this->input->post('tgl_akhir');

		$data = array(
				'title' 		=> 'Laporan',
				'jml_DP_bca'   	=> $this->M_admin->jml_DP_bca($tgl_awal,$tgl_akhir),
				'tot_DP_bca'   	=> $this->M_admin->tot_DP_bca($tgl_awal,$tgl_akhir),
				'jml_DP_bri'   	=> $this->M_admin->jml_DP_bri($tgl_awal,$tgl_akhir),
				'tot_DP_bri'   	=> $this->M_admin->tot_DP_bri($tgl_awal,$tgl_akhir),
				'jml_DP_tunai' 	=> $this->M_admin->jml_DP_tunai($tgl_awal,$tgl_akhir),
				'tot_DP_tunai' 	=> $this->M_admin->tot_DP_tunai($tgl_awal,$tgl_akhir),
				'jml_lns_tunai' => $this->M_admin->jml_Lunas_tunai($tgl_awal,$tgl_akhir),
				'tot_lns_tunai' => $this->M_admin->tot_Lunas_tunai($tgl_awal,$tgl_akhir),
				'jml_tranfer'	=> $this->M_admin->jml_tranfer($tgl_awal,$tgl_akhir),
				'tot_tranfer'	=> $this->M_admin->tot_tranfer($tgl_awal,$tgl_akhir),
				'jml_setor'		=> $this->M_admin->jml_setor($tgl_awal,$tgl_akhir),
				'blm_setor'		=> $this->M_admin->blm_setor($tgl_awal,$tgl_akhir),
				'jml_tunai'		=> $this->M_admin->jml_tunai($tgl_awal,$tgl_akhir),
				'tot_tunai'		=> $this->M_admin->tot_tunai($tgl_awal,$tgl_akhir),
				'tgl_awal'		=> $tgl_awal,
				'tgl_akhir'		=> $tgl_akhir
			);
		$this->load->view('Admin/laporan',$data);
	}

	function chart_data()
	{
		$period = $this->input->get('period');
		$days = 7; // default
		if ($period == '1M') $days = 30;
		elseif ($period == '3M') $days = 90;
		elseif ($period == '1Y') $days = 365;

		$data = array(
			'revenue' => $this->M_admin->weekly_revenue($days),
			'transactions' => $this->M_admin->weekly_stats($days),
			'customers' => $this->M_admin->weekly_customers($days),
			'technician_productivity' => $this->M_admin->weekly_technician_productivity($days),
			'technician_details' => $this->M_admin->technician_productivity_details($period)
		);

		echo json_encode($data);
	}

	function chart_technician_data()
	{
		$period = $this->input->get('period');
		$data = array(
			'technician_details' => $this->M_admin->technician_productivity_details($period)
		);
		echo json_encode($data);
	}

	function export_dashboard()
	{
		// Get filter parameters from URL
		$weekly_period = $this->input->get('weekly_period') ?: '7D';
		$technician_period = $this->input->get('technician_period') ?: '7D';
		$section = $this->input->get('section') ?: 'all';

		// Convert period to days for weekly data
		$weekly_days = 7; // default
		if ($weekly_period == '1M') $weekly_days = 30;
		elseif ($weekly_period == '3M') $weekly_days = 90;
		elseif ($weekly_period == '1Y') $weekly_days = 365;

		// Get dashboard data with filter parameters
		$data = array(
			'title' 	=> 'Dashboard Report - ' . date('d F Y') . ' (Period: ' . $weekly_period . ')',
			'weekly_period' => $weekly_period,
			'technician_period' => $technician_period,
			'export_section' => $section,
			'konf'		=> $this->M_admin->konf(),
			'discount'	=> $this->M_admin->discount(),
			'bca'		=> $this->M_admin->bca(),
			'mandiri'	=> $this->M_admin->mandiri(),
			'bri'		=> $this->M_admin->bri(),
			'tunai'		=> $this->M_admin->tunai(),
			'total_bca'	=> $this->M_admin->total_bca(),
			'total_mandiri'	=> $this->M_admin->total_mandiri(),
			'total_bri'	=> $this->M_admin->total_bri(),
			'total_tunai'	=> $this->M_admin->total_tunai(),
			'total_voucher'	=> $this->M_admin->total_voucher(),
			'baru'		=> $this->M_admin->customer_baru(),
			'users_baru'	=> $this->M_admin->users_baru(),
			'weekly_revenue'	=> $this->M_admin->weekly_revenue($weekly_days),
			'weekly_transactions'	=> $this->M_admin->weekly_stats($weekly_days),
			'weekly_customers'	=> $this->M_admin->weekly_customers($weekly_days),
			'weekly_productivity'	=> $this->M_admin->weekly_technician_productivity($weekly_days),
			'technician_details'	=> $this->M_admin->technician_productivity_details($technician_period),
			'revenue_today'	=> $this->M_admin->revenue_today(),
			'service_completion_rate'	=> $this->M_admin->service_completion_rate(),
			'total_customers'	=> $this->M_admin->total_customers(),
			'dp_pending'	=> $this->M_admin->dp_pending(),
			'bank_percentages'	=> $this->M_admin->bank_percentages(),
			'tunai_percentage'	=> $this->M_admin->tunai_percentage(),
			'voucher_usage_percentage'	=> $this->M_admin->voucher_usage_percentage(),
			'total_pending_transfers'	=> $this->M_admin->total_pending_transfers()
		);

		// Load the dashboard report view for printing
		$this->load->view('Admin/dashboard_report', $data);
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Dashboard.php */