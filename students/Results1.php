<!-- The CSS styles for the page -->

<style>

h1 {
    color: black;
    text-align: left;
    font-size: 1em;
}

h2 {
    color: black;
    text-align: center;
    font-size: 1em;
    right: 0%;
}

h3 {
    color: black;
    font-size: 1em;
    text-align: right;
}

.showButton {
	float: center;
	font-size: 1.5em;
	width: 25%;
	heigth: 50%;
	position: relative;
	right: -40%;
}

.apptType {
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
# This file handles getting the advisors from the database based on 
# selected majors
#
#####################################################################


include("headHTML.php");
include("Utilities.php");

print("<form action='Results2.php' method='post' name='SendApptType'>");

$tfNameF = $_POST['tfNameF'];
$tfNameL = $_POST['tfNameL'];
$tfId = $_POST['tfId'];
$cbMajors = $_POST['cbMajors'];

# These are used for the showing of individual appointment availabilities
$selectedAdvisorList;
$booleanList;

$chkbIndividual;
$chkbGroup;

$debug = false; 
include('./CommonMethods.php');
$COMMON = new Common($debug); // common methods

# Prints out user's info
print($tfNameF . " " . $tfNameL);
print("<br>" . $tfId);
	print("<div class = 'showButton'>");
        # Disable until at least one of the "at least one required" checkboxes is checked

		print("Show Appointments: <input type='submit' style='height: 5%; width: 25%;' value='GO' id='btnGo' disabled>"); 
	print("</div>");
print("<br><br><hr>");

print("<h2>");
print("<div class = 'apptType'>");
       	# The requiredAtLeastOne attribute makes it easy for the javascript to access all the "at least one required" checkboxes
	print("Individual Advising: <input type='checkbox' style='height: 20px; width: 20px;' name='chkbIndividual' requiredAtLeastOne-apptType><br>"); 
	print("Group Advising: <input type='checkbox' style='height: 20px; width: 20px;' name='chkbGroup' requiredAtLeastOne-apptType><br>"); 
print("</div>");
print("</h2>");

print("<input type='hidden' name='tfNameF' value='$tfNameF'>");
print("<input type='hidden' name='tfNameL' value='$tfNameL'>");
print("<input type='hidden' name='tfId' value='$tfId'>");

$arr = serialize($cbMajors);
print("<input type='hidden' name='cbMajors' value='$arr'>");

# Set up first part of query with first major choice
$advisorNames = "SELECT * FROM `Advisor` WHERE `Majors` LIKE '%$cbMajors[0]%'";

# Add additional major choices after the first one
for ($i = 1; $i < count($cbMajors); $i++)
{
		$advisorNames = $advisorNames . " OR `Majors` LIKE '%$cbMajors[$i]%'";
}

$advisorNames = $advisorNames . " ORDER BY `Majors` ASC, `Name` ASC";

$rs = $COMMON-> executeQuery($advisorNames, $_SERVER["SCRIPT_NAME"]);
while ($row = mysql_fetch_array($rs)) 
{
	printAdvisors($row['Name']);
}

?>

<!-- Submit validator script -->
<script type="text/javascript">

// Create the function that enables and disables the submit button 
function enableDisable() 
{ 
    // Get all "at least one required" elements
    var atLeastOne = document.querySelectorAll("input[requiredAtLeastOne-apptType]");
    var advAtLeastOne = document.querySelectorAll("input[requiredAtLeastOne-advisor]");

    // Make separate booleans for grouping and advisor checkboxes
    var groupingNotChecked;
    var advisorNotChecked;
    
    // Loop through the group/individual appointment checkboxes
    for (var index = 0; index < atLeastOne.length; index++) 
    { 
        // If at least one required box is checked, disabled becomes false
        groupingNotChecked = !(atLeastOne[index].checked);

        // Break on first checked box
        if (groupingNotChecked == false)
        {
            break;
        }
    } 
    
    // Loop through the individual advisor checkboxes
    for (var index = 0; index < advAtLeastOne.length; index++) 
    { 
        // If at least one required box is checked, disabled becomes false
        advisorNotChecked = !(advAtLeastOne[index].checked);

        // Break on first checked box
        if (advisorNotChecked == false)
        {
            break;
        }
    } 

    // Disable the submit button based on results
    document.getElementById("btnGo").disabled = groupingNotChecked || advisorNotChecked; 
}

// Get all the checkboxes on the page
var inputs = document.querySelectorAll("input[type=checkbox]");

// Add an event listener to each which calls the enableDisable function
for (var index = 0; index < inputs.length; index++) 
{ 
    inputs[index].addEventListener("click", function() { enableDisable(); });
}
</script>

<?php
include("tailHTML.html");

?>

