<!-- The CSS styles for the page -->

<style>

h1 {
    color: black;
    text-align: left;
    font-size: 16px;
}

h2 {
    color: black;
    text-align: center;
    font-size: 16px;
    right: 0%;
}

h3 {
    color: black;
    font-size: 12px;
    text-align: right;
}

.right {
    float: right;
    width: 300px;
}

.showButton {
	float: center;
	font-size: 24px;
	width: 20%;
	heigth: 50%;
	position: relative;
	right: -40%;
}

.apptType {
	float: center;
	width: 20%;
	heigth: 50%;
	position: relative;
	right: -40%;
	background-color: white;
}

</style>

<?php

#####################################################################
#
# This file handles getting the advisors from the database based on 
# selected majors
#
#####################################################################

print("<form action='Results2.php' method='post' name='SendApptType'>");

include("headHTML.html");
include("Utilities.php");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$cbMajors = $_POST['cbMajors'];

#these are used for the showing of individual appointment availabilities
$selectedAdvisorList;
$booleanList;

$chkbIndividual;
$chkbGroup;

$debug = false; 
include('../phpCode/CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
	print("<div class = 'showButton'>");
		print("Show Appointments: <input type='submit' style='height: 5%; width: 25%;' value='GO'>"); 
	print("</div>");
print("<br><br><hr>");

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");
$arr = serialize($cbMajors);
print("<input type='hidden' name='cbMajors' value='$arr'>");

for($majorNum = 0; $majorNum < count($cbMajors); $majorNum++) {

	$selectedChoice = $cbMajors[$majorNum];

	createReservedSpace($selectedChoice);
	
	print("<h2>");

	print("<div class = 'apptType'>");
		print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' name='chkbIndividual[]' value='$selectedChoice'><br>"); 
		print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' name='chkbGroup[]' value='$selectedChoice'><br>"); 
	print("</div>");
	print("</h2>");

	printAdvisors($selectedChoice);
	
}

include("tailHTML.html");

?>