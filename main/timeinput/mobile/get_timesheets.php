<?php
require_once ('../../sp-bootstrap.php');
$bootstrap->isLoggedIn('mobile');
?>

<?php mobile_header(); ?>

<?php   if($_GET['date']) $type = 'Date';
			elseif($_GET['job_id']) $type = 'Job ID';
				else $type = 'Employee'; 
				
		$formated_get = format_output($_GET);
?>
   <div data-role="content" >
   	<h3 align="center">Time Entries</h3>
     <div data-role="collapsible-set" data-theme="c" data-inset="false">
        <div data-role="collapsible" data-collapsed="false">
        <h2>Entries 
		<?php echo $type .": "; 
				switch($type) {
					case 'Date':
					
						echo $formated_get['date'];
					break;
					case 'Job ID':
						echo $formated_get['job_id'];
					break;
					case 'Employee';
						echo $formated_get['user_name'];
					break;
				}
		?>
        </h2>
            <ul data-role="listview" data-theme="c">
                <?php
			 	switch($type) {
					case 'Date':
						foreach ($bootstrap->get_timesheets('date',$_GET['date']) as $array ) {
							  foreach ( $array as $key => $value ) {
								$$key = $value;
								}
							if( $current_jobid != $job_id )	 {
								$current_jobid = $job_id;
								$divider = '<li data-theme="b" data-role="list-divider" >Job ID: '.$job_id.'</li>';	
								echo $divider;
							}
								
							echo '<li>'.ucfirst(strtolower($firstname)). ' ' .ucfirst(strtolower($lastname)).'<span class="ui-li-count">'.$hours.' hrs</span></li>';
							$total_hours += $hours;
						}
					break;
					case 'Job ID':
						foreach ($bootstrap->get_timesheets('job_id',$_GET['job_id']) as $array ) {
							  foreach ( $array as $key => $value ) {
								$$key = $value;
								}
							if( $current_date != $date )	 {
								$current_date = $date;
								$divider = '<li data-theme="b" data-role="list-divider" >Date: '.$date.'</li>';	
								echo $divider;
							}
								
							echo '<li>'.ucfirst(strtolower($firstname)). ' ' .ucfirst(strtolower($lastname)).'<span class="ui-li-count">'.$hours.' hrs</span></li>';
							$total_hours += $hours;
						}
					break;
					case 'Employee':
						foreach ($bootstrap->get_timesheets('user_id',$_GET['user_id']) as $array ) {
							  foreach ( $array as $key => $value ) {
								$$key = $value;
								}
							if( $current_jobid != $job_id )	 {
								$current_jobid = $job_id;
								$divider = '<li data-theme="b" data-role="list-divider" >Job ID: '.$job_id.'</li>';	
								echo $divider;
							}
								
							echo '<li>'.$date.'<span class="ui-li-count">'.$hours.' hrs</span></li>';
							$total_hours += $hours;
						}
					break;
				}
				
			  	 echo '<li data-theme="e" >Total Hours:<span style="float:right;">'.$total_hours.' hrs</span></li>';
				?>
            </ul>
        </div><!-- By Date -->  
     </div><!-- Master set -->       
   </div>

<?php mobile_footer(); ?>