<?php
    include("headHTML.html");
?>

<!-- Begin CSS styles -->

<style>

    body
    {
        background-color: #F5CA5C;
    }

    table, td, th
    {
        border: 1px solid black;
    }

    table
    {
        border-collapse: collapse;
        background-color: white;
    }

    td
    {
        padding: 5px;
    }
    
    form img
    {
        cursor: pointer;
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
$query = "SELECT `ID`,`Advisor Name`, `Time` FROM `Appointment` WHERE `Student ID` LIKE '".$studentID."' AND `isGroup` = 0 AND `Time` >= '".$date."' ORDER BY `Time` ASC";
$normalAppointmentData = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

$normalAppointments = array();

while ($appointment = MYSQL_FETCH_ARRAY($normalAppointmentData))
{
    array_push($normalAppointments, $appointment);
}

$query = "SELECT `ID`, `appointmentID` FROM `Appointment` WHERE `Student ID` LIKE '".$studentID."' AND `isGroup` = 1 AND `Time` >= '".$date."' ORDER BY `Time` ASC";
$groupAppointmentIds = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

$groupAppointments = array();

while ($idRow = MYSQL_FETCH_ARRAY($groupAppointmentIds))
{
    // Grab the group appointment based on its id
    $query = "SELECT `ID`,`Date/Time`, `Advisors` FROM `GroupAppointments` WHERE `ID` = ".$idRow["appointmentID"];
    $resultArr = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));

    // Add the original event's ID to the array
    $resultArr["signupID"] = $idRow["ID"];

    array_push($groupAppointments, $resultArr);
}

// Display error message if no appointments were fetched
if ($normalAppointments[0] == NULL && $groupAppointments[0] == NULL)
{
    print("<center><h1>You have not signed up for any appointments.</h1></center>");
}
else
{
    // Display individual appointments if any are found
    if ($normalAppointments[0] != NULL)
    {
        // Begin table
        print("<br><h2>Individual appointments</h2>
            <table><tbody>");
        print("<tr>
                    <th>Date/Time</th>
                    <th>Advisor</th>
                    <th>Delete Appointment</th>
               </tr>");

        // Every table row is just the data gathered from the query
        foreach ($normalAppointments as $appointment)
        {
            $formattedTime = date("M j g:i A", strtotime($appointment["Time"]));

            print("<tr>
                <td>".$formattedTime."</td>
                <td>".$appointment["Advisor Name"]."</td>
                <td>
                    <form action='DeleteAppointment.php' method='post' deleteAction>
                        <center>
                            <img src='DeleteIcon.svg' width='50' height='50' />
                        </center>
                        <input name='eventId' type='hidden' value='".$appointment["ID"]."'>
                        <input name='isGroup' type='hidden' value='false'>
                        <input name='studentID' type='hidden' value='".$studentID."'>
                    </form>
                </td>
            </tr>");
        }

        // End table
        print("</tbody></table>");
    }
    if ($groupAppointments[0] != NULL)
    {
        // Begin table
        print("<br><h2>Group Appointments</h2>
            <table><tbody>");
        print("<tr>
            <th>Date/Time</th>
            <th>Advisors</th>
            <th>Delete Appointment</th>
            </tr>");
        
        // Again, table rows are data gathered from queries
        foreach ($groupAppointments as $appointment)
        {
            $formattedTime = date("M j g:i A", strtotime($appointment["Date/Time"]));

            print("<tr>
                <td>".$formattedTime."</td>
                <td>".$appointment["Advisors"]."</td>
                <td>
                    <form action='DeleteAppointment.php' method='post' deleteAction>
                        <center>
                            <img src='DeleteIcon.svg' width='50' height='50' />
                        </center>
                        <input name='eventId' type='hidden' value='".$appointment["signupID"]."'>
                        <input name='groupId' type='hidden' value='".$appointment["ID"]."'>
                        <input name='isGroup' type='hidden' value='true'>
                        <input name='studentID' type='hidden' value='".$studentID."'>
                    </form>
                </td>
            </tr>");
        }

        // End table
        print("</tbody></table>");
    }
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
            event.currentTarget.submit();
        });
    }
</script>

<?php
    include("tailHTML.html");
?>
