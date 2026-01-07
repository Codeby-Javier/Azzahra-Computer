<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_karyawan');
		$this->load->library('form_validation');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }
	}

	public function index()
	{
		$data = array(
			'title' 	=> 'Data Karyawan',
			'karyawan'	=> $this->M_karyawan->GetAll(),
			'no'		=> $this->uri->segment(3)
			 );
		$this->load->view('Karyawan/read',$data);
	}
	function save()
	{
		$this->form_validation->set_rules('nik', 'NIK', 'trim|required|is_unique[karyawan.kry_nik]', array('required' => 'NIK Belum Di Isi','is_unique'=> 'NIK sudah digunakan' ));
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[karyawan.kry_username]', array('required' => 'Username Belum Di Isi','is_unique'=> 'Username sudah digunakan' ));

		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'title' 	=> 'Data Karyawan',
				'karyawan'	=> $this->M_karyawan->GetAll(),
				'no'		=> $this->uri->segment(3)
				 );
			$this->load->view('Karyawan/read',$data);
		}else{
			$data = array(
				'kry_kode' 		=> karyawan(),
				'kry_nik'		=> $this->input->post('nik'),
				'kry_nama' 		=> $this->input->post('nama'),
				'kry_tempat' 	=> $this->input->post('tempat'),
				'kry_tgl_lahir' => $this->input->post('tgl_lahir'),
				'kry_alamat' 	=> $this->input->post('alamat'),
				'kry_tlp' 		=> $this->input->post('tlp'),
				'kry_username'  => $this->input->post('username'),
				'kry_pswd' 		=> password_hash($this->input->post('pswd'), PASSWORD_DEFAULT),
				'kry_level' 	=> $this->input->post('level'),
				'kry_tgl_masuk' => $this->input->post('tgl_masuk')

			);
			$this->M_karyawan->save($data);

			$this->session->set_flashdata('sukses', 'DI TAMBAHKAN');
			redirect('Karyawan','refresh');
		}

	}
	function update()
	{
		$kode = $this->input->post('kode');
		$data = array(
			'kry_nik'		 => $this->input->post('nik'),
			'kry_nama' 		 => $this->input->post('nama'),
			'kry_tempat' 	 => $this->input->post('tempat'),
			'kry_tgl_lahir'  => $this->input->post('tgl_lahir'),
			'kry_alamat' 	 => $this->input->post('alamat'),
			'kry_tlp' 		 => $this->input->post('tlp'),
			'kry_tgl_masuk'  => $this->input->post('tgl_masuk'),
			'kry_tgl_keluar' => $this->input->post('tgl_keluar')

		);
		$this->M_karyawan->update($kode,$data);

		$this->session->set_flashdata('sukses', 'DI PERBAHARUI');
		redirect('Karyawan','refresh');
	}
	function delete()
	{
		$kode = $this->uri->segment(3);

		$this->M_karyawan->delete($kode);
		$this->session->set_flashdata('sukses', 'DI HAPUS');
		redirect('Karyawan','refresh');
	}

}

/* End of file Karyawan.php */
/* Location: ./application/controllers/Karyawan.php */