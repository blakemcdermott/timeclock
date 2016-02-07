<?php
require_once ('sp-bootstrap.php'); 
$bootstrap->isLoggedIn();
init_current_module();
$type = $module['name'].'_entries';

site_header( $system ); 

// Pull in most recently used employee list
$previous_list = $bootstrap->get_lastsubmitted_emp($bootstrap->userInfo['id']);
$employee_count = count($previous_list);
						  
?>
<style type="text/css">
.flip-checkbox { display:inline !important; }
/* Custom indentations are needed because the length of custom labels differs from
   the length of the standard labels */
.custom-size-flipswitch.ui-flipswitch .ui-btn.ui-flipswitch-on {
    text-indent: -5.9em;
}
.custom-size-flipswitch.ui-flipswitch .ui-flipswitch-off {
    text-indent: 0.5em;
}
/* Custom widths are needed because the length of custom labels differs from
   the length of the standard labels */
.custom-size-flipswitch.ui-flipswitch {
    width: 8.875em;
}
.custom-size-flipswitch.ui-flipswitch.ui-flipswitch-active {
    padding-left: 7em;
    width: 1.875em;
}
@media (min-width: 28em) {
    // Repeated from rule .ui-flipswitch above
    .ui-field-contain > label + .custom-size-flipswitch.ui-flipswitch {
        width: 1.875em;
    }
}
</style>
<script type="text/javascript">
	var type = '<?php echo $type; ?>';
</script>
    <form action="submit.php?type=<?php echo $type; ?>&system=<?php echo $system; ?>" method="post">
    <fieldset> 
   <div data-role="content" >
		<?php if ( USER_ROLE == 1 ) { ?>
            <div style="text-align:right;"><label for="flip-checkbox" class="flip-checkbox">Time Type:</label>
            <input type="checkbox" data-role="flipswitch" name="flip-checkbox" id="flip-checkbox" data-on-text="Punch-in" data-off-text="Standard" data-wrapper-class="custom-size-flipswitch">
            </div>
        <?php } ?>
        <input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $bootstrap->userInfo['id'];?>" > 
        <input name="mydate" id="mydate" type="text" data-role="date" data-inline="false" value="<?PHP echo date("m/d/Y"); ?>" > 
        <input id="job_id" name="job_id" value="<?php echo (count($previous_list) > 0)? $previous_list['data'][0]['job_id'] : "Job ID"; ?>" data-theme="a" type="text" >
        
        <ul id="all-records" data-role="listview" data-filter="true" data-filter="true" data-filter-placeholder="Search employees..." data-inset="true" data-filter-reveal="true" data-theme="a" data-icon="plus" data-iconpos="right" >
            <?php 
            $companyUserRecords = $bootstrap->get_active_employees();
            foreach( $companyUserRecords as $a_key => $users ) {
                foreach ( $users as $key => $value ) {
                    $$key = $value;
                }
                echo '<li record-id="'.$id.'" data-filtertext="'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">';
                echo '<a href="#">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
            }
            
            
            ?>
       </ul>
               
        <div data-role="collapsible-set" data-theme="a" data-content-theme="a">
        <div data-role="collapsible" data-collapsed="false">
            <h3>
            Employee Management
            </h3>

            <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
            <a href="#" class="ui-btn ui-btn-icon-right ui-icon-delete" id="delete" onclick="delete_selected();" >Delete</a>
            <?php if ( USER_ROLE == 1 ) { ?>
                <!--Punch In/Punch Out buttons-->
                <a href="#punchin" data-rel="popup" id="punch-in" class="ui-btn ui-btn-icon-right ui-icon-edit ui-screen-hidden" >Punch-in</a>
                <a href="#punchout" data-rel="popup" id="punch-out" class="ui-btn ui-btn-icon-right ui-icon-edit ui-screen-hidden">Punch-out</a>
            <?php } ?>
            <a href="#hours" data-rel="popup" data-transition="pop" id="employee-hours" class="ui-btn ui-btn-icon-right ui-icon-edit" >Hours</a>
            <a href="#options" data-rel="popup" data-transition="pop" id="employee-options" class="ui-btn ui-btn-icon-right ui-icon-gear">Options</a>
            <div data-role="popup" data-overlay-theme="b" id="hours" style="padding:10px;">
                <p>Set Total Hours</p>
                <?php 
                echo timeSelect(23,50);					
                 ?>
                <button id="set-hours" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="set_hours();" >Set hours</button>
            </div> 
              <div data-role="popup" id="punchin" style="padding:10px;">
                <p>Set punch-in time</p>
                <?php 
                echo timeSelect(12,25);					
                 ?>
                <button id="set-in-time" data-rel="back" data-theme="a" data-mini="false" onClick="punchin_selected();" >Set time</button>
            </div>  
            <div data-role="popup" id="punchout" style="padding:10px;">
                <p>Set punch-out time</p>
                <?php 
                echo timeSelect(12,25);					
                 ?>
                <button id="set-out-time" data-rel="back" data-theme="a" data-mini="false" onClick="punchout_selected();" >Set time</button>
            </div>
            <div data-role="popup" data-overlay-theme="b" id="options" style="padding:10px;">
                <p>Set Options</p>
                 <div data-role="controlgroup" data-type="vertical">
                <label for="perdiam-o">Per-diam:O</label><input id="perdiam-o" value="Per-diam-o" data-theme="a" type="checkbox">
                <label for="perdiam-e">Per-diam:E</label><input id="perdiam-e" value="Per-diam-e" data-theme="a" type="checkbox">
                <label for="holiday">Holiday Pay</label> <input id="holiday" value="Holiday Pay" data-theme="a" type="checkbox">
                <label for="vacation">Vacation</label> <input id="vacation" value="Vacation" data-theme="a" type="checkbox">
                </div>
                <input id="mileage" value="Mileage" data-theme="a" type="text" >
                <input id="traveltime" value="Travel Time" data-theme="a" type="text" >
                <button id="set-options" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="options_selected();" >Set Options</button>
            </div> 
           </div>
           <fieldset data-role="controlgroup" data-type="horizontal">
                <input type="checkbox" name="selectall" id="selectall" data-mini="true" >
                <label for="selectall">Select All</label>
            </fieldset>

            <div class="ui-grid-a">
                 <div id="added-employees" data-role="fieldcontain">
                       <fieldset data-role="controlgroup" data-type="horizontal" data-mini="false" id="remove-me" >
                   <?php if (!$employee_count) { ?>
                       <input name="remove-me" data-theme="a" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No employees added" data-mini="false">
                        <?php } ?> 
                       </fieldset>
                        
                       <fieldset data-role="controlgroup" data-type="vertical" data-mini="false" id="employees" >
                        <?php 	if (count($employee_count) > 0) {  ?>
                            <script type="text/javascript">
                                count = <?php echo $employee_count ?>;
                                employeecount = <?php echo $employee_count ?>;
                                checkboxcount = <?php echo $employee_count ?>;
                            </script>
                            <?php
                                    foreach ($previous_list as $key => $emp) {
                                        if(is_int($key)) echo $emp;
                                    }
                                }
                        ?>
                       </fieldset>
                    </div>
                 </div>
            </div>
        </div>
            
            <input type="submit" name="submit" data-inline="true" data-icon="arrow-r" data-iconpos="right"
            value="Submit" data-mini="true">
           </fieldset>
        </form>
    </div>
<?php site_footer(); ?>