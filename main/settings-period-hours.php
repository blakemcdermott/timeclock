<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();
$system = 'time'; 
$type = 'pay_period_hours';
site_header( $system ); 
$get_page = $_SERVER['PHP_SELF']; 
?>
<?php site_header( $system ); ?>
         
    
     <h3><a href="/main/settings.php">Settings</a>  > <?php echo ($_GET['id'])? '<a href="'.$get_page.'">Pay Period Hours</a>' : 'Pay Period Hours'; ?></h3>
     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus">Add</a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit">Edit</a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=true" class="ui-btn ui-btn-icon-right ui-icon-delete">Delete</a>
    </div>
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) || $_POST['submit'] || $_GET['add'] == 'true' ) {

	if($_POST['submit']){
		
		$affected_rows = $bootstrap->mysqliHandler->update_record($_POST); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Pay Period Hours have been successfully updated!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" >Pay Period Hours update was unsuccessful!</button>';
			//echo '<div data-role="content">Record ID: '.$affected_rows[1].'<br />Query :'.$affected_rows[2].'</div>';
		}
	}
	
	$id = $_GET['id'];
	$queryArgs[0] = "options";
	$queryArgs[1] = "id";
	$queryArgs[2] = $id;
	$system_data = $bootstrap->mysqliHandler->getArray($queryArgs); 
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?type=union&id=<?php echo $system_data[0]['id']; ?>" name="union" method="post">
  
    <h3 class="ui-bar ui-bar-a">Pay Period Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="id">System ID:</label>
        <input disabled="disabled" type="text" name="id" id="id" value="<?php echo $system_data[0]['id']; ?>"> 
        <label for="name">Union Name:</label>
        <input type="text" data-clear-btn="false" type="text" name="name" id="name" value="<?php echo $system_data[0]['name']; ?>"> 
        <label for="week_day">Weekday Hours:</label>
        <input type="number" data-clear-btn="false" type="text" name="week_day" id="week_day" value="<?php echo $system_data[0]['week_day']; ?>">
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="saturday">Saturday Rate:</label>
        <input type="text" name="saturday" id="saturday" value="<?php echo $system_data[0]['saturday']; ?>">  
        <label for="sunday">Sunday Rate:</label>
        <input type="text" name="sunday" id="sunday" value="<?php echo $system_data[0]['sunday']; ?>"> 
        <label for="holiday">Holiday Rate:</label>
        <input type="text" name="holiday" id="holiday" value="<?php echo $system_data[0]['holiday']; ?>">          
        </div>
        </div><!-- /grid-a -->

    <input data-icon="check" type="submit" name="submit" value="Update Union"> 
	<input type="hidden" id="id" name="id" value="<?php echo $system_data[0]['id']; ?>" />
    </form> 
<?php } else { ?>     
       <form>
            <input id="filterTable-input" data-type="search">
        </form>
        <table data-role="table" id="union-table" data-filter="true" data-filter-reveal="false" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-employee-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="persist">Effective Date Range</th>
                <th data-priority="1">Date Name</th>
                <th data-priority="2">Period Hours</th>
            </tr>
            </thead>
            <?php 
				$queryArgs[0] = "options";
				$queryArgs[1] = "meta_key";
				$queryArgs[2] = "pay_period_hours";
				$pay_period_hours = $bootstrap->mysqliHandler->getArray($queryArgs); 
				$x=0;
				foreach( $pay_period_hours as $a_key => $effective_date ) {
					
					echo '<tr>'; 
					foreach ( $effective_date as $key => $value ) {
						if($key == 'id'){ 
							echo '<td><a href="'.$_SERVER['PHP_SELF'].'?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
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
