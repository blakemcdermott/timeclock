<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$ip = $_SERVER['REMOTE_ADDR'];
if(isset($_GET['s_email']) && isset($_GET['s_hash'])){
include('/hsphere/local/home/c124727/ams.thesalespal.com/main/inc/sp_registration.class.php');
$sp_reg = new sp_registration($_GET);

// Uncomment to show submitted registration info
//$sp_reg->printRegInfo();

$sp_reg->userExists();
$sp_reg->verifyConfirmationHash();
} 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META name="resource-type" content="document">
<META name="description" content="Silverstone Payments specializes in setting your business up with the lowest possible processing rates.">
<META name="keywords" content="Credit Card Processing, Silverstone, Silverstone Payments, Payments Processing, Merchant Accounts, 1.00%, Rates, 1% rates, lower processing rates, lower mechant account fees, Daily Discount, Batch fees, AVS fees, Credit Cards, Money Saving Tips, lower credit card rates">
<title>Payability - <?php echo $sp_reg->FULL_APP_NAME; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="external.css" />
</head>
<body style="background:none;">  
<div id="top_div"></div>
<div id="wrapper_all">
<div class="wrapperbg_top"></div>
<div id="wrapper_content">
<?php //include("header.php"); ?>
</div>
<div class="formwrapper" style="width:390px;margin-left:auto;margin-right:auto;">
<div class="formwrapperheader" ><?php echo $sp_reg->FULL_APP_NAME; ?> - Account Confirmation</div>
<table align="center">
<tr>
<td>
<?php 
if(isset($_GET['s_email']) && isset($_GET['s_hash']) && $sp_reg->notification){
echo "<img style=\"float:left;margin-top:8px;margin-right:5px;\" src=\"images/information-button.png\" alt=\"Message Notification\" />";
echo "<p style=\"font-size:11px;color:#4e68a9;\">";
$sp_reg->printNotification(); 
echo "</p>";
}
?>
</td>
</tr>
</table>
</form>
<!-- <p style="font-size:11px;color:#4e68a9;">Already registered? <a href="loginsql.php" >Click Here</a> to login</p> -->
</div>

</div>
</div>
<?php //include("footer.php"); ?>
</body>
</html>
