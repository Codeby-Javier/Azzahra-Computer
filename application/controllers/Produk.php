<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_produk');
        $this->load->library('pagination');	
        $this->load->library('form_validation');

        // Check login
        if($this->session->userdata('masuk') != TRUE){
            redirect(base_url());
        }

        // Check admin
        if ($this->session->userdata('level') != 'Admin') {
            redirect(base_url());
        }
    }

    /**
     * Halaman utama produk
     */
    public function index()
    {
        $data = array(
            'title' => 'Produk'
        );
        
        $this->load->view('Template/header', $data);
        $this->load->view('Produk/read', $data);
        $this->load->view('Template/footer');
		
    }

    /**
     * AJAX Search & Pagination
     */
    public function ajax_search()
    {
		$search = $this->input->get('search', TRUE);
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $per_page = 15;	
        
        // Get data
        $data['produk'] = $this->M_produk->get_produk_paginated($search, $per_page, $page);
        $data['total_records'] = $this->M_produk->count_all_produk($search);
        $data['offset'] = $page;
        $data['per_page'] = $per_page;
        
        // Pagination config
        $config['base_url'] = site_url('Produk/index');
        $config['total_rows'] = $data['total_records'];
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Style pagination
        $this->set_pagination_style($config);
        
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        // Modify links untuk AJAX
        $data['pagination'] = $this->modify_pagination_links($pagination);
        
        // Load view
		
        $this->load->view('Produk/ajax_table', $data);
    }

    /**
     * Tambah produk
     */
    public function add()
    {
        // Generate kode_barang otomatis
        $kode_barang = $this->M_produk->generate_kode_barang();
        
        $data = array(
            'title' => 'Tambah Produk',
            'kode_barang' => $kode_barang
        );
        
        $this->load->view('Template/header', $data);
        $this->load->view('Produk/add', $data);
        $this->load->view('Template/footer');
    }

	public function save()
	{
		log_message('info', 'Produk save method called');

		$this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim');
		$this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim');
		$this->form_validation->set_rules('harga', 'Harga', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('error', 'Produk save validation failed: ' . validation_errors());
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => validation_errors()]);
				return;
			}
			$this->session->set_flashdata('error', validation_errors());
			redirect('Produk/add');
		}

		$kode_barang = $this->input->post('kode_barang', TRUE);
		log_message('info', 'Processing kode_barang: ' . $kode_barang);

		if ($this->M_produk->is_kode_barang_exists($kode_barang)) {
			log_message('error', 'Kode barang already exists: ' . $kode_barang);
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'Kode barang sudah digunakan!']);
				return;
			}
			$this->session->set_flashdata('error', 'Kode barang sudah digunakan!');
			redirect('Produk/add');
		}

		$harga = str_replace('.', '', $this->input->post('harga', TRUE));

		$data = array(
			'kode_barang' => $kode_barang,
			'nama_produk' => $this->input->post('nama_produk', TRUE),
			'harga' => $harga,
			'deskripsi' => $this->input->post('deskripsi', TRUE)
		);

		// Upload gambar ke Laravel API (multiple)
		$uploaded_images = array();
		log_message('info', 'Checking FILES array: ' . json_encode($_FILES));
		if (!empty($_FILES['gambar']['name'][0])) {
			log_message('info', 'Starting image upload for kode_barang: ' . $kode_barang);
			$files_count = count($_FILES['gambar']['name']);
			log_message('info', 'Number of files to upload: ' . $files_count);

			for ($i = 0; $i < $files_count; $i++) {
				if ($_FILES['gambar']['error'][$i] == 0) {
					$filename = 'produk_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION);
					log_message('info', 'Uploading file ' . ($i+1) . ': ' . $filename);

					// Upload via API
					$mime_type = isset($_FILES['gambar']['type'][$i]) ? $_FILES['gambar']['type'][$i] : 'application/octet-stream';
					$result = $this->upload_image_to_api($_FILES['gambar']['tmp_name'][$i], $filename, 'produk', $mime_type);
					if ($result['success']) {
						$uploaded_images[] = $result['filename'];
						log_message('info', 'Image upload successful: ' . $result['filename']);
					} else {
						log_message('error', 'Image upload failed for ' . $filename . ': ' . $result['message']);
						if ($this->input->is_ajax_request()) {
							echo json_encode(['success' => false, 'message' => 'Gagal upload gambar: ' . $result['message']]);
							return;
						}
						$this->session->set_flashdata('error', 'Gagal upload gambar: ' . $result['message']);
						redirect('Produk/add');
					}
				} else {
					log_message('error', 'File upload error for index ' . $i . ': ' . $_FILES['gambar']['error'][$i]);
				}
			}
		} else {
			log_message('info', 'No images to upload');
		}

		// Simpan nama file gambar sebagai string dipisah koma
		$data['gambar'] = !empty($uploaded_images) ? implode(',', $uploaded_images) : '';
		log_message('info', 'Prepared data for insert: ' . json_encode($data));

		if ($this->M_produk->insert_produk($data)) {
			log_message('info', 'Product inserted successfully: ' . $kode_barang);
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan.']);
				return;
			}
			$this->session->set_flashdata('sukses', 'Produk berhasil ditambahkan.');
		} else {
			log_message('error', 'Failed to insert product: ' . $kode_barang);
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'Gagal menambahkan produk.']);
				return;
			}
			$this->session->set_flashdata('error', 'Gagal menambahkan produk.');
		}

		redirect('Produk');
	}

	/**
	 * Edit produk
	 */
	public function edit($kode_barang)
	{
		$kode_barang = urldecode($kode_barang);
		log_message('info', 'Edit produk called with kode_barang: ' . $kode_barang);

		$produk = $this->M_produk->get_produk_by_id($kode_barang);
		
		if (!$produk) {
			show_404();
		}
		
		$data = array(
			'title' => 'Edit Produk',
			'produk' => $produk
		);
		
		$this->load->view('Template/header', $data);
		$this->load->view('Produk/edit', $data);
		$this->load->view('Template/footer');
	}
	/**	
	 * Update produk
	 */
	public function update()
	{
		$kode_barang = $this->input->post('kode_barang', TRUE);
		$kode_barang_lama = $this->input->post('kode_barang_lama', TRUE);

		// Get original product data
		$produk = $this->M_produk->get_produk_by_id($kode_barang_lama);
		if (!$produk) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
				return;
			}
			$this->session->set_flashdata('error', 'Produk tidak ditemukan.');
			redirect('Produk');
		}

		$this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim');
		$this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim');
		$this->form_validation->set_rules('harga', 'Harga', 'required');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => validation_errors()]);
				return;
			}
			$this->session->set_flashdata('error', validation_errors());
			redirect('Produk/edit/' . $kode_barang_lama);
		}

		if ($kode_barang != $kode_barang_lama) {
			if ($this->M_produk->is_kode_barang_exists($kode_barang, $kode_barang_lama)) {
				if ($this->input->is_ajax_request()) {
					echo json_encode(['success' => false, 'message' => 'Kode barang sudah digunakan!']);
					return;
				}
				$this->session->set_flashdata('error', 'Kode barang sudah digunakan!');
				redirect('Produk/edit/' . $kode_barang_lama);
			}
		}

		$harga = str_replace('.', '', $this->input->post('harga', TRUE));

		$data = array(
			'kode_barang' => $kode_barang,
			'nama_produk' => $this->input->post('nama_produk', TRUE),
			'harga' => $harga,
			'deskripsi' => $this->input->post('deskripsi', TRUE)
		);

		// ========== HANDLE IMAGES ==========

		// 1. Gambar yang di-delete
		$delete_images_json = $this->input->post('delete_images', TRUE);
		$delete_images = json_decode($delete_images_json, true) ?: array();

		// Hapus file via API untuk deleted images
		foreach ($delete_images as $img_name) {
			$this->delete_image_from_api($img_name, 'produk');
		}

		// 2. Upload gambar baru/replaced
		$new_images = array();
		if (!empty($_FILES['images']['name'][0])) {
			$files_count = count($_FILES['images']['name']);

			for ($i = 0; $i < $files_count; $i++) {
				if ($_FILES['images']['error'][$i] == 0) {
					$filename = 'produk_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);

					// Upload via API
					$mime_type = isset($_FILES['images']['type'][$i]) ? $_FILES['images']['type'][$i] : 'application/octet-stream';
					$result = $this->upload_image_to_api($_FILES['images']['tmp_name'][$i], $filename, 'produk', $mime_type);
					if ($result['success']) {
						$new_images[] = $result['filename'];
					} else {
						if ($this->input->is_ajax_request()) {
							echo json_encode(['success' => false, 'message' => 'Gagal upload gambar: ' . $result['message']]);
							return;
						}
						$this->session->set_flashdata('error', 'Gagal upload gambar: ' . $result['message']);
						redirect('Produk/edit/' . $kode_barang_lama);
					}
				}
			}
		}

		// 3. Parse images_info untuk mapping replace
		$images_info_array = $this->input->post('images_info', TRUE);
		$replace_map = array(); // oldName => newFileName
		$add_images = array(); // new images to add

		if (is_array($images_info_array)) {
			foreach ($images_info_array as $index => $info_json) {
				$info = json_decode($info_json, true);
				if ($info['action'] === 'replace' && isset($new_images[$index])) {
					$replace_map[$info['oldName']] = $new_images[$index];
				} elseif ($info['action'] === 'add' && isset($new_images[$index])) {
					$add_images[] = $new_images[$index];
				}
			}
		}

		// 4. Build final images maintaining order
		$original_images = explode(',', $produk->gambar);
		$final_images = array();

		foreach ($original_images as $img) {
			$img = trim($img);
			if (in_array($img, $delete_images)) {
				// Already deleted above, skip
				continue;
			} elseif (isset($replace_map[$img])) {
				// Replace with new image (old file will be deleted via API above)
				$final_images[] = $replace_map[$img];
			} else {
				// Keep original
				$final_images[] = $img;
			}
		}

		// Append newly added images at the end
		$final_images = array_merge($final_images, $add_images);

		// Update database dengan urutan gambar yang benar
		$data['gambar'] = !empty($final_images) ? implode(',', $final_images) : NULL;

		// Update database
		if ($kode_barang != $kode_barang_lama) {
			$this->db->trans_start();
			$this->M_produk->delete_produk($kode_barang_lama);
			$this->M_produk->insert_produk($data);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				if ($this->input->is_ajax_request()) {
					echo json_encode(['success' => false, 'message' => 'Gagal mengupdate produk.']);
					return;
				}
				$this->session->set_flashdata('error', 'Gagal mengupdate produk.');
			} else {
				if ($this->input->is_ajax_request()) {
					echo json_encode(['success' => true, 'message' => 'Produk berhasil diupdate.']);
					return;
				}
				$this->session->set_flashdata('sukses', 'Produk berhasil diupdate.');
			}
		} else {
			if ($this->M_produk->update_produk($kode_barang, $data)) {
				if ($this->input->is_ajax_request()) {
					echo json_encode(['success' => true, 'message' => 'Produk berhasil diupdate.']);
					return;
				}
				$this->session->set_flashdata('sukses', 'Produk berhasil diupdate.');
			} else {
				if ($this->input->is_ajax_request()) {
					echo json_encode(['success' => false, 'message' => 'Gagal mengupdate produk.']);
					return;
				}
				$this->session->set_flashdata('error', 'Gagal mengupdate produk.');
			}
		}

		redirect('Produk');
	}
	/**
	 * Delete produk
	 */

	public function delete($kode_barang)
	{
		$kode_barang = urldecode($kode_barang);
		log_message('info', 'Delete produk called with kode_barang: ' . $kode_barang);

		// Hapus gambar dari Laravel API
		$produk = $this->M_produk->get_produk_by_id($kode_barang);
		if ($produk && $produk->gambar) {
			$images = explode(',', $produk->gambar);
			foreach ($images as $image) {
				$this->delete_image_from_api(trim($image), 'produk');
			}
		}

		if ($this->M_produk->delete_produk($kode_barang)) {
			$response = ['success' => true, 'message' => 'Produk berhasil dihapus.'];
		} else {
			$response = ['success' => false, 'message' => 'Gagal menghapus produk.'];
		}

		if ($this->input->is_ajax_request()) {
			header('Content-Type: application/json');
			echo json_encode($response);
		} else {
			$this->session->set_flashdata('sukses', 'Produk berhasil dihapus.');
			redirect('Produk');
		}
	}

    /**
     * Helper: Set pagination style
     */
    /**
	 * Helper: Set pagination style
	 */
	private function set_pagination_style(&$config)
	{
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		
		$config['first_link'] = '««';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_link'] = '»»';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_link'] = '›';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_link'] = '‹';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li><span class="page-link active">';
		$config['cur_tag_close'] = '</span></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$config['attributes'] = array('class' => 'page-link');
		
		$config['num_links'] = 5;
	}
    /**
     * Helper: Modify pagination links untuk AJAX
     */
    private function modify_pagination_links($links)
    {
        if (!$links) return '';

        // Replace href dengan data-page
        $links = preg_replace_callback(
            '/href="[^"]*page=(\d+)[^"]*"/',
            function($matches) {
                return 'href="javascript:void(0)" data-page="' . $matches[1] . '"';
            },
            $links
        );

        return $links;
    }

    /**
     * Sync data to Laravel API
     */
    private function sync_to_laravel($method, $url, $data = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            // Log error but don't fail the operation
            log_message('error', 'CURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        // Log the sync attempt
        log_message('info', 'Sync to Laravel API: ' . $method . ' ' . $url . ' - HTTP ' . $http_code);

        return $response;
    }

    /**
     * Upload image to Laravel API via HTTP
     */
    private function upload_image_to_api($file_path, $filename, $type = 'produk', $mime_type = null)
    {
        try {
            log_message('info', 'Starting image upload to API: ' . $filename . ' type: ' . $type);

            // Check if curl extension is loaded
            if (!extension_loaded('curl')) {
                log_message('error', 'CURL extension not loaded');
                return [
                    'success' => false,
                    'message' => 'CURL extension not available'
                ];
            }

            $api_url = $this->config->item('laravel_api_url') . '/upload-image';
            log_message('info', 'API URL: ' . $api_url);

            // Detect PHP version and CURLFile availability
            if (function_exists('curl_file_create') && class_exists('CURLFile')) {
                // Modern PHP with CURLFile support
                // Use provided MIME type or default
                if ($mime_type === null) {
                    $mime_type = 'application/octet-stream';
                }
                $cFile = curl_file_create($file_path, $mime_type, $filename);
            } else {
                // Older PHP compatibility
                $cFile = '@' . $file_path . ';filename=' . $filename;
            }

            $post_data = array(
                'image'    => $cFile,
                'filename' => $filename,
                'type'     => $type
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);

            curl_close($ch);

            log_message('info', 'API response HTTP code: ' . $http_code);
            log_message('info', 'API response: ' . $response);

            // Handle errors
            if ($curl_error) {
                log_message('error', 'CURL Error during image upload: ' . $curl_error);
                return [
                    'success' => false,
                    'message' => 'CURL Error: ' . $curl_error
                ];
            }

            // Decode JSON dari laravel
            $result = json_decode($response, true);

            if ($http_code === 200 && isset($result['success']) && $result['success']) {
                log_message('info', 'Image upload successful: ' . ($result['filename'] ?? $filename));
                return [
                    'success'  => true,
                    'filename' => $result['filename'] ?? $filename
                ];
            }

            log_message('error', 'Image upload failed: HTTP ' . $http_code . ' - ' . ($result['message'] ?? 'Unknown error'));
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Upload gagal (HTTP ' . $http_code . ')'
            ];

        } catch (Exception $e) {
            log_message('error', 'Exception in upload_image_to_api: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }


    /**
     * Delete image from Laravel API via HTTP
     */
    private function delete_image_from_api($filename, $type = 'produk')
    {
        try {
            // Delete image via HTTP from Laravel API
            $api_url = $this->config->item('laravel_api_url') . '/delete-image';

            $post_data = array(
                'filename' => $filename,
                'type' => $type
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ));

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);

            curl_close($ch);

            // Log the delete attempt
            log_message('info', 'Image delete from API: ' . $api_url . ' - HTTP ' . $http_code);

            if ($curl_error) {
                log_message('error', 'CURL Error during image delete: ' . $curl_error);
                return false;
            }

            return $http_code == 200;
        } catch (Exception $e) {
            log_message('error', 'Exception in delete_image_from_api: ' . $e->getMessage());
            return false;
        }
    }
}
