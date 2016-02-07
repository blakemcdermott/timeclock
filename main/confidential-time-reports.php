<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();
init_current_module();
$type = $module['name'].'_entries';
site_header( $system ); ?> 
<script type="text/javascript">

    var module = '<?php echo json_encode($module); ?>';
	//alert(module_json);
      
</script>
<div data-role="collapsible-set" data-theme="a" data-inset="false">
         <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Visual Reports</h2>
            <ul class="rh_options" data-role="listview" >
               <li ><a href="#popupWeeklyMenu-employeecode" data-rel="popup" data-transition="slideup">Weekly Time Report - By Employee Code</a></li>
            </ul>
         </div>
 <?php if ($bootstrap->get_user_role() < 2 || $bootstrap->get_user_role() == 6 ) { ?>
         <div data-role="collapsible-set" data-theme="a" data-inset="false">
         <div data-role="collapsible" data-icon="" data-collapsed="false" data-expanded-icon="arrow-d" data-collapsed-icon="arrow-r" >
         	<h2>Import Reports</h2>
            <ul class="rh_options" data-role="listview" >
           <li><a href="#popupFullMenu" data-rel="popup" data-transition="slideup">System Import Report</a></li>
            </ul>
         </div>
         </div>
<?php } ?>
<div data-role="popup" id="popupWeeklyMenu-employeecode" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="employeecode-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Enter Employee Code</li>
        	<input type="number" id="employee_code" data-inline="true">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="date-input-employee_code" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="get_report('view','employee_code');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupWeeklyMenu-employee" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="employee-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Enter Employee Code</li>
        	<input type="number" id="employee_code" data-inline="true">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="date-input-employee" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('visual','view',true,'user_id');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupWeeklyMenu-inactive-employee" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="inactive-employee-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="inactive-date-input-employee" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('inactive_employees');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupWeeklyMenu-state" data-theme="a" data-overlay-theme="b" class="report-options" >
<form id="state-options" >
    <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="date-input-state" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
        <fieldset data-role="controlgroup" data-corners="false" class="no-margin" >
                <input type="radio" name="state" id="state-al" value="al" >
                <label for="state-al">Alabama</label>
                <input type="radio" name="state" id="state-tx" value="tx" >
                <label for="state-tx">Texas</label>
            </fieldset>
            <li data-icon="grid"><a id="viewWeeklyReport" class="triggerPopup" data-rel="back" href="" onclick="generate_report('visual','view',true,'state');" >View Report</a></li>
    </ul>
</form>
</div>

<div data-role="popup" id="popupFullMenu" data-theme="a" data-overlay-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:275px;">
        <li data-role="list-divider">Select Date</li>
        	 <input type="text" id="import-date-input" data-inline="true" data-role="date">
        <li data-role="list-divider">Choose an option</li>
            <li data-icon="grid"><a id="" class="triggerPopup" data-rel="back" href="" onclick="generate_report('confidential_time_import','view',true,'');" >View Report</a></li>
            <li data-icon="arrow-d"><a id="" href="" onclick="generate_report('confidential_time_import','download',true,'');" >Download Report</a></li>
    </ul>
        
<!--        <ul data-role="listview" data-inset="true" style="min-width:275px;">
            <li data-role="list-divider">Choose an action</li>
			<li data-icon="grid"><a class="triggerPopup" data-rel="back" href="" id="viewReport" onclick="generate_report('import','view');" >View Report</a></li>
            <li data-icon="arrow-d"><a href="" onclick="generate_report('import','download');" >Download Report</a></li>
           
        </ul>-->
</div>

<a style="display:none;" id="showPopup" href="#popupReport" data-rel="popup" data-position-to="window" data-transition="pop" ></a>
<div data-role="popup" id="popupReport" class="ui-content" style="max-width:100%">
    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
    
    <a class="ui-btn ui-btn-icon-right ui-icon-arrow-r" target="_blank" id="continue-to-print" href="#" >Continue to Print</a>
<div id="response" data-role="content" data-inset="true"></div>
</div>
<?php site_footer(); ?>
