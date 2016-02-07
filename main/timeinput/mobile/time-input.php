<?php
header('Location: http://timeclock.fsii.co/');
require_once ('../../sp-bootstrap.php');
$bootstrap->isLoggedIn('mobile');

//$queryArgs[0] = "id, firstname, lastname ";
//$queryArgs[1] = "vwEmployeeData";
//$queryArgs[2] = "";
//$queryArgs[3] = "";
//$queryArgs[4] = ' WHERE disabled = "N" ORDER BY id';
//$companyUserRecords = $bootstrap->mysqliHandler->getArray($queryArgs);
//$totalUsers = $bootstrap->totalCompanyUsers();
$companyUserRecords = $bootstrap->get_active_employees();

// Pull in most recently used employee list
$previous_list = $bootstrap->get_lastsubmitted_emp($bootstrap->userInfo['id']);
$employee_count = count($previous_list);
						  
?>

<?php mobile_header(); ?>

    <form action="submit.php" method="post">
    
  		<fieldset> 
    <div data-theme="g" data-role="header">
<input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $bootstrap->userInfo['id'];?>" > 
   
<input name="mydate" id="mydate" type="date" data-role="datebox" data-theme="b"
   data-options='{"mode": "calbox"}' value="<?PHP echo date("m/d/Y"); ?>" > 
<input id="job_id" name="job_id" value="<?php echo (count($previous_list) > 0)? $previous_list['data'][0]['job_id'] : "Job ID"; ?>" data-theme="b" type="text" >       
    </div>
   <div data-role="content" >
      
       
			<ul id="all-employees" data-role="listview" data-filter="true" data-filter-placeholder="Search employees..." data-filter-reveal="true" data-theme="d" data-icon="plus" data-iconpos="right" >
            	
				<?php 
				
				foreach( $companyUserRecords as $a_key => $users ) {
					foreach ( $users as $key => $value ) {
						$$key = $value;
					}
					echo '<li employee-id="'.$id.'" data-filtertext="'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">';
					echo '<a href="#">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
				}
				
				
				?>
           </ul>
            
       <br />
        
            <div data-role="collapsible-set" data-theme="b" data-content-theme="b">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                Employee Management
                </h3>
                <div data-role="controlgroup" data-type="horizontal" style="float:left;">
               
                <input id="delete" onclick="delete_selected();" type="button" data-inline="true" data-icon="delete" data-iconpos="left" value="Delete" data-mini="true">
                </div>
                <div data-role="controlgroup" data-type="horizontal" style="position:relative;left:5px;">
           
               <!-- Punch In/Punch Out buttons
               <a href="#punchin" data-rel="popup" id="punch-in" type="button" data-inline="true" data-icon="plus" data-iconpos="left" value="In" data-mini="true" >In</a>
                <a href="#punchout" data-rel="popup" id="punch-out" type="button" data-inline="true" data-icon="minus" data-iconpos="left" value="Out" data-mini="true">Out</a>
                -->
                <a href="#hours" data-rel="popup" data-transition="fade"  id="employee-hours" type="button" data-inline="true" data-icon="gear" data-iconpos="left" value="Hours" data-mini="true">Hours</a>
                <a href="#options" data-rel="popup" data-transition="fade" id="employee-options" type="button" data-inline="true" data-icon="gear" data-iconpos="left" value="Options" data-mini="true">Options</a>
                <div data-role="popup" id="hours" style="padding:10px;">
                    <p>Set Total Hours</p>
					<?php 
					echo timeSelect(23,50);					
					 ?>
                 	<button data-rel="back" data-transition="fade" id="set-hours" data-theme="c" data-mini="false" onClick="set_hours();" >Set hours</button>
                </div> 
                  <div data-role="popup" id="punchin" style="padding:10px;">
                    <p>Set punch-in time</p>
					<?php 
					echo timeSelect(12,25);					
					 ?>
                 	<button data-rel="back" data-transition="fade" id="set-in-time" data-theme="c" data-mini="false" onClick="punchin_selected();" >Set time</button>
                </div>  
                <div data-role="popup" id="punchout" style="padding:10px;">
                    <p>Set punch-out time</p>
					<?php 
					echo timeSelect(12,25);					
					 ?>
                 	<button data-rel="back" data-transition="pop" id="set-out-time" data-theme="c" data-mini="false" onClick="punchout_selected();" >Set time</button>
                </div>
                <div data-role="popup" id="options" style="padding:10px;">
                	<p>Set Options</p>
                     <div data-role="controlgroup" data-type="vertical">
                    <label for="perdiam-o">Per-diam:O</label><input id="perdiam-o" value="Per-diam-o" data-theme="c" type="checkbox">
                    <label for="perdiam-e">Per-diam:E</label><input id="perdiam-e" value="Per-diam-e" data-theme="c" type="checkbox">
                    <label for="holiday">Paid Holiday</label> <input id="holiday" value="Holiday Pay" data-theme="c" type="checkbox">
                    <label for="worked-holiday">Worked Holiday</label> <input id="worked-holiday" value="Worked Holiday" data-theme="c" type="checkbox">
                    <label for="vacation">Vacation</label> <input id="vacation" value="Vacation" data-theme="c" type="checkbox">
                    </div>
                    <input id="mileage" value="Mileage" data-theme="c" type="text" >
                    <input id="traveltime" value="Travel Time" data-theme="c" type="text" >
                    <button data-rel="back" data-transition="fade" id="set-options" data-theme="c" data-mini="false" onClick="options_selected();" >Set Options</button>
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
						   <input name="remove-me" data-theme="e" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No employees added" data-mini="false">
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

<?php mobile_footer(); ?>