<?php 
global $bootstrap;
$user_role = $bootstrap->get_user_role();
define('USER_ROLE',$user_role);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>Russo Time System</title>
      
  <!-- jQuery and jQuery Mobile -->
	<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> 
    <script type="text/javascript">
		$(document).bind("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script type='text/javascript' src="/main/js/jquery.fileDownload.js"></script> 	
    <script type='text/javascript' src="/main/js/functions.js"></script> 
    
   <!-- jQuery UI and DatePicker -->

    <link rel="stylesheet" href="/main/css/jquery.mobile.datepicker.css" />
    <script src="/main/jquery/jquery.ui.datepicker.js"></script>
    <script id="mobile-datepicker" src="/main/jquery/jquery.mobile.datepicker-beta.js"></script>

   	
	<script type="text/javascript">
	$(document).bind("mobileinit", function(){
				$.mobile.listview.prototype.options.filterPlaceholder = "Search employees...";
			});	
	$(document).on('pageshow', 'div[data-role*="page"],div[data-role*="dialog"]', function () {
		 (function () {
			var script = document.createElement('script'); script.type = 'text/javascript'; script.async = true;
			script.src = '/main/js/timeinput-custom.js';
			var one = document.getElementsByTagName('script')[0]; one.parentNode.insertBefore(script, one);
			var script = document.createElement('script'); script.type = 'text/javascript'; script.async = true;
			script.src = '/main/js/jquey.functions.js';
			var two = document.getElementsByTagName('script')[0]; one.parentNode.insertBefore(script, two);
		})();
	});
	</script>  

<!-- Extra Codiqa features -->
  <link rel="stylesheet" href="custom.css">
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.core.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.mode.calbox.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/i18n/jquery.mobile.datebox.i18n.en_US.utf8.js"></script>

  <!-- Extra Codiqa features -->
  <script src="https://d10ajoocuyu32n.cloudfront.net/codiqa.ext.js"></script>
<link rel="stylesheet" media="print" type="text/css" href="/main/css/print.css" />  
  <link rel="stylesheet" type="text/css" href="/main/css/responsive.style.css" />
  <link rel="stylesheet" type="text/css" href="/main/css/style.css" />
 </head>
<body>
<!-- Home -->
<div data-role="page" id="wrapper">
    
	<?php switch( $system ) {
		case "time": ?>
		<?php if ( $user_role < 4 ) { ?>
        
            <div id="header-wrapper">
                <div class="header">
                    <h3 id="site-title">Russo: Time System</h3>
                </div>
            </div> 
            <?php if($bootstrap) { ?>
            <div id="navigation-wrapper">
                 <div id="nav-menu" data-role="navbar">
                    <ul>
                        <?php 
                        if($user_role < 2 ) { 
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li> 
                                <li ><a  href="/main/time-input.php" >Time Input</a></li>
                                <li ><a href="/main/time-management.php">Time Management</a></li>
                                <li ><a href="/main/settings.php">Settings</a></li>
                                <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        } else {
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li>
                                <li ><a  href="/main/time-input.php" >Time Input</a></li>
								<li ><a  href="/main/reports.php" >Reports</a></li>
                                 <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        <?php } 
	break;
	case "confidential_time": ?>
		<?php if ( $user_role == 6 || $user_role == 7 || $user_role == 1 ) { ?>
        
            <div id="header-wrapper">
                <div class="header">
                    <h3 id="site-title">Russo: Confidential Time System</h3>
                </div>
            </div> 
            <?php if($bootstrap) { ?>
            <div id="navigation-wrapper">
                 <div id="nav-menu" data-role="navbar">
                    <ul>
                        <?php 
                        if( $user_role == 6 || $user_role == 1 ) { 
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li> 
                                <li ><a  href="/main/confidential-time-input.php" >Confidential Time Input</a></li>
                                <li ><a href="/main/confidential-time-management.php">Time Management</a></li>
								<li ><a href="/main/settings.php">Settings</a></li>
                                <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        } else {
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li>
                                <li ><a  href="/main/confidential-time-input.php" >My Time</a></li>
                                 <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        <?php } 
	break;
	case "punch_clock": ?>
		<?php if ( $user_role == 6 || $user_role == 7 || $user_role == 1 ) { ?>
        
            <div id="header-wrapper">
                <div class="header">
                    <h3 id="site-title">Russo: Confidential Time System</h3>
                </div>
            </div> 
            <?php if($bootstrap) { ?>
            <div id="navigation-wrapper">
                 <div id="nav-menu" data-role="navbar">
                    <ul>
                        <?php 
                        if( $user_role == 6 || $user_role == 1 ) { 
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li> 
                                <li ><a  href="/main/confidential-time-input.php" >Confidential Time Input</a></li>
                                <li ><a href="/main/confidential-time-management.php">Time Management</a></li>
								<li ><a href="/main/settings.php">Settings</a></li>
                                <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        } else {
                            echo '<li ><a  href="/main/default.php" >Dashboard</a></li>
                                <li ><a  href="/main/confidential-time-input.php" >My Time</a></li>
                                 <li ><a href="/" data-ajax="false" >Logout</a></li>
                            ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        <?php } 
	break;
	case 'inventory':
	if ( $user_role == 1 || $user_role == 4 || $user_role == 5 ) { ?>
    	<div id="header-wrapper">
            <div class="header">
                <h3 id="site-title">Russo: Inventory System</h3>
            </div>
        </div> 
        <?php if($bootstrap) { ?>
        <div id="navigation-wrapper">
             <div id="nav-menu" data-role="navbar">
                <ul>
                    <?php 
                    if( $user_role == 4 || $user_role == 1) { 
                        echo '<li ><a  href="/main/default.php" >Dashboard</a></li> 
                            <li ><a  href="/main/inventory.php" >Inventory</a></li>
							<li><a href="inventory-checkout.php" >Inventory Checkout</a></li>
                            <li ><a href="/main/inventory-checkouthistory.php">Checkout History</a></li>
                            <li ><a href="/" data-ajax="false" >Logout</a></li>
                        ';
                    } else {
                        echo '<li ><a  href="/main/default.php" >Dashboard</a></li>
						<li><a href="inventory-checkout.php" >Inventory Checkout</a></li>
                           <li ><a href="/main/inventory-checkouthistory.php">Checkout History</a></li>
                            <li ><a href="/" data-ajax="false" >Logout</a></li>
                        ';
                    }
                    ?>
                </ul>
            </div>
        </div>
    	<?php } ?>
    
    
    <?php } 
	break;
	}
	?>
	<div class="inside-wrap">
    	<div data-role="content" id="main-content" >
     