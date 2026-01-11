<?php defined('BASEPATH') OR exit('No direct script access allowed');

class HR extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_hr');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);

        // Security & Auth Check
        if ($this->session->userdata('masuk') != TRUE) {
            redirect('Auth');
        }

        $level = $this->session->userdata('level');
        if ($level != 'HR' && $level != 'Admin' && $level != 'Owner') {
            $this->session->set_flashdata('gagal', 'Anda tidak memiliki akses ke modul HR.');
            redirect('Auth');
        }

        // Clear old flashdata to prevent stale messages
        $this->session->unset_userdata('sukses');
        $this->session->unset_userdata('gagal');
    }

    public function index()
    {
        $data['title'] = 'HR Dashboard';
        $today = date('Y-m-d');

        // Stats
        $absensi_today = $this->M_hr->get_absensi_by_date($today);
        $data['stats'] = [
            'hadir' => 0,
            'izin' => 0,
            'telat' => 0,
            'alpa' => 0,
            'avg_kpi' => 0
        ];

        $data['detail_absensi'] = ['TELAT' => [], 'IZIN' => [], 'CUTI' => [], 'ALPA' => [], 'HADIR' => []];

        foreach ($absensi_today as $ab) {
            $s = strtoupper($ab['status']);
            if ($s == 'HADIR')
                $data['stats']['hadir']++;
            elseif ($s == 'IZIN' || $s == 'CUTI')
                $data['stats']['izin']++;
            elseif ($s == 'TELAT')
                $data['stats']['telat']++;
            elseif ($s == 'ALPA')
                $data['stats']['alpa']++;
            elseif ($s == 'SAKIT')
                $data['stats']['izin']++;

            if (isset($data['detail_absensi'][$s])) {
                $data['detail_absensi'][$s][] = $ab['nama_karyawan'];
            }
        }

        // KPI Async
        $kpi_month = $this->M_hr->get_kpi_by_siklus('bulanan', date('Y-m'));
        $total_score = 0;
        $count = 0;
        foreach ($kpi_month as $k) {
            $total_score += $k['rata_rata'];
            $count++;
        }
        $data['stats']['avg_kpi'] = $count > 0 ? number_format($total_score / $count, 2) : 0;
        $data['kpi_data'] = $kpi_month;

        // Chart Data
        $data['chart_absensi'] = json_encode([
            $data['stats']['hadir'],
            $data['stats']['izin'],
            $data['stats']['telat'] + $data['stats']['alpa']
        ]);

        $this->load->view('HR/overview', $data);
    }

    // --- ABSENSI ---

    public function absensi()
    {
        $data['title'] = 'Absensi Karyawan';
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

        $data['selected_date'] = $tanggal;
        $data['absensi_list'] = $this->M_hr->get_absensi_by_date($tanggal);
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

        $this->load->view('HR/absensi', $data);
    }

    public function save_absensi()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan) {
            $this->session->set_flashdata('gagal', 'Karyawan tidak ditemukan');
            redirect('HR/absensi');
        }

        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan->kry_nama,
            'posisi' => $karyawan->kry_level, // Menggunakan kry_level bukan kry_jabatan
            'status' => $this->input->post('status'),
            'jam_masuk' => $this->input->post('jam_masuk'),
            'jam_pulang' => $this->input->post('jam_pulang'),
            'keterangan' => $this->input->post('keterangan')
        ];

        if ($this->M_hr->save_absensi($data)) {
            $this->session->set_flashdata('sukses', 'Absensi berhasil disimpan');
        } else {
            $this->session->set_flashdata('gagal', 'Gagal menyimpan absensi');
        }
        redirect('HR/absensi?tanggal=' . $data['tanggal']);
    }

    public function delete_absensi($id)
    {
        $this->M_hr->delete_absensi($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    // --- KPI ---

    public function kpi()
    {
        $data['title'] = 'KPI Karyawan';
        $siklus = $this->input->get('siklus') ?: 'harian';

        if ($siklus == 'harian')
            $periode = $this->input->get('periode_harian') ?: date('Y-m-d');
        elseif ($siklus == 'mingguan')
            $periode = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
        elseif ($siklus == 'bulanan')
            $periode = $this->input->get('periode_bulanan') ?: date('Y-m');
        else
            $periode = $this->input->get('periode_tahunan') ?: date('Y');

        $data['selected_siklus'] = $siklus;
        $data['selected_periode'] = $periode;
        $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus, $periode);
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

        $this->load->view('HR/kpi', $data);
    }

    public function save_kpi()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        $dis = $this->input->post('kedisiplinan');
        $kua = $this->input->post('kualitas_kerja');
        $prod = $this->input->post('produktivitas');
        $team = $this->input->post('kerja_tim');
        $avg = ($dis + $kua + $prod + $team) / 4;

        $cat = 'Kurang';
        if ($avg >= 4.5)
            $cat = 'Sangat Baik';
        elseif ($avg >= 3.5)
            $cat = 'Baik';
        elseif ($avg >= 2.5)
            $cat = 'Cukup';

        $data = [
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan->kry_nama,
            'posisi' => $karyawan->kry_level, // Menggunakan kry_level bukan kry_jabatan
            'status_kerja' => 'Karyawan',
            'siklus' => 'harian',
            'periode' => $this->input->post('periode_harian'),
            'kedisiplinan' => $dis,
            'kualitas_kerja' => $kua,
            'produktivitas' => $prod,
            'kerja_tim' => $team,
            'total' => ($dis + $kua + $prod + $team),
            'rata_rata' => $avg,
            'kategori' => $cat,
            'catatan' => $this->input->post('catatan')
        ];

        $this->M_hr->save_kpi($data);
        $this->session->set_flashdata('sukses', 'KPI Harian berhasil disimpan');
        redirect('HR/kpi?siklus=harian&periode_harian=' . $data['periode']);
    }

    public function delete_kpi($id)
    {
        $this->M_hr->delete_kpi($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    // --- ARSIP ---

    public function arsip()
    {
        $data['title'] = 'Arsip Dokumen';
        $data['arsip_dreame'] = $this->M_hr->get_arsip('Dreame');
        $data['arsip_laptop'] = $this->M_hr->get_arsip('Laptop');

        $this->load->view('HR/arsip', $data);
    }

    public function add_arsip_dreame()
    {
        $this->_save_arsip('Dreame');
    }
    public function add_arsip_laptop()
    {
        $this->_save_arsip('Laptop');
    }

    private function _save_arsip($tipe)
    {
        $data = [
            'tipe' => $tipe,
            'nama' => $this->input->post('nama'),
            'tanggal' => $this->input->post('tanggal'),
            'no_hp' => $this->input->post('no_hp'),
            'tipe_detail' => $this->input->post('tipe_detail'),
            'kerusakan' => $this->input->post('kerusakan'),
            'alamat' => $this->input->post('alamat')
        ];
        $this->M_hr->save_arsip($data);
        $this->session->set_flashdata('sukses', 'Arsip berhasil disimpan');
        redirect('HR/arsip');
    }

    public function edit_arsip($id)
    {
        $data = [
            'nama' => $this->input->post('nama'),
            'tanggal' => $this->input->post('tanggal'),
            'no_hp' => $this->input->post('no_hp'),
            'tipe_detail' => $this->input->post('tipe_detail'),
            'kerusakan' => $this->input->post('kerusakan'),
            'alamat' => $this->input->post('alamat')
        ];
        $this->M_hr->update_arsip($id, $data);
        $this->session->set_flashdata('sukses', 'Arsip berhasil diupdate');
        redirect('HR/arsip');
    }

    public function delete_arsip($id)
    {
        $this->M_hr->delete_arsip($id);
        redirect('HR/arsip');
    }

    // --- REKAP ---

    public function rekap()
    {
        $data['title'] = 'Rekap HR';
        $siklus_kpi = $this->input->get('siklus_kpi') ?: 'bulanan';
        $periode_kpi = $this->input->get('periode_kpi') ?: date('Y-m');

        $data['selected_periode'] = $periode_kpi;
        $data['selected_siklus'] = $siklus_kpi;
        $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus_kpi, $periode_kpi);
        
        // Get laporan mingguan
        $periode_laporan = $this->input->get('periode_arsip_mingguan') ?: date('Y') . '-W' . date('W');
        $data['laporan_list'] = $this->M_hr->get_laporan_mingguan($periode_laporan);
        
        // Get poin performa
        $periode_performa = $this->input->get('periode_performa') ?: date('Y') . '-W' . date('W');
        $tipe_performa = $this->input->get('tipe_performa') ?: '';
        $data['poin_performa_list'] = $this->M_hr->get_poin_performa_mingguan($periode_performa, $tipe_performa ?: null) ?: [];
        $data['selected_periode_performa'] = $periode_performa;
        $data['selected_tipe_performa'] = $tipe_performa;

        // Get rekap performa bulanan data
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        $tipe = $this->input->get('tipe') ?: '';
        
        $data['selected_bulan'] = $bulan;
        $data['selected_tipe'] = $tipe;
        $data['rekap_karyawan'] = $this->M_hr->get_poin_performa_bulanan($bulan, 'Karyawan') ?: [];
        $data['rekap_magang'] = $this->M_hr->get_poin_performa_bulanan($bulan, 'Magang') ?: [];
        
        // Add ranking
        $all_karyawan = $data['rekap_karyawan'];
        usort($all_karyawan, function($a, $b) {
            return $b['total_poin_bulan'] <=> $a['total_poin_bulan'];
        });
        foreach ($all_karyawan as $key => $k) {
            $data['rekap_karyawan'][$key]['ranking'] = $key + 1;
            if ($k['rata_rata_poin'] >= 80) $data['rekap_karyawan'][$key]['level_performa'] = 'Top Performer';
            elseif ($k['rata_rata_poin'] >= 70) $data['rekap_karyawan'][$key]['level_performa'] = 'High Performer';
            elseif ($k['rata_rata_poin'] >= 60) $data['rekap_karyawan'][$key]['level_performa'] = 'Average';
            else $data['rekap_karyawan'][$key]['level_performa'] = 'Below Average';
        }
        
        $all_magang = $data['rekap_magang'];
        usort($all_magang, function($a, $b) {
            return $b['total_poin_bulan'] <=> $a['total_poin_bulan'];
        });
        foreach ($all_magang as $key => $k) {
            $data['rekap_magang'][$key]['ranking'] = $key + 1;
            if ($k['rata_rata_poin'] >= 90) $data['rekap_magang'][$key]['level_performa'] = 'Top Performer';
            elseif ($k['rata_rata_poin'] >= 75) $data['rekap_magang'][$key]['level_performa'] = 'High Performer';
            elseif ($k['rata_rata_poin'] >= 60) $data['rekap_magang'][$key]['level_performa'] = 'Average';
            else $data['rekap_magang'][$key]['level_performa'] = 'Below Average';
        }

        if (file_exists(APPPATH . 'views/HR/rekap.php')) {
            $this->load->view('HR/rekap', $data);
        } else {
            $this->load->view('HR/overview', $data);
        }
    }

    // --- LAPORAN MINGGUAN ---

    public function laporan_mingguan()
    {
        $data['title'] = 'Laporan Mingguan';
        $periode = $this->input->get('periode') ?: date('Y') . '-W' . date('W');
        
        $data['selected_periode'] = $periode;
        $data['laporan_list'] = $this->M_hr->get_laporan_mingguan($periode);
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

        $this->load->view('HR/laporan_mingguan', $data);
    }

    public function save_laporan_mingguan()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan) {
            $this->session->set_flashdata('gagal', 'Karyawan tidak ditemukan');
            redirect('HR/laporan_mingguan');
        }

        $data = [
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan->kry_nama,
            'posisi' => $karyawan->kry_level,
            'periode' => $this->input->post('periode'),
            'target_mingguan' => $this->input->post('target_mingguan'),
            'tugas_dilakukan' => $this->input->post('tugas_dilakukan'),
            'hasil' => $this->input->post('hasil'),
            'kendala' => $this->input->post('kendala'),
            'solusi' => $this->input->post('solusi')
        ];

        $this->M_hr->save_laporan_mingguan($data);
        $this->session->set_flashdata('sukses', 'Laporan Mingguan berhasil disimpan');
        redirect('HR/laporan_mingguan?periode=' . $data['periode']);
    }

    public function delete_laporan_mingguan($id)
    {
        $this->M_hr->delete_laporan_mingguan($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    // --- EXPORTS ---

    public function export_absensi_csv()
    {
        $per = $this->input->get('periode');
        $tipe = $this->input->get('tipe');
        $data = $this->M_hr->get_absensi_all_by_periode($per, $tipe);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Absensi_' . $per . '.csv"');
        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['Tanggal', 'Nama', 'Jam Masuk', 'Jam Pulang', 'Status', 'Ket']);
        foreach ($data as $d) {
            fputcsv($fp, [$d['tanggal'], $d['nama_karyawan'], $d['jam_masuk'], $d['jam_pulang'], $d['status'], $d['keterangan']]);
        }
        fclose($fp);
    }

    public function export_rekap_csv()
    {
        $periode = $this->input->get('periode');
        $kpi_list = $this->M_hr->get_kpi_by_periode($periode);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Rekap_KPI_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['ID', 'Nama', 'Posisi', 'Disiplin', 'Kualitas', 'Produktivitas', 'Kerja Tim', 'Total', 'Rata-rata', 'Kategori']);
        foreach ($kpi_list as $k) {
            fputcsv($fp, [
                $k['id_karyawan'],
                $k['nama_karyawan'],
                $k['posisi'],
                $k['kedisiplinan'],
                $k['kualitas_kerja'],
                $k['produktivitas'],
                $k['kerja_tim'],
                $k['total'],
                $k['rata_rata'],
                $k['kategori']
            ]);
        }
        fclose($fp);
    }

    public function export_rekap_pdf()
    {
        if (file_exists(FCPATH . 'vendor/autoload.php')) {
            require_once FCPATH . 'vendor/autoload.php';
            
            // Check if this is for KPI or Performa based on parameters
            $bulan = $this->input->get('bulan');
            $periode = $this->input->get('periode');
            $tipe = $this->input->get('tipe') ?: '';
            
            if ($bulan) {
                // PERFORMA REKAP
                $data = $this->M_hr->get_rekap_bulanan($bulan, $tipe ?: null);
                
                $html = '<style>';
                $html .= 'body { font-family: Arial, sans-serif; font-size: 11px; }';
                $html .= 'h2 { font-size: 14px; margin: 10px 0; }';
                $html .= 'table { width: 100%; border-collapse: collapse; margin: 10px 0; }';
                $html .= 'th, td { border: 1px solid #000; padding: 5px; text-align: left; }';
                $html .= 'th { background-color: #ddd; font-weight: bold; }';
                $html .= 'tr:nth-child(even) { background-color: #f9f9f9; }';
                $html .= '.text-center { text-align: center; }';
                $html .= '.text-right { text-align: right; }';
                $html .= '</style>';
                
                $bulan_label = date('F Y', strtotime($bulan . '-01'));
                $html .= '<h2>Rekap Performa Bulanan - ' . $bulan_label;
                if ($tipe) {
                    $html .= ' (' . $tipe . ')';
                }
                $html .= '</h2>';
                
                $html .= '<table>';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th class="text-center">Ranking</th>';
                $html .= '<th>Nama</th>';
                $html .= '<th>Posisi</th>';
                $html .= '<th class="text-center">Tipe</th>';
                $html .= '<th class="text-right">Total Poin</th>';
                $html .= '<th class="text-center">Jumlah Minggu</th>';
                $html .= '<th class="text-right">Rata-rata</th>';
                $html .= '<th>Level Performa</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                
                if (empty($data)) {
                    $html .= '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
                } else {
                    foreach ($data as $d) {
                        $html .= '<tr>';
                        $html .= '<td class="text-center">' . $d['ranking'] . '</td>';
                        $html .= '<td>' . $d['nama_karyawan'] . '</td>';
                        $html .= '<td>' . $d['posisi'] . '</td>';
                        $html .= '<td class="text-center">' . $d['tipe_karyawan'] . '</td>';
                        $html .= '<td class="text-right">' . number_format($d['total_poin_bulan'], 0) . '</td>';
                        $html .= '<td class="text-center">' . $d['jumlah_minggu'] . '</td>';
                        $html .= '<td class="text-right">' . number_format($d['rata_rata_poin'], 2) . '</td>';
                        $html .= '<td>' . $d['level_performa'] . '</td>';
                        $html .= '</tr>';
                    }
                }
                
                $html .= '</tbody>';
                $html .= '</table>';
                
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $dompdf->stream("Rekap_Performa_" . $bulan . ".pdf");
            } else {
                // KPI REKAP
                $periode = $periode ?: date('Y-m');
                $kpi_list = $this->M_hr->get_kpi_by_periode($periode);

                $html = '<h2>Rekap KPI ' . $periode . '</h2><table border="1" cellpadding="5" cellspacing="0" width="100%"><thead><tr><th>Nama</th><th>Posisi</th><th>Nilai</th><th>Kategori</th></tr></thead><tbody>';
                foreach ($kpi_list as $k) {
                    $html .= '<tr><td>' . $k['nama_karyawan'] . '</td><td>' . $k['posisi'] . '</td><td>' . $k['rata_rata'] . '</td><td>' . $k['kategori'] . '</td></tr>';
                }
                $html .= '</tbody></table>';

                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream("Rekap_KPI_" . $periode . ".pdf");
            }
        } else {
            $this->session->set_flashdata('gagal', 'Library Dompdf tidak ditemukan');
            redirect('HR/rekap');
        }
    }

    // --- POIN PERFORMA ---

    public function poin_performa()
    {
        $data['title'] = 'Poin Performa';
        
        // Get periode dari GET parameter, default ke periode minggu ini
        $periode = $this->input->get('periode');
        
        if (!$periode) {
            // Default ke minggu sekarang
            $year = date('Y');
            $week = date('W');
            $periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
        } else {
            // Ensure periode format is correct (YYYY-WXX)
            // Browser might send in format like 2026-W02 or 2026-W2
            if (preg_match('/^(\d{4})-W(\d{1,2})$/', $periode, $matches)) {
                $periode = $matches[1] . '-W' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            } else {
                // If format is wrong, reset to current week
                $year = date('Y');
                $week = date('W');
                $periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
            }
        }
        
        $data['selected_periode'] = $periode;
        $data['selected_tipe'] = $this->input->get('tipe') ?: '';
        $data['selected_date'] = date('Y-m-d');
        
        // Load data
        $data['poin_list'] = $this->M_hr->get_poin_performa_mingguan($data['selected_periode'], $data['selected_tipe'] ?: null) ?: [];
        // Jika data kosong, auto-generate dummy
        if (empty($data['poin_list'])) {
            $periode = $data['selected_periode'];
            $bulan = date('Y-m', strtotime($periode));
            $dummy = [
                ['id_karyawan' => 'K001', 'nama_karyawan' => 'Ahmad Fauzi', 'posisi' => 'Senior Developer', 'tipe_karyawan' => 'Karyawan', 'periode_minggu' => $periode, 'bulan' => $bulan, 'hasil_kerja' => 20, 'pencapaian_target' => 18, 'kualitas_kerja' => 15, 'disiplin' => 12, 'tanggung_jawab' => 10, 'produktivitas_layanan' => 8, 'kepatuhan_sop' => 5, 'minim_komplain' => 4, 'total_poin' => 92, 'catatan' => 'Performa bagus'],
                ['id_karyawan' => 'K002', 'nama_karyawan' => 'Budi Santoso', 'posisi' => 'Developer', 'tipe_karyawan' => 'Karyawan', 'periode_minggu' => $periode, 'bulan' => $bulan, 'hasil_kerja' => 18, 'pencapaian_target' => 16, 'kualitas_kerja' => 14, 'disiplin' => 13, 'tanggung_jawab' => 9, 'produktivitas_layanan' => 8, 'kepatuhan_sop' => 4, 'minim_komplain' => 3, 'total_poin' => 85, 'catatan' => 'Cukup baik'],
                ['id_karyawan' => 'M001', 'nama_karyawan' => 'Siti Nurhaliza', 'posisi' => 'IT Trainee', 'tipe_karyawan' => 'Magang', 'periode_minggu' => $periode, 'bulan' => $bulan, 'proses_belajar' => 22, 'tugas_dijalankan' => 20, 'sikap' => 18, 'kedisiplinan' => 14, 'kepatuhan_sop_magang' => 13, 'total_poin' => 87, 'catatan' => 'Bagus'],
                ['id_karyawan' => 'M002', 'nama_karyawan' => 'Rina Wijaya', 'posisi' => 'Admin Trainee', 'tipe_karyawan' => 'Magang', 'periode_minggu' => $periode, 'bulan' => $bulan, 'proses_belajar' => 20, 'tugas_dijalankan' => 18, 'sikap' => 16, 'kedisiplinan' => 12, 'kepatuhan_sop_magang' => 11, 'total_poin' => 77, 'catatan' => 'Cukup baik']
            ];
            foreach ($dummy as $row) {
                $this->db->insert('poin_performa', $row);
            }
            // After insert, reload data to get poin_id
            $data['poin_list'] = $this->M_hr->get_poin_performa_mingguan($data['selected_periode'], $data['selected_tipe'] ?: null) ?: [];
        }
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db() ?: [];

        $this->load->view('HR/poin_performa', $data);
    }

    public function create_dummy_data()
    {
        // Insert test data langsung
        $periode = '2026-W02';
        $bulan = '2026-01';
        
        $test_data = [
            ['id_karyawan' => 'K001', 'nama_karyawan' => 'Ahmad Fauzi', 'posisi' => 'Senior Developer', 'tipe_karyawan' => 'Karyawan', 'periode_minggu' => $periode, 'bulan' => $bulan, 'hasil_kerja' => 20, 'pencapaian_target' => 18, 'kualitas_kerja' => 15, 'disiplin' => 12, 'tanggung_jawab' => 10, 'produktivitas_layanan' => 8, 'kepatuhan_sop' => 5, 'minim_komplain' => 4, 'total_poin' => 92, 'catatan' => 'Performa bagus'],
            ['id_karyawan' => 'K002', 'nama_karyawan' => 'Budi Santoso', 'posisi' => 'Developer', 'tipe_karyawan' => 'Karyawan', 'periode_minggu' => $periode, 'bulan' => $bulan, 'hasil_kerja' => 18, 'pencapaian_target' => 16, 'kualitas_kerja' => 14, 'disiplin' => 13, 'tanggung_jawab' => 9, 'produktivitas_layanan' => 8, 'kepatuhan_sop' => 4, 'minim_komplain' => 3, 'total_poin' => 85, 'catatan' => 'Cukup baik'],
            ['id_karyawan' => 'M001', 'nama_karyawan' => 'Siti Nurhaliza', 'posisi' => 'IT Trainee', 'tipe_karyawan' => 'Magang', 'periode_minggu' => $periode, 'bulan' => $bulan, 'proses_belajar' => 22, 'tugas_dijalankan' => 20, 'sikap' => 18, 'kedisiplinan' => 14, 'kepatuhan_sop_magang' => 13, 'total_poin' => 87, 'catatan' => 'Bagus'],
            ['id_karyawan' => 'M002', 'nama_karyawan' => 'Rina Wijaya', 'posisi' => 'Admin Trainee', 'tipe_karyawan' => 'Magang', 'periode_minggu' => $periode, 'bulan' => $bulan, 'proses_belajar' => 20, 'tugas_dijalankan' => 18, 'sikap' => 16, 'kedisiplinan' => 12, 'kepatuhan_sop_magang' => 11, 'total_poin' => 77, 'catatan' => 'Cukup baik']
        ];
        
        // Delete old
        $this->db->where('periode_minggu', $periode);
        $this->db->delete('poin_performa');
        
        // Insert new
        $success = 0;
        foreach ($test_data as $data) {
            if ($this->db->insert('poin_performa', $data)) {
                $success++;
            }
        }
        
        redirect('HR/poin_performa');
    }

    public function save_poin_performa()
    {
        $poin_id = $this->input->post('poin_id');
        $id_karyawan = $this->input->post('id_karyawan');
        $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan) {
            $this->session->set_flashdata('gagal', 'Karyawan tidak ditemukan');
            redirect('HR/poin_performa');
        }

        $tipe = $this->input->post('tipe_karyawan');
        $periode = $this->input->post('periode_minggu');
        
        // Extract bulan from periode (YYYY-WXX -> YYYY-MM)
        $year = substr($periode, 0, 4);
        $week = intval(substr($periode, 6));
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $bulan = $dto->format('Y-m');

        if ($tipe == 'Karyawan') {
            $total = $this->input->post('hasil_kerja') + 
                     $this->input->post('pencapaian_target') + 
                     $this->input->post('kualitas_kerja') + 
                     $this->input->post('disiplin') + 
                     $this->input->post('tanggung_jawab') + 
                     $this->input->post('produktivitas_layanan') + 
                     $this->input->post('kepatuhan_sop') + 
                     $this->input->post('minim_komplain');
            
            $data = [
                'id_karyawan' => $id_karyawan,
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'tipe_karyawan' => 'Karyawan',
                'periode_minggu' => $periode,
                'bulan' => $bulan,
                'hasil_kerja' => $this->input->post('hasil_kerja'),
                'pencapaian_target' => $this->input->post('pencapaian_target'),
                'kualitas_kerja' => $this->input->post('kualitas_kerja'),
                'disiplin' => $this->input->post('disiplin'),
                'tanggung_jawab' => $this->input->post('tanggung_jawab'),
                'produktivitas_layanan' => $this->input->post('produktivitas_layanan'),
                'kepatuhan_sop' => $this->input->post('kepatuhan_sop'),
                'minim_komplain' => $this->input->post('minim_komplain'),
                'total_poin' => $total,
                'catatan' => $this->input->post('catatan')
            ];
        } else {
            $total = $this->input->post('proses_belajar') + 
                     $this->input->post('tugas_dijalankan') + 
                     $this->input->post('sikap') + 
                     $this->input->post('kedisiplinan') + 
                     $this->input->post('kepatuhan_sop_magang');
            
            $data = [
                'id_karyawan' => $id_karyawan,
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'tipe_karyawan' => 'Magang',
                'periode_minggu' => $periode,
                'bulan' => $bulan,
                'proses_belajar' => $this->input->post('proses_belajar'),
                'tugas_dijalankan' => $this->input->post('tugas_dijalankan'),
                'sikap' => $this->input->post('sikap'),
                'kedisiplinan' => $this->input->post('kedisiplinan'),
                'kepatuhan_sop_magang' => $this->input->post('kepatuhan_sop_magang'),
                'total_poin' => $total,
                'catatan' => $this->input->post('catatan')
            ];
        }

        // Handle update if poin_id is provided
        if (!empty($poin_id)) {
            $this->db->where('poin_id', $poin_id);
            $this->db->update('poin_performa', $data);
            $this->session->set_flashdata('sukses', 'Poin Performa berhasil diperbarui');
        } else {
            $this->M_hr->save_poin_performa($data);
            $this->session->set_flashdata('sukses', 'Poin Performa berhasil disimpan');
        }
        
        redirect('HR/poin_performa?periode=' . $periode);
    }

    public function delete_poin_performa($id)
    {
        $this->M_hr->delete_poin_performa($id);
        $this->session->set_flashdata('sukses', 'Data Poin Performa berhasil dihapus');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function get_poin_performa($id)
    {
        $poin = $this->M_hr->get_poin_performa_by_id($id);
        
        if ($poin) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $poin
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    // --- REKAP PERFORMA BULANAN ---

    public function rekap_performa()
    {
        $data['title'] = 'Rekap Performa Bulanan';
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        $tipe = $this->input->get('tipe') ?: '';
        
        $data['selected_bulan'] = $bulan;
        $data['selected_tipe'] = $tipe;
        
        // Get rekap data with safety checks
        $rekap_karyawan = $this->M_hr->get_rekap_bulanan($bulan, 'Karyawan');
        $data['rekap_karyawan'] = is_array($rekap_karyawan) ? $rekap_karyawan : [];
        
        $rekap_magang = $this->M_hr->get_rekap_bulanan($bulan, 'Magang');
        $data['rekap_magang'] = is_array($rekap_magang) ? $rekap_magang : [];
        
        // Get chart data with safety checks
        $chart_karyawan = $this->M_hr->get_poin_performa_bulanan($bulan, 'Karyawan');
        $data['chart_karyawan'] = is_array($chart_karyawan) ? $chart_karyawan : [];
        
        $chart_magang = $this->M_hr->get_poin_performa_bulanan($bulan, 'Magang');
        $data['chart_magang'] = is_array($chart_magang) ? $chart_magang : [];
        
        // Get KPI data for comparison with safety check
        $kpi_list = $this->M_hr->get_kpi_by_siklus('bulanan', $bulan);
        $data['kpi_list'] = is_array($kpi_list) ? $kpi_list : [];
        
        // Get laporan mingguan count with safety check
        $year = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);
        if ($this->db->table_exists('laporan_mingguan')) {
            $data['laporan_count'] = $this->db->where('periode LIKE', $year . '-W%')->get('laporan_mingguan')->num_rows();
        } else {
            $data['laporan_count'] = 0;
        }

        $this->load->view('HR/rekap_performa', $data);
    }

    public function generate_rekap()
    {
        $bulan = $this->input->post('bulan') ?: date('Y-m');
        $this->M_hr->generate_rekap_bulanan($bulan);
        $this->session->set_flashdata('sukses', 'Rekap bulanan berhasil di-generate');
        redirect('HR/rekap_performa?bulan=' . $bulan);
    }

    public function export_poin_csv()
    {
        $periode = $this->input->get('periode');
        $tipe = $this->input->get('tipe');
        $data = $this->M_hr->get_poin_performa_mingguan($periode, $tipe ?: null);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Poin_Performa_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        
        if ($tipe == 'Magang') {
            fputcsv($fp, ['ID', 'Nama', 'Posisi', 'Proses Belajar', 'Tugas', 'Sikap', 'Kedisiplinan', 'SOP', 'Total Poin', 'Catatan']);
            foreach ($data as $d) {
                fputcsv($fp, [
                    $d['id_karyawan'], $d['nama_karyawan'], $d['posisi'],
                    $d['proses_belajar'], $d['tugas_dijalankan'], $d['sikap'],
                    $d['kedisiplinan'], $d['kepatuhan_sop_magang'], $d['total_poin'], $d['catatan']
                ]);
            }
        } else {
            fputcsv($fp, ['ID', 'Nama', 'Posisi', 'Hasil Kerja', 'Target', 'Kualitas', 'Disiplin', 'Tanggung Jawab', 'Produktivitas', 'SOP', 'Minim Komplain', 'Total Poin', 'Catatan']);
            foreach ($data as $d) {
                fputcsv($fp, [
                    $d['id_karyawan'], $d['nama_karyawan'], $d['posisi'],
                    $d['hasil_kerja'], $d['pencapaian_target'], $d['kualitas_kerja'],
                    $d['disiplin'], $d['tanggung_jawab'], $d['produktivitas_layanan'],
                    $d['kepatuhan_sop'], $d['minim_komplain'], $d['total_poin'], $d['catatan']
                ]);
            }
        }
        fclose($fp);
    }

    public function export_rekap_performa_csv()
    {
        $bulan = $this->input->get('bulan');
        $tipe = $this->input->get('tipe');
        
        $data = $this->M_hr->get_rekap_bulanan($bulan, $tipe ?: null);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Rekap_Performa_' . $bulan . '.csv"');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['Ranking', 'ID', 'Nama', 'Posisi', 'Tipe', 'Total Poin', 'Jumlah Minggu', 'Rata-rata', 'Level Performa', 'Catatan']);
        
        foreach ($data as $d) {
            fputcsv($fp, [
                $d['ranking'], $d['id_karyawan'], $d['nama_karyawan'], $d['posisi'],
                $d['tipe_karyawan'], $d['total_poin_bulan'], $d['jumlah_minggu'],
                $d['rata_rata_poin'], $d['level_performa'], $d['catatan_evaluasi']
            ]);
        }
        fclose($fp);
    }
}
