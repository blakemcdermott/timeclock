 $( document ).ready(function() {

			var the_date = $( "#date" ).val();
			$("#ui-datepicker-div").css("display","none");
          	$( "#date" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
			var date = $('#date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
			$( "#date" ).val(the_date);
			
			
		
  });