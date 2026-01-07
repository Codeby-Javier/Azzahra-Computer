<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_voucher extends CI_Model {

    /**
     * Get all voucher dengan pagination dan search
     */
    public function get_all_voucher($limit = null, $offset = null, $search = null)
    {
        $this->db->select('*');
        $this->db->from('voucher');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('voucher_code', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('discount_percent', $search);
            $this->db->group_end();
        }

        $this->db->order_by('voucher_id', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get();
    }

    /**
     * Get voucher paginated (untuk AJAX)
     */
    public function get_voucher_paginated($search = null, $limit = 15, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from('voucher');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('voucher_code', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('discount_percent', $search);
            $this->db->group_end();
        }

        $this->db->order_by('voucher_id', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get();
    }

    /**
     * Count total voucher
     */
    public function count_all_voucher($search = null)
    {
        $this->db->from('voucher');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('voucher_code', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('discount_percent', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /**
     * Insert voucher
     */
    public function insert_voucher($data)
    {
        return $this->db->insert('voucher', $data);
    }

    /**
     * Update voucher
     */
    public function update_voucher($voucher_id, $data)
    {
        $this->db->where('voucher_id', $voucher_id);
        return $this->db->update('voucher', $data);
    }

    /**
     * Delete voucher
     */
    public function delete_voucher($voucher_id)
    {
        $this->db->where('voucher_id', $voucher_id);
        return $this->db->delete('voucher');
    }

    /**
     * Get voucher by voucher_id
     */
    public function get_voucher_by_id($voucher_id)
    {
        $this->db->where('voucher_id', $voucher_id);
        return $this->db->get('voucher')->row();
    }

    /**
     * Check if voucher_code already exists
     */
    public function is_voucher_code_exists($voucher_code, $exclude_id = null)
    {
        $this->db->where('voucher_code', $voucher_code);

        if ($exclude_id !== null) {
            $this->db->where('voucher_id !=', $exclude_id);
        }

        return $this->db->count_all_results('voucher') > 0;
    }
}