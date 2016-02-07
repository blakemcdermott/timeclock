 <?php






##########################################################################################################
/* Begin Russo Time Registration Class */

class sp_registration {
var $rootSite;
var $system_dbhost, $system_dbuser, $system_dbpass, $system_dbname;
var $regInfo;
var $origRegInfo;
var $userexists;
var $userInfo;

public $APP_NAME="Russo Corp - Time Clock";
public $FULL_APP_NAME;
public $DOMAIN="timeclock.fsii.co";
var $REG_DIR;

public $notification;

function __construct($regInfo){
	include('db.config.php');
	$this->system_dbhost = $system_dbhost;
	$this->system_dbuser = $system_dbuser;
	$this->system_dbpass = $system_dbpass;
	$this->system_dbname = $system_dbname;
	$this->rootSite = $_ENV['SERVER_NAME'];
	$this->regInfo = $regInfo;
	
	$this->FULL_APP_NAME = "The ".$this->APP_NAME;
		
	foreach($regInfo as $name=>$value){
		
			/* Build origRegInfo Array */
			# This array contains all of the original form field names and values
			$this->origRegInfo[$name] = $value;
			
			$type = substr($name, 0, 2);
			$modifiedFields = $this->modifyTypedFields($type, $name);
			unset($this->regInfo[$name]);
			
			/* Build regInfo Array */
			# This array contains all of the original form field names and values
			$this->regInfo[$modifiedFields] = $value;
	}
	
}
function getRegistrationFolder(){
			$pattern = "/\/([^\/]+)/";
			$subject = $this->origRegInfo['directory'];
			$folder = preg_match($pattern, $subject, $match);
			if($folder){
				$match[1] .= "/";
				$this->REG_DIR = $match[1];
				}
	
}

function printRegInfo(){
	foreach($this->origRegInfo as $name=>$value){
			echo $name." => ".$value."<br />";
			}	
}

function userExists(){
	try{
		
	$mysqli = new mysqli($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
 	/* Prepare a select statement */
	$query = "SELECT email FROM `user` WHERE email='".$this->regInfo['email']."';";
	$result = $mysqli->query($query);
	$this->regInfo = $result->fetch_assoc();
	$count = (int) count($this->regInfo);
	
		### Check for user
		if($count < 1){ 
		//$this->notification = "Username \"".$this->regInfo['firstname']." ".$this->regInfo['lastname']."\" does not exist.";
		$this->userexists = false;
		} 
		else{ 
		$this->notification = "Account with email address: \"".$this->regInfo['email']."\" already exists.";
		$this->userexists = true;
		}
	$mysqli->close();
	}
		catch (Exception $e) {
			die($e->getMessage());
		}
		/* This session was created by the addToCounter() function */
		if(isset($_SESSION)) { session_unset($_SESSION);}
		if(isset($_SESSION)) {session_destroy();}
}

function getUserInfo() {
	if($this->userexists == TRUE){
		try{
		
	$mysqli = new mysqli($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
 	/* Prepare a select statement */
	$query = "SELECT * FROM `user` WHERE email='".$this->origRegInfo['s_email']."';";
	$result = $mysqli->query($query);
	$this->userInfo = $result->fetch_assoc();
	//print_r($this->regInfo);
	$count = (int) count($this->userInfo);
	$mysqli->close();
	}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

}

function register(){
	if($this->userexists == FALSE){
	
	$mysqli = new mysqli($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
	/* Create Password salt and hash */
	try {
		$this->origRegInfo['s_salt'] = $this->createSalt();
		$this->origRegInfo['s_password'] = $this->createHash();
	/* Create confirmation hash */
		$this->origRegInfo['s_hash'] = hash('sha1', $this->origRegInfo['s_email']);
		} catch(Exception $e) {
		die($e->getMessage());
		}
	/* Prepare an insert statement */
 	try {
	// If the users title is not set, set it to Standard User
	if($this->origRegInfo["s_title"] == "" || $this->origRegInfo["s_title"] == NULL){ $this->origRegInfo["s_title"] = "Russo Time: Standard User";}
	$query = $this->insertStmt("user", $this->origRegInfo);
	$mysqli->query($query);
	$mysqli->commit();
	$mysqli->close();
	$this->notification = "Congratulations ".$this->origRegInfo['s_firstname']." ".$this->origRegInfo['s_lastname'].", your account has been created! <br />";
	//$this->sendConfirmationEmail();
	return true;
	} catch(Exception $e) {
			die($e->getMesage());
		}

		/* This session was created by the addToCounter() function */
		session_unset($_SESSION);
		session_destroy();
	}
		
	
}

function sendConfirmationEmail(){
	$this->getRegistrationFolder();
$firstname = $this->origRegInfo['s_firstname'];
$lastname = $this->origRegInfo['s_lastname'];
$email = $this->origRegInfo['s_email'];
$hash = $this->origRegInfo['s_hash'];
$rootSite = $this->rootSite;
	
$to = $email; 
$from = $this->FULL_APP_NAME.' - System <system@'.$this->DOMAIN.'>';
$subject = $this->FULL_APP_NAME.' - Registration Confirmation';
(is_dir($this->REG_DIR)) ? $regDir = $this->REG_DIR : $regDir = NULL;
$confirmationUrl = "http://".$rootSite."/".$regDir."confirmRegistration.php?s_email=".$email."&s_hash=".$hash;
$visibleUrl = "http://".$rootSite."/".$regDir."confirmRegistration/".strtolower($firstname.$lastname)."/".$hash;

$message = <<< EOF
<html>
<head></head>
<body>
<!-- email content below -->
<div>
<h3 style="font-size:16px;font-family:arial;font-weight:normal;">Hello, $firstname $lastname.</h3>
<p style="font-size:12px;font-family:arial">Please confirm your $this->APP_NAME account by clicking this link:<br />
<a href="$confirmationUrl">$visibleUrl</a>
<br /><br />
Once you confirm your account, you will have access to $this->APP_NAME.
</p>
<div style="border-bottom:1px solid #d9d9d9;">
<span style="font: italic 13px Georgia,serif; color: rgb(102, 102, 102);">The $this->APP_NAME Team</span>
</div>
<p style="color:#a0a0a0;font-size:10px;font-family:arial">If you received this message in error and did not sign up for a $this->APP_NAME account, disregard this email</p>
<p style="color:#a0a0a0;font-size:10px;font-family:arial">Please do not reply to this message; it was sent from an unmonitored email address. This message is a service email related to your use of $this->FULL_APP_NAME. For general inquiries or to request support with your $this->APP_NAME account, please email us at <a href="mailto:support@$this->DOMAIN">support@$this->DOMAIN</a></p>
</div>
</body>
</html>
EOF;
$replyto = $this->APP_NAME.' Management <management@silverstonepayments.com>';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'From: ' . $from . "\r\n";
$headers .= 'Cc:' . "\r\n";
$headers .= 'Bcc: ' . $bcc . "\r\n";
$this->sendMail($to, $subject, $message, $headers);
}

function sendMail($to, $subject, $message, $headers){
$mailResult = mail($to, $subject, $message, $headers);
if($mailResult){
	$this->notification .= "You should receive a confirmation email shortly.";
	} else {
	$this->notification .= "Your confirmation email failed to send. Please contact support.";
		}
	}

function verifyConfirmationHash(){
	if($this->userexists == FALSE){
	$this->notification = "User does not exist.";
		} else {
			
			// User's email exists within the database, now pull their info for verification
			$this->getUserInfo();
			
			// See if the hash in the users browser matches what we've stored in the database
			if(hash('sha1', $this->origRegInfo['s_email']) == $this->userInfo['hash']) {
				
				if($this->confirmUser() == TRUE){ 		
				// Yay, it matched!		
				$this->notification = "Your ".$this->APP_NAME." acount has been confirmed.<br /><a href=\"http://login.thesalespal.com\" >Click Here</a> to login.";
				}
			}
		}
			
}

function confirmUser(){
	$args = func_get_args();
	$email = $this->origRegInfo['s_email'];
	
	try{
		
	$mysqli = new mysqli($this->system_dbhost, $this->system_dbuser, $this->system_dbpass, $this->system_dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
 	/* Prepare a select statement */
	$query = "UPDATE `user` SET `isconfirmed`='Y' WHERE `email`='".$email."';";
	$mysqli->query($query);
	$mysqli->commit();
	$mysqli->close();
	return true;
	}
		catch (Exception $e) {
			die($e->getMessage());
		}
}

function printNotification(){
	if($this->notification){
	echo $this->notification;
	}
}

function addToCounter($fieldModified){
	if(!isset($_SESSION)){session_start();
	$_SESSION['modifiedCount'] = 0;
	$_SESSION['nonModifiedCount'] = 0;
	$_SESSION['totalCount'] = 0;
	}
	
	if($fieldModified == TRUE){
		$_SESSION['modifiedCount']++;	
	} elseif($fieldModified == FALSE) {
		$_SESSION['nonModifiedCount']++;
	} 
		$_SESSION['totalCount']++;
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

function insertStmt($tbl,  $regInfo){

$sqlcommand = "INSERT INTO `".$tbl."` (";	
$sqlfields = "";
$sqlvalues = " VALUES (";
$count = (int) count($regInfo);
	$x=0;
	$y=0;
	foreach($regInfo as $name=>$value){
	$type = substr($name, 0, 2);
	$this->countFieldTypes($type);
	}
	
	$modifiedCount = $_SESSION['modifiedCount'];
	$nonModifiedCount = $_SESSION['nonModifiedCount'];
	$totalCount = $_SESSION['totalCount'];
		foreach($regInfo as $name=>$value){
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
return $sql;

}

function selectStmt($tbl, $regInfo){
$sqlcolumns = "";
$count = (int) count($regInfo);
	$x=0;
	$y=0;
	foreach($regInfo as $name=>$value){
	$type = substr($name, 0, 2);
	$this->countFieldTypes($type);
	}
	
	$modifiedCount = $_SESSION['modifiedCount'];
	$nonModifiedCount = $_SESSION['nonModifiedCount'];
	$totalCount = $_SESSION['totalCount'];
		foreach($regInfo as $name=>$value){
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
$sqlwhere = "WHERE username='".$username."';";
$sql = $sqlcommand.$sqlcolumns.$sqlfrom.$sqlwhere;
return $sql;
}

### Password Functions ###

function createSalt(){    
$string = md5(uniqid(rand(), true));    
return substr($string, 0, 32);
}

function createHash(){
	$hash = hash('sha256', $this->origRegInfo['s_password']);
	$hash = hash('sha256', $this->origRegInfo['s_salt'] . $hash);
	return $hash;
}

}
?>