<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Russo Time System - Report</title>
<style type="text/css" media="all">
	body{background-color:#F9F9F9;}
	#page-wrapper {width:900px;margin:0px auto;}
	.qr-wrapper {float:left;padding:10px; width:100px;}
	td { border:1px solid #333 !important; padding:0px 3px; }
</style>
<style type="text/css" media="print">
div { float:left; }
</style>
</head>
<body>
<div>
    <div id="page-wrapper" >
    <?php 
    require_once ('../sp-bootstrap.php'); 
    $bootstrap->isLoggedIn();
    // Database table name in use
    $type = 'inventory';
        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = '../'.dirname(__FILE__).DIRECTORY_SEPARATOR.'inventory'.DIRECTORY_SEPARATOR.'qr_codes'.DIRECTORY_SEPARATOR;
        //html PNG location prefix
        $PNG_WEB_DIR = 'qr_codes/';   
     
        $queryArgs[0] = $type;
        $queryArgs[1] = "";
        $queryArgs[2] = "";
        $records = $bootstrap->mysqliHandler->getArray($queryArgs); 
    
        foreach( $records as $a_key => $row ) {
            //print_r($row);
            $filename = $PNG_WEB_DIR . $row['inventory_code'] . '.png';
			//display generated file
			$html .= '<div id="'.$row['inventory_code'].'" class="qr-wrapper" >';
			$html .= '<img src="'.$filename.'" /><br />'; 
			$html .= '<p align="center">' . $row['inventory_code'] . '</p></div>' . "\n";
        }
    	
		echo $html;
    ?>
    <script type="text/javascript">
		window.print();
    </script>
    </div>
</div>
</body>
</html>
