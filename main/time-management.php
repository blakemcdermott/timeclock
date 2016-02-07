<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header('time'); ?>
<?php 
$get_page = $_SERVER['PHP_SELF']; 
$type = 'time_entries'; 
$last_week = last_week_range(); 
if($_GET['from-date']) $from_date = date('Y-m-d',strtotime($_GET['from-date']));
if($_GET['to-date']) $to_date = date('Y-m-d',strtotime($_GET['to-date']));
if ( $_GET['from-date'] || $_GET['to-date'] ) $date_range_get_params = '&from-date=' . $from_date . '&to-date=' . $to_date;
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
     <h3>Time Management</h3>
	     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $get_page; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus ui-disabled">Add</a>
        <a href="<?php echo $get_page; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit ui-disabled">Edit</a>
        <a href="#popupDialog" href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop"  class="ui-btn ui-btn-icon-right ui-icon-delete <?php if(!$_GET['id'])echo 'ui-disabled';?>">Delete</a>
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
	$queryArgs[0] = "vwTimeEntries";
	$queryArgs[1] = "id";
	$queryArgs[2] = $id;
	$data = $bootstrap->mysqliHandler->getArray($queryArgs); 
	$options = $bootstrap->get_time_options($data[0]['options']);

	foreach($options as $option){
		
		switch($option['option']){
			case "ho":
				$ho = 'selected=""';
			break;
			case "hw":
				$hw = 'selected=""';
			break;
			case "va":
				$va = 'selected=""';
			break;
			case "po":
				$po = 'selected=""';
			break;
			case "pe":
				$pe = 'selected=""';
			break;
			case "mi":
				$mi = $option['value'];
			break;
			case "tt":
				$tt = $option['value'];
			break;
		}
	}
	if(!$ho && !$hw && !$va) $time_type_none = 'selected=""';
	if(!$po && !$pd) $perdiam_none = 'selected=""';
	
?>
    <form action="<?php echo $get_page.'?type='.$type.'&id='. $data[0]['id']; ?>" name="<?php echo $type; ?>" id="<?php echo $type; ?>" method="post">
  	<h3 class="ui-bar ui-bar-a">Time Entry Details</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="id">System ID:</label>
        <input disabled="disabled" type="text" name="id" id="id" value="<?php echo $data[0]['id']; ?>">
        <label for="hours">Hours:</label>
        <input type="text" data-clear-btn="false" name="hours" id="hours" value="<?php echo $data[0]['hours']; ?>">
        <label for="mileage">Mileage:</label>
        <input type="text" data-clear-btn="false" name="mileage" id="mileage" value="<?php echo $mi; ?>">
        <label for="travel_time">Travel Time:</label>
        <input type="text" data-clear-btn="false" name="travel_time" id="travel_time" value="<?php echo $tt; ?>">
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="job_id">Job ID:</label>
        <input type="number" data-clear-btn="false" name="job_id" id="job_id" value="<?php echo $data[0]['job_id']; ?>">
        <label for="date">Date:</label>
        <input type="text" data-clear-btn="false" data-inline="false" data-role="date" name="date" id="date" value="<?php echo $data[0]['date']; ?>">
		
        <label for="perdiam-select" class="perdiam-select">Perdiam</label>
        <select class="perdiam-select" id="perdiam-select" >
        <option <?php echo $perdiam_none; ?> value="">None</option>
        <option <?php echo $po; ?> value="po">Perdiam-o</option>
        <option <?php echo $pe; ?> value="pe">Perdiam-e</option>
        </select>
        <input type="hidden" id="perdiam" name="perdiam" /> 
        
        <label for="time_type-select" class="time_type-select">Time Type</label>
        <select class="time_type-select" id="time_type-select" >
        <option <?php echo $time_type_none; ?> value="">None</option>
        <option <?php echo $ho; ?> value="ho">Paid Holiday</option>
        <option <?php echo $hw; ?> value="hw">Worked Holiday</option>
        <option <?php echo $va; ?> value="va">Vacation</option>
        </select>
        <input type="hidden" id="time_type" name="time_type" /> 
        </div>
        </div><!-- /grid-a -->

	<h3 class="ui-bar ui-bar-a">Employee Related Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="employee_name">Employee Name:</label>
        <input disabled="disabled" type="text" name="employee_name" id="employee_name" value="<?php echo ucfirst(strtolower(trim($data[0]['firstname']))).' '. ucfirst(strtolower(trim($data[0]['middlename']))).' '. ucfirst(strtolower(trim($data[0]['lastname']))); ?>"> 
        <label for="pdo">Per-Diam O:</label>
        <input disabled="disabled" type="number" data-clear-btn="false" type="text" name="pdo" id="pdo" value="<?php echo $data[0]['pdo']; ?>"> 
        <label for="pde">Per-Diam E:</label>
        <input disabled="disabled" type="number" data-clear-btn="false" type="text" name="pde" id="pde" value="<?php echo $data[0]['pde']; ?>">
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="employee_code">Employee Code:</label>
        <input disabled="disabled" type="number" data-clear-btn="false" type="text" name="employee_code" id="employee_code" value="<?php echo $data[0]['employee_code']; ?>"> 
        <label for="union_code">Union Code:</label>
        <input disabled="disabled" type="number" data-clear-btn="false" type="text" name="union_code" id="union_code" value="<?php echo $data[0]['union_code']; ?>">
        <label for="code">Code:</label>
        <input disabled="disabled" type="number" data-clear-btn="false" type="text" name="code" id="code" value="<?php echo $data[0]['code']; ?>">
        </div>
        </div><!-- /grid-a -->

        <input data-icon="check" type="submit" name="submit" value="Update Time Entry"> 
    <input type="hidden" id="id" name="id" value="<?php echo $data[0]['id']; ?>" /> 
	
    </form> 
    
<?php } else { ?>     

       <form>
       		<label for="from-date">From date:</label>
       		<input name="from-date" id="from-date" type="text" data-role="date" data-inline="false"  value="<?PHP echo ($from_date)? date('m/d/Y',strtotime($from_date)) : $last_week[0]; ?>" > 
       		<label for="from-date">To date:</label>
            <input name="to-date" id="to-date" type="text" data-role="date" data-inline="false" value="<?PHP echo ($to_date)? date('m/d/Y',strtotime($to_date)) : $last_week[1]; ?>" >
            <button>Get Time Entries</button>
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
                <th data-priority="persist">Full Name</th>
                <th data-priority="1">Emp. Code</th>
                <th data-priority="2">Job ID</th>
                <th>Hours</th>
                <th data-priority="3">Date</th>
                <th>Created On</th>
            </tr>
            </thead>
            <?php 
				$last_week = x_week_range($last_week[0],'Y-m-d');
				$queryArgs[0] = "id, CONCAT(firstname,' ', lastname) AS `full_name`, employee_code, job_id, hours, date, createdon";
				$queryArgs[1] = "vwTimeEntries";
				$queryArgs[2] = "";
				$queryArgs[3] = "";
				if( $from_date || $to_date ) $queryArgs[4] = " WHERE date >= '".$from_date."' AND date <= '".$to_date."' ORDER BY job_id, date DESC";//.$set_start.", ".$records_per_set."";
				else $queryArgs[4] = " WHERE date >= '". $last_week[0] ."' AND date <= '". $last_week[1] ."' ORDER BY job_id, date DESC";//.$set_start.", ".$records_per_set."";
				$records = $bootstrap->mysqliHandler->getArray($queryArgs); 

				$x=0;
				foreach( $records as $a_key => $data ) {
					
					echo '<tr>'; 
					foreach ( $data as $key => $value ) {
						if($key == 'id'){ 
							echo '<td><a href="'.$get_page.'?id='.$value . $date_range_get_params . '" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
							} elseif ($key == 'employee_code'){
									echo '<td class="ui-vertical-middle" >';
									echo '#'.$value;
									echo '</td>';
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
