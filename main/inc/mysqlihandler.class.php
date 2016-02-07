<?php

function get_createSalt(){    
$string = md5(uniqid(rand(), true));    
return substr($string, 0, 32);
}

function get_createHash($salt,$password){
	$hash = hash('sha256', $password);
	$hash = hash('sha256', $salt . $hash);
	return $hash;
}

class mysqliHandler extends mysqli {
public $mysqliHandler;
var $operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname;	
var $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
var $loginInfo;

var $queryArgs;
var $connectionCount;
var $queryCount;

public $result;
public $collection = array();
var $mysqli_count;

function __construct($select_db=false){
	
	include('db.config.php');
		$this->system_dbhost = $system_dbhost;
		$this->system_dbuser = $system_dbuser;
		$this->system_dbpass = $system_dbpass;
		if($select_db == true){ $this->select_db = $select_db; }
		$this->system_dbname = $system_dbname;
		
			
			$this->mysqli = @new mysqli($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);
			$this->mysqliHandler = $this->mysqli;
		
			$this->checkConnection();
			
}

function checkConnection() {
	/* check connection */
			if ($this->mysqliHandler->connect_errno) {
				throw new Exception('Database Connection Failed');
				} 
		
}

function close() {
	$this->mysqliHandler->close();
}

function insert_record($data) {
	$table =  $_GET['type'];
	$post = $data;

	unset($post['id']);
	unset($post['submit']);
	$this->checkConnection();
	
	// Clear last collection set
	unset($this->collection);
	
	// Determine what table we are updating
	switch( $table ) {
		case "user":
		break; // Employee //
		
		case "union":
		break; // Union //
		
		case "time_entries":
		break;
		
		case "options":
		// Build serialized format
		if ( $post['options_type'] == 'pay_period_hours' ) {
			$serialized = '{nu:'.$post['date'].'|'.$post['non_union_hours'].':"Non-union Hours";u:'.$post['date'].'|'.$post['union_hours'].':"Union Hours";}'; 
			
			$post['meta_key'] = $post['options_type'];
			$post['meta_value'] = addslashes($serialized);
			$post['meta_date'] = date('Y-m-d',strtotime($post['date']));
			unset($post['date']);
			unset($post['non_union_hours']);
			unset($post['union_hours']);
			unset($post['options_type']);
		}
		break; // Pay Perdiod Hours //
		
	} // Type Case //
	// Build query
	$query = $this->get_insert_query( $table, $post );
	//echo $query; die();
	$result = $this->mysqliHandler->query($query);
	$affected_rows[] = $this->mysqliHandler->affected_rows;
	$affected_rows[] = $id;
	$affected_rows[] = $query;
	
	return $affected_rows;
}

function update_record($data) {
	$type =  $_GET['type'];
	$id = array( 'column_name' => 'id',
				'id' => $data['id']);
	$post = $data;

	unset($post['id']);
	unset($post['submit']);
	$this->checkConnection();

	// Clear last collection set
	unset($this->collection);
	
	// Determine what table we are updating
	switch( $type ) {
		case "user":
			if(is_array($post['permissions'])) { 
				foreach ( $post['permissions'] as $key => $value ) {
					$permissions[] = $key;
				}
				
				$update_permissions = true;
				unset($post['permissions']);
				$meta_id = $post['meta_id'];
				unset($post['meta_id']);

				$query = 'INSERT INTO `user_meta` (`id`,`user_id`,`meta_key`, `meta_value`) VALUES ( "'.$meta_id.'", "'.$data['id'].'","roles","'.addslashes(json_encode($permissions)).'" )';
				$query .= ' ON DUPLICATE KEY UPDATE `meta_value`=VALUES(meta_value);';
				$result = $this->mysqliHandler->query($query);
				//echo $query;
				//$affected_rows[] = $this->mysqliHandler->affected_rows;
			}
			if($post['new_password'] != "" ) {
					$query = 'Select salt FROM user WHERE id="'. $data['id'].'";';
					$result = $this->mysqliHandler->query($query);
					$existing_salt = $result->fetch_assoc();
		
						$the_salt = get_createSalt();
						$the_hash = get_createHash($the_salt, $post['new_password']);
						
						$post['salt'] = $the_salt;
						$post['password'] = $the_hash;
						
						unset($post['new_password']);
					
				} else {
				unset($post['salt']);
				unset($post['password']);
				unset($post['new_password']);
				}
			
		break; // Employee //
		
		case "union":
		
		break; // Union //
		
		case "inventory_checkout":
			$id['column_name'] = 'checkout_id';
		break; // Inventory Checkout //
		
		case "time_entries":
		// Consolidate extra fields down to the single "options" field
			
			// Perdiam Selection
				if($post['perdiam'] == 'po'){
					$perdiam = $post['perdiam'].':1:"Perdiam-o";'; 
				} elseif($post['perdiam'] == 'pe'){
					$perdiam = $post['perdiam'].':1:"Perdiam-e";';
				} else { $perdiam = ''; }
			// Time Type Selection
				if($post['time_type'] == 'ho'){
					//if($post['time_type'] == 0 || $post['time_type'] == '') $time_type .= '';
					$time_type .= $post['time_type'].':1:"Holiday";';
				} elseif ($post['time_type'] == 'hw') {
					//if($post['time_type'] == 0 || $post['time_type'] == '') $time_type .= '';
					$time_type .= $post['time_type'].':1:"Worked Holiday";';
				} elseif($post['time_type'] == 'va'){
					//if($post['time_type'] == 0 || $post['time_type'] == '') $time_type .= '';
					$time_type .= $post['time_type'].':1:"Vacation";';
				} else { $time_type = ''; }
	
			$mileage = ($post['mileage'])?'mi:'.$post['mileage'].':"Mileage";':'';
			$travel_time = ($post['travel_time'])?'tt:'.$post['travel_time'].':"Travel Time";':'';
					
			$post['options'] = '{'.$perdiam.$time_type.$mileage.$travel_time.'}';
					// Clean up
					unset($post['perdiam']);
					unset($post['time_type']);	
					unset($post['mileage']);
					unset($post['travel_time']);			
		break;
		case "options":
		// Build serialized format
		if ( $post['options_type'] == 'pay_period_hours' ) {
			$serialized = '{nu:'.$post['date'].'|'.$post['non_union_hours'].':"Non-union Hours";u:'.$post['date'].'|'.$post['union_hours'].':"Union Hours";}'; 
			
			$post['meta_key'] = $post['options_type'];
			$post['meta_value'] = addslashes($serialized);
			$post['meta_date'] = ($post['date'] != NULL && $post['date'] != '' )? date('Y-m-d',strtotime($post['date'])) : NULL;
			unset($post['date']);
			unset($post['non_union_hours']);
			unset($post['union_hours']);
			unset($post['options_type']);
		}
		break; // Pay Perdiod Hours //
		
	} // Type Case //
	// Build query
	$query = 'UPDATE `'.$type.'` SET ';
	$x=0;
	$count = count($post) - 1;

	foreach ($post as $field_name=>$value){
		if($x <= $count){
			if($x==$count) {$query .= '`'.$field_name.'`=\''.$value.'\' ';}
				else {$query .= '`'.$field_name.'`=\''.$value.'\', ';}
		
		}
	$x++;
	}
	
	$query .= 'WHERE `'.$id['column_name'].'`="'.$id['id'].'";';
	$result = $this->mysqliHandler->query($query);
		
	$affected_rows[] = $this->mysqliHandler->affected_rows;
	$affected_rows[] = $id;
	$affected_rows[] = $query;
	
	return $affected_rows;
}

function delete_record($id,$type) {
	$this->checkConnection();
	
	// Clear last collection set
	unset($this->collection);
	
	// Determine what table we are updating
	switch($type) {
		case "user":
			
		break; // Employee //
		
		case "union":
		
		break; // Union //
		
	} // Type Case //
	// Build query DELETE FROM somelog WHERE user = 'jcole'
	$query = 'DELETE FROM `'.$type.'` WHERE id = "'.$id.'" ;';

	$result = $this->mysqliHandler->query($query);
	$affected_rows[] = $this->mysqliHandler->affected_rows;
	$affected_rows[] = $id;
	$affected_rows[] = $query;
	
	return $affected_rows;
}

function getArray(){
	// Clear last collection set
	reset($this->collection);
	
	// Step #1: Get any arguments passed
	$this->getQueryArgs(func_get_args());
	
	
	// Step #3: Use mysqli method to convert to array
	while($row = $this->result->fetch_assoc())
	{
		$collection[] = $row;
	}
	
	$this->collection = $collection;	
	//print_r($collection);
		return $this->collection; 
}


function getQueryArgs(){
	$queryArgs = func_get_args();
	//print_r($queryArgs);
	$this->queryArgs = $queryArgs;
	
		foreach($queryArgs as $key => $value){
			if(is_array($value)){
				unset($queryArgs);
				foreach($value as $xkey => $xvalue){
					$this->queryArgs[] = $xvalue;
				}
			}
		}
	
	// Step #2: Run the query processor using the arguments stored in global variables
	$this->dbQuery();
		
}

function dbQuery(){
	$args = func_get_args();
	if ( count($args) > 0 ) {
		
		$query = $args[0];
		$this->checkConnection();
		$this->result = $this->mysqliHandler->query($query);
		
		return $this->result;
		
	} 
	else {
			
				if( strpos($this->queryArgs[0][0][0], ',') ){
					$query = $this->selectPartial($this->queryArgs[0], $this->queryArgs[1], $this->queryArgs[2], $this->queryArgs[3],$this->queryArgs[4]);
				} else {
					$query = $this->selectAll($this->queryArgs[0], $this->queryArgs[1], $this->queryArgs[2], $this->queryArgs[3]);
				}
		//echo $query;
		$this->result = $this->mysqliHandler->query($query);
		
		}
	
}


/*			Query builders/helpers			*/

private function selectAll(){
	$queryArgs = func_get_args();
	
	foreach($queryArgs as $key => $value){
		if(is_array($value)){
			unset($queryArgs);
			foreach($value as $xkey => $xvalue){
				$queryArgs[] = $xvalue;
			}
		}
	}

	$sqlcolumns = "*";
	$sqlcommand = "SELECT ";	
	$sqlfrom = " FROM `".$queryArgs[0]."` ";
	if ( $queryArgs[1] != "" || $queryArgs[1] != FALSE ) $sqlwhere = "WHERE ".$queryArgs[1]."='".$queryArgs[2]."'";
		if($queryArgs[3] != ""){
		$sqlparams = " ".$queryArgs[3];
		} else	$sqlparams = "";
	$sql = $sqlcommand.$sqlcolumns.$sqlfrom.$sqlwhere.$sqlparams.";";


return $sql;
}

function selectPartial(){
	$queryArgs = func_get_args();
	
	foreach($queryArgs as $key => $value){
		if(is_array($value)){
			unset($queryArgs);
			foreach($value as $xkey => $xvalue){
				$queryArgs[] = $xvalue;
			}
		}
	}
	$sqlcolumns = $queryArgs[0];
	$sqlcommand = "SELECT ";	
	$sqlfrom = " FROM `".$queryArgs[1]."` ";
	if($queryArgs[2] && $queryArgs[3]) $sqlwhere = "WHERE ".$queryArgs[2]."='".$queryArgs[3]."'";
	if($queryArgs[4]) $sqlparams = " ".$queryArgs[4];

	$sql = $sqlcommand.$sqlcolumns.$sqlfrom.$sqlwhere.$sqlparams.";";

return $sql;
}

function get_insert_query( $table, $data ){
	if($data['id']) { array_pop($data); }
	
	$table = __DB_NAME__.'.'.$table;
	
	$datacount = count($data);	
	$count = 1;
	$sqlfields = ' ( ';
	$sqlvalues = 'VALUES 	 ( ';
	
	while ( $count < $datacount ) {
		foreach($data as $key => $value) {
	
			$sqlfields .= ( $count != $datacount ) ? $key.', ' : $key.' ) ' ;
			$sqlvalues .= ( $count != $datacount ) ? '"'.$value.'", ' : '"'.$value.'" ) ' ;
			$count++;
		}
	}
	
	$sqlcommand = "INSERT INTO ";	
	$sql = $sqlcommand.$table.$sqlfields.$sqlvalues.";";

return $sql;
}


}


?>