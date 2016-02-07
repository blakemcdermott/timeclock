<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$ip = $_SERVER['REMOTE_ADDR'];
if(isset($_POST['register'])){
include('main/inc/sp_registration.class.php');

$sp_Reg = new sp_registration($_POST);
$sp_Reg->userExists();
$sp_Reg->register();
$sp_Reg->confirmUser();
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

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="registration" >
<input type="hidden" maxlength="15" size="15" name="s_ip" value="<?php echo $ip ?>" />
<input type="hidden" maxlength="15" size="15" name="s_disabled" value="N" />
<input type="hidden" maxlength="15" size="15" name="s_salt" value="" />
<input type="hidden" maxlength="15" size="15" name="directory" value="<?php echo $_SERVER['PHP_SELF'] ?>" />
<table align="center">

<?php 
if(isset($_POST['register']) && $sp_Reg->notification){
echo "<img style=\"float:left;margin-top:0px;margin-right:5px;\" src=\"images/information-button.png\" alt=\"Message Notification\" />";
echo "<p style=\"font-size:11px;color:#4e68a9;\">";
$sp_Reg->printNotification(); 
echo "</p>";
}
?>
<tr>
<td width="132">First Name </td><td width="191" class="login">
<div class="input-box"><input type="text" maxlength="20" size="20" name="s_firstname" id="firstname" onChange="buildUsername();" /></div>
</td>
</tr>
<tr>
<td width="132">Last Name </td><td width="191" class="login">
<div class="input-box"><input type="text" maxlength="20" size="20" name="s_lastname" id="lastname" onChange="buildUsername();"  /></div>
</td>
</tr>
<tr>
<td>Phone </td><td class="login"><div class="input-box"><input type="text" maxlength="25" size="15" name="s_phone" id="phone" /></div></td>
</tr>
<tr>
<td>Street Address </td><td class="login"><div class="input-box"><input type="text" maxlength="60" size="15" name="s_address1" id="address1" /></div></td>
</tr>
<tr>
<td>City </td><td class="login"><div class="input-box"><input type="text" maxlength="60" size="10" name="s_city" id="city" /></div></td>
</tr>
<tr>
<td>State </td>
<td class="login">
<div class="input-box"><select name="s_state" id="state">
	<option value="">Select State</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select></div></td>
</tr>
<tr>
<td>Zip </td><td class="login"><div class="input-box"><input type="text" maxlength="9" size="9" name="s_zip" id="zip" /></div></td>
</tr>

<tr>
<td width="132">E-mail </td><td width="191" class="login">
<div class="input-box"><input type="text" maxlength="60" size="20" name="s_email" id="email"  /></div>
</td>
</tr>

<tr>
<td>Password </td><td class="login"><div class="input-box"><input type="password" maxlength="20" size="20" name="s_password" id="password" /></div></td>
</tr>
</tr>

<tr>
<td>Retype Password </td><td class="login"><div class="input-box"><input type="password" maxlength="20" size="20" name="password1" id="password1" onBlur="confirmPassword()" /></div></td>
</tr>
<tr>
<td><input type="submit" name="register" id="register" value="Register" /></td>
</tr>
<tr><td>
<?php $sp_Reg->getRegistrationFolder(); ?>
</td></tr>
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