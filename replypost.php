<!DOCTYPE html>
<html>
<title>replypost</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if(!isset($_SESSION['userid']))
	{
		echo 'You must be signed in to send a message.';
	}
	else {
		$sql = 'INSERT INTO 
				reply(postid,
						 replier,
						 text) 
				VALUES (' . $_POST['postid'] . ',
					    ' . $_SESSION['userid']. ',
					    "'. $_POST['message-content'].'")';
	}
		
	$result = $mysqli->query($sql);

	if(!$result){
			echo 'Your message has not been sent, please try again later.';
	}
	else
	{
			echo 'Your message has been saved. check out <a href="readpost.php?group='.$_POST['group'].'&id='.$_POST['postid'].'">the post</a>.';
	}

$mysqli->close();
include 'footer.php';
?>
