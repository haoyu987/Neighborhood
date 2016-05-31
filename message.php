<!DOCTYPE html>
<html>
<title>messagebox</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if (!isset($_SESSION["userid"])){
    echo 'Sorry, you have to <a href="signin.php">sign in</a> to access to message.';
}
else{
	echo '<form action="createmessage.php" method="POST">';
	echo '<input type="submit" value="send a message" />';
	echo '</form>';
	echo '<br>';

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	    if ($_POST['type'] == 'block'){
	    	if ($_POST['state'] == 'Y'){
	    		$result=$mysqli->query("update member set status = status+1 where userid=".$_POST['fromid']);
	    	}
	    }
	    elseif ($_POST['type'] == 'friends'){
	    	$minid = min($_POST['fromid'],$_SESSION["userid"]);
	    	$maxid = max($_POST['fromid'],$_SESSION["userid"]);
	    	if ($_POST['state'] == 'Y'){
	    		$result=$mysqli->query("update friends set status = 'Y' where userid1=".$minid." and userid2 = ".$maxid);
	    		$result=$mysqli->query("insert into `messages` (`recipient_id`,`title`,`author`,`text`) VALUES (".$_POST['fromid'].", 'New Friends', ".$_SESSION["userid"].", 'I have accepted your request. We are friends now.')");
	    	}
	    	else{
	    		$result=$mysqli->query("delete from friends where userid1=".$minid." and userid2 = ".$maxid);
	    	}
		}
		$result=$mysqli->query("delete from requests where requestid=".$_POST['rid']);
	}

	$query = "select requestid, request_type, from_id, uname, email
		      from requests,users
				  where rec_id = ? and from_id = userid";

	if ($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('s', $_SESSION["userid"]);
		$stmt->execute();
		$stmt->bind_result($rid, $type, $fromid, $fromname, $email);
	}

	echo '<table border="1">
	  <tr>
	  <th>Request</th>
	  <th>  </th> <th>  </th>
	  </tr>';

	if (!$stmt->fetch()) {
	echo '<tr><td class="leftpart">You have no request.</td></tr>';
	}
	else {
		echo '<tr>';
		echo '<td class="leftpart">';
		if ($type == 'friends'){
			echo '<p><strong>['. $type.']</strong>'.$fromname.'('.$email.')'.' '.'want to add you as a friend.</p>';
		}
		else{
			echo '<p><strong>['. $type.']</strong>'.$fromname.'('.$email.')'.' '.'want to join the block.</p>';
		}
		echo '</td><td class="rightpart1">';
		echo '<form action="message.php" method="POST">
				<input type="hidden" name="type" value="'.$type.'">
				<input type="hidden" name="fromid" value="'.$fromid.'">
				<input type="hidden" name="rid" value="'.$rid.'">
				<input type="hidden" name="state" value="Y">
				<input type="submit" value="Y" />
				</form>';
		echo '</td>';

		echo '</td><td class="rightpart1">';
		echo '<form action="message.php" method="POST">
				<input type="hidden" name="type" value="'.$type.'">
				<input type="hidden" name="fromid" value="'.$fromid.'">
				<input type="hidden" name="rid" value="'.$rid.'">
				<input type="hidden" name="state" value="N">
				<input type="submit" value="N" />
				</form>';
		echo '</td>';
		echo '</tr>';
	}

	while ($stmt->fetch()) {
			echo '<tr>';
			echo '<td class="leftpart">';
			if ($type == 'friends'){
				echo '<p><strong>['. $type.']</strong>'.$fromname.'('.$email.')'.' '.'want to add you as a friend.</p>';
			}
			else{
				echo '<p><strong>['. $type.']</strong>'.$fromname.'('.$email.')'.' '.'want to join the block.</p>';
			}
			echo '</td><td class="rightpart1">';
			echo '<form action="message.php" method="POST">
					<input type="hidden" name="type" value="'.$type.'">
					<input type="hidden" name="fromid" value="'.$fromid.'">
					<input type="hidden" name="rid" value="'.$rid.'">
					<input type="hidden" name="state" value="Y">
					<input type="submit" value="Y" />
					</form>';
			echo '</td>';

			echo '</td><td class="rightpart1">';
			echo '<form action="message.php" method="POST">
					<input type="hidden" name="type" value="'.$type.'">
					<input type="hidden" name="fromid" value="'.$fromid.'">
					<input type="hidden" name="rid" value="'.$rid.'">
					<input type="hidden" name="state" value="N">
					<input type="submit" value="N" />
					</form>';
			echo '</td>';
			echo '</tr>';
	}
	$stmt->close();

	echo '<table>
			<tr>
	      <td></td>
	      </tr>';

	$query = "select messageid, title, uname, time, email
	          from messages,users
	 		  where readf = 'N' and author = userid and recipient_id = ?
	 		  order by time desc";
	if ($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('s', $_SESSION["userid"]);
	    $stmt->execute();
	    $stmt->bind_result($mid, $title, $author, $time, $email);
	    }

	echo '<table border="1">
	      <tr>
	      <th>Unread Messages</th>
	      <th>From</th>
	      </tr>';

	if (!$stmt->fetch()) {
	    echo '<tr><td class="leftpart">You have no unread message.</td></tr>';
	    }
	else {
		echo '<tr>';
		echo '<td class="leftpart">';
		echo '<p><a href="readmessage.php?id='.$mid.'"'.'</a>'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</p>';
		echo '</td>';
		echo '<td class="rightpart">';
		echo $author.'('.$email.')';
		echo '<br> sent at ';
		echo $time;
		echo '</td>';
		echo '</tr>';
	    while ($stmt->fetch()) {
	    	echo '<tr>';
			echo '<td class="leftpart">';
			echo '<p><a href="readmessage.php?id='.$mid.'"'.'</a>'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</p>';
			echo '</td>';
			echo '<td class="rightpart">';
			echo $author.'('.$email.')';
			echo '<br> sent at ';
			echo $time;
			echo '</td>';
			echo '</tr>';
	    }
	}
	$stmt->close();

	$query = "select messageid, title, uname, time, email
	          from messages,users
	 		  where readf = 'Y' and author = userid and recipient_id = ?
	 		  order by time desc";

	if ($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('s', $_SESSION["userid"]);
	    $stmt->execute();
	    $stmt->bind_result($mid, $title, $author, $time, $email);
	    }

	echo '<table border="1">
	      <tr>
	      <th>Read Messages</th>
	      <th>From</th>
	      </tr>';

	if (!$stmt->fetch()) {
	    echo '<tr><td class="leftpart">You have no message.</td></tr>';
	    }
	else {
		echo '<tr>';
		echo '<td class="leftpart">';
		echo '<p><a href="readmessage.php?id='.$mid.'"'.'</a>'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</p>';
		echo '</td>';
		echo '<td class="rightpart">';
		echo $author.'('.$email.')';
		echo '<br> sent at ';
		echo $time;
		echo '</td>';
		echo '</tr>';
	    while ($stmt->fetch()) {
	    	echo '<tr>';
			echo '<td class="leftpart">';
			echo '<p><a href="readmessage.php?id='.$mid.'"'.'</a>'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</p>';
			echo '</td>';
			echo '<td class="rightpart">';
			echo $author.'('.$email.')';
			echo '<br> sent at ';
			echo $time;
			echo '</td>';
			echo '</tr>';
	    }
	}
	echo '</table>';

	$stmt->close();
}

$mysqli->close();

include 'footer.php';
?>
