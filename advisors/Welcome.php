<?php
// Get advisor ID from POST
$advisorID = $_POST["advID"];
if (!isset($advisorID)) {
include("index.php");
exit();
}
?>
<html>
<head>
    <title>Advisor Control Panel </title>

    <!-- Page-specific styles -->
    <style type="text/css">
        form
        {
            display: inline;
            margin: 4px;
        }
    </style>
</head>

<body>
    <!-- "Special" link bar which passes POST data on link clicks -->
    <?php include("LinkBar.php") ?>

    <!-- Paw print logo -->
    <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

    <!-- Page title. PHP retrieves the advisor's name. -->
    <center>
        <font size="18" color="red" face="Tw Cen MT">Welcome, 
        <?php
                    // Get advisor ID from POST
                    $advisorID = $_POST["advID"];

                    /*
                     * This statement fixes an issue with my implementation on advisor validation.
                     * When AttemptLogin.php includes this file, common already
                     * exists and I get errors when I try to reinstantiate the class.
                     */  
                    if (!class_exists(Common))
                    {
                        include("CommonMethods.php");
                    }

                    // Connect to DB
                    $DB = new Common(false);
                    $query = "SELECT `Name` FROM `Advisor` WHERE `ID` = ".$advisorID;

                    // Get the advisor's name
                    $advisorName = MYSQL_FETCH_ARRAY($DB->executeQuery($query, $_SERVER["SCRIPT_NAME"]));
                    print($advisorName["Name"]);
    ?>!</font>
    </center>

    <!-- Box on page -->
    <div class="centerBox">

        <!-- "Control Panel" title -->
        <center>
            <h2>YOUR CONTROL PANEL</h2>
        </center>

        <!-- Navigation buttons to other pages. Every button sends the POST variable "advID" -->
        <center>
            <form action="EventSelect.php" method="POST">
                <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?>>
                <input type="submit" value="Modify Appointment Availability">
            </form>
            <form action="ApptDayPick.php" method="POST">
                <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?>>
                <input type="submit" value="View Appointments">
            </form>
            <form action="TemplateWeek.php" method="POST">
                <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?>>
				<input type="hidden" name="advName" value=<?php print("'".$advisorName["Name"]."'"); ?>>
                <input type="submit" value="Set Default Templates">
            </form>
        </center>
    </div>
</body>
</html>
