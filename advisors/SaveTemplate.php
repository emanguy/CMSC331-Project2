<?php
	include("LinkBar.php");

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods

    $advisorID = $_POST["advID"];
    print($advisorID);
    $templateID = $_POST["tempID"];
    $strName = $_POST["strName"];
    $arrTimes = $_POST["timeChkb"];
    $boolIsTemp = $_POST["boolIsTemp"];
    $date = $_POST["date"];

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

<html>
<head>
	<title>Template Saver</title>	
</head>

<body>
	<?php
		# Really not sure why this needs to be done but works
		if ($templateID == "")
			$templateID = NULL;

		if (isset($boolIsTemp))
			$boolIsTemp = 1;
		else
			$boolIsTemp = 0;

		$strTimes = $arrTimes[0];
		for ($i = 1; $i < count($arrTimes); $i++) {
			$strTimes = $strTimes . ", " . $arrTimes[$i];
		}

		if (isset($templateID) && isset($advisorID)) {
			$sql = "SELECT * FROM `Templates` WHERE `ID` = $templateID";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

			if (isset($rs)) {
				# Edit template

				$sql = "UPDATE `Templates` SET `Name`='$strName', `Times`='$strTimes', `IsTemporary`='$boolIsTemp', `Date`='$date' WHERE `ID`='$templateID'";
				$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

				print("<center><font size='5' style='float:center'>Your '" . $strName . "' template has been successfully edited.");
				print("<form action='EventSelect.php' method='post'>");
					print("<input type='hidden' value='$advisorID' name='advID'>");
	           		print("<input type='submit' value='RETURN'>");
				print("</form>");
			}
		} else {
			# Create template

			$sql = "SELECT * FROM `Advisor` WHERE `ID`=$advisorID";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
			$row = mysql_fetch_array($rs);
			
			$advName = $row['Name'];
			$advMajors = $row['Majors'];
			$sql = "INSERT INTO `Templates` (`Name`, `Advisor`, `Major`, `Times`, `IsTemporary`, `Date`, `Default`) VALUES ('$strName', '$advName', '$advMajors', '$strTimes', '$boolIsTemp', '$date', '0')";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
			
			print("<center><font size='5'>Your template has been successfully created.");
			print("<form action='EventSelect.php' method='post'>");
				print("<input type='hidden' value='$advisorID' name='advID'>");
	           	print("<input type='submit' value='RETURN'>");
			print("</form>");
		}
	?>
</body>
</html>