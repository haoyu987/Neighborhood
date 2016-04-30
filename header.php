<!DOCTYPE html PUBLIC >
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="A short description." />
    <meta name="keywords" content="put, keywords, here" />
    <title>Neighborhood</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<h1>Neighborhood</h1>
    <div id="wrapper">
    <div id="menu">
        <a class="item" href="/neighborhood/overview.php">Home</a> -
        <a class="item" href="/neighborhood/createpost.php">New Post</a> -
        <a class="item" href="/neighborhood/searchpost.php">Search Post</a> -
        <a class="item" href="/neighborhood/relations.php">Friends and Neighbors</a> -
        <a class="item" href="/neighborhood/block.php">Block</a>

        <div id="userbar">
            <?php
            if(isset($_SESSION["userid"]))
            {
                $newmsg = mysqli_fetch_array($mysqli->query("select count(*) as new from messages where recipient_id=".$_SESSION["userid"]." and readf = 'N'"));
                $req = mysqli_fetch_array($mysqli->query("select count(*) as req from requests where rec_id=".$_SESSION["userid"]));
                echo '<a class="item" href="profile.php">Profile</a> <a class="item" href="message.php">Messages('.($newmsg['new']+$req['req']).')</a>' . ' <a class="item" href="signout.php"> Sign out</a>';
            }
            else
            {
                echo '<a class="item" href="signin.php">Sign in</a> or <a class="item" href="register.php">create an account</a>.';
            }
            ?>
        </div>
    </div>
    <div id="content">
