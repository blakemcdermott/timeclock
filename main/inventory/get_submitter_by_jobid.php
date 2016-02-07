<?php
require_once ('../sp-bootstrap.php'); $bootstrap->isLoggedIn();
$jobid = $_GET['jobid'];

if( is_numeric( $jobid ) ) {
	$data = $bootstrap->get_submitter_by_jobid( $jobid );
	echo json_encode($data);
} else {
		echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me">
				<input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="Invalid Job ID" data-mini="false">
			</fieldset>';
	}
?>
