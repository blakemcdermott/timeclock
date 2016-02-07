<?php
require_once ('sp-bootstrap.php'); 
$bootstrap->isLoggedIn();
init_current_module();
$type = $module['name'].'_entries';
site_header( $system ); 

$date_selected = ( $_GET['date'] != NULL ) ? $_GET['date'] : date("m/d/Y");
$data = $bootstrap->get_confidential_time_week_hours ( $bootstrap->userInfo['id'], $date_selected );

?>
<script type='text/javascript' src="/main/js/confidential-time-input-custom.js"></script> 
<script type="text/javascript">
	var type = '<?php echo $type; ?>';
</script>
    <form action="submit.php?type=<?php echo $type; ?>&system=<?php echo $system; ?>" method="post">
    <fieldset> 
	<div data-role="content" >
<?php // print_array($data); ?>
        <input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $bootstrap->userInfo['id'];?>" > 
        <input type="hidden" id="admin_department" name="admin_department" value="<?php echo $bootstrap->userInfo['id'];?>" >
        <label for="mydate" >Work Week</label><input name="mydate" id="mydate" type="text" data-role="date" data-inline="false" value="<?PHP echo date("m/d/Y", strtotime($date_selected)); ?>" > 
        <div class="jquery-load"></div>
		<?php 
		$args = array(
					"submitter_id" => $bootstrap->userInfo['id'],
					"date" => $date_selected,
					"date_data" => $data
					);
		get_confidential_time_entry_elements( $args ); 
		?>
        <input type="submit" name="submit" data-inline="true" data-icon="clock" data-iconpos="right" value="Update Timesheet" data-mini="true">
        <input type="submit" name="sign-timesheet" data-inline="true" data-icon="check" data-iconpos="right" value="Sign Completed Timesheet" data-mini="true"> 
           </div>
    </div>
            
            
           </fieldset>
        </form>
    </div>
<?php site_footer(); ?>