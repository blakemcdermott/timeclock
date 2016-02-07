<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header(); ?>
         
    
     <h3>Settings</h3>
	
<?php if(count($_GET) > 0 || $_POST['submit']) { ?>
 
	
<?php } else { ?>     

         <div data-role="collapsible-set" data-theme="a" data-inset="false" data-collapsed="false">
         <div data-role="collapsible" data-collapsed="false" >
         	<h2>System Settings</h2>
            <ul class="rh_options" data-role="listview" >
                <li data-icon="gear" data-iconpos="left" class="ui-disabled" ><a href="settings-roles.php" >User Roles</a></li>
                <li data-icon="gear" data-iconpos="left" ><a href="settings-pay-period-hours.php" >Pay Period Hours</a></li>
            </ul>
         </div>
         </div>
   
   		<div data-role="collapsible-set" data-theme="a" data-inset="false" data-collapsed="false">
         <div data-role="collapsible" data-collapsed="false" >
         	<h2>Employee Settings</h2>
            <ul class="rh_options" data-role="listview" >
            	<li data-icon="gear" data-iconpos="left"><a href="settings-unions.php" >Union Settings</a></li>
            </ul>
         </div>
         </div>
   
   
<?php } ?>
<?php site_footer(); ?>
