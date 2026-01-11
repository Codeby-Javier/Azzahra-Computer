<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_data extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('M_hr');
    }

    public function create_poin_dummy()
    {
        // Force output buffering
        ob_start();
        
        echo "<!-- DEBUG: Starting create_poin_dummy method -->\n";
        flush();
        
        try {
            // Get current week periode
            $year = date('Y');
            $week = date('W');
            $periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
            $bulan = date('Y-m');

            // Clear existing data for this period first
            $this->db->where('periode_minggu', $periode);
            $this->db->delete('poin_performa');

            // Sample data - Simple array
            $data = [
                [
                    'id_karyawan' => 'K001',
                    'nama_karyawan' => 'Ahmad Fauzi',
                    'posisi' => 'Senior Developer',
                    'tipe_karyawan' => 'Karyawan',
                    'periode_minggu' => $periode,
                    'bulan' => $bulan,
                    'hasil_kerja' => 20,
                    'pencapaian_target' => 18,
                    'kualitas_kerja' => 15,
                    'disiplin' => 12,
                    'tanggung_jawab' => 10,
                    'produktivitas_layanan' => 8,
                    'kepatuhan_sop' => 5,
                    'minim_komplain' => 4,
                    'total_poin' => 92,
                    'catatan' => 'Performa bagus'
                ],
                [
                    'id_karyawan' => 'K002',
                    'nama_karyawan' => 'Budi Santoso',
                    'posisi' => 'Developer',
                    'tipe_karyawan' => 'Karyawan',
                    'periode_minggu' => $periode,
                    'bulan' => $bulan,
                    'hasil_kerja' => 18,
                    'pencapaian_target' => 16,
                    'kualitas_kerja' => 14,
                    'disiplin' => 13,
                    'tanggung_jawab' => 9,
                    'produktivitas_layanan' => 8,
                    'kepatuhan_sop' => 4,
                    'minim_komplain' => 3,
                    'total_poin' => 85,
                    'catatan' => 'Cukup baik'
                ],
                [
                    'id_karyawan' => 'M001',
                    'nama_karyawan' => 'Siti Nurhaliza',
                    'posisi' => 'IT Trainee',
                    'tipe_karyawan' => 'Magang',
                    'periode_minggu' => $periode,
                    'bulan' => $bulan,
                    'proses_belajar' => 22,
                    'tugas_dijalankan' => 20,
                    'sikap' => 18,
                    'kedisiplinan' => 14,
                    'kepatuhan_sop_magang' => 13,
                    'total_poin' => 87,
                    'catatan' => 'Bagus sekali'
                ],
                [
                    'id_karyawan' => 'M002',
                    'nama_karyawan' => 'Rina Wijaya',
                    'posisi' => 'Admin Trainee',
                    'tipe_karyawan' => 'Magang',
                    'periode_minggu' => $periode,
                    'bulan' => $bulan,
                    'proses_belajar' => 20,
                    'tugas_dijalankan' => 18,
                    'sikap' => 16,
                    'kedisiplinan' => 12,
                    'kepatuhan_sop_magang' => 11,
                    'total_poin' => 77,
                    'catatan' => 'Cukup baik'
                ]
            ];

            $success_count = 0;
            $errors = [];
            
            foreach ($data as $idx => $record) {
                $insert_result = $this->db->insert('poin_performa', $record);
                if ($insert_result) {
                    $success_count++;
                } else {
                    $db_error = $this->db->error();
                    $errors[] = "Record " . ($idx + 1) . ": " . $db_error['message'];
                }
            }

            // Display result
            $html = "
            <html>
            <head>
                <title>Test Data Created</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 30px; background: #f5f5f5; }
                    .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .success { color: #22c55e; font-weight: bold; font-size: 18px; margin-bottom: 20px; }
                    .warning { color: #f59e0b; font-weight: bold; }
                    .error { color: #ef4444; }
                    .info { color: #333; margin: 10px 0; }
                    ul { margin: 10px 0 0 20px; }
                    li { margin: 8px 0; }
                    a { color: #2563eb; text-decoration: none; margin-top: 20px; display: inline-block; padding: 10px 15px; background: #f0f0f0; border-radius: 4px; }
                    a:hover { background: #e0e0e0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <p class='success'>✓ Proses selesai!</p>
                    <div class='info'>
                        <p><strong>Periode:</strong> $periode</p>
                        <p><strong>Bulan:</strong> $bulan</p>
                        <p><strong>Records berhasil:</strong> $success_count / 4</p>";
                        
                if ($success_count > 0) {
                    $html .= "<p><strong>Data yang dibuat:</strong></p>
                    <ul>
                        <li>Ahmad Fauzi (K001) - Senior Developer - Karyawan - 92 poin</li>
                        <li>Budi Santoso (K002) - Developer - Karyawan - 85 poin</li>
                        <li>Siti Nurhaliza (M001) - IT Trainee - Magang - 87 poin</li>
                        <li>Rina Wijaya (M002) - Admin Trainee - Magang - 77 poin</li>
                    </ul>";
                }
                        
                if (!empty($errors)) {
                    $html .= "<p class='error'><strong>⚠ Errors:</strong></p><ul>";
                    foreach ($errors as $err) {
                        $html .= "<li class='error'>$err</li>";
                    }
                    $html .= "</ul>";
                }
                
                $html .= "</div>
                    <a href='/Azzahra-Computer-main/HR/poin_performa'>← Kembali ke Poin Performa</a>
                </div>
            </body>
            </html>";
            
            echo $html;
            
        } catch (Exception $e) {
            echo "<html><body style='padding: 20px; font-family: Arial;'>
            <h2 style='color: red;'>ERROR</h2>
            <p>" . $e->getMessage() . "</p>
            </body></html>";
        }
    }

    public function clear_poin_data()
    {
        // Get current week periode
        $year = date('Y');
        $week = date('W');
        $periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);

        // Delete data
        $this->db->where('periode_minggu', $periode);
        $deleted = $this->db->delete('poin_performa');

        $output = "
        <html>
        <head>
            <title>Data Cleared</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .info { color: orange; font-weight: bold; }
                a { color: blue; text-decoration: none; margin-top: 20px; display: inline-block; }
            </style>
        </head>
        <body>
            <h2 class='info'>⚠ Test data untuk periode $periode telah dihapus</h2>
            <a href='/Azzahra-Computer-main/HR/poin_performa'>← Kembali ke Poin Performa</a>
        </body>
        </html>
        ";
        
        echo $output;
    }
}

                'periode_minggu' => $data['periode_minggu']
            ])->row();
            
            if (!$existing) {
                $this->db->insert('poin_performa', $data);
            }
        }

        echo "Dummy data berhasil dibuat/diupdate untuk periode: " . $periode . "\n";
        echo "Total records: " . count($all_data);
        redirect('HR/poin_performa');
    }

    public function clear_poin_data()
    {
        // Clear poin_performa table for current week
        $year = date('Y');
        $week = date('W');
        $periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);

        $this->db->where('periode_minggu', $periode);
        $this->db->delete('poin_performa');

        echo "Data periode " . $periode . " sudah dihapus";
        redirect('HR/poin_performa');
    }
}
?>
