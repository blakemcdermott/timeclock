 <?php

require ('sp_operations.class.php');
// require ('sp_permissions.class.php');
// require ('sp_login.class.php');
// require ('sp_userfunctions.class.php');

##########################################################################################################
/* Begin the Sales Pal Settings Class */

class sp_settings extends sp_operations {
private $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
private $select_db;
private $collection;
private $queryArgs;
var $debug = false;
var $debugMsg;
var $mysqliHandler;
var $loginInfo;

function __construct($loginInfo,$select_db=false){
	if($this->debug){ $this->debugMsg = "sp_settings() class constructor initiated". '\r'; viewErrors(true);}
	sp_operations::__construct($loginInfo,$select_db);
	
	$this->loginInfo = $loginInfo;
	if(!isset($this->mysqliHandler))$this->mysqliHandler();
	require_once('sp_formbuilder.class.php');
}

function mysqliHandler(){
	require_once('mysqlihandler.class.php');
	$this->mysqliHandler = new mysqliHandler($this->loginInfo,$select_db=false);
}

function echoHello(){
	echo "hello";
}

function loadUserSettings($queryArgs){
	$this->queryArgs($queryArgs);
}

function totalCompanyUsers(){
	if($this->collection){
		$totalUsers = count($this->collection);
		return $totalUsers;

	}
}

function pull_company_users($queryArgs){
	$this->collection = $this->mysqliHandler->getArray($queryArgs);
	
		//$this->massageData();
	
		$frameWorkHtml = <<< EOF
<div class="widgetbox">
<div class="widgetboxheader"><p>$header</p></div>
<table class="data_table" cellpadding="5" cellspacing="0">
<thead align="left">
	<tr>
		<th>Select</th>
		<th>Full name</th>
		<th>User Type</th>
	</tr>
</thead>
EOF;
			$i=0;
			$x=0;
			while($this->collection[$x]) {
				foreach ($this->collection[$x] as $name => $value) {
						$name = htmlspecialchars($name);
						$value = htmlspecialchars($value);
						$$name = $value;
				}
						
			$i++;
			$frameWorkHtml .= "\n<tr class=\"tr_".($i & 1)."\" spid=\"".$id."\" >\n<td><input type=\"checkbox\" name=\"checkbox\" id=\"".$id."\" value=\"1\" /></td>\n<td>";	
			$frameWorkHtml .= "<div><a style=\"color:#000;text-decoration:underline;\" href=\"user.php?id=".$this->collection[$x]['id']."\">";		
			$frameWorkHtml .= $firstname." ".$lastname."</div></td><td><div>".$role_name."</div></td></tr>";
			$frameWorkHtml .= "<tr class=\"record_dropdown ".$id."\" ><td class=\"dropdown_main\"colspan=\"3\"><div class=\"dropdown_content ".$id."\" ><img src=\"images/ajax-loader-circle.gif\" /></td></tr>";	
			$x++;
				
			}
			$frameWorkHtml .= "</table>";
$frameWorkHtml .= "</div>";
return $frameWorkHtml;
}



function add_company_user( $table='user', $data ) {
	$query = $this->mysqliHandler->get_insert_query( $table, $data );
		$this->mysqliHandler->dbQuery($query);
}

function delete_company_user() {
	
}

function modify_company_user() {
	
}

}
?>