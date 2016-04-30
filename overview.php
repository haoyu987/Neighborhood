<!DOCTYPE html>
<html>
<title>overview</title>

<?php
//This page displays the list of the forum's categories
include "include.php";
include "header.php";

if (!isset($_SESSION["userid"])){
    echo 'Sorry, you have to <a href="signin.php">sign in</a> to access to content.';
}
else{
    $sql = "select status,count(*) as cnt from member where userid = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $_SESSION['userid']);
        $stmt->execute();
        $stmt->bind_result($status,$cnt);
        $stmt->fetch();
    }
    $stmt->close();

    if ($status != 'Y') {
        if ($cnt == 0){
            echo "You are not a member of any block.";
            echo 'click <a href="block.php">here</a> to apply.';
        }
        else{
            echo "Your membership application is under process.";
        }
    }
    else{
        $groups =  array("friends", "neighbors", "hood", "block");

        //prepare the table
        echo '<table border="1">
              <tr>
              <th>Group</th>
              <th>New topics</th>
              </tr>';

        foreach ($groups as $group) {
            switch ($group) {
            case "friends":
                $sql = 'select count(*) as new
                        from posts, users
                        where recipient_type = "friends" and ('.$_SESSION['userid'].' in (select userid1 from friends where userid2 = author and status = "Y") or '.$_SESSION['userid'].' in (select userid2 from friends where userid1 = author and status = "Y") or author = ?) and users.userid = ? and time>lastlogtime';
                break;
            case "neighbors":
                $sql = 'select count(*) as new
                from posts, users
                where recipient_type = "neighbors" and ('.$_SESSION['userid'].' in (select userid2 from neighbors where userid1 = author) or author = ?) and users.userid = ? and time>lastlogtime';
                break;
            case "hood":
                $sql = 'select count(*) as new
                from posts, users
                where recipient_type = "hood" and recipient_id = (select block.hid from member natural join block join hood where block.hid = hood.hid and member.userid = ?) and users.userid = ? and time>lastlogtime';
                break;
            case "block":
                $sql = 'select count(*) as new
                from posts, users
                where recipient_type = "block" and recipient_id = (select bid from member where member.userid = ?) and users.userid = ? and time>lastlogtime';
                break;
            }
            
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ss', $_SESSION['userid'], $_SESSION['userid']);
                $stmt->execute();
                $stmt->bind_result($new);
                $stmt->fetch();
            }
            echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3><a href="post.php?group='.$group.'">'.$group.'</a></h3>';
            echo '</td>';
            echo '<td class="rightpart">';
            echo $new;
            echo '</td>';
            echo '</tr>';
            $stmt->close();
        }
        echo '</table>';
    }

}

$mysqli->close();
include 'footer.php';
?>
