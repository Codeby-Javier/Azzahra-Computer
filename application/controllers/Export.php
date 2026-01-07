<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('M_export','M_admin'));
		$this->load->library("excel");
	}

	public function index()
	{
		$object = new PHPExcel();
		$style_col = array( 
         'font' 		=> array('bold' => true),     
         'alignment' 	=> array(        
         'horizontal' 	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,        
         'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER ),      
         'borders' 		=> array(        
         					'top'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),       
         					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),        
         					'bottom'=> array('style'  => PHPExcel_Style_Border::BORDER_THIN),       
         					'left' 	=> array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
            				)    
        );
        $style_row = array(      
            'alignment' => array(        
            				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER ),      
            'borders'   => array(        
            				'top'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN),        
            				'right'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN),       
            				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),        
            				'left'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            				)    
        );
        $object->setActiveSheetIndex(0);
        $table_columns = array("TTS","NAMA CUSTOMER","ALAMAT","NO HP","MODEL UNIT","STATUS UNIT","TANGGAL","JAM","SETATUS TRANSAKSI");

        $column = 0;
        foreach($table_columns as $field){

        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

        $column++;

      	}
      	$customer = $this->M_export->getCustomer()->result_array();
      	$excel_row = 2;
      	$ind = 62;
      	foreach($customer as $row){
      	    
      	    $data1 = substr($row['cos_hp'], 1,15);
      	    $nohp = $ind.$data1;

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['cos_kode']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['cos_nama']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['cos_alamat']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $nohp );
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['cos_model']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['cos_status']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['cos_tanggal']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['cos_jam']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row['trans_status']);

        $excel_row++;

      	}
      	$object->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);
	    $object->getActiveSheet()->getStyle('I1')->applyFromArray($style_col);

	    $object->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	    $object->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

	    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
	    header('Content-Type: application/vnd.ms-excel');
      	header('Content-Disposition: attachment;filename="DATA CUSTOMER.xls"');
      	$object_writer->save('php://output');


	}
	function lap_excel()
	  {
	    $object = new PHPExcel();
	    $style_col = array(
	         'font'     => array('bold' => true),
	         'alignment'  => array(
	         'horizontal'   => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	         'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER ),
	         'borders'    => array(
	                  'top'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'bottom'=> array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'left'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
	                    )
	        );
	        $style_row = array(
	            'alignment' => array(
	                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER ),
	            'borders'   => array(
	                    'top'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'right'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'left'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
	                    )
	        );
	        $object->setActiveSheetIndex(0);
	        $table_columns = array("DESCRIPTION","JUMLAH","SUBTOTAL");

	        $column = 0;
	        foreach($table_columns as $field){

	        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

	        $column++;

	        }

	        $tgl_awal  = $this->input->post('tgl_1');
	        $tgl_akhir = $this->input->post('tgl_2');

	        $jml_DP_bca = $this->M_admin->jml_DP_bca($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_bca = $this->M_admin->tot_DP_bca($tgl_awal,$tgl_akhir);
	        $jml_DP_bri = $this->M_admin->jml_DP_bri($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_bri = $this->M_admin->tot_DP_bri($tgl_awal,$tgl_akhir);
	        $jml_DP_tunai = $this->M_admin->jml_DP_tunai($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_tunai = $this->M_admin->tot_DP_tunai($tgl_awal,$tgl_akhir);
	        $jml_lns_tunai = $this->M_admin->jml_Lunas_tunai($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_lns_tunai = $this->M_admin->tot_Lunas_tunai($tgl_awal,$tgl_akhir);

	        // Get additional data for complete report
	        $jml_tranfer = $this->M_admin->jml_tranfer($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_tranfer = $this->M_admin->tot_tranfer($tgl_awal,$tgl_akhir);
	        $jml_setor = $this->M_admin->jml_setor($tgl_awal,$tgl_akhir)->num_rows();
	        $blm_setor = $this->M_admin->blm_setor($tgl_awal,$tgl_akhir);
	        $jml_tunai = $this->M_admin->jml_tunai($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_tunai = $this->M_admin->tot_tunai($tgl_awal,$tgl_akhir);

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'DOWN PATMENT BANK BCA');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $jml_DP_bca);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 2, "Rp. ".number_format($tot_DP_bca, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DOWN PATMENT BANK BRI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $jml_DP_bri);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 3, "Rp. ".number_format($tot_DP_bri, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'DOWN PATMENT TUNAI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $jml_DP_tunai);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 4, "Rp. ".number_format($tot_DP_tunai, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'PELUNASAN TUNAI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, $jml_lns_tunai);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "Rp. ".number_format($tot_lns_tunai, 0).",-");

	        // Add empty row for spacing
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 6, '');

	        // Bank Transfer Down Payment section
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 7, 'BANK TRANSFER DOWN PAYMENT');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 7, $jml_tranfer);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 7, "Rp. ".number_format($tot_tranfer, 0).",-");

	        // Add empty row for spacing
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 8, '');

	        // Bank Transfer Yang Belum Disetorkan section
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 9, 'BANK TRANSFER YANG BELUM DISETORKAN');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 9, $jml_setor);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 9, "Rp. ".number_format($blm_setor, 0).",-");

	        // Add empty row for spacing
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 10, '');

	        // Pembayaran Tunai DP dan Pelunasan section
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 11, 'PEMBAYARAN TUNAI DP DAN PELUNASAN');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 11, $jml_tunai);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 11, "Rp. ".number_format($tot_tunai, 0).",-");

	        // Add period information at the end
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 13, 'Periode: '.$tgl_awal.' - '.$tgl_akhir);

	        $object->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
	        $object->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
	        $object->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);

	        $object->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	        $object->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	        $object->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

	        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
	        header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="DATA LAPORAN.xls"');
	        $object_writer->save('php://output');
	  }

	  function lap_perhari_excel()
	  {
	    $object = new PHPExcel();
	    $style_col = array(
	         'font'     => array('bold' => true),
	         'alignment'  => array(
	         'horizontal'   => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	         'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER ),
	         'borders'    => array(
	                  'top'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'bottom'=> array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                  'left'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
	                    )
	        );
	        $style_row = array(
	            'alignment' => array(
	                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER ),
	            'borders'   => array(
	                    'top'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'right'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
	                    'left'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
	                    )
	        );
	        $object->setActiveSheetIndex(0);
	        $table_columns = array("DESCRIPTION","JUMLAH","SUBTOTAL");

	        $column = 0;
	        foreach($table_columns as $field){

	        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

	        $column++;

	        }

	        $tgl_awal  = date('Y-m-d');
	        $tgl_akhir = date('Y-m-d');

	        $jml_DP_bca = $this->M_admin->jml_DP_bca($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_bca = $this->M_admin->tot_DP_bca($tgl_awal,$tgl_akhir);
	        $jml_DP_bri = $this->M_admin->jml_DP_bri($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_bri = $this->M_admin->tot_DP_bri($tgl_awal,$tgl_akhir);
	        $jml_DP_tunai = $this->M_admin->jml_DP_tunai($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_DP_tunai = $this->M_admin->tot_DP_tunai($tgl_awal,$tgl_akhir);
	        $jml_lns_tunai = $this->M_admin->jml_Lunas_tunai($tgl_awal,$tgl_akhir)->num_rows();
	        $tot_lns_tunai = $this->M_admin->tot_Lunas_tunai($tgl_awal,$tgl_akhir);

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'DOWN PAYMENT BANK BCA');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $jml_DP_bca);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 2, "Rp. ".number_format($tot_DP_bca, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DOWN PAYMENT BANK MANDIRI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $jml_DP_bri);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 3, "Rp. ".number_format($tot_DP_bri, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'DOWN PAYMENT TUNAI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $jml_DP_tunai);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 4, "Rp. ".number_format($tot_DP_tunai, 0).",-");

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'PELUNASAN TUNAI');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, $jml_lns_tunai);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "Rp. ".number_format($tot_lns_tunai, 0).",-");

	        // Add empty row for spacing
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 6, '');

	        // Bank Transfer Down Payment section
	        $total_bank_transfer = $tot_DP_bca + $tot_DP_bri;
	        $total_bank_count = $jml_DP_bca + $jml_DP_bri;
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 7, 'BANK TRANSFER DOWN PAYMENT');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 7, $total_bank_count);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 7, "Rp. ".number_format($total_bank_transfer, 0).",-");

	        // Add empty row for spacing
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 8, '');

	        // Pembayaran Tunai DP dan Pelunasan section
	        $total_cash = $tot_DP_tunai + $tot_lns_tunai;
	        $total_cash_count = $jml_DP_tunai + $jml_lns_tunai;
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 9, 'PEMBAYARAN TUNAI DP DAN PELUNASAN');
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 9, $total_cash_count);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 9, "Rp. ".number_format($total_cash, 0).",-");

	        // Add period information at the end
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 11, 'Laporan Hari Ini: '.date('d-F-Y'));

	        $object->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
	        $object->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
	        $object->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);

	        $object->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	        $object->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	        $object->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

	        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
	        header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="LAPORAN_HARI_INI_'.date('d-m-Y').'.xls"');
	        $object_writer->save('php://output');
	  }

}

/* End of file Export.php */
/* Location: ./application/controllers/Export.php */