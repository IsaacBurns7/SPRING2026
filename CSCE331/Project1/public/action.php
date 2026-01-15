<html>
<body>

<h1>Action.php</h1>

This page can be called by other PHP scripts or from itself. 

<p/>

<?php

# Function
function proc_action($mode) {
  
  echo "<font color=\"green\">Calling function proc_action(): The \$mode was $mode </font> <p/>\n";

}


# Test function call
proc_action($_GET["getName"]);


# URL usage: action.php?getName=string
if ($_GET["getName"]) {
  echo "<font color=\"red\">The GET keyword was ".$_GET["getName"]."</font>\n";
}

# URL usage: action.php
if ($_POST["name"]) {
  echo "<font color=\"blue\">The POST keyword was ".$_POST["name"]."</font>\n";
}

?>

<p/>

<!-- GET form method testing area ................................ --> 
<form action="action.php" method="get">
GET method test (on self): <input type="text" name="getName">
<input type="Submit">
</form>

<!-- POST form method testing area ................................ --> 
<form action="action.php" method="post">
POST method test (on self): <input type="text" name="name">
<input type="Submit">
</form>

</body>
</html>
