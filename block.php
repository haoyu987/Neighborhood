<!DOCTYPE html>
<html>
<title>block</title>

<?php
include "include.php";
include "header.php";

echo '<h2>Your block</h2><br>';
if (!isset($_SESSION["userid"])){
    echo 'Sorry, you have to <a href="signin.php">sign in</a> to apply.';
}
else{
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$sql = "insert into member (bid,userid,status) VALUES (?,?,'0')";
		if ($stmt = $mysqli->prepare($sql)) {
	        $stmt->bind_param('ss', $_POST["bid"],$_SESSION["userid"]);
	        $stmt->execute();
	        $stmt->fetch();
	    }
	    $stmt->close();
	    echo 'Your application has been sent. Please wait for other members to reply.';
	}
	else{
		$sql = "select X(locationpoint),Y(locationpoint),bid,address from users left join member on users.userid=member.userid where users.userid = ?";
		if ($stmt = $mysqli->prepare($sql)) {
	        $stmt->bind_param('s', $_SESSION['userid']);
	        $stmt->execute();
	        $stmt->bind_result($lat,$long,$bid0,$address);
	        $stmt->fetch();
	    }
	    $stmt->close();

	    $sql = "select bid,bname,hname,X(block.swpoint),Y(block.swpoint),X(block.nepoint),Y(block.nepoint)
				from block join hood on block.hid = hood.hid
				where X(block.swpoint) <= ? and Y(block.swpoint) <= ? and X(block.nepoint) >= ? and  Y(block.nepoint) >= ?";
		if ($stmt = $mysqli->prepare($sql)) {
	        $stmt->bind_param('ssss', $lat,$long,$lat,$long);
	        $stmt->execute();
	        $stmt->bind_result($bid,$bname,$hname,$bswlat,$bswlong,$bnelat,$bnelong);
	        $stmt->fetch();
	    }
	    $stmt->close();

	    if ($bid == NULL){
	    	echo "<br>Sorry. Your block is not in our database.<br>";
	    		$_SESSION["pName"]=$_SESSION["uname"];
				$_SESSION["pAddress"]=$address;
				$_SESSION["pProfile"]='you';
				$_SESSION["pLat"]=$lat;
				$_SESSION["pLong"]=$long;
				$_SESSION["show"]=1;
	            $_SESSION["pNorth"]=$lat;
	            $_SESSION["pSouth"]=$lat;
	            $_SESSION["pEast"]=$long;
	            $_SESSION["pWest"]=$long;
	            $_SESSION["drawRectangle"]=1;
				include "map.php";
	    }
	    else{
		    if ($bid0 != $bid){
		    	$sql = "delete from member where userid = ". $_SESSION["userid"];
		    	if ($mysqli->query($sql) === TRUE) {
		        	echo 'You move to another block.';
		    	}
			    echo "According to your current address, we recommend the following block to you.<br></br>";
			    echo "<p>hood <strong>".$hname. "</strong><br>block <strong>".$bname."</strong><br></p>";
	    		$_SESSION["pName"]=$_SESSION["uname"];
				$_SESSION["pAddress"]=$address;
				$_SESSION["pProfile"]='you';
				$_SESSION["pLat"]=$lat;
				$_SESSION["pLong"]=$long;
				$_SESSION["show"]=1;
	            $_SESSION["pNorth"]=$bnelat;
	            $_SESSION["pSouth"]=$bswlat;
	            $_SESSION["pEast"]=$bnelong;
	            $_SESSION["pWest"]=$bswlong;
	            $_SESSION["drawRectangle"]=1;
				include "map.php";

			    echo '<form action="" method="POST">
			    	  <input type="hidden" name="bid" value="'.$bid.'">
			    	  <input type="submit" value="apply">
			    	  </form>';
		    }
		    else{
		    	echo "Your are still in the same block.<br>
		    		  <p>hood <strong>".$hname. "</strong><br>block <strong>".$bname."</strong><br></p>
		    	      If you cannot see any feeds, your application is still under processing.<br>
		    	      Thank you for your patience.";
	          		$_SESSION["pName"]=$_SESSION["uname"];
	      			$_SESSION["pAddress"]=$address;
	      			$_SESSION["pProfile"]='you';
	      			$_SESSION["pLat"]=$lat;
	      			$_SESSION["pLong"]=$long;
	      			$_SESSION["show"]=1;
	                $_SESSION["pNorth"]=$bnelat;
	                $_SESSION["pSouth"]=$bswlat;
	                $_SESSION["pEast"]=$bnelong;
	                $_SESSION["pWest"]=$bswlong;
                    $_SESSION["drawRectangle"]=1;
	      			include "map.php";
		    }
	    }

	}
	$mysqli->close();

}

include 'footer.php';
?>
