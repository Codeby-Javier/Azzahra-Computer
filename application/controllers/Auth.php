<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_auth');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->load->view('Auth/login');
	}
	function login()
	{
		$username = htmlspecialchars($this->input->post('username',TRUE), ENT_QUOTES);
		$pswd	  = htmlspecialchars($this->input->post('pswd',TRUE), ENT_QUOTES);

		$login = $this->db->get_where('karyawan', ['kry_username' => $username])->row_array();

		if ($login) {
			$this->session->set_userdata('masuk',TRUE);
			if (password_verify($pswd, $login['kry_pswd'])) {
				$data = array(
					'kode'  => $login['kry_kode'] ,
					'nama'  => $login['kry_nama'] ,
					'level' => $login['kry_level'] 
					 );
				if ($login['kry_level'] == 'Admin') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Admin','refresh');
				}elseif ($login['kry_level'] == 'Kasir') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Kasir','refresh');
				}elseif ($login['kry_level'] == 'Customer Service') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Service','refresh');
				}elseif ($login['kry_level'] == 'Teknisi') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Teknisi','refresh');
				}elseif ($login['kry_level'] == 'HR') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('HR','refresh');
				}

			} else {
				$this->session->set_flashdata('gagal', 'Password yang dimasukan salah');
				redirect('Auth','refresh');
			}
			
		} else {
			$this->session->set_flashdata('gagal', 'Username tidak ditemukan');
			redirect('Auth','refresh');
		}
		
	}
	function logout()
	{
		$this->session->sess_destroy();
		redirect('Auth','refresh');
	}
	function reset()
	{
		$this->load->view('Auth/reset');
	}
	function change_pswd()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required', array('required' => 'Username belum di isi' ));
		$this->form_validation->set_rules('pswd', 'Pasword', 'trim|required', array('required' => 'Pasword belum di isi' ));
		$this->form_validation->set_rules('con_pswd', 'Confirmasi Pasword', 'trim|required|matches[pswd]', array('required' => 'Pasword belum di isi','matches' => 'Pasword tidak sama' ));
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('Auth/reset');
		} else {
			
			$username = htmlspecialchars($this->input->post('username',TRUE), ENT_QUOTES);
			$pswd	  = htmlspecialchars($this->input->post('pswd',TRUE), ENT_QUOTES);

			$kode = $this->session->userdata('kode');

			$cek  = $this->db->get_where('karyawan', ['kry_kode' => $kode])->row_array();

			if ($cek) {
				$update = array(
						'kry_username' => $username,
						'kry_pswd' 	   => password_hash($pswd, PASSWORD_DEFAULT), 
					);
				$this->M_auth->change_pswd($kode,$update);

				$this->session->sess_destroy();
				$this->session->set_flashdata('sukses', 'DI PERBAHARI SILAHKAN LOGIN');
				redirect('Auth','refresh');
			} else {
				$this->session->sess_destroy();
				$this->session->set_flashdata('gagal', 'Username tidak ditemukan');
				redirect('Auth','refresh');
			}
			
		}
		
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */