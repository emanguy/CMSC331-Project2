<?php
    include("headHTML.php");
?>

<!-- Copy of CSS code from index.php for consistency -->
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
    } 

    h1 {
        text-align: right;
        float: center;
        position: relative;
        right: 35%;
    }

    h2 {
        text-align: right;
        float: center;
        position: relative;
        right: 10%;
    }

</style>

<!-- Page title -->
<center>
    <font size="18" color="red" face="Tw Cen MT">View or Delete An Appointment</font>
</center>

<!-- Begin form -->
<form action="ShowAppointments.php" method="post">
    <!-- Begin student ID box -->
    <div class="nameEntryBox">
        <!-- Align content correctly -->
        <h1>
            <!-- Input for student's user ID -->
            CAMPUS ID: <input type="text" size="12" maxLength="8" name="tfId"
                placeholder="E.g. AB12345" onblur="this.value=this.value.toUpperCase()" required><br>
        </h1>

        <!-- Submit button -->
        <center>
            <input type="submit" value="VIEW/DELETE APPOINTMENTS">
        </center>
    </div>
</form>

<?php
    include("tailHTML.html");
?>
