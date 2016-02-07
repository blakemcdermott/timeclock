<?php ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title></title>
    
  <!-- jQuery and jQuery Mobile -->
  <?php //if($_COOKIE['userInfo']['id'] != '788'){ ?>
  		<script type='text/javascript' src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
		<!--<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>-->
        <script type="text/javascript">
		$(document).bind("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
        <script type="text/javascript">
				
        $(document).bind("mobileinit", function(){
                     $.mobile.listview.prototype.options.filterPlaceholder = "Search employees...";
					 
                });
                
        $(document).on('pageshow', 'div[data-role*="page"],div[data-role*="dialog"]', function () {
             (function () {
                var script = document.createElement('script'); script.type = 'text/javascript'; script.async = true;
                script.src = 'js/timeinput-custom.js';
                var one = document.getElementsByTagName('script')[0]; one.parentNode.insertBefore(script, one);
                script.src = 'js/timesheet-custom.js';
                var two = document.getElementsByTagName('script')[0]; two.parentNode.insertBefore(script, two);
            })();
        });
        </script>  
        <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/latest/jquery.mobile.css" />
        <script type='text/javascript' src="/main/js/jquery.functions.js"></script> 
         <!-- jQuery UI and DatePicker -->

    <link rel="stylesheet" href="/main/css/jquery.mobile.datepicker.css" />
    <script src="/main/jquery/jquery.ui.datepicker.js"></script>
    <script id="mobile-datepicker" src="/main/jquery/jquery.mobile.datepicker.js"></script>
    <?php //} else {  ?>
    
		<!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript">
        $(document).bind("mobileinit", function(){
                     $.mobile.listview.prototype.options.filterPlaceholder = "Search employees...";
                });
                
        $(document).on('pageshow', 'div[data-role*="page"],div[data-role*="dialog"]', function () {
             (function () {
                var script = document.createElement('script'); script.type = 'text/javascript'; script.async = true;
                script.src = 'js/timeinput-custom.js';
                var one = document.getElementsByTagName('script')[0]; one.parentNode.insertBefore(script, one);
                script.src = 'js/timesheet-custom.js';
                var two = document.getElementsByTagName('script')[0]; two.parentNode.insertBefore(script, two);
            })();
        });
        </script>  
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />
		<script src="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>-->
        
    <?php //} ?>
	<link rel="stylesheet" type="text/css" href="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.min.css" /> 



<!-- Extra Codiqa features -->
  <link rel="stylesheet" href="custom.css">
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.core.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.mode.calbox.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/i18n/jquery.mobile.datebox.i18n.en_US.utf8.js"></script>

  <!-- Extra Codiqa features -->
  <script src="https://d10ajoocuyu32n.cloudfront.net/codiqa.ext.js"></script>
  <style type="text/css">
	.ui-datebox-container {
		background-color:white;
	}
  </style>
 </head>
<body>

<!-- Home -->
<div data-role="page" id="timeinput">
    <div data-theme="b" data-role="header">
        <h3>
            Russo Corp - Time Clock
        </h3>
         <a href="#main-menu" data-rel="popup" id="main-menu-btn" type="button" data-inline="true" data-icon="arrow-d" data-iconpos="left" value="Menu" data-mini="true">Menu</a>
         <div data-role="popup" id="main-menu">
            <ul data-role="listview" data-mini="true" data-inset="true" >
                <li data-icon="grid" data-theme="c" ><a href="dashboard.php">Dashboard</a></li>
                <li><a href="time-input.php">Time Input</a></li>
                <li><a href="time-sheets.php">Time Sheets</a></li>
                <li><a href="index.php">Log out</a></li>
            </ul>
    	</div>
    </div><!-- header -->
      
	