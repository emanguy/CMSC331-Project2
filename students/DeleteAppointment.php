<?php
    if (isset($_POST["tfId"]))
    {
        include("headHTML.php");
?>

<!-- CSS Styles -->
<style>
    body {
        background-color: #F5CA5C;
    }
    
    /* TODO add more styles later */
</style>

<?php

// Do import
include("CommonMethods.php");

// Gather POST data
$eventId = $_POST["eventId"];
$groupId = "";
$isGroup = $_POST["isGroup"];
$studentID = $_POST["tfId"];

if (isset($_POST["groupId"]))
{
    $groupId = $_POST["groupId"];
}

// Init DB connection
$DB = new Common(false);

// Delete given appointment
$query = "DELETE FROM `Appointment` WHERE `ID` = ".$eventId." AND `isGroup` = ".$isGroup;
$DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

// Rebuild list of students in the group appointment if applicable
if ($isGroup === 'true')
{
    // Get names of all students attending group appointment
    $query = "SELECT `Student Name` FROM `Appointment` WHERE `appointmentID` = ".$groupId." AND
        `isGroup` = true";
    $result = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

    // Create array and store names
    $nameArray = array();

    while ($name = MYSQL_FETCH_ARRAY($result))
    {
        array_push($nameArray, $name["Student Name"]);
    }

    // Generate string
    $nameString = implode(", ", $nameArray);

    // Update group appointment
    $query = "UPDATE `GroupAppointments` SET `Signups`='".$nameString."' WHERE `ID` = ".$groupId;
    $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);
}

/* 
 * The actual UI. Tells you your appointment is gone and allows you to return 
 * to your appointment list.
 */
print("<h1>The appointment was successfully deleted. Press the button below to return to your list of appointments.</h1>");
print("<form action='ShowAppointments.php' method='POST'>
        <input type='submit' value='Return to Appointment List'>
        <input type='hidden' name='tfId' value='".$studentID."'>
    </form>");

// Add tail HTML
include("tailHTML.html");
    }
    else
    {
        include("DeleteAppointmentBegin.php");
    }
?>
