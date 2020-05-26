<?php
include "source/scripts.php";
include "source/css.php";



?>
<input type="text" id="sercompany"/>

<script>
$("#sercompany").autocomplete({
	minLength: 2,
    delay : 400,
	source: function(request, response) {
		jQuery.ajax({
		   url: 	 "completed_routes.php",
		   data:  {		   		
		   			term : request.term
		   	},
		   dataType: "json",

		   success: function(data) 	{

			 response(data);
		  }	

		})
   },

   select:  function(e, ui) {
		var keyvalue = ui.item.value;
		alert("Customer number is " + keyvalue); 

	}
});
</script>