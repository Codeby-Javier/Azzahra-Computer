    <!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="LEFT4CODE">
        <title><?= $title; ?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/beck/dist/css/app.css" />
         <!-- sweet alert -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/file/alert/animet.css">        
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="app">
        <!-- BEGIN: Mobile Menu -->

        <!-- END: Mobile Menu -->

        <!-- BEGIN: Top Bar -->
        <div class="border-b border-theme-24 -mt-10 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 pt-3 md:pt-0 mb-10">
            <div class="top-bar-boxed flex items-center">
                <!-- BEGIN: Logo -->
                <a href="#" class="-intro-x hidden md:flex">
                    <img alt="Azzahra Computer Tegal" class="w-6" src="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg">
                    <span class="text-white text-lg ml-3"> Azza<span class="font-medium">hra</span> </span>
                </a>
                <!-- END: Logo -->
                <!-- BEGIN: Breadcrumb -->
                <div class="-intro-x breadcrumb breadcrumb--light mr-auto"> <a href="<?php echo base_url(); ?>assets/template/beck/" class=""><?= $this->session->userdata('nama') ?></a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href="#" class="breadcrumb--active"><?= $title ?></a> </div>
                <!-- END: Breadcrumb -->
                <!-- BEGIN: Notifications -->

                <!-- END: Notifications -->
                <!-- BEGIN: Account Menu -->
                <div class="intro-x dropdown w-8 h-8 relative">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110">
                        <img alt="Midone Tailwind HTML Admin Template" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-9.jpg">
                    </div>
                    <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
                        <div class="dropdown-box__content box bg-theme-38 text-white">
                            <div class="p-4 border-b border-theme-40">
                                <div class="font-medium"><?= $this->session->userdata('nama');?></div>
                                <div class="text-xs text-theme-41"><?= $this->session->userdata('level');?></div>
                            </div>
                            <div class="p-2">
                                <a href="#" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> 
                                    <i data-feather="user" class="w-4 h-4 mr-2"></i> Profile 
                                </a>
                                <a href="<?= site_url('Auth/reset')?>" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> 
                                    <i data-feather="lock" class="w-4 h-4 mr-2"></i> Reset Password 
                                </a>
                            </div>
                            <div class="p-2 border-t border-theme-40">
                                <a href="<?= site_url('Auth/logout')?>" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Account Menu -->
            </div>
        </div>
        <!-- END: Top Bar -->
        <!-- BEGIN: Top Menu -->
        <nav class="top-nav">
            <?php
            if ($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Customer Service') {?>
                <ul>
                    <li>
                        <a href="<?= site_url('Service')?>" class="top-menu <?php if($title == 'Dashboard') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="home"></i> </div>
                            <div class="top-menu__title"> Dashboard </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Service/cos_baru')?>" class="top-menu <?php if($title == 'Customer') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="users"></i> </div>
                            <div class="top-menu__title"> Customer </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Service/laporan')?>" class="top-menu <?php if($title == 'Laporan') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="top-menu__title"> Laporan  </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Order')?>" class="top-menu <?php if($title == 'Order') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="shopping-cart"></i> </div>
                            <div class="top-menu__title"> Order </div>
                        </a>
                    </li>
                </ul>
            <?php } else { ?>
                <ul>
                    <li>
                        <a href="<?= site_url('Kasir')?>" class="top-menu <?php if($title == 'Customer') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="users"></i> </div>
                            <div class="top-menu__title"> Customer </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Kasir/pembayaran')?>" class="top-menu <?php if($title == 'Pembayaran') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="credit-card"></i> </div>
                            <div class="top-menu__title"> Pembayaran </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Kasir/laporan')?>" class="top-menu <?php if($title == 'Laporan') echo 'top-menu--active'?>">
                            <div class="top-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="top-menu__title"> Laporan  </div>
                        </a>
                    </li>
                                        
                </ul>
            <?php } ?>
            
        </nav>
        <!-- END: Top Menu -->
        <!-- BEGIN: Content -->
        