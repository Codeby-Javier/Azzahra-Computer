<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hr extends CI_Model
{

    private $cache_path;

    public function __construct()
    {
        parent::__construct();
        $this->cache_path = APPPATH . 'cache/hr_data/';

        // Ensure cache directory exists
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0777, true);
        }

        // Load PHPExcel Library (now using PhpSpreadsheet wrapper)
        // require_once APPPATH . 'libraries/PHPExcel.php'; 
        // Commented out to avoid errors if not present, assuming direct DB operations mostly
    }

    // --- ABSENSI METHODS ---

    public function save_absensi($data)
    {
        // Ensure table exists
        if (!$this->db->table_exists('absensi')) {
            return false;
        }

        // Check if record exists (Single date per employee)
        $existing = $this->db->get_where('absensi', [
            'tanggal' => $data['tanggal'],
            'id_karyawan' => $data['id_karyawan']
        ])->row();

        if ($existing) {
            // Update existing record
            $this->db->where('tanggal', $data['tanggal']);
            $this->db->where('id_karyawan', $data['id_karyawan']);
            return $this->db->update('absensi', $data);
        } else {
            // Insert new record
            return $this->db->insert('absensi', $data);
        }
    }

    public function delete_absensi($id)
    {
        if (!$this->db->table_exists('absensi'))
            return false;
        $this->db->where('absensi_id', $id);
        return $this->db->delete('absensi');
    }

    public function get_absensi_by_date($tanggal)
    {
        if (!$this->db->table_exists('absensi'))
            return [];
        $this->db->where('tanggal', $tanggal);
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('absensi')->result_array();
    }

    public function get_absensi_all_by_periode($periode, $tipe = 'bulanan')
    {
        if (!$this->db->table_exists('absensi'))
            return [];

        if ($tipe === 'harian') {
            $this->db->where('tanggal', $periode);
        } elseif ($tipe === 'mingguan') {
            // Parse week format YYYY-W## or W##-YYYY
            if (strpos($periode, 'W') !== false) {
                $parts = explode('-W', $periode);
                if (count($parts) == 2) {
                    $year = $parts[0];
                    $week = $parts[1];
                } else {
                    $parts = explode('-', $periode);
                    // Try to guess if W01-2025 format
                    if (strpos($parts[0], 'W') !== false) {
                        $week = intval(substr($parts[0], 1));
                        $year = $parts[1];
                    } else {
                        // Fallback
                        $year = date('Y');
                        $week = date('W');
                    }
                }

                $dto = new DateTime();
                $dto->setISODate($year, $week);
                $start = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $end = $dto->format('Y-m-d');

                $this->db->where('tanggal >=', $start);
                $this->db->where('tanggal <=', $end);
            }
        } else {
            // Bulanan
            $this->db->like('tanggal', $periode, 'after');
        }

        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('absensi')->result_array();
    }

    // --- KPI METHODS ---

    public function migrate_kpi_file()
    {
        return true;
    }

    public function save_kpi($data)
    {
        if (!$this->db->table_exists('kpi'))
            return false;

        // Check if record exists
        $this->db->where('id_karyawan', $data['id_karyawan']);
        $this->db->where('periode', $data['periode']);
        $this->db->where('siklus', isset($data['siklus']) ? $data['siklus'] : 'bulanan');
        $existing = $this->db->get('kpi')->row();

        if ($existing) {
            $this->db->where('kpi_id', $existing->kpi_id);
            return $this->db->update('kpi', $data);
        } else {
            return $this->db->insert('kpi', $data);
        }
    }

    public function update_kpi_by_id($id, $data)
    {
        if (!$this->db->table_exists('kpi'))
            return false;
        $this->db->where('kpi_id', $id);
        return $this->db->update('kpi', $data);
    }

    public function delete_kpi($id)
    {
        if (!$this->db->table_exists('kpi'))
            return false;
        $this->db->where('kpi_id', $id);
        return $this->db->delete('kpi');
    }

    public function get_kpi_by_id($id)
    {
        return $this->db->get_where('kpi', ['kpi_id' => $id])->row_array();
    }

    public function get_kpi_by_siklus($siklus, $periode)
    {
        if (!$this->db->table_exists('kpi'))
            return [];

        switch ($siklus) {
            case 'harian':
                $this->db->where('siklus', 'harian');
                $this->db->where('periode', $periode);
                $this->db->order_by('nama_karyawan', 'ASC');
                return $this->db->get('kpi')->result_array();

            case 'mingguan':
                return $this->get_aggregated_kpi($periode, 'mingguan');
            case 'bulanan':
                return $this->get_aggregated_kpi($periode, 'bulanan');
            case 'tahunan':
                return $this->get_aggregated_kpi($periode, 'tahunan');
            default:
                return [];
        }
    }

    private function get_aggregated_kpi($periode, $type)
    {
        // Smart Aggregation Logic
        $this->db->select('
            id_karyawan,
            nama_karyawan,
            posisi,
            status_kerja,
            ROUND(AVG(kedisiplinan), 1) as kedisiplinan,
            ROUND(AVG(kualitas_kerja), 1) as kualitas_kerja,
            ROUND(AVG(produktivitas), 1) as produktivitas,
            ROUND(AVG(kerja_tim), 1) as kerja_tim,
            ROUND(AVG(total), 1) as total,
            ROUND(AVG(rata_rata), 2) as rata_rata,
            CASE 
                WHEN AVG(rata_rata) >= 4.5 THEN "Sangat Baik"
                WHEN AVG(rata_rata) >= 3.5 THEN "Baik"
                WHEN AVG(rata_rata) >= 2.5 THEN "Cukup"
                ELSE "Kurang"
            END as kategori,
            GROUP_CONCAT(catatan SEPARATOR "; ") as catatan
        ');
        $this->db->from('kpi');
        $this->db->where('siklus', 'harian');

        if ($type == 'mingguan') {
            // Parse week
            $year = substr($periode, 0, 4);
            $week = substr($periode, 6); // 2025-W05
            $dto = new DateTime();
            $dto->setISODate(intval($year), intval($week));
            $start = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $end = $dto->format('Y-m-d');
            $this->db->where('periode >=', $start);
            $this->db->where('periode <=', $end);
        } elseif ($type == 'bulanan') {
            $this->db->like('periode', $periode, 'after'); // 2025-01
        } elseif ($type == 'tahunan') {
            $this->db->like('periode', $periode, 'after'); // 2025
        }

        $this->db->group_by('id_karyawan');
        $this->db->order_by('rata_rata', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_kpi_by_periode($periode)
    {
        // For reports - basically monthly aggregation
        return $this->get_aggregated_kpi($periode, 'bulanan');
    }

    // --- OTHER METHODS ---

    public function get_all_karyawan_from_db()
    {
        // Check if karyawan table exists
        if (!$this->db->table_exists('karyawan')) {
            return [];
        }

        $this->db->order_by('kry_nama', 'ASC');
        $query = $this->db->get('karyawan');

        if ($query) {
            return $query->result();
        }

        return [];
    }

    public function get_karyawan_by_id($id)
    {
        if (!$this->db->table_exists('karyawan')) {
            return null;
        }

        return $this->db->get_where('karyawan', ['kry_kode' => $id])->row();
    }

    // --- Laporan Mingguan & Arsip (Standard CRUD) ---
    public function get_laporan_mingguan($periode = null)
    {
        if (!$this->db->table_exists('laporan_mingguan'))
            return [];
        
        if ($periode) {
            // Exact match for week format (e.g., 2026-W01)
            $this->db->where('periode', $periode);
        }
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('laporan_mingguan')->result_array();
    }
    
    public function save_laporan_mingguan($data)
    {
        if (!$this->db->table_exists('laporan_mingguan'))
            return false;
        
        // Check if record exists (same employee + period)
        $existing = $this->db->get_where('laporan_mingguan', [
            'id_karyawan' => $data['id_karyawan'],
            'periode' => $data['periode']
        ])->row();
        
        if ($existing) {
            // Update existing record
            $this->db->where('laporan_id', $existing->laporan_id);
            return $this->db->update('laporan_mingguan', $data);
        } else {
            // Insert new record
            return $this->db->insert('laporan_mingguan', $data);
        }
    }
    public function update_laporan_mingguan($id, $data)
    {
        $this->db->where('laporan_id', $id);
        return $this->db->update('laporan_mingguan', $data);
    }
    public function delete_laporan_mingguan($id)
    {
        $this->db->where('laporan_id', $id);
        return $this->db->delete('laporan_mingguan');
    }

    public function get_arsip($tipe = null)
    {
        if ($tipe)
            $this->db->where('tipe', $tipe);
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get('arsip')->result_array();
    }
    public function get_arsip_by_periode($tipe, $periode, $siklus)
    {
        // Simple logic reused from above
        $this->db->where('tipe', $tipe);
        if ($siklus == 'harian')
            $this->db->where('tanggal', $periode);
        else if ($siklus == 'bulanan')
            $this->db->like('tanggal', $periode, 'after');
        else if ($siklus == 'tahunan')
            $this->db->like('tanggal', $periode, 'after');
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get('arsip')->result_array();
    }
    public function save_arsip($data)
    {
        return $this->db->insert('arsip', $data);
    }
    public function update_arsip($id, $data)
    {
        $this->db->where('arsip_id', $id);
        return $this->db->update('arsip', $data);
    }
    public function delete_arsip($id)
    {
        $this->db->where('arsip_id', $id);
        return $this->db->delete('arsip');
    }

}
