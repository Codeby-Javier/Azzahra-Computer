<?php
function maskPhone($phone) {
    if (strlen($phone) >= 10) {
        return substr($phone, 0, 2) . 'xx-xxxx-' . substr($phone, -4);
    }
    return $phone;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Azzahra Computer - Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font modern -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            background:#e5e5e5;
            font-family:'Open Sans', Arial, sans-serif;
            font-size:12px;
            color:#444;
            margin:0;
            padding:20px 0;
        }
        .invoice-wrapper{
            width:884px;
            margin:0 auto;
            background:#fff;
            box-shadow:0 0 10px rgba(0,0,0,.08);
            padding:35px 40px 40px;
            box-sizing:border-box;
        }
        h1,h2,h3,h4,h5,h6{
            margin:0;
            font-weight:600;
            letter-spacing:.5px;
        }
        .text-right{text-align:right;}
        .text-center{text-align:center;}
        .text-uppercase{text-transform:uppercase;}
        .muted{color:#999;}
        .primary{color:#1a7dc1;}
        .small{font-size:11px;}

        /* HEADER ATAS */
        .header-row{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            border-bottom:2px solid #e2e6ea;
            padding-bottom:20px;
            margin-bottom:20px;
            flex-wrap: nowrap;
        }
        /*.brand-box{*/
        /*    display:flex;*/
        /*    flex-direction:column;*/
        /*    align-items:flex-start;*/
        /*}*/
        .brand-box{
            flex: 0 0 auto;      /* lebar mengikuti isi/logo, tidak dipaksa melebar */
            max-width: 65%;      /* supaya tidak makan seluruh baris */
        }

        .brand-logo{
            width:60%;
            height:70px;
            object-fit:contain;
            margin-bottom:10px;
            margin-top: -15px;
        }
        .brand-title{
            font-size:22px;
            font-weight:700;
            color:#1a7dc1;
            letter-spacing:1px;
        }
        .brand-sub{
            font-size:11px;
            color:#777;
        }

        .brand-box > div {
            text-align: left;
        }

        .contact-icons{
            flex: 0 0 auto;
            max-width: 35%;      /* kolom kanan pakai maksimal 35% lebar */
            text-align:right;
            font-size:11px;
            white-space:normal;  /* teks di dalamnya boleh turun baris */
        }

        /* SECTION RINGKASAN */
        .summary-row{
            display:flex;
            justify-content:space-between;
            margin-bottom:25px;
        }
        .total-box{
            flex:1.5;
        }
        .total-label{
            font-size:14px;
            letter-spacing:1px;
            margin-bottom:4px;
        }
        .total-value{
            font-size:18px;
            color:#1a7dc1;
            font-weight:700;
            margin-bottom:10px;
        }
        .invoice-to-label{
            font-size:11px;
            letter-spacing:1px;
            color:#999;
        }
        .invoice-customer-name{
            font-size:14px;
            font-weight:700;
            color:#333;
            margin:3px 0 2px;
        }
        .invoice-customer-meta{
            font-size:11px;
            line-height:1.4;
        }

        .invoice-badge-box{
            flex:1;
            text-align:right;
        }
        .invoice-badge{
            display:inline-block;
            background:#1a7dc1;
            color:#fff;
            padding:8px 25px;
            font-size:16px;
            font-weight:700;
            transform:skewX(-10deg);
            margin-bottom:12px;
        }
        .invoice-badge span{
            display:inline-block;
            transform:skewX(10deg);
        }
        .invoice-meta{
            font-size:11px;
            line-height:1.6;
            display:inline-block;
            text-align:left;
        }
        .invoice-meta-label{
            width:65px;
            display:inline-block;
            color:#777;
        }
        .invoice-meta-value{
            font-weight:600;
        }

        /* TABEL UTAMA */
        table.invoice-table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:18px;
            font-size:11px;
        }
        table.invoice-table thead th{
            background:#1a7dc1;
            color:#fff;
            padding:8px 10px;
            font-weight:600;
            border-right:1px solid #e5f1fb;
        }
        table.invoice-table thead th:last-child{
            border-right:none;
        }
        table.invoice-table tbody td{
            padding:8px 10px;
            border-bottom:1px solid #e9ecef;
            background:#f8f9fb;
        }
        table.invoice-table tbody tr:nth-child(even) td{
            background:#f1f4f8;
        }

        .textarea-cell{
            text-align:left;
            vertical-align:top;
        }
        .textarea-cell textarea{
            width:100%;
            height:80px;
            border:none;
            resize:none;
            background:transparent;
            font-size:11px;
            line-height:1.4;
            padding:0;
            outline:none;
        }

        /* FOOTER */
        .footer-row{
            margin-top:25px;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            font-size:11px;
        }
        .notice-box{
            flex:2;
            background:#f5f7fb;
            border-left:4px solid #1a7dc1;
            padding:10px 12px;
            font-style:italic;
        }
        .sign-box{
            flex:1;
            text-align:center;
        }
        .sign-space{
            height:50px;
        }
        .sign-name{
            border-top:1px solid #777;
            display:inline-block;
            padding-top:3px;
            font-size:11px;
            font-weight:600;
        }

        .cut-line{
            border-top:1px dashed #999;
            margin:35px 0 25px;
            position:relative;
            text-align:center;
            font-size:10px;
            color:#aaa;
        }
        .cut-line span{
            background:#fff;
            padding:0 8px;
            position:relative;
            top:-7px;
        }

        .copy-label{
            text-align:right;
            font-size:10px;
            color:#aaa;
            margin-top:-10px;
            margin-bottom:10px;
        }

        @media print{
            body{background:#fff;margin:0;padding:0;}
            .invoice-wrapper{box-shadow:none;margin:0;width:100%;padding:20px 25px;}
        }

        /* Responsive untuk layar kecil */
        @media (max-width: 768px) {
            .invoice-wrapper {
                width: 100%;
                max-width: 884px;
                margin:0 auto;
                font-size: 10px;
            }
            .header-row {
                flex-wrap: wrap;
                gap: 10px;
            }
            .summary-row {
                flex-wrap: wrap;
                gap: 15px;
            }
            .footer-row {
                flex-wrap: wrap;
                gap: 15px;
            }
            .brand-box{
                max-width: 60%;
            }
            
            .brand-title {
                font-size: 18px;
            }
            .brand-logo {
                width: 50%;
                height: 60px;
            }
            .total-value {
                font-size: 14px;
            }
            .invoice-badge {
                font-size: 12px;
                padding: 5px 15px;
            }
            .contact-icons {
                max-width: 40%;
                font-size: 10px;
            }
            .invoice-badge-box {
                font-size: 10px;
            }
            table.invoice-table thead th,
            table.invoice-table tbody td {
                padding: 5px 6px;
                font-size: 9px;
            }
            .textarea-cell textarea {
                height: 80px;
                font-size: 9px;
            }
            .sign-space {
                height: 30px;
            }
            .notice-box {
                font-size: 9px;
            }
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">

    <!-- HEADER -->
    <div class="header-row">
        <div class="brand-box">
            <img src="<?php echo base_url('assets/image/logo_tts.png'); ?>" class="brand-logo" alt="Logo">
            <div>
                
                <div class="brand-sub">
                    Kantor Pusat  : Ruko Citraland Blok B/11, Kraton Tegal<br>
                    Kantor Cabang : Ruko Kranggan Permai RT.16 No.27, Bekasi<br>
                    Telp / WA: 0859 4200 1720 (Tegal)/0818 0387 7771(bekasi)<br>
                    Call Center (0283) 340909
                </div>
            </div>
        </div>

        <!-- kolom kanan hanya informasi yang sudah ada -->
        <div class="contact-icons">
            <div>No. Invoice: <strong><?php echo $data['cos_kode']; ?></strong></div>
            <div>Tanggal: <?php echo $data['cos_tanggal']; ?> &nbsp; <?php echo $data['cos_jam']; ?></div>
        </div>
    </div>

    <!-- RINGKASAN CUSTOMER -->
    <div class="summary-row">
        <div class="total-box">
            <div class="total-label text-uppercase muted">Customer</div>
            <div class="invoice-customer-name"><?php echo $data['cos_nama']; ?></div>
            <div class="invoice-customer-meta">
                Alamat: <?php echo $data['cos_alamat']; ?><br>
                Hp/WA: <?php echo maskPhone($data['cos_hp']); ?>
            </div>
        </div>

        <div class="invoice-badge-box">
            <div class="invoice-meta">
                <div>
                    <span class="invoice-meta-label">No</span>
                    <span class="invoice-meta-value">: <?php echo $data['cos_kode']; ?></span>
                </div>
                <div>
                    <span class="invoice-meta-label">Tanggal</span>
                    <span class="invoice-meta-value">: <?php echo $data['cos_tanggal']; ?></span>
                </div>
                <div>
                    <span class="invoice-meta-label">Jam</span>
                    <span class="invoice-meta-value">: <?php echo $data['cos_jam']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL INFORMASI UNIT -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width:25%;">MEREK</th>
                <th style="width:35%;">TYPE</th>
                <th style="width:20%;">SN</th>
                <th style="width:20%;">KELENGKAPAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $data['cos_tipe']; ?></td>
                <td><?php echo $data['cos_model']; ?></td>
                <td><?php echo $data['cos_no_seri']; ?></td>
                <td><?php echo $data['cos_asesoris']; ?></td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL STATUS/KELUHAN/KETERANGAN -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width:18%;">STATUS</th>
                <th style="width:32%;">KELUHAN</th>
                <th style="width:32%;">KETERANGAN</th>
                <th style="width:18%;">PASSWORD</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-top: 8px;"><?php echo $data['cos_status']; ?></td>
                <td class="textarea-cell">
                    <textarea readonly><?php echo $data['cos_keluhan']; ?></textarea>
                </td>
                <td class="textarea-cell">
                    <textarea readonly><?php echo $data['cos_keterangan']; ?></textarea>
                </td>
                <td style="vertical-align: top; padding-top: 8px;"><?php echo $data['cos_pswd']; ?></td>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER 1 -->
    <div class="footer-row">
        <div class="notice-box">
            <strong>Perhatian:</strong><br>
            KAMI TIDAK BERTANGGUNG JAWAB LAGI DENGAN UNIT INI, APABILA DALAM 1 BULAN SETELAH DI KONFIRMASI UNIT TIDAK DI AMBIL.
        </div>
    </div>

</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- <script>window.print();</script> -->
</body>
</html>

