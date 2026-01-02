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
            $periode = $this->input->get('periode') ?: date('Y-m');
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
        } else {
            $this->session->set_flashdata('gagal', 'Library Dompdf tidak ditemukan');
            redirect('HR/kpi');
        }
    }
}