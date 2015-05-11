<html>
<head>
    <title>Advisor Control Panel </title>

    <!-- Page-specific styles -->
    <style type="text/css">
        form
        {
            display: inline;
            margin: 4px;
        }
		<!-div { float:left;}>
		table, td, th {
    border: 0px solid black;
}
    </style>
</head>

<body>
 <?php include("LinkBar.php") ?>
  <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>
	<center>
		<?php
			$debug = false; 
			 if (!class_exists(Common))
                    {
                        include("CommonMethods.php");
                    }
			$COMMON = new Common($debug); // common methods
			$advID=$_POST['advID'];
			$name=$_POST['advName'];
			$sqlcurrent = "select * from `DefaultTemplates` where `AdvisorID` = '$advID'";
			$rscurrent = $COMMON-> executeQuery($sqlcurrent, $_SERVER["SCRIPT_NAME"]);
			$hasdefault=false;
			if($row = mysql_fetch_array($rscurrent))
			{
				$currentdefaults= array(
				
					0 => $row['Mon'],
					1 => $row['Tue'],
					2 => $row['Wed'],
					3 => $row['Thu'],
					4 => $row['Fri']);
				$hasdefault=true;
			}
			$sqltemplates = "select * from `Templates` where (`Advisor` = '$name' and `isTemporary` = '0') or `Default` = '1'";
			$rstemplates = $COMMON-> executeQuery($sqltemplates, $_SERVER["SCRIPT_NAME"]);
			$count=0;
			while($row = mysql_fetch_array($rstemplates))
			{
				$templates[$count]=$row['Name'];
				$templateIDs[$count]=$row['ID'];
				for($x=0;$x<5;$x++)
				{
					if($hasdefault==true and $currentdefaults[$x]==$row['ID'])
					{$currentdefaults[$x]=$row['Name'];}
				}
				
				$count++;
			}

		?>
		<form action='DefaultMidway.php' method='post' name='SendDefaultSelect'>
			<table>
				<tr>
					<td>
					<div>
					<b>Monday</b><br>
					<select name="Monday" id="Mon">
					<?php 
						for($x=0;$x<count($templates);$x++) { 
						if ($currentdefaults[0]==$templates[$x]){
							print("<option value='".$templateIDs[$x]."' selected>".$templates[$x]."</option>");
						}
						else
						{
							print("<option value='".$templateIDs[$x]."'>".$templates[$x]."</option>");
						}
						  
						} ?>
					</select> 
					</div>
					</td>
					<td>
					<div>
					<b>Tuesday</b><br>
					<select name="Tuesday" id="Tue">
					<?php 
						for($x=0;$x<count($templates);$x++) { 
						if ($currentdefaults[1]==$templates[$x]){
							print("<option value='".$templateIDs[$x]."' selected>".$templates[$x]."</option>");
						}
						else
						{
							print("<option value='".$templateIDs[$x]."'>".$templates[$x]."</option>");
						}

						} ?>
					</select> 
					</div>
					</td>
					<td>
					<div>
					<b>Wednesday</b><br>
					<select name="Wednesday" id="Wed">
					<?php 
						for($x=0;$x<count($templates);$x++) { 
						if ($currentdefaults[2]==$templates[$x]){
							print("<option value='".$templateIDs[$x]."' selected>".$templates[$x]."</option>");
						}
						else
						{
							print("<option value='".$templateIDs[$x]."'>".$templates[$x]."</option>");
						}
						  
						} ?>
					</select> 
					</div>
					</td>
					<td>
					<div>
					<b>Thursday</b><br>
					<select name="Thursday" id="Thu">
					<?php 
						for($x=0;$x<count($templates);$x++) { 
						if ($currentdefaults[3]==$templates[$x]){
							print("<option value='".$templateIDs[$x]."' selected>".$templates[$x]."</option>");
						}
						else
						{
							print("<option value='".$templateIDs[$x]."'>".$templates[$x]."</option>");
						}	
						 
						} ?>
					</select> 
					</div>
					</td>
					<td>
					<div>
					<b>Friday</b><br>
					<select name="Friday" id="Fri">
					<?php 
						for($x=0;$x<count($templates);$x++) { 
						if ($currentdefaults[4]==$templates[$x]){
							print("<option value='".$templateIDs[$x]."' selected>".$templates[$x]."</option>");
						}
						else
						{
							print("<option value='".$templateIDs[$x]."'>".$templates[$x]."</option>");
						}
						  
						} ?>
					</select> 
					</div>
					</td>
				</tr>
			</table>
			<input type="hidden" name="advID" value=<?php print("'".$advID."'"); ?>>
			<input type="hidden" name="advName" value=<?php print("'".$name."'"); ?>>
			<input type="hidden" name="hasdefault" value=<?php print("'".$hasdefault."'"); ?>>
			<br><input type="submit" value="Submit">
		</form>
	</center>
</body>
</html>