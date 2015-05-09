<!-- This page allows the advisor to search their appointments -->
<html>
<head>
    <title>Appointment Search</title>
    
    <!-- Page-specific styles -->
    <style type="text/css">
        
        /* This creates vertical space between elements in the box containing the form */
        div.centerBox > form > *
        {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <!-- Add link bar -->
    <?php include("LinkBar.php"); ?>

    <!-- Paw print image -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

    <!-- Page title. -->
    <center>
        <font size="18" color="red" face="Tw Cen MT">Search For Appointments</font>
    </center>

    <!-- Box on page -->
    <div class="centerBox">
        <form action="SearchResults.php" method="POST">
            <center><h2>REQUIRED FIELDS:</h2></center>
            <?php
                // Get the date 2 days from now
                $startDate = date("Y-m-d");

                // Output necessary form elements
                print("<input type='hidden' name='advID' value='".$_POST["advID"]."'>\n");
                print("<label for='day'>Date: </label>");
                print("<input type='date' name='day' min='".$startDate."' value='".$startDate."' required>\n");
            ?>
            <br>

            <!-- Don't need to check for lack of checked boxes here
                because the next page handles the "no results" case -->
            <label for="chkbIndividual">Individual Appointments</label>
            <input type="checkbox" name="chkbIndividual"checked>
            <label for="chkbGroup">Group Appointments</label>
            <input type="checkbox" name="chkbGroup" checked>
            <br>

            <center><h2>OPTIONAL FIELDS:</h2></center>
            <label for="studLast">Student Last Name:</label>
            <input type="text" name="studLast" placeholder="E.g. Smith">
            <br>

            <label for="studID">Student ID:</label>
            <input type="text" name="studID" placeholder="E.g. AB12345">
            <br>

            <input id="searchSubmit" type="submit" value="SEARCH APPOINTMENTS">
        </form>
    </div>
</body>
</html>
