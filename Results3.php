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

print("<form action='Final.php' method='post' name='SendApptType'>");

include("headHTML.html");
include("Utilities.php");

$chkbIndividual = unserialize($_POST['chkbIndividual']);
$chkbGroup = unserialize($_POST['chkbGroup']);
$arraySelectedAdvisors = unserialize($_POST['arraySelectedAdvisors']);

$dateApptI = $_POST['dateApptI'];
$dateApptG = $_POST['dateApptG'];

$arr = serialize($dateApptI);
print("<input type='hidden' name='dateApptI' value='$arr'>");
$arr = serialize($dateApptG);
print("<input type='hidden' name='dateApptG' value='$arr'>");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");

$arr = serialize($chkbIndividual);
print("<input type='hidden' name='chkbIndividual' value='$arr'>");
$arr = serialize($chkbGroup);
print("<input type='hidden' name='chkbGroup' value='$arr'>");
$arr = serialize($arraySelectedAdvisors);
print("<input type='hidden' name='arraySelectedAdvisors' value='$arr'>");

$debug = false; 
include('../phpCode/CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

$startTimes = array(
	0 => "09:00:00", 
	1 => "09:30:00",
	2 => "10:00:00", 
	3 => "10:30:00",
	4 => "11:00:00", 
	5 => "11:30:00",
	6 => "12:00:00", 
	7 => "12:30:00",
	8 => "13:00:00",
	9 => "13:30:00", 
	10 => "14:00:00", 
	11 => "14:30:00",
	12 => "15:00:00", 
	13 => "15:30:00");
$printables = array(
	0 => "09:00 AM", 
	1 => "09:30 AM",
	2 => "10:00 AM", 
	3 => "10:30 AM",
	4 => "11:00 AM", 
	5 => "11:30 AM",
	6 => "12:00 PM", 
	7 => "12:30 PM",
	8 => "01:00 PM",
	9 => "01:30 PM", 
	10 => "02:00 PM", 
	11 => "02:30 PM",
	12 => "03:00 PM", 
	13 => "03:30 PM");

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
		$date = "2015";
		foreach ($chkbIndividual as $checkObj) {

			if($checkObj == $major) {
				print("Individual Appointment: ");
				
				$sql = "select * from `Appointment` where `Advisor Name` = '$name' and `isGroup` = '0' and `Time` LIKE '$date%' ORDER BY `Time` ASC";
				$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
				
				$dbTimes;				

				$counter = 0;
				while($row = mysql_fetch_array($rs))
				{

					$timedateArr = $row['Time'];
					$timeDate = explode(" ", $timedateArr);
					$date = $timeDate[0];
					$time = $timeDate[1];
					$dbTimes[$counter] = $time;
					$counter++;

				}

				$openings = 14;

				print("<select name='cbTimeSlotI[]' value='cbTimeSlotI' required>");
				for ($i = 0; $i < $openings; $i++) {
					$counter = 0;
					foreach($dbTimes as $filledTime) {
						if ($startTimes[$i] == $filledTime)
							$counter++;
					}
					if ($counter == 0)
						print("<option value='$startTimes[$i]'>$printables[$i]</option>");
				}

			}	
			print("</select><br>");
			#resets blacklistings		
			$dbTimes = array(); 
		}


		foreach ($chkbGroup as $checkObj) {
			if($checkObj == $major) {
				print("Group Appointment:"); 
				
				$sql = "select * from `Appointment` where `isGroup` = '1' and `Major` = '$major' and `Time` LIKE '$date%'";
				$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

				$dbTimes;				

				$counter = 0;
				while($row = mysql_fetch_array($rs))
				{
					$timedateArr = $row['Time'];
					$timeDate = explode(" ", $timedateArr);
					$date = $timeDate[0];
					$time = $timeDate[1];
					$dbTimes[$counter] = $time;
					$counter++;
				}

				$openings = 14;

				print("<select name='cbTimeSlotG[]' value='cbTimeSlotG' required>");
				for ($i = 0; $i < $openings; $i++) {
					$counter = 0;
					if(count($dbTimes) >= 10) {
						if ($startTimes[$i] == $filledTime)
							$counter++;
					}
					if ($counter == 0)
						print("<option value='$startTimes[$i]'>$printables[$i]</option>");
				}
			}
			print("</select>");	
			$dbTimes = array(); 
		}
	}
	print("</div>");
	print("</h2>");
	print("<div class = 'button'>");
		print("<input type='submit' value='FILL APPOINTMENTS'>"); 
	print("</div>");
include("tailHTML.html");

?>