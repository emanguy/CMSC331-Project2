<style>
# Used in printing information about advisors
h1 {
    color: black;
    text-align: left;
    font-size: 16em;
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

function getMajorRow($currentMajor) {
	global $debug; global $COMMON;

	$sql = "select * from `Department` where `Name` = '$currentMajor'";
	$rs = $COMMON -> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	$row = mysql_fetch_array($rs);
	return $row;
}

##################################################################################
#
# createReservedSpace(string currentMajor)
# This method creates the beginning of the tablespace for each major
#
# This method is used in Results1.php to separate the selected majors
# 
##################################################################################

function createReservedSpace($currentMajor) {
	global $debug; global $COMMON;

	# Begins the listing of all selected majors
	print("<h1>");
	getMajorRow($currentMajor);
	$majorRow = getMajorRow($currentMajor);
	print("<b>" . $currentMajor. " " . $majorRow['Degree'] . "</b><br>");
	print("</h1>");

}


##################################################################################
#
# printAdvisors(string advisorName)
# This method takes prints the information for a specific advisor
#
# Used in Results2.php to show the information for the selected advisors chosen
# in Results1.php
# 
##################################################################################

function printAdvisors($advisorName) {
	global $debug; global $COMMON;

	print("<h1>");
	$sql2 = "select * from `Advisor` where `Name` = '$advisorName'";
	$rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
	$row2 = mysql_fetch_array($rs2);
	print("</h1><b>" . $advisorName . "</b> - " . $row2['Majors'] . "<h1>");
	$email = $row2['Email'];
	print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 
	$name = $row2['Name'];
	$temp = $name . ", " . $advisorName;
	print("Select: <input type='checkbox' name='arraySelectedAdvisors[]' style='height: 3vh; width: 3vw;' value='$temp' checked requiredAtLeastOne-advisor><br><br>"); 
	print("</h1>");
}

##################################################################################
#
# printAdvisors(array selectedAdvisors, string currentMajor)
# This method prints all the advisors for the given major
#
# Used in Results1 to show all available advisors for the selected majors
# from index.php
# 
##################################################################################

function printSelected($arraySelectedAdvisors, $currentMajor) {

	global $debug; global $COMMON;

	$majorRow = getMajorRow($currentMajor);
	$advisorField = $majorRow['Advisors'];

	# Breaks continuous string of names into individual name objects
	$advisorNames = explode(", ", $advisorField);

	print("<h1>");
	# Prints out all information about each advisor (Email, Position, Location)

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

				# Grabs from first query in Department Database and prints any additional info
				print("Select: <input type='checkbox' name='arraySelectedAdvisors[]' style='height: 20px; width: 20px;' value='$name' checked><br><br>"); 
				if ($majorRow['Comments'] != "")
					print("Additional Information: " . $majorRow['Comments'] . "<br><br>");
			}
		}
	}

   	print("</h1>");
	print("<hr>");

}

?>


