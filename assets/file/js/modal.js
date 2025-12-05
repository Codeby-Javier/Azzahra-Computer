$('.modal-setoran').on('click',function(e){
	e.preventDefault();
	var kode 		 = $(this).data('kode');
	var bank 		 = $(this).data('bank');
	var jml_setoran	 = $(this).data('jml_setoran');

	$('#modal-kode').val(kode);
	$('#modal-bank').val(bank);
	$('#modal-jml-tranfer').val(jml_setoran);
	$('#setoran').modal('show');
});