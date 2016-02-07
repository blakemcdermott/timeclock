<?php
##########################################################################################################
/* Begin the Sales Pal Settings Class */

class sp_userfunctions extends sp_permissions {
private $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
private $select_db;
private $collection;
private $queryArgs;

function __construct(){
	if(!isset($this->mysqliHandler))$this->mysqliHandler();
}

function echoHello(){
	echo "hello";
}

function loadUserSettings($queryArgs){
	$this->queryArgs($queryArgs);
}



}
?>