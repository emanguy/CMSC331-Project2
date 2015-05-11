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


include("headHTML.php");
include("Utilities.php");

print("<form action='Results3.php' method='post' name='SendApptType'>");

$chkbIndividual = unserialize($_POST['chkbIndividual']);
$chkbGroup = unserialize($_POST['chkbGroup']);
$arraySelectedAdvisors = unserialize($_POST['arraySelectedAdvisors']);

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$dtTimesI = $_POST['cbTimeSlotI'];
$dtTimesG = $_POST['cbTimeSlotG'];

$dateApptI = unserialize($_POST['dateApptI']);
$dateApptG = unserialize($_POST['dateApptG']);

$debug = false; 
include('../CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

$count = 0;
foreach ($arraySelectedAdvisors as $advisorMajor) {
	$advisor = explode(", ", $advisorMajor);
	$advisorName[$count] = $advisor[0];
	$count++;
}

for ($i = 0; $i < count($dtTimesG); $i++)
{
	$sql = "INSERT INTO `Appointment`(`Student ID`, `Major`, `Advisor Name`, `isGroup`, `Time`) VALUES ('$tfId', '$chkbGroup[$i]', 'null', '1', '$dateApptG[$i] $dtTimesG[$i]')";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
}

for ($i = 0; $i < count($dtTimesI); $i++)
{
	$sql = "INSERT INTO `Appointment`(`Student ID`, `Major`, `Advisor Name`, `isGroup`, `Time`) VALUES ('$tfId', '$chkbIndividual[$i]', '$advisorName[$i]', '0', '$dateApptI[$i] $dtTimesI[$i]')";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
}

print("<h2>");
print("Thank you for using this program! Your appointments are scheduled. Feel free to contact your advisors via E-mail should you need them prior to your appointment.");
print("</h2>");
include("tailHTML.html");

?>
