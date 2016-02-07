<?php
require_once ('sp-bootstrap.php'); 
$bootstrap->isLoggedIn();
$type = $_GET['type'];
$system = $_GET['system'];
//$queryArgs[0] = "vwEmployeeData";
//$queryArgs[1] = "company_id";
//$queryArgs[2] = $bootstrap->userInfo['company_id'];
//$companyUserRecords = $bootstrap->mysqliHandler->getArray($queryArgs); 
//$totalUsers = $bootstrap->totalCompanyUsers();

?>

<?php site_header( $system ); ?>

<?php if ( $type == 'confidential_time_entries' ) { ?>
	<div data-role="content" >
		<?php
            // Submit time entries
            $postdata = $_POST;
            $submit_data = $bootstrap->kill_the_dead($postdata,$type);
			
			if($postdata['sign-timesheet']) {
				if ( $bootstrap->sign_timesheet($submit_data) ) echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Time Sheet has been signed.</button><br />';
			} else {
				if ( $bootstrap->insert_record($submit_data, $type) ) echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Time has been updated</button><br />';
				
			}
		   
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
           
        </ul>
        <br />
        <a href="confidential-time-input.php?date=<?php echo $submit_data['mydate'] ;?>" class="ui-btn ui-icon-arrow-l ui-corner-all" >Back To Confidential Time</a>
	</div>
<?php } if ( $type == 'time_entries' ) { ?>
	<div data-role="content" >
		<?php
            // Submit time entries
            $postdata = $_POST;
            $submit_data = $bootstrap->kill_the_dead($postdata);
            $bootstrap->insert_record($submit_data, $type);
        ?>
        <button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Employee Time has been entered.</button><br />
        <ul data-role="listview">
            <?php
            foreach ( $postdata as $key => $value) {
                if( is_array($value) ){
                foreach ( $value as $emparray){	
					if ( $emparray['hours'] > 0 ) {
						 $totalhours += $emparray['hours'];
						echo '<li>'.$emparray['full_name'].'<span style="float:right;">'.$emparray['hours'].'</span></li>';
					}
                }
                }
            }
            ?>
            <li data-theme="e" >Total hours: <span style="float:right;"><?php echo $totalhours; ?></span></li>
        </ul>
        <br />
        <a href="time-input.php" class="ui-btn ui-icon-arrow-l ui-corner-all" >Enter More Time</a>
	</div>
<? } if ( $type == 'inventory_checkout' ) { ?>
	<div data-role="content" >
		<?php
            // Submit time entries
            $postdata = $_POST;
			$postdata['job_id'] = strtoupper( $postdata['job_id'] );
            $bootstrap->insert_record($postdata, $type);
        ?>
        <button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Invetory has been checked out.</button><br />
        <ul data-role="listview">
            <?php
            foreach ( $postdata as $key => $value) {
                if( is_array($value) ){
                foreach ( $value as $dataarray){	
                //$totalhours += $emparray['hours'];
                echo '<li>'.$dataarray['name'].'<span style="float:right;">'.$dataarray['quantity'].'</span></li>';
                }
                }
            }
            ?>
            
        </ul>
        <br />
        <a href="inventory-checkout.php" class="ui-btn ui-icon-arrow-l ui-corner-all" >Checkout More Inventory</a>
	</div>
<?php } ?>

<?php site_footer(); ?>