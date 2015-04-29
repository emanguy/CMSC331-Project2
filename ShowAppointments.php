<?php
    include("headHTML.html");
?>

<!-- Begin CSS styles -->

<style>

    body
    {
        background-color: #F5CA5C;
    }

    /* TODO add more styles here later */
</style>

<!-- Main page content -->
<?php

include("CommonMethods.php");

// Get the ID of the student from DeleteAppointmentBegin.php
$studentID = $_POST['tfId'];

// Get today's date
$date = date("Y-m-d");

// Initialize DB connection
$DB = new Common(false);

// Query DB for student's appointments after today
$query = "SELECT `ID`,`Advisor Name`, `Time` FROM `Appointment` WHERE `StudentID` LIKE '".$studentID."' AND `isGroup` = 0 AND `Time` >= '".$date."'";
$normalAppointments = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

$query = "SELECT `ID`, `appointmentID` FROM `Appointment` WHERE `StudentID` LIKE '".$studentID."' AND `isGroup` = 1 AND `Time` >= '".$date."'";
$groupAppointmentIds = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

$groupAppointments = array();

while ($idRow = MYSQL_FETCH_ARRAY($groupAppointmentIds))
{
    // Grab the group appointment based on its id
    $query = "SELECT `ID`,`Date/Time`, `Advisors` FROM `GroupAppointments` WHERE `ID` = ".$idRow["appointmentID"];
    $resultArr = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"];

    // Add the original event's ID to the array
    $resultArr["signupID"] = $idRow["appointmentID"];

    array_push($groupAppointments, $resultArr));
}

// Display error message if no appointments were fetched
if ($normalAppointments == NULL && $groupAppointments[0] == NULL)
{
    print("<center><h1>You have not signed up for any appointments.</h1></center>");
}
else
{
    // Display individual appointments if any are found
    if ($normalAppointments != NULL)
    {
        // Begin table
        print("<br><h2>Individual appointments</h2>
            <table>");
        print("<th>
                    <td>Date/Time</td>
                    <td>Advisor</td>
                    <td>Delete Appointment</td>
               </th>");

        // Every table row is just the data gathered from the query
        foreach ($normalAppointments as $appointment)
        {
            $formattedTime = date("mm/DD hh:MM meridian", strtotime($appointment["Time"]);

            print("<tr>
                <td>".$formattedTime."</td>
                <td>".$appointment["Advisor Name"]."</td>
                <td>
                    <form action='DeleteAppointment.php' method='post' deleteAction>
                        <img src='DeleteIcon.svg' width='50' height='50' />
                        <input name='eventId' type='hidden' value='".$appointment["ID"]."'>
                        <input name='isGroup' value='false'>
                        <input name='studentID' type='hidden' value='".$studentID."'>
                    </form>
                </td>
            </tr>");
        }

        // End table
        print("</table>");
    }
    if ($groupAppointments[0] != NULL)
    {
        // Begin table
        print("<br><h2>Group Appointments</h2>
            <table>");
        print("<th>
            <td>Date/Time</td>
            <td>Advisors</td>
            <td>Delete Appointment</td>
            </th>");
        
        // Again, table rows are data gathered from queries
        foreach ($groupAppointments as $appointment)
        {
            $formattedTime = date("mm/DD hh:MM meridian", strtotime($appointment["Date/Time"]);

            print("<tr>
                <td>".$formattedTime."</td>
                <td>".$appointment["Advisors"]."</td>
                <td>
                    <form action='DeleteAppointment.php' method='post' deleteAction>
                        <img src='DeleteIcon.svg' width='50' height='50' />
                        <input name='eventId' type='hidden' value='".$appointment["signupID"]."'>
                        <input name='groupId' type='hidden' value='".$appointment["ID"]."'>
                        <input name='isGroup' type='hidden' value='true'>
                        <input name='studentID' type='hidden' value='".$studentID."'>
                    </form>
                </td>
            </tr>");
        }

        // End table
        print("</table>");
    }
?>

<!-- Make clicking the forms submit them -->
<script type="text/javascript">
    // Get all the forms marked as "delete action"
    var deleteActions = document.querySelectorAll("form[deleteAction]");

    // Add a click listener to the delete actions which makes them submit
    for (var index = 0; index < deleteActions.length; index++)
    {
        deleteActions[index].addEventListener("click", function(event)
        {
            event.srcElement.submit();
        }
    }
</script>

<?php
 include("tailHTML.html");
?>
