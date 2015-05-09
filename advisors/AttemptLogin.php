<?php
/* 
 * This php code handles login from index.php
 */

// Get advisor ID from POST
$advisorID = $_POST["advID"];

// Connect to DB
include("CommonMethods.php");
$DB = new Common(false);
$query = "SELECT `ID` from `Advisor` WHERE `ID` = ".$advisorID;

// Determine if the advisor exists
$result = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));

if ($result["ID"] == NULL)
{
    // Go back to index.php on failed login
    $_GET["failedLogin"] = true;
    include("./index.php");
}
else
{
    // Go to the navigation page on successful login
    include("Welcome.php");
}
?>
