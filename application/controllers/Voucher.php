<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_voucher');
        $this->load->library('pagination');
        $this->load->library('form_validation');

        // Check login
        if($this->session->userdata('masuk') != TRUE){
            redirect(base_url());
        }

        // Check level: Admin or Customer Service
        $level = $this->session->userdata('level');
        if ($level != 'Admin' && $level != 'Customer Service') {
            redirect(base_url());
        }
    }

    /**
     * Halaman utama voucher
     */
    public function index()
    {
        // Clear any leftover flashdata
        $this->session->unset_userdata(['sukses', 'error']);

        $data = array(
            'title' => 'Voucher Discount'
        );

        $this->load->view('Template/header', $data);
        $this->load->view('Voucher/read', $data);
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
        $data['voucher'] = $this->M_voucher->get_voucher_paginated($search, $per_page, $page);
        $data['total_records'] = $this->M_voucher->count_all_voucher($search);
        $data['offset'] = $page;
        $data['per_page'] = $per_page;

        // Pagination config
        $config['base_url'] = site_url('Voucher/index');
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
        $this->load->view('Voucher/ajax_table', $data);
    }

    /**
     * Tambah voucher
     */
    public function add()
    {
        $data = array(
            'title' => 'Tambah Voucher'
        );

        $this->load->view('Template/header', $data);
        $this->load->view('Voucher/add', $data);
        $this->load->view('Template/footer');
    }

    public function save()
    {
        log_message('info', 'Voucher save method called');

        $this->form_validation->set_rules('voucher_code', 'Voucher Code', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('discount_percent', 'Discount Percent', 'required|numeric');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim');
        $this->form_validation->set_rules('max_usage', 'Max Usage', 'numeric');

        if ($this->form_validation->run() == FALSE) {
            log_message('error', 'Voucher save validation failed: ' . validation_errors());
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => validation_errors()]);
                return;
            }
            $this->session->set_flashdata('error', validation_errors());
            redirect('Voucher/add');
        }

        $voucher_code = $this->input->post('voucher_code', TRUE);
        log_message('info', 'Processing voucher_code: ' . $voucher_code);

        if ($this->M_voucher->is_voucher_code_exists($voucher_code)) {
            log_message('error', 'Voucher code already exists: ' . $voucher_code);
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Voucher code sudah digunakan!']);
                return;
            }
            $this->session->set_flashdata('error', 'Voucher code sudah digunakan!');
            redirect('Voucher/add');
        }

        $data = array(
            'voucher_code' => $voucher_code,
            'description' => $this->input->post('description', TRUE),
            'discount_percent' => $this->input->post('discount_percent', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'max_usage' => $this->input->post('max_usage', TRUE) ?: 1,
            'status' => $this->input->post('status', TRUE) ?: 'active'
        );

        // Upload gambar ke Laravel API
        if (!empty($_FILES['voucher_gambar']['name'])) {
            log_message('info', 'Uploading voucher image');
            $filename = 'voucher_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['voucher_gambar']['name'], PATHINFO_EXTENSION);

            // Upload via API
            $mime_type = isset($_FILES['voucher_gambar']['type']) ? $_FILES['voucher_gambar']['type'] : 'application/octet-stream';
            $result = $this->upload_image_to_api($_FILES['voucher_gambar']['tmp_name'], $filename, 'voucher', $mime_type);
            if ($result['success']) {
                $data['voucher_gambar'] = $result['filename'];
                log_message('info', 'Voucher image uploaded successfully: ' . $result['filename']);
            } else {
                log_message('error', 'Voucher image upload failed: ' . $result['message']);
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'message' => 'Gagal upload gambar: ' . $result['message']]);
                    return;
                }
                $this->session->set_flashdata('error', 'Gagal upload gambar: ' . $result['message']);
                redirect('Voucher/add');
            }
        } else {
            log_message('info', 'No voucher image to upload');
        }

        if ($this->M_voucher->insert_voucher($data)) {
            log_message('info', 'Voucher inserted successfully: ' . $voucher_code);
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => true, 'message' => 'Voucher berhasil ditambahkan.']);
                return;
            }
            $this->session->set_flashdata('sukses', 'Voucher berhasil ditambahkan.');
            redirect('Voucher');
        } else {
            log_message('error', 'Failed to insert voucher: ' . $voucher_code);
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan voucher.']);
                return;
            }
            $this->session->set_flashdata('error', 'Gagal menambahkan voucher.');
            redirect('Voucher/add');
        }
    }

    /**
     * Edit voucher
     */
    public function edit($voucher_id)
    {
        $voucher = $this->M_voucher->get_voucher_by_id($voucher_id);

        if (!$voucher) {
            show_404();
        }

        $data = array(
            'title' => 'Edit Voucher',
            'voucher' => $voucher
        );

        $this->load->view('Template/header', $data);
        $this->load->view('Voucher/edit', $data);
        $this->load->view('Template/footer');
    }

    /**
     * Update voucher
     */
    public function update()
    {
        $voucher_id = $this->input->post('voucher_id', TRUE);
        log_message('info', 'Voucher update method called for ID: ' . $voucher_id);

        $this->form_validation->set_rules('voucher_code', 'Voucher Code', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('discount_percent', 'Discount Percent', 'required|numeric');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim');
        $this->form_validation->set_rules('max_usage', 'Max Usage', 'numeric');

        if ($this->form_validation->run() == FALSE) {
            log_message('error', 'Voucher update validation failed: ' . validation_errors());
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => validation_errors()]);
                return;
            }
            $this->session->set_flashdata('error', validation_errors());
            redirect('Voucher/edit/' . $voucher_id);
        }

        $voucher_code = $this->input->post('voucher_code', TRUE);
        $old_voucher_code = $this->input->post('old_voucher_code', TRUE);
        log_message('info', 'Updating voucher_code from ' . $old_voucher_code . ' to ' . $voucher_code);

        if ($voucher_code != $old_voucher_code) {
            if ($this->M_voucher->is_voucher_code_exists($voucher_code, $old_voucher_code)) {
                log_message('error', 'New voucher code already exists: ' . $voucher_code);
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'message' => 'Voucher code sudah digunakan!']);
                    return;
                }
                $this->session->set_flashdata('error', 'Voucher code sudah digunakan!');
                redirect('Voucher/edit/' . $voucher_id);
            }
        }

        $data = array(
            'voucher_code' => $voucher_code,
            'description' => $this->input->post('description', TRUE),
            'discount_percent' => $this->input->post('discount_percent', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'max_usage' => $this->input->post('max_usage', TRUE) ?: 1,
            'status' => $this->input->post('status', TRUE) ?: 'active'
        );

        // Handle image upload
        if (!empty($_FILES['voucher_gambar']['name'])) {
            log_message('info', 'Uploading new voucher image for update');
            $filename = 'voucher_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['voucher_gambar']['name'], PATHINFO_EXTENSION);

            // Upload via API
            $mime_type = isset($_FILES['voucher_gambar']['type']) ? $_FILES['voucher_gambar']['type'] : 'application/octet-stream';
            $result = $this->upload_image_to_api($_FILES['voucher_gambar']['tmp_name'], $filename, 'voucher', $mime_type);
            if ($result['success']) {
                $data['voucher_gambar'] = $result['filename'];
                log_message('info', 'New voucher image uploaded: ' . $result['filename']);

                // Delete old image if exists
                $old_voucher = $this->M_voucher->get_voucher_by_id($voucher_id);
                if ($old_voucher && $old_voucher->voucher_gambar) {
                    $old_filename = $old_voucher->voucher_gambar;
                    log_message('info', 'Deleting old voucher image: ' . $old_filename);
                    $this->delete_image_from_api($old_filename, 'voucher');
                }
            } else {
                log_message('error', 'Voucher image upload failed during update: ' . $result['message']);
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'message' => 'Gagal upload gambar: ' . $result['message']]);
                    return;
                }
                $this->session->set_flashdata('error', 'Gagal upload gambar: ' . $result['message']);
                redirect('Voucher/edit/' . $voucher_id);
            }
        } else {
            log_message('info', 'No new voucher image for update');
        }

        if ($this->M_voucher->update_voucher($voucher_id, $data)) {
            log_message('info', 'Voucher updated successfully: ' . $voucher_code);
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => true, 'message' => 'Voucher berhasil diupdate.']);
                return;
            }
            $this->session->set_flashdata('sukses', 'Voucher berhasil diupdate.');
            redirect('Voucher');
        } else {
            log_message('error', 'Failed to update voucher: ' . $voucher_code);
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate voucher.']);
                return;
            }
            $this->session->set_flashdata('error', 'Gagal mengupdate voucher.');
            redirect('Voucher/edit/' . $voucher_id);
        }
    }

    /**
     * Delete voucher
     */
    public function delete($voucher_id)
    {
        log_message('info', 'Voucher delete method called for ID: ' . $voucher_id);

        // Hapus gambar dari Laravel API
        $voucher = $this->M_voucher->get_voucher_by_id($voucher_id);
        if ($voucher && $voucher->voucher_gambar) {
            $filename = $voucher->voucher_gambar;
            log_message('info', 'Deleting voucher image: ' . $filename);
            $this->delete_image_from_api($filename, 'voucher');
        } else {
            log_message('info', 'No voucher image to delete');
        }

        if ($this->M_voucher->delete_voucher($voucher_id)) {
            log_message('info', 'Voucher deleted successfully: ' . $voucher_id);
            $response = ['success' => true, 'message' => 'Voucher berhasil dihapus.'];
        } else {
            log_message('error', 'Failed to delete voucher: ' . $voucher_id);
            $response = ['success' => false, 'message' => 'Gagal menghapus voucher.'];
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('sukses', 'Voucher berhasil dihapus.');
            redirect('Voucher');
        }
    }

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
     * Upload image to Laravel API via HTTP
     */
    private function upload_image_to_api($file_path, $filename, $type = 'voucher', $mime_type = null)
    {
        try {
            log_message('info', 'Starting voucher image upload to API: ' . $filename . ' type: ' . $type);

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
                log_message('error', 'CURL Error during voucher image upload: ' . $curl_error);
                return [
                    'success' => false,
                    'message' => 'CURL Error: ' . $curl_error
                ];
            }

            // Decode JSON dari laravel
            $result = json_decode($response, true);

            if ($http_code === 200 && isset($result['success']) && $result['success']) {
                log_message('info', 'Voucher image upload successful: ' . ($result['filename'] ?? $filename));
                return [
                    'success'  => true,
                    'filename' => $result['filename'] ?? $filename
                ];
            }

            log_message('error', 'Voucher image upload failed: HTTP ' . $http_code . ' - ' . ($result['message'] ?? 'Unknown error'));
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Upload gagal (HTTP ' . $http_code . ')'
            ];

        } catch (Exception $e) {
            log_message('error', 'Exception in voucher upload_image_to_api: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete image from Laravel API via HTTP
     */
    private function delete_image_from_api($filename, $type = 'voucher')
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
            log_message('info', 'Voucher image delete from API: ' . $api_url . ' - HTTP ' . $http_code);

            if ($curl_error) {
                log_message('error', 'CURL Error during voucher image delete: ' . $curl_error);
                return false;
            }

            return $http_code == 200;
        } catch (Exception $e) {
            log_message('error', 'Exception in voucher delete_image_from_api: ' . $e->getMessage());
            return false;
        }
    }
}