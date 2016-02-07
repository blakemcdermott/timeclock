<?php
function viewErrors($option){
	if($option === TRUE){
		error_reporting(E_ALL);
		return ini_set('display_errors', '1');
	}
}
function expireCookies($cookies, $url=".fsii.co"){
			foreach($cookies as $key => $value){
				setcookie('userInfo['.$key.']', '', time()-3600, '', $url);
			}
		}
		

	

//viewErrors(true);
##########################################################################################################
/* Begin the Sales Pal Login Class */

class sp_login {

private $loginInfo;
private $origLoginInfo;
private $userexists;
public $userInfo;
private $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
private $select_db;
var $mysqliHandler;

public $apps, $appCount, $appInfo;

public $error;
public $notification;

	public 
	$the_company_id, 
	$the_user_id,
	$the_username,
	$the_fullname,
	$the_email,
	$the_ip,
	$the_title,
	$is_valid
	;


function __construct($loginInfo,$select_db=false){
	if($this->debug){ $this->debugMsg .= "sp_login() class constructor initiated". '\r';
	 $this->debugMsg .= '\r'."\$loginInfo (array)".'\r';
		foreach($loginInfo as $key => $value) {
			$this->debugMsg .= '----> '.$key.' = '.$value.'\r';
		}
	}
	
	$this->loginInfo = $loginInfo;
	if(!isset($this->mysqliHandler))$this->mysqliHandler();

	
		foreach($this->loginInfo as $name=>$value){
			
				/* Build origRegInfo Array */
				# This array contains all of the original names and values
				$this->origLoginInfo[$name] = $value;
				
				$type = substr($name, 0, 2);
				$modifiedFields = $this->modifyTypedFields($type, $name);
				unset($this->loginInfo[$name]);
				
				/* Build regInfo Array */
				# This array contains all of the formated names and values
				$this->loginInfo[$modifiedFields] = $value;
		}
		
}

protected function debug($loginInfo){
	if($this->debug){ $this->debugMsg .= "sp_login() class constructor initiated". '\r'; 
		foreach($loginInfo as $key => $value){
		$this->debugMsg .= "    "."[".$key."] = ".$value.'\r';
		}
	}
}

function accessLevelCheck($lvl_req,$email){
	$this->collectUserInfo($email);
	
	$grant = false;
	
	if($this->userInfo['accesslevel'] >= $lvl_req){
		$grant = true;
		return $grant;
	} else return $grant;
}

function get_user_role(){
	$this->collectUserInfo($this->userInfo['email']);
	return $this->userInfo['user_role_id'];
}

function collectUserInfo(){

	$args = func_get_args();
	($this->userInfo['email'] != "" && $this->userInfo['email'] != NULL) ? $email = $this->userInfo['email'] : $email = $args[0];
	
	$userInfo = $this->mysqliHandler->getArray("user", "email", $email);
		$this->userInfo =  $userInfo[0];
		
		
}

function userExists(){

		if(is_array($this->userInfo) && isset($this->userInfo)) return true;
		else return false;
}

public function get_userInfo() {
	
	$args = func_get_args();
	($this->userInfo['email'] != "" && $this->userInfo['email'] != NULL) ? $email = $this->userInfo['email'] : $email = $args[0];
	
	$userInfo = $this->mysqliHandler->getArray("user", "email", $email);
	//print_r($userInfo);
		return  $userInfo;
}

function passwordValid(){
	
	$hashToValidate = $this->createHash($this->loginInfo['password'], $this->userInfo['salt']);
		if($hashToValidate != $this->userInfo['password']) { 
			return false;
			} else {
			return true;
			}
}

function userConfirmed(){
	if($this->userInfo['isconfirmed'] != "Y") { 
		return false;
		} else {
		return true;
		}
}

function userEnabled(){
	if($this->userInfo['disabled'] != "N") { 
		return false;
		}
	else {
		return true;
		}

}

function authenticate(){
	
	($this->loginInfo['email'] != "" && $this->loginInfo['email'] != NULL) ? $email = $this->loginInfo['email'] : $email = $args[0];
	$this->collectUserInfo($email);
	
		
			if(!$this->userExists()){
					// User does not exist
					Throw new Exception('User does not exist');
					return false;
				} elseif (!$this->passwordValid()){
					// Invalid password
					Throw new Exception('Invalid username or password');
					return false;
				} elseif (!$this->userEnabled()){
					// User account is not enabled
					Throw new Exception('User account is not enabled');
					return false;
				} else {
					// User fully authenticated
					return true;
				}
		
	
}



function setCookie(){
	
		setcookie("userInfo[id]", $this->userInfo['id'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[valid]", 1, time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[username]", $this->userInfo['username'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[fullname]", $this->userInfo['firstname']." ".$this->userInfo['lastname'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[title]", $this->userInfo['title'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[email]", $this->userInfo['email'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[ip]", $this->userInfo['ip'], time()+60*60*24, '', '.fsii.co');
		setcookie("userInfo[time]", time(), time()+60*60*24, '', '.fsii.co');

}

function setSession(){
	if (!isset($_SESSION) ){
	//$_SESSION['userInfo']['accesslevel'] = 0;
		session_start();
		$this->collectUserInfo($_COOKIE['userInfo']['email']);
		//$_SESSION = $this->userInfo;	
	}

}

function destroyCounterData(){
		/* This session was created by the addToCounter() function */
		session_unset($_SESSION['counter']['modifiedCount']);
		session_unset($_SESSION['counter']['nonModifiedCount']);
		session_unset($_SESSION['counter']['totalCount']);
}

function login(){
	$args = func_get_args();
	
	if($this->authenticate()){
		$this->setCookie();
		$this->setSession();
		
		header('Location: http://'. $_SERVER['SERVER_NAME'].'/main/default.php');

	} else {
		$this->notification = "Error: ".$this->notification;
		$this->forceLogOut($args[0]);
	}
}

public function isLoggedIn(){
	$args = func_get_args();
	$this->ip = $_SERVER['REMOTE_ADDR'];
		 if(isset($_COOKIE['userInfo']['valid']) && $_COOKIE['userInfo']['valid']){
			 foreach ($_COOKIE['userInfo'] as $name => $value) {
				$name = htmlspecialchars($name);
				$value = htmlspecialchars($value);
				$$name = $value;
			 	}
			$this->loadAppFunc();
			$this->setSession();
			 return true;
			 }
			 else {   
		 $this->forceLogOut($args[0]);
			 }
		 }
		 
function loadAppFunc(){
	require_once('functions.php');
}

public function forceLogOut($type='desktop'){
	
	header('Location: http://'. $_SERVER['SERVER_NAME']);

}

function logout(){
}

function selectAll(){
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
	$sqlwhere = "WHERE ".$queryArgs[1]."='".$queryArgs[2]."'";
		if($queryArgs[3]){
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
	$sqlwhere = "WHERE ".$queryArgs[2]."='".$queryArgs[3]."'";
		if($queryArgs[4]){
		$sqlparams = " ".$queryArgs[4];
		} else	$sqlparams = "";
	$sql = $sqlcommand.$sqlcolumns.$sqlfrom.$sqlwhere.$sqlparams.";";

return $sql;
}

function selectStmt($tbl, $loginInfo){
$sqlcolumns = "";
$count = (int) count($loginInfo);
	$x=0;
	$y=0;
	foreach($loginInfo as $name=>$value){
	$type = substr($name, 0, 2);
	$this->countFieldTypes($type);
	}
	
	$modifiedCount = $_SESSION['counter']['modifiedCount'];
	$nonModifiedCount = $_SESSION['counter']['nonModifiedCount'];
	$totalCount = $_SESSION['counter']['totalCount'];
		foreach($loginInfo as $name=>$value){
				$type = substr($name, 0, 2);
					$modifiedFields = $this->modifyTypedFields($type, $name);
					$$modifiedFields = $value;
					if($modifiedFields){
								
								if($y <= $totalCount){
									$sqlcolumns .= $modifiedFields;
								}
								if($y < ($modifiedCount - 1)){
									$sqlcolumns .= ", ";
									}
							$y++;		
						} else {
							//echo "FALSE<br />";
							if($x <= $totalCount){
									$sqlcolumns .= "";
								}
							}
			$x++;
		}

$sqlcommand = "SELECT ";	
$sqlfrom = " FROM `".$tbl."` ";
$sqlwhere = "WHERE email='".$this->loginInfo['email']."';";
$sql = $sqlcommand.$sqlcolumns.$sqlfrom.$sqlwhere;
$this->destroyCounterData();
return $sql;
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
				$name = "";
				return $name;
				break;
			}
}

public function printNotification(){
	if($this->notification){
	echo $this->notification;
	}
}

### Password Functions ###
// Depreciated method
function createSalt(){    
$string = md5(uniqid(rand(), true));    
return substr($string, 0, 32);
}
// Depreciated method
function createHash($password, $salt){
	$hash = hash('sha256', $password);
	$hash = hash('sha256', $salt . $hash);
	return $hash;
}


### Application Handler ###

function appHandler(){
	$mysqliHandler = new mysqliHandler($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
 	/* Prepare a select statement */
	$query = 'SELECT id, user_id, app_id FROM user_apps WHERE user_id='.$_COOKIE[userInfo]['id'].';';

	$result = $mysqliHandler->query($query);
	$x=0;
		while($row = $result->fetch_assoc()){
		$this->apps[$x] = $row;
		$x++;
		}
$mysqliHandler->close();

$this->appCounter();
}

function appCounter(){
	$appCount = count($this->apps);
	$this->appCount = $appCount;

}

function spPortalNav(){
$portalBarHtml = <<< EOF
<div id="portal_box">
<p class="folder_label">SalesPal Applications</p>
<div class="portal_icon_container">
<div class="portal_iconbox">
<a id="crm" class="crm"><div class="iconbox_title">SalesPal CRM</div>
<img src="images/crm_icon_hover.png" width="239" height="183" alt="SalesPal CRM" id="crm_hover" class="hoverImg"/></a>
</div>
<div class="portal_iconbox">
<a id="sites" class="sites"><div class="iconbox_title">SalesPal Site(s)</div>
<img src="images/sites_icon_hover.png" width="239" height="183" alt="SalesPal Sites" id="sites_hover" class="hoverImg"/></a>
</div>
<div class="portal_iconbox">
<a id="partners" class="partners"><div class="iconbox_title">SalesPal Partners</div>
<img src="images/partners_icon_hover.png" width="239" height="183" alt="SalesPal Partners" id="partners_hover" class="hoverImg"/></a>
</div>
<div class="portal_iconbox">
<a id="reports" class="reports"><div class="iconbox_title">SalesPal Reports</div>
<img src="images/reports_icon_hover.png" width="239" height="183" alt="SalesPal Reports" id="reports_hover" class="hoverImg"/></a>
</div>
</div>
</div>
<div style="margin:-10px auto 10px auto;text-align:center;"><img src="images/expand.png" id="show-portalbox" /></div>
EOF;

for($x=0; $x <= $this->appCount; $x++){
	if($this->apps[$x]['app_id'] == 6){
	print($portalBarHtml);
	} 
}
}

function appsBar(){


}

function runApp($appId){
	$mysqliHandler = new mysqliHandler($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
 	/* Prepare a select statement */
	$query = 'SELECT * FROM salespal_apps WHERE id='.$appId.';';

	$result = $mysqliHandler->query($query);
		$x=0;
		while($row = $result->fetch_assoc()){
		$this->appInfo[$x] = $row;
		$x++;
		}
	$mysqliHandler->close();
	$webAppLoc = $this->appInfo[0]['root_dir'].'/'.$this->appInfo[0]['category_dir'].'/'.$this->appInfo[0]['dir'].'/index.php';
	$newWebAppLoc = $this->appInfo[0]['root_dir'].'/'.$this->appInfo[0]['dir'].'/index.php';
	return $webAppLoc;
}

}
?>