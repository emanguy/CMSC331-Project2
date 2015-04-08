<!-- The CSS styles for the page -->

<style>

h1 {
    color: black;
    text-align: left;
    font-size: 16px;
}

h2 {
    color: black;
    text-align: center;
    font-size: 16px;
    right: 0%;
}

h3 {
    color: black;
    font-size: 12px;
    text-align: right;
}

.right {
    float: right;
    width: 300px;
}

.button {
    float: center;
    width: 20%;
    heigth: 50%;
    position: relative;
    right: -45%;
}

.table {
    float: center;
    width: 20%;
    heigth: 50%;
    position: relative;
    right: -40%;
    background-color: white;
}

</style>

<?php

#####################################################################
#
# This file handles getting the schedules for the selected advisors
# or majors
#
#####################################################################

print("<form action='Results3.php' method='post' name='SendApptType'>");

include("headHTML.html");
include("Utilities.php");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$cbMajors = unserialize($_POST['cbMajors']);

# Get the day two days later
$twoDaysLater = date("Y-m-d", strtotime("+2 days"));

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");

$chkbIndividual = $_POST['chkbIndividual'];
$chkbGroup = $_POST['chkbGroup'];
$arraySelectedAdvisors = $_POST['arraySelectedAdvisors'];

$debug = false; 
include('./CommonMethods.php');
$COMMON = new Common($debug); // common methods

# Prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
print("<br><br><hr>");

# Stringify the list of checkboxes and save them for passing
$arr = serialize($chkbIndividual);
print("<input type='hidden' name='chkbIndividual' value='$arr'>");
$arr = serialize($chkbGroup);
print("<input type='hidden' name='chkbGroup' value='$arr'>");
$arr = serialize($arraySelectedAdvisors);
print("<input type='hidden' name='arraySelectedAdvisors' value='$arr'>");

for($num = 0; $num < count($arraySelectedAdvisors); $num++) {

    # Split the list of majors by comma
    $selected = explode(", ", $arraySelectedAdvisors[$num]);

    # Set name and major by given advisor list
    $name = $selected[0];
    $major = $selected[1];

    # Get the list of advisors for the specified major
    $majorRow = getMajorRow($currChoice);
    $advisorField = $majorRow['Advisors'];

    # Breaks continuous string of names into individual name objects
    $advisorNames = explode(", ", $advisorField);

    print("<h1>");
    # Prints out all information about each advisor (Email, Position, Location)

    $sql2 = "select * from `Advisor` where `Name` = '$name'";
    $rs2 = $COMMON-> executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
    $row2 = mysql_fetch_array($rs2);
    print($row2['Name'] . "<br>");
    $email = $row2['Email'];
    print("<a href='mailto:$email'>$email</a><br>" . $row2['Position'] . "<br>"); 

    print("<h2>");

    # Prints out a date picker for individual advising if applicable
    $count = 0;
    if ($chkbIndividual != NULL) {
        foreach ($chkbIndividual as $checkObj) {

            if($checkObj == $major) {
                print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True' checked>"); 

                # Minimum date on picker is now the date calculated in $twoDaysLater
                print("<input type='date' min = '".$twoDaysLater."' name = 'dateApptI[]' required><br>");
                $count++;
            }

        }
    }

    # No matches
    if ($count == 0) {
        print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True'><br>"); 
    }

    # Print out a date picker for group advising if applicable
    $count = 0;
    if ($chkbGroup != NULL) {
        foreach ($chkbGroup as $checkObj) {
            if ($checkObj == $major) {

                print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True' checked>"); 

                # Again, minimum date is date calculated in $twoDaysLater
                print("<input type='date' min = '".$twoDaysLater."' name = 'dateApptG[]' required><br>");
                $count++;
            }
        }
    }

    # No matches
    if ($count == 0) {
        print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' value='True'><br>"); 
    }

    print("</div>");
    print("</h2>");


}
# Submit button
print("<div class = 'button'>");
print("<input type='submit' value='SELECT APPOINTMENTS'>"); 
print("</div>");
include("tailHTML.html");

?>



