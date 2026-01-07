<!DOCTYPE html>
	<marquee class="warna" width="1400" style="padding: 0px; margin-bottom: 0%; margin: 0,6%;"><h1><strong>Tidak perlu Hebat untuk memulai, tetapi kamu perlu memulai untuk menjadi Hebat. <br> ~Berikan Etos Kerja Terbaik dan Pelayanan Prima kepada seluruh Customer~ </h1></strong></marquee>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="LEFT4CODE">
        <title>Login -</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/beck/dist/css/app.css" />
        <!-- sweet alert -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/file/alert/animet.css">
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="#" class="-intro-x flex items-center pt-5">
                        <img alt="Azzahra Computer Tegal" class="w-6" src="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg">
                        <span class="text-white text-lg ml-3"> Azz<span class="font-medium">ahra</span> </span>
                    </a>
                    <div class="my-auto">
                        <img alt="Azzahra Computer Tegal" class="-intro-x w-1/2 -mt-16" src="<?php echo base_url(); ?>assets/template/beck/dist/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Selamat datang kembali
                            <br>
                            silahkan masukan akun anda.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white">Sistem manajemen informasi azzahra computer tegal</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="gagal" data-gagal="<?php echo $this->session->flashdata('gagal');?>"></div>
                    <div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">Selamat datang kembali silahkan masukan akun anda</div>
                        <form method="post" action="<?= site_url('Auth/login')?>">
                            <div class="intro-x mt-8">
                                <input type="text" class="intro-x login__input input input--lg border border-gray-300 block" name="username" placeholder="Username">
                                <input type="password" name="pswd" class="intro-x login__input input input--lg border border-gray-300 block mt-4" placeholder="Password">
                            </div>
                            <div class="intro-x flex text-gray-700 text-xs sm:text-sm mt-4">
                                <a href="javascript:;">Forgot Password?</a> 
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button type="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3">Login</button>
                            </div>
                        </form>
                        
                        <div class="intro-x mt-10 xl:mt-24 text-gray-700 text-center xl:text-left">
                            Jika anda belum mempuyai akun 
                            <br>
                            <a class="text-theme-1" href="<?php echo base_url(); ?>assets/template/beck/">Silahkan hubungi administrator</a>  
                        </div>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="<?php echo base_url(); ?>assets/template/beck/dist/js/app.js"></script>
        <!-- SweetAlert -->
        <script src="<?php echo base_url();?>assets/file/alert/sweetalert2.all.min.js"></script>
        <script src="<?php echo base_url();?>assets/file/alert/alertscript.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>