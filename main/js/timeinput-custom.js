	var recordname;
	var added = new Array();
	var selectedemployees = 0;
	var add_inventory_quantity_pop_id = null;
	var add_inventory_quantity_pop_run = false;
	var is_punchin = false;	
	// These vars can be assigned on load of time-input.php
	if (!checkboxcount) var checkboxcount = 0;
	if (!count) var count = 0;
	if (!recordcount) var recordcount = 0;
	
jQuery(function($) {
		
	$("#job_id").focus(function(){
		if( $("#job_id").val() == 'Job ID' ) {
		$("#job_id").val('')
		}
	});
	$("#job_id").blur(function(){
		if( $("#job_id").val() == '' ) {
		$("#job_id").val('Job ID')
		}
	});
	$("#mileage").focus(function(){
		if( $("#mileage").val() == 'Mileage' ) {
		$("#mileage").val('')
		}
	});
	$("#mileage").blur(function(){
		if( $("#mileage").val() == '' ) {
		$("#mileage").val('Mileage')
		}
	});
	$("#traveltime").focus(function(){
		if( $("#traveltime").val() == 'Travel Time' ) {
		$("#traveltime").val('')
		}
	});
	$("#traveltime").blur(function(){
		if( $("#traveltime").val() == '' ) {
		$("#traveltime").val('Travel Time')
		}
	});
	$("#perdiam-o").click(function(){
		// Unselect perdiam-e
			$("#perdiam-e").val('0');
			$("#perdiam-e").attr("checked",false).checkboxradio("refresh");
		
			if( $("#perdiam-o").val() != '1' ) {
			$("#perdiam-o").val('1');
			} else {
			$("#perdiam-o").val('0');
			}
		
	});
	$("#perdiam-e").click(function(){
		$("#perdiam-o").val('0');
		$("#perdiam-o").attr("checked",false).checkboxradio("refresh");
		
			if( $("#perdiam-e").val() != '1' ) {
			$("#perdiam-e").val('1');
			} else {
			$("#perdiam-e").val('0');
			}
		
	});
	$("#holiday").click(function(){
		$("#vacation").val('0');
		$("#vacation").attr("checked",false).checkboxradio("refresh");
		
		if( $("#holiday").val() != '1' ) {
		$("#holiday").val('1');
		} else {
		$("#holiday").val('0');
		}
	});
	$("#vacation").click(function(){
		$("#holiday").val('0');
		$("#holiday").attr("checked",false).checkboxradio("refresh");
		
		if( $("#vacation").val() != '1' ) {
		$("#vacation").val('1');
		} else {
		$("#vacation").val('0');
		}
	});
	 
	 $("#all-mechanics li").click(function(){
	 	mechanicid = $(this).attr('mechanic-id');
		mechanicname = $(this).text();
		$("#user_id").val(mechanicid);
		$("#mechanic-display").html(mechanicname);
		$( "#add-mechanic" ).popup( "close" );
	 });
	 $("#superintendant #all-employees li").click(function(){
	 	employeeid = $(this).attr('employee-id');
		employeename = $(this).text();
		$("#superintendant_id").val(employeeid);
		$("#superintendant-display").html("Job Superintendant: " + employeename);
		$( "#superintendant" ).popup( "close" );
	 });
	 $("#received-by #all-employees li").click(function(){
	 	employeeid = $(this).attr('employee-id');
		employeename = $(this).text();
		$("#receiving_user_id").val(employeeid);
		$("#received-by-display").html("Items received by: " + employeename);
		$( "#received-by" ).popup( "close" );
	 });
	 
	$("#equipment-search").keyup(function(){
		var numItems = $('#all-employees').length;
		alert(numItems);
		if( numItems > 1 ) {
			alert(numItems);
			var to_add = $(this).val();
			$("#item-to-add a").html("Create: " + to_add);
		}
	}); 
	
  $("#all-records li").click(function(){
	  recordid = $(this).attr('record-id');
	  add_record_to_list(recordid);
	});

	add_record_to_list = function(recordid){
		if ( recordid != 'item-to-add' ) {
		 has_record = false;
		 record_count('+');
		//recordid = $(this).attr('record-id');
		//recordname = $(this).text();
		recordname = $( "li[record-id='" + recordid + "']" ).text();
		 added[recordcount] = recordid;
		 
		 $("#remove-me").remove();
		 for(var x=0; x < recordcount; x++){
			if ( recordid == added[x] ) {
				has_record = true;
			}
		 }
		 if ( has_record == false ) {
		
		if(type == 'inventory_checkout'){
			$("#added-employees #employees .ui-controlgroup-controls").append('<input class="employee_checkbox" id="' + recordid + '" name="inventory_checkout[' + count + '][name]" value="' + recordname + '" data-theme="c" type="checkbox"><label class="' + recordid + '" for="' + recordid + '"><div class="ui-block-a">' + recordname + '</div><div style="float:right;"><span style="color:orange;" id="' + recordid + '-display-quantity"></span></div><input type="hidden" name="inventory_checkout[' + count + '][inventory_id]" value="' + recordid + '" /><input type="hidden" id="' + recordid + '-quantity" name="inventory_checkout[' + count + '][quantity]" /></label>');
		}
		if( type == 'time_entries' ) {
			
			if ( is_punchin == true ) {
				// Punch in/Punch Out
				//alert("punchin");
		 		$("#added-employees #employees .ui-controlgroup-controls")
				.append('<input id="' + recordid + '" name="employee[' + count + '][full_name]" value="' + recordname + '" data-theme="c" type="checkbox"><label class="' + recordid + '" for="' + recordid + '"><div class="ui-block-a">' + recordname + '</div><div style="float:right;"><span id="' + recordid + '-display-in"></span><span id="' + recordid + '-display-out" ></span><span style="color:orange;" id="' + recordid + '-display-options"></span></div><input type="hidden" name="employee[' + count + '][id]" value="' + recordid + '" /><input type="hidden" id="' + recordid + '-in-time" name="employee[' + count + '][punch_in]" /><input type="hidden" id="' + recordid + '-out-time" name="employee[' + count + '][punch_out]" /><input type="hidden" id="' + recordid + '-perdiam-o" name="employee[' + count + '][perdiam-o]" /><input type="hidden" id="' + recordid + '-perdiam-e" name="employee[' + count + '][perdiam-e]" /><input type="hidden" id="' + recordid + '-holiday" name="employee[' + count + '][holiday]" /><input type="hidden" id="' + recordid + '-vacation" name="employee[' + count + '][vacation]" /><input type="hidden" id="' + recordid + '-mileage" name="employee[' + count + '][mileage]" /><input type="hidden" id="' + recordid + '-traveltime" name="employee[' + count + '][traveltime]" /><input type="hidden" id="' + recordid + '-punchin" name="employee[' + count + '][punchin]" /><input type="hidden" id="' + recordid + '-punchout" name="employee[' + count + '][punchout]" /></label>');
			} else {
				// Hours
				//alert("hours");
				 $("#added-employees #employees .ui-controlgroup-controls")
				 .append('<input class="employee_checkbox" id="' + recordid + '" name="employee[' + count + '][full_name]" value="' + recordname + '" data-theme="c" type="checkbox"><label class="' + recordid + '" for="' + recordid + '"><div class="ui-block-a">' + recordname + '</div><div style="float:right;"><span id="' + recordid + '-display-hours"></span><span id="' + recordid + '-display-in"></span><span id="' + recordid + '-display-out" ></span><span style="color:orange;font-size:.70em;" id="' + recordid + '-display-options"></span></div><input type="hidden" name="employee[' + count + '][id]" value="' + recordid + '" /><input type="hidden" id="' + recordid + '-hours" name="employee[' + count + '][hours]" /><input type="hidden" id="' + recordid + '-quantity" name="employee[' + count + '][quantity]" /><input type="hidden" id="' + recordid + '-perdiam-o" name="employee[' + count + '][perdiam-o]" /><input type="hidden" id="' + recordid + '-perdiam-e" name="employee[' + count + '][perdiam-e]" /><input type="hidden" id="' + recordid + '-holiday" name="employee[' + count + '][holiday]" /><input type="hidden" id="' + recordid + '-vacation" name="employee[' + count + '][vacation]" /><input type="hidden" id="' + recordid + '-mileage" name="employee[' + count + '][mileage]" /><input type="hidden" id="' + recordid + '-traveltime" name="employee[' + count + '][traveltime]" /></label>');
			}
		
		}
		
		checkboxcount++;
		count++;	
			trigger_create();
		 }
	}
		$( ".ui-input-clear" ).trigger( "click" );
	}
	delete_selected = function() {
		
		$(".employee_checkbox:checked").each(function() {
				recordid = $(this).attr('id');
				recordname = $(this).text();
				removeA(added, recordid);
				
				$("input#"+recordid+", label[for="+recordid+"]").remove ();
				
				if ( recordcount == 1 ) {
					if( type == 'inventory' ) {
						$("#added-employees").append('<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me" ><input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No inventory added" data-mini="false"></fieldset>');
					}
					if( type == 'time_entries' ) {
						$("#added-employees").append('<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me" ><input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No employees added" data-mini="false"></fieldset>');
					}
				} 
			record_count('-');
			trigger_create();
			});

	
  			
			if ( added.length < 1 ) {
				if(record_count != 0) {
					if( type == 'inventory' ) {
						$("#added-employees").append('<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me"><input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No inventory added" data-mini="false"></fieldset>');
					}
					if( type == 'time_entries' ) {
						$("#added-employees").append('<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me"><input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No employees added" data-mini="false"></fieldset>');
					}
					} 
				trigger_create();
				}
			
	}
	
	
	
	options_selected = function() {
		var perdiamo = $("#perdiam-o").val();
		var perdiame = $("#perdiam-e").val();
		var holiday = $("#holiday").val();
		var vacation = $("#vacation").val();
		var mileage = ($("#mileage").val() == 'Mileage' )? '': $("#mileage").val();
		var traveltime = ($("#traveltime").val() == 'Travel Time' )? '': $("#traveltime").val();
		var display_options = '';
		if(perdiamo=='1'){
				display_options += ' Po';
			}
			if(perdiame=='1'){
				display_options += ' Pe';
			}
			if(holiday=='1'){
				display_options += ' H';
			}
			if(vacation=='1'){
				display_options += ' V';
			}
			if(mileage>0){
				display_options += " " + mileage +"mi";
			}
			if(traveltime>0){
				display_options += " " + traveltime;
			}	
			
		$("input:checkbox:checked").each(function()
		{
			recordid = $(this).attr('id');
			recordname = $(this).val();
			if(display_options != ""){
				$("#"+recordid+"-display-options").html( " ("+display_options+" )" );
			} else $("#"+recordid+"-display-options").html( "" );
			
			$("#"+recordid+"-perdiam-o").val( (perdiamo=='1')? '1' : '0' );
			$("#"+recordid+"-perdiam-e").val( (perdiame=='1')? '1' : '0' );
			$("#"+recordid+"-holiday").val( (holiday=='1')? '1' : '0' );
			$("#"+recordid+"-vacation").val( (vacation=='1')? '1' : '0' );
			if(mileage>0)$("#"+recordid+"-mileage").val( mileage ); else $("#"+recordid+"-mileage").val( 0 );
			if(traveltime>0)$("#"+recordid+"-traveltime").val( traveltime ); else $("#"+recordid+"-traveltime").val( 0 );
		});
		$( "#options" ).popup( "close" );
		
	}
	
	set_hours = function() {
		var time = $("#hours #hour-select").val() + "." + $("#hours #minute-select").val();
		var display_time = $("#hours #hour-select").val() + "." + $("#hours #minute-select").val() + " Hrs";

		$("input:checkbox:checked").each(function()
		{
			recordid = $(this).attr('id');
			recordname = $(this).val();
			
			$("#"+recordid+"-hours").val( time );
			$("#"+recordid+"-display-hours").html( display_time );
		});
		$( "#hours" ).popup( "close" );
		
		
	}
	
	punchin_selected = function() {
		var in_time = $("#punchin #hour-select").val() + ":" + $("#punchin #minute-select").val();
		var in_display_time = $("#punchin #hour-select").val() + ":" + $("#punchin #minute-select").val() + $("#punchin #am-pm-select").val();

		$("input:checkbox:checked").each(function()
		{
			employeeid = $(this).attr('id');
			employeename = $(this).val();
			
			$("#"+employeeid+"-in-time").val( in_time );
			$("#"+employeeid+"-display-in").html( in_display_time );
		});
		$("#punchin-popup").fadeOut();
		$( "#punchin-popup" ).popup( "close" );
		
	}
	
	punchout_selected = function() {
		var out_time = $("#punchout #hour-select").val() + ":" + $("#punchout #minute-select").val();
		var out_display_time = " - " + $("#punchout #hour-select").val() + ":" + $("#punchout #minute-select").val() + $("#punchout #am-pm-select").val();

		$("input:checkbox:checked").each(function()
		{
			employeeid = $(this).attr('id');
			employeename = $(this).text();
			
			$("#"+employeeid+"-out-time").val( out_time );
			$("#"+employeeid+"-display-out").html( out_display_time );
		});
		$("#punchout-popup").fadeOut();
		$( "#punchout-popup" ).popup( "close" );
		
	}
	
	add_inventory_quantity_pop = function(recordid) {
		console.log( "function init: add_inventory_quantity_pop" );
		add_inventory_quantity_pop_id = recordid
		add_inventory_quantity_pop_run = true;
		$( "#item-quantity" ).popup( "open" );
		trigger_create();
		$("#quantity").focus();
		$("#set-quantity").attr("onclick","add_inventory_quantity_set(\'" + recordid + "\');");
	}
	
	add_inventory_quantity_set = function(recordid) {
		var quantity = $("#quantity").val();
		var display_quantity = "Qty: " + quantity;
		
		$("#"+recordid+"-quantity").val( quantity );
		$("#"+recordid+"-display-quantity").html( display_quantity );
		$( "#item-quantity" ).popup( "close" );
		$("#quantity").val('');
		trigger_create();
		$("#set-quantity").attr("onclick","set_quantity();");
		add_inventory_quantity_pop_run = false;
		add_inventory_quantity_pop_id = null;
		$( ".ui-filterable input" ).focus();
	}
	
	set_quantity = function() {
		var quantity = $("#quantity").val();
		var display_quantity = "Qty: " + quantity;
		$("input:checkbox:checked").each(function()
		{
			recordid = $(this).attr('id');
			recordname = $(this).val();
			
			$("#"+recordid+"-quantity").val( quantity );
			$("#"+recordid+"-display-quantity").html( display_quantity );
		});
		$( "#item-quantity" ).popup( "close" );
		$("#quantity").val('');
		$( ".ui-filterable input" ).focus();
	}
	
	$( "#quantity" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			if (add_inventory_quantity_pop_run == true) {
				add_inventory_quantity_set(add_inventory_quantity_pop_id);
			} else {
				set_quantity();
			}
		}
	});
	
	$( "#quantity" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			if (add_inventory_quantity_pop_run == true) {
				add_inventory_quantity_set(add_inventory_quantity_pop_id);
			} else {
				set_quantity();
			}
		}
	});
	
	$( ".ui-filterable input" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			recordid = $(".ui-first-child").attr("record-id");
			add_record_to_list(recordid);
			add_inventory_quantity_pop(recordid);
		}
	});
	
	
	
	trigger_create = function() {
		$("#added-employees").trigger('create');
		$("#punch-times").trigger('create');
	}
	
	record_count = function (operation) {
		if (operation == "+" ) {
			recordcount = recordcount + 1;
		} else if ( operation == "-" ){
			recordcount = recordcount - 1;
		}
	}
	
	$("#job_id").change(function(){
		jobid = $("#job_id").val();
		/*if( type == 'inventory_checkout' ) {
			$("#submitter-name").html('<img src="images/ajax-preloader.gif" alt="Loading Superintendant" />');
			var url = "http://timeclock.fsii.co/main/inventory/get_submitter_by_jobid.php?jobid=" + jobid;
			  $.getJSON( url, {
				format: "json"
			  })
				.done(function( data ) {
				  console.log( "Superintendant data loaded" );
				  $("#submitter-name").html("Superintendant: " + data.firstname + " " + data.lastname);
				  $("#superintendant_id").val(data.submitter_id);
				  $('#submitter-name').fadeIn('fast');
				});	
		}*/
		if( type == 'time_entries' ) {
			$("#remove-me").remove();
			$('#employees').fadeOut('fast');
			// Jquery employee data load
			setTimeout(function(){
				$("#added-employees #employees .ui-controlgroup-controls").load( '/main/timeinput/get_employees_by_jobid.php?jobid=' + jobid ,{},
					function(){
						trigger_create();
						$('#employees').fadeIn('fast');
						//alert(recordcount);
					});
			},75);
		}
	});
	
		//select and deselect
		$("#selectall").click(function () {
			$('.employee_checkbox').prop('checked', this.checked);
			trigger_create();
		});
		
		//If one item deselect then button CheckAll is UnCheck
		$(".employee_checkbox").click(function () {
			if (!$(this).is(':checked'))
				$('#selectall').prop('checked', false);
		});
	
    
});

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

$("#flip-checkbox").change(function () {
	var is_punchin = $('#flip-checkbox').prop('checked');
	if ( is_punchin ) {
		$("#punch-in").removeClass("ui-screen-hidden");
		$("#punch-out").removeClass("ui-screen-hidden");
		$("#employee-hours").addClass("ui-screen-hidden");
	} else {
		$("#punch-in").addClass("ui-screen-hidden");
		$("#punch-out").addClass("ui-screen-hidden");
		$("#employee-hours").removeClass("ui-screen-hidden");
	}
});

$('.check:button').toggle(function(){
        $('input:checkbox').attr('checked','checked');
        $(this).val('uncheck all')
    },function(){
        $('input:checkbox').removeAttr('checked');
})

/*  returned value: (Array)
three,eleven
*/