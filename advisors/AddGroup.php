<?php
	include("LinkBar.php");

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods

    // Get advisor ID from POST
    $advisorID = $_POST["advID"];

    // Get template ID from POST
    $groupID = $_POST["grpID"];

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

	# I hate this major restriction, but if this was to expanded, please use Major table
	$allMajors = array(
	0 => "Computer Science", 
	1 => "Computer Engineering", 
	2 => "Mechanical Engineering",
	3 => "Chemical Engineering",
	4 => "Engineering"); 
?>

<html>
<head>
	<title>Group Appt. Creater/Editor</title>	
</head>

<link rel="stylesheet" type="text/css" href="TableStyle.css">

<body>
    <!-- Paw print logo -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>
	
	<br><br>
	<center>
    	<font size="18" color="red" face="Tw Cen MT"><u>Create/Edit Group Appointments</u></font><br>
		<table>
			<td>
				<form action="SaveAppointment.php" id="saveButton" style="float: bottom, right; padding: 5px" method='post'>
				<?php 
					# if Edit, else Create

					if (isset($groupID)) {
						$sql = "SELECT * FROM `GroupAppointments` WHERE `ID`=$groupID";
						$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
						$row = mysql_fetch_array($rs);

						$datetimeTime = explode(" ", $row['Date/Time']);

						$date = $datetimeTime[0];
						$time = $datetimeTime[1];

						$arrAdvisors = explode(", ", $row['Advisors']);
					} else {
						$sql = "SELECT * FROM `Advisor` WHERE `ID`='$advisorID'";
						$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
						$row = mysql_fetch_array($rs);

						$date = date('Y-m-d');
						$time = date('h:i');

						$row = array(
						"ID" => "",
						"Date/Time" => $datetimeTime,
						"Capacity" => "10", 
						"Majors" => "Computer Science, Computer Engineering, Mechanical Engineering, Chemical Engineering, Engineering", 
						"Signups" => "", 
						"Advisors" => "");
						print($row['Name']);
					}	

					$majors = explode(", ", $row['Majors']); 
				?>
				
				Date: <input type="date" name="date" value="<?php print($date) ?>" required> <br>
				Time: <input type="time" name="time" min="08:00" max="16:00" value="<?php print($time) ?>" required> <br>
				Capacity: <input type="number" name="capacity" value="<?php print($row['Capacity']) ?>" style="width: 30%" required> <br><br><br>
				<b> Majors:</b> <br>
				<?php 
					$j = 0;
					for ($i = 0; $i < count($allMajors); $i++) {
						if ($allMajors[$i] == $majors[$j]) {
							print("<input type='checkbox' name='majors[]' value='" . $majors[$i] . "' checked>" . $allMajors[$i] . "<br>");		
							$j++;		
						} else {
							print("<input type='checkbox' name='majors[]' value='" . $majors[$i] . "'>" . $allMajors[$i] . "<br>");
						}
					}
				?>
	
				<br><br><br>
				<b> Assign Advisors:</b> <br>
				<?php 

					$sql = "SELECT * FROM `Advisor` WHERE `Majors` LIKE '%" . $allMajors[0] . "%'";

					for ($i = 0; $i < count($allMajors); $i++) {
						$sql = $sql . " OR `Majors` LIKE '%" . $allMajors[$i] . "%'";
					}

					$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

					$i = 0;
					while ($row = mysql_fetch_array($rs)) {
						if ($i == 0) {
							$allAdvisors = $row['Name'];
						} else {
							$allAdvisors = $allAdvisors . ", " . $row['Name'];
						}
						$i++;
					}
					$allAdvisors = explode(", ", $allAdvisors);
					$j = 0;
					for ($i = 0; $i < count($allAdvisors); $i++) {
						if ($allAdvisors[$i] == $arrAdvisors[$j]) {
						
							print("<input type='checkbox' name='assignedAdvisors[]' value='" . $allAdvisors[$i] . "' checked>" . $allAdvisors[$i] . "<br>");		
							$j++;		
						} else {
							print("<input type='checkbox' name='assignedAdvisors[]' value='" . $allAdvisors[$i] . "'>" . $allAdvisors[$i] . "<br>");
						}
					}
				?>
				<br><br>
					<?php
						print("<input type='hidden' value='$groupID' name='grpID'>");
						print("<input type='hidden' value='$advisorID' name='advID'>");
						print("<input type='submit' value='Save')'>");
					?>
				</form>
				<form action="EventSelect.php" id="cancelButton" style="padding: 5px">
					<input type='hidden' value='$advisorID' name='advID'>
					<input type="submit" value="Cancel">
				</form>
			</td>
		</table>
	</center>
</body>
</html>