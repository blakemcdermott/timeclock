<?php
require_once ('sp_login.class.php');
##########################################################################################################
/* Begin the Permissions class */

class sp_permissions extends sp_login {
private $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
private $select_db;
private $collection;
private $queryArgs;
public $capabilities;

function __construct($loginInfo,$select_db){
	if($this->debug){ $this->debugMsg .= "sp_permissions() class constructor initiated". '\r';}
	sp_login::__construct($loginInfo,$select_db);
	if(!sp_login::isLoggedIn()){
		sp_login::login();
	}
	if(!isset($this->mysqliHandler))$this->mysqliHandler();
	$this->capabilities =  $this->get_capabilities( $this->userInfo['id']);
}

// Accepts single role or an array of roles
// v0.1
function register_role( $roles ){
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	
	foreach ( $roles as $key => $role_details ) {
		$capabilities = '';
		if( is_array( $role_details ) ){
			foreach( $role_details['capabilities'] as $page_name => $name ) {
				$capabilities[$role_details['module_display_name']][$page_name] = $role_details['slug']."-".$name;
				//$capabilities[] = $role_details['slug']."-".$capability;
			}
			
			if( $role_details['name'] == "" || $role_details['name'] == NULL ) $role_details['name'] = 'NULL';
			$sql[] = '("'.$role_details['name'].'","'.$role_details['display_name'].'","'.$role_details['slug'].'","'.$role_details['description'].'","'.addslashes(json_encode($capabilities)).'" )';
		}
	}
			
			/* Prepare an insert statement */
	try {
		$query = 'INSERT INTO `'.$system_dbname.'`.`user_role` (`name`, `display_name`, `slug`, `description`, `capabilities`) VALUES '.implode(',', $sql);
		$query .= ' ON DUPLICATE KEY UPDATE `display_name`=VALUES(display_name), `slug`=VALUES(slug), `description`=VALUES(description), `capabilities`=VALUES(capabilities);';	

		$mysqli->query($query);
		$affected_rows = $mysqli->affected_rows;
		$mysqli->close();
		return $affected_rows;
	
	} catch(Exception $e) {
			die($e->getMesage());
		}
}

// Assigns one or more roles to users
// v0.1
function get_permission_roles($user_id){
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$query = 'SELECT meta_value FROM `'.$system_dbname.'`.`user_meta` WHERE user_id = "'.$user_id.'" and meta_key = "roles" LIMIT 1;';
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	return json_decode($row[0]);
}

// Retrieves the users capabilities
// v0.1
function get_capabilities(){
	
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$query = 'SELECT um.meta_value FROM `'.$system_dbname.'`.`user_meta` um WHERE user_id = "'.$this->userInfo['id'].'" and um.meta_key = "roles" LIMIT 1;';
	$result = $mysqli->query($query);
	
	$row = $result->fetch_row();
	$roles = json_decode($row[0]);
		
	foreach( $roles as $key => $role ) {
		$condition .= (!$condition)? "name='$role'" : " OR name='$role'";
	}
	$query = 'SELECT ur.name, ur.capabilities FROM `'.$system_dbname.'`.`user_role` ur WHERE '.$condition.';';

	$result = $mysqli->query($query);
	while( $row = $result->fetch_assoc()){
		$capabilities[$row['name']] = json_decode( $row['capabilities'] );
		//print_array( $capabilities );
	}
	//die();
	return $capabilities;
}

function get_permission_checkboxes( $access_type, $assigned_roles=NULL ) {
	include('db.config.php');
	$mysqli = new mysqli($operations_dbhost, $operations_dbuser, $operations_dbpass, $operations_dbname);
			
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$query = 'SELECT name, display_name FROM `'.$system_dbname.'`.`user_role`;';

	$result = $mysqli->query($query);
	while ( $row = $result->fetch_assoc() ){
		
		if ( $access_type == 'administrator' && strstr($row['name'], '_administrator' ) ) {
			
			$head = '<fieldset data-role="controlgroup" data-iconpos="right"><legend>Administrator Roles:</legend>';
			$roles['administrator'][] = $row['name'];
			$html .=  ( in_array($row['name'],$assigned_roles) )? '<input type="checkbox" name="permissions['.$row['name'].']" id="permissions['.$row['name'].']" checked="checked" ><label for="permissions['.$row['name'].']">'.$row['display_name'].'</label>':
			'<input type="checkbox" name="permissions['.$row['name'].']" id="permissions['.$row['name'].']" ><label for="permissions['.$row['name'].']">'.$row['display_name'].'</label>';  
		} elseif( $access_type == 'user' && !strstr($row['name'], '_administrator') ) {
			
			$head = '<fieldset data-role="controlgroup" data-iconpos="right"><legend>User Roles:</legend>';
			$roles['user'][] = $row['name'];  
			$html .=  ( in_array($row['name'],$assigned_roles) )? '<input type="checkbox" name="permissions['.$row['name'].']" id="permissions['.$row['name'].']" checked="checked" ><label for="permissions['.$row['name'].']">'.$row['display_name'].'</label>':
			'<input type="checkbox" name="permissions['.$row['name'].']" id="permissions['.$row['name'].']" ><label for="permissions['.$row['name'].']">'.$row['display_name'].'</label>';
			
		}
	}
	$foot = '</fieldset>';
	echo $head.$html.$foot;
}

}
?>