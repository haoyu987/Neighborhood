<!DOCTYPE html>
<html>
<title>addrelation</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if(!isset($_SESSION['userid']))
	{
		echo 'You must be signed in to add a relation.';
	}
	else
	{
		$mysqli->query("SET @userid1 = " . $_SESSION['userid']);
		$mysqli->query("SET @userid2 = " . $_POST['fid']);
		if ($_POST['type'] == 'friends'){
			$sql = "Call addfriends(@userid1,@userid2)";
		}
		elseif ($_POST['type'] == 'neighbors'){
			$sql = "Call addneighbors(@userid1,@userid2)";
		}

		$result = $mysqli->query($sql);

		if(!$result)
		{
			echo 'Error please try again later.';
		}
		else
		{
			if($_POST['type'] == 'friends'){
				echo 'Your request has been sent.';
			}
			else{
				echo 'You add a new neighbor.';
			}
		}
	}

$mysqli->close();

include 'footer.php';
?>
