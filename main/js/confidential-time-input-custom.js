jQuery(function($) {
	//hide all admin-department select elemnts
	$( $('.admin-select').parent() ).hide();
	$(".confidential-time-entry").trigger('create');
	
	$( ".add-time" )
	  .click(function() {
		var $addtimebtn = $( this );
		var $addtime = $( this ).attr('date');
		var $row_num = $( this ).attr('row-count');
		var $new_row_num = parseInt($row_num) + 1;
		var $date = $( this ).attr('date'); 
		var $day = $( this ).attr('day');
		//$( $addtimebtn.parent() ).append("<div class=\"ui-grid-a\"><div class=\"ui-block-a\"><fieldset data-role=\"controlgroup\" data-type=\"horizontal\" data-mini=\"true\"><input type=\"hidden\" name=\"" + $day + "-date-" + $row_num + "\" id=\"" + $day + "-date-" + $row_num + "\" value=\"" + $date + "\"><input type=\"radio\" name=\"" + $day + "-timetype-" + $row_num + "\" id=\"" + $day + "-radio-choice-c-" + $row_num + "\" value=\"job\" checked=\"checked\" ><label for=\"" + $day + "-radio-choice-c-" + $row_num + "\">JOB</label><input type=\"radio\" name=\"" + $day + "-timetype-" + $row_num + "\" id=\"" + $day + "-radio-choice-d-" + $row_num + "\" value=\"admin\" ><label for=\"" + $day + "-radio-choice-d-" + $row_num + "\">ADMIN</label><input type=\"radio\" name=\"" + $day + "-timetype-" + $row_num + "\" id=\"" + $day + "-radio-choice-e-" + $row_num + "\" value=\"pto\" ><label for=\"" + $day + "-radio-choice-e-" + $row_num + "\">PTO</label></fieldset></div><div class=\"ui-block-b\"><!--<div class=\"ui-bar ui-bar-a\" style=\"height:60px\">Block B</div>--><fieldset class=\"ui-grid-a\"><div class=\"ui-block-a\"><input type=\"text\" type=\"number\" name=\"" + $day + "-jobid-" + $row_num + "\" id=\"" + $day + "-jobid-" + $row_num + "\" value=\"\" placeholder=\"Job ID\"></div><div class=\"ui-block-b\"><input type=\"text\" type=\"number\" name=\"" + $day + "-hours-" + $row_num + "\" id=\"" + $day + "-hours-" + $row_num + "\" value=\"\" placeholder=\"Hours\"></div></fieldset></div></div><!-- /grid-a -->");
		$( $addtimebtn.parent() ).append("<div class=\"ui-grid-a\"><div class=\"ui-block-a\"><fieldset data-role=\"controlgroup\" data-type=\"horizontal\" data-mini=\"true\"><input type=\"hidden\" name=\"" + $day + "-date-" + $row_num + "\" id=\"" + $day + "-date-" + $row_num + "\" value=\"" + $date + "\"><label for=\"" + $day + "-timetype-" + $row_num + "\" class=\"select\">Select Time Type</label><select name=\"" + $day + "-timetype-" + $row_num + "\" id=\"" + $day + "-timetype-" + $row_num + "\" data-native-menu=\"false\" data-icon=\"grid\" data-iconpos=\"left\"><option>Choose a time type</option><optgroup label=\"Standard Types\"><option value=\"job\">Job</option><option value=\"pto\">Paid Time Off</option></optgroup><optgroup label=\"Admin Types\"><option value=\"5\">Shop</option><option value=\"7\">Administrative</option><option value=\"15\">Warehouse</option><option value=\"31\">Excavating</option><option value=\"33\">Foundation</option><option value=\"44\">TX Foundation</option></optgroup></select></fieldset></div><div class=\"ui-block-b\"><!--<div class=\"ui-bar ui-bar-a\" style=\"height:60px\">Block B</div>--><fieldset class=\"ui-grid-a\"><div class=\"ui-block-a\"><input type=\"text\" type=\"number\" name=\"" + $day + "-jobid-" + $row_num + "\" id=\"" + $day + "-jobid-" + $row_num + "\" value=\"\" placeholder=\"Job ID\"></div><div class=\"ui-block-b\"><input type=\"text\" type=\"number\" name=\"" + $day + "-hours-" + $row_num + "\" id=\"" + $day + "-hours-" + $row_num + "\" value=\"\" placeholder=\"Hours\"></div></fieldset></div></div><!-- /grid-a -->");
	
		$(".confidential-time-entry").trigger('create');
		$row_num = $new_row_num;
		$( this ).attr('row-count', $new_row_num );
	  })
	
	$('#mydate').datepicker().change(function() {
		 $date = $('#mydate').val();
		 window.location.assign('?date=' + $date );
	});
	
	admin_hide = function(id) {
		// alert(id);
		 $( $('#' + id).parent() ).hide();
		 $( $('.admin-select').parent() ).show();
		 $(".confidential-time-entry").trigger('create');
		// $( "#admin_department_popup" ).popup( "open" );
	}


});
