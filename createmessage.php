<!DOCTYPE html>
<html>
<title>create message</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if(!isset($_SESSION['userid'])) {
		echo '<tr><td colspan=2>You must be <a href="signin.php">signed in</a> to reply. You can also <a href="signup.php">sign up</a> for an account.';
	}
else{
	//show reply box
	echo '<tr><td colspan="2"><h2>message:</h2><br />
		<form method="post" action="sendmessage.php">
		send to<input type="text" name="receipient" required="required"/><br/>
		title  <input type="text" name="title" required="required"/><br/>
		<input type="hidden" name="type" value="initial"/>
		<textarea name="message-content" required="required"></textarea><br /><br />
		<input type="submit" value="Send message" />
		</form></td></tr>';
	}
			
//finish the table
echo '</table>';

include 'footer.php';
?>
