<?php
// Dynamic variable function load
// Requests handled by jquery_load(); found in functions.php
// jQuery $.load(); will perfom an ajax load on the requested function to retreive data
require_once ('../sp-bootstrap.php');
foreach ( $_GET as $key=>$value ) {
	$$key = $value;
}
$func( $func_args );
?>