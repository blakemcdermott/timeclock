<?php 
require_once (toMainDir().'inc/sp_settings.class.php');
if ( !$bootstrap || !isset($bootstrap) ) { 	global $bootstrap; $bootstrap = new sp_settings($_COOKIE['userInfo']); }

// Build a relative link to the "main" directory
function toMainDir(){
	$mainDir = "/main";
	$thisDir = $_SERVER['PHP_SELF'];
		$dirMatch = preg_match_all('/\/([^\/]+)/',$thisDir,$currentDirs);
			if($dirMatch){
				$relativeDir = "";
				// Get the number of directories out the current location is. 
				// -2 adjusts the count to compensate for the main folder and the php file itself.
				$numDirs = (int) count($currentDirs[1]) - 2;
				//echo ($numDirs > 1) ? $numDirs." directories up<br />" : $numDirs." directory up<br />" ;
				for ($i = 1; $i <= $numDirs; $i++) {
					$relativeDir .= "../";
				}
				//echo "Relative directory link: ".$relativeDir;
				return $relativeDir;	
			}
}
?>