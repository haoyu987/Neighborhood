<!DOCTYPE html>
<html>
<title>relations</title>

<?php
//This page displays the list of the forum's categories
include "include.php";
include "header.php";

echo '<h2>Your friends and neighbors</h2><br>';
if (!isset($_SESSION["userid"])){
	echo 'Sorry, you have to <a href="signin.php">sign in</a> to see your relations.';
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
		echo '<table border="1">
		      <tr>
		      <th>People in the hood</th>
		      <th>Friend Status</th>
		      <th>Neighbor Status</th>
		      </tr>';

		$sql = "select userid,uname,address,x(locationpoint) as lat, y(locationpoint) as lng, profile,email
				from users natural join member natural join block
				where status = 'Y' and userid <> ".$_SESSION["userid"]." and hid = (select hid from users natural join member natural join block where userid = ".$_SESSION["userid"].")";

		$result = $mysqli->query($sql);
		if($result->num_rows == 0){
			echo 'You are the first and the only member in the hood.';
		}
		else{
			while($row = $result->fetch_array()){
				$rows[] = $row;
			}

			foreach($rows as $row)
			{
				echo '<tr>';
				echo '<td class="leftpart">';
				echo $row['uname'].'('.$row['email'].')';
				echo '</td>';
				echo '</td><td class="rightpart1">';
				if ($_SESSION["userid"] < $row['userid']){
					$fstatus = mysqli_fetch_array($mysqli->query("select status from friends where userid1 = ".$_SESSION["userid"]." and userid2 = ".$row['userid']));
				}
				else{
					$fstatus = mysqli_fetch_array($mysqli->query("select status from friends where userid2 = ".$_SESSION["userid"]." and userid1 = ".$row['userid']));
				}
				if (!$fstatus){
					echo '<form action="addrelation.php" method="POST">
						<input type="hidden" name="type" value="friends">
						<input type="hidden" name="fid" value="'.$row['userid'].'">
						<input type="submit" value="Send request" />
						</form>';
				}
				else{
					echo $fstatus['status'];
				}
				echo '</td>';
				echo '</td><td class="rightpart1">';
				$nstatus = mysqli_fetch_array($mysqli->query("select count(*) as cnt from neighbors where userid1 = ".$_SESSION["userid"]." and userid2 = ".$row['userid']));
				if ($nstatus['cnt'] == 0){
					echo '<form action="addrelation.php" method="POST">
						<input type="hidden" name="type" value="neighbors">
						<input type="hidden" name="fid" value="'.$row['userid'].'">
						<input type="submit" value="Add" />
						</form>';
				}
				else{
					echo 'Y';
				}
				echo '</td>';
			}
		}
		echo '</table>';

	    $query = 'select X(locationpoint),Y(locationpoint),address,X(hood.swpoint),Y(hood.swpoint),X(hood.nepoint),Y(hood.nepoint) 
	    		  from users,member,block,hood 
	    		  where users.userid = ? and users.userid = member.userid and member.bid = block.bid and block.hid = hood.hid';
		if ($stmt = $mysqli->prepare($query)) {
	        $stmt->bind_param('i', $_SESSION["userid"]);
	        $stmt->execute();
	        $stmt->bind_result($lat,$long,$address,$hswlat,$hswlong,$hnelat,$hnelong);
	        $stmt->fetch();
	    }
	    $stmt->close();

	    echo "<br><br/>";
		$_SESSION["pName"]=$_SESSION["uname"];
		$_SESSION["pAddress"]=$address;
		$_SESSION["pProfile"]='you';
		$_SESSION["pLat"]=$lat;
		$_SESSION["pLong"]=$long;
		$_SESSION["show"]=0;
	    $_SESSION["pNorth"]=$hnelat;
	    $_SESSION["pSouth"]=$hswlat;
	    $_SESSION["pEast"]=$hnelong;
	    $_SESSION["pWest"]=$hswlong;
	    $_SESSION["drawRectangle"]=1;
	    $_SESSION["query"]= $sql;
		include "map.php";

		$result->close();
	}
	else{
		echo $status;
		echo "You are currently not a member of any blocks.";
	}
}
$mysqli->close();
include 'footer.php';
?>
