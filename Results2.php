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

.button {
	float: center;
	width: 20%;
	heigth: 50%;
	position: relative;
	right: -45%;
}

.table {
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
# This file handles getting the schedules for the selected advisors
# or majors
#
#####################################################################

print("<form action='Results3.php' method='post' name='SendApptType'>");

include("headHTML.html");
include("Utilities.php");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$cbMajors = unserialize($_POST['cbMajors']);

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");

$chkbIndividual = $_POST['chkbIndividual'];
$chkbGroup = $_POST['chkbGroup'];
$arraySelectedAdvisors = $_POST['arraySelectedAdvisors'];

$debug = false; 
include('../phpCode/CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

$arr = serialize($chkbIndividual);
print("<input type='hidden' name='chkbIndividual' value='$arr'>");
$arr = serialize($chkbGroup);
print("<input type='hidden' name='chkbGroup' value='$arr'>");
$arr = serialize($arraySelectedAdvisors);
print("<input type='hidden' name='arraySelectedAdvisors' value='$arr'>");

for($num = 0; $num < count($arraySelectedAdvisors); $num++) {

	$selected = explode(", ", $arraySelectedAdvisors[$num]);

	$name = $selected[0];
	$major = $selected[1];

	$majorRow = getMajorRow($currChoice);
	$advisorField = $majorRow['Advisors'];

	#breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	print("<h1>");
	#prints out all information about each advisor (Email, Position, Location)

	$sql2 = "select * from `Advisor` where `Name` = '$name'";
	$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
	$row2 = mysql_fetch_array($rs2);
	print($row2['Name'] . "<br>");
	$email = $row2['Email'];
	print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 

		print("<h2>");
		
		$count = 0;
		foreach ($chkbIndividual as $checkObj) {

			if($checkObj == $major) {
				print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True' checked>"); 
	
				print("<input type='date' name = 'dateApptI[]' required><br>");
				$count++;
			}

			
		}

		# no matches
		if ($count == 0) {
			print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True'><br>"); 
		}

		$count = 0;
		foreach ($chkbGroup as $checkObj) {
			if ($checkObj == $major) {

				print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True' checked>"); 
	
				print("<input type='date' name = 'dateApptG[]' required><br>");
				$count++;
			}
		}
		
		# no matches
		if ($count == 0) {
			print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True'><br>"); 
		}
 
		print("</div>");
		print("</h2>");
	
		
}
	print("<div class = 'button'>");
		print("<input type='submit' value='SELECT APPOINTMENTS'>"); 
	print("</div>");
include("tailHTML.html");

?>



