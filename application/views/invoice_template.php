<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
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
            width:100%;
            max-width:884px;
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

        /* HEADER ATAS */
        .header-row{
            position: relative;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            border-bottom:2px solid #e2e6ea;
            padding-bottom:20px;
            margin-bottom:20px;
            flex-wrap: nowrap;
        }
        .brand-box{
           display:flex;
           flex-direction:column;
           align-items:flex-start;
        }

        /* .brand-box{ */
            /* flex: 0 0 auto;      */
            /* max-width: 65%;      */
        /* } */

        .brand-logo{
            width:40%;
            height:50px;
            object-fit:contain;
            margin-bottom:10px;
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
            position: absolute;
            top: 10px;
            right: 0;
            text-align:right;
            font-size:11px;
            white-space:normal;  /* teks di dalamnya boleh turun baris */
        }

        /* SECTION RINGKASAN */
        .summary-row{
            position: relative;
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
            position: absolute;
            top: 23px;
            right: 0;
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
            .invoice-wrapper{box-shadow:none;margin:0;width:100%;max-width:500px;padding:20px 25px;}
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
            

                <div class="brand-sub">
                    Kantor Pusat  : Ruko Citraland Blok B/11, Kraton Tegal<br>
                    Kantor Cabang : Ruko Kranggan Permai RT.16 No.27, Bekasi<br>
                    Telp / WA: 0859 4200 1720 (Tegal)/0818 0387 7771(bekasi)<br>
                    Call Center (0283) 340909
                </div>            
        </div>
         <!-- kolom kanan hanya informasi yang sudah ada -->
        <div class="contact-icons">
            <div>No. Invoice: <strong><?php echo $customer['cos_kode'] . ($dtl_status == 'DP' ? '/DP' : ($dtl_status == 'PELUNASAN' ? '/LUNAS' : '')); ?></strong></div>
            <div>Tanggal: <?php echo $tanggal; ?> &nbsp; <?php echo date('H:i'); ?></div>
             <div class="invoice-badge" style="margin-top: 30px">
                <span>INVOICE <?php echo $dtl_status == 'DP' ? 'DP' : ($dtl_status == 'PELUNASAN' ? 'LUNAS' : ''); ?></span>
            </div>
        </div>
       
    </div>

    <!-- RINGKASAN CUSTOMER -->
    <div class="summary-row">
        <div class="total-box">
            <div class="total-label text-uppercase muted">Customer</div>
            <div class="invoice-customer-name"><?php echo $customer['cos_nama']; ?></div>
            <div class="invoice-customer-meta">
                Alamat: <?php echo $customer['cos_alamat']; ?><br>
                Hp/WA: <?php echo isset($customer['cos_hp']) ? preg_replace('/(\d{2})(\d{4})(\d{4,})/', '$1xx-xxxx-$3', $customer['cos_hp']) : ''; ?>
            </div>
        </div>

        <div class="invoice-badge-box">
           
            <div class="invoice-meta">
                <div>
                    <span class="invoice-meta-label">No</span>
                    <span class="invoice-meta-value">: <?php echo $customer['cos_kode'] . ($dtl_status == 'DP' ? '/DP' : ($dtl_status == 'PELUNASAN' ? '/LUNAS' : '')); ?></span>
                </div>
                <div>
                    <span class="invoice-meta-label">Tanggal</span>
                    <span class="invoice-meta-value">: <?php echo $tanggal; ?></span>
                </div>
                <div>
                    <span class="invoice-meta-label">Jam</span>
                    <span class="invoice-meta-value">: <?php echo date('H:i'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL PEMBAYARAN -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width:10%;">QTY</th>
                <th style="width:40%;">ITEM</th>
                <th style="width:25%;">HARGA</th>
                <th style="width:25%;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($barang as $row):
                $subtotal = ($row['tdkn_qty'] ?? 1) * $row['tdkn_subtot'];
                $total += $subtotal;
            ?>
            <tr>
                <td style="text-align:center"><?php echo $row['tdkn_qty'] ?? 1; ?></td>
                <td><?php echo $row['tdkn_nama']; ?></td>
                <td style="text-align:right"><?php echo number_format($row['tdkn_subtot'], 0, ',', '.'); ?></td>
                <td style="text-align:right"><?php echo number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if ($dp > 0): ?>
            <tr>
                <td colspan="3" style="text-align:right"><strong>DP</strong></td>
                <td style="text-align:right"><strong><?php echo number_format($dp, 0, ',', '.'); ?></strong></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td colspan="3" style="text-align:right"><strong>Total</strong></td>
                <td style="text-align:right"><strong><?php echo number_format($final_total, 0, ',', '.'); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer-row">
        <div class="notice-box">
            <strong>Terima Kasih</strong><br>
            Atas kepercayaan Anda menggunakan jasa kami.
        </div>
        <div class="sign-box">
            <div class="sign-space"></div>
            <div class="sign-name">Azzahra Computer</div>
        </div>
    </div>

</div>

</body>
</html>