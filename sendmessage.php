<!DOCTYPE html>
<html>
<title>sendmessage</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if(!isset($_SESSION['userid']))
	{
		echo 'You must sign in to send a message.';
	}
	else
	{
		if ($_POST['type'] == 'reply'){
		$sql = 'INSERT INTO 
				messages(recipient_id,
						 title,
						 author,
						 text) 
				VALUES (' . $_POST['receipient'] . ',
					    "Re:' . $_POST['title'] . '",
					    '.$_SESSION['userid'].',
					    "'. $_POST['message-content'].'")';
		}
		elseif ($_POST['type'] == 'initial') {
			$result = $mysqli->query('select userid from users where email = "'.$_POST['receipient'].'"');
			if (!$result) {
				echo "Wrong receipient Email.";
				$sql = 0;
			}
			else{
				$row = $result->fetch_assoc();
		        $sql = 'INSERT INTO 
				messages(recipient_id,
						 title,
						 author,
						 text) 
				VALUES (' .$row['userid']. ',
					    "' . mysqli_real_escape_string($mysqli,$_POST['title']) . '",
					    '.$_SESSION['userid'].',
					    "'. mysqli_real_escape_string($mysqli,$_POST['message-content']).'")';
				$result->free();
			}

		}
		
		$result = $mysqli->query($sql);
		if(!$result)
		{
			echo 'Your message has not been sent, please check the email address.';
		}
		else
		{

			echo 'Your message has been sent. ';
		}
	}

$mysqli->close();
include 'footer.php';
?>
