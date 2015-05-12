<?php
	include("LinkBar.php");

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods

    // Get advisor ID from POST
    $advisorID = $_POST["advID"];

    // Get template ID from POST
    $templateID = $_POST["tempID"];

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
	<title>Template Creater/Editor</title>	
</head>

<link rel="stylesheet" type="text/css" href="TableStyle.css">

<body>
    <!-- Paw print logo -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>
	
	<br><br>
	<center>
    	<font size="18" color="red" face="Tw Cen MT"><u>Create/Edit Templates</u></font><br>
		<table>
			<td>
				<form action="SaveTemplate.php" id="saveButton" style="align-vertical: bottom; float: right; padding: 5px" method='post'>
				<?php 
					# if Edit, else Create

					if (isset($templateID)) {
						$sql = "SELECT * FROM `Templates` WHERE `ID` = $templateID";
						$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
						$row = mysql_fetch_array($rs);

						$strTimes = $row['Times'];
						$arrTimes = explode(", ", $strTimes);
					} else {
						$row = array(
						"ID" => "",
						"Name" => "",
						"Advisor" => "", 
						"Major" => "", 
						"Times" => "", 
						"IsTemporary" => "0", 
						"Date" => "" . getdate());
					}	
				?>

				Name: <input type="text" name="strName" value="<?php print($row['Name']) ?>" required> <br>
				<input type="checkbox" name="boolIsTemp" <?php if ($row['IsTemporary'] == "1") print("checked") ?>> Is Temporary?&nbsp&nbsp&nbsp&nbsp 
				Applies to: <input type="date" name="date" value="<?php print($row['Date']) ?>" required> <br><br><br>
				<b> Start Times:</b> <br>
				<?php 
					$j = 0;
					for ($i = 0; $i < count($printables); $i++) {
						if ($i == $arrTimes[$j]) {
							print("<input type='checkbox' name='timeChkb[]' value=" . $i . " checked>" . $printables[$i] . "<br>");		
							$j++;		
						} else {
							print("<input type='checkbox' name='timeChkb[]' value=" . $i . ">" . $printables[$i] . "<br>");
						}
					}
				?>

					<?php
						$boolIsTemp = $row['IsTemporary'];
						$date = $row['Date'];
						print("<input type='hidden' value='$templateID' name='tempID'>");
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