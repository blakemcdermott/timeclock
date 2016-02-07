<?php
// Load Sales Pal application bootstrap
require_once ('../sp-bootstrap.php');

$data = $bootstrap->get_importdata(1, 0);
$data = $bootstrap->calc_importdata($data);

$file_url = $bootstrap->get_importdata_csv($data);
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="'.$file_url.'"');
?>