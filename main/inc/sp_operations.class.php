<?php

function has_array($array){
	print_r($array);
	foreach( $array as $key=>$value ) {
		if( is_array($value) ){
			echo "array";
			has_array($value);
		}
		else{
			echo $key . ' = ' . $value;
		}
	}
}
 
function x_two_week_range($date,$format='Y-m-d') {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('sunday this week'.' - 7 days', $ts);
    return array(date($format, $start),
                 date($format, strtotime('saturday next week', $start)));
}

function x_week_range($date,$format='Y-m-d') {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('sunday this week'.' - 7 days', $ts);
    return array(date($format, $start),
                 date($format, strtotime('saturday this week', $start)));
}

function last_week_range( $format='m/d/Y' ){
	$last_week = x_week_range( date('Y-m-d',strtotime('last week')) , $format );
	return $last_week;
}

function week_dates($date,$format='Y-m-d'){
	$days_of_week = array("","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	
	if( is_array($date) ){
		if( array_search( ucfirst( strtolower( $date[1] ) ), $days_of_week ) ) {
			$day = $date[1];
		}
		$ts = strtotime($date[0]);
		$start = (date('w', $ts) == 0) ? $ts : strtotime($days_of_week[1].' this week'.' - 7 days', $ts);
	} else { 
		$ts = strtotime($date);
		$start = (date('w', $ts) == 0) ? $ts : strtotime($days_of_week[1].' this week'.' - 7 days', $ts);
	}
 
	if ( $day ) {
		if ( $day == "Sunday" ) $work_week[ucfirst( strtolower( $day ) )] = date($format, $start);
		else $work_week[ucfirst( strtolower( $day ) )] = date($format, strtotime($day.' this week', $start));
	} else {
		array_shift( $days_of_week );
		foreach ( $days_of_week as $day ) {
			if ( $day == "Sunday" ) $work_week[ucfirst( strtolower( $day ) )] = date($format, $start);
			else $work_week[ucfirst( strtolower( $day ) )] = date($format, strtotime($day.' this week', $start));
		}
	}

	return $work_week;
	
}
// Converts 4 units of 15 to 4 units of 25
function minute_adjustment($time){
	$time = explode('.',$time);
	$adjusted = ($time[1] / 60) * 10;
	return $time[0] + $adjusted;
}

require_once ('sp_permissions.class.php');
##########################################################################################################
/* Begin the Operations class */

class sp_operations extends sp_permissions {
var $rootSite;
var $loginInfo;
var $operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname;
var $apptInfo;	
var $origApptInfo;
var $cc, $bcc;
public $notification;
var $registeredApps;
var $mysqliHandler;
private $timesheet_data;
var $union;
public $pay_period_hours;

var $employee_time_data;
	
function __construct($loginInfo,$select_db=false){

	if($loginInfo){
		if($this->debug){ $this->debugMsg .= "sp_operations() class constructor initiated". '\r';}
		sp_permissions::__construct($loginInfo,$select_db);
	}
	
	$this->loginInfo = $loginInfo;
	if(!isset($this->mysqliHandler))$this->mysqliHandler();
		
}
// Accepts an array from is_allowed()
// argument format: array('authorized' => '', 'reason' => '' );
// v0.1
public function to_dashboard( $arg ) {
	session_start();
	$_SESSION["authorized"] = $arg['authorized'];
	$_SESSION["reason"] = $arg['reason'];
	header('Location: http://'. $_SERVER['SERVER_NAME'].'/main/default.php');
}

function getRegisteredApps(){ 
	try {
				
	
	$this->mysqliHandler->checkConnection();
	$result = $this->mysqliHandler->getArray("vwSalesPalApps","user_id",$_COOKIE['userInfo']['id']);
		$this->result =  $result;

	if ( $result ) {
		$this->registeredApps = $this->result;
		$this->mysqliHandler->close();
	}
		} catch(Exception $e) {
				die($e->getMesage());
			}
}

public function printRegisteredApps($for=NULL){
	$this->getRegisteredApps();
	
	switch($for){
		// Print results formated for Applications navigation bar
		case "nav":
		if($this->registeredApps > 0 || $this->registeredApps != NULL){
			echo "<ul class=\"sub_nav_list\">";
				$x=0;
				while($row = $this->registeredApps[$x]){
				foreach($row as $key => $value){
						($$key==$key) ? $key="" : $$key = $value; 
						//echo $key." = ".$value."\n";
						}
					$appsRow = "<li class=\"list_app\"><a href=\"?app=".$id."\">".$name."</a></li>";		
					echo $appsRow;
				$x++;
				}
			echo "</ul>";
		} else {
			echo "No data returned";
		}
		break;
		// Print results to an unordered list
		case "list":
		
		if($this->registeredApps > 0 || $this->registeredApps != NULL){
			echo "<ul class=\"sub_list\">";
				$x=0;
				while($row = $this->registeredApps[$x]){
				foreach($row as $key => $value){
						($$key==$key) ? $key="" : $$key = $value; 
						//echo $key." = ".$value."\n";
						}
					$appsRow = "<li class=\"list_app\"><a href=\"?app=".$id."\">".$name."</a></li>";		
					echo $appsRow;
				$x++;
				}
				
				
			echo "</ul>";
		} else {
			echo "No data returned";
		}
		break;
	
		default:
		return (array) $this->registeredApps;		
	}
}

function getNewsFeed(){
	
	/* Prepare a select statement */
 	try {
	$query = "SELECT * FROM salespal_news ORDER BY dateTime DESC LIMIT 4";
	
	$result = $this->mysqliHandler->dbQuery($query);
	
		$x=0;
		while($row = $result){
		$newsFeed[$x] = $row;
			foreach($newsFeed[$x] as $name => $value){$$name = $value; }
			$newsRow = "<h2>".$subject."</h2><img style=\"position:relative;top:3px;\" src=\"images/mini_calendar.png\" />".$dateTime."<div class=\"newsMsg\"><p>".$msg."</p></div>";		
			echo $newsRow;
		$x++;
		}
	
		
	$this->mysqliHandler->close();
	
	} catch(Exception $e) {
			die($e->getMesage());
		}
	
}
	
function addToCounter($fieldModified){
	if(!isset($_SESSION['counter']) || !$_SESSION['counter']){
	session_start();
	$_SESSION['counter']['modifiedCount'] = 0;
	$_SESSION['counter']['nonModifiedCount'] = 0;
	$_SESSION['counter']['totalCount'] = 0;
	}
	
	if($fieldModified == TRUE){
		$_SESSION['counter']['modifiedCount']++;	
	} elseif($fieldModified == FALSE) {
		$_SESSION['counter']['nonModifiedCount']++;
	} 
		$_SESSION['counter']['totalCount']++;
}

function destroyCounterData(){
		/* This session was created by the addToCounter() function */
		session_unset($_SESSION['counter']);
}

function countFieldTypes($type){
			switch($type){
				case "s_": ## String/other field ##
					$this->addToCounter(TRUE);
					break;
				case "i_": ## Integer field ##
					$this->addToCounter(TRUE);	
					break;
				case "b_": ## Blog field ##
					$this->addToCounter(TRUE);
					break;
				case "d_": ## Double/Decimal field ##
					$this->addToCounter(TRUE);
					break;	
				default: ## No match field ##
					$this->addToCounter(FALSE);
					return false;
					break;
			}
}

function modifyTypedFields($type, $name){
			switch($type){
				case "s_": ## String/other field ##
			
			$pattern = "/s_([\w]+)/";
			$replace = "$1";
			$subject = $name;
			$name = preg_replace($pattern, $replace, $subject);
			
			return $name;	
				break;
				case "i_": ## Integer field ##
			
			$pattern = "/i_([\w]+)/";
			$replace = "$1";
			$subject = $name;
			$name = preg_replace($pattern, $replace, $subject);
			
			return $name;	
				break;
				case "b_": ## Blog field ##
			
			$pattern = "/b_([\w]+)/";
			$replace = "$1";
			$subject = $name;
			$name = preg_replace($pattern, $replace, $subject);
			
			return $name;
				break;
				case "d_": ## Double/Decimal field ##
			
			$pattern = "/d_([\w]+)/";
			$replace = "$1";
			$subject = $name;
			$name = preg_replace($pattern, $replace, $subject);
			
			return $name;
				break;	
				default: ## No match field ##
				$name = false;
				return $name;
				break;
			}
}

function insertStmt($tbl,  $data){

$sqlcommand = "INSERT INTO `".$tbl."` (";	
$sqlfields = "";
$sqlvalues = " VALUES (";
$count = (int) count($data);
	$x=0;
	$y=0;
	foreach($data as $name=>$value){
	$type = substr($name, 0, 2);
	$this->countFieldTypes($type);
	}
	
	$modifiedCount = $_SESSION['counter']['modifiedCount'];
	$nonModifiedCount = $_SESSION['counter']['nonModifiedCount'];
	$totalCount = $_SESSION['counter']['totalCount'];
		foreach($data as $name=>$value){
				$type = substr($name, 0, 2);
					$modifiedFields = $this->modifyTypedFields($type, $name);
					$$name = $value;
						if($modifiedFields){
								
								if($y <= $totalCount){
									$sqlfields .= "`".$modifiedFields."`";
									$sqlvalues .= "'".$value."'";
								}
								if($y < ($modifiedCount - 1)){
									$sqlfields .= ", ";
									$sqlvalues .= ", ";
									}
							$y++;		
						} else {
							//echo "FALSE<br />";
							if($x <= $totalCount){
									$sqlfields .= "";
									$sqlvalues .= "";
								}
							}
			$x++;
		}
$sqlfields .= ")";
$sqlvalues .= ")";
$sql = $sqlcommand.$sqlfields.$sqlvalues;
$this->destroyCounterData();
return $sql;

}

public function printNotification(){
	if($this->notification){
	echo $this->notification;
	}
}

public function kill_the_dead($data){
	$args = func_get_args();
	if ( count($args) < 2 ) {
	foreach ( $data['employee'] as $key1=>$array ) {
				
				foreach ( $array as $key2=>$value ) {
					
					if ( $key2 == "hours" && !$value) {
						unset($data['employee'][$key1]);
					}
				}
				
			}
	} else {
		if ( $args[1] == 'confidential_time_entries' ) {
			foreach ( $data as $key=>$value ) {
				if($value == '') { 
					unset($data[$key]);
					
					$removed[] = $key;
				}
				else {
					// Organize the confidential data in an array
						$this_key = explode('-',$key);
						if ( $this_key[0] != 'submitter_id' && $this_key[0] != 'mydate' ) {
							$data_by_day[$this_key[0]][$this_key[2]][$this_key[1]] =  $value;
							$last_value = $value;
						} else {
							$data_by_day[$this_key[0]] = $value;
						}

				}
			}
			
			foreach ( $data_by_day as $key=>$value ) {
				if ( is_array($value) && $key != 'submitter_id' && $key != 'mydate' ) {
					foreach ( $value as $day=>$row_num ) {
						if ( count($row_num) <= 2 ) {
							unset($data_by_day[$key][$day][key($row_num)]);
							unset($data_by_day[$key][$day]);
						}
					}
					if ( count($data_by_day[$key]) == 0 ) unset($data_by_day[$key]);
				}
			}
			$data = $data_by_day;
		}
	}
	return $data;
}

public function get_active_employees(){
	$queryArgs[0] = "id, firstname, lastname ";
	$queryArgs[1] = "vwEmployeeData";
	$queryArgs[2] = "";
	$queryArgs[3] = "";
	$queryArgs[4] = ' WHERE disabled = "N" ORDER BY id';
	
	$employees = $this->mysqliHandler->getArray($queryArgs);
	return $employees;
}

public function get_equipment(){
	$queryArgs[0] = "id, name, equipment_code ";
	$queryArgs[1] = "equipment";
	$queryArgs[2] = "";
	$queryArgs[3] = "";
	$queryArgs[4] = ' ORDER BY equipment_code';
	
	$data = $this->mysqliHandler->getArray($queryArgs);
	return $data;
}

public function get_inventory(){
	$queryArgs[0] = "id, inventory_code, name, quantity";
	$queryArgs[1] = "inventory";
	$queryArgs[2] = "";
	$queryArgs[3] = "";
	$queryArgs[4] = ' ORDER BY name';
	
	$data = $this->mysqliHandler->getArray($queryArgs);
	return $data;
}

public function sign_timesheet($data){
	$datearray = explode('/', $data['mydate']);
	$date = $datearray['2'].'-'.$datearray['0'].'-'.$datearray['1'];
	$date_range = x_week_range($data['mydate']);
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$query = 'UPDATE `'.$system_dbname.'`.`confidential_time_entries` SET is_signed = "y" WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" AND submitter_id = "'.$data['submitter_id'].'"';
	$mysqli->query($query);
	$affected_rows = $mysqli->affected_rows;
	$mysqli->close();
	
	return $affected_rows;
}

public function insert_record($data, $type){
	$datearray = explode('/', $data['mydate']);
	$date = $datearray['2'].'-'.$datearray['0'].'-'.$datearray['1'];
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
				
	$sql = array(); 
	switch($type) {
		case 'punch_clock_entries':
			$data['action'] = ($data['in'])? 'in' : 'out';
			try{
			$query = 'INSERT INTO `'.$system_dbname.'`.`'.$type.'` (`id`, `submitter_id`, `action`) 
			VALUES(NULL,'.$data['submitter_id'].',\''.$data['action'].'\')';

				$mysqli->query($query);
				$mysqli->close();
				return true;
			
			} catch(Exception $e) {
					die($e->getMesage());
				}
			die();
		break;
		case 'time_entries':
			foreach ( $data as $key => $value) {
				if( is_array($value) ){
					foreach ( $value as $emparray){	
				
						$po = ($emparray['perdiam-o'])?'po:'.$emparray['perdiam-o'].':"Perdiam-o";':'';
						$pe = ($emparray['perdiam-e'])?'pe:'.$emparray['perdiam-e'].':"Perdiam-e";':'';
						$holiday = ($emparray['holiday'])?'ho:'.$emparray['holiday'].':"Holiday";':'';
						$workedholiday = ($emparray['worked-holiday'])?'hw:'.$emparray['worked-holiday'].':"Worked Holiday";':'';
						$vacation = ($emparray['vacation'])?'va:'.$emparray['vacation'].':"Vacation";':'';
						$mileage = ($emparray['mileage'])?'mi:'.$emparray['mileage'].':"Mileage";':'';
						$traveltime = ($emparray['traveltime'])?'tt:'.$emparray['traveltime'].':"Travel Time";':'';
						
						$sql[] = '(NULL, '.$data['submitter_id'].', 
											'.$data['job_id'].', 
											'.$emparray['id'].', 
											\'{'.$po.$pe.$holiday.$workedholiday.$vacation.$mileage.$traveltime.'}\', 
											'.$emparray['hours'].', 
											NULL, 
											NULL, 
											"'.$date.'")';
					}
				}
				
			}
					/* Prepare an insert statement */
			try {
				$query = 'INSERT INTO `'.$system_dbname.'`.`time_entries` (`id`, `submitter_id`, `job_id`, `user_id`, `options`, `hours`, `punchin`, `punchout`, `date`) VALUES '.implode(',', $sql);
				$mysqli->query($query);
				$mysqli->close();
				return true;
			
			} catch(Exception $e) {
					die($e->getMesage());
				}
		break;
		case 'confidential_time_entries':

			foreach ( $data as $key => $field_name ) {
				if( is_array($field_name) ){
					foreach ( $field_name as $row_num ){	
						if( is_array( $row_num ) ){
						if( $row_num['id'] == "" || $row_num['id'] == NULL ) $row_num['id'] = 'NULL';
						$sql[] = '('.$row_num['id'].','.$data['submitter_id'].',"'.$row_num['timetype'].'","'.$row_num['jobid'].'",'.$row_num['hours'].',"'.$row_num['date'].'" )';
						}
					}
				}
				
			}
			
					/* Prepare an insert statement */
			try {
				$query = 'INSERT INTO `'.$system_dbname.'`.`confidential_time_entries` (`id`, `submitter_id`, `timetype`, `job_id`, `hours`, `date`) VALUES '.implode(',', $sql);
				$query .= ' ON DUPLICATE KEY UPDATE `timetype`=VALUES(timetype), `job_id`=VALUES(job_id), `hours`=VALUES(hours), `date`=VALUES(date);';	
				$mysqli->query($query);
				$mysqli->close();
				return true;
			
			} catch(Exception $e) {
					die($e->getMesage());
				}
		break;
		case 'equipment':
			foreach ( $data as $key => $value) {
				if( is_array($value) ){
					foreach ( $value as $emparray){	
				
						$po = ($emparray['perdiam-o'])?'po:'.$emparray['perdiam-o'].':"Perdiam-o";':'';
						$pe = ($emparray['perdiam-e'])?'pe:'.$emparray['perdiam-e'].':"Perdiam-e";':'';
						$holiday = ($emparray['holiday'])?'ho:'.$emparray['holiday'].':"Holiday";':'';
						$workedholiday = ($emparray['worked-holiday'])?'hw:'.$emparray['worked-holiday'].':"Worked Holiday";':'';
						$vacation = ($emparray['vacation'])?'va:'.$emparray['vacation'].':"Vacation";':'';
						$mileage = ($emparray['mileage'])?'mi:'.$emparray['mileage'].':"Mileage";':'';
						$traveltime = ($emparray['traveltime'])?'tt:'.$emparray['traveltime'].':"Travel Time";':'';
						
						$sql[] = '(NULL, '.$data['submitter_id'].', 
											'.$data['job_id'].', 
											'.$emparray['id'].', 
											\'{'.$po.$pe.$holiday.$workedholiday.$vacation.$mileage.$traveltime.'}\', 
											'.$emparray['hours'].', 
											NULL, 
											NULL, 
											"'.$date.'")';
					}
				}
				
			}
					/* Prepare an insert statement */
			try {
				$query = 'INSERT INTO `'.$system_dbname.'`.`time_entries` (`id`, `submitter_id`, `job_id`, `equipment_id`, `options`, `hours`, `punchin`, `punchout`, `date`) VALUES '.implode(',', $sql);
				$mysqli->query($query);
				$mysqli->close();
				return true;
			
			} catch(Exception $e) {
					die($e->getMesage());
				}
		break;
		case 'inventory_checkout':
			$query = 'SELECT checkout_id FROM `'.$system_dbname.'`.`inventory_checkout` ORDER BY checkout_id DESC LIMIT 0,1';
			$result = $mysqli->query($query);
			
			$row = $result->fetch_row();
			if($row[0] < 1) $new_checkout_id = 1;
			else $new_checkout_id = $row[0] + 1;

			foreach ( $data as $key => $value) {
				if( is_array($value) ){
					foreach ( $value as $dataarray){	
						
						$sql[] = '(NULL, 
								"'.$new_checkout_id.'", 
								"'.$dataarray['inventory_id'].'", 
								"'.$data['submitter_id'].'",
								"'.$data['job_id'].'",
								"'.$data['superintendant_id'].'", 
								"'.$data['receiving_user_id'].'",
								"'.$dataarray['name'].'",
								"'.$dataarray['quantity'].'",
								"'.$date.'"      
								)';
					}
				}
				
			}
					/* Prepare an insert statement */
			try {
				$query = 'INSERT INTO `'.$system_dbname.'`.`inventory_checkout` (`id`, `checkout_id`, `inventory_id`, `submitter_id`, `job_id`, `superintendant_id`, `receiving_user_id`, `name`, `quantity`, `date`) VALUES '.implode(',', $sql);
				$mysqli->query($query);
				$mysqli->close();
				return true;
			
			} catch(Exception $e) {
					die($e->getMesage());
				}
		break;
	}
	}

public function get_timesheets($datatype, $data=false){
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
	$listview_count = '30';
	switch($datatype){
		case "date":
		if(!$data){
		try {
			$x=0;
			$query = 'SELECT job_id, date, count(*) as count FROM `time_entries` WHERE submitter_id = '.$this->userInfo['id'].' AND `date` <= CURDATE() + INTERVAL 5 DAY GROUP By date Order by date DESC LIMIT 0 ,'.$listview_count.';';
		
			$result = $mysqli->query($query);
			if ( $result->num_rows < 1 ) echo '<li>No time entries present</li>';
			while($row = $result->fetch_assoc()){
				$this->timesheet_data['date'][$x] = format_output($row);
				$x++;
			}
			$mysqli->close();
			return $this->timesheet_data['date'];
			
			} catch(Exception $e) {
					die($e->getMesage());
			}
	
		} else {
			try {
			$x=0;
			$query = 'SELECT * FROM `vwTimeEntries` WHERE `date` = \''.$data.'\' AND submitter_id = '.$this->userInfo['id'].' ORDER BY job_id DESC, firstname, lastname LIMIT 0 ,'.$listview_count.';';
			$result = $mysqli->query($query);
			while($row = $result->fetch_assoc()){
				$this->timesheet_data['date'][$x] = format_output($row);
				$x++;
			}
			$mysqli->close();
			return $this->timesheet_data['date'];
			
			} catch(Exception $e) {
					die($e->getMesage());
			}
		}
		break;
		
		case "job_id":
		if(!$data){
			try {
			$x=0;
			$query = 'SELECT job_id, count(*) as count FROM `time_entries` WHERE submitter_id = '.$this->userInfo['id'].' GROUP By job_id Order by job_id DESC LIMIT 0 , '.$listview_count.';';
			$result = $mysqli->query($query);
			if ( $result->num_rows < 1 ) echo '<li>No time entries present</li>';
			while($row = $result->fetch_assoc()){
				$this->timesheet_data['job_id'][$x] = format_output($row);
				$x++;
			}
			$mysqli->commit();
			$mysqli->close();
			return $this->timesheet_data['job_id'];
			
			} catch(Exception $e) {
					die($e->getMesage());
			}
		} else {
			try {
				$x=0;
				$query = 'SELECT * FROM `vwTimeEntries` WHERE `job_id` = \''.$data.'\' ORDER BY date DESC, firstname, lastname LIMIT 0 ,'.$listview_count.';';
				$result = $mysqli->query($query);
				if (!$result->fetch_assoc()) echo '<li>No time entries present</li>';
				while($row = $result->fetch_assoc()){
					//$row['date']= get_date($row['date']);
					$this->timesheet_data['job_id'][$x] = format_output($row);
					
					$x++;
				}
				$mysqli->close();
				return $this->timesheet_data['job_id'];
				
				} catch(Exception $e) {
						die($e->getMesage());
				}
		}
		break;
		
		case "user_id":
		if(!$data){
			try {
			$x=0;
			$query = 'SELECT id, user_id, count(*) as count FROM `time_entries` WHERE submitter_id = '.$this->userInfo['id'].' GROUP By user_id LIMIT 0 , '.$listview_count.';';
			$result = $mysqli->query($query);
			while($row = $result->fetch_assoc()){
				$this->timesheet_data['user_id'][$x] = format_output($row);
				$x++;
			}
			$mysqli->commit();
			$mysqli->close();
			return $this->timesheet_data['user_id'];
			
			} catch(Exception $e) {
					die($e->getMesage());
			}
		} else {
			try {
			$x=0;
			$query = 'SELECT * FROM `vwTimeEntries` WHERE `user_id` = \''.$data.'\' LIMIT 0 ,'.$listview_count.';';
			$result = $mysqli->query($query);
			while($row = $result->fetch_assoc()){
				$this->timesheet_data['user_id'][$x] = format_output($row);
				$x++;
			}
			$mysqli->close();
			return $this->timesheet_data['user_id'];
			
			} catch(Exception $e) {
					die($e->getMesage());
			}
		}
		break;
		
	}
}

public function get_employees_by_jobid ($job_id) {
	$last_week = last_week_range('Y-m-d');
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT distinct te.user_id, u.firstname, u.lastname 
				FROM '.$system_dbname.'.time_entries te
				join user u
				on te.user_id = u.id
				WHERE job_id = "'.$job_id.'" AND
				date >= "'.$last_week[0].'" AND
				date <= "'.$last_week[1].'"
				ORDER BY u.firstname, u.lastname';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$employee_list[$x] = '
						<input class="employee_checkbox" id="'. $row['user_id'] .'" name="employee['. $x .'][full_name]" value="'. trim(ucfirst(strtolower($row['firstname']))).' '. trim(ucfirst(strtolower($row['lastname']))) .'" data-theme="c" type="checkbox">
						<label class="'. $row['user_id'] .'" for="'. $row['user_id'] .'" ><div class="ui-block-a"> '. trim(ucfirst(strtolower($row['firstname']))).' '. trim(ucfirst(strtolower($row['lastname']))) .'</div>
							<div style="float:right;"><span id="'. $row['user_id']  .'-display-hours"></span>
							<span style="color:orange;font-size:.70em;" id="'. $row['user_id']  .'-display-options"></span></div>
							<input type="hidden" name="employee['. $x  .'][id]" value="'. $row['user_id']  .'" />
							<input type="hidden" id="'. $row['user_id']  .'-hours" name="employee['. $x  .'][hours]" />
							<input type="hidden" id="'. $row['user_id']  .'-perdiam-o" name="employee['. $x  .'][perdiam-o]" />
							<input type="hidden" id="'. $row['user_id']  .'-perdiam-e" name="employee['. $x  .'][perdiam-e]" />
							<input type="hidden" id="'. $row['user_id']  .'-holiday" name="employee['. $x  .'][holiday]" />
							<input type="hidden" id="'. $row['user_id']  .'-vacation" name="employee['. $x  .'][vacation]" />
							<input type="hidden" id="'. $row['user_id']  .'-mileage" name="employee['. $x  .'][mileage]" />
							<input type="hidden" id="'. $row['user_id']  .'-traveltime" name="employee['. $x  .'][traveltime]" />
						</label>
					';					
					$employee_list['data'][$x] = $row;
					$x++;
				}
				$mysqli->close();
	


	return $employee_list;
}

public function get_submitter_by_jobid ($job_id) {
	$last_week = last_week_range('Y-m-d');
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT te.submitter_id, u.firstname, u.lastname
				FROM '.$system_dbname.'.time_entries te
				JOIN user u
				ON te.submitter_id = u.id
				WHERE job_id = "'.$job_id.'"
				ORDER BY te.id DESC
				LIMIT 0,1';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$data = $row;
					//$submitter_fullname = trim(ucfirst(strtolower($row['firstname']))).' '. trim(ucfirst(strtolower($row['lastname'])));
				}
				$mysqli->close();
	
	return $data;
}

public function get_lastsubmitted_emp ($submitter_id) {
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT date FROM `'.$system_dbname.'`.`vwTimeEntries` WHERE submitter_id = \''.$submitter_id.'\' ORDER BY date DESC LIMIT 1;';
	$result = $mysqli->query($query);
	$date = $result->fetch_assoc();

	$query = 'SELECT DISTINCT user_id, firstname, lastname, job_id FROM `'.$system_dbname.'`.`vwTimeEntries` WHERE `submitter_id` = \''.$submitter_id.'\' AND date = \''.$date['date'].'\' ORDER BY date DESC, firstname, lastname ;';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					
					$employee_list[$x] = '
						<input class="employee_checkbox" id="'. $row['user_id'] .'" name="employee['. $x .'][full_name]" value="'. trim(ucfirst(strtolower($row['firstname']))).' '. trim(ucfirst(strtolower($row['lastname']))) .'" data-theme="c" type="checkbox">
						<label class="'. $row['user_id'] .'" for="'. $row['user_id'] .'" ><div class="ui-block-a"> '. trim(ucfirst(strtolower($row['firstname']))).' '. trim(ucfirst(strtolower($row['lastname']))) .'</div>
							<div style="float:right;"><span id="'. $row['user_id']  .'-display-hours"></span>
							<span style="color:orange;font-size:.70em;" id="'. $row['user_id']  .'-display-options"></span></div>
							<input type="hidden" name="employee['. $x  .'][id]" value="'. $row['user_id']  .'" />
							<input type="hidden" id="'. $row['user_id']  .'-hours" name="employee['. $x  .'][hours]" />
							<input type="hidden" id="'. $row['user_id']  .'-perdiam-o" name="employee['. $x  .'][perdiam-o]" />
							<input type="hidden" id="'. $row['user_id']  .'-perdiam-e" name="employee['. $x  .'][perdiam-e]" />
							<input type="hidden" id="'. $row['user_id']  .'-holiday" name="employee['. $x  .'][holiday]" />
							<input type="hidden" id="'. $row['user_id']  .'-vacation" name="employee['. $x  .'][vacation]" />
							<input type="hidden" id="'. $row['user_id']  .'-mileage" name="employee['. $x  .'][mileage]" />
							<input type="hidden" id="'. $row['user_id']  .'-traveltime" name="employee['. $x  .'][traveltime]" />
						</label>
					';
					$employee_list['data'][$x] = $row;
					$x++;
				}
				$mysqli->close();
	


	return $employee_list;
}

public function get_confidential_time_week_hours ( $submitter_id, $date ) {
	$format = 'Y-m-d';
	$date_range = x_week_range($date,$format);
	$week_days = week_dates($date,$format);
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT cte.id, cte.submitter_id, cte.timetype, cte.job_id, cte.hours, cte.date, cte.is_signed  
	FROM `'.$system_dbname.'`.`confidential_time_entries` cte
	WHERE submitter_id = \''.$submitter_id.'\' 
	AND date >= "'.$date_range[0].'" 
	AND date <= "'.$date_range[1].'" '.$condition .' 
	ORDER BY date ASC, id ASC;';

	$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$week_day_list[$row['date']][] = $row;
					$x++;
				}
				$mysqli->close();
	


	return $week_day_list;
}

function get_inventory_import_data($date=0,$echo=0) {
	$format = 'm/d/y';
	$date_range = explode('|',$date);
	foreach ( $date_range as $key=>$date ) {
		$date_range[$key] = date($format,strtotime($date));
	}
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
	if($date > 0 ) $query = 'SELECT * FROM '.$system_dbname.'.vwImportInventoryData WHERE Transaction_Date >= "'.$date_range[0].'" AND Transaction_Date <= "'.$date_range[1].'" '.$condition .';';
	else $query = 'SELECT * FROM vwImportInventoryData';
//	echo $query; die();
	$result = $mysqli->query($query);
	$x=0;
		
	while($row_data = $result->fetch_assoc()){
		$data[] = $row_data;
	}
	
	$mysqli->close();
	return $data;

}

function get_time_import_data($date=0,$echo=0) {
	$format = 'Y/m/d';
	$date_range = x_week_range($date,$format);
	$week_days = week_dates($date,$format);
	$this->get_pay_period_hours( date("Y-m-d",strtotime($date_range[1])) );
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
	if($date > 0 ) $query = 'SELECT * FROM '.$system_dbname.'.vwImportDataAll WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" '.$condition .' AND date != "00/00/00";';
	else $query = 'SELECT * FROM vwImportDataAll';
	//echo $query; die();
	$result = $mysqli->query($query);
	$x=0;
	
	// Combine all records into an array: 
	// Array structure: $array[User ID][End of pay period date][Count][Field Name][Value]
	
	while($row_data = $result->fetch_assoc()){
		$row_data['date'] = date('m/d/y',strtotime($row_data['date']));
		$range = x_week_range($row_data['date'],'Ymd');
		$week_end = $range[1];
		foreach ($row_data as $key=>$value) {
			$current_user_id = $row_data['user_id'];
			if($current_user_id != $previous_user_id) $x=0;
			$previous_user_id = $current_user_id;
			$row_week_end = x_week_range($row_data['date'], 'Ymd');
			if($row_week_end[1] != $previous_week_end[1]) {
				$previous_week_end[1] = $row_week_end[1];
			} 
			$data[$row_data['user_id']][$week_end][$x][$key] = $value;
			
		}
			$x++;
	}
	unset($result);
	
	// Get union data
	$this->get_union_data();

	$mysqli->close();
	return $data;
}

function get_union_data(){
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	
	// Get union data
	$query = 'SELECT * FROM '.$system_dbname.'.union';
	$result = $mysqli->query($query);
	while ( $union = $result->fetch_assoc()) {
		$this->union[$union['name']] = $union;
	}

	$mysqli->close();
}

function get_pay_period_hours($date) {
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	
	// Get pay period data
	$query = 'SELECT * FROM '.$system_dbname.'.options WHERE meta_date = "'.$date.'"';
	$result = $mysqli->query($query);
	while ( $data = $result->fetch_assoc()) {
		$pay_period_hours = $data;
	}
	if(!$pay_period_hours) {
		$query = 'SELECT * FROM '.$system_dbname.'.options WHERE name = "Default"';
		$result = $mysqli->query($query);
		while ( $data = $result->fetch_assoc()) {
			$pay_period_hours = $data;
		}
	}
	$mysqli->close();
	
	$this->pay_period_hours = $this->get_pay_period_options($pay_period_hours['meta_value']);
}

function get_time_calc_data($date=0,$echo=0){
	$employee_time_data = $this->get_time_import_data($date,$echo);
	$debug = false;
	$ot_debug = $debug;
	$debug_emp = 1024;
	if($debug) echo "<strong>Debug for Employee #: ".$debug_emp."</strong><br />";
	// Group By week
	foreach($employee_time_data as $uid_array=>$week_end) {			// Array Path: $employee_time_data[$uid_array]
		foreach($week_end as $end_date=>$date_array) {		// Array Path: $employee_time_data[$uid_array][$end_date]
			foreach($date_array as $row_num=>$row_array) {	// Array Path: $employee_time_data[$uid_array][$end_date][$row_num]
			
				// Force Batch Date
				//echo $end_date; die();
				$employee_time_data[$uid_array][$end_date][$row_num]['batch'] = date('mdy',strtotime($end_date));
				//$employee_time_data[$uid_array][$end_date][$row_num]['date'] = date('m/d/y', strtotime($employee_time_data[$uid_array][$end_date][$row_num]['date']));
				if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
					if(!$initial_count){
					 echo "<strong>Initial Count :".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
					 $initial_count = count($employee_time_data[$uid_array][$end_date]);
					 print_r($date_array);
					 }
				}
				// Check for Salaried
				$is_salaried = ( $employee_time_data[$uid_array][$end_date][$row_num]['issalaried'] == 'Y' )? true : false;
				// Set union RT Hours
				$row_union_code = (int) $employee_time_data[$uid_array][$end_date][$row_num]['union_code'];
					if ( $row_union_code > 0 ) { 
						if(is_int( (int) $this->union[$row_union_code]['week_day']) && $this->union[$row_union_code]['week_day'] > 0 && !$is_salaried ){
							$union_weekday_hours = $this->union[$row_union_code]['week_day'];
							$union_match = true;
						} else {
							$union_weekday_hours = '8';
							$union_match = false;
						}
					} else {
						$union_weekday_hours = '8';
						$union_match = false;
					}
				$row_date = $employee_time_data[$uid_array][$end_date][$row_num]['date'];
							
					// Saturday
					if(date("w",strtotime($row_date))==6){
						$day = 'saturday';
						$row_union_paytype = ($this->union[$row_union_code][$day])? $this->union[$row_union_code][$day] : "R";
						$row_paytype = $row_union_paytype;
					}
					// Sunday
					elseif(date("w",strtotime($row_date))==0){
						$day = 'sunday';
						$row_union_paytype = ($this->union[$row_union_code][$day])? $this->union[$row_union_code][$day] : "R";
						$row_paytype = $row_union_paytype;
					}
					else{
						$row_paytype = "R";
					}
					
					// Determine pay type before hour calculations
					// Add row mechanism. Adds row at end of loop
					$rt_only_hours = false;
					$row_options = $this->get_time_options($employee_time_data[$uid_array][$end_date][$row_num]['options']);
					if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
						echo $row_options;
					}
					if( $row_paytype == "D" || $row_paytype == "V" || $row_paytype == "H" ) { 
						$rt_only_hours = true;
						if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
							echo "<br /><strong>rt_only_hours: ".$employee_time_data[$uid_array][$end_date][$row_number]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. "</strong><br />";
						}
					}
					
					if ( is_array( $row_options) ) {
					foreach ( $row_options as $row_option ) {
						if( $row_option['option'] == 'po' || $row_option['option'] == 'pe' ){ 
								$row_count = count($employee_time_data[$uid_array][$end_date]);
								$new_row_count = $row_count + 1;
							if($row_option['option'] == 'po') {
								$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = $row_option['pay_type'];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_rate'] = $employee_time_data[$uid_array][$end_date][$row_num]['pdo'];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['man_hours'] = '';
								$employee_time_data[$uid_array][$end_date][$new_row_count]['phase'] = substr($employee_time_data[$uid_array][$end_date][$new_row_count]['phase'],0,2).'401000';
							}
							if($row_option['option'] == 'pe') {
								$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = $row_options[0]['pay_type'];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_rate'] = $employee_time_data[$uid_array][$end_date][$row_num]['pde'];
								$employee_time_data[$uid_array][$end_date][$new_row_count]['man_hours'] = '';
								$employee_time_data[$uid_array][$end_date][$new_row_count]['phase'] = substr($employee_time_data[$uid_array][$end_date][$new_row_count]['phase'],0,2).'401000';
							}
						} 
						elseif( $row_option['option'] == 'mi' ){
							$row_count = count($employee_time_data[$uid_array][$end_date]);
							$new_row_count = $row_count + 1;
							$mileage_rate = .56;
							$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = $row_option['pay_type'];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_rate'] = $mileage_rate * $row_option['value'];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['man_hours'] = '';
							$employee_time_data[$uid_array][$end_date][$new_row_count]['phase'] = substr($employee_time_data[$uid_array][$end_date][$new_row_count]['phase'],0,2).'401000';
						}
						elseif( $row_option['option'] == 'tt' ){
							$row_count = count($employee_time_data[$uid_array][$end_date]);
							$new_row_count = $row_count + 1;
							$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = $row_option['pay_type'];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_rate'] = $employee_time_data[$uid_array][$end_date][$row_num]['wage_rate'] * $row_option['value'];
							$employee_time_data[$uid_array][$end_date][$new_row_count]['man_hours'] = '';
						}
						elseif($row_options){
							$row_paytype = $row_options[0]['pay_type'];
							if( $row_paytype == "D" || $row_paytype == "V" || $row_paytype == "H" ) { 
									$rt_only_hours = true;
									if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
										echo "<br /><strong>rt_only_hours: ".$employee_time_data[$uid_array][$end_date][$row_number]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. "</strong><br />";
									}
								}
							} 
					}
					}
				// OT Debug	
				if($ot_debug){ $ot_1 = 1; $ot_2 = 2; $ot_3 = 3; $ot_4 = 4; $rt_1 = 1; $rt_2 = 2; $rt_3 = 3; $rt_4 = 4;}
				
				foreach($row_array as $key=>$value) {		// Array Path: $employee_time_data[$uid_array][$end_date][$row_num][$key]
					// This variable counts the records in the set prior to adding OT records
					$row_count = count($employee_time_data[$uid_array][$end_date]);
					switch($key){
						case 'pay_type':
							if($employee_time_data[$uid_array][$end_date][$row_num]) $employee_time_data[$uid_array][$end_date][$row_num][$key] = $row_paytype;
						break;
						case 'man_hours':
							// Create OT row and adjust initial row
							$allowed_daily_RT = (int) $union_weekday_hours; // Regular Time
							if( $row_union_code <= 0 ) { $allowed_weekly_RT = (int) $this->pay_period_hours['nu']['date_hours'][1]; } // Non-union allowed RT
							else { $allowed_weekly_RT = (int) $this->pay_period_hours['u']['date_hours'][1]; } // Union allowed RT
							// Salaried Calculations
							if( $is_salaried ){
								 $employee_time_data[$uid_array][$end_date][$row_num][$key] = '8.00';
								 if( $rt_hours < 40 ) { 
								 	$rt_hours += (!$rt_only_hours)? $employee_time_data[$uid_array][$end_date][$row_num][$key] : 0 ;
									$employee_time_data[$uid_array][$end_date][$row_num]['pay_type'] = 'R'.$rt_1;
								 }
								 	elseif( $rt_hours == 40 ) $max_hours = TRUE;
							}
							// OT1 & OT2
							elseif($value > $allowed_daily_RT && 
									$rt_only_hours != true && 
									$union_match && 
									$rt_hours < $allowed_weekly_RT 
							) {
								// RT hours have not added up to 40 yet
									$OT = $value - $allowed_daily_RT;
									$employee_time_data[$uid_array][$end_date][$row_num][$key] = $allowed_daily_RT;
										// OT1: (Unions only) Cuts the overtime off the top and moves to a new record
										$row_count = count($employee_time_data[$uid_array][$end_date]);
										$new_row_count = $row_count + 1;
											$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
											$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = 'O'.$ot_1;
											$employee_time_data[$uid_array][$end_date][$new_row_count][$key] = $OT;
											$ot_over_allowed_daily_RT = $OT;
											if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
												 echo "<br /><strong>Record # for ".$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. ": ".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
											}
										
										// Add OT/RT hours
										$ot_hours +=  $OT;
										// Add Regular time up to 40 hours
										if($rt_hours <= $allowed_weekly_RT){
											if($rt_hours + ($value - $OT) <= $allowed_weekly_RT) {
												$rt_hours += (!$rt_only_hours)? $allowed_daily_RT : 0 ;
												$employee_time_data[$uid_array][$end_date][$row_num]['pay_type'] = 'R'.$rt_2;
											}  else {
												$rt_leftover = $value - ($rt_hours + $value - $allowed_weekly_RT);
												if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
													echo "<br /><strong>rt_leftover :</strong>".$rt_leftover."<br />";
												}
												$OT = $rt_hours + $value - $allowed_weekly_RT - $ot_over_allowed_daily_RT;
												$ot_hours += $OT;
												// OT2: Writes overtime at the end of the loop
												$row_count = count($employee_time_data[$uid_array][$end_date]);
												$new_row_count = $row_count + 1;
													$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
													$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = 'O'.$ot_2;
													$employee_time_data[$uid_array][$end_date][$new_row_count][$key] = $OT;
													if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
														echo "<br /><strong>Record # for ".$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. ": ".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
													}
												// Remove the RT record that was converted to OT
												if($rt_leftover > 0){
													$rt_hours += (!$rt_only_hours)? $rt_leftover : 0 ;
													$employee_time_data[$uid_array][$end_date][$row_num]['man_hours'] = $rt_leftover;
													$employee_time_data[$uid_array][$end_date][$row_num]['pay_type'] = 'R'.$rt_3;
												} else unset($employee_time_data[$uid_array][$end_date][$row_num]);
											}
										}
							} 
							// Regular Time
							elseif( !$rt_only_hours ) {
								$rt_hours += (!$rt_only_hours)? $value : 0 ;
								$employee_time_data[$uid_array][$end_date][$row_num]['pay_type'] = 'R'.$rt_4;
								if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
									 echo "<br /><strong>rt_hours</strong> ".$allowed_weekly_RT."<br />";
								}
								// If weekly regular time exceeded, dump to overtime
								// OT3 & OT4
								if($rt_hours >= $allowed_weekly_RT) {
									
										$OT = $rt_hours - $allowed_weekly_RT;
										$ot_hours += $OT;
										$employee_time_data[$uid_array][$end_date][$row_num][$key] = $value - $OT;
										$rt_hours -= $OT;
										
										$row_count = count($employee_time_data[$uid_array][$end_date]);
										$new_row_count = $row_count + 1;
										if($value != $OT) {
											// OT3: Writes overtime at the end of the loop
											$employee_time_data[$uid_array][$end_date][$new_row_count] = $employee_time_data[$uid_array][$end_date][$row_num];
											$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type'] = 'O'.$ot_3;
											$employee_time_data[$uid_array][$end_date][$new_row_count][$key] = $OT;
											if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
												echo "<br /><strong>Record # for ".$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. ": ".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
												print_r($employee_time_data[$uid_array][$end_date][$new_row_count]);
											}
										} else { 
											// OT4: Writes overtime in for the current record hours
												$employee_time_data[$uid_array][$end_date][$row_num]['pay_type'] = 'O'.$ot_4;
												$employee_time_data[$uid_array][$end_date][$row_num][$key] = $OT;
											$row_to_remove = $row_num.' '.$employee_time_data[$uid_array][$end_date][$row_num]['man_hours'];
											if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
												 echo "<br /><strong>Record # for ".$employee_time_data[$uid_array][$end_date][$new_row_count]['pay_type']. "-" .$employee_time_data[$uid_array][$end_date][$new_row_count]['date']. ": ".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
											}
										}
								}
							}
							// Does not add Vacation and Holiday pay into the overtime calculation
							elseif ( $rt_only_hours ) {
								if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
									 echo "<br /><strong>rt_only_hours</strong> ".$value."<br />";
								}
								$add_to_rt_hours += $value; 
							}
						break;
					} // $key=>$value switch
					
											
				}										// Array Path: $employee_time_data[$uid_array][$end_date][$row_num][$key]
				// Set salaried works with more than 40 hours for the week to 0
						if ( $is_salaried && $max_hours ) {  $employee_time_data[$uid_array][$end_date][$row_num]['man_hours'] = 0; }
					// Remove rows with 0 hours	
						if ( $employee_time_data[$uid_array][$end_date][$row_num]['man_hours'] == "0.00" ) { 
							if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
								echo "<br /><strong>Deleted Row:</strong> ";
								print_r( $employee_time_data[$uid_array][$end_date][$row_num])."<br />";
							}
							if($employee_time_data[$uid_array][$end_date][$row_num]['man_hours'] == "0.00" ) { $employee_time_data[$uid_array][$end_date][$row_num] = array(); }
						}
			
			}	
													// Array Path: $employee_time_data[$uid_array][$end_date][$row_num]
			
			
			unset($max_hours);
			unset($ot_hours);
			unset($add_to_rt_hours);
			unset($rt_hours);
			
			if($employee_time_data[$uid_array][$end_date][$row_num]['employee_code'] == $debug_emp && $debug){
				 echo "<br /><strong>Total Records in Final Array: ".count($employee_time_data[$uid_array][$end_date])."</strong><br />";
				 print_r($employee_time_data[$uid_array][$end_date]);
			}
		}												// Array Path: $employee_time_data[$uid_array][$end_date]
	}													// Array Path: $employee_time_data[$uid_array]
	//$this->employee_time_data = $employee_time_data;
	return $employee_time_data;
}

public $ot_hours;
public $rt_hours;

public function get_confidential_time_data($date){
	$format = 'Y-m-d';
	$date_range = x_two_week_range($date,$format);
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
	$query = 'SELECT * FROM '.$system_dbname.'.vwConfidentialTimeReport WHERE date >= date_format("'.$date_range[0].'", "%m/%d/%y") AND date <= date_format("'.$date_range[1].'", "%m/%d/%y");'; 
	$result = $mysqli->query($query);
	while( $row = $result->fetch_assoc() ) {
		$data[] = $row;
	}
	return $data;
}

public function calc_hours($employee_time_data,$hours){
	$run=true;
if($run == true){
	
	// Set union RT Hours
				$row_union_code = (int) $employee_time_data['union_code'];
					if ( $row_union_code > 0 ) { 
						if(is_int( (int) $this->union[$row_union_code]['week_day']) && $this->union[$row_union_code]['week_day'] > 0){
							$union_weekday_hours = $this->union[$row_union_code]['week_day'];
							$union_match = true;
						} else {
							$union_weekday_hours = '8';
							$union_match = false;
						}
					} else {
						$union_weekday_hours = '8';
						$union_match = false;
					}
					$row_date = $employee_time_data[$uid_array][$end_date][$row_num]['date'];
					$row_options = $this->get_time_options($employee_time_data['options']);
																					
					// Saturday
					if(date("w",strtotime($row_date))==6){
						$day = 'saturday';
						$row_union_paytype = ($employee_time_data[$uid_array][$end_date][$row_num]['options'] > 0)? $this->union[$row_union_code][$day] : "R";
						$row_paytype = $row_union_paytype;
					}
					// Sunday
					elseif(date("w",strtotime($row_date))==0){
						$day = 'sunday';
						$row_union_paytype = ($employee_time_data[$uid_array][$end_date][$row_num]['options'] > 0)? $this->union[$row_union_code][$day] : "R";
						$row_paytype = $row_union_paytype;
					}
					elseif ( $row_options ) {
						$row_paytype = $row_options[0]['pay_type'];
						if( $row_paytype == "D" || $row_paytype == "V" || $row_paytype == "H" ) $rt_only_hours = true;
					}
					else{
						$row_paytype = "R";
					}
					
					// Determine pay type before hour calculations
					$rt_only_hours = false;
				// OT Debug	
				$ot_debug = false;
				if($ot_debug){ $ot_1 = 1; $ot_2 = 2; $ot_3 = 3; $ot_4 = 4; }

							// Create OT row and adjust initial row
							$allowed_daily_RT = (int) $union_weekday_hours; // Regular Time
							if( $row_union_code <= 0 ) { $allowed_weekly_RT = (int) $this->pay_period_hours['nu']['date_hours'][1]; } // Non-union allowed RT
							else { $allowed_weekly_RT = (int) $this->pay_period_hours['u']['date_hours'][1]; } // Union allowed RT
							// Overtime 
							if($hours > $allowed_daily_RT && $row_paytype != "D" && $union_match) {
								// RT hours have not added up to 40 yet
								// OT1: (Unions only) Cuts the overtime off the top and moves to a new record
								$OT = $hours - $allowed_daily_RT;
									// Add OT/RT hours
										$this->ot_hours +=  $OT;
										// Add Regular time up to 40 hours
										if($rt_hours <= $allowed_weekly_RT){
											if($this->rt_hours + ($hours - $OT) <= $allowed_weekly_RT) {
												$this->rt_hours += $allowed_daily_RT;
											}  else {
												// OT2: Writes overtime at the end of the loop
												$rt_leftover = $hours - ($this->rt_hours + $hours - $allowed_weekly_RT);
												$OT = $this->rt_hours + $hours - $allowed_weekly_RT;
												$this->ot_hours += $OT;
											}
										}
							} 
							
							// Regular Time
							elseif($row_paytype != "D") {
								if( !$rt_only_hours ) {
									$this->rt_hours += $hours;
									// If weekly regular time exceeded, dump to overtime
									if($this->rt_hours > $allowed_weekly_RT) {
										
											$OT = $this->rt_hours - $allowed_weekly_RT;
											$this->ot_hours += $OT;
											$this->rt_hours -= $OT;
									}
								// Does not add Vacation and Holiday pay into the overtime calculation
								elseif ( $rt_only_hours ) {
									$add_to_rt_hours += $value; 
								}
								}
							}


					
}
	return $hours;
}

public function get_time_import_data_csv($date) {
	$end_date = x_week_range($date,$format='m-d-Y');
	
	//$this->get_time_import_data($date, 0);
	$employee_time_data = $this->get_time_calc_data($date,$echo=0);
	
	$dir = 'reports/';
	$filename = 'import-'.$end_date[1].'.csv';
	$file_url = $dir . $filename;
	
	$fp = fopen($filename, 'w+');

	$echo = false;
	foreach($employee_time_data as $uid_array=>$week_end) {			// Array Path: $employee_time_data[$uid_array]
		foreach($week_end as $end_date=>$date_array) {		// Array Path: $employee_time_data[$uid_array][$end_date]
			foreach($date_array as $row_num=>$row_array) {	// Array Path: $employee_time_data[$uid_array][$end_date][$row_num]
				unset($row_array['submitter_id']);
					unset($row_array['user_id']);
					unset($row_array['union_code']);
					unset($row_array['options']);
					unset($row_array['pdo']);
					unset($row_array['pde']);
					unset($row_array['wage_rate']);
					unset($row_array['id']);
					unset($row_array['issalaried']);
				if(count($row_array)> 0)fputcsv($fp,$row_array);
			}
		}
	}
		
	fclose($fp);
	return $file_url;
}

public function get_confidential_time_import_data_csv($date) {
	$end_date = x_week_range($date,$format='m-d-Y');
	
	$confidential_time_data = $this->get_confidential_time_data($date,$echo=0);
	
	$dir = 'reports/';
	$filename = 'confidential_time_import-'.$end_date[1].'.csv';
	$file_url = $dir . $filename;
	
	$fp = fopen($filename, 'w+');

	$echo = false;
	foreach($confidential_time_data as $row_num=>$row_array) {			// Array Path: $employee_time_data[$uid_array]
		fputcsv($fp,$row_array);
	}
		
	fclose($fp);
	return $file_url;
}

public function get_inventory_import_data_csv($date) {
	$inventory_data = $this->get_inventory_import_data($date,$echo=0);
	
	$format = 'm/d/y';
	$date_range = explode('|',$date);
	foreach ( $date_range as $key=>$date ) {
		$date_range[$key] = date($format,strtotime($date));
	}
	
	$filename_date_range = date('mdy', strtotime($date_range[0]));
	$filename_date_range .= "-" . date('mdy', strtotime( $date_range[1]));
	
	

	$dir = 'reports/';
	$filename = 'inventory-import-'.$filename_date_range.'.csv';
	$file_url = $dir . $filename;
	
	$fp = fopen($filename, 'w+');

	$echo = false;
	foreach( $inventory_data as $row_key => $row_array ) {	
		fputcsv($fp,$row_array);
	}
		
	fclose($fp);
	return $file_url;
}

public function get_time_import_report($date){
		$employee_time_data = $this->get_time_calc_data($date,$echo=0);

		foreach($employee_time_data as $uid_array=>$week_end) {			// Array Path: $employee_time_data[$uid_array]
		$formated_date = date('m/d/Y', strtotime(key($week_end)));
		if( $payroll_date != $formated_date ){
			$payroll_date = $formated_date;
		}
		foreach($week_end as $end_date=>$date_array) {		// Array Path: $employee_time_data[$uid_array][$end_date]
					
			foreach($date_array as $row_num=>$row_array) {	// Array Path: $employee_time_data[$uid_array][$end_date][$row_num]
					$te_id = $row_array['id'];
					$user_id = $row_array['user_id'];
					
					unset($row_array['submitter_id']);
					unset($row_array['user_id']);
					unset($row_array['union_code']);
					unset($row_array['options']);
					unset($row_array['pdo']);
					unset($row_array['pde']);
					unset($row_array['wage_rate']);
					unset($row_array['id']);
					unset($row_array['issalaried']);
				$report_data .= '<tr>';
			
				foreach ($row_array as $key=>$value){		// Array Path: $employee_time_data[$uid_array][$end_date][$row_num][$key]
					if(count($th) < count($row_array) ) $th[] = $key;
						//if($key == 'man_hours')$report_data .= '<td><a href="time-management.php?id='.$te_id.'" target="_blank" >'.$value.'</a></td>';
						if($key == 'employee_code') $report_data .= '<td><a href="employees.php?id='.$user_id.'" target="_blank" >'.$value.'</a></td>';
						elseif($key == 'date')$report_data .= '<td><a href="time-management.php?id='.$te_id.'" target="_blank" >'.$value.'</a></td>';
						else $report_data .= '<td>'.$value.'</td>';
					}
				$report_data .= '</tr>';
			}
			
		}
		
	}
	$report_head .= '<table class="report import-report" cellpadding="0" cellspacing="0" >';
	$report_head .= '<thead>';
	foreach ( $th as $column_name ) {
		$report_head .= '<th>'.$column_name.'</th>';
	}
	$report_head .= '</thead>';
	$report_end .= '</table>';


	return $report_head.$report_data.$report_end;
}

public function get_confidential_time_import_report($date){
	$end_date = x_week_range($date,$format='m-d-Y');

	//$this->get_time_import_data($date, 0);
	$confidential_time_data = $this->get_confidential_time_data($date,$echo=0);
	
	//print_r($confidential_time_data); 
	//die();

	foreach( $confidential_time_data as $row ) {			// Array Path: $employee_time_data[$uid_array]
		$report_data .= '<tr>';
		foreach ($row as $key=>$value){		// Array Path: $employee_time_data[$uid_array][$end_date][$row_num][$key]
			if( count($th) < count($row) ) $th[] = $key;
				//if($key == 'man_hours')$report_data .= '<td><a href="time-management.php?id='.$te_id.'" target="_blank" >'.$value.'</a></td>';
				if($key == 'employee_code') $report_data .= '<td><a href="employees.php?id='.$user_id.'" target="_blank" >'.$value.'</a></td>';
				elseif($key == 'date')$report_data .= '<td><a href="time-management.php?id='.$te_id.'" target="_blank" >'.$value.'</a></td>';
				else $report_data .= '<td>'.$value.'</td>';
			}
		$report_data .= '</tr>';
	}
	$report_head .= '<table class="report import-report" cellpadding="0" cellspacing="0" >';
	$report_head .= '<thead>';
	foreach ( $th as $column_name ) {
		$report_head .= '<th>'.$column_name.'</th>';
	}
	$report_head .= '</thead>';
	$report_end .= '</table>';


	return $report_head.$report_data.$report_end;
}

public function get_inventory_import_report($date){
		$inventory_data = $this->get_inventory_import_data($date,$echo=0);
		//print_r($inventory_data);
		
		foreach($inventory_data as $row_num=>$row_array) {	// Array Path: $inventory_data[$uid_array][$end_date][$row_num]

			$report_data .= '<tr>';
		
			foreach ($row_array as $key=>$value){		// Array Path: $inventory_data[$uid_array][$end_date][$row_num][$key]
				if(count($th) < count($row_array) ) $th[] = $key;
					$report_data .= '<td>'.$value.'</td>';
			}
			$report_data .= '</tr>';
		}
			
		
		
		
	$report_head .= '<table class="report import-report" cellpadding="0" cellspacing="0" >';
	$report_head .= '<thead>';
	foreach ( $th as $column_name ) {
		$report_head .= '<th>'.$column_name.'</th>';
	}
	$report_head .= '</thead>';
	$report_end .= '</table>';


	return $report_head.$report_data.$report_end;
}

public function get_visualweeklyreport($date,$condition,$orderby){
	$format = 'Y-m-d';
	$date_range = x_week_range($date,$format);
	$week_days = week_dates($date,$format);
	if(!$this->pay_period_hours) { $this->get_pay_period_hours($date_range[1]); }
	
	// Get union data
	$this->get_union_data();
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	
	// ### Generate report ordered by user_id ### //
	if ($orderby == 'user_id'){
			if(is_array($condition)) $condition = 'AND  '.key($condition).' = "'.$condition[key($condition)].'"';
			$query = 'SELECT * FROM vwTimeEntries WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" '.$condition .' ORDER BY '.$orderby.', job_id DESC, date DESC;';
			//echo $query;
			$result = $mysqli->query($query);
			while($row_data = $result->fetch_assoc()){
				$data[] = $row_data;
			}
		
		// Combine user records to one array
		foreach($week_days as $day=>$date){
			foreach($data as $row ) {
				$total_hours += $row['hours'];
				if(!$employee_records[$row['user_id']][$row['job_id']]['inventory_data']) $employee_records[$row['user_id']]['inventory_data'] = $row;
				if($row['date'] == $date){
					$options = $this->get_time_options($row['options']);
					if($options[0]['option'] && $options[0]['option'] != 'R')$employee_records[$row['user_id']][$row['job_id']][$day]['option'] = $options[0]['option'];
					$employee_records[$row['user_id']][$row['job_id']][$day]['hours'] = $row['hours'];
					$employee_records[$row['user_id']][$row['job_id']][$row['employee_code']][$day]['id'] = $row['id'];
					$employee_records[$row['user_id']][$row['job_id']][$day]['id'] = $row['id'];
				} else {
					$employee_records[$row['user_id']][$row['job_id']][$day][] = false;
				}
				$employee_records[$row['user_id']][$row['job_id']][$day]['total_hours'] += $total_hours;
				unset($total_hours);
			}
		}
		
		//print_r($employee_records); die();
		//echo count($employee_records);
		
		foreach($employee_records as $records_by_job){
			$job_id = $records_by_job[key($records_by_job)]['job_id'];
			$employee_code = $records_by_job['inventory_data']['employee_code'];
			$employee_name = $records_by_job['inventory_data']['firstname']." ".$records_by_job['inventory_data']['lastname'];
			$user_id = $records_by_job['inventory_data']['user_id'];
	
			$report .= '
			<div class="report-wrapper" >
			<span><strong>Employee Name: ';
			$report .= ($this->get_user_role() < 2 )? '<a href="employees.php?id='.$user_id.'" target="_blank" >': '';
			$report .= $employee_name.'</a></strong></span><br />
			<span><strong>Employee Code: '.$employee_code.'</strong></span>
			';
			$week_days = week_dates($date,'m/d/y');
			$report .= '<table cellspacing="5" cellpadding="0" >';
			$report .= '<thead><tr>';
			$report .= '<th>Job ID</th>';
			foreach ($week_days as $day=>$date ){
				$report .= '<th>'.$day.'<br />'.$date.'</th>';
			}
			$report .= '<th>RT</th>';
			$report .= '<th>OT</th>';
			$report .= '<th>Total</th>';
			$report .= '</tr></thead>';
			unset($records_by_job['inventory_data']);
			// Report Line Items
			foreach($records_by_job as $job_id=>$array){ // Get Hours Certain employee: $employee_records['1035']['Wednesday']['data']
				$report .= '<tr>';
				$report .= '<td>'.$job_id.'</td>';
				
					foreach ( $week_days as $day=>$date ){
						if($array[$day]['option'])$option = '<span class="report-time-options" >('.strtolower($array[$day]['option']).')</span>';
						$report .= '<td><a href="/main/time-management.php?id='.$array[$day]['id'].'" target="_blank" >'.$this->calc_hours($records_by_job[$emp_number]['inventory_data'],$array[$day]['hours']). $option . '</a></td>';
						unset($option);
					}
					
				$report .= '<td>'.$this->rt_hours.'</td>';
				$report .= '<td>'.$this->ot_hours.'</td>';
				$report .= '<td><strong>'.$array[$day]['total_hours'].'</strong></td>';
				$report .= '</tr>';
				
				unset($this->rt_hours);
				unset($this->ot_hours);
			}
			$report .= '</table></div>';
		}
	}
	// ### Generate report ordered by job_id ### //
	elseif($orderby == 'job_id' || $orderby == 'state' ) {
			if(is_array($condition)) $query_condition = 'AND  '.key($condition).' = "'.$condition[key($condition)].'"';
			if( $condition['state'] ){
				$orderby = 'job_id';
				switch($condition['state']){
					case 'al':
						$query_condition = 'AND job_id LIKE "__3%" OR job_id LIKE "__1%"';
					break;
					case 'tx':
						$query_condition = 'AND job_id LIKE "__4%"';
					break;
				}
				
			}
			
			$query = 'SELECT * FROM vwTimeEntries WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" '.$query_condition.' ORDER BY '.$orderby.';';
			$result = $mysqli->query($query);
			while($row_data = $result->fetch_assoc()){
				$data[] = $row_data;
			}
		
		// Combine user records to one array
		foreach($week_days as $day=>$date){
			foreach($data as $row ) {
				$total_hours += $row['hours'];
				if(!$employee_records[$row['job_id']][$row['employee_code']]['inventory_data']) $employee_records[$row['job_id']][$row['employee_code']]['inventory_data'] = $row;
				if($row['date'] == $date){
					$options = $this->get_time_options($row['options']);
					if($options[0]['option'] && $options[0]['option'] != 'R')$employee_records[$row['job_id']][$row['employee_code']][$day]['option'] = $options[0]['option'];
					$employee_records[$row['job_id']][$row['employee_code']][$day]['hours'] = $row['hours'];
					$employee_records[$row['job_id']][$row['employee_code']][$day]['id'] = $row['id'];
				} else {
					$employee_records[$row['job_id']][$row['employee_code']][$day][] = false;
				}
				$employee_records[$row['job_id']][$row['employee_code']][$day]['total_hours'] += $total_hours;
				unset($total_hours);
			}
		
		
		
		}
		foreach($employee_records as $records_by_job){
			$job_id = $records_by_job[key($records_by_job)]['inventory_data']['job_id'];
			$emp_number = key($records_by_job);
	
			$report .= '<div class="report-wrapper" ><span><strong>Job ID: '.$job_id.'</strong><br />
			</span><span><strong>Supervisor: '.$records_by_job[$emp_number]['inventory_data']['submitter_first'].' '.$records_by_job[$emp_number]['inventory_data']['submitter_last'].'</strong></span>';
			$week_days = week_dates($date,'m/d/y');
			$report .= '<table cellspacing="5" cellpadding="0" >';
			$report .= '<thead><tr>';
			$report .= '<th>Emp #</th>';
			$report .= '<th>Emp Name</th>';
			$report .= '<th>Code</th>';
			foreach ($week_days as $day=>$date ){
				$report .= '<th>'.$day.'<br />'.$date.'</th>';
			}
			$report .= '<th>RT</th>';
			$report .= '<th>OT</th>';
			$report .= '<th>Total</th>';
			$report .= '</tr></thead>';
			// Report Line Items
			foreach($records_by_job as $user_record=>$array){ // Get Hours Certain employee: $employee_records['1035']['Wednesday']['data']
				$report .= '<tr>';
				$report .= '<td>'.$user_record.$user_id.'</td>';
				$report .= '<td>';
				$report .= ($this->get_user_role() < 2 )? '<a href="employees.php?id='.$array['inventory_data']['user_id'].'" target="_blank" >'. ucwords(strtolower(trim($array['inventory_data']['firstname'] . ' ' . $array['inventory_data']['lastname']))) . '</a>': 
				'<strong>' . ucwords(strtolower(trim($array['inventory_data']['firstname'] . ' ' . $array['inventory_data']['lastname']))) . '</strong>';
				$report .= '</td>';
				$report .= '<td>'.$array['inventory_data']['code'].'</td>';
				
					foreach ($week_days as $day=>$date ){
						if($array[$day]['option'])$option = '<span class="report-time-options" >('.strtolower($array[$day]['option']).')</span>';
						$report .= '<td><a href="/main/time-management.php?id='.$array[$day]['id'].'" target="_blank" >'.$this->calc_hours($records_by_job[$emp_number]['inventory_data'],$array[$day]['hours']). $option . '</a></td>';
						unset($option);
					}
					
				$report .= '<td>'.$this->rt_hours.'</td>';
				$report .= '<td>'.$this->ot_hours.'</td>';
				$report .= '<td><strong>'.$array[$day]['total_hours'].'</strong></td>';
				$report .= '</tr>';
				
				unset($this->rt_hours);
				unset($this->ot_hours);
			}
			$report .= '</table></div>';
		}
	}
	
	
	
	
	$mysqli->close();
		
	return $report_head.$report;
}

public function get_inactiveemployeesreport( $date ){
	$format = 'Y-m-d';
	$date_range = x_week_range($date,$format);
	$week_days = week_dates($date,$format);

	// Get union data
	$this->get_union_data();
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
	// Employees with time entries for the specified week
	$query = '
			SELECT distinct id, employee_code, firstname, lastname FROM '.$system_dbname.'.vwEmployeesAndTimeEntries 
			WHERE ( 
			((date >= "'.$date_range[0].'") and (date <= "'.$date_range[1].'"))
			and (NOT isnull(hours) and (hours > "0" ))
			and (disabled = "N"))
			order by employee_code
			';
	$result = $mysqli->query($query);
	while($row_data = $result->fetch_assoc()){
		$employees_with_time[] = $row_data;
		$employees_with_time_id[] = $row_data['id'];
	}
	// All employees
	$query = '
			SELECT distinct id, employee_code, code, firstname, lastname, title, union_code, issalaried FROM '.$system_dbname.'.user
			WHERE disabled = "N"
			AND employee_code > "0"
			';
	$result = $mysqli->query($query);
	while($row_data = $result->fetch_assoc()){
		$employees[] = $row_data;
		$employees_ids[] = $row_data['id'];
	}
	
	foreach ( $employees_ids as $key=>$employee_id ) {
		foreach ( $employees_with_time_id as $employee_with_time ) {
			if ( $employee_with_time == $employee_id ) {
				unset($employees[$key]);
			}
		}
	}
	$report .= '<div class="report-wrapper" ><span><strong>Weekly Inactive Employees</strong><br />
	</span><span><strong>Pay Period: '.$date_range[0].' - '.$date_range[1].'</strong></span>';
	$report .= '<table cellspacing="5" cellpadding="0" >';
	$report .= '<thead><tr>';
	$report .= '<th>Emp #</th>';
	$report .= '<th>Code</th>';
	$report .= '<th>Emp Name</th>';
	$report .= '<th>Title</th>';
	$report .= '<th>Union</th>';
	$report .= '<th>Salaried</th>';
	$report .= '</tr></thead>';

	foreach($employees as $employee){
	$employee['union_code'] = ( $employee['union_code'] != 0 || $employee['union_code'] == NULL )? substr($employee['union_code'],0,3) : "";
	// Report Line Items
		$report .= '<tr>';
		$report .= '<td>'.$employee['employee_code'].'</td>';
		$report .= '<td>'.$employee['code'].'</td>';
		$report .= '<td style="white-space:nowrap;">';
		$report .= ($this->get_user_role() < 2 )? '<a href="employees.php?id='.$employee['id'].'" target="_blank" >'. ucwords(strtolower(trim($employee['firstname'] . ' ' . $employee['lastname']))) . '</a>': 
		'<strong>' . ucwords(strtolower(trim($employee['firstname'] . ' ' . $employee['lastname']))) . '</strong>';
		$report .= '</td>';
		$report .= '<td style="white-space:nowrap;">'.$employee['title'].'</td>';
		$report .= '<td>'.substr($employee['union_code'],0,3).'</td>';
		$report .= '<td>'.$employee['issalaried'].'</td>';
		$report .= '</tr>';
	}
	
	$report .= '</table></div>';
	$mysqli->close();
		
	return $report;
}

public function get_visualinventoryreport($date,$condition,$orderby){
	$format = 'Y-m-d';
	$date_range = explode('|',$date);
	foreach ( $date_range as $key=>$date ) {
		$date_range[$key] = date($format,strtotime($date));
	}
	//print_r($date_range); die();
	//$date_range = x_week_range($date,$format);
	//$week_days = week_dates($date,$format);

	// Get union data
	$this->get_union_data();
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	if(is_array($condition)) $condition = 'AND  '.key($condition).' = "'.$condition[key($condition)].'"';
	// ### Generate report ordered by job_id ### //
	switch ( $orderby ) {
		case 'job_id':
				$query = 'SELECT * FROM '.$system_dbname.'.vwInventoryCheckoutReport WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" ORDER BY '.$orderby.' DESC, checkout_id DESC;';
				$result = $mysqli->query($query);
				while($row_data = $result->fetch_assoc()){
					$inventory_records[$row_data['job_id']][] = $row_data;
				}
			//print_r($data);die();
			
			foreach($inventory_records as $job_id=>$records_by_job){
				$superintendant = $inventory_records[$job_id][0]['superintendant'];
				
				$report .= '<div class="report-wrapper" >
				<span><strong>Job ID: </strong>'.$job_id.'</span><br />
				<span><strong>Superintendant: </strong>'.$superintendant.'</span><br />
				<span><strong>Date Range: </strong>'.date('m/d/Y',strtotime($date_range[0])).' to '.date('m/d/Y',strtotime($date_range[1])).'</span><br />
				';
				$report .= '<table cellspacing="5" cellpadding="0" >';
				$report .= '<thead><tr>';
				$report .= '<th>Checkout Date</th>';
				$report .= '<th>Inventory Code</th>';
				$report .= '<th>Name</th>';
				$report .= '<th>Qty</th>';
				$report .= '<th>Received By</th>';
				$report .= '</tr></thead>';
				// Report Line Items
				foreach($records_by_job as $key=>$row){
					$report .= '<tr>';
					$report .= '<td>'.date('m/d/Y', strtotime($row['date'])).'</td>';
					$report .= '<td>'.$row['inventory_code'].'</td>';
					$report .= '<td>'.$row['name'].'</td>';
					$report .= '<td>'.$row['quantity'].'</td>';
					$report .= '<td>'.$row['received_by'].'</td>';
					$report .= '</tr>';
				}
				$report .= '</table></div>';
			}
		break; // <--- Inventory by job_id 
		case 'inventory_code':
				$query = 'SELECT * FROM '.$system_dbname.'.vwInventoryCheckoutReport WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" '.$condition.' ORDER BY '.$orderby.' ASC, date DESC;';
				$result = $mysqli->query($query);
				while($row_data = $result->fetch_assoc()){
					$inventory_records[$row_data['inventory_code']][] = $row_data;
				}
				//print_r($inventory_records); die();
			foreach($inventory_records as $inventory_code=>$records_by_job){
				$inventory_code = $inventory_records[$inventory_code][0]['inventory_code'];
				
				$report .= '<div class="report-wrapper" >
				<span><strong>Inventory Code: </strong>'.$inventory_code.'</span><br />
				<span><strong>Date Range: </strong>'.date('m/d/Y',strtotime($date_range[0])).' to '.date('m/d/Y',strtotime($date_range[1])).'</span><br />
				';
				$report .= '<table cellspacing="5" cellpadding="0" >';
				$report .= '<thead><tr>';
				$report .= '<th>Checkout Date</th>';
				$report .= '<th>Item/Job</th>';
				$report .= '<th>Name</th>';
				$report .= '<th>Qty</th>';
				$report .= '<th>Superintendant</th>';
				$report .= '<th>Received By</th>';
				$report .= '</tr></thead>';
				// Report Line Items
				foreach($records_by_job as $key=>$row){ 
					$report .= '<tr>';
					$report .= '<td>'.date('m/d/Y', strtotime($row['date'])).'</td>';
					$report .= '<td>'.$row['job_id'].'</td>';
					$report .= '<td>'.$row['name'].'</td>';
					$report .= '<td>'.$row['quantity'].'</td>';
					$report .= '<td>'.$row['superintendant'].'</td>';
					$report .= '<td>'.$row['received_by'].'</td>';
					$report .= '</tr>';
				}
				$report .= '</table></div>';
			}
		break; // <--- Inventory by inventory_code
		case 'checkout_id':
				$query = 'SELECT * FROM '.$system_dbname.'.vwInventoryCheckoutReport WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" ORDER BY '.$orderby.' DESC, date DESC;';
				$result = $mysqli->query($query);
				while($row_data = $result->fetch_assoc()){
					$inventory_records[$row_data['checkout_id']][] = $row_data;
				}
			foreach($inventory_records as $checkout_id=>$records_by_job){
				$checkout_id = $inventory_records[$checkout_id][0]['checkout_id'];
				
				$report .= '<div class="report-wrapper" >
				<span><strong>Checkout ID: </strong>'.$checkout_id.'</span><br />
				<span><strong>Date Range: </strong>'.date('m/d/Y',strtotime($date_range[0])).' to '.date('m/d/Y',strtotime($date_range[1])).'</span><br />
				';
				$report .= '<table cellspacing="5" cellpadding="0" >';
				$report .= '<thead><tr>';
				$report .= '<th>Checkout Date</th>';
				$report .= '<th>Job ID</th>';
				$report .= '<th>Inventory Code</th>';
				$report .= '<th>Name</th>';
				$report .= '<th>Qty</th>';
				$report .= '<th>Superintendant</th>';
				$report .= '<th>Received By</th>';
				$report .= '</tr></thead>';
				// Report Line Items
				foreach($records_by_job as $key=>$row){
					$report .= '<tr>';
					$report .= '<td>'.date('m/d/Y', strtotime($row['date'])).'</td>';
					$report .= '<td>'.$row['job_id'].'</td>';
					$report .= '<td>'.$row['inventory_code'].'</td>';
					$report .= '<td>'.$row['name'].'</td>';
					$report .= '<td>'.$row['quantity'].'</td>';
					$report .= '<td>'.$row['superintendant'].'</td>';
					$report .= '<td>'.$row['received_by'].'</td>';
					$report .= '</tr>';
				}
				$report .= '</table></div>';
			}
		break; // <--- Inventory by checkout_id
		case 'superintendant_id':
				$query = 'SELECT * FROM '.$system_dbname.'.vwInventoryCheckoutReport WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" ORDER BY '.$orderby.' DESC, date DESC, job_id DESC;';
				$result = $mysqli->query($query);
				while($row_data = $result->fetch_assoc()){
					$inventory_records[$row_data['superintendant_id']][] = $row_data;
				}
			foreach($inventory_records as $superintendant_id=>$records_by_job){
				$superintendant_id = $inventory_records[$superintendant_id][0]['superintendant_id'];
				$superintendant = $inventory_records[$superintendant_id][0]['superintendant'];
				
				$report .= '<div class="report-wrapper" >
				<span><strong>Superintendant: </strong>'.$superintendant.'</span><br />
				<span><strong>Date Range: </strong>'.date('m/d/Y',strtotime($date_range[0])).' to '.date('m/d/Y',strtotime($date_range[1])).'</span><br />
				';
				$report .= '<table cellspacing="5" cellpadding="0" >';
				$report .= '<thead><tr>';
				$report .= '<th>Checkout Date</th>';
				$report .= '<th>Job ID</th>';
				$report .= '<th>Inventory Code</th>';
				$report .= '<th>Name</th>';
				$report .= '<th>Qty</th>';
				$report .= '<th>Received By</th>';
				$report .= '</tr></thead>';
				// Report Line Items
				foreach($records_by_job as $key=>$row){
					$report .= '<tr>';
					$report .= '<td>'.date('m/d/Y', strtotime($row['date'])).'</td>';
					$report .= '<td>'.$row['job_id'].'</td>';
					$report .= '<td>'.$row['inventory_code'].'</td>';
					$report .= '<td>'.$row['name'].'</td>';
					$report .= '<td>'.$row['quantity'].'</td>';
					$report .= '<td>'.$row['received_by'].'</td>';
					$report .= '</tr>';
				}
				$report .= '</table></div>';
			}
		break; // <--- Inventory by checkout_id
	}

	$mysqli->close();
		
	return $report_head.$report;
}

public function get_visualconfidentialreport($date,$orderby){
	$format = 'm/d/y';
	$date_range = x_two_week_range($date,$format);
	
	include('db.config.php');
	
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	
	// ### Generate report ordered by job_id ### //
	switch ( $orderby ) {
		case 'employee_code':
				$query = 'SELECT * FROM '.$system_dbname.'.vwConfidentialTimeVisualReport WHERE date >= "'.$date_range[0].'" AND date <= "'.$date_range[1].'" ORDER BY '.$orderby.' ASC, date ASC;';
				$result = $mysqli->query($query);
				
				while($row_data = $result->fetch_assoc()){
					$records[$row_data['employee_code']][] = $row_data;
				}
			//return print_array(print_r($records,true));
			
			foreach($records as $employee_code=>$record){
				$employee = $records[$employee_code][0]['fullname'];
				
				$report .= '<div class="report-wrapper" >
				<span><strong>Employee Code: </strong>'.$employee_code.'</span><br />
				<span><strong>Employee: </strong>'.$employee.'</span><br />
				<span><strong>Date Range: </strong>'.date('m/d/Y',strtotime($date_range[0])).' to '.date('m/d/Y',strtotime($date_range[1])).'</span><br />
				';
				$report .= '<table cellspacing="5" cellpadding="0" >';
				$report .= '<thead><tr>';
				//$report .= '<th>Name</th>';
				$report .= '<th>Time Type</th>';
				$report .= '<th>Hours</th>';
				$report .= '<th>Job ID</th>';
				$report .= '<th>Timesheet Signed</th>';
				$report .= '<th>Date</th>';
				$report .= '</tr></thead>';
				// Report Line Items
				foreach($record as $key=>$row){
					$row['is_signed'] = ( $row['is_signed'] == 'y' )? 'Yes' : 'No';
					$report .= '<tr>';
					$report .= '<td>'.ucfirst($row['timetype']).'</td>';
					$report .= '<td>'.$row['hours'].'</td>';
					$report .= '<td>'.$row['job_id'].'</td>';
					$report .= '<td>'.$row['is_signed'].'</td>';
					$report .= '<td>'.date('m/d/Y', strtotime($row['date'])).'</td>';
					$report .= '</tr>';
				}
				$report .= '</table></div>';
			}
		break; // <--- Inventory by employee_code 
		
	}

	$mysqli->close();
		
	return $report_head.$report;
}

public function get_time_options($options){

	$pattern = '/{*([^:]*):([^:]*):"([^"]*)";}*/';
	$match = preg_match_all($pattern,$options,$matches);
	$x=0;
	foreach($matches[1] as $the_match){
		switch($the_match){
			// Paid Holiday
			case "ho":
				// Double Time
				$option[$x]['option'] = $the_match;
				$option[$x]['name'] = $matches[3][$x];
				$option[$x]['pay_type'] = "H";
				$x++;
			break;
			// Worked Holiday
			case "hw":
				// Double Time
				$option[$x]['option'] = $the_match;
				$option[$x]['name'] = $matches[3][$x];
				$option[$x]['pay_type'] = "D";
				$x++;
			break;
			// Vacation Pay
			case "va":
					$option[$x]['option'] = $the_match;
					$option[$x]['name'] = $matches[3][$x];
					$option[$x]['pay_type'] = "V";
					$x++;
			break; 
			// Perdiam-O
			case "po":
					$option[$x]['option'] = $the_match;
					$option[$x]['name'] = $matches[3][$x];
					$option[$x]['pay_type'] = "15";
					$x++;
			break;
			// Perdiam-E
			case "pe":
					$option[$x]['option'] = $the_match;
					$option[$x]['name'] = $matches[3][$x];
					$option[$x]['pay_type'] = "15";
					$x++;
			break;
			// Mileage
			case "mi":
					$option[$x]['option'] = $the_match;
					$option[$x]['name'] = $matches[3][$x];
					$option[$x]['value'] = $matches[2][$x];
					$option[$x]['pay_type'] = "19";
					$x++;
			break;
			// Travel Time
			case "tt":
					$option[$x]['option'] = $the_match;
					$option[$x]['name'] = $matches[3][$x];
					$option[$x]['value'] = $matches[2][$x];
					$option[$x]['pay_type'] = "17";
					$x++;
			break;
			default:
			$x++;
			$option[$x] = false;
		}
	}

	return $option;
}

// Return type: array
// v0.1
public function get_pay_period_options($options){
	$pattern = '/{*([^:]*):([^:]*):"([^"]*)";}*/';
	$match = preg_match_all($pattern,$options,$matches);
	$x=0;
	foreach($matches[1] as $the_match){
		switch($the_match){
			// Non-union Hours
			case "nu":
				$option[$the_match]['type'] = $the_match;
				$option[$the_match]['name'] = $matches[3][$x];
				$option[$the_match]['date_hours'] = explode("|",$matches[2][$x]);
				$x++;
			break;
			// Union Hours
			case "u":
				$option[$the_match]['type'] = $the_match;
				$option[$the_match]['name'] = $matches[3][$x];
				$option[$the_match]['date_hours'] = explode("|",$matches[2][$x]);
				$x++;
			break;
		}
	}
	return $option;
}

public function get_dashboard() {
	//print_array( $this->capabilities );
	
	$dashboard = '<h3>Dashboard</h3>';
	foreach ( $this->capabilities as $role_name ) {
		foreach( $role_name as $module_name => $caps ) {
			if ( $module_name != '0' ) {
				$dashboard .= '<div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >';
				$dashboard .= '<h2>'.$module_name.'</h2>';
				$dashboard .= '<ul class="rh_options" data-role="listview" >';
				foreach ( $caps as $display_name => $name ) {
					$dashboard .= '<li ><a href="/main/'.$name.'.php">'.$display_name.'</a></li>';
				}
				$dashboard .= '</ul></div>';
			}
		}
	}
	return $dashboard;
}

public function is_punched_in($user_id) {
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT action FROM `punch_clock_entries` WHERE submitter_id = \''.$user_id.'\' ORDER BY id DESC LIMIT 1';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$data = $row;
					$is_punched_in = ($data['action'] == 'in')? true : false;
				}
				$mysqli->close();
				return $is_punched_in;
}

public function get_todays_punched_times($user_id) {
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT action, punch_time FROM `punch_clock_entries` WHERE submitter_id = \''.$user_id.'\' ORDER BY id ASC';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$data[] = $row;
				}
				$mysqli->close();
				return $data;
}
public function get_user($user_id){
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
	/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = 'SELECT * FROM vwEmployees WHERE user_id = \''.$user_id.'\' LIMIT 1';
				$result = $mysqli->query($query);
				$x=0;
				while($row = $result->fetch_assoc()){
					$data = $row;
				}
				$mysqli->close();
				return $data;
}

public function the_employee_search_table(){ ?>
	<form>
            <input id="filterTable-input" data-type="search" placeholder="Select employee..."  >
        </form>
        <table data-role="table" id="employee-table" data-filter="true" data-filter-reveal="true" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-employee-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="persist">Full Name</th>
                <th data-priority="1">Emp. Code</th>
                <th data-priority="2">Union</th>
                <th data-priority="3">Code</th>
                <th data-priority="4">Title</th>
                <th data-priority="5">Status</th>
            </tr>
            </thead>
            <?php 
				$queryArgs[0] = "vwEmployees";
				$queryArgs[1] = "";
				$queryArgs[2] = "";
				$companyUserRecords = $this->mysqliHandler->getArray($queryArgs); 
				$totalUsers = $this->totalCompanyUsers();
				$x=0;
				foreach( $companyUserRecords as $a_key => $users ) {
					
					echo '<tr>'; 
					foreach ( $users as $key => $value ) {
						if($key == 'user_id'){ 
							echo '<td><a href="'.$get_page.'?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
							} else {
									echo '<td class="ui-vertical-middle" >';
									echo $value;
									echo '</td>';
							}
					}
					echo '</tr>';
					$x++;
				}
				?>
        </table> 
        <?php
}

}
?>