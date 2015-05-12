<?php
	include("LinkBar.php");

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods
?>
<head>
	<title>Template Displayer</title>
</head>

<body>
    <!-- Paw print logo -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

	<?php
		$advID = $_POST["advID"];
		$grpID = $_POST["grpID"];	
		$tempID = $_POST["tempID"];		

		if (($grpID != NULL && $tempID != NULL) || ($grpID == NULL && $tempID == NULL)) {
			print("An error has occurred, please click the Main Page button on the toolbar and report this to the database admin. #RMV17");
		} else { 
			# Only one selected

			if ($grpID != NULL) {
				# Delete group appointment in DB

				$sql = "DELETE FROM `GroupAppointments` WHERE `ID`=$grpID";
				$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
				print("<center><font size='18' color='red'>Your group appointment has been successfully deleted.");
			} elseif($tempID != NULL) {
				# Delete template in DB

				$sql = "DELETE FROM `Templates` WHERE `ID`=$tempID";
				$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
				print("<center><font size='18' color='red'>Your template has been successfully deleted.");
			} else {
				print("An error has occurred, please click the Main Page button on the toolbar and report this to the database admin. #RMV26");
			}

			print("<form action='EventSelect.php' method='post'>");
				print("<input type='hidden' value='$advID' name='advID'>");
	           		print("<input type='submit' value='RETURN'>");
			print("</form>");
				
		}
	?>
</body>
</html>
