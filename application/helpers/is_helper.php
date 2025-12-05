<?php

date_default_timezone_set('Asia/Jakarta');

function karyawan()
{
    $hri = date('d');
    $bln = date("m");
    $thn = date("y");
    $cari = $hri.$bln.$thn;
    $baru = $cari.'01';
    $CI =& get_instance();
    $cek = $CI->db->query("SELECT kry_kode FROM karyawan WHERE kry_kode LIKE '%$cari%' order by kry_kode DESC");
    if ($cek->num_rows()>0){
        $data =$cek->row_array();
        $kd = $data['kry_kode'];
        $ambil = substr($kd, 6,2)+1;
        $newCode = $cari.sprintf("%02s",$ambil);
        return $newCode;
    }else{
        return $baru;
    }
}

function customer()
{
	$hri  = date('d');
	$bln  = date("m");
    $thn  = date("y");
    $cari = 'TTS'.$thn.$bln.$hri;
    $baru = $cari.'001';
    $CI   =& get_instance();
    $cek  = $CI->db->query("SELECT id_costomer FROM costomer WHERE id_costomer LIKE '%$cari%' order by id_costomer DESC");
    if ($cek->num_rows()>0){
    	$data    = $cek->row_array();
    	$kd      = $data['id_costomer'];
    	$ambil   = substr($kd, 9,3)+1;
    	$newCode = $cari.sprintf("%03s",$ambil);
    	return $newCode;
    }else{
    	return $baru;
    }
}


	function tgl_indo($tgl){
	    
	   
	    $tanggal = substr($tgl,8,2);
	    $bulan = substr($tgl, 5,2);
	    $tahun = substr($tgl, 0,4);

	    return $tanggal."-".$bulan."-".$tahun;
	    
	    
	}

	function UploadFoto($file,$dst){
		
		$date = date("Y-m-m_H-i-s");
		
		//Penjabaran File
		$filename 	= $file['name'];
		$filetype 	= $file['type'];
		$filetmp 	= $file['tmp_name'];

		$fileupload = $dst.$filename;
		//upload ukuran sebenarnya
		move_uploaded_file($filetmp, $fileupload);
		
		//Identifikasi Gambar
		if ($filetype == 'image/jpeg' || $filetype == 'image/jpg') {
			$src 	= imagecreatefromjpeg($fileupload);		
		}elseif ($filetype == 'image/png') {
			$src 	= imagecreatefrompng($fileupload);
		}

			$wsrc = imageSX($src);
			$hsrc = imageSY($src);

			//Set Ukuran Gambar
			$wdst = 360;
			$hdst = ($wdst / $wsrc) * $hsrc;

			//Proses Perubahan Ukuran
			$filecreate = imagecreatetruecolor($wdst, $hdst);
			imagecopyresampled($filecreate, $src, 0, 0, 0, 0, $wdst, $hdst, $wsrc, $hsrc);

			//Nama Acak
			$x 			= explode(".", $filename);
			$name 		= $x[0];
			$extension 	= $x[1];
			$filename 	= $date.'.'.$extension;
			
		//Reupload
		if ($filetype == 'image/jpeg' || $filetype == 'image/jpg') {
			imagejpeg($filecreate,$dst.$filename);
		}elseif ($filetype == 'image/png') {
			imagepng($filecreate,$dst.$filename);
		}

			//Hapus Foto Lama
			unlink($fileupload);

		return $filename;
	}

    function promo(){

        $kode = 'PRM';
        $sup  = $kode.'001';
        $CI =& get_instance();
        $cek = $CI->db->query("SELECT promo_kode FROM promo WHERE promo_kode LIKE '%$kode%' order by promo_kode DESC");
        if ($cek->num_rows()>0){
            $cek =$cek->row_array();
            $lastCode = $cek['promo_kode'];
            $ambil = substr($lastCode, 3,3)+1;
            $newCode = $kode.sprintf("%03s",$ambil);
            return $newCode;
        }else{
            return $sup;
        }
    }

?>