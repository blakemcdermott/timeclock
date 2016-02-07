<?php
global $bootstrap, $system, $get_page, $module, $capabilities;

$module = get_module( __FILE__ );
$system = $module['name']; 
$get_page = $_SERVER['PHP_SELF']; 

// Construct Roles
$roles[] = array (
	'module_display_name' => $module['display_name'],
	'display_name'	=> $module['display_name']. " " . "Administrator",
	'name'			=> $module['name'].'_administrator',
	'slug'			=> $module['slug'],
	'description'	=> 'Full access to all '.$module['display_name'].' functions',
	'capabilities'	=> array(
							$module['display_name']. " " . 'Employees' 	=> 'employees',
							$module['display_name']. " " . 'Input' 		=> 'input',
							$module['display_name']. " " . 'Reports'	=> 'reports'
							)
	);

// Construct Roles
$roles[] = array (
	'module_display_name' => $module['display_name'],
	'display_name'	=> $module['display_name']. " " . "Supervisor",
	'name'			=> $module['name'].'_supervisor',
	'slug'			=> $module['slug'],
	'description'	=> 'Has access to input '.$module['display_name'].' for all employees',
	'capabilities'	=> array(
							$module['display_name']. " " . 'Input' 		=> 'input',
							$module['display_name']. " " . 'Reports'	=> 'reports'
							)
	);
				
$roles[] = array (
	'module_display_name' => $module['display_name'],
	'display_name'	=> $module['display_name']. " " . "Employee",
	'name'			=> $module['name'].'_employee',
	'slug'			=> $module['slug'],
	'description'	=> 'Has no access to '.$module['display_name'].' functions',
	'capabilities'	=> array()
	);
	
$affected_rows = $bootstrap->register_role( $roles );
?>