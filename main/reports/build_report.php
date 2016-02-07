<?php 
require_once ('../sp-bootstrap.php'); 
$bootstrap->isLoggedIn();

$type = $_GET['type'];
$action = $_GET['action'];
$date = $_GET['date'];
$thejobid = $_GET['thejobid'];
$employeecode = $_GET['employee_code'];
$state = $_GET['thestate'];
$orderby = $_GET['orderby'];
$inventory_code = $_GET['inventory_code'];

if( $thejobid > 0 ) {
	$condition = array( 'job_id' => $thejobid );
	
}
if( $employeecode > 0 ) {
	$condition = array( 'employee_code' => $employeecode );
}
if( $state == 'tx' || $state == 'al' ) {
	$condition = array( 'state' => $state );
}
if( $inventory_code != false ) {
	$condition = array( 'job_id' => $inventory_code );
}

if($type == "visual"){
	switch($action){
		case 'view':
			echo $bootstrap->get_visualweeklyreport($date,$condition,$orderby);
		break;
		case 'download':
			
		break;
	}
} elseif($type == "import") {
	switch($action){
		case 'view':
			//echo "debug";
			echo $bootstrap->get_time_import_report($date);
		break;
		case 'download':
			$file_url = $bootstrap->get_time_import_data_csv($date);
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
} elseif($type == "inactive_employees") {
	echo $bootstrap->get_inactiveemployeesreport( $date );
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