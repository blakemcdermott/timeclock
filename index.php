<?php
if($_GET['beta'] != 'on' ) {
	//require_once ('main/inc/mobileredirect.php');
}

/* Logout any currently logged in users */
        if(isset($_SESSION)){
        session_unset() ;
        session_destroy();
        }

// Main loader


require_once ('main/sp-bootstrap.php');
if ( !$bootstrap || !isset($bootstrap) ) { $bootstrap = new sp_settings($_COOKIE['userInfo']); }

if(isset($_POST['login'])){
	try {
		$bootstrap = new sp_settings($_POST);
		$bootstrap->login();
	} catch (Exception $e) {
		$bootstrap->notification = $e->getMessage();
	}
		} else {
	expireCookies($_COOKIE['userInfo']);
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

 

	<title>Russo Corporation</title>

	

	<meta name="keywords" content="caissons, drilled shafts, drilled shaft, drilled piers, drilled pier, micropile, micropiles, shoring, blasting, tiebacks, drilling, excavating, utilities, trenching, foundation, deep foundation, shafts, piers, ground improvement, geotechnical services, Drilling, Shoring, Design-Build, Professional Engineering Services,  Secant Walls, Rock, Casing, Slurry, Jet Grouting, Permeation Grouting, Driven piles, Augured Piles, Water Cut-Off, Compaction Grouting, Expansive Soil Remediation, Earth Retention, augercast, sanitary sewer, water main" />	
	<meta name="description" content="Russo Corporation, a specialty contractor serving the Southeastern United States." />
	<meta name="robots" content="index,follow" /><!-- change into index, follow -->
				
	<link href="main/style.css" rel="stylesheet" type="text/css" />
    <title>Russo Corporation - Time Clock</title>
	
	<!--[if lte IE 6]>
		<script type="text/javascript" src="main/js/pngfix.js"></script>
		<script type="text/javascript" src="main/js/ie6.js"></script>
		<link rel="stylesheet" href=".main/ie6.css" type="text/css" />
	<![endif]-->
    <style type="text/css">
<!--
.style3 {
	font-size: 18px;
	font-weight: bold;
	color: #881E0A;
}
.style5 {font-size: 12px}
-->
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body onLoad="document.getElementById('email').focus();">  

<!--  / WRAPPER \ -->
<div id="wrapper">
	
	<!--  / MAIN CONTAINER \ -->
	<div id="mainCntr">		
		
		<!--  / HEADER CONTAINER \ -->
		<div id="headerCntr">
		  
            <!-- / LOGO \ -->
            <div class="logo"></div>
        <!-- \ LOGO / -->

			<!-- / header txt \ -->
			<p>&nbsp;</p>
		  <!-- \ header txt / -->
        </div>
		<!--  \ HEADER CONTAINER / -->
		
		<!--  / CONTENT CONTAINER \ -->
		<div id="contentCntr">
		
			<div class="formwrapper" style="margin-left:auto;margin-right:auto;margin-top:65px;">
        <div class="formwrapperheader" >Time Clock - Login</div>
            <div id="notice">
                <div class="alert-box">
                	<p>Supervisors please note, This web application is formated to properly fit and work on all iOS devices and Android devices. If you have problems properly accessing this application, please notify your office for support.</p>
                </div>
            </div>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login" >
        <input type="hidden" maxlength="15" size="15" name="ip" value="<?php echo $ip ?>" />
            <table align="left" class="login-holder" cellspacing="10">
            <?php 
            if(isset($_POST['login']) && $bootstrap->notification){
            echo "<img style=\"float:left;margin-top:3px;margin-right:5px;\" src=\"images/information-button.png\" alt=\"Message Notification\" />";
            echo "<p style=\"font-size:11px;color:#4e68a9;\">";
            echo $bootstrap->notification;
            echo "<br />".$_COOKIE['userInfo']['valid'];
            echo "</p>";
            }
             ?>
                <tr>
                <td width="132">Email </td><td width="191" class="login">
                <div class="input-box"><input type="text" maxlength="60" size="25" name="s_email" id="email"  /></div>
                </td>
                </tr>
                <tr>
                <td>Password </td><td class="login"><div class="input-box"><input type="password" maxlength="25" size="25" name="s_password" id="password" /></div></td>
                </tr>
                <tr>
                <td><input type="submit" name="login" id="login" value="Login" /></td>
                </tr>
            </table>
        </form>
    <p style="font-size:11px;color:#255cab;">Not a registerd user?<br /><a href="registration.php" >Click Here</a> to register!</p>
    <br style="clear:both;" />
    </div><!-- end formwrapper -->

			
			<div class="clear"></div>
		</div>
		<!--  \ CONTENT CONTAINER / -->
	</div>
	<!--  \ MAIN CONTAINER / -->

	<!--  / FOOTER CONTAINER \ -->
	<div id="footerCntr">
	<div id="footerCntrinner">
		<div class="center">
			&copy; Russo Corporation. All Rights Reserved		
            </div>
	</div>	
	</div>
	<!--  \ FOOTER CONTAINER / -->
</div>
<!--  \ WRAPPER / -->
</body>
</html>
