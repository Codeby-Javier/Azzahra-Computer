
        <!-- END: Content -->
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>         
        <script src="<?php echo base_url(); ?>assets/template/beck/dist/js/app.js"></script>
        <!-- SweetAlert -->
	    <script src="<?php echo base_url();?>assets/file/alert/sweetalert2.all.min.js"></script>
	    <script src="<?php echo base_url();?>assets/file/alert/alertscript.js"></script>        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	    <!-- myjs -->
	    <script src="<?php echo base_url();?>assets/file/js/trans.js"></script>
        <script src="<?php echo base_url();?>assets/file/js/rupiah.js"></script>
        <!-- END: JS Assets-->
        <script type="text/javascript">
            $(function(){
                $('select[name=bank]').on('change',function(){
                    var bank = $(this).children("option:selected").val();
                    var bca = 123456;
                    var mandiri = 9876543 ;
                    if (bank == 'BCA') {
                        $('#reg').text("FERRY JUANDA");
                        $('#noreg').text("0470727705");
                    }else{
                        $('#reg').text("Azzahra Computer");
                        $('#noreg').text("1390023150083");
                    }
                })
            });

            
        </script>
    </body>
</html>