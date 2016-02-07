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
	'capabilities'	=> array(	$module['display_name']. " " . 'Checkout'			=> 'checkout',
								$module['display_name']. " " . 'QR'					=> 'qr',
								$module['display_name']. " " . 'Checkout History'	=> 'checkouthistory',
								$module['display_name']. " " . 'Reports'			=> 'reports'
								)
	);
				
$roles[] = array (
	'module_display_name' => $module['display_name'],
	'display_name'	=> $module['display_name']. " " . "User",
	'name'			=> $module['name'].'_user',
	'slug'			=> $module['slug'],
	'description'	=> 'Has access to Checkin/Checkout '.$module['display_name'].' Items',
	'capabilities'	=> array(	$module['display_name']. " " . 'Checkout'	=> 'checkout',
								$module['display_name']. " " . 'QR'			=> 'qr',
								),
	);
	
$affected_rows = $bootstrap->register_role( $roles );

?>