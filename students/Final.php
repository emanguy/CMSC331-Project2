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

$chkbIndividual = $_POST['chkbIndividual'];
$chkbGroup = $_POST['chkbGroup'];
$arraySelectedAdvisors = unserialize($_POST['arraySelectedAdvisors']);

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$dtTimesI = $_POST['cbTimeSlotI'];
$dtTimesG = $_POST['cbTimeSlotG'];

$dateApptI = unserialize($_POST['dateApptI']);
$dateApptG = unserialize($_POST['dateApptG']);

$debug = false; 
include('CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
$studentName=$tfNameF . " " . $tfNameL;
print($studentName);
print("<br>" . $tfId);
print("<br><br><hr>");

for($num = 0; $num < count($arraySelectedAdvisors); $num++) {
	$selected = explode(", ", $arraySelectedAdvisors[$num]);

	$name = $selected[0];
	$major = $selected[1];

	$majorRow = getMajorRow($currChoice);
	$advisorField = $majorRow['Advisors'];

	#breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	$sql2 = "select * from `Advisor` where `Name` = '$name'";
	$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
	$row2 = mysql_fetch_array($rs2);
	$advID=$row2['ID'];
	$email = $row2['Email'];
	$indAppt=$_POST["radioindID".$advID];
	$grpAppt=$_POST["radiogrpID".$advID];
	
	if ($chkbIndividual != NULL and $indAppt != NULL)
	{
		$sqlcheck = "select * from `Appointment` where `isGroup` = '0' and `Student ID`='$tfId' and `Advisor Name` = '$name'";
		$rscheck = $COMMON-> executeQuery($sqlcheck, $_SERVER["SCRIPT_NAME"]);
		$flagged=false;
		while($row = mysql_fetch_array($rscheck))
		{
			$thetime=strtotime($row['Time']);
			$base=strtotime($indAppt);
			$week=60*60*24*7;
			if ((($base+$week)>$thetime) or (($base-$week)<$thetime))
			{
				$flagged=true;
			}
		}
		if(!$flagged)
		{
		$sqladd = "INSERT INTO `Appointment`(`AppointmentID`, `Student ID`, `Student Name`, `Advisor Name`, `IsGroup`, `Time`) VALUES ('0','$tfId','$studentName','$name','0','$indAppt')";
		$rsadd = $COMMON-> executeQuery($sqladd, $_SERVER["SCRIPT_NAME"]);
		print("Appointment made on ".date("D, m/d, g:iA",strtotime($indAppt))." with ".$name.".<br>");
		}
		else
		{
			print("The time slot on ".date("D, m/d, g:iA",strtotime($indAppt))." is within a week of another appointment you have with ".$name.".<br>");
		}
	}
	if ($chkbGroup != NULL and $grpAppt != NULL)
	{
		$sqlfind = "select * from `GroupAppointments` where `Date/Time` = '$grpAppt' and `Advisors` LIKE '%$name%'";
		$rsfind = $COMMON-> executeQuery($sqlfind, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_array($rsfind))
		{
			$grpID=$row['ID'];
			$signups=$row['Signups'];
		}
		
		
		$sqlcheck = "select * from `Appointment` where `isGroup` = '1' and `Time` = '$grpAppt' and `Student ID`='$tfId'";
		$rscheck = $COMMON-> executeQuery($sqlcheck, $_SERVER["SCRIPT_NAME"]);
		
		if($row = mysql_fetch_array($rscheck))
		{
			print("You already have a group advising appointment on ".date("D, m/d, g:iA",strtotime($grpAppt)).".<br>");
		}
		else{
			if($signups==NULL)
			{
				$sqladd = "UPDATE `GroupAppointments` SET `Signups` = '$studentName' WHERE `ID`='$grpID'";
			}
			else{
				$signups=$signups.", ".$studentName;
				$sqladd = "UPDATE `GroupAppointments` SET `Signups` = '$signups' WHERE `ID`='$grpID'";
			}
			$rsadd = $COMMON-> executeQuery($sqladd, $_SERVER["SCRIPT_NAME"]);
			
			$sqlappt = "INSERT INTO `Appointment`(`AppointmentID`, `Student ID`, `Student Name`, `Advisor Name`, `IsGroup`, `Time`) VALUES ('$grpID','$tfId','$studentName','','1','$grpAppt')";
			$rsappt = $COMMON-> executeQuery($sqlappt, $_SERVER["SCRIPT_NAME"]);
			print("Group Appointment made on ".date("D, m/d, g:iA",strtotime($grpAppt)).".<br>");
		}
		
		
	
	}
	
	
}

include("tailHTML.html");

?>
