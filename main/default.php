<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php  $user_role = $bootstrap->get_user_role();?>
<?php if ( $user_role < '4' || $_GET['system'] == 'time' ) { ?>  
<?php site_header('time');  ?> 

<?php echo $bootstrap->get_dashboard(); ?>

<!--	<h3>Dashboard</h3>
    <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Time System</h2>
             <ul class="rh_options" data-role="listview" >
            	<li ><a href="/main/employees.php">Employees</a></li>
                <li><a href="reports.php?system=time" >Reports</a></li>
            </ul>
         </div>
      <?php if ( $user_role == 1 ) { ?> 
      <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Confidential Time System</h2>
             <ul class="rh_options" data-role="listview" >
            	<li ><a href="/main/confidential-time-input.php">My Time</a></li>
                <li><a href="confidential-time-reports.php" >Reports</a></li>
            </ul>
         </div>
      <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Inventory System</h2>
             <ul class="rh_options" data-role="listview" >
       			<li><a href="inventory-checkout.php" >Inventory Checkout</a></li>
                <li><a href="/main/inventory-checkouthistory.php" >Checkout History</a></li>
                <li ><a  href="/main/inventory-qr.php" >QR Codes</a></li>
                <li class="" ><a href="inventory-reports.php?system=inventory" >Reports</a></li>
            </ul>
         </div>
        <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Equipment System</h2>
             <ul class="rh_options" data-role="listview" >
       			<li class="ui-disabled"><a href="equipment-input.php" >Equipment Input</a></li>
                <li class="ui-disabled"><a href="reports.php?system=equipment" >Reports</a></li>
            </ul>
         </div>
         <?php } ?>
            <br />
<?php } elseif ( $user_role == 1 || $user_role == 6 && $_GET['system'] == 'confidential_time' ) { ?>
<?php site_header('confidential_time'); ?>
	<h3>Dashboard</h3>
       <ul class="rh_options" data-role="listview" >
                <li ><a  href="/main/confidential-time-reports.php" >Reports</a></li>
            </ul>
            <br />
<?php } elseif ( $user_role == 7 || $user_role == 6 ) { ?>
<?php site_header('confidential_time'); ?>
	<h3>Dashboard</h3>
       <ul class="rh_options" data-role="listview" >
                <li ><a  href="/main/confidential-time-input.php" >My Time</a></li>
                <?php if ( $user_role == 6 ) { ?>
                <li ><a  href="/main/confidential-time-reports.php" >Reports</a></li>
                <? } ?>
            </ul>
            <br />
<?php } elseif ( $user_role > '3' || $_GET['system'] == 'inventory' ) { ?>
<?php site_header('inventory'); ?>
	<h3>Dashboard</h3>
       <ul class="rh_options" data-role="listview" >
                <li ><a  href="/main/inventory-qr.php" >QR Codes</a></li>
                <li class="ui-disabled" ><a href="inventory-reports.php?system=inventory" >Reports</a></li>
            </ul>
            <br />-->
<?php } ?>
<?php site_footer(); ?>








<?php /*
     <div data-role="collapsible-set" data-theme="c" data-inset="false">
        <div data-role="collapsible">
        <h2>List by Date</h2>
            <ul data-role="listview" data-theme="e">
               <?php
			   	foreach ($bootstrap->get_timesheets('date') as $array ) {
					  foreach ( $array as $key => $value ) {
						$$key = $value;
						}
						$datearr = explode('/', $date);
						$input_date = $datearr['2'].'-'.$datearr['0'].'-'.$datearr['1'];
					echo '<li><a href="timeinput/get_timesheets.php?date='.$input_date.'">'.$date.' <span class="ui-li-count">'.$count.'</span></a></li>';
				}
				?>
            </ul>
        </div><!-- By Date -->  
        <div data-role="collapsible">
        <h2>List by Job ID</h2>
            <ul data-role="listview" data-theme="e">
                <?php
			   	foreach ($bootstrap->get_timesheets('job_id') as $array ) {
					  foreach ( $array as $key => $value ) {
						$$key = $value;
						}
					echo '<li><a href="timeinput/get_timesheets.php?job_id='.$job_id.'">Job ID: '.$job_id.' <span class="ui-li-count">'.$count.'</span></a></li>';
				}
				?>
            </ul>
        </div><!-- By Job ID -->  
        <div data-role="collapsible">
        <h2>Search by Employee</h2>
        <ul data-role="listview" data-filter="true" data-filter-theme="c" data-divider-theme="b" data-filter-reveal="true">
            <?php 
				$companyUserRecords = $bootstrap->get_active_employees();
				foreach( $companyUserRecords as $a_key => $users ) {
					foreach ( $users as $key => $value ) {
						$$key = $value;
					}
					echo '<li employee-id="'.$id.'" data-filtertext="'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">';
					echo '<a href="timeinput/get_timesheets.php?user_id='.$id.'&user_name='.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
				}
			?>
        </ul>
        </div><!-- By Employee --> 
    </div><!-- Master set -->   
 */  ?>

