<?php 
/*
* Page name: Inventory
*/
require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header('inventory'); ?>
<?php $get_page = $_SERVER['PHP_SELF']; 

// Database table name in use
$type = 'inventory';
?>
 
    

   
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) && $_GET['type'] && $_GET['action'] == 'delete' ) { 

$affected_rows = $bootstrap->mysqliHandler->delete_record($_GET['id'],$_GET['type']); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Inventory Entry was successfully deleted!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e">Inventory Entry was unable to be deleted!</button>';
		}
unset($_GET);
} ?>         
  
     <h3>Inventory Management</h3>
	     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $get_page; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus ui-disabled">Add</a>
        <a href="<?php echo $get_page; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit ui-disabled">Edit</a>
        <a href="#popupDialog" href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop"  class="ui-btn ui-btn-icon-right ui-icon-delete ui-disabled <?php if(!$_GET['id'])echo 'ui-disabled';?>">Delete</a>
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
	$queryArgs[0] = $type;
	$queryArgs[1] = "id";
	$queryArgs[2] = $id;
	$data = $bootstrap->mysqliHandler->getArray($queryArgs); 
?>
   <form action="<?php echo $get_page.'?type='.$type.'&id='. $data[0]['id']; ?>" name="<?php echo $type; ?>" method="post">
  
    <h3 class="ui-bar ui-bar-a">Item Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="id">System ID:</label>
        <input disabled="disabled" type="text" name="id" id="id" value="<?php echo $data[0]['id']; ?>">
        <label for="inventory_code">Inventory Code:</label>
        <input type="text" name="inventory_code" id="inventory_code" value="<?php echo $data[0]['inventory_code']; ?>">  
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $data[0]['name']; ?>">     
        <label for="quantity">Quantity:</label>
        <input type="text" name="quantity" id="quantity" value="<?php echo $data[0]['quantity']; ?>"> 
        </div>
        </div><!-- /grid-a -->

    <input data-icon="check" type="submit" name="submit" value="Update Item"> 
    <input type="hidden" id="id" name="id" value="<?php echo $data[0]['id']; ?>" /> 
	
    </form> 
<?php } else { ?>     
       <form>
            <input id="filterTable-input" data-type="search"  >
        </form>
        <table data-role="table" id="inventory-table" data-filter="true" data-filter-reveal="true" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-inventory-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="1">Invetory Code</th>
                <th data-priority="2">Name</th>
                <th >Quantity</th>
            </tr>
            </thead>
            <?php 
				$queryArgs[0] = $type;
				$queryArgs[1] = "";
				$queryArgs[2] = "";
				$records = $bootstrap->mysqliHandler->getArray($queryArgs); 

				foreach( $records as $a_key => $rows ) {
					
					echo '<tr>'; 
					foreach ( $rows as $key => $value ) {
						if($key == 'id'){ 
							echo '<td><a href="inventory.php?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
							} else {
									echo '<td class="ui-vertical-middle" >';
									echo $value;
									echo '</td>';
							}
					}
					echo '</tr>';

				}
				?>
        </table>
<?php } ?>

<?php site_footer(); ?>
