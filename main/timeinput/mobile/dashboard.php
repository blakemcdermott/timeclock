<?php
require_once ('../../sp-bootstrap.php');
$bootstrap->isLoggedIn('mobile');



?>

<?php mobile_header(); ?>


   <div data-role="content" >
      <div  style="text-align:center;"><img src="images/logo.png" alt="Russo Corporation" /></div>
       <ul data-role="listview" data-mini="true" data-inset="true" >
                <li data-icon="grid" data-theme="c" ><a href="dashboard.php">Dashboard</a></li>
                <li><a href="time-input.php">Time Input</a></li>
                <li><a href="time-sheets.php">Time Sheets</a></li>
                <li><a href="index.php">Log out</a></li>
            </ul>
   </div>

<?php mobile_footer(); ?>