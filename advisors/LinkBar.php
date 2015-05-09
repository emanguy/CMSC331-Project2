<!-- Including this file adds the link bar on the top and includes necessary CSS styles -->

<!-- Import external stylesheet -->
<link rel="stylesheet" type="text/css" href="PageStyle.css">

<!-- Navigation bar div -->
<div id="navigationBar">

    <!-- Form styled to look like a link so links pass POST data -->
    <form action="Welcome.php" method="POST">
        <input type="hidden" name="advID" value=<?php print("'".$_POST["advID"]."'"); ?>>
        <input class="submitLink" type="submit" value="MAIN PAGE">
    </form>
</div>

