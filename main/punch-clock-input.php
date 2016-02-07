<?php
require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn(); init_current_module(); $type = $module['name'].'_entries';

site_header( $system ); 

$date_selected = ( $_GET['date'] != NULL ) ? $_GET['date'] : date("Y-m-d H:i:s");
$data = $bootstrap->get_confidential_time_week_hours ( $bootstrap->userInfo['id'], $date_selected );

$user_id = ( $_GET['id'] > 0 )? $_GET['id'] : $bootstrap->userInfo['id'];
$fullname = $bootstrap->userInfo['firstname']." ".$bootstrap->userInfo['lastname'];

$is_punched_in = $bootstrap->is_punched_in($user_id);
 
 
if ($_POST) {
	$bootstrap->insert_record($_POST, $type);
}

?>
<script type='text/javascript' src="/main/js/punch-clock-input-custom.js"></script> 
<script type="text/javascript">
	var type = '<?php echo $type; ?>';
</script>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id'];?>&type=<?php echo $type; ?>&system=<?php echo $system; ?>" method="post">
        <fieldset> 
        <div data-role="content" >
            <input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $user_id;?>" > 
            <input type="hidden" id="admin_department" name="admin_department" value="<?php echo $user_id ;?>" >
            <input type="hidden" id="date" name="date" value="<?php echo $date_selected;?>" >  

            <div>
            <?php if( is_admin_role() && $_GET['id'] < 1 ) { ?>
            <h3>Punch Clock - Administration</h3>
             <?php $bootstrap->the_employee_search_table(); ?>
		<?php } else {  ?>
		<h3>Punch Clock</h3>
        <?php
			if ($_GET['id']){
				$user_info = $bootstrap->get_user($user_id);
				$fullname = $user_info['fullname'];
				$bootstrap->the_employee_search_table();
			}
		?>
        			
                    
                    <h3>Employee: <span style="font-weight:normal;"><?php echo $fullname; ?></span></h3>
                    
                    </div>
                    <div data-role="header" <?php echo ($is_punched_in)? 'class="clocked-in"':'class="clocked-out"'; ?> >
                    <h1><?php echo ($is_punched_in)? "You are currently clocked in":"You are currently clocked out";?></h1>
                    </div>
                    
                    <input <?php if($is_punched_in) echo 'disabled="disabled"'; ?> data-icon="arrow-r" id="punch-in" name="in" type="submit" value="Punch In" />
                    <input <?php if(!$is_punched_in) echo 'disabled="disabled"'; ?> data-icon="arrow-l" id="punch-out" name="out" type="submit" value="Punch Out" />
                       </div>
                       
                </div>
                </fieldset>
            </form>
        
            <?php
            $todays_punched_times = $bootstrap->get_todays_punched_times($user_id);
            foreach ($todays_punched_times as $row ) {
                $punched_times_list .= '<li><a href="#">Punched '.ucfirst($row['action']).' <span class="ui-li-count">'.date('H:i',strtotime($row['punch_time'])).'</span></a></li>';
            }
            ?>
            <ul data-role="listview" data-count-theme="b" data-inset="true">
            <li data-role="list-divider" style="background-color:#9BD8F5;">Todays Activity</li>
            <?php echo $punched_times_list; ?>
            </ul>
    	<?php } ?>
    </div>
<?php site_footer(); ?>