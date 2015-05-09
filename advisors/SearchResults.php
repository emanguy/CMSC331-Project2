<html>
    <head>
        <title>Search Results- UMBC Advising</title>
    </head>
    <body>
        <!-- Import nav bar and custom styles -->
        <?php include("LinkBar.php"); ?>
        <!-- Import table style -->
        <link rel="stylesheet" type="text/css" href="TableStyle.css">

        <!-- Add logo picture -->
        <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

        <!-- Page title -->
        <center><h1><u>Search Results</u></h1></center>

        <?php
            // Store a boolean to see if any appointments were found
            $resultsFound = false;
                
            // Create a connection to the database
            include("CommonMethods.php");
            $DB = new Common(true);

            // Get advisor's name from DB
            $query = "SELECT `Name` FROM `Advisor` WHERE `ID` = '".$_POST["advID"]."'";
            $advisorName = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));

            // Determine day after selected date
            $nextDay = date("Y-m-d", strtotime($_POST["day"]." + 1 day"));

            // Display individual appointments
            if (isset($_POST["chkbIndividual"]))
            {
                // Build next query
                $query = "SELECT `Student Name`, `Student ID`, `Time` FROM `Appointment` WHERE `Advisor Name` LIKE '".$advisorName["Name"]."' AND `isGroup` = 0 AND `Time` >= '".$_POST["day"]."' AND `Time` < '".$nextDay."'";

                // Add student name selector if necessary
                if (isset($_POST["studLast"]) && strcmp($_POST["studLast"], "") != 0)
                {
                    $query = $query." AND `Student Name` LIKE '%".$_POST["studLast"]."%'";
                }

                // Add student ID selector if necessary
                if (isset($_POST["studID"]) && strcmp($_POST["studID"], "") != 0)
                {
                    $query = $query." AND `Student ID` LIKE '".$_POST["studID"]."'";
                }

                $query = $query." ORDER BY `Time` ASC";
                $queryResult = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

                // Build array of SQL results
                $individualApptArray = array();

                while ($arrayRow = MYSQL_FETCH_ARRAY($queryResult))
                {
                    array_push($individualApptArray, $arrayRow);
                }

                // Verify that the query actually returned results
                if ($individualApptArray[0] != NULL)
                {
                    // Mark that results were retrieved
                    $resultsFound = true;

                    // Display table
                    print("<h2>Individual Appointments</h2><br>\n");
                    print("<table><tbody>
                        <tr>
                            <th>Name</th>
                            <th>Student ID</th>
                            <th>Day</th>
                            <th>Time</th>
                        </tr>");
                    
                    // Print results into table
                    foreach ($individualApptArray as $appointment)
                    {
                        print("<tr><td>".$appointment["Student Name"]."</td>");
                        print("<td>".$appointment["Student ID"]."</td>");
                        print("<td>".date("l, M d", strtotime($appointment["Time"]))."</td>");
                        print("<td>".date("g:i a", strtotime($appointment["Time"]))."</td></tr>");
                    }

                    // End table
                    print("</tbody></table><br><br>");
                }
            }

            // Display group appointments
            if (isset($_POST["chkbGroup"]))
            {
                $query = "SELECT `Date/Time`, `Advisors`, `Signups`, `Majors` FROM `GroupAppointments` WHERE `Advisors` LIKE '%".$advisorName["Name"]."%' AND `Date/Time` >= '".$_POST["day"]."' AND `Date/Time` < '".$nextDay."'";

                // Add name search if necessary
                if (isset($_POST["studLast"]) && strcmp($_POST["studLast"], "") != 0)
                {
                    $query = $query." AND `Signups` LIKE '%".$_POST["studLast"]."%'";
                }

                // Add student ID search if necessary
                if (isset($_POST["studID"]) && strcmp($_POST["studID"], "") != 0)
                {
                    // Determine student last name from appointment table
                    $secondQuery = "SELECT `Student Name` FROM `Appointment` WHERE `Student ID` LIKE '".$_POST["studID"]."'";
                    $studentName = MYSQL_FETCH_ARRAY($DB->executeQuery($secondQuery, $_SERVER["SCRIPT_NAME"]));
                    $studentName = $studentName["Student Name"];

                    // Add student name to query
                    $query = $query." AND `Signups` LIKE '%".$studentName."%'";
                }

                $query = $query." ORDER BY `Date/Time` ASC";
                $queryResult = $DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]);

                // Build array of SQL results
                $groupArray = array();

                while ($arrayRow = MYSQL_FETCH_ARRAY($queryResult))
                {
                    array_push($groupArray, $arrayRow);
                }

                // Verify that the search actually returned results
                if ($groupArray[0] != NULL)
                { // Mark that results were retrieved
                    $resultsFound = true;

                    // Display table
                    print("<h2>Group Appointments</h1><br>\n");
                    print("<table><tbody>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Advisors</th>
                            <th>Students</th>
                            <th>Majors</th>
                        </tr>");
                    
                    // Print results into table
                    foreach ($groupArray as $appointment)
                    {
                        print("<tr><td>".date("l, M d", strtotime($appointment["Date/Time"]))."</td>");
                        print("<td>".date("g:i a", strtotime($appointment["Date/Time"]))."</td>");
                        print("<td>".$appointment["Advisors"]."</td>");

                        // Print students if they are signed up, otherwise print "NONE"
                        if (strcmp($appointment["Signups"], "") != 0)
                        {
                        print("<td>".$appointment["Signups"]."</td>");
                        }
                        else
                        {
                           print("<td>NONE</td>");
                        }

                        print("<td>".$appointment["Majors"]."</td></tr>");
                    }

                    // End table
                    print("</tbody></table><br><br>");
                }
            }

            // Display text on no search results
            if (!$resultsFound)
            {
                print("<center><h2 class='errorText'>No results found for your search.</h2></center>");
            }
        ?>

        <!-- Link back to search page -->
        <br><hr>
        <form action="ApptDayPick.php" method="POST">
            <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?>>
            <input class="submitLink" type="submit" value="&#8592; BACK TO SEARCH">
        </form>
    </body>
</html>
