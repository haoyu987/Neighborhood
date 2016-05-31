<!DOCTYPE html>
<html>
<title>searchpost</title>

<?php
include "include.php";
include "header.php";

echo "<h2>Edit your profile</h2><br>";
//if the user is already logged in, redirect them back to homepage
if(!isset($_SESSION["userid"])) {
  echo "You are not logged in. \n";
  echo "You will be redirected in 3 seconds or click <a href=\"login.php\">here</a>.\n";
  header("refresh: 3; login.php");
}
else {
	  $userid = $_SESSION["userid"];
    if(isset($_POST["uname"])&& ($_POST["password"]==$_POST["confirm"])){
		$password = md5($_POST["password"]);
		$uname = $_POST["uname"];
		$address = $_POST["address"];
		$profile = $_POST["profile"];

		$googleAddress = str_replace(" ","+",$address);
        $url = "http://maps.google.com/maps/api/geocode/json?address=$googleAddress&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
		if(!$response_a->results)
		{
			echo "Address Not Found!";
			echo '<br /><a href="profile.php">Go back</a>';
		}
		else{
        $lat = $response_a->results[0]->geometry->location->lat;
        $long = $response_a->results[0]->geometry->location->lng;
              if ($stmt = $mysqli->prepare("Update users set uname = ?,password=?,address=?,profile =?,locationpoint = point(?,?) where userid =?")){
			         $stmt->bind_param("ssssdds",$uname,$password,$address,$profile,$lat,$long,$userid);
    		 //		  	sleep(1); //pause a bit to help prevent brute force attacks
              if($stmt->execute()){
                if ($_POST["oldaddress"] == $_POST["address"]){
                        echo "Succeeed!";
                        $_SESSION["uname"] = $uname;
                        echo '<br /><a href="overview.php">Go back</a>';
                }
                else {
                   echo 'moved?</br>';
                   echo '<br>check out your <a href="block.php">New block</a>.';
                }
    			     }
    			     else{
    				      echo "Failed!";
    				      echo '<br /><a href="profile.php">Try again</a>';
    			     }
              $stmt->close();

              }
			  else
			  	echo "stmt failed";

		      
         }   			
    }
    else{
    	if(isset($_POST["uname"]))
    		echo "Please retype your passwords:<br /><br />";
    	else 
    		echo "Please input your inforamation below: <br /><br />";
        if ($stmt = $mysqli->prepare("Select uname,address,password,profile from users where userid=".$_SESSION['userid'])){
            //  $stmt->bind_param("sssss",$email,$uname,$password,$address,$profile);
              $stmt->execute();
			        $stmt->bind_result($uname,$address,$password,$profile);

              if($stmt->fetch()){

    			
		          echo '<form action="profile.php" method="POST">';
          	  echo "Name:<input type=\"text\" name=\"uname\" pattern=\"[a-zA-Z ]*\" maxlength=\"32\" required=\"required\" value = \"$uname\"/><br />";
          	  echo "Password:<input type=\"password\" name=\"password\" pattern=\"[A-z0-9]*\" maxlength=\"32\" required=\"required\" /><br />";
          	  echo "Confirm:<input type=\"password\" name=\"confirm\" pattern=\"[A-z0-9]*\" maxlength=\"32\" required=\"required\" /><br />";
           	  echo "Address:<input type=\"text\" name=\"address\" pattern=\"[A-z0-9 -_]*\" maxlength=\"32\" required=\"required\" value = \"$address\"/><br />";
              echo "<input type='hidden' name='oldaddress' value='".$address."'>";
              echo 'Profile:<br>
              <textarea name="profile" required="required" />Hi!</textarea><br /><br />';
      
          	  echo '<input type="submit" value="Save the change" />';
          	  echo '</form>';
			     }

            $stmt->close();
   

            }
        	
    }
}
$mysqli->close();
include "footer.php";
?>
