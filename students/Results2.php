<!-- The CSS styles for the page -->

<style>

h1 {
	color: black;
	text-align: left;
	font-size: 1em;
}

h2 {
	color: black;
	text-align: center;
	font-size: 1em;
	right: 0%;
}

h3 {
	color: black;
	font-size: 1em;
	text-align: right;
}

.button {
	float: center;
	width: 20%;
	heigth: 50%;
	position: relative;
	right: -45%;
}

</style>

<?php

#####################################################################
#
# This file handles selecting appointment dates to be queried to the
# database in the next page, Results3.php
#
#####################################################################

print("<form action='Results3.php' method='post' name='SendApptType'>");

include("headHTML.html");
include("Utilities.php");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$cbMajors = unserialize($_POST['cbMajors']);

# Get the day two days later
$twoDaysLater = date("Y-m-d", strtotime("+2 days"));

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");

$chkbIndividual = $_POST['chkbIndividual'];
$chkbGroup = $_POST['chkbGroup'];
$arraySelectedAdvisors = $_POST['arraySelectedAdvisors'];

$debug = false; 
include('./CommonMethods.php');
$COMMON = new Common($debug); // common methods

# Prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

# Stringify the list of checkboxes and save them for passing
$arr = serialize($arraySelectedAdvisors);
print("<input type='hidden' name='arraySelectedAdvisors' value='$arr'>");

print("<input type='hidden' name='chkbIndividual' value='$chkbIndividual'>");
print("<input type='hidden' name='chkbGroup' value='$chkbGroup'>");

for($num = 0; $num < count($arraySelectedAdvisors); $num++) {

	# Separates the string of "name, major" into two separate objects
	$selected = explode(", ", $arraySelectedAdvisors[$num]);

	# Set name and major by given advisor list
	$name = $selected[0];
	$major = $selected[1];

	# Get the list of advisors for the specified major
	$majorRow = getMajorRow($currChoice);
	$advisorField = $majorRow['Advisors'];

	# Breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	print("<h1>");
	# Prints out all information about each advisor (Email, Position, Location)

	$sql2 = "select * from `Advisor` where `Name` = '$name'";
	$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
	$row2 = mysql_fetch_array($rs2);
	print($row2['Name'] . "<br>");
	$email = $row2['Email'];
	print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 

	print("<h2>");

	# Prints out a date picker for individual advising if applicable
	if ($chkbIndividual != NULL) {
    	# Minimum date on picker is now the date calculated in $twoDaysLater and the default date

        print("Individual Advising: <input type='date' min = '$twoDaysLater' name = 'dateApptI[]' value = '$twoDaysLater' required><br>"); 
	}

	# Print out a date picker for group advising if applicable
	if ($chkbGroup != NULL) {
    	# Minimum date on picker is now the date calculated in $twoDaysLater and the default date

		print("Group Advising: <input type='date' min = '$twoDaysLater' name = 'dateApptG[]' value = '$twoDaysLater' required><br>"); 
    }

	print("</h2>");
}

# Submit button
print("<div class = 'button'>");
print("<input type='submit' value='SELECT APPOINTMENTS'>"); 
print("</div>");

include("tailHTML.html");

?>