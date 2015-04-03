<html>
	<head>
	</head>

	<body>

	<?php

		$debug = false;
		include('../CommonMethods.php');
		$COMMON = new Common($debug); // common methods

		$sql = "select * from question";

		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

		echo("<div>");

			while($row = mysql_fetch_row($rs))
			{
    				// REPEATING HTML Code goes here
      				echo("Question: ");
    				echo($row[0]);
    				echo("<br>");
    				echo($row[1]);
    				echo("<br>");

				echo('<form action="results.php" method="post" name="Survey">');
    					for($x = 3; $x <= 7; $x++) {
        					if($row[$x] != null) 
						{
         						echo('<input type = "radio" name = "option$x" >');
          						echo($row[$x]);
          						echo("<br>");
						}
					}
    				echo("</form>");
			}

			echo("<input type='submit' value='Submit'><br>");
			echo("<br>");
    
		echo("</div>");
	?>

	</body>
</html>