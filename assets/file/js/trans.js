$("#tbl-add").click(function(){

	if ($("#qty").val() > 0) {
		var no 		 = $("#tbl-trans tbody tr").length + 1;
		var tindakan = $("#tindakan").val();
		var qty 	 = $("#qty").val();
		var ket 	 = $("#area").val();
		var row = "<tr>";

			row += "<td>"+no+"</td>";

			row += "<td>";
		    row += "<input type='hidden' name='no[]' value='"+no+"' >";
		    row += "<input type='hidden' name='tindakan[]' value='"+tindakan+"'>";
		    row += "<span>"+tindakan+"</span>";
		    row += "</td>";

		    row += "<td>";
		    row += "<input type='hidden' name='qty[]' value='"+qty+"'>";
		    row += "<span>"+qty+"</span>";
		    row += "</td>";

		    row += "<td>";
		    row += "<input type='hidden' name='ket[]' value='"+ket+"'>";
		    row += "<span>"+ket+"</span>";
		    row += "</td>";

		    row += "<td>";
		    row += "<input type='hidden' name='subtot[]' value='0'>";
		    row += "<span>0</span>";
		    row += "</td>";

	    $("#tbl").append(row);

	    $("#tindakan").val('');
		$("#area").val('');
	}else{
		Swal.fire({
	      type: 'error',
	      title: 'Oops...',
	      text: 'QUANTITY BELUM DI ISI !!!'
	    });
	}	

});