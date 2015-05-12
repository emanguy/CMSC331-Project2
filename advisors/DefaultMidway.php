
<?php
if(!isset($_POST['advID']))
{
	include("index.php");
	
}
else{
	$debug = false; 
	include('./CommonMethods.php');
	$COMMON = new Common($debug); // common methods
	$advID=$_POST['advID'];
	$name=$_POST['advName'];
	$mon=$_POST['Monday'];
	$tues=$_POST['Tuesday'];
	$wed=$_POST['Wednesday'];
	$thurs=$_POST['Thursday'];
	$fri=$_POST['Friday'];
	$hasDefault=$_POST['hasdefault'];
	if(!$hasDefault){
	$sqladd = "INSERT INTO `DefaultTemplates`(`AdvisorID`, `Mon`, `Tue`, `Wed`, `Thu`, `Fri`) VALUES ('$advID','$mon','$tues','$wed','$thurs','$fri')";
	$rsadd = $COMMON-> executeQuery($sqladd, $_SERVER["SCRIPT_NAME"]);
	}
	else
	{
		$sqladd = "UPDATE `DefaultTemplates` SET `Mon` = '$mon', `Tue` = '$tues', `Wed` = '$wed', `Thu` = '$thurs', `Fri` = '$fri' WHERE `AdvisorID`='$advID'";
		$rsadd = $COMMON-> executeQuery($sqladd, $_SERVER["SCRIPT_NAME"]);
	}
	$_POST['updated']=true;
	include('TemplateWeek.php');
}
	?>

	