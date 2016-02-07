<?php
//	Example Usage for jquery_load();
//	
//	$args = array( "func" => "get_confidential_time_entry_elements",
//		"func_args" => array (
//			"submitter_id" => $bootstrap->userInfo['id'],
//			"date" => $date_selected
//		) 
//	);
//	jquery_load( $args );

function jquery_load( $args ){
	$file = '/main/inc/ajax.functions.php';
	echo '<script type="text/javascript">';
	echo '$(document).ready(function(){';
	echo '$(".jquery-load").load( \''.$file.'\?';
	$x=0;
	foreach ( $args as $key=>$value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $var_name=>$var_data ) {
				echo "func_args[".$var_name."]=".$var_data."&";
			}
		} else {
			echo $key."=".$value."&";
		}
	}
	echo '\' ,{},function(){$(".jquery-load").trigger(\'create\');});';
	echo '});';
	echo '</script>';	
}

function timeSelect($hrs,$mins,$type,$military=true) {

	### Build hours
	$hrs_select = '
	
	<select id="hour-select" name="hour-select" >';
		for($x=0;$x<=$hrs;$x++){
			$build_options .= '<option value="'.$x.'" >Hour: '.$x.'</option>';
		}
	$hrs_select .= $build_options . '</select>';
	unset($build_options);
	
	### Build minutes
	if($mins <= 15) { 
		$interval = ( 60 / $mins );
	} else {
		$interval = ( 100 / $mins );
	}

	$interval_step = 0;
	$min_select = '
	<select id="minute-select" name="minute-select" >';
		for($x=0;$x<$interval;$x++){
			if($mins == 50 && $x != 0)$build_options .= '<option value="'.$x * $mins .'" >Minute: 30</option>';
			else $build_options .= ($x == 0) ? '<option value="00" >Minute: 00</option>' : '<option value="'.$x * $mins .'" >Minute: '.$x * $mins .'</option>';
			$interval_step += $mins;
			
		}
	$min_select .= $build_options . '</select>';
	unset($build_options);
/*	
	$am_pm = '
	<select id="am-pm-select" style="float:left;">
		<option>AM</option>
		<option>PM</option>
	</select>
	';
	*/
	
	return $hrs_select.$min_select.$am_pm;
}

function format_output($data) {
		// Format Date
		$datearr = explode('-',$data['date']);
		$date = $datearr['1'].'/'.$datearr['2'].'/'.$datearr['0'];
		$data['date'] = $date;
		
		return $data;

}

function format_input($data) {
		// Format Date
		$datearr = explode('/',$data['date']);
		$date = $datearr['2'].'-'.$datearr['0'].'-'.$datearr['1'];
		$data['date'] = $date;
		
		return $data;

}

function recursive_array_search($array){
	if(is_array($array)){
		foreach($array as $key => $value){
			if(is_array($value)){
				foreach($value as $key1 => $value1){
					$file = array_search('.php', $value1);
				}
				
			}
		$file = array_search('.php', $value);	
		}
	}
}

function site_header($system='time'){
	global $bootstrap;
	define('__SYSTEM__',$system);
	include(toMainDir().'site-header.php');
	$is_allowed = is_allowed();
	//if ( $is_allowed['authorized'] != true ) $bootstrap->to_dashboard( $is_allowed ); 
	//if ( $_SESSION["authorized"] != true ) echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e">'.$_SESSION["reason"].'</button><br />';

}
	
function site_footer(){
	global $type;
	global $data;
	include(toMainDir().'site-footer.php');
}

function sp_debugger(){ 
	$args = func_get_args();
	//include(toMainDir().'inc/sp-debugger.php'); ?> 
		<?php 
        if($args[0]){ ?>
        <script type="text/javascript">
          var debugMsg = "<?php echo $args[0]; ?>";
			  if(debugMsg){
				alert('<?php echo $args[1]; ?>');
			  }
        </script>
        <?php } 
        ?>
<?php 
}

// Settings page functions - Begin //

function settingsMenuSelect($menuItem){
	$filepath = 'inc-settings/'.$menuItem.".php";
	if(file_exists($filepath)){
		require_once($filepath);
	} 
	else {
		echo "This page is currently under development.<br />";
		//echo $filepath;
	}
}

// jQuery Mobile Themed layout 
// Generates entire week with defined number of rows
// Can be used with jquery_load() function
function get_punch_clock_entry_elements( $func_args ){
	foreach ( $func_args as $key=>$value ) {
		$$key = $value;
	}

	$week_dates = week_dates( $date ,$format='m/d/Y');
	
		foreach ($week_dates as $day=>$date ) {
			$row_count = count($date_data[date("Y-m-d",strtotime($date))]);
			echo '<ul data-role="listview" data-inset="true" class="confidential-time-entry" >';
				echo '<li data-role="list-divider">'.$day.'<span class="ui-li-count">'.$date.'</span></li>';
				echo '<li class="'.$day.'-li">';
					echo '<a href="#" class="ui-btn ui-icon-carat-r ui-btn-icon-right punch-out" date="'.date("Y-m-d",strtotime($date)).'" day="'.$day.'" row-count="'.$row_count.'" >Punch-Out</a>';
					echo '<a href="#" class="ui-btn ui-icon-carat-l ui-btn-icon-right punch-in" date="'.date("Y-m-d",strtotime($date)).'" day="'.$day.'" row-count="'.$row_count.'" >Punch-In</a>';
					echo '<br style="clear:both;" />';
					for( $row_num=0; $row_num < $row_count; $row_num++ ) {
						$func_args = array( "day"=>$day, "row_num"=>$row_num, "date"=> date("Y-m-d",strtotime($date)), "date_data" => $date_data[date("Y-m-d",strtotime($date))][$row_num], "is_signed" => $date_data[date("Y-m-d",strtotime($date))][$row_num]['is_signed'] );
						get_punch_clock_entry_row( $func_args );
					}
				echo '</li>';
			echo '</ul>';
			
		}
	
}

// jQuery Mobile Themed layout 
// Generates individual confidential hour rows
// Can be used with jquery_load() function
function get_punch_clock_entry_row( $func_args ){
	foreach ( $func_args as $key=>$value ) {
		$$key = $value;
	}
	if( $is_signed ) $is_disabled = 'disabled="disabled"';
	$selected = $date_data['timetype'];

	echo '<div class="ui-grid-a" ><div class="ui-block-a">';
	echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
	<input type="hidden" name="'.$day.'-id-'.$row_num.'" id="'.$day.'-id-'.$row_num.'" value="'.$date_data['id'].'">
	<input type="hidden" name="'.$day.'-date-'.$row_num.'" id="'.$day.'-date-'.$row_num.'" value="'.$date.'">';
	
	$time_type_select =  '
	<label for="'.$day.'-timetype-'.$row_num.'" class="select">Select Time Type</label><select name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-timetype-'.$row_num.'" data-native-menu="false" data-icon="grid" data-iconpos="left">
	<option>Choose a time type</option><optgroup label="Standard Types">
	<option value="job">Job</option>
	<option value="pto">Paid Time Off</option>
	</optgroup>
	<optgroup label="Admin Types"><option value="5">Shop</option>    
	<option value="7">Administrative</option><option value="15">Warehouse</option>
	<option value="31">Excavating</option><option value="33">Foundation</option>
	<option value="44">TX Foundation</option></optgroup>
	</select>';
	
	echo $time_type_select = preg_replace('/(option value="'.$selected.'")/','$1 selected=""',$time_type_select);
	
	echo '</fieldset></div>';
	echo '<div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->';
	echo '<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-jobid-'.$row_num.'" id="'.$day.'-jobid-'.$row_num.'" value="'.$date_data['job_id'].'" placeholder="Job ID">
			</div>
			<div class="ui-block-b">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-hours-'.$row_num.'" id="'.$day.'-hours-'.$row_num.'" value="'.$date_data['hours'].'" placeholder="Hours">
			</div>
		</fieldset>
	</div></div><!-- /grid-a -->';
}


// jQuery Mobile Themed layout 
// Generates entire week with defined number of rows
// Can be used with jquery_load() function
function get_confidential_time_entry_elements( $func_args ){
	foreach ( $func_args as $key=>$value ) {
		$$key = $value;
	}

	$week_dates = week_dates( $date ,$format='m/d/Y');
	
		foreach ($week_dates as $day=>$date ) {
			$row_count = count($date_data[date("Y-m-d",strtotime($date))]);
			echo '<ul data-role="listview" data-inset="true" class="confidential-time-entry" >';
				echo '<li data-role="list-divider">'.$day.'<span class="ui-li-count">'.$date.'</span></li>';
				echo '<li class="'.$day.'-li">';
					echo '<a href="#" class="ui-btn ui-icon-plus ui-btn-icon-right add-time" date="'.date("Y-m-d",strtotime($date)).'" day="'.$day.'" row-count="'.$row_count.'" >Add Time</a><br style="clear:both;" />';
					for( $row_num=0; $row_num < $row_count; $row_num++ ) {
						$func_args = array( "day"=>$day, "row_num"=>$row_num, "date"=> date("Y-m-d",strtotime($date)), "date_data" => $date_data[date("Y-m-d",strtotime($date))][$row_num], "is_signed" => $date_data[date("Y-m-d",strtotime($date))][$row_num]['is_signed'] );
						get_confidential_time_entry_row( $func_args );
					}
				echo '</li>';
			echo '</ul>';
			
		}
	
}

// jQuery Mobile Themed layout 
// Generates individual confidential hour rows
// Can be used with jquery_load() function
function get_confidential_time_entry_row( $func_args ){
	foreach ( $func_args as $key=>$value ) {
		$$key = $value;
	}
	if( $is_signed ) $is_disabled = 'disabled="disabled"';
	$selected = $date_data['timetype'];

	echo '<div class="ui-grid-a" ><div class="ui-block-a">';
	echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
	<input type="hidden" name="'.$day.'-id-'.$row_num.'" id="'.$day.'-id-'.$row_num.'" value="'.$date_data['id'].'">
	<input type="hidden" name="'.$day.'-date-'.$row_num.'" id="'.$day.'-date-'.$row_num.'" value="'.$date.'">';
	
	$time_type_select =  '
	<label for="'.$day.'-timetype-'.$row_num.'" class="select">Select Time Type</label><select name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-timetype-'.$row_num.'" data-native-menu="false" data-icon="grid" data-iconpos="left">
	<option>Choose a time type</option><optgroup label="Standard Types">
	<option value="job">Job</option>
	<option value="pto">Paid Time Off</option>
	</optgroup>
	<optgroup label="Admin Types"><option value="5">Shop</option>    
	<option value="7">Administrative</option><option value="15">Warehouse</option>
	<option value="31">Excavating</option><option value="33">Foundation</option>
	<option value="44">TX Foundation</option></optgroup>
	</select>';
	
	echo $time_type_select = preg_replace('/(option value="'.$selected.'")/','$1 selected=""',$time_type_select);
	
	echo '</fieldset></div>';
	echo '<div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->';
	echo '<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-jobid-'.$row_num.'" id="'.$day.'-jobid-'.$row_num.'" value="'.$date_data['job_id'].'" placeholder="Job ID">
			</div>
			<div class="ui-block-b">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-hours-'.$row_num.'" id="'.$day.'-hours-'.$row_num.'" value="'.$date_data['hours'].'" placeholder="Hours">
			</div>
		</fieldset>
	</div></div><!-- /grid-a -->';
}

// ### DEPRECIATED ###
// jQuery Mobile Themed layout 
// Generates individual confidential hour rows
// Can be used with jquery_load() function
function get_confidential_time_entry_row_old( $func_args ){
	foreach ( $func_args as $key=>$value ) {
		$$key = $value;
	}
	if( $is_signed ) $is_disabled = 'disabled="disabled"';
	$checked = $date_data['timetype'];
	if ( $checked == 'job' ) $jobid = 'checked="checked"';
	elseif ( $checked == 'admin' ) $admin = 'checked="checked"';
	elseif ( $checked == 'pto' ) $pto = 'checked="checked"';
	else $jobid = 'checked="checked"';
	
	//<input '.$is_disabled.' type="radio" name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-radio-choice-d-'.$row_num.'" onclick="admin_department();" value="admin" '.$admin.' ><label for="'.$day.'-radio-choice-d-'.$row_num.'">ADMIN</label>
	
	echo '<div class="ui-grid-a" ><div class="ui-block-a">';
	echo '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
	<input type="hidden" name="'.$day.'-id-'.$row_num.'" id="'.$day.'-id-'.$row_num.'" value="'.$date_data['id'].'">
	<input type="hidden" name="'.$day.'-date-'.$row_num.'" id="'.$day.'-date-'.$row_num.'" value="'.$date.'">
	<input '.$is_disabled.' type="radio" name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-radio-choice-c-'.$row_num.'" value="job" '.$jobid.' ><label for="'.$day.'-radio-choice-c-'.$row_num.'">JOB</label>';
	
	echo '<input '.$is_disabled.' type="radio" name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-radio-choice-e-'.$row_num.'" value="pto" '.$pto.' ><label for="'.$day.'-radio-choice-e-'.$row_num.'">PTO</label>';
	echo '<input '.$is_disabled.' type="radio" name="'.$day.'-timetype-'.$row_num.'" id="'.$day.'-radio-choice-d-'.$row_num.'" onclick="admin_hide(this.id);" value="admin" '.$admin.' ><label for="'.$day.'-radio-choice-d-'.$row_num.'">ADMIN</label>';
	echo '<label for="'.$day.'-admin-department-'.$row_num.'" id="'.$day.'-admin-department-'.$row_num.'" class="select">Custom select menu:</label>
	<select name="'.$day.'-admin-department-'.$row_num.'" id="'.$day.'-admin-department-'.$row_num.'" id="'.$day.'-admin-department-'.$row_num.'" data-native-menu="false" class="admin-select">
	    <option>ADMIN</option>
		<option value="5">Shop</option>    
		<option value="7">Administrative</option>
	    <option value="15">Warehouse</option>
	    <option value="31">Excavating</option>
	    <option value="33">Foundation</option>
		<option value="44">TX Foundation</option>
	</select>';
	
	echo '</fieldset></div>';
	echo '<div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->';
	echo '<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-jobid-'.$row_num.'" id="'.$day.'-jobid-'.$row_num.'" value="'.$date_data['job_id'].'" placeholder="Job ID">
			</div>
			<div class="ui-block-b">
				<input '.$is_disabled.' type="text" type="number" name="'.$day.'-hours-'.$row_num.'" id="'.$day.'-hours-'.$row_num.'" value="'.$date_data['hours'].'" placeholder="Hours">
			</div>
		</fieldset>
	</div></div><!-- /grid-a -->';
}



function print_array($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

// Gets the slug of the specified module
function get_file_module( $file ){
	if(!$file) $file = $_SERVER['PHP_SELF'];
	preg_match('/([^\/]+)-init\.php/',$file, $module);
	if(!$module[1]) {
		preg_match('/([^\/]+)-(.*?)\.php/',$file, $module);
	}
	
	return $module[1];
}

// Function to be depreciated
// Use get_module() instead
function get_module_name( $module_name ){
	// New Function Name
	get_module( $init_file );
}

// Gets all pages associated with the accessed module
function get_module_pages( $module_name ){
	$files = scandir( getcwd(), 1 );
	
	foreach ( $files as $key => $file ) {
		if ( $file != '.' || $file != '..' ) {
			if ( get_file_module( $file ) != $module_name ){
				unset( $files[$key] );
			} else {
				$pages[] = $file;
			}
		}
	}
	return $pages;
}

// Builds master module array
function get_module( $init_file=false ) {
	$module_name = get_file_module( $init_file );
	
	$module['slug'] = $module_name;
	$module['display_name'] = ucwords( strtolower( str_replace("-", " ", $module_name) ) );
	$module['name'] = strtolower( str_replace("-", "_", $module_name) );
	$module['current_page'] = $_SERVER['PHP_SELF'];
	$module['pages'] = get_module_pages( $module_name );
	
	if( get_report() ) exit;
	else return $module;
}

function init_current_module( $module_file=false ){
	if(!$module_file) { 
		$module_file = $_SERVER['PHP_SELF'];
		
	}
	preg_match('/([^\/]+)-(.*?)\.php/',$module_file, $module);
	$init_file = $module[1]."-init.php";
	if ( file_exists( $init_file ) ) {
		require_once( $init_file );
	}
	
}

function is_admin_role() {
	global $bootstrap;
	global $module;

	foreach ( $bootstrap->capabilities as $role_name => $obj ) {
			preg_match('/'.$module['name'].'_administrator/',$role_name,$matches);
			if($matches) return true;
		}
		return false;
}

function has_capability($cap=0){
	global $bootstrap;
	foreach ( $bootstrap->capabilities as $role_name ) {
			foreach( $role_name as $module_name ) {
				$module_name =  (array) $module_name;
				if ( in_array($cap, $module_name )  ){
					$has_capability = true;
					return $has_capability;
				} else {
					$has_capability = false;
				}
			}
		}
	return $has_capability;
}

function is_allowed(){
	global $bootstrap;
	
	if( !is_home() && !is_submit() ) {
		$module_file = $_SERVER['PHP_SELF'];
		preg_match('/([^\/]+)\.php/',$module_file, $page);
	
		foreach ( $bootstrap->capabilities as $role_name ) {
			foreach( $role_name as $module_name ) {
				$module_name =  (array) $module_name;
				if ( in_array($page[1], $module_name ) || in_array("full-access", $module_name ) ){
				$is_allowed['authorized'] = true;
				
				return $is_allowed;
				} else {
					$is_allowed['authorized'] = false;
					$is_allowed['reason'] = "You do not have the appropriate permissions to view that page";
				}
			}
		}
		return $is_allowed;
		
/*		foreach($bootstrap->capabilities as $role ) {
			if ( in_array($page[1], $role ) || in_array("full-access", $role ) ){
				$is_allowed['authorized'] = true;
				return $is_allowed;
			} else {
				$is_allowed['authorized'] = false;
				$is_allowed['reason'] = "You do not have the appropriate permissions to view that page";
			}
		}
		return $is_allowed;*/
	} else {
		$is_allowed['authorized'] = true;
		return $is_allowed;
	}
}

function is_home(){
	$current_page = $_SERVER['PHP_SELF'];
	preg_match('/([^\/]+)\.php/',$current_page, $page);
	if ( $page[1] == 'default' ) {
		return true;
	} else return false;
}

function is_submit(){
	$current_page = $_SERVER['PHP_SELF'];
	preg_match('/([^\/]+)\.php/',$current_page, $page);
	if ( $page[1] == 'submit' ) {
		return true;
	} else return false;
}

function get_report(){
	global $bootstrap;
	
	if( $_GET['type'] && $_GET['action'] ) {
		echo $bootstrap->get_visualconfidentialreport( $_GET['date'], $_GET['orderby'] );
		return true;	
	}
	
	return false;	
}


?>