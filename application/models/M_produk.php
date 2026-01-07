<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produk extends CI_Model {

    /**
     * Get all produk dengan pagination dan search
     */
    public function get_all_produk($limit = null, $offset = null, $search = null)
    {
        $this->db->select('*');
        $this->db->from('produk');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('nama_produk', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->or_like('kode_barang', $search);
            $this->db->or_like('harga', $search);
            $this->db->group_end();
        }

        $this->db->order_by('kode_barang', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get();
    }

    /**
     * Get produk paginated (untuk AJAX)
     */
    public function get_produk_paginated($search = null, $limit = 15, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from('produk');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('nama_produk', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->or_like('kode_barang', $search);
            $this->db->or_like('harga', $search);
            $this->db->group_end();
        }

        $this->db->order_by('kode_barang', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get();
    }

    /**
     * Count total produk
     */
    public function count_all_produk($search = null)
    {
        $this->db->from('produk');

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('nama_produk', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->or_like('kode_barang', $search);
            $this->db->or_like('harga', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /**
     * Insert produk
     */
    public function insert_produk($data)
    {
        return $this->db->insert('produk', $data);
    }

    /**
     * Update produk
     */
    public function update_produk($kode_barang, $data)
    {
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->update('produk', $data);
    }

    /**
     * Delete produk
     */
    public function delete_produk($kode_barang)
    {
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->delete('produk');
    }

    /**
     * Delete multiple produk
     */
    public function delete_multiple_produk($kode_barangs)
    {
        $this->db->where_in('kode_barang', $kode_barangs);
        return $this->db->delete('produk');
    }

    /**
     * Get produk by kode_barang
     */
    public function get_produk_by_id($kode_barang)
    {
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->get('produk')->row();
    }

    /**
     * Check if produk exists
     */
    public function is_produk_exists($kode_barang)
    {
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->count_all_results('produk') > 0;
    }

    /**
     * Generate kode_barang otomatis
     * Format: PRD-YYYYMMDD-XXXX
     */
    public function generate_kode_barang()
    {
        $prefix = 'PRD';
        $date = date('Ymd');

        // Get last kode with same date
        $this->db->select('kode_barang');
        $this->db->from('produk');
        $this->db->like('kode_barang', $prefix . '-' . $date, 'after');
        $this->db->order_by('kode_barang', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_kode = $query->row()->kode_barang;
            // Extract number part: PRD-20240101-0001 -> 0001
            $last_number = (int) substr($last_kode, -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        // Format: PRD-20240101-0001
        return $prefix . '-' . $date . '-' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if kode_barang already exists
     */
    public function is_kode_barang_exists($kode_barang, $exclude_kode = null)
    {
        $this->db->where('kode_barang', $kode_barang);

        if ($exclude_kode !== null) {
            $this->db->where('kode_barang !=', $exclude_kode);
        }

        return $this->db->count_all_results('produk') > 0;
    }
}