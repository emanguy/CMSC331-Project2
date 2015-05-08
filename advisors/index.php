<html>
    <head>
        <title>Advisor Login</title>
    </head>
    <body>
        <!-- Copy of CSS code from other index.php for consistency -->
        <style>

            body {
                background-color: #F5CA5C
            }

            .nameEntryBox {
                float: center;
                position: relative;
                right: -25%;
                background-color: #ffffff;
                width: 50%;
                padding: 8px;
            } 

            h1 {
                text-align: right;
                float: center;
                position: relative;
                right: 35%;
            }

            .errorText
            {
                color: red;
                font-style: italic;
                font-weight: bold;
            }
        </style>

        <img height="10%" src="http://assets1-my.umbc.edu/images/avatars/myumbc/original.png?1425393485"></img><br>

        <!-- Page title -->
        <center>
            <font size="18" color="red" face="Tw Cen MT">Advisor Login</font>
        </center>

        <!-- Begin form -->
        <form action="VerifyLogin.php" method="post">
            <!-- Begin student ID box -->
            <div class="nameEntryBox">
                <!-- Align content correctly -->
                <h1>
                    <!-- Input for student's user ID -->
                    Advisor ID: <input type="text" size="12" maxLength="4" name="advId"
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
