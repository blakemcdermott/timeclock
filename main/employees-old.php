<?php require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header(); ?>
<?php $get_page = $_SERVER['PHP_SELF']; $type = 'user';?>
 
    

   
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) && $_GET['type'] && $_GET['action'] == 'delete' ) { 

$affected_rows = $bootstrap->mysqliHandler->delete_record($_GET['id'],$_GET['type']); 
		if($affected_rows[0]) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Time Entry was successfully deleted!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e">Time Entry was unable to be deleted!</button>';
		}
unset($_GET);
} ?>         
  
     <h3>Employee Management</h3>
	     <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
        <a href="<?php echo $get_page; ?>?add=true" class="ui-btn ui-btn-icon-right ui-icon-plus ui-disabled">Add</a>
        <a href="<?php echo $get_page; ?>?edit=true" class="ui-btn ui-btn-icon-right ui-icon-edit ui-disabled">Edit</a>
        <a href="#popupDialog" href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop"  class="ui-btn ui-btn-icon-right ui-icon-delete ui-disabled <?php if(!$_GET['id'])echo 'ui-disabled';?>">Delete</a>
    </div>  
   
<?php if($_GET['id'] > 0 && is_numeric($_GET['id']) || $_POST['submit']) {
 	
	if($_POST['submit']){
		
		$affected_rows = $bootstrap->mysqliHandler->update_record($_POST);

		if($affected_rows[0] != -1 ) { 
			echo '<button class="ui-bar ui-bar-d" data-icon="info" data-theme="d" >Employee has been successfully updated!</button>';  
   		} else {
			echo '<button class="ui-bar ui-bar-e" data-icon="info" data-theme="e" >Employee update was unsuccessful!</button>';
		}
	}
	
	$id = $_GET['id'];
	$queryArgs[0] = "vwEmployeeData";
	$queryArgs[1] = "id";
	$queryArgs[2] = $id;
	$data = $bootstrap->mysqliHandler->getArray($queryArgs); 
?>
   <form action="<?php echo $get_page.'?type='.$type.'&id='. $data[0]['id']; ?>" name="<?php echo $type; ?>" method="post">
  
    <h3 class="ui-bar ui-bar-a">Personal Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="id">System ID:</label>
        <input disabled="disabled" type="text" name="id" id="id" value="<?php echo $data[0]['id']; ?>">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo $data[0]['firstname']; ?>">  

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $data[0]['lastname']; ?>">
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $data[0]['title']; ?>">     
                <label for="middlename">Middle Name:</label>
        <input type="text" name="middlename" id="middlename" value="<?php echo $data[0]['middlename']; ?>"> 
        </div>
        </div><!-- /grid-a -->

	<h3 class="ui-bar ui-bar-a">Company Info</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="employee_code">Employee Code:</label>
        <input type="number" data-clear-btn="false" type="text" name="employee_code" id="employee_code" value="<?php echo $data[0]['employee_code']; ?>"> 
        <label for="union_code">Union Code:</label>
        <input type="number" data-clear-btn="false" type="text" name="union_code" id="union_code" value="<?php echo $data[0]['union_code']; ?>">
        <label for="code">Code:</label>
        <input type="number" data-clear-btn="false" type="text" name="code" id="code" value="<?php echo $data[0]['code']; ?>">
        <label for="salaried">Salaried:</label>
            <select id="issalaried" name="issalaried" data-role="flipswitch">
            <?php if($data[0]['issalaried'] == 'Y') { ?>
                <option value="N" >No</option>
                <option selected="" value="Y" >Yes</option>
            <?php } else { ?>
            	<option selected="" value="N" >No</option> 
                <option value="Y" >Yes</option>
            <?php } ?>
            </select>
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="pdo">Per-Diam O:</label>
        <input type="number" data-clear-btn="false" type="text" name="pdo" id="pdo" value="<?php echo $data[0]['pdo']; ?>"> 
        <label for="pde">Per-Diam E:</label>
        <input type="number" data-clear-btn="false" type="text" name="pde" id="pde" value="<?php echo $data[0]['pde']; ?>">
        <label for="wage_rate">Hourly Rate:</label>
        <input type="text" data-clear-btn="false" type="text" name="wage_rate" id="wage_rate" value="<?php echo $data[0]['wage_rate']; ?>">
        </div>
        </div><!-- /grid-a -->

    <h3 class="ui-bar ui-bar-a">User Account</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" autocomplete="off" value="<?php echo $data[0]['email']; ?>"> 
        <label for="disabled">Account Status:</label>
            <select id="disabled" name="disabled" data-role="flipswitch">
            <?php if($data[0]['disabled'] == 'Y') { ?>
                <option selected="" value="Y" >Off</option>
                <option value="N" >On</option>
            <?php } else { ?>
            	<option value="Y" >Off</option>
                <option selected="" value="N" >On</option>    
            <?php } ?>
            </select>
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        <label for="new_password">Password:</label>
        <input data-clear-btn="true" type="password" name="new_password" id="new_password" value="">
        
        </div>
        </div><!-- /grid-a -->
        
        <h3 class="ui-bar ui-bar-a">User Permissions</h3>
        <div class="ui-grid-a">
        <div class="ui-block-a"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block A</div>-->
        	<?php $bootstrap->get_permission_checkboxes("administrator", $bootstrap->get_permission_roles( $data[0]['id'] ) ); ?>
        </div>
        <div class="ui-block-b"><!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
        	<?php $bootstrap->get_permission_checkboxes("user", $bootstrap->get_permission_roles( $data[0]['id'] ) ); ?>
        </div>
        </div><!-- /grid-a -->


    <input data-icon="check" type="submit" name="submit" value="Update Employee"> 
    <input type="hidden" id="meta_id" name="meta_id" value="<?php echo $data[0]['meta_id']; ?>" />
    <input type="hidden" id="id" name="id" value="<?php echo $data[0]['id']; ?>" /> 
    <input type="hidden" id="salt" name="salt" value="<?php echo $data[0]['salt']; ?>" /> 
	
    </form> 
<?php } else { ?>     
       <form>
            <input id="filterTable-input" data-type="search"  >
        </form>
        <table data-role="table" id="employee-table" data-filter="true" data-filter-reveal="true" data-input="#filterTable-input" data-mode="reflow" class="ui-responsive ui-employee-table">
            <thead>
            <tr class="ui-table-thead">
                <th >Edit</th>
                <th data-priority="persist">Full Name</th>
                <th data-priority="1">Emp. Code</th>
                <th data-priority="2">Union</th>
                <th data-priority="3">Code</th>
                <th data-priority="4">Title</th>
                <th data-priority="5">Status</th>
            </tr>
            </thead>
            <?php 
				$queryArgs[0] = "vwEmployees";
				$queryArgs[1] = "";
				$queryArgs[2] = "";
				$companyUserRecords = $bootstrap->mysqliHandler->getArray($queryArgs); 
				$totalUsers = $bootstrap->totalCompanyUsers();
				$x=0;
				foreach( $companyUserRecords as $a_key => $users ) {
					
					echo '<tr>'; 
					foreach ( $users as $key => $value ) {
						if($key == 'user_id'){ 
							echo '<td><a href="employees.php?id='.$value.'" class="ui-btn ui-icon-edit ui-btn-icon-notext ui-corner-all ui-mini-icon"></a></td>'; 
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
