<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="LEFT4CODE">
        <title>Reset - password</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/beck/dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Register Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="#" class="-intro-x flex items-center pt-5">
                        <img alt="Azzahra Computer Tegal" class="w-6" src="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg">
                        <span class="text-white text-lg ml-3"> Azza<span class="font-medium">hra</span> </span>
                    </a>
                    <div class="my-auto">
                        <img alt="Azzahra Computer Tegal" class="-intro-x w-1/2 -mt-16" src="<?php echo base_url(); ?>assets/template/beck/dist/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Demi keamanan account anda 
                            <br>
                            rubahlah password account anda secara berkala.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white">Sistem manajemen informasi azzahra computer tegal</div>
                    </div>
                </div>
                <!-- END: Register Info -->
                <!-- BEGIN: Register Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Reset Password
                        </h2>
                        <form method="post" action="<?= site_url('Auth/change_pswd')?>">
                            <div class="intro-x mt-8">
                                <?= form_error('username','<small class="text-theme-1">','</small>')?>
                                <input type="text" class="intro-x login__input input input--lg border border-gray-300 block" name="username" placeholder="Username" value="<?= set_value('username');?>">
                                <?= form_error('pswd','<small class="text-theme-1">','</small>')?>
                                <input type="text" class="intro-x login__input input input--lg border border-gray-300 block mt-4" name="pswd" placeholder="Password" value="<?= set_value('username');?>">
                                <?= form_error('con_pswd','<small class="text-theme-1">','</small>')?>
                                <input type="text" class="intro-x login__input input input--lg border border-gray-300 block mt-4" name="con_pswd" placeholder="Password Confirmation" value="<?= set_value('username');?>">
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button type="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3">Change </button>
                                <a href="<?= site_url('Auth')?>" class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 mt-3 xl:mt-0">
                                    Login
                                </a>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <!-- END: Register Form -->
            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="<?php echo base_url(); ?>assets/template/beck/dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>