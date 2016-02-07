<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Russo Time System - Report</title>
<link rel="stylesheet" media="print" type="text/css" href="/main/css/print.css" /> 
<style type="text/css" media="all">
	td { border:1px solid #C3C3C3 !important; padding:0px 3px; }

</style>
<style type="text/css" media="print">
<?php if($_GET['orderby'] == 'user_id') { ?>
	.report-wrapper {
		padding:10px;
		display:block !important;
		float:none;
		overflow:visible !important;
		border:1px solid #CCC !important;
		page-break-after:avoid !important;
		break-after:avoid !important;
		}
<?php } ?>
	td { border:1px solid #C3C3C3 !important; padding:0px 3px; }
<?php if ($_GET['type'] == 'inventory' ) { ?>  
	@page { size: landscape; }
	td { font-size:.85em; }
	.report-wrapper {
	padding:10px;
	display:block !important;
	float:none;
	overflow:visible !important;
	border:1px solid #CCC !important;
	page-break-after:avoid !important;
	break-after:avoid !important ;
	}
 <?php } ?>
</style>
</head>
<body>
<?php 
require_once ('sp-bootstrap.php'); 
$bootstrap->isLoggedIn();

$type = $_GET['type'];
$action = $_GET['action'];
$date = $_GET['date'];
$thejobid = $_GET['thejobid'];
$employeecode = $_GET['employee_code'];
$orderby = $_GET['orderby'];
$inventory_code = $_GET['inventory_code'];

if( $thejobid > 0 ) {
	$condition = array( 'job_id' => $_GET['thejobid'] );
}
elseif( $employeecode > 0 ) {
	$condition = array( 'employee_code' => $_GET['employee_code'] );
}
if( $inventory_code != false ) {
	$condition = array( 'job_id' => $inventory_code );
}
if( $state == 'tx' || $state == 'al' ) {
	$condition = array( 'state' => $state );
}
if( $type == "visual" ){
	switch($action){
		case 'view':
			echo $bootstrap->get_visualweeklyreport($date,$condition,$orderby);
		break;
		case 'download':
			
		break;
	}
} elseif( $type == "import" ) {
	switch($action){
		case 'view':
			echo $bootstrap->get_importreport($date);
		break;
		case 'download':
			$file_url = $bootstrap->get_importdata_csv($date);
			echo $file_url;
		break;
	}
} elseif($type == "confidential_time_import" || $type == "confidential_time") {
	switch($action){
		case 'view':
			//echo "debug";
			echo $bootstrap->get_confidential_time_import_report($date,$orderby);
		break;
		case 'download':
			$file_url = $bootstrap->get_confidential_time_import_data_csv($date);
			echo $file_url;
		break;
	}
} elseif( $type == "inactive_employees" ) {
	echo $bootstrap->get_inactiveemployeesreport($date);
} elseif ( $type == "inventory" ) {
	if ( $orderby != false && $orderby != 'undefined' ) {
		echo $bootstrap->get_visualinventoryreport($date,$condition,$orderby);
	} else {
		switch($action){
			case 'view':
				echo $bootstrap->get_inventory_import_report($date,$orderby);
			break;
			case 'download':
				echo $bootstrap->get_inventory_import_data_csv($date,$orderby);
			break;
		}
	}
}

?>
<script type="text/javascript">
	window.print();
</script>
</body>
</html>
