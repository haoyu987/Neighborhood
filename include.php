<?php

$mysqli = new mysqli("127.0.0.1", "root", "0000", "neighborhood");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect database failed: %s\n", mysqli_connect_error());
    exit();
}

if(!isset($_SESSION)) { 
    session_start(); 
}
if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {
session_destroy();
session_start();
}

?>
