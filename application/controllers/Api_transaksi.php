<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_transaksi');
		header('Content-Type: application/json'); // Tambahkan ini
	}

	public function tambah()
	{
		// Read JSON data from raw input stream
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// Validate JSON format
		if (json_last_error() !== JSON_ERROR_NONE) {
			$response = array(
				'status' => false,
				'message' => 'Format JSON tidak valid',
				'error' => json_last_error_msg()
			);
			echo json_encode($response);
			exit; // Use exit instead of return to prevent further output
		}

		// Validate if data is empty
		if (empty($data)) {
			$response = array(
				'status' => false,
				'message' => 'Data kosong'
			);
			echo json_encode($response);
			exit;
		}

		// Prepare data for insertion
		$insert_data = array(
			'id_costomer' => $data['cos_kode'],
			'kry_kode' => $data['kry_kode'],
			'trans_total' => $data['trans_total'],
			'trans_discount' => $data['trans_discount'],
			'trans_status' => $data['trans_status'],
			'trans_tanggal' => date('Y-m-d H:i:s'), // timestamp sekarang
		);

		// Insert data into database
		if ($this->M_transaksi->save_order_list($insert_data)) {
			$response = array(
				'status' => true,
				'message' => 'Order berhasil disimpan',
				'data' => $insert_data
			);
		} else {
			$response = array(
				'status' => false,
				'message' => 'Gagal menyimpan order'
			);
		}

		echo json_encode($response);
		exit; // Ensure no additional output
	}
}
