<?php
	include("headHTML.html");
?>

<style>

body {
	background-color: #F5CA5C
}

p {
	text-indent: 400x;
}

h1 {
	text-indent: 100x;
	text-align: justify;
	margin-right: auto;
	margin-left: 300x;
}

.loginBox {
	float: center;
	position: relative;
	right: -25%;
	background-color: #ffffff;
	width: 50%;
}

h1 {
	text-align: right;
	float: center;
	position: relative;
	right: 35%;
}

h2 {
	text-align: right;
	float: center;
	position: relative;
	right: 10%;
}

</style>

<form action="Results1.php" method="post" name="Form1">
<center>
	<font size="18" color = "red" face = "Tw Cen MT">UMBC Advising Kiosk</font>
</center>
<div class = "loginBox">
<h1>
	<font size="5" face="Serif">
	<br>
	FIRST NAME: <input type="text" size="12" maxlength="20" name="tfNameF" required><br>
	<br>
	LAST NAME: <input type="text" size="12" maxlength="20" name="tfNameL" required><br>
	<br>
	CAMPUS ID: <input type="text" size="12" maxlength="7" name="tfId" required><br>
	</font>
</h1>
	<center><font size="3">Two Characters, five numbers code (Eg. AB12345)</font><br><br></center>
	<font size="5" face="Serif">
<h2>
	MAJOR/DEPARTMENT: </font><select style="vertical-align: middle;" name="cbMajors[]" required multiple size="5">

<?php>

	$debug = false; 
	include('../phpCode/CommonMethods.php');
	$COMMON = new Common($debug); // common methods

	$sql = "select * from `Department` order by `Name` ASC";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	while($row = mysql_fetch_array($rs))
	{
		$major = $row['Name'];
		$degree = $row['Degree'];
		print("<option value='$major' value='$degree'>$major $degree");
	}
?>
	</select>
</h2>
	<center><font size="3">Choose all applicable Advising Departments by holding Ctrl</font>
	<br><br>
	<input type="submit" value="LOGIN"></center>
	<br>
</div>
</form>

<?php
	include("tailHTML.html");
?>
