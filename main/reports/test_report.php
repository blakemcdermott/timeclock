<?php 
require_once ('../sp-bootstrap.php'); 
$bootstrap->isLoggedIn();
$date = "12/01/2014|12/24/2014";
$bootstrap->get_inventory_import_data_csv($date,$echo=0);


?>