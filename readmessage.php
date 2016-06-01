<!DOCTYPE html>
<html>
<title>readmessage</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";
if(isset($_GET['id']) && isset($_SESSION['userid'])){
	$id = intval($_GET['id']);
	$query = "select recipient_id
			  from messages
			  where messageid = ?";
	if ($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('s', $id);
	    $stmt->execute();
	    $stmt->bind_result($reid);
	    }
	$stmt->fetch();
	$stmt->close();

	// check whether the user is the recipient of this message.
	if ($reid == $_SESSION['userid']){
		// echo "update messages set readf = 'Y' where messageid = ".$id;
		$result = $mysqli->query("update messages set readf = 'Y' where messageid = ".$id);
		// if ($result) {echo 'OK';}

		$query = "select title,text,time,uname,author,email
				  from messages,users
				  where messageid = ? and author = userid";
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param('s', $id);
		    $stmt->execute();
		    $stmt->bind_result($title, $text, $time, $author,$authorid,$email);
		    }

		if (!$stmt->fetch()) {
		    echo 'failed.';
		    }
		else {
			echo '<table class="topic" border="1">
				  <tr>
				  <th colspan="2">' . htmlentities($title, ENT_QUOTES, 'UTF-8') . '</th>
				  </tr>';
			echo '<tr class="topic-post">';
			echo '<td class="user-post">';
			echo $author.'('.$email.')';
			echo "<br>";
			echo "send at ".$time;
			echo '</td>';
			echo '<td class="post-content">';
			echo htmlentities($text, ENT_QUOTES, 'UTF-8');
			echo '</td>';
			echo '</tr>';
		}

		if(!isset($_SESSION['userid'])) {
				echo '<tr><td colspan=2>You must be <a href="signin.php">signed in</a> to reply. You can also <a href="signup.php">sign up</a> for an account.';
			}
		else{
			//show reply box
			echo '<tr><td colspan="2"><h2>Reply:</h2><br />
				<form method="post" action="sendmessage.php">
				<textarea name="message-content">  '.$author."\n  >>".htmlentities($text, ENT_QUOTES, 'UTF-8')."\n\n".'</textarea><br /><br />
				<input type="hidden" name="receipient" value="'.$authorid.'"/>
				<input type="hidden" name="title" value="'.$title.'"/>
				<input type="hidden" name="type" value="reply"/>
				<input type="submit" value="Submit reply" />
				</form></td></tr>';
			}
					
		//finish the table
		echo '</table>';

		$stmt->close();
	}
	else {
		echo "You have no access to this message.";
	}
}
else{
	echo "Failed. No message is selected or you are not signed in.";
}


$mysqli->close();
include 'footer.php';
?>
