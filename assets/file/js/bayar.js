function rp(bil)
{
var rev     = parseInt(bil, 10).toString().split('').reverse().join('');
var rev2    = '';
  for(var i = 0; i < rev.length; i++){
      rev2  += rev[i];
      if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
          rev2 += '.';
      }
  }
  return 'Rp. ' + rev2.split('').reverse().join('');
}

$(document).ready(function(){
    TotalBayar();
});
function TotalBayar(){

  var harga    = $("#bulanan").val();
  var denda    = $("#denda").val();
  var discount = $("#discount").val();

  var TotalBayar = parseInt(harga) + parseInt(denda) - parseInt(discount);
  $("#total_bayar").val(rp(TotalBayar));
  $("#total_bayar_hidd").val(TotalBayar);
}

$("#uang").on('keyup',function(){
	var uang = $("#uang").val();
	var harga = $("#hrg_cash").val();
	var tot = (uang - harga);

	$("#uang_kembali").val(rp(tot));
	$("#uang_balik").val(tot);
});



$("#kredit").on('keyup',function(){
  var uang     = $("#kredit").val();
  var harga    = $("#bulanan").val();
  var denda    = $("#denda").val();
  var discount = $("#discount").val();

  var total = parseInt(uang) - parseInt(harga) - parseInt(denda) + parseInt(discount);

  $("#uang_kembali").val(rp(total));
  $("#uang_balik").val(total);

  HitungSetoran();
});

function HitungSetoran(){

  var uang_bayar = $("#kredit").val();
  var harga      = $("#bulanan").val();

  var total = parseInt(uang_bayar) - parseInt(harga);
  if (parseInt(uang_bayar) < parseInt(harga) ) {
    $("#kurang").val(total * -1);
    $("#saldo_setor").val(total * -1);
    $("#krg_setor").val('0');
  } else {
    $("#kurang").val('Rp. 0');
    $("#saldo_setor").val('0');
    $("#krg_setor").val(total);
  }
  HitungDenda();

}
function HitungDenda(){

  var sisa_bayar = $("#krg_setor").val();
  var denda      = $("#denda").val();

  var total = parseInt(sisa_bayar) - parseInt(denda);
  if (parseInt(sisa_bayar) < parseInt(denda) ) {
    $("#bayar_denda").val(rp(denda));
    $("#sisa_denda").val(denda);
    $("#saldo_denda").val(denda);
  } else {
    $("#bayar_denda").val('Rp. 0');
    $("#saldo_denda").val('0');
    $("#sisa_denda").val(total);
  }
  HitungSisaBayar();
}
function HitungSisaBayar(){

  var uang_bayar = $("#kredit").val();
  var harga      = $("#bulanan").val();
  var sisa       = $("#sisa_denda").val();

  var total = parseInt(uang_bayar) - parseInt(harga);
  if (parseInt(uang_bayar) < parseInt(harga) ) {
    $("#uang_kembali").val('Rp. 0');
    $("#uang_balik").val('0');
  } else {
    $("#uang_kembali").val(rp(sisa));
    $("#uang_balik").val(sisa);
  }

}

  
   



  
