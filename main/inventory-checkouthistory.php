<?php 
/*
* Page name: Inventory Checkout History
*/
require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header('inventory'); ?>
<?php 
$get_page = $_SERVER['PHP_SELF']; 
$type = 'inventory_checkout'; 
$last_week = last_week_range(); 
if($_GET['from-date']) $from_date = date('Y-m-d',strtotime($_GET['from-date']));
if($_GET['to-date']) $to_date = date('Y-m-d',strtotime($_GET['to-date']));
?>
<script type="text/javascript">

$(document).on('pageshow', 'div[data-role*="page"],div[data-role*="dialog"]', function () {
		 (function () {
			var script = document.createElement('script'); script.type = 'text/javascript'; script.async = true;
			script.src = 'js/jquery.datepicker.custom.js';
			var three = document.getElementsByTagName('script')[0]; three.parentNode.insertBefore(script, three);
		})();
	});
$(document).ready(function(){
	
$('#perdiam').val($('#perdiam-select').val());
	$('#time_type').val($('#time_type-select').val());
	
	$("#time_type-select").change( function() {
		$('#time_type').val($('#time_type-select').val());
	});
	$("#perdiam-select").change(function() {
		$('#perdiam').val($('#perdiam-select').val());
	});
});
</script>
   
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) && $_GET['type'] && $_GET['action'] == 'delete' ) { 
    


$affected_rows = $bootstrap->mysqliHandler->delete_record($_GET['id'],$_GET['type']); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Time Entry was successfully deleted!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" Time Entry was unable to be deleted!</button>';
		}
unset($_GET);
} ?>
     <h3>Checkout History</h3>
	     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $get_page; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus ui-disabled">Add</a>
        <a href="<?php echo $get_page; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit ui-disabled">Edit</a>
        <a href="#popupDialog" href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop"  class="ui-disabled ui-btn ui-btn-icon-right ui-icon-delete <?php if(!$_GET['id'])echo 'ui-disabled';?>">Delete</a>
    </div>	
	
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) || $_POST['submit']) {
 	
	if($_POST['submit']){
		
		$affected_rows = $bootstrap->mysqliHandler->update_record($_POST); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Employee has been successfully updated!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" >Employee update was unsuccessful!</button>';
		}
	}
	
	$id = $_GET['id'];
	$queryArgs[0] = "vwInventoryCheckout";
	$queryArgs[1] = "checkout_id";
	$queryArgs[2] = $id;
	$data = $bootstrap->mysqliHandler->getArray($queryArgs); 
	
?>
    <form action="<?php echo $get_page.'?type='.$type.'&id='. $data[0]['checkout_id']; ?>" name="<?php echo $type; ?>" id="<?php echo $type; ?>" method="post">
  	<h3 class="ui-bar ui-bar-a">Items Checked Out</h3>
        <ul data-role="listview" data-theme="a" data-inset="true">
                <?php
			   	foreach ($data as $array ) {
					  foreach ( $array as $key => $value ) {
						$$key = $value;
						}
					echo '<li><a href="#">'. $inventory_code .': '. $name .' <span class="ui-li-count">'.$quantity.'</span></a></li>';
				}
				?>
            </ul>
     <?php
	 $companyUserRecords = $bootstrap->get_active_employees();
	foreach( $companyUserRecords as $a_key => $users ) {
		/*foreach ( $users as $key => $value ) {
			//$$key = $value;
		}*/
		$full_name = ucfirst( strtolower( trim( $users['firstname'] ) ) ).' '.ucfirst( strtolower( trim( $users['lastname'] ) ) );
		$company_users_dropdown .= '<li employee-id="'.$users['id'].'" data-filtertext="'.$full_name.'">';
		$company_users_dropdown .= '<a href="#">'.$full_name.'</a></li>';
	}
	 ?>
    <h3 class="ui-bar ui-bar-a">Checkout Details</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="job_id">Job ID:</label>
        <input type="text" name="job_id" id="job_id" value="<?php echo $job_id; ?>">
        
       <!-- <label for="superintendant">Superintendant:</label>
        <input type="text" name="superintendant" id="superintendant" value="<?php echo $superintendant; ?>"> -->
        <input type="hidden" name="superintendant_id" id="superintendant_id" value="<?php echo $superintendant_id; ?>">
        <a href="#superintendant" id="superintendant-display" data-rel="popup" data-transition="pop" data-position-to="origin" class="ui-btn ui-corner-all ui-btn-icon-right ui-icon-edit">Job Superintendant: <?php echo $superintendant; ?></a> 
            <div data-role="popup" data-overlay-theme="b" id="superintendant" style="padding:16px;" class="ui-content">
            	<p>Select Superintendant</p>
                <ul id="all-employees" data-role="listview" data-filter="true" data-filter-placeholder="Search employees..." data-filter-reveal="true" data-theme="a" data-iconpos="right" >
					<?php echo $company_users_dropdown; ?>
                </ul>
                <br />
            </div>
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="checkout_id">Checkout ID:</label>
        <input disabled="disabled" type="text" name="checkout_id" id="checkout_id" value="<?php echo $checkout_id; ?>">
        <label for="submitted_by">Submitted By:</label>
        <input disabled="disabled" type="text" name="submitted_by" id="submitted_by" value="<?php echo $submitted_by; ?>">
        <label for="received_by">Received By:</label>
        <input disabled="disabled" type="text" name="received_by" id="received_by" value="<?php echo $received_by; ?>">
        </div>
        </div><!-- /grid-a -->
          <input data-icon="check" type="submit" name="submit" value="Update Inventory Entry" > 
    <input type="hidden" id="id" name="id" value="<?php echo $checkout_id; ?>" /> 
	
    </form> 
    
<?php } else { ?>     

       <form>
       		<label for="from-date">From date:</label>
       		<input name="from-date" id="from-date" type="text" data-role="date" data-inline="false"  value="<?PHP echo ($from_date)? date('m/d/Y',strtotime($from_date)) : $last_week[0]; ?>" > 
       		<label for="from-date">To date:</label>
            <input name="to-date" id="to-date" type="text" data-role="date" data-inline="false" value="<?PHP echo ($to_date)? date('m/d/Y',strtotime($to_date)) : $last_week[1]; ?>" >
            <button>Get Inventory Entries</button>
            <input id="filterTable-input" data-type="search"  >
        </form>
        <?php
		/*$queryArgs[0] = "id, COUNT(*) as `record_count` ";
		$queryArgs[1] = "vwTimeEntries";
		$count = $bootstrap->mysqliHandler->getArray($queryArgs); 
		$record_count = $count[0]['record_count'];
		
		$records_per_set = 300;
		$set_start = ($_GET['set'] > 1)? ($_GET['set'] - 1) * 15 : 0 ;
		if(!$_GET['set']) $_GET['set'] = 1;
		$total_sets = ceil($record_count / $records_per_set );
		
		echo '<div data-role="controlgroup" data-type="horizontal" class="ui-mini">';
		for($x=1;$x<=$total_sets;$x++){
			echo ($_GET['set'] == $x)? '<a href="'.$get_page.'?set='.$x.'" class="ui-btn ui-btn-active" >'.$x.'</a>' : '<a href="'.$get_page.'?set='.$x.'" class="ui-btn" >'.$x.'</a>';
		}
		echo '</div>';*/
		?>
    
        <table data-role="table" id="employee-table" data-filter="true" data-filter-reveal="false" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-employee-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="1">Job ID</th>
                <th data-priority="2">Submitted by</th>
                <th data-priority="3">Superintendant</th>
                <th data-priority="4">Received By</th>
                <th>Date</th>
                <th>Created On</th>
            </tr>
            </thead>
            <?php 
				$last_week = x_week_range($last_week[0],'Y-m-d');
				$queryArgs[0] = "checkout_id, job_id, submitted_by, superintendant, received_by, date";
				$queryArgs[1] = "vwInventoryCheckoutHistory";
				$queryArgs[2] = "";
				$queryArgs[3] = "";
				if( $from_date || $to_date ) $queryArgs[4] = " WHERE date >= '".$from_date."' AND date <= '".$to_date."' ORDER BY job_id, date DESC";//.$set_start.", ".$records_per_set."";
				else $queryArgs[4] = " WHERE date >= '". $last_week[0] ."' AND date <= '". $last_week[1] ."' ORDER BY job_id, date DESC";//.$set_start.", ".$records_per_set."";
				$records = $bootstrap->mysqliHandler->getArray($queryArgs); 

				$x=0;
				foreach( $records as $a_key => $data ) {
					
					echo '<tr>'; 
					foreach ( $data as $key => $value ) {
						if($key == 'checkout_id'){ 
							echo '<td><a href="'.$get_page.'?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
							} else {
									echo '<td class="ui-vertical-middle" >';
									echo $value;
									echo '</td>';
							}
					}
					echo '</tr>';
					$x++;
				}
				?>
        </table>
        
<?php } ?>

<?php site_footer(); ?>
