<?php
/* Logout any currently logged in users */
        if(isset($_SESSION)){
        session_unset() ;
        session_destroy();
        }

include('main/inc/sp_login.class.php');

if(isset($_POST['login'])){
	
		$sp_login = new sp_login($_POST);
		$sp_login->authenticate();
		$sp_login->login();
		} else {
	expireCookies($_COOKIE['userInfo']);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META name="resource-type" content="document">
<META name="description" content="Silverstone Payments specializes in setting your business up with the lowest possible processing rates.">
<META name="keywords" content="Credit Card Processing, Silverstone, Silverstone Payments, Payments Processing, Merchant Accounts, 1.00%, Rates, 1% rates, lower processing rates, lower mechant account fees, Daily Discount, Batch fees, AVS fees, Credit Cards, Money Saving Tips, lower credit card rates">
<title>Russo Corporation - Time Clock</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="external.css" />
</head>
<body onLoad="document.getElementById('email').focus();">  
    <div id="top_div">
        <div style="width:479px;height:221px;margin:0px auto;">
        <img src="images/salespal_logo.png" width="479" height="221">
        </div>
    </div>
<div id="wrapper_all">
    <div class="formwrapper" style="margin-left:auto;margin-right:auto;margin-top:65px;">
    <div class="formwrapperheader" >Please sign in</div>
    <div id="notice"><div class="alert-box"><p>Please note, if you are coming from the Virtual Appointment Center, do not re-register. Your information has been transfered. Please use the <strong>email address</strong> that you originally signed up with to login to the sales pal.</p></div></div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login" >
    <input type="hidden" maxlength="15" size="15" name="ip" value="<?php echo $ip ?>" />
        <table align="left" class="login-holder" cellspacing="10">
        <?php 
        if(isset($_POST['login']) && $sp_login->notification){
        echo "<img style=\"float:left;margin-top:8px;margin-right:5px;\" src=\"images/information-button.png\" alt=\"Message Notification\" />";
        echo "<p style=\"font-size:11px;color:#4e68a9;\">";
        echo $sp_login->notification;
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
</div>
</div>
<div class="wrapperbg_top"></div>
<div id="wrapper_content">
</div>

</div>
</body>
</html>
