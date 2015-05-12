<?php
	include("headHTML.php");
?>

<style>

body {
	background-color: #F5CA5C
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
<!-- title of page -->
<center>
	<font size="18" color = "red" face = "Tw Cen MT">UMBC Advising Kiosk</font>
</center>

<!-- places a box around the login elements -->
<div class = "loginBox">
<h1>
	<!-- input fields fName, lName, StudentID -->
	<font size="5" face="Serif">
	<br>
	FIRST NAME: <input type="text" size="12" maxlength="20" name="tfNameF" placeholder="E.g. John" required><br>
	<br>
	LAST NAME: <input type="text" size="12" maxlength="20" name="tfNameL" placeholder="E.g. Smith" required><br>
	<br>
	CAMPUS ID: <input type="text" size="12" maxlength="8" name="tfId" placeholder="E.g. AB12345" onblur="this.value=this.value.toUpperCase()" required><br>
	</font>
</h1>


<h2>
	<!-- major/department picker combobox -->
	MAJOR/DEPARTMENT: </font><select style="vertical-align: middle;" name="cbMajors[]" required multiple size="5">

<?php>

	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods

	$sql = "select * from `Department` order by `Name` ASC";
	$rs = $COMMON-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	# grabs every major from the Department table
	while($row = mysql_fetch_array($rs))
	{
		$major = $row['Name'];
		$degree = $row['Degree'];
		print("<option value='$major' value='$degree'>$major $degree");
	}
?>
	</select>
</h2>
	<!-- comment on how to select multiple majors -->
	<center><font size="3">Choose all applicable Advising Departments by holding Ctrl</font>
	<br><br>

	<!-- submit button -->
	<input type="submit" value="LOGIN"></center>
	<br>
</div>
</form>

<?php
	include("tailHTML.html");
?>
