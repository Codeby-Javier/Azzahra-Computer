<!DOCTYPE html>
<html lang="en">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">

    <!-- SEO -->
    <meta name="description" content="Dashboard Admin - Azzahra Computer Tegal">
    <meta name="author" content="LEFT4CODE">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link href="<?= base_url('assets/template/beck/dist/images/logo.svg'); ?>" rel="shortcut icon">

    <!-- Title -->
    <title><?= isset($title) ? html_escape($title) : 'Dashboard'; ?> - Azzahra Computer</title>

    <!-- Preconnect -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- BEGIN: CSS Assets -->
    <link rel="stylesheet" href="<?= base_url('assets/template/beck/dist/css/app.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/file/alert/animet.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css'); ?>">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- JS Pola -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- END: CSS Assets -->
</head>

<body class="app">
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-mark">
                    <img src="<?php echo base_url(); ?>assets/image/logo.png" alt="Logo">
                </div>
                <div class="logo-name">
                    <h1>Azzahra Computer</h1>

                </div>
                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i data-feather="chevron-left"></i>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav-menu">
                <?php $level = $this->session->userdata('level'); ?>
                <?php if ($level == 'Customer Service'): ?>
                    <div class="nav-group">
                        <div class="nav-group-title">Menu</div>
                        <a href="<?= site_url('Service') ?>" class="nav-link <?php if ($title == 'Dashboard')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="home"></i></div>
                                <span class="nav-text">Dashboard</span>
                            </a>
                            <a href="<?= site_url('Service/cos_baru') ?>" class="nav-link <?php if ($title == 'Customer')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="users"></i></div>
                                <span class="nav-text">Customer</span>
                            </a>
                            <a href="<?= site_url('QuickService/cos_baru') ?>" class="nav-link <?php if ($title == 'Quick Service')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="zap"></i></div>
                                <span class="nav-text">Quick Service</span>
                            </a>
                            <a href="<?= site_url('Service/pembayaran') ?>" class="nav-link <?php if ($title == 'Pembayaran')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="credit-card"></i></div>
                                <span class="nav-text">Pembayaran</span>
                            </a>
                            <a href="<?= site_url('Service/laporan') ?>" class="nav-link <?php if ($title == 'Laporan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="activity"></i></div>
                                <span class="nav-text">Laporan</span>
                            </a>
                        </div>

                        <div class="nav-group">
                            <div class="nav-group-title">Management E-Service</div>
                            <a href="<?= site_url('Order') ?>" class="nav-link <?php if ($title == 'Order')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                                <span class="nav-text">Order</span>
                            </a>
                            <a href="<?= site_url('Voucher') ?>" class="nav-link <?php if ($title == 'Voucher Discount')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="percent"></i></div>
                                <span class="nav-text">Voucher Discount</span>
                            </a>
                        </div>

                        <div class="nav-group">
                            <div class="nav-group-title">Keuangan</div>
                            <a href="https://docs.google.com/spreadsheets/d/1cFLqCD1hYNF2qcgHX_YyyS3ADQ0EEXqhmYdlJ48-O5g/edit?pli=1&gid=1141638400#gid=1141638400" target="_blank" class="nav-link <?php if ($title == 'Pencatatan Keuangan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="dollar-sign"></i></div>
                                <span class="nav-text">Pencatatan Keuangan</span>
                                <div class="nav-icon" style="margin-left: auto;"><i data-feather="external-link" class="w-3 h-3"></i></div>
                            </a>
                        </div>
                <?php elseif ($level == 'Kasir'): ?>
                    <div class="nav-group">
                        <div class="nav-group-title">Menu</div>
                        <a href="<?= site_url('Kasir') ?>" class="nav-link <?php if ($title == 'Customer')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="users"></i></div>
                                <span class="nav-text">Customer</span>
                            </a>
                            <a href="<?= site_url('QuickService/cos_baru') ?>" class="nav-link <?php if ($title == 'Quick Service')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="zap"></i></div>
                                <span class="nav-text">Quick Service</span>
                            </a>
                            <a href="<?= site_url('Kasir/pembayaran') ?>" class="nav-link <?php if ($title == 'Pembayaran')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="credit-card"></i></div>
                                <span class="nav-text">Pembayaran</span>
                            </a>
                            <a href="<?= site_url('Kasir/laporan') ?>" class="nav-link <?php if ($title == 'Laporan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="activity"></i></div>
                                <span class="nav-text">Laporan</span>
                            </a>
                        </div>

                        <div class="nav-group">
                            <div class="nav-group-title">Keuangan</div>
                            <a href="https://docs.google.com/spreadsheets/d/1cFLqCD1hYNF2qcgHX_YyyS3ADQ0EEXqhmYdlJ48-O5g/edit?pli=1&gid=1141638400#gid=1141638400" target="_blank" class="nav-link <?php if ($title == 'Pencatatan Keuangan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="dollar-sign"></i></div>
                                <span class="nav-text">Pencatatan Keuangan</span>
                                <div class="nav-icon" style="margin-left: auto;"><i data-feather="external-link" class="w-3 h-3"></i></div>
                            </a>
                        </div>
                <?php elseif ($level == 'HR'): ?>
                    <div class="nav-group">
                        <div class="nav-group-title">Menu HR</div>
                        <a href="<?= site_url('HR') ?>" class="nav-link <?php if ($title == 'HR Dashboard')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="home"></i></div>
                                <span class="nav-text">Overview</span>
                            </a>
                            <a href="<?= site_url('HR/absensi') ?>" class="nav-link <?php if ($title == 'Absensi Karyawan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="clock"></i></div>
                                <span class="nav-text">Absensi</span>
                            </a>
                            <a href="<?= site_url('HR/kpi') ?>" class="nav-link <?php if ($title == 'KPI Karyawan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="bar-chart-2"></i></div>
                                <span class="nav-text">KPI</span>
                            </a>
                            <a href="<?= site_url('HR/laporan_mingguan') ?>" class="nav-link <?php if ($title == 'Laporan Mingguan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="edit-3"></i></div>
                                <span class="nav-text">Laporan Mingguan</span>
                            </a>
                            <a href="<?= site_url('HR/rekap') ?>" class="nav-link <?php if ($title == 'Rekap HR')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Rekap</span>
                            </a>
                            <a href="<?= site_url('HR/arsip') ?>" class="nav-link <?php if ($title == 'Arsip Dokumen')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="archive"></i></div>
                                <span class="nav-text">Arsip</span>
                            </a>
                            <a href="<?= site_url('Mou') ?>" class="nav-link <?php if ($title == 'Mou' || $title == 'Rekap MOU')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Mou</span>
                            </a>
                        </div>
                <?php else: ?>
                    <div class="nav-group">
                        <div class="nav-group-title">Menu</div>
                        <a href="<?= site_url('Admin') ?>" class="nav-link <?php if ($title == 'Dashboard')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="home"></i></div>
                                <span class="nav-text">Dashboard</span>
                            </a>
                            <a href="<?= site_url('Karyawan') ?>" class="nav-link <?php if ($title == 'Data Karyawan')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="user"></i></div>
                                <span class="nav-text">Karyawan</span>
                            </a>
                            <a href="<?= site_url('Customer') ?>" class="nav-link <?php if ($title == 'Customer')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="users"></i></div>
                                <span class="nav-text">Customer</span>
                            </a>
                            <a href="javascript:;" class="nav-link laporan-toggle <?php if ($title == 'Laporan')
                                  echo 'active' ?>" onclick="toggleLaporanDropdown()">
                                <div class="nav-icon"><i data-feather="activity"></i></div>
                                <span class="nav-text">Laporan</span>
                                <div class="dropdown-chevron">
                                    <i data-feather="chevron-up" class="w-4 h-4"></i>
                                </div>
                            </a>
                            <div class="nav-dropdown-menu" id="laporanDropdownMenu">
                                <a href="<?= site_url('Admin/lap_perhari') ?>" class="dropdown-menu-item">
                                <div class="nav-icon"><i data-feather="book-open"></i></div>
                                <span class="nav-text">Harian</span>
                            </a>
                            <a href="<?= site_url('Admin/laporan') ?>" class="dropdown-menu-item">
                                <div class="nav-icon"><i data-feather="book"></i></div>
                                <span class="nav-text">Bulanan</span>
                            </a>
                        </div>
                        </div>

                        <div class="nav-group">
                            <div class="nav-group-title">Transaksi</div>
                            <a href="<?= site_url('Admin/cus_baru') ?>" class="nav-link <?php if ($title == 'Transaksi-Baru')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="user-plus"></i></div>
                                <span class="nav-text">Baru</span>
                            </a>
                            <a href="<?= site_url('Admin/cus_konf_bank') ?>" class="nav-link <?php if ($title == 'Transaksi-Bank Transfer')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="credit-card"></i></div>
                                <span class="nav-text">Bank Transfer</span>
                            </a>
                            <a href="<?= site_url('Admin/cus_proses') ?>" class="nav-link <?php if ($title == 'Transaksi-Proses')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="play-circle"></i></div>
                                <span class="nav-text">Proses</span>
                            </a>

                            <?php
                              $konf = $this->db->where('trans_status', 'Diproses')
                                  ->get('transaksi')->num_rows();
                              ?>
                        <a href="<?= site_url('Admin/cus_konf') ?>" class="nav-link <?php if ($title == 'Transaksi-Konfirmasi')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="bell"></i></div>
                                <span class="nav-text">Konfirmasi</span>
                            <?php if ($konf > 0)
                              echo '<span class="nav-badge">' . $konf . '</span>'; ?>
                        </a>
                        <a href="<?= site_url('Admin/discount') ?>" class="nav-link <?php if ($title == 'Discount')
                              echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="message-square"></i></div>
                                <span class="nav-text">Discount</span>
                            </a>
                        </div>

                        <div class="nav-group">
                            <div class="nav-group-title">Management E-Service</div>
                            <a href="<?= site_url('Produk') ?>" class="nav-link <?php if ($title == 'Produk')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="package"></i></div>
                                <span class="nav-text">Produk</span>
                            </a>
                            <a href="<?= site_url('Order') ?>" class="nav-link <?php if ($title == 'Order')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                                <span class="nav-text">Order</span>
                            </a>
                            <a href="<?= site_url('Voucher') ?>" class="nav-link <?php if ($title == 'Voucher Discount')
                                  echo 'active' ?>">
                                <div class="nav-icon"><i data-feather="percent"></i></div>
                                <span class="nav-text">Voucher Discount</span>
                            </a>
                        </div>
                <?php endif; ?>
            </nav>

            <!-- Footer -->
            <div class="sidebar-footer">
                <div class="user-profile" onclick="toggleUserDropdown()">
                    <div class="user-avatar"><?php echo strtoupper(substr($this->session->userdata('nama'), 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?= $this->session->userdata('nama'); ?></h4>
                        <p><?= $this->session->userdata('level'); ?></p>
                    </div>
                    <div class="dropdown-chevron">
                        <i data-feather="chevron-up" class="w-4 h-4"></i>
                    </div>
                </div>

                <!-- Dropdown Menu -->
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <a href="#" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="user"></i></div>
                        <span class="nav-text">Profile</span>
                    </a>
                    <?php if ($level != 'Customer Service' && $level != 'Kasir'): ?>
                        <a href="<?= site_url('Karyawan') ?>" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="user-plus"></i></div>
                            <span class="nav-text">Add Account</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('Auth/reset') ?>" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="lock"></i></div>
                        <span class="nav-text">Reset Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= site_url('Auth/logout') ?>" class="dropdown-menu-item logout">
                        <div class="nav-icon"><i data-feather="log-out"></i></div>
                        <span class="nav-text">Logout</span>
                    </a>
                </div>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="main-content">
            <!-- BEGIN: Content -->