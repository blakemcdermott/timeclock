$(document).ready(function(){
appLoader = function(application){
	switch(application){
		case "crm":
		var appName = "spcrm";
		return appName;
		break;
		
		case "sites":
		var appName = "spsites";
		return appName;
		break;
		
		case "partners":
		var appName = "sppartners";
		return appName;
		break;
		
		case "reports":
		var appName = "spreports";
		return appName;
		break;
		
		}

}


ajaxCall = function(appName){
	//$("application_display").empty().html();
	var appLocation = appName + "/" + "index.php";
	$("#application_display").load(appLocation);
	
}

});