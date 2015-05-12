<?php
	include("LinkBar.php");

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods

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

<link rel="stylesheet" type="text/css" href="TableStyle.css">
<html>
<head>
	<title>Template Displayer</title>
	<style>
	table, th, td {
    	border: 1px solid black;
    	border-collapse: collapse;
	}

	h1 {
		font-size: 20px;
	}

	
</style>
</head>

<body>
    <!-- Paw print logo -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

	<table style="background-color: rgba(0,0,0,0); border: 0px; width: 100%">

		<td style="border: 0px">
			<!-- Begin Templates -->
			<h1>
				<form action="AddTemplate.php" method="post">
					<u>Templates</u>
            		<input type="hidden" name="advID" value=<?php print("" . $_POST["advID"]); ?>>
					<input type="image" src="AddIcon.svg" value=$advID width="20" height="20">
				</form>
			</h1> 


		
			<table style="width:100%">

			<tr>
				<th>Name: </th>
				<th>Times: </th>
				<th>Actions: </th>
			</tr>

			<?php

	
			$sql = "SELECT * FROM `Templates` WHERE `IsTemporary` = 0";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

			while ($row = mysql_fetch_array($rs)) 
			{
				print("<tr>");
					$strName = $row['Name'];
					$templateID = $row['ID'];
	
					# Converting string of ints to string of printable times
					$strTimes = $row['Times'];
					$arrTimeIndices = explode(", ", $strTimes);
					$strPrintables = $printables[$arrTimeIndices[0]];
					for ($i = 1; $i < count($arrTimeIndices); $i++) {
						$strPrintables = $strPrintables . "<br>" . $printables[$arrTimeIndices[$i]];
					}
	
					print("<td style='vertical-align=top'>$strName</td>");
					print("<td>$strPrintables</td>");
					print("<td>");
						# Poor choice to place icons but works

						if ($strName != "DEFAULT") {
							print("<form action='AddTemplate.php' method='post'>");
								print("&nbsp &nbsp");
	            				print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
								print("<input type='image' src='EditIcon.svg' name='tempID' value=$templateID width='20' height='20'>");
								print(" Edit");
							print("</form>");
	
							print("<form name='deleter' method='post'>");
								print("&nbsp &nbsp");
	           					print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
								print("<input type='image' src='DeleteIcon.svg' name='tempID' value=$templateID width='20' height='20' onclick='confirmDelete()'>");
								print(" Delete");
							print("</form>");
						}
					print("</td>");
				print("</tr>");
			}
	
		?>
	
		</table>
	</td>
	<br><br><br>
	<!-- Begin Group Appointments -->

	<td style="border: 0px; vertical-align: top">
		<h1>
			<form action="AddGroup.php" method="post">
				<u>Group Appointments</u>
       		    <input type="hidden" name="advID" value=<?php print("" . $_POST["advID"]); ?>>
				<input type="image" src="AddIcon.svg" value=$advID width="20" height="20">
			</form>
		</h1> 

		<table style="width:70%" align="left">
		<tr>
			<th>Date: </th>
			<th>Time: </th>
			<th>Capacity: </th>
			<th>Actions: </th>
		</tr>

		<?php

			$sql = "SELECT * FROM `GroupAppointments` WHERE `Advisors` LIKE '%". $advID . "%'";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	
			while($row = mysql_fetch_array($rs)) {
				$max = $row['Capacity'];
				$dateTime = $row['Date/Time'];
				$printDate= date("l, F j", strtotime($dateTime));
				$printTime= date("g:iA", strtotime($dateTime));
				$grpID = $row['ID'];

				print("<tr>");
					print("<td>" . $printDate . "</td>");
					print("<td>" . $printTime . "</td>");
					print("<td>" . $max . "</td>");

					# Poor choice to place icons but works
	
					print("<td>");
					print("<form action='AddGroup.php' method='post'>");
						print("&nbsp &nbsp");
   	        			print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
						print("<input type='image' src='EditIcon.svg' name='grpID' value=$grpID width='20' height='20'>");
						print(" Edit");
					print("</form>");

					print("<form name='deleter' method='post'>");
						print("&nbsp &nbsp");
	       				print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
						print("<input type='image' src='DeleteIcon.svg' name='grpID' value=$grpID width='20' height='20' onclick='confirmDelete()'>");
						print(" Delete");
					print("</form>");
					print("</td>");
				print("</tr>");
			}
		?>
		</table>

	</td>
	</table>

	<!-- Begin Temporary Templates below -->

	<h1>
			<u>Temporary Templates</u>
	</h1> 

	<table style="width:42%">
		<tr>
			<th>Day: </th>
			<th>Times: </th>
			<th>Actions: </th>
		</tr>

		<?php

			$sql = "SELECT * FROM `Templates` WHERE `IsTemporary` = 1";
			$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	
			while ($row = mysql_fetch_array($rs)) 
			{
				print("<tr>");
					$templateID = $row['ID'];
	
					$date = $row['Date'];
					$arrDate = explode("-", $date);
					$year = $arrDate[0];
					$month = $arrDate[1];
					$day = $arrDate[2];
					$printableDate = date("l\, F j", mktime(0, 0, 0, $month, $day, $year));
					
					# Converting string of ints to printable times
					$test2 = $row['Times'];
					$arrTimeIndices = explode(", ", $test2);
					$strPrintables = $printables[$arrTimeIndices[0]];
					for ($i = 1; $i < count($arrTimeIndices); $i++) {
						$strPrintables = $strPrintables . "<br>" . $printables[$arrTimeIndices[$i]];
					}
	
					print("<td>$printableDate</td>");
					print("<td>$strPrintables</td>");
					print("<td>");
						# Poor choice to place icons but works
	
						print("<form action='AddTemplate.php' method='post'>");
							print("&nbsp &nbsp");
   	         			print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
							print("<input type='image' src='EditIcon.svg' name='tempID' value=$templateID width='20' height='20'>");
							print(" Edit");
						print("</form>");
	
						print("<form name='deleter' method='post'>");
							print("&nbsp &nbsp");
	           				print("<input type='hidden' name='advID' value=" . $_POST['advID'] . ">");
							print("<input type='image' src='DeleteIcon.svg' name='tempID' value=$templateID width='20' height='20' onclick='confirmDelete()'>");
							print(" Delete");
						print("</form>");
					print("</td>");
				print("</tr>");
			}
	
		?>
	
		</table>

		<script>
			function confirmDelete() {
				if (confirm("Are you sure you want to delete this?") == true) {
					var x = document.getElementsByName("deleter");
					for (var i = 0; i < x.length; i++) {
    					x[i].action = "Remove.php";
					}
				} else {
					var x = document.getElementsByName("deleter");
					for (var i = 0; i < x.length; i++) {
    					x[i].action = "";
					}
				}
			}
		</script>

</body>
</html>