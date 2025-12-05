$('#lokasi').on('click',function(){

	if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        view.innerHTML = "Yah browsernya ngga support Geolocation bro!";
    }
	
	function showPosition(position) {
      var latitut = position.coords.latitude;
      var longitut = position.coords.longitude;
      $("#la").val(latitut);
      $("#latitute").val(latitut);
      $("#lo").val(longitut);
       $("#longitute").val(longitut);
    }
    
});