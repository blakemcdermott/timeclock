<?php 
/*
* Page name: Inventory QR
*/
require_once ('sp-bootstrap.php'); $bootstrap->isLoggedIn();?>
<?php site_header('inventory'); ?>
<?php $get_page = $_SERVER['PHP_SELF']; ?>

<?php
// Database table name in use
$type = 'inventory';
	//set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'inventory'.DIRECTORY_SEPARATOR.'qr_codes'.DIRECTORY_SEPARATOR;
    //html PNG location prefix
    $PNG_WEB_DIR = 'inventory/qr_codes/';
    include ("inc/qr/qrlib.php");    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        
		}

 
				$queryArgs[0] = $type;
				$queryArgs[1] = "";
				$queryArgs[2] = "";
				$records = $bootstrap->mysqliHandler->getArray($queryArgs); 
				echo '<a href="inventory/print-qr.php" target="_blank" id="print" class="ui-btn">Print QR Codes</a>';
				echo '
				<script type="text/javascript">
					var qr_inventory_codes = []; var count = 1;
                </script>
				';
                foreach( $records as $a_key => $row ) {
					
					//print_r($row);
					$filename = $PNG_WEB_DIR . $row['inventory_code'] . '.png';
					if ( !file_exists($filename) ){
						// user data
						QRcode::png($row['inventory_code'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);; 
						//display generated file
						$html .= '<div class="qr-wrapper" id="'.$row['inventory_code'].'" style="float:left;padding:10px;" >';
						$html .= '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><br />'; 
						$html .= '<p align="center">' . $row['inventory_code'] . '<br />'. $row['name'] .'</p></div>';
					} else {
						//display generated file
						$html .= '<div class="qr-wrapper" id="'.$row['inventory_code'].'" style="float:left;padding:10px;" >';
						$html .= '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><br />'; 
						$html .= '<p align="center">' . $row['inventory_code'] . '<br />'. $row['name'] .'</p></div>';
					}
					/*echo '
					<script type="text/javascript">
					    qr_inventory_codes[count] = "'.$row['inventory_code'].'";
						count++;
					</script>
					';*/
                    
				}
				echo $html;
?>
<script type="text/javascript">
$(document).ready(function(e) {	
/*	var img = '';
	for(x=1;x<=count;x++){
		img = '<img src="inventory/qr_codes/' + qr_inventory_codes[x] + '.png" />';
			$("#" + qr_inventory_codes[x]).prepend(img);
	
	}*/
});
	
</script>
<?php site_footer(); ?>
