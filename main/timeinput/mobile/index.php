<?php
header('Location: http://timeclock.fsii.co/');
require_once (toMainDir().'inc/sp_settings.class.php');
unset($_COOKIE['userInfo']);
if(isset($_POST['login'])){
	try {
		$bootstrap = new sp_settings($_POST);
		$bootstrap->login('mobile');
	} catch (Exception $e) {
		//echo 'could not log in';
		echo $bootstrap->notification = $e->getMessage();
	}
		} else {
	
		/* Logout any currently logged in users */
        if(isset($_SESSION)){
        session_unset() ;
        session_destroy();
        }
}

// Build a relative link to the "main" directory
function toMainDir(){
	$mainDir = "/main";
	$thisDir = $_SERVER['PHP_SELF'];
		$dirMatch = preg_match_all('/\/([^\/]+)/',$thisDir,$currentDirs);
			if($dirMatch){
				$relativeDir = "";
				// Get the number of directories out the current location is. 
				// -2 adjusts the count to compensate for the main folder and the php file itself.
				$numDirs = (int) count($currentDirs[1]) - 2;
				//echo ($numDirs > 1) ? $numDirs." directories up<br />" : $numDirs." directory up<br />" ;
				for ($i = 1; $i <= $numDirs; $i++) {
					$relativeDir .= "../";
				}
				//echo "Relative directory link: ".$relativeDir;
				return $relativeDir;	
			}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title></title>
  <link rel="stylesheet" href="https://d10ajoocuyu32n.cloudfront.net/mobile/1.3.1/jquery.mobile-1.3.1.min.css">
  
  <!-- Extra Codiqa features -->
  <link rel="stylesheet" href="codiqa.ext.css">
  
  <!-- jQuery and jQuery Mobile -->
  <script src="https://d10ajoocuyu32n.cloudfront.net/jquery-1.9.1.min.js"></script>
  <script src="https://d10ajoocuyu32n.cloudfront.net/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>

  <!-- Extra Codiqa features -->
  <script src="https://d10ajoocuyu32n.cloudfront.net/codiqa.ext.js"></script>
   
</head>
<body>

<!-- Home -->
<div data-role="page" id="page1">
    <div data-theme="b" data-role="header" >
        <h3>
            Russo Corp - Time Clock
        </h3>
    </div>
    <?php if( $bootstrap->notification ) {  ?>
    <div data-theme="a" data-role="header" align="center">
      
            <?php echo $bootstrap->notification; ?>
     
    </div>
    <?php } ?>
   
    <div data-role="content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login" data-ajax="false">
       
        <input type="hidden" maxlength="15" size="15" name="ip" value="<?php echo $ip ?>" />
            <div data-role="fieldcontain">
                <label for="email"">
                    Email
                </label>
                <input name="s_email" id="email" placeholder="" value="" type="text"
                data-mini="true">
            </div>
            <div data-role="fieldcontain">
                <label for="password">
                    Password
                </label>
                <input name="s_password" id="password" placeholder="" value="" type="password"
                data-mini="true">
            </div>
            <div id="rememberme" data-role="fieldcontain">
                <fieldset data-role="controlgroup" data-type="vertical">
                    <legend>
                        Remember me
                    </legend>
                    <input id="radio1" name="" value="rememberme" type="radio">
                    <label for="radio1">
                        Remember Me
                    </label>
                </fieldset>
            </div>
            <input type="submit" data-inline="true" data-icon="arrow-r" data-iconpos="right"
            name="login" id="login" value="Login" data-mini="true">
        </form>
        
    </div>
</div>
</body>
</html>
