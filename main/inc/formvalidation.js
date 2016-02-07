function buildUsername() {
var firstname = document.getElementById('firstname').value;
var lastname = document.getElementById('lastname').value;
var username = document.getElementById('username').value;

	if(firstname != false && lastname != false	){
	// Create Username
	var	joined = firstname+lastname;
	var lowercase = joined.toLowerCase();
	var finalusername = lowercase.replace(/\s+/g, '');
	document.getElementById('username').value = finalusername;
	} else { document.getElementById('username').value = "";}
	
}

function confirmPassword() {
var password = document.getElementById('password').value;
var password1 = document.getElementById('password1').value;
	if(password && password1){
	if(password != password1){
	alert("Passwords must match");
	}
	}
}

function onload() {
}