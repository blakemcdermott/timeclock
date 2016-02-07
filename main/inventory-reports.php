<?php 
/*
* Page name: Inventory Reports
*/
require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header('inventory'); ?>
         <h3>Inventory Reports</h3>
         <div data-role="collapsible-set" data-theme="a" data-inset="false">
         <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Visual Reports</h2>
            <ul class="rh_options" data-role="listview" >
               <li ><a href="#popupWeeklyMenu-jobid" data-rel="popup" data-transition="slideup">Weekly Inventory Report - By Job</a></li>
               <li ><a href="#popupWeeklyMenu-inventorycode" data-rel="popup" data-transition="slideup">Weekly Inventory Report - By Item Number</a></li>
               <li ><a href="#popupWeeklyMenu-checkout" data-rel="popup" data-transition="slideup">Weekly Inventory Report - By Checkout</a></li>
               <li ><a href="#popupWeeklyMenu-superintendant" data-rel="popup" data-transition="slideup">Weekly Inventory Report - By Supervisor</a></li>
            </ul>
         </div>
         </div>
 <?php if ($bootstrap->get_user_role() < 2 ) { ?>
         <div data-role="collapsible-set" data-theme="a" data-inset="false">
         <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Import Reports</h2>
            <ul class="rh_options" data-role="listview" >
           <li><a href="#popupFullMenu" data-rel="popup" data-transition="slideup">System Import Report</a></li>
            </ul>
         </div>
         </div>
<?php } ?>
<div data-role="popup" id="popupWeeklyMenu-jobid" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="jobid-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <!--<li data-role="list-divider">Enter Job ID</li>
        	<input type="number" id="jobid" data-inline="true">-->
        <li data-role="list-divider">From Date</li>
        	 <input type="text" id="from-date-input-jobid" data-inline="true" data-role="date">
        <li data-role="list-divider">To Date</li>
             <input type="text" id="to-date-input-jobid" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inventory','view',true,'job_id');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupWeeklyMenu-inventorycode" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="inventorycode-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Enter Inventory Code</li>
        	<input type="text" id="inventory_code" data-inline="true">
        <li data-role="list-divider">From Date</li>
        	 <input type="text" id="from-date-input-inventorycode" data-inline="true" data-role="date">
        <li data-role="list-divider">To Date</li>
             <input type="text" id="to-date-input-inventorycode" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inventory','view',true,'inventory_code');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupWeeklyMenu-checkout" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="checkout-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <!--<li data-role="list-divider">Enter Employee Code</li>
        	<input type="number" id="employee_code" data-inline="true">-->
        <li data-role="list-divider">From Date</li>
        	 <input type="text" id="from-date-input-checkout" data-inline="true" data-role="date">
        <li data-role="list-divider">To Date</li>
             <input type="text" id="to-date-input-checkout" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inventory','view',true,'checkout_id');" >View Report</a></li>
    </ul>
</form>
</div>
<div data-role="popup" id="popupWeeklyMenu-superintendant" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="superintendant-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <!--<li data-role="list-divider">Enter Employee Code</li>
        	<input type="number" id="employee_code" data-inline="true">-->
        <li data-role="list-divider">From Date</li>
        	 <input type="text" id="from-date-input-superintendant" data-inline="true" data-role="date">
        <li data-role="list-divider">To Date</li>
             <input type="text" id="to-date-input-superintendant" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inventory','view',true,'superintendant_id');" >View Report</a></li>
    </ul>
</form>
</div>
 
<div data-role="popup" id="popupFullMenu" data-theme="a" data-overlay-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:275px;">
       <li data-role="list-divider">From Date</li>
        	 <input type="text" id="from-date-input" data-inline="true" data-role="date">
        <li data-role="list-divider">To Date</li>
             <input type="text" id="to-date-input" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inventory','view',true);" >View Report</a></li>
            <li data-icon="arrow-d"><a id="" href="" onclick="generate_report('inventory','download',true);" >Download Report</a></li>
    </ul>
</div>

<a style="display:none;" id="showPopup" href="#popupReport" data-rel="popup" data-position-to="window" data-transition="pop" ></a>
<div data-role="popup" id="popupReport" class="ui-content" style="max-width:100%">
    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
    
    <a class="ui-btn ui-btn-icon-right ui-icon-arrow-r" target="_blank" id="continue-to-print" href="#" >Continue to Print</a>
<div id="response" data-role="content" data-inset="true"></div>
</div>


<?php site_footer(); ?>
