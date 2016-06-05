<!DOCTYPE html>
<html>
<title>register</title>

<?php
include "include.php";
include "header.php";

echo '<h2>Create your account</h2><br />';
//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["userid"])) {
  echo "You are already logged in. <br />";
  echo "You will be redirected in 3 seconds or click <a href=\"overview.php\">here</a>.<br />";
  header("refresh: 3; overview.php");
}
else {
	
    if(isset($_POST["email"]) && ($_POST["password"]==$_POST["confirm"])){
		$email = $_POST["email"];
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
            echo '<br /><a href="register.php">Try again.</a>';
        }
		else
        {
            $lat = $response_a->results[0]->geometry->location->lat;
            $long = $response_a->results[0]->geometry->location->lng;

            if ($stmt = $mysqli->prepare("call addUser('$email','$uname','$password','$address','$lat','$long','$profile')")){

              $stmt->execute();
    		  $stmt->bind_result($registSuccess);
    
              if($stmt->fetch()){
                $stmt->close();
               
    		  	if($registSuccess!=0){
    				echo "Register Succeeed!<br></br>";
                    $_SESSION["userid"] = $registSuccess;
                    $_SESSION["uname"] = $uname;
    				echo 'You have one more step to go. Apply for your block membership <a href="block.php">here</a>.';
    			}
    			else{
    				echo "This Email is alread registered!";
    				echo '<br /><a href="register.php">Try again.</a>';
    			}
    
              }
            
            }
    		
    		
		}
    
    }
    else{
    	if(isset($_POST["email"]))
    		echo "Please retype your passwords:<br /><br />";

    	else 
    		echo "Please enter your inforamation below: <br /><br />";

    	echo '<form action="register.php" method="POST">';
    	echo 'Email:<input type="text" name="email" pattern="[A-z0-9_]*[@][A-z0-9_]*[.][A-z0-9_]*" maxlength="32" required="required"/><br />';
    	echo 'Password:<input type="password" name="password" pattern="[A-z0-9]*" maxlength="32" required="required" /><br />';
    	echo 'Confirm:<input type="password" name="confirm" pattern="[A-z0-9]*" maxlength="32" required="required" /><br />';
    	echo 'Name <input type="text" name="uname" pattern="[a-zA-z ]*" maxlength="32" required="required"/><br />';
    	echo 'Address:<input type="text" name="address" pattern="[A-z0-9 -_]*" maxlength="32" required="required" /><br />';
    	echo 'Profile:<br>
              <textarea name="profile" required="required" />Hi!Say something.</textarea><br /><br />';

    	echo '<input type="submit" value="Submit" />';
    	echo '</form>';

    	
    }
}
$mysqli->close();
include "footer.php";
?>
