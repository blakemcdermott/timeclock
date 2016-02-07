<?php
require_once ('../sp-bootstrap.php'); $bootstrap->isLoggedIn();
$jobid = $_GET['jobid'];

if( is_numeric( $jobid ) ) {
	$employees_by_jobid = $bootstrap->get_employees_by_jobid( $jobid );
	//print_r($employees_by_jobid);die();
	if( $employees_by_jobid ) {
		foreach ( $employees_by_jobid as $employee ){
			if( !is_array($employee) ) {
				$record_count++;
				echo $employee;
			}
		}
		$js_variables = "\n" .'<script type="text/javascript">'. "\n" .'employeecount = "'.$record_count.'";' . "\n" ;
		for($x=0;$x<$record_count;$x++){
			$js_variables .= 'added['.$x.'] = "'.$employees_by_jobid['data'][$x]['user_id'].'";' . "\n" ;
		}
		$js_variables .='</script>';
		echo $js_variables;
	} else {
		echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me">
				<input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No employees for: '.$jobid.'" data-mini="false">
			</fieldset>';
	}
} else {
		echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me">
				<input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="Invalid Job ID" data-mini="false">
			</fieldset>';
	}
?>
