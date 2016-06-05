<!DOCTYPE html>
<html>
<title>signout</title>

<?php
include "include.php";
include "header.php";
echo '<h2>Sign out</h2><br>';

if (isset($_SESSION["userid"])){
	$result = $mysqli->query("update users set lastlogtime = NOW() where userid = ".$_SESSION["userid"]);
	session_destroy();
	header("Refresh:0");
}

echo "Succesfully sign out, thank you for visiting.";// You will be redirected in 3 seconds";

$mysqli->close();
include "footer.php";
?>
