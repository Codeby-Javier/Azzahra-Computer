<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HR extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load HR Model
        $this->load->model('M_hr');

        // Cek Login & Role
        if ($this->session->userdata('masuk') != TRUE) {
            redirect('Auth');
        }

        if ($this->session->userdata('level') != 'HR') {
            // Jika bukan HR, redirect kembali atau show error
            // Tapi khusus admin mungkin boleh akses? Sesuai prompt "Cek role HR"
            if ($this->session->userdata('level') == 'Admin') {
                // Admin allowed typically
            } else {
                $this->session->set_flashdata('gagal', 'Anda tidak memiliki akses ke halaman HR');
                redirect('Auth');
            }
        }
    }

    public function index()
    {
        $data['title'] = 'HR Dashboard';

        // Data Ringkasan untuk Dashboard
        $today = date('Y-m-d');
        $absensi_today = $this->M_hr->get_absensi_by_date($today);

        $hadir = 0;
        $izin = 0;
        $telat = 0;
        $alpa = 0;
        
        foreach ($absensi_today as $ab) {
            $status = strtoupper($ab['status']);
            if ($status == 'HADIR')
                $hadir++;
            elseif ($status == 'IZIN' || $status == 'CUTI')
                $izin++;
            elseif ($status == 'TELAT')
                $telat++;
            elseif ($status == 'ALPA')
                $alpa++;
        }

        // Hitung rata-rata KPI periode ini (BULANAN - dari agregasi harian)
        $periode_ini = date('Y-m');
        $kpi_list = $this->M_hr->get_kpi_by_siklus('bulanan', $periode_ini);
        $total_nilai = 0;
        $count_kpi = 0;
        foreach ($kpi_list as $k) {
            $total_nilai += floatval($k['rata_rata']);
            $count_kpi++;
        }
        $avg_kpi = ($count_kpi > 0) ? number_format($total_nilai / $count_kpi, 2) : 0;

        $data['stats'] = [
            'hadir' => $hadir,
            'izin' => $izin,
            'telat' => $telat,
            'alpa' => $alpa,
            'avg_kpi' => $avg_kpi,
            'interview' => 0
        ];

        // Group names by status
        $detail_absensi = [
            'HADIR' => [],
            'IZIN' => [],
            'CUTI' => [],
            'TELAT' => [],
            'ALPA' => []
        ];

        foreach ($absensi_today as $ab) {
            $st = strtoupper($ab['status']);
            if (isset($detail_absensi[$st])) {
                $detail_absensi[$st][] = $ab['nama_karyawan'];
            } else {
                $detail_absensi[$st] = [$ab['nama_karyawan']];
            }
        }
        $data['detail_absensi'] = $detail_absensi;

        // Data Chart - combine izin and cuti
        $data['chart_absensi'] = json_encode([$hadir, $izin, $telat]);

        $data['kpi_data'] = $kpi_list;

        $this->load->view('HR/overview', $data);
    }

    // --- ABSENSI ---

    public function absensi()
    {
        $data['title'] = 'Absensi Karyawan';

        $tanggal_filter = $this->input->get('tanggal');
        if (empty($tanggal_filter)) {
            $tanggal_filter = date('Y-m-d');
        }

        $data['selected_date'] = $tanggal_filter;
        $data['absensi_list'] = $this->M_hr->get_absensi_by_date($tanggal_filter);
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

        $this->load->view('HR/absensi', $data);
    }

    public function save_absensi()
    {
        $tanggal = $this->input->post('tanggal');
        $id_karyawan = $this->input->post('id_karyawan');
        // Ambil nama & posisi dari drop down value atau hidden input?
        // Sesuai prompt form, kita asumsikan select option value="ID|NAMA|POSISI" atau kita lookup.
        // Cara termudah lookup dari DB atau kirim hidden. Mari kita lookup dari DB Karyawan
        // Tapi method M_hr->save_absensi butuh nama dan posisi.

        $karyawan_db = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan_db) {
            $this->session->set_flashdata('gagal', 'Data karyawan tidak ditemukan');
            redirect('HR/absensi');
        }

        $data = [
            'tanggal' => $tanggal,
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan_db->kry_nama,
            // Asumsi field jabatan/posisi ada di tabel karyawan, jika tidak default 'Staff'
            'posisi' => isset($karyawan_db->kry_jabatan) ? $karyawan_db->kry_jabatan : 'Staff',
            'status' => $this->input->post('status'),
            'jam_masuk' => $this->input->post('jam_masuk'),
            'jam_pulang' => $this->input->post('jam_pulang'),
            'keterangan' => $this->input->post('keterangan')
        ];

        if ($this->M_hr->save_absensi($data)) {
            $this->session->set_flashdata('sukses', 'Data absensi berhasil disimpan');
        } else {
            $this->session->set_flashdata('gagal', 'Gagal menyimpan ke Excel');
        }

        redirect('HR/absensi?tanggal=' . $tanggal);
    }

    // --- KPI ---

    public function kpi()
    {
        $data['title'] = 'KPI Karyawan';

        // Ensure KPI file is properly initialized with correct structure
        $this->M_hr->migrate_kpi_file();

        // Get filter parameters
        $siklus = $this->input->get('siklus') ?: 'harian';
        $periode = '';
        
        // Determine periode based on siklus type
        switch ($siklus) {
            case 'harian':
                $periode = $this->input->get('periode_harian') ?: date('Y-m-d');
                break;
            case 'mingguan':
                $periode = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
                break;
            case 'tahunan':
                $periode = $this->input->get('periode_tahunan') ?: date('Y');
                break;
            case 'bulanan':
            default:
                $periode = $this->input->get('periode_bulanan') ?: date('Y-m');
                break;
        }

        $data['selected_periode'] = $periode;
        $data['selected_siklus'] = $siklus;
        $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus, $periode);
        $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();
        
        // Get laporan mingguan for cards
        if ($siklus === 'mingguan') {
            $data['laporan_mingguan'] = $this->M_hr->get_laporan_mingguan($periode);
        } else {
            $data['laporan_mingguan'] = [];
        }

        $this->load->view('HR/kpi', $data);
    }

    public function save_kpi()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $siklus = $this->input->post('siklus');
        
        // IMPORTANT: Only allow saving daily KPI
        // Weekly, monthly, and yearly are auto-calculated
        if ($siklus !== 'harian') {
            redirect('HR/kpi');
            return;
        }
        
        // Get periode from the correct field
        $periode = $this->input->post('periode_harian');

        $karyawan_db = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan_db) {
            $this->session->set_flashdata('gagal', 'Data Karyawan Error');
            redirect('HR/kpi');
        }

        $disiplin = intval($this->input->post('kedisiplinan'));
        $kualitas = intval($this->input->post('kualitas_kerja'));
        $prod = intval($this->input->post('produktivitas'));
        $team = intval($this->input->post('kerja_tim'));

        $total = $disiplin + $kualitas + $prod + $team;
        $rata = $total / 4;

        $kategori = 'Kurang';
        if ($rata >= 4.5)
            $kategori = 'Sangat Baik';
        elseif ($rata >= 3.5)
            $kategori = 'Baik';
        elseif ($rata >= 2.5)
            $kategori = 'Cukup';

        $data = [
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan_db->kry_nama,
            'posisi' => isset($karyawan_db->kry_jabatan) ? $karyawan_db->kry_jabatan : 'Staff',
            'status_kerja' => 'Karyawan',
            'periode' => $periode,
            'siklus' => 'harian',
            'kedisiplinan' => $disiplin,
            'kualitas_kerja' => $kualitas,
            'produktivitas' => $prod,
            'kerja_tim' => $team,
            'total' => $total,
            'rata_rata' => $rata,
            'kategori' => $kategori,
            'catatan' => $this->input->post('catatan')
        ];

        $this->M_hr->save_kpi($data);
        redirect('HR/kpi');
    }

    // --- LAPORAN MINGGUAN CRUD ---

    public function save_laporan_mingguan()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $periode = $this->input->post('periode'); // Format: 2025-W01

        $karyawan_db = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

        if (!$karyawan_db) {
            $this->session->set_flashdata('gagal', 'Data Karyawan tidak ditemukan');
            redirect('HR/kpi');
            return;
        }

        $data = [
            'id_karyawan' => $id_karyawan,
            'nama_karyawan' => $karyawan_db->kry_nama,
            'posisi' => isset($karyawan_db->kry_jabatan) ? $karyawan_db->kry_jabatan : 'Staff',
            'periode' => $periode,
            'target_mingguan' => $this->input->post('target_mingguan'),
            'tugas_dilakukan' => $this->input->post('tugas_dilakukan'),
            'hasil' => $this->input->post('hasil'),
            'kendala' => $this->input->post('kendala'),
            'solusi' => $this->input->post('solusi')
        ];

        $this->M_hr->save_laporan_mingguan($data);
        redirect('HR/kpi');
    }

    public function edit_laporan_mingguan($id)
    {
        $data = [
            'target_mingguan' => $this->input->post('target_mingguan'),
            'tugas_dilakukan' => $this->input->post('tugas_dilakukan'),
            'hasil' => $this->input->post('hasil'),
            'kendala' => $this->input->post('kendala'),
            'solusi' => $this->input->post('solusi')
        ];

        if ($this->M_hr->update_laporan_mingguan($id, $data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil diubah']);
            } else {
                $this->session->set_flashdata('sukses', 'Laporan berhasil diubah');
                redirect('HR/kpi');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah laporan']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal mengubah laporan');
                redirect('HR/kpi');
            }
        }
    }

    public function delete_laporan_mingguan($id)
    {
        if ($this->M_hr->delete_laporan_mingguan($id)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dihapus']);
            } else {
                $this->session->set_flashdata('sukses', 'Laporan berhasil dihapus');
                redirect('HR/kpi');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus laporan']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menghapus laporan');
                redirect('HR/kpi');
            }
        }
    }

    // --- REKAP ---

    public function rekap()
    {
        $data['title'] = 'Rekap HR';

        // Get filter parameters for KPI
        $siklus_kpi = $this->input->get('siklus_kpi') ?: 'bulanan';
        $periode_kpi = '';
        
        switch ($siklus_kpi) {
            case 'harian':
                $periode_kpi = $this->input->get('periode_harian') ?: date('Y-m-d');
                break;
            case 'mingguan':
                $periode_kpi = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
                break;
            case 'tahunan':
                $periode_kpi = $this->input->get('periode_tahunan') ?: date('Y');
                break;
            case 'bulanan':
            default:
                $periode_kpi = $this->input->get('periode_bulanan') ?: date('Y-m');
                break;
        }

        // Get filter parameters for Arsip Laporan
        $siklus_arsip = $this->input->get('siklus_arsip') ?: 'mingguan';
        $periode_arsip = '';
        
        switch ($siklus_arsip) {
            case 'mingguan':
                $periode_arsip = $this->input->get('periode_arsip_mingguan') ?: date('Y') . '-W' . date('W');
                break;
            case 'bulanan':
                $periode_arsip = $this->input->get('periode_arsip_bulanan') ?: date('Y-m');
                break;
            case 'tahunan':
                $periode_arsip = $this->input->get('periode_arsip_tahunan') ?: date('Y');
                break;
            default:
                $periode_arsip = date('Y') . '-W' . date('W');
                break;
        }

        $data['selected_periode'] = $periode_kpi;
        $data['selected_siklus'] = $siklus_kpi;
        $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus_kpi, $periode_kpi);
        $data['laporan_list'] = $this->M_hr->get_laporan_mingguan_by_periode($periode_arsip, $siklus_arsip);

        $this->load->view('HR/rekap', $data);
    }

    // --- ARSIP ---

    public function arsip()
    {
        $data['title'] = 'Arsip Dokumen';

        // Get filter parameters for Dreame
        $siklus_dreame = $this->input->get('siklus_dreame') ?: 'bulanan';
        $periode_dreame = '';
        switch ($siklus_dreame) {
            case 'harian':
                $periode_dreame = $this->input->get('periode_harian') ?: date('Y-m-d');
                break;
            case 'mingguan':
                $periode_dreame = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
                break;
            case 'tahunan':
                $periode_dreame = $this->input->get('periode_tahunan') ?: date('Y');
                break;
            case 'bulanan':
            default:
                $periode_dreame = $this->input->get('periode_bulanan') ?: date('Y-m');
                break;
        }

        // Get filter parameters for Laptop
        $siklus_laptop = $this->input->get('siklus_laptop') ?: 'bulanan';
        $periode_laptop = '';
        switch ($siklus_laptop) {
            case 'harian':
                $periode_laptop = $this->input->get('periode_harian') ?: date('Y-m-d');
                break;
            case 'mingguan':
                $periode_laptop = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
                break;
            case 'tahunan':
                $periode_laptop = $this->input->get('periode_tahunan') ?: date('Y');
                break;
            case 'bulanan':
            default:
                $periode_laptop = $this->input->get('periode_bulanan') ?: date('Y-m');
                break;
        }

        // Check if filter is applied
        $tipe_filter = $this->input->get('tipe');
        
        if ($tipe_filter === 'Dreame') {
            $data['arsip_dreame'] = $this->M_hr->get_arsip_by_periode('Dreame', $periode_dreame, $siklus_dreame);
            $data['arsip_laptop'] = $this->M_hr->get_arsip('Laptop');
        } elseif ($tipe_filter === 'Laptop') {
            $data['arsip_dreame'] = $this->M_hr->get_arsip('Dreame');
            $data['arsip_laptop'] = $this->M_hr->get_arsip_by_periode('Laptop', $periode_laptop, $siklus_laptop);
        } else {
            $data['arsip_dreame'] = $this->M_hr->get_arsip('Dreame');
            $data['arsip_laptop'] = $this->M_hr->get_arsip('Laptop');
        }

        $this->load->view('HR/arsip', $data);
    }

    // --- EXPORT ---

    public function export_rekap_pdf()
    {
        require_once FCPATH . 'vendor/autoload.php';

        $periode = $this->input->get('periode') ?: date('Y-m');
        $kpi_list = $this->M_hr->get_kpi_by_periode($periode);
        $laporan_list = $this->M_hr->get_laporan_mingguan($periode);

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; font-size: 10px; }
            h2 { color: #333; text-align: center; }
            h3 { color: #666; margin-top: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { background-color: #4CAF50; color: white; padding: 8px; text-align: left; font-size: 9px; }
            td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 9px; }
        </style></head><body>';
        
        $html .= '<h2>Rekap Performa HR</h2>';
        $html .= '<p><strong>Periode:</strong> ' . $periode . ' | <strong>Tanggal Cetak:</strong> ' . date('d/m/Y H:i:s') . '</p>';

        // KPI Section
        $html .= '<h3>Rekap Penilaian Kinerja</h3>';
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Nama</th><th>Posisi</th><th>Disiplin</th><th>Kualitas</th><th>Produktivitas</th><th>Kerja Tim</th><th>Rata-rata</th><th>Kategori</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($kpi_list)) {
            $html .= '<tr><td colspan="8" style="text-align:center;">Tidak ada data KPI</td></tr>';
        } else {
            foreach ($kpi_list as $k) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($k['nama_karyawan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($k['posisi']) . '</td>';
                $html .= '<td>' . number_format($k['kedisiplinan'], 1) . '</td>';
                $html .= '<td>' . number_format($k['kualitas_kerja'], 1) . '</td>';
                $html .= '<td>' . number_format($k['produktivitas'], 1) . '</td>';
                $html .= '<td>' . number_format($k['kerja_tim'], 1) . '</td>';
                $html .= '<td>' . number_format($k['rata_rata'], 2) . '</td>';
                $html .= '<td>' . htmlspecialchars($k['kategori']) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table>';

        // Laporan Mingguan Section
        if (!empty($laporan_list)) {
            $html .= '<h3>Laporan Kinerja Mingguan (Arsip)</h3>';
            $html .= '<table>';
            $html .= '<thead><tr>';
            $html .= '<th>Nama</th><th>Periode</th><th>Target</th><th>Tugas</th><th>Hasil</th><th>Kendala</th><th>Solusi</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($laporan_list as $l) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($l['nama_karyawan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($l['periode']) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['target_mingguan'], 0, 30)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['tugas_dilakukan'], 0, 30)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['hasil'], 0, 30)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['kendala'], 0, 30)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['solusi'], 0, 30)) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        $html .= '</body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Rekap_Performa_HR_' . $periode . '.pdf"');
        echo $dompdf->output();
        exit;
    }

    public function export_rekap_csv()
    {
        $periode = $this->input->get('periode') ?: date('Y-m');
        $kpi_list = $this->M_hr->get_kpi_by_periode($periode);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Rekap_KPI_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($fp, ['ID', 'Nama', 'Posisi', 'Disiplin', 'Kualitas', 'Produktivitas', 'Kerja Tim', 'Total', 'Rata-rata', 'Kategori', 'Catatan']);

        // Data
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
                $k['kategori'],
                $k['catatan']
            ]);
        }

        fclose($fp);
        exit;
    }

    // --- ARSIP CRUD ---

    public function add_arsip_dreame()
    {
        $data = [
            'tipe' => 'Dreame',
            'nama' => $this->input->post('nama'),
            'tanggal' => $this->input->post('tanggal'),
            'no_hp' => $this->input->post('no_hp'),
            'tipe_detail' => $this->input->post('tipe_detail'),
            'kerusakan' => $this->input->post('kerusakan'),
            'alamat' => $this->input->post('alamat')
        ];

        if ($this->M_hr->save_arsip($data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Arsip Dreame berhasil ditambahkan']);
            } else {
                $this->session->set_flashdata('sukses', 'Arsip Dreame berhasil ditambahkan');
                redirect('HR/arsip');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan arsip Dreame']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menambahkan arsip Dreame');
                redirect('HR/arsip');
            }
        }
    }

    public function add_arsip_laptop()
    {
        $data = [
            'tipe' => 'Laptop',
            'nama' => $this->input->post('nama'),
            'tanggal' => $this->input->post('tanggal'),
            'no_hp' => $this->input->post('no_hp'),
            'tipe_detail' => $this->input->post('tipe_detail'),
            'kerusakan' => $this->input->post('kerusakan'),
            'alamat' => $this->input->post('alamat')
        ];

        if ($this->M_hr->save_arsip($data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Arsip Laptop berhasil ditambahkan']);
            } else {
                $this->session->set_flashdata('sukses', 'Arsip Laptop berhasil ditambahkan');
                redirect('HR/arsip');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan arsip Laptop']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menambahkan arsip Laptop');
                redirect('HR/arsip');
            }
        }
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

        if ($this->M_hr->update_arsip($id, $data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Arsip berhasil diubah']);
            } else {
                $this->session->set_flashdata('sukses', 'Arsip berhasil diubah');
                redirect('HR/arsip');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah arsip']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal mengubah arsip');
                redirect('HR/arsip');
            }
        }
    }

    public function delete_arsip($id)
    {
        if ($this->M_hr->delete_arsip($id)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Arsip berhasil dihapus']);
            } else {
                $this->session->set_flashdata('sukses', 'Arsip berhasil dihapus');
                redirect('HR/arsip');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus arsip']);
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menghapus arsip');
                redirect('HR/arsip');
            }
        }
    }

    public function export_arsip($format = 'csv')
    {
        $tipe = $this->input->get('tipe');
        $siklus = $this->input->get('siklus') ?: 'bulanan';
        $periode = $this->input->get('periode') ?: date('Y-m');
        
        if ($tipe) {
            $all_arsip = $this->M_hr->get_arsip_by_periode($tipe, $periode, $siklus);
        } else {
            $arsip_dreame = $this->M_hr->get_arsip('Dreame');
            $arsip_laptop = $this->M_hr->get_arsip('Laptop');
            $all_arsip = array_merge($arsip_dreame, $arsip_laptop);
        }

        if ($format == 'csv') {
            $this->export_arsip_csv($all_arsip, $tipe, $periode);
        } elseif ($format == 'pdf') {
            $this->export_arsip_pdf($all_arsip, $tipe, $periode);
        }
    }

    private function export_arsip_csv($data, $tipe = '', $periode = '')
    {
        $filename = 'Arsip_' . ($tipe ?: 'All') . '_' . ($periode ?: date('Ymd')) . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fp = fopen('php://output', 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($fp, ['Nama', 'Tanggal', 'No HP', 'Tipe', 'Kerusakan', 'Alamat']);

        foreach ($data as $item) {
            fputcsv($fp, [
                $item['nama'],
                $item['tanggal'],
                $item['no_hp'],
                isset($item['tipe_detail']) ? $item['tipe_detail'] : $item['tipe'],
                $item['kerusakan'],
                $item['alamat']
            ]);
        }

        fclose($fp);
        exit;
    }

    private function export_arsip_pdf($data, $tipe = '', $periode = '')
    {
        require_once FCPATH . 'vendor/autoload.php';

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; }
            h2 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #4CAF50; color: white; padding: 10px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
        </style></head><body>';
        
        $html .= '<h2>Laporan Arsip Dokumen' . ($tipe ? ' ' . $tipe : '') . '</h2>';
        $html .= '<p><strong>Periode:</strong> ' . ($periode ?: 'Semua') . ' | <strong>Tanggal Cetak:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Nama</th><th>Tanggal</th><th>No HP</th><th>Tipe</th><th>Kerusakan</th><th>Alamat</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($data)) {
            $html .= '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>';
        } else {
            foreach ($data as $item) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($item['nama']) . '</td>';
                $html .= '<td>' . date('d/m/Y', strtotime($item['tanggal'])) . '</td>';
                $html .= '<td>' . $item['no_hp'] . '</td>';
                $html .= '<td>' . htmlspecialchars(isset($item['tipe_detail']) ? $item['tipe_detail'] : $item['tipe']) . '</td>';
                $html .= '<td>' . htmlspecialchars($item['kerusakan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($item['alamat']) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Arsip_' . ($tipe ?: 'All') . '_' . ($periode ?: date('Ymd')) . '.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $dompdf->output();
        exit;
    }

    // --- ABSENSI EXPORTS ---
    public function export_absensi_csv()
    {
        $periode = $this->input->get('periode') ?: date('Y-m');
        $tipe = $this->input->get('tipe') ?: 'bulanan';
        
        // Get all absensi data for the period
        $absensi_list = $this->M_hr->get_absensi_all_by_periode($periode, $tipe);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Absensi_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($fp, ['Tanggal', 'ID Karyawan', 'Nama', 'Posisi', 'Status', 'Jam Masuk', 'Jam Pulang', 'Keterangan']);

        // Data
        foreach ($absensi_list as $a) {
            fputcsv($fp, [
                $a['tanggal'],
                $a['id_karyawan'],
                $a['nama_karyawan'],
                $a['posisi'],
                $a['status'],
                $a['jam_masuk'],
                $a['jam_pulang'],
                $a['keterangan']
            ]);
        }

        fclose($fp);
        exit;
    }

    public function export_absensi_pdf()
    {
        require_once FCPATH . 'vendor/autoload.php';

        $periode = $this->input->get('periode') ?: date('Y-m');
        $tipe = $this->input->get('tipe') ?: 'bulanan';
        
        // Get all absensi data for the period
        $absensi_list = $this->M_hr->get_absensi_all_by_periode($periode, $tipe);

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; }
            h2 { color: #333; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #4CAF50; color: white; padding: 10px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            tr:hover { background-color: #f5f5f5; }
        </style></head><body>';
        
        $html .= '<h2>Laporan Absensi Karyawan</h2>';
        $html .= '<p><strong>Periode:</strong> ' . $periode . ' (' . ucfirst($tipe) . ')</p>';
        $html .= '<p><strong>Tanggal Cetak:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Tanggal</th><th>Nama</th><th>Posisi</th><th>Status</th><th>Jam Masuk</th><th>Jam Pulang</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($absensi_list)) {
            $html .= '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>';
        } else {
            foreach ($absensi_list as $a) {
                $html .= '<tr>';
                $html .= '<td>' . date('d/m/Y', strtotime($a['tanggal'])) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['nama_karyawan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['posisi']) . '</td>';
                $html .= '<td>' . $a['status'] . '</td>';
                $html .= '<td>' . ($a['jam_masuk'] ?: '-') . '</td>';
                $html .= '<td>' . ($a['jam_pulang'] ?: '-') . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Absensi_' . $periode . '.pdf"');
        echo $dompdf->output();
        exit;
    }

    public function delete_absensi($tanggal, $id_karyawan)
    {
        if ($this->M_hr->delete_absensi($tanggal, $id_karyawan)) {
            $this->session->set_flashdata('sukses', 'Data absensi berhasil dihapus');
        } else {
            $this->session->set_flashdata('gagal', 'Gagal menghapus data absensi');
        }
        redirect('HR/absensi?tanggal=' . $tanggal);
    }

    // Export KPI with filter
    public function export_kpi_pdf()
    {
        require_once FCPATH . 'vendor/autoload.php';

        $siklus = $this->input->get('siklus') ?: 'bulanan';
        $periode = $this->input->get('periode') ?: date('Y-m');
        $kpi_list = $this->M_hr->get_kpi_by_siklus($siklus, $periode);

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; font-size: 10px; }
            h2 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { background-color: #4CAF50; color: white; padding: 8px; text-align: left; font-size: 9px; }
            td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 9px; }
        </style></head><body>';
        
        $html .= '<h2>Rekap Penilaian Kinerja</h2>';
        $html .= '<p><strong>Periode:</strong> ' . $periode . ' (' . ucfirst($siklus) . ') | <strong>Tanggal Cetak:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Nama</th><th>Posisi</th><th>Disiplin</th><th>Kualitas</th><th>Produktivitas</th><th>Kerja Tim</th><th>Total</th><th>Rata-rata</th><th>Kategori</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($kpi_list)) {
            $html .= '<tr><td colspan="9" style="text-align:center;">Tidak ada data KPI</td></tr>';
        } else {
            foreach ($kpi_list as $k) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($k['nama_karyawan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($k['posisi']) . '</td>';
                $html .= '<td>' . number_format($k['kedisiplinan'], 1) . '</td>';
                $html .= '<td>' . number_format($k['kualitas_kerja'], 1) . '</td>';
                $html .= '<td>' . number_format($k['produktivitas'], 1) . '</td>';
                $html .= '<td>' . number_format($k['kerja_tim'], 1) . '</td>';
                $html .= '<td>' . number_format($k['total'], 1) . '</td>';
                $html .= '<td>' . number_format($k['rata_rata'], 2) . '</td>';
                $html .= '<td>' . htmlspecialchars($k['kategori']) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="KPI_' . $periode . '.pdf"');
        echo $dompdf->output();
        exit;
    }

    public function export_kpi_csv()
    {
        $siklus = $this->input->get('siklus') ?: 'bulanan';
        $periode = $this->input->get('periode') ?: date('Y-m');
        $kpi_list = $this->M_hr->get_kpi_by_siklus($siklus, $periode);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="KPI_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
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
        exit;
    }

    // Export Laporan Mingguan with filter
    public function export_laporan_mingguan_pdf()
    {
        require_once FCPATH . 'vendor/autoload.php';

        $siklus = $this->input->get('siklus') ?: 'mingguan';
        $periode = $this->input->get('periode') ?: date('Y') . '-W' . date('W');
        
        $laporan_list = $this->M_hr->get_laporan_mingguan_by_periode($periode, $siklus);

        // Format periode display
        $periode_display = $this->format_periode_display($periode, $siklus);

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; font-size: 9px; }
            h2 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { background-color: #4CAF50; color: white; padding: 8px; text-align: left; font-size: 8px; }
            td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 8px; }
        </style></head><body>';
        
        $html .= '<h2>Laporan Kinerja Mingguan (Arsip)</h2>';
        $html .= '<p><strong>Periode:</strong> ' . $periode_display . ' | <strong>Tanggal Cetak:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Nama</th><th>Periode</th><th>Target</th><th>Tugas</th><th>Hasil</th><th>Kendala</th><th>Solusi</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($laporan_list)) {
            $html .= '<tr><td colspan="7" style="text-align:center;">Tidak ada data</td></tr>';
        } else {
            foreach ($laporan_list as $l) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($l['nama_karyawan']) . '</td>';
                $html .= '<td>' . $this->format_periode_display($l['periode'], 'mingguan') . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['target_mingguan'], 0, 50)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['tugas_dilakukan'], 0, 50)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['hasil'], 0, 50)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['kendala'], 0, 50)) . '</td>';
                $html .= '<td>' . htmlspecialchars(substr($l['solusi'], 0, 50)) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Laporan_Mingguan_' . $periode . '.pdf"');
        echo $dompdf->output();
        exit;
    }

    public function export_laporan_mingguan_csv()
    {
        $siklus = $this->input->get('siklus') ?: 'mingguan';
        $periode = $this->input->get('periode') ?: date('Y') . '-W' . date('W');
        
        $laporan_list = $this->M_hr->get_laporan_mingguan_by_periode($periode, $siklus);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Laporan_Mingguan_' . $periode . '.csv"');

        $fp = fopen('php://output', 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($fp, ['Nama', 'Periode', 'Target', 'Tugas', 'Hasil', 'Kendala', 'Solusi']);

        foreach ($laporan_list as $l) {
            fputcsv($fp, [
                $l['nama_karyawan'],
                $this->format_periode_display($l['periode'], 'mingguan'),
                $l['target_mingguan'],
                $l['tugas_dilakukan'],
                $l['hasil'],
                $l['kendala'],
                $l['solusi']
            ]);
        }

        fclose($fp);
        exit;
    }

    // Helper function to format periode display
    private function format_periode_display($periode, $siklus)
    {
        if ($siklus === 'mingguan' && strpos($periode, '-W') !== false) {
            list($year, $week_part) = explode('-W', $periode);
            $week_num = intval($week_part);
            $start_date = date('Y-m-d', strtotime($year . 'W' . str_pad($week_num, 2, '0', STR_PAD_LEFT)));
            $end_date = date('Y-m-d', strtotime($start_date . ' +6 days'));
            return date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));
        }
        return $periode;
    }
}