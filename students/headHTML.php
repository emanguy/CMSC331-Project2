<html>
<style>
body {
	background-color: #F5CA5C;
}
.toolbar {
	float: left;
	position: relative;
	background-color: white;
	width: 100%;
	height: 6%;
}

/* This CSS styles a submit button in a form to look like a link */
input[type="submit"].submitLink
{
    border: 0px;
    background-color: rgba(0,0,0,0);
    color: blue;
    text-decoration: underline;
    cursor: pointer;
    font-family: monospace;
}
</style>
<head>
	<title>
		Advising Kiosk
	</title>
</head>
	
<body>

<div class="toolbar">
    <pre>
    <a href='./'>HOME</a>   <?php
     if (isset($_POST["tfId"]) && strcmp($_POST["tfId"], "") != 0)
     {
         print("<form action='ShowAppointments.php' method='POST'> <input type='hidden' name='tfId' value='".$_POST["tfId"]."'> <input class='submitLink' type='submit' value='DELETE/VIEW APPOINTMENTS'> </form>");
     }
     else
     {
        print("<a href='./DeleteAppointmentBegin.php'>DELETE/VIEW APPOINTMENTS</a>");
     }
     ?>   <a href='http://www.umbc.edu/advising/dept_advising.html'>ADDITIONAL SUPPORT</a>

    </pre>
</div>

<img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>
