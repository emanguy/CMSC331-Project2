<!-- Login page -->
<html>
    <head>
        <title>Advisor Login</title>
    </head>
    <body>
        <!-- Import typical page styles -->
        <link rel="stylesheet" href="PageStyle.css" type="text/css">
        <!-- Copy of CSS code from other index.php for consistency -->
        <style>
            h1 {
                text-align: right;
                float: center;
                position: relative;
                right: 35%;
            }
        </style>

        <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

        <!-- Page title -->
        <center>
            <font size="18" color="red" face="Tw Cen MT">Advisor Login</font>
        </center>

        <!-- Begin form -->
        <form action="AttemptLogin.php" method="post">
            <!-- Begin student ID box -->
            <div class="centerBox">
                <!-- Align content correctly -->
                <h1>
                    <!-- Input for student's user ID -->
                    Advisor ID: <input type="text" size="12" maxLength="4" name="advID"
                        placeholder="E.g. 1" required><br>
                </h1>

                <!-- Optional error message upon wrong advisor ID -->
                <?php
                if (isset($_GET["failedLogin"]))
                {
                    print("<center><p class='errorText'>Login failed. You may have entered an incorrect advisor ID.</p><center>\n");
                }
                ?>

                <!-- Submit button -->
                <center>
                    <input type="submit" value="LOGIN">
                </center>
            </div>
        </form>
    </body>
</html>
