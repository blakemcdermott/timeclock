<?php
/*
* Page name: Inventory Checkout
*/
require_once ('sp-bootstrap.php'); 
$bootstrap->isLoggedIn();
init_current_module();
$type = $module['name'].'_checkout';

site_header( $system ); 

$data = $bootstrap->get_inventory();
$companyUserRecords = $bootstrap->get_active_employees();
foreach( $companyUserRecords as $a_key => $users ) {
	foreach ( $users as $key => $value ) {
		$$key = $value;
	}
	$company_users_dropdown .= '<li employee-id="'.$id.'" data-filtertext="'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'">';
	$company_users_dropdown .= '<a href="#">'.ucfirst( strtolower( trim( $firstname ) ) ).' '.ucfirst( strtolower( trim( $lastname ) ) ).'</a></li>';
}

// Pull in most recently used employee list
//$previous_list = $bootstrap->get_lastsubmitted_emp($bootstrap->userInfo['id']);
//$employee_count = count($previous_list);
						  
?>
<script type="text/javascript">
	var type = '<?php echo $type; ?>';
</script>
    <form action="submit.php?type=<?php echo $type; ?>&system=<?php echo $system; ?>" method="post">
    <fieldset> 
   <div data-role="content" >
      
            <input type="hidden" id="submitter_id" name="submitter_id" value="<?php echo $bootstrap->userInfo['id'];?>" > 
            <input type="hidden" id="superintendant_id" name="superintendant_id" value="" >
            <input type="hidden" id="receiving_user_id" name="receiving_user_id" value="" >
            <input name="mydate" id="mydate" type="text" data-role="date" data-inline="false" value="<?PHP echo date("m/d/Y"); ?>" >
            <input id="job_id" name="job_id" value="<?php echo (count($previous_list) > 0)? $previous_list['data'][0]['job_id'] : "Job ID"; ?>" data-theme="a" type="text" >
       		<a href="#superintendant" id="superintendant-display" data-rel="popup" data-transition="pop" data-position-to="origin" class="ui-btn ui-corner-all ui-btn-icon-right ui-icon-edit">Job Superintendant...</a> 
            <div data-role="popup" data-overlay-theme="b" id="superintendant" style="padding:16px;" class="ui-content">
            	<p>Select Superintendant</p>
                <ul id="all-employees" data-role="listview" data-filter="true" data-filter-placeholder="Search employees..." data-filter-reveal="true" data-theme="a" data-iconpos="right" >
					<?php echo $company_users_dropdown; ?>
                </ul>
                <br />
            </div>
            <a href="#received-by" id="received-by-display" data-rel="popup" data-transition="pop" data-position-to="origin" class="ui-btn ui-corner-all ui-btn-icon-right ui-icon-edit">Items received by...</a> 
            <div data-role="popup" data-overlay-theme="b" id="received-by" style="padding:16px;" class="ui-content">
            	<p>Select Mechanic</p>
                <ul id="all-employees" data-role="listview" data-filter="true" data-filter-placeholder="Search employees..." data-filter-reveal="true" data-theme="a" data-iconpos="right" >
					<?php echo $company_users_dropdown; ?>
                </ul>
                <br />
            </div> 
			<ul id="all-records" data-filter-placeholder="Search inventory..." data-role="listview" data-filter="true" data-filter-reveal="true" data-inset="true" data-theme="a" >
            	
				<?php 
				foreach( $data as $a_key => $row ) {
					foreach ( $row as $key => $value ) {
						$$key = $value;
					}
					echo '<li record-id="'.$id.'" data-filtertext="'. $inventory_code .'" onClick="add_inventory_quantity_pop(\''.$id.'\');" >';
					echo '<a href="#" >'. $inventory_code .': ' . htmlspecialchars($name) .'</a></li>';
				}
				echo '<li data-iconpos="left" id="item-to-add" data-filtertext="" class="ui-screen-hidden" >';
				echo '<a href="#" ></a></li>';
				
				?>
           </ul>	
        
            <div data-role="collapsible-set" data-theme="a" data-content-theme="a">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                Checkout Management
                <div style="float:right;" id="submitter-name"></div>
                </h3>

                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
           		<!-- Punch In/Punch Out buttons
              	<a href="#punchin" data-rel="popup" id="punch-in" type="button" data-inline="true" data-icon="plus" data-iconpos="left" value="In" data-mini="true" >In</a>
                <a href="#punchout" data-rel="popup" id="punch-out" type="button" data-inline="true" data-icon="minus" data-iconpos="left" value="Out" data-mini="true">Out</a>
                -->
                <a href="#" class="ui-btn ui-btn-icon-right ui-icon-delete" id="delete" onclick="delete_selected();" >Delete</a>
                <a href="#item-quantity" data-rel="popup" data-transition="pop" id="quantity-btn" class="ui-btn ui-btn-icon-right ui-icon-gear">Quantity</a>
                <div data-role="popup" data-overlay-theme="b" id="item-quantity" style="padding:10px;">
                	<p>Quantity</p>
                     <div data-role="controlgroup" data-type="vertical">
                    <input id="quantity" value="Quantity" autocomplete="off" data-theme="a" type="number" >
                    <button id="set-quantity" data-rel="back" data-transition="pop" data-theme="a" data-mini="false" onClick="set_quantity();" >Set Quantity</button>
                    </div>
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
						   <input name="remove-me" data-theme="a" type="button" data-inline="false" data-icon="info" data-iconpos="left" value="No inventory added" data-mini="false">
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