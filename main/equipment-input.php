<?php
 require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header(); ?>
<?php $get_page = $_SERVER['PHP_SELF']; $type = 'time_entries'; 

$data = $bootstrap->get_equipment();

// Pull in most recently used employee list
//$previous_list = $bootstrap->get_lastsubmitted_emp($bootstrap->userInfo['id']);
//$employee_count = count($previous_list);
						  
?>
<script type="text/javascript">
	$(document).ready(function(){
    $('.check:button').toggle(function(){
        $('input:checkbox').attr('checked','checked');
        $(this).val('uncheck all')
    },function(){
        $('input:checkbox').removeAttr('checked');
        $(this).val('check all');        
    })
})
</script>
    <form action="submit.php" method="post">
    <fieldset> 
   <div data-role="content" >
      
            <input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $bootstrap->userInfo['id'];?>" > 
            <input name="mydate" id="mydate" type="text" data-role="date" data-inline="false" value="<?PHP echo date("m/d/Y"); ?>" >
            <input type="hidden" id="user_id" name="user_id" value="" > 
            <a href="#add-mechanic" id="mechanic-display" data-rel="popup" data-transition="pop" data-position-to="origin" class="ui-btn ui-corner-all ui-btn-icon-right ui-icon-edit">Select Mechanic...</a> 
            <div data-role="popup" data-overlay-theme="b" id="add-mechanic" style="padding:16px;" class="ui-content">
                	<p>Select Mechanic</p>
                     <ul id="all-mechanics" data-role="listview" data-filter="true" data-filter-placeholder="Search employees..." data-filter-reveal="true" data-theme="a" data-iconpos="right" >
						<?php 
                        $companyUserRecords = $bootstrap->get_active_employees();
                        foreach( $companyUserRecords as $a_key => $users ) {
                            foreach ( $users as $key => $value ) {
                                $$key = $value;
                            }
                            echo '<li mechanic-id="'.$id.'" data-filtertext="'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">';
                            echo '<a href="#">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
                        }
                        
                        
                        ?>
                   </ul>
                   <br />
                </div> 
			<ul id="all-records" data-role="listview" data-filter="true" data-filter-placeholder="Search items..." data-inset="true" data-filter-reveal="true" data-theme="a" >
            	
				<?php 
				foreach( $data as $a_key => $row ) {
					foreach ( $row as $key => $value ) {
						$$key = $value;
					}
					echo '<li employee-id="'.$id.'" data-filtertext="'. $equipment_code .'">';
					echo '<a href="#">'. $equipment_code .'</a></li>';
				}
				echo '<li data-iconpos="left" id="item-to-add" data-filtertext="" class="ui-screen-hidden" >';
				echo '<a href="#" ></a></li>';
				
				?>
           </ul>

            <div data-role="collapsible-set" data-theme="a" data-content-theme="a">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                Equipment Management
                </h3>

                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
           		<!-- Punch In/Punch Out buttons
              	<a href="#punchin" data-rel="popup" id="punch-in" type="button" data-inline="true" data-icon="plus" data-iconpos="left" value="In" data-mini="true" >In</a>
                <a href="#punchout" data-rel="popup" id="punch-out" type="button" data-inline="true" data-icon="minus" data-iconpos="left" value="Out" data-mini="true">Out</a>
                -->
                <a href="#" class="ui-btn ui-btn-icon-right ui-icon-delete" id="delete" onclick="delete_selected();" >Delete</a>
                <a href="#hours" data-rel="popup" data-transition="pop" id="employee-hours" class="ui-btn ui-btn-icon-right ui-icon-edit" >Hours</a>
                <a href="#options" data-rel="popup" data-transition="pop" id="employee-options" class="ui-btn ui-btn-icon-right ui-icon-gear">Options</a>
                <div data-role="popup" data-overlay-theme="b" id="hours" style="padding:10px;">
                    <p>Set Total Hours</p>
					<?php 
					echo timeSelect(23,50);					
					 ?>
                 	<button id="set-hours" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="set_hours();" >Set hours</button>
                </div> 
                  <div data-role="popup" data-overlay-theme="b" id="punchin" style="padding:10px;">
                    <p>Set punch-in time</p>
					<?php 
					echo timeSelect(12,25);					
					 ?>
                 	<button id="set-in-time" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="punchin_selected();" >Set time</button>
                </div>  
                <div data-role="popup" id="punchout" style="padding:10px;">
                    <p>Set punch-out time</p>
					<?php 
					echo timeSelect(12,25);					
					 ?>
                 	<button id="set-out-time" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="punchout_selected();" >Set time</button>
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
						   <input name="remove-me" data-theme="a" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No equipment added" data-mini="false">
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