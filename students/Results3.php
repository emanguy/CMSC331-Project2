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

$chkbIndividual = $_POST['chkbIndividual'];
$chkbGroup = $_POST['chkbGroup'];
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
include('./CommonMethods.php');
$COMMON = new Common($debug); // common methods

#prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

$startTimes = array(
	0 => "08:00:00", 
	1 => "08:30:00",
	2 => "09:00:00", 
	3 => "09:30:00",
	4 => "10:00:00", 
	5 => "10:30:00",
	6 => "11:00:00",
	7 => "11:30:00",
	8 => "12:00:00", 
	9 => "12:30:00",
	10 => "13:00:00",
	11 => "13:30:00", 
	12 => "14:00:00", 
	13 => "14:30:00",
	14 => "15:00:00", 
	15 => "15:30:00");
$printables = array(
	0 => "08:00 AM", 
	1 => "08:30 AM",
	2 => "09:00 AM", 
	3 => "09:30 AM",
	4 => "10:00 AM", 
	5 => "10:30 AM",
	6 => "11:00 AM", 
	7 => "11:30 AM",
	8 => "12:00 PM", 
	9 => "12:30 PM",
	10 => "01:00 PM",
	11 => "01:30 PM", 
	12 => "02:00 PM", 
	13 => "02:30 PM",
	14 => "03:00 PM", 
	15 => "03:30 PM");
?>
<style>
		section { padding:10px; overflow:hidden; }
		.bordered{ border:1px solid black; padding:10px; overflow:hidden; }
		section > section { float:left; }
			.closed {background-color: #CCCCCC;}
		.abrams { padding-left:100px; padding-bottom:20px; }
		.arey { padding-left:100px; padding-bottom:20px; }
		.stevens { padding-left:100px; padding-bottom:20px; }
	</style>
	<?php

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
	$advID=$row2['ID'];
	print($row2['Name'] . "<br>");
	$email = $row2['Email'];
	print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 

	
    # Get the current year
	$date = date("Y");
	if ($chkbIndividual != NULL)
	{
		print("Individual Appointments<br>");
		print("<input type='radio' name='radioindID".$advID."' value='NULL' checked>No selection<br><section>");
		$currentDate=$dateApptI[$num];
		$UTCdate=strtotime($currentDate);
		$dateAdd=0;
		$iteration=0;
		$complete=false;
		while(!$complete)
		{
			$tempDate=$UTCdate+$dateAdd*(24*60*60);
			if (date('D',$tempDate)=="Sat" or date('D',$tempDate)=="Sun")
			{$dateAdd++;}
			else{
				print("<section style='background-color: White'>");
				print(date("Y-m-d, D",$tempDate));
				$count=0;
				foreach($startTimes as $times)
				{
					
					if (checkAvail($tempDate,$count,$name, $advID, $COMMON)){
						$datetime=date("Y-m-d",$tempDate)." ".$startTimes[$count];
						if(!apptTaken($datetime, $name, $COMMON)){
							#name of the form variable will be of the form 'radioindID2 where 2 is the advisor ID, and the value will be the datetime of the appointment
							print("<div class='bordered'><input type='radio' name='radioindID".$advID."' value='".$datetime."'>".$printables[$count]."</div>");}#name of the form variable will be of the form 
						else{
							print("<div class='bordered' style='background-color: Red'>".$printables[$count]."</div>");#if appointment is available normally but taken
						}
					}
					else{
						print("<div class='bordered' style='background-color: #CCCCCC'>".$printables[$count]."</div>");}#if appointment isn't available on schedule
					$count++;
				}
				
				$dateAdd++;
				$iteration++;
				print("</section>");
			}
			if($iteration>6)
				{$complete=true;}
		}
		print("</section>");
		
		
	}
	


	if ($chkbGroup != NULL)
	{ 
		print("Group Appointments<br>");
		print("<input type='radio' name='radiogrpID".$advID."' value='NULL' checked>No selection<br>");
		$sql = "select * from `GroupAppointments` where `Advisors` LIKE '%".$name."%' and `Signups` NOT LIKE '%".$tfNameF." ".$tfNameL."%'";
		$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		while($row = mysql_fetch_array($rs))
		{
			$max=$row['Capacity'];
			$datetime=$row['Date/Time'];
			$printdate=date("l, F jS, g:iA",strtotime($datetime));
			
			$students=$row['Signups'];
			$numstudents=0;
			if($students!=NULL){
			$numstudents=substr_count($students, ",")+1;
			}
			if($numstudents>=$max)
			{print("FULL - ".$printdate."<br>");}
			else
			{
				print("<input type='radio' name='radiogrpID".$advID."' value='".$datetime."'>");
				print(($max-$numstudents)." seats left - ".$printdate."<br>");
			}
		}
		
	}
	
}
	//print("</div>");
	//print("</section>");
	print("<div class = 'button'>");
		print("<input type='submit' value='FILL APPOINTMENTS'>"); 
	print("</div>");
include("tailHTML.html");

#date is UTC, time is one of the times from the array in results3
function checkAvail($date, $time, $advisor, $advID, $COMMON)
{
	$available=false;
	$sql = "select * from `Templates` where `Advisor` = '".$advisor."' and `isTemporary` = '1' and `Date` ='".date("Y-m-d",$date)."'";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	if($row = mysql_fetch_array($rs))
	{
		$times=explode(", ",$row[4]);
		foreach($times as $temp)
		{
			//print(" ".$temp." ".$time." ");
			if($temp==$time){$available=true;}
		}
	}
	else{
		$sql = "select * from `DefaultTemplates` where `AdvisorID` = '".$advID."'";
		$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_array($rs))
		{
			$templateID=$row[date("D", $date)];
			$tempsql="select * from `Templates` where `ID` = '".$templateID."'";
			$temprs=$COMMON-> executeQuery($tempsql, $_SERVER["SCRIPT_NAME"]);
			if($row = mysql_fetch_array($temprs))
			{
				$times=explode(", ",$row[4]);
				foreach($times as $temp)
				{
					//print(" ".$temp." ".$time." ");
					if($temp==$time){$available=true;}
				}
			}
			
		}
		
		}
	return $available;
}
function checkGroupFull()
{
	
}
function apptTaken($when, $advisor, $COMMON)
{
	$taken=false;
	$sql = "select * from `Appointment` where `Advisor Name` = '".$advisor."' and `Time` = '".$when."'";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	if($row = mysql_fetch_array($rs))
	{$taken=true;}
	return $taken;
}

?>
