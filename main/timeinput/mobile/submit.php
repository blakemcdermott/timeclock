<?php
require_once ('../../sp-bootstrap.php');
$bootstrap->isLoggedIn('mobile');

$queryArgs[0] = "vwEmployeeData";
$queryArgs[1] = "company_id";
$queryArgs[2] = $bootstrap->userInfo['company_id'];
$companyUserRecords = $bootstrap->mysqliHandler->getArray($queryArgs); 
$totalUsers = $bootstrap->totalCompanyUsers();

?>

<?php mobile_header(); ?>

   <div data-role="content" >
		<?php
			// Submit time entries
			$postdata = $_POST;
			$submit_data = $bootstrap->kill_the_dead($postdata);
			$bootstrap->insert_timesheet($submit_data);
		?>
<ul data-role="listview">
<?php
foreach ( $postdata as $key => $value) {
	if( is_array($value) ){
	foreach ( $value as $emparray){	
	$totalhours += $emparray['hours'];
	echo '<li>'.$emparray['full_name'].'<span style="float:right;">'.$emparray['hours'].'</span></li>';
	}
	}
}
?>
 <li data-theme="e" >Total hours: <span style="float:right;"><?php echo $totalhours; ?></span></li>
</ul>



	
         
    </div>
    
<?php mobile_footer(); ?>