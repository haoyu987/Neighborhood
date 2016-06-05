<!DOCTYPE html>
<html>
<title>searchpost</title>

<?php
include "include.php";
include "header.php";

echo '<h2>Search post</h2><br>';
if (!isset($_SESSION["userid"])){
	echo 'Sorry, you have to <a href="signin.php">sign in</a> to search post.';
}
else{
	$sql = "select status from member where userid = ?";
	if ($stmt = $mysqli->prepare($sql)){
		$stmt->bind_param('i', $_SESSION['userid']);
    	$stmt->execute();
    	$stmt->bind_result($status);
	}
	$stmt->fetch();
	$stmt->close();
	if ($status == 'Y'){

		if (isset($_GET["type"])){
			echo '<form method="GET" action="searchpost.php?type='.$_GET["type"].'&keywords='.str_replace(" ","+",$_GET['keywords']).'">
			Search fields:
    		<select name="type">

			<option value="subject">subject</option>
			<option value="title">title</option>
			<option value="text">text</option>
			<option value="location">location</option>
			<option value="all">all</option>
			</select>
			keywords:
			<input type="text" name="keywords">
			<input type="submit" value="Search"><br>';


			$sql = "Call postsSearcher(?,?,?)";
			if ($stmt = $mysqli->prepare($sql)) {
    			$keyword = "%".mysqli_real_escape_string($mysqli,$_GET['keywords'])."%";
    			$stmt->bind_param('sss', $_SESSION['userid'], $_GET["type"], $keyword);
    			$stmt->execute();
    			$stmt->bind_result($pid, $subject, $title, $author, $time, $group, $email);
    		}

    			echo '<table border="1">
      				<tr>
      				<th>Posts</th>
      				<th>From</th>
      				</tr>';

      			if (!$stmt->fetch()) {
    				echo '<tr><td class="leftpart">No matched posts.</td></tr>';
    			}
				else {
					echo '<tr>';
					echo '<td class="leftpart">';
					echo '<h3><a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a>['.$group.']['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</h3>';
					echo '</td>';
					echo '<td class="rightpart">';
					echo $author.'('.$email.')';
					echo '<br> created at ';
					echo $time;
					echo '</td>';
					echo '</tr>';

				    while ($stmt->fetch()) {
				    	echo '<tr>';
						echo '<td class="leftpart">';
						echo '<h3><a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a>['.$group.']['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</h3>';
						echo '</td>';
						echo '<td class="rightpart">';
						echo $author.'('.$email.')';
						echo '<br> created at ';
						echo $time;
						echo '</td>';
						echo '</tr>';
    					}
    				echo '</table>';
    				$stmt->close();
				}
		}
		else
		{
			echo '<form method="GET" action="">
			Search fields:
	    	<select name="type">

			<option value="subject">subject</option>
			<option value="title">title</option>
			<option value="text">text</option>
			<option value="location">location</option>
			<option value="all">all</option>
			</select>
			keywords:
			<input type="text" name="keywords">
			<input type="submit" value="Search"><br>';
		}

	}
	else{
		echo "You are currently not a member of any blocks.";
	}

}
$mysqli->close();
include 'footer.php';
?>
