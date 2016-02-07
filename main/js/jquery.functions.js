$(document).ready(function(){
	//$("#viewWeeklyReport").attr("onclick","generate_report('view','2014-06-14');");
	function triggerPopup(){
		setTimeout(function(){$( '#showPopup' ).trigger('click')},100);
	}
	
	delete_record = function(record_id,type){
		
	}
		
	generate_report = function(type, action, hasdate, orderby){
			
				if(orderby == 'job_id') { var dateinput = $("#date-input-jobid").val();	}
				if(orderby == 'user_id') { var dateinput = $("#date-input-employee").val(); }
				if(orderby == '') var dateinput = $("#import-date-input").val();
				if(orderby == 'state') { 
					var dateinput = $("#date-input-state").val(); 
					var state = '&thestate=' + $('input[name=state]:checked', '#state-options').val();
				} else { var state = ''; }
			
				if($("#jobid").val() != '' ){ 
					var jobid = '&thejobid=' + $("#jobid").val(); 
					} else {  var jobid = ''; }
				if($("#employee_code").val() != '' ){ 
					var employeecode = '&employee_code=' + $("#employee_code").val(); 
					} else {  var employeecode = ''; }
				
				if(type == 'import') {
					var url = "reports/build_report.php?type=" + type + "&action=" + action + "&date=" + dateinput;
					$("#continue-to-print").attr("href","/main/print-report.php?type=" + type + "&action=" + action + "&date=" + $("#import-date-input").val() );
				}
				else if(type == 'visual' || type == 'inventory' ){
					if(type == 'inventory'){
						switch(orderby) {
							case 'job_id':
								var fromdateinput = $("#from-date-input-jobid").val();
								var todateinput = $("#to-date-input-jobid").val();
							break;
							case 'inventory_code':
								var fromdateinput = $("#from-date-input-inventorycode").val();
								var todateinput = $("#to-date-input-inventorycode").val();
							break;
							case 'checkout_id':
								var fromdateinput = $("#from-date-input-checkout").val();
								var todateinput = $("#to-date-input-checkout").val();
							break;
							case 'superintendant_id':
								var fromdateinput = $("#from-date-input-superintendant").val();
								var todateinput = $("#to-date-input-superintendant").val();
							break;
							default:
								var fromdateinput = $("#from-date-input").val();
								var todateinput = $("#to-date-input").val();
						}
						if ( fromdateinput && todateinput ) { var dateinput = fromdateinput + "|" + todateinput;}
					}
					var url_base = "reports/build_report.php";
					var url_params = "?type=" + type + "&action=" + action + "&date=" + dateinput + "&orderby=" + orderby + jobid + employeecode + state;
					var url = url_base + url_params;
					$("#continue-to-print").attr("href","/main/print-report.php?type=" + type + "&action=" + action + "&date=" + dateinput + "&orderby=" + orderby + jobid + employeecode + state );
					
				}
			
			if(type == 'inactive_employees') { 
				var dateinput = $("#inactive-date-input-employee").val(); 
				var url_base = "reports/build_report.php";
				var url_params = "?type=" + type + "&date=" + dateinput ;
				var url = url_base + url_params;
				$("#continue-to-print").attr("href","/main/print-report.php?type=" + type + "&date=" + dateinput);
			}
			
			 $("#response").load(url, function() {
				switch(action) {
					case 'view':
						view_report();
						triggerPopup();
					break;
					
					case 'download':
						download_report();
					break;
					default:
					view_report();
					triggerPopup();
				}
			});
			delete window.state;
			delete window.employeecode;
			delete window.jobid;
			$("#jobid").val(null);
			$("#employee_code").val(null);
			
		}
	
	
	download_report = function(){
		$.fileDownload($("#response").html());
	}
	
	view_report = function(){
		
			$("#response").html($("#response").html());

	}
	
});
