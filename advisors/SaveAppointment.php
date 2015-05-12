<?php
    if (isset($_POST["advID"]))
    {
?>
<html>
    <head>
        <title>Group Appointment Submission</title>
    </head>
    <body>
        <?php
            include("LinkBar.php");
        ?>

        <!-- Paw print image -->
        <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>
        
        

<?php
        include("CommonMethods.php");
        $advisorList = implode(", ", $_POST["assignedAdvisors"]);
        $majorList = implode(", ", $_POST["majors"]);
        $DB = new Common(false);

        // Here begins the SQL
        // Update on grpID present
        if (isset($_POST["grpID"]) && strcmp($_POST["grpID"], "") != 0)
        {
            $query = "UPDATE `GroupAppointments` SET `Date/Time`='".$_POST["date"]." ".$_POST["time"]."',`Capacity`=".$_POST["capacity"].",`Majors`='".$majorList."',`Advisors`='".$advisorList."' WHERE `ID` = ".$_POST["grpID"]; 
            $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

            print("<center><h1>Your group appointment was successfully updated!</h1></center>");
        }
        else // Insert on grpID not present
        {
            // Get advisor name
            $query = "SELECT `Name` FROM `Advisor` WHERE `ID` = ".$_POST["advID"];
            $result = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));

            // Check to see if appointment already exists at this time
            $query = "SELECT `ID` FROM `GroupAppointments` WHERE `Advisors` LIKE '%".$result["Name"]."%' AND `Date/Time` = '".$_POST["date"]." ".$_POST["time"]."'";
            $result = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));

            // If no appointment exists with that time or advisor insert
            if ($result["ID"] == NULL)
            {
                $query= "INSERT INTO `GroupAppointments`(`Date/Time`, `Capacity`, `Majors`, `Advisors`) VALUES ('".$_POST["date"]." ".$_POST["time"]."', ".$_POST["capacity"].", '".$majorList."', '".$advisorList."')";
                $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);
                print("<center><h1>Your group appointment was successfully created!</h1></center>");
            }
            else // Print error message otherwise
            {
                print("<center><h1>You already have a group appointment at that time.</h1></center>");
            }
        } 
?>
        <br><br>

        <form action="EventSelect.php" method="POST">
            <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?> >
            <input class="submitLink" type="submit" value="<-- Back to templates">
        </form>
    </body>
</html>

<?php
    }
    else
    {
        include("index.php");
    }
?>
