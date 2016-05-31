<?php
include "include.php";
function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

/*
// Opens a connection to a MySQL server
$connection=mysql_connect ("127.0.0.1", "root", "root");
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db("neighborhood", $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}
*/
// Select all the rows in the markers table
$query = "select userid, uname, address, x(locationpoint) as lat, y(locationpoint) as lng, profile from users where userid< 8 and userid >4";
$singlePoint = $_SESSION["show"];
if( $singlePoint==0 )
	$query = $_SESSION["query"];
$result = $mysqli->query($query);
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}
//echo $query;
header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

$sumLat = 0;
$sumLng = 0;
$times = 0;
// Iterate through the rows, printing XML nodes for each
while ($row = $result->fetch_assoc()){
  $sumLat = $sumLat + $row['lat'];
  $sumLng = $sumLng + $row['lng'];
  $times = $times+1;
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['uname']) . '" ';
  echo 'userid="' . parseToXML($row['userid']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'profile="' . $row['profile'] . '" ';
  echo '/>';
}

$_SESSION["pAverLat"] = $averLat = $sumLat/$times;
 $_SESSION["pAverLong"] = $averLng = $sumLng/$times;
// echo "$averLat $averLng";
// echo $query;

echo '</markers>';

?>
