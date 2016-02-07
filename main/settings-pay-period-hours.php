<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();
$system = 'time'; 
$type = 'options';
$options_type = 'pay_period_hours';
$app_name = 'Pay Period Hours';
site_header( $system ); 
$get_page = $_SERVER['PHP_SELF']; 
?>
         
    
     <h3><a href="/main/settings.php">Settings</a>  > <?php echo ($_GET)? '<a href="'.$get_page.'">'.$app_name.'</a>' : $app_name; ?></h3>
     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus">Add</a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit">Edit</a>
        <a href="#popupDialog" href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop"  class="ui-btn ui-btn-icon-right ui-icon-delete <?php if(!$_GET['id'])echo 'ui-disabled';?>">Delete</a>
    </div>
<?php 
if($_GET['id'] > 0 && is_numeric($_GET['id']) && $_GET['type'] && $_GET['action'] == 'delete' ) { 
$affected_rows = $bootstrap->mysqliHandler->delete_record($_GET['id'],$type); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >'.$app_name.' was successfully deleted!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e">'.$app_name.' was unable to be deleted!</button>';
		}
unset($_GET);
} 

if ( $_GET['add'] == 'true' && $_GET['id'] < 1 ){
	?>
		<form action="<?php echo $get_page; ?>?add=true&type=<?php echo $type; ?>" name="<?php echo $type; ?>" method="post">
	<?php
	if($_POST['submit']){
		
		$affected_rows = $bootstrap->mysqliHandler->insert_record($_POST); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >'.$app_name.' have been successfully added!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" >'.$app_name.' could not added!</button>';
			//echo '<div data-role="content">Record ID: '.$affected_rows[1].'<br />Query :'.$affected_rows[2].'</div>';
		}
	}
}
elseif($_GET['id'] > 0 && is_numeric($_GET['id']) ) {

	if ( $_GET['id'] > 0 && is_numeric($_GET['id'] ) ) {
		if($_POST['submit']){
			$affected_rows = $bootstrap->mysqliHandler->update_record($_POST); 
			if($affected_rows[0]) { 
				echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >'.$app_name.' have been successfully updated!</button>';  
			} else {
				echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" >'.$app_name.' update was unsuccessful!</button>';
				//echo '<div data-role="content">Record ID: '.$affected_rows[1].'<br />Query :'.$affected_rows[2].'</div>';
			}
		}
	$id = $_GET['id'];
	$queryArgs[0] = "options";
	$queryArgs[1] = "id";
	$queryArgs[2] = $id;
	$data = $bootstrap->mysqliHandler->getArray($queryArgs); 
	$pay_period_options = $bootstrap->get_pay_period_options($data[0]['meta_value']);
	?>
	<form action="<?php echo $get_page; ?>?type=<?php echo $type; ?>&id=<?php echo $data[0]['id']; ?>" name="<?php echo $type; ?>" method="post">
<?php 
	} 
}

if ($_GET) {
?>    
    <h3 class="ui-bar ui-bar-a"><?php echo $app_name; ?> Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="id">System ID:</label>
        <input disabled="disabled" type="text" name="id" id="id" value="<?php echo $data[0]['id']; ?>"> 
        <label for="name">Name:</label>
        <input type="text" data-clear-btn="false" class="<?php echo ($data[0]['id'] == '1')? "ui-disabled" : "";?>"  type="text" name="name" id="name" value="<?php echo $data[0]['name']; ?>">
        <label for="date">Ending Date:</label>
        <input type="text" class="<?php echo ($data[0]['id'] == '1')? "ui-disabled" : "";?>" data-clear-btn="false" data-inline="false" data-role="date" name="date" id="date" value="<?php echo ($data[0]['id'] == '1')? "default" : $pay_period_options['nu']['date_hours'][0]; ?>">
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="union_hours">Union Hours:</label>
        <input type="text" name="union_hours" id="union_hours" value="<?php echo $pay_period_options['u']['date_hours'][1]; ?>">  
        <label for="non_union_hours">Non-union Hours:</label>
        <input type="text" name="non_union_hours" id="non_union_hours" value="<?php echo $pay_period_options['nu']['date_hours'][1]; ?>">        
        </div>
        </div><!-- /grid-a -->

    <input data-icon="check" type="submit" name="submit" value="Update <?php echo $app_name; ?>"> 
	<input type="hidden" id="id" name="id" value="<?php echo $data[0]['id']; ?>" />
    <input type="hidden" id="options_type" name="options_type" value="<?php echo $options_type; ?>" />
    </form> 
<?php 

	} else { ?>     
       <form>
            <input id="filterTable-input" data-type="search">
        </form>
        <table data-role="table" id="union-table" data-filter="true" data-filter-reveal="false" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-employee-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="persist">Name</th>
                <th data-priority="1">Effective Date Range</th>
                <th data-priority="2">Union Hours</th>
                <th data-priority="2">Non-union Hours</th>
            </tr>
            </thead>
            <?php 
				//$queryArgs[] = "id, name";
				$queryArgs[] = "options";
				$queryArgs[] = "meta_key";
				$queryArgs[] = "pay_period_hours";
				$pay_period_hours = $bootstrap->mysqliHandler->getArray($queryArgs); 
				$x=0;
				foreach( $pay_period_hours as $a_key => $data ) {
					
					echo '<tr>'; 
					foreach ( $data as $key => $value ) {
						if($key == 'id'){ 
							echo '<td><a href="'.$_SERVER['PHP_SELF'].'?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
							} 
							
							elseif($key == 'name') {
									echo '<td class="ui-vertical-middle" >';
									echo $value;
									echo '</td>';
							}
							
					}
					$pay_period_options = $bootstrap->get_pay_period_options($data['meta_value']);
					$date_range = x_week_range($pay_period_options['nu']['date_hours'][0],"m/d/Y");
					echo '<td class="ui-vertical-middle" >';
					echo($pay_period_options['nu']['date_hours'][0] != "default" )? $date_range[0]. " to ".$date_range[1] : "All Pay Periods" ;
					echo '</td>';
					echo '<td class="ui-vertical-middle" >';
					print_r($pay_period_options['u']['date_hours'][1]);
					echo '</td>';
					echo '<td class="ui-vertical-middle" >';
					print_r($pay_period_options['nu']['date_hours'][1]);
					echo '</td>';
					echo '</tr>';
					$x++;
				}
				?>
        </table>
<?php } ?>
<?php site_footer(); ?>
