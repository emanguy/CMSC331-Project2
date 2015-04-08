<style>

h1 {
    color: black;
    text-align: left;
    font-size: 16px;
}

</style>

<?php

##################################################################################
#
# getMajorRow(string currentMajor)
# This method returns the entire row from the Department table for the current major
# returns array currMajorRow
# 
##################################################################################

function getMajorRow($currChoice) {
	global $debug; global $COMMON;

	$sql = "select * from `Department` where `Name` = '$currChoice'";
	$rs = $COMMON -> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	$row = mysql_fetch_array($rs);
	return $row;
}

##################################################################################
#
# createReservedSpace(string currentMajor)
# This method creates the beginning of the tablespace for each major
# 
##################################################################################

function createReservedSpace($currChoice) {
	global $debug; global $COMMON;

	#begins the listing of all selected majors
	print("<h1>");
	getMajorRow($currChoice);
	$majorRow = getMajorRow($currChoice);
	print("<b>" . $currChoice . " " . $majorRow['Degree'] . "</b><br>");
	print("</h1>");

}


##################################################################################
#
# printAdvisors(string currentMajor)
# This method takes prints all the advisors for the given major
# returns the array of all booleans of the advisors
# 
##################################################################################

function printAdvisors($currChoice) {
	global $debug; global $COMMON;

	$majorRow = getMajorRow($currChoice);
	$advisorField = $majorRow['Advisors'];

	#breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	print("<h1>");
	#prints out all information about each advisor (Email, Position, Location)
	for ($i = 0; $i < count($advisorNames); $i++) {

		$sql2 = "select * from `Advisor` where `Name` = '$advisorNames[$i]'";
		$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
		$row2 = mysql_fetch_array($rs2);
		print($row2['Name'] . "<br>");
		$email = $row2['Email'];
		print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 
		$name = $row2['Name'];
		$temp = $name . ", " . $currChoice;
		print("Select: <input type='checkbox' name='arraySelectedAdvisors[]' style='height: 20px; width: 20px;' value='$temp' checked><br><br>"); 
	}

	#grabs from first query into Department Database and prints any additional info
	if ($majorRow['Comments'] != "")
		print("Additional Information: " . $majorRow['Comments'] . "<br><br>");
   	print("</h1>");
	print("<hr>");
}

##################################################################################
#
# printAdvisors(string currentMajor)
# This method takes prints all the advisors for the given major
# returns the array of all booleans of the advisors
# 
##################################################################################

function printSelected($arraySelectedAdvisors, $currChoice) {

	global $debug; global $COMMON;

	$majorRow = getMajorRow($currChoice);
	$advisorField = $majorRow['Advisors'];

	#breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	print("<h1>");
	#prints out all information about each advisor (Email, Position, Location)

	for ($i = 0; $i < count($advisorNames); $i++) {

		foreach($arraySelectedAdvisors as $selected) {

			if ($advisorNames[$i] == $selected) {
				$sql2 = "select * from `Advisor` where `Name` = '$selected'";
				$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
				$row2 = mysql_fetch_array($rs2);
				print($row2['Name'] . "<br>");
				$email = $row2['Email'];
				print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 
				$name = $row2['Name'];
				print("Select: <input type='checkbox' name='arraySelectedAdvisors[]' style='height: 20px; width: 20px;' value='$name' checked><br><br>"); 
					#grabs from first query into Department Database and prints any additional info
	if ($majorRow['Comments'] != "")
		print("Additional Information: " . $majorRow['Comments'] . "<br><br>");
			}
		}


	}


   	print("</h1>");
	print("<hr>");

}

?>


