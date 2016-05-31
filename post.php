<!DOCTYPE html>
<html>
<title>postlist</title>

<?php
//This page displays the list of the user's message
include "include.php";
include "header.php";

if (!isset($_SESSION["userid"])){
    echo 'Sorry, you have to <a href="signin.php">sign in</a> to access to content.';
}
elseif (!$_GET['group']){
    echo 'No group is specified';
}
else {
    $group = $_GET['group'];

    switch ($group) {
        case "friends":
            $sql = 'select posts.postid,subject,title,uname,time,email,sum(readpost.userid=?) as readflag
                    from posts left join readpost on posts.postid = readpost.postid, users
                    where recipient_type = "friends" and (? in (select userid1 from friends where userid2 = author and status = "Y") or '.$_SESSION['userid'].' in (select userid2 from friends where userid1 = author and status = "Y") or author = '.$_SESSION['userid'].') and users.userid = author
                    group by posts.postid desc';
            break;
        case "neighbors":
            $sql = 'select posts.postid,subject,title,uname,time,email,sum(readpost.userid=?) as readflag
            from posts left join readpost on posts.postid = readpost.postid, users
            where recipient_type = "neighbors" and (? in (select userid2 from neighbors where userid1 = author) or author = '.$_SESSION['userid'].') and users.userid = author
            group by posts.postid desc';
            break;
        case "hood":
            $sql = 'select posts.postid,subject,title,uname,time,email,sum(readpost.userid=?) as readflag
            from posts left join readpost on posts.postid = readpost.postid, users
            where recipient_type = "hood" and recipient_id = (select hid from member natural join block where member.userid = ? and status = "Y") and users.userid = author
            group by posts.postid desc';
            break;
        case "block":
            $sql = 'select posts.postid,subject,title,uname,time,email,sum(readpost.userid=?) as readflag
            from posts left join readpost on posts.postid = readpost.postid, users
            where recipient_type = "block" and recipient_id = (select bid from member where userid = ? and status = "Y") and users.userid = author
            group by posts.postid desc';
            break;
        default:
            echo "No such group!";
    }

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('ss', $_SESSION['userid'],$_SESSION['userid']);
        $stmt->execute();
        $stmt->bind_result($pid, $subject, $title,$author,$time,$email,$flag);
        }

    echo '<table border="1">
          <tr>
          <th>Posts</th>
          <th>From</th>
          </tr>';

    if (!$stmt->fetch()) {
        echo '<tr><td class="leftpart">No post in '.$group.'.</td></tr>';
        }
    else {
        echo '<tr>';
        echo '<td class="leftpart">';
        if($flag){
            echo '<h3> <a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a><font color="#FF9933">'.'['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</font>';
        }
        else{
            echo '<h3><a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a>'.'['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8');
        }
        if(strtotime($time)>$_SESSION['time']) {
            echo '<font color="red"> [NEW]</font>';
        }
        echo '</h3>';
        echo '</td>';
        echo '<td class="rightpart">';
        echo $author.'('.$email.')';
        echo '<br> created at';
        echo $time;
        echo '</td>';
        echo '</tr>';
        while ($stmt->fetch()) {
            echo '<tr>';
            echo '<td class="leftpart">';
            if($flag){
                echo '<h3> <a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a><font color="#FF9933">'.'['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8').'</font>';
            }
            else{
                echo '<h3><a href="readpost.php?group='.$group.'&id='.$pid.'"'.'</a>'.'['.htmlentities($subject, ENT_QUOTES, 'UTF-8').']'. htmlentities($title, ENT_QUOTES, 'UTF-8');
            }
            if(strtotime($time)>$_SESSION['time']) {
                echo '<font color="red"> [NEW]</font>';
            }
            echo '</h3>';
            echo '</td>';
            echo '<td class="rightpart">';
            echo $author.'('.$email.')';
            echo '<br> created at';
            echo $time;
            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';
    $stmt->close();
}

$mysqli->close();

include 'footer.php';
?>
