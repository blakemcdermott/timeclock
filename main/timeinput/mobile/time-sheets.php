<?php
require_once ('../../sp-bootstrap.php');
$bootstrap->isLoggedIn('mobile');
?>

<?php mobile_header(); ?>


   <div data-role="content" >
            
    <h3 align="center">Time Sheets</h3>
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
					echo '<li><a href="get_timesheets.php?date='.$input_date.'">'.$date.' <span class="ui-li-count">'.$count.'</span></a></li>';
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
					echo '<li><a href="get_timesheets.php?job_id='.$job_id.'">Job ID: '.$job_id.' <span class="ui-li-count">'.$count.'</span></a></li>';
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
					echo '<a href="get_timesheets.php?user_id='.$id.'&user_name='.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
				}
				?>
        </ul>
        </div><!-- By Employee --> 
    </div><!-- Master set -->       
   </div>
    <ul class="rh_options" data-role="listview" >
               <li ><a href="#popupWeeklyMenu" data-rel="popup" data-transition="slideup">Weekly Time Report</a></li>
            </ul>
<div data-role="popup" id="popupWeeklyMenu" data-theme="a" data-overlay-theme="b">
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Enter Job ID</li>
        	<input type="number" id="jobid" data-inline="true">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="date-input" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('visual','view',true);" >View Report</a></li>
            <li data-icon="arrow-d" class="ui-disabled" ><a id="downloadWeeklyReport" href="" onclick="generate_report('visual','download',true);" >Download Report</a></li>
    </ul>
</div>  

<?php mobile_footer(); ?>