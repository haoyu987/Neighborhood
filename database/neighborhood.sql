SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `neighborhood`
--
drop DATABASE IF EXISTS `neighborhood`;

CREATE DATABASE IF NOT EXISTS `neighborhood` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `neighborhood`;


--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(10) NOT NULL AUTO_INCREMENT,
  `uname` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `locationpoint` point,
  `address` varchar(32) NOT NULL,
  `profile` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` blob,
  `email` varchar(32) NOT NULL UNIQUE,
  `lastlogtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15;

--
-- Dumping data for table `customer`
--

INSERT INTO `users` (`userid`, `uname`, `password`,`locationpoint`, `address`, `profile`, `photo`, `email`, `lastlogtime`) VALUES
(1, 'Mary Lopez', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62188,-73.98357),'1660 64th st.', 'Hi! I\'m Mary Lopez!', NULL, 'lm123@gmail.com', '2015-10-13 22:45:34'),
(2, 'John Smith', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62162,-73.98992), '1662 64th st.', 'Hi! I\'m Mary\'s neighbor!', NULL, 'sj321@gmail.com', '2015-10-14 22:45:34'),
(3, 'Bob Jones', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62201,-74.00100), '1760 63rd st.', 'Hi! I\'m Bob Jones!',NULL, 'jb321@gmail.com', '2015-10-15 22:45:34'),
(4, 'Jake Weber', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62162,-74.00512), '1770 63rd st.', 'Hi! I\'m Jake Weber!',NULL, 'wj321@gmail.com', '2015-10-17 22:45:34'),
(5, 'Karen Lam', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.61666,-74.00881), '1772 63rd st.', 'Hi! I\'m Karen Lam!',NULL, 'kl321@gmail.com', '2015-10-19 22:45:34'),
(6, 'Miguel Chico', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.63210,-74.02829), '1850 59th st.', 'Hi! I\'m Miguel Chico!',NULL, 'cm321@gmail.com', '2015-10-23 22:45:34'),
(7, 'Alex Wadi', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62813,-74.03481), '5904 18th Av.', 'Hi! I\'m Alex Wadi!',NULL, 'wa321@gmail.com', '2015-11-10 22:45:34'),
(8, 'Hao Yu', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62041,-73.98966), '1770 63th st.', 'Hi! I\'m Hao!',NULL, 'hy987@gmail.com', '2015-11-14 22:45:34'),
(9, 'Robinson Crusoe', '4a7d1ed414474e4033ac29ccb8653d9b',point(40.62541,-73.98966), '1670 63th st.', 'Hi! I\'m Robinson!',NULL, 'cr321@gmail.com', '2015-11-14 23:45:34');

--
-- Table structure for table `hood`
--

CREATE TABLE IF NOT EXISTS `hood` (
  `hid` int(10) NOT NULL AUTO_INCREMENT,
  `hname` varchar(32) NOT NULL,
  `swpoint` point NOT NULL,
  `nepoint` point NOT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;

--
-- Dumping data for table `hood`
--

INSERT INTO `hood` (`hid`,`hname`,`swpoint`,`nepoint`) VALUES
(1,'Bensonhurst',point(40.61399,-74.01379),point(40.62494,-73.97696)),
(2,'Bay Ridge',point(40.61927,-74.04022),point(40.63543,-74.02022));

--
-- Table structure for table `block`
--

CREATE TABLE IF NOT EXISTS `block` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `hid` int(10) NOT NULL,
  `bname` varchar(50) NOT NULL,
  `swpoint` point NOT NULL,
  `nepoint` point NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;

--
-- Constraints for table `block`
--

ALTER TABLE `block`
ADD CONSTRAINT `block_ibfk_1` FOREIGN KEY (`hid`) REFERENCES `hood` (`hid`);

--
-- Dumping data for table `block`
--

INSERT INTO `block` (`bid`,`hid`,`bname`,`swpoint`,`nepoint`) VALUES
(1,1,'Bensonhurst East',point(40.62181,-73.99499),point(40.62494,-73.97696)),
(2,1,'Bensonhurst West',point(40.61399,-74.01379),point(40.62181,-73.97499)),
(3,2,'Bay Ridge',point(40.61927,-74.04022),point(40.63543,-74.02022));

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `userid` int(10) NOT NULL,
  `bid` int(10) NOT NULL,
  `status` ENUM('1','2','Y') NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `member`
--

ALTER TABLE `member`
ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `block` (`bid`);

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`userid`,`bid`,`status`) VALUES
(1,1,'Y'),
(2,1,'Y'),
(3,2,'Y'),
(4,2,'Y'),
(5,2,'Y'),
(6,3,'Y'),
(7,3,'Y'),
(8,2,'0'),
(9,1,'0');

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `userid1` int(10) NOT NULL,
  `userid2` int(10) NOT NULL,
  `status` ENUM('pending','Y') NOT NULL,
  PRIMARY KEY (`userid1`,`userid2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `friends`
--

ALTER TABLE `friends`
ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`userid1`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`userid2`) REFERENCES `users` (`userid`);

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`userid1`,`userid2`,`status`) VALUES
(1,2,'Y'),
(2,3,'Y'),
(1,3,'pending'),
(2,4,'Y'),
(3,4,'pending'),
(4,5,'Y'),
(6,7,'Y');

--
-- Table structure for table `neighbors`
--

CREATE TABLE IF NOT EXISTS `neighbors` (
  `userid1` int(10) NOT NULL,
  `userid2` int(10) NOT NULL,
  PRIMARY KEY (`userid1`,`userid2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `neighbors`
--

ALTER TABLE `neighbors`
ADD CONSTRAINT `neighbors_ibfk_1` FOREIGN KEY (`userid1`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `neighbors_ibfk_2` FOREIGN KEY (`userid2`) REFERENCES `users` (`userid`);

--
-- Dumping data for table `neighbors`
--

INSERT INTO `neighbors` (`userid1`,`userid2`) VALUES
(1,2),
(2,1),
(3,5),
(3,4),
(4,3),
(6,7);

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `requestid` int(10) NOT NULL AUTO_INCREMENT,
  `request_type` ENUM('friends','block') NOT NULL,
  `from_id` int(10) NOT NULL,
  `rec_id` int(10) NOT NULL,
  PRIMARY KEY (`requestid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10;

--
-- Constraints for table `requests`
--

ALTER TABLE `requests`
ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`rec_id`) REFERENCES `users` (`userid`);

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`requestid`,`request_type`,`from_id`,`rec_id`) VALUES
(1,'friends',1,3),
(2,'friends',4,3),
(3,'block',9,3);

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `messageid` int(10) NOT NULL AUTO_INCREMENT,
  `recipient_id` int(10) NOT NULL,
  `title` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `author` int(10) NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `readf` VARCHAR(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20;

--
-- Constraints for table `messages`
--

ALTER TABLE `messages`
ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`userid`);

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageid`,`recipient_id`,`title`,`author`,`text`,`time`,`readf`) VALUES
(1,3,'Hi I\'m Mary Lopez!',1,'Hello new here nice to meet you!','2015-10-23 22:46:34','Y'),
(2,1,'Re:Hi I\'m Mary Lopez!',2,'Nice to meet you! Where do you live?','2015-10-23 22:47:34','Y'),
(3,1,'Re:Hi I\'m Mary Lopez!',3,'Nice to meet you! I\'m Bob Jones','2015-10-23 22:47:34','N'),
(4,5,'Dinner Tommorrow',4,'Hi neighbor! I\'ll cook at home tommorrow. Do you want to come over?','2015-10-25 22:47:34','Y'),
(5,4,'Re:Dinner Tommorrow!',5,'Sure what time?','2015-10-25 22:48:34','Y'),
(6,5,'Re:Re:Dinner Tommorrow!',4,'about 6.','2015-10-25 22:49:34','Y'),
(7,4,'our new neighbor',3,'Have you met the guy recently moved in next door?','2015-10-26 21:49:34','Y'),
(8,3,'Re:our new neighbor',4,'Ya, I just have him over for dinner tonight.','2015-10-26 22:00:34','Y'),
(9,7,'Welcome!',6,'Welcome to the neighborhood!','2015-11-11 22:00:34','Y'),
(10,6,'Re:Welcome!',7,'Thank you!','2015-11-11 23:00:34','N'),
(11,4,'Thanksgiving',3,'Do you have any plan for thanksgiving?','2015-11-12 23:00:34','Y');

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `postid` int(10) NOT NULL AUTO_INCREMENT,
  `subject` ENUM('General','Free items','Lost&Found','Crime&Safety') NOT NULL,
  `recipient_type` ENUM('hood','block','neighbors','friends') NOT NULL,
  `recipient_id` int(10) DEFAULT NULL,
  `title` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `author` int(10) NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(32),
  `coordinate` point DEFAULT NULL,
  PRIMARY KEY (`postid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20;

--
-- Constraints for table `posts`
--

ALTER TABLE `posts`
ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`userid`);

-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postid`,`subject`,`recipient_type`,`recipient_id`,`title`,`author`,`text`,`time`,`location`,`coordinate`) VALUES
(1,'General','hood','1','Hi I\'m Mary Lopez!',1,'Hello world! This is the first post!','2015-10-23 22:46:34','1660 64th st.',point(40.62188,-73.98357)),
(2,'General','hood','1','Hi I\'m Mary Lopez\'s neighbor!',2,'And this is the second post!','2015-10-24 22:46:34','1662 64th st.',point(40.62162,-73.98992)),
(3,'Lost&Found','hood','1','lost white chihuahua',3,'Any one see a white chihuahua around 17 avenue? Contact me ASAP!','2015-10-25 22:46:34',NULL,NULL),
(4,'Crime&Safety','block','2','Robbery!',4,'Robbery at the cross of 17av and 63rd street last night! Be careful!','2015-10-26 12:46:34','17th Av. 63rd Street',point(40.62152,-73.97992)),
(5,'General','hood','1','Hi!I\'m new here!',5,'Just moved here! Don\'t know many in the area but always looking to meet chill folks and hear about great spots. See you around!','2015-10-27 22:46:34',NULL,NULL),
(6,'Free items','hood','1','Stoop sale',3,'Stoop sale this saturday.','2015-10-26 22:46:34','1762 63th st.',point(40.62062,-73.96992)),
(7,'General','neighbors',NULL,'Thanksgiving',6,'Anyone like to come to my house for thanksgiving dinner?','2015-11-03 22:46:34',NULL,NULL),
(8,'Lost&Found','block',2,'Garbage bag',5,'Who took my garbage bag?','2015-11-04 22:46:34',NULL,NULL),
(9,'Free items','friends',NULL,'old books',4,'old books giveaway.','2015-11-05 12:46:34',NULL,NULL);

--
-- Table structure for table `readpost`
--

CREATE TABLE IF NOT EXISTS `readpost` (
  `userid` int(10) NOT NULL,
  `postid` int(10) NOT NULL,
  PRIMARY KEY (`userid`,`postid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `readpost`
--

ALTER TABLE `readpost`
ADD CONSTRAINT `readpost_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `readpost_ibfk_2` FOREIGN KEY (`postid`) REFERENCES `posts` (`postid`);

--
-- Dumping data for table `readpost`
--

INSERT INTO `readpost` (`userid`,`postid`) VALUES
(1,1),
(1,2),
(1,3),
(1,5),
(2,1),
(2,2),
(2,3),
(2,5),
(3,1),
(3,3),
(3,4),
(3,5),
(4,3),
(4,4),
(4,5),
(5,5),
(6,6),
(7,6);

--
-- Table structure for table `reply`
--

CREATE TABLE IF NOT EXISTS `reply` (
  `postid` int(10) NOT NULL,
  `replier` int(10) NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`postid`,`replier`,`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `reply`
--

ALTER TABLE `reply`
ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`replier`) REFERENCES `users` (`userid`),
ADD CONSTRAINT `reply_ibfk_2` FOREIGN KEY (`postid`) REFERENCES `posts` (`postid`);

--
-- Dumping data for table `reply`
--

INSERT INTO `reply` (`postid`,`replier`,`text`,`time`) VALUES
(1,2,'YOU\'RE NO.1!','2015-10-23 22:50:34'),
(1,3,'I\'m replying to the first post ever!','2015-10-23 23:15:34'),
(2,1,'I know.','2015-10-24 23:15:34'),
(3,1,'He\'s so cute!','2015-10-25 23:15:34'),
(3,3,'right? I don\'t want to lose him!','2015-10-25 23:15:34'),
(3,2,'I see a stray dog last night around my house.','2015-10-26 00:15:34'),
(3,3,'Do you know where it is?','2015-10-26 09:15:34'),
(3,4,'Hope he\'ll come back safe.','2015-10-26 12:15:34'),
(3,3,'Thank you! He\'s back!','2015-10-26 19:15:34'),
(3,1,'Great!','2015-10-26 23:15:34'),
(4,3,'Are you all right?','2015-10-26 20:15:34'),
(4,4,'It\'s my friend who got robbed but he\'s ok except that his phone is gone...','2015-10-26 20:20:34'),
(4,3,'Take care.','2015-10-26 20:35:34'),
(5,3,'Welcome','2015-10-28 12:15:34'),
(5,4,'Welcome to our group!','2015-10-28 12:20:34'),
(5,1,'If you need any recommendations, don\'t hesitate to call me. I\'m the oldest user!','2015-10-28 14:20:34'),
(5,5,'Thank you all!','2015-10-28 22:15:34'),
(6,6,'There will be some old books and appliance.','2015-10-27 12:20:34'),
(6,7,'See you around.','2015-10-27 13:20:34'),
(8,4,'Saw it yesterday morning.','2015-11-04 23:20:34'),
(9,3,'wow','2015-11-05 23:20:34');



-- Trigger on friends
-- check userid1 < userid2

DELIMITER //
create trigger friends_check1 before insert on `friends` 
for each row
begin
   DECLARE userid INT;
   set userid = new.userid2;
   if new.userid1 > new.userid2 then
      set new.userid2 = new.userid1, new.userid1 = userid;
   end if;
end //
delimiter ;

-- Trigger on member
-- Member_check1 on update. When updating a member, a member approved the request, the status is set to the next state(From ‘’ to 1, from 1 to 2, from 2 to Y). If the number of existing members is less than 3, then check if all the member has approved the request, the status is set to Y. Upon the admission of a new member, insert a new tuple in posts on the block feeds automatically.

drop trigger if exists member_check1;
DELIMITER //
create trigger member_check1 before update on `member`
for each row
begin
  if (select count(*) 
from member
where bid = new.bid and status = 'Y' ) = new.status + 0 then
set new.status = 'Y';
  if new.status = 'Y' then
    insert into posts (subject,recipient_type,recipient_id,title,author,text) VALUES ('General', 'block',new.bid, 'New member in the block', new.userid,'Hi, I\'m a new member in the block.');
  end if;
end if;
end //
DELIMITER ;

-- Member_check2 on insert. When inserting a new tuple in the member table, sending a member request to all the existing members in the block.

use neighborhood;
DELIMITER //
create trigger member_check2 before insert on `member` 
for each row
begin
  DECLARE done INT DEFAULT FALSE;
  DECLARE ids INT;
DECLARE cur CURSOR FOR SELECT userid FROM member where bid = new.bid and status = 'Y';
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  if (select count(*) 
from member
where bid = new.bid) < 1 then
set new.status = 'Y';
  else 
    OPEN cur;
    ins_loop: LOOP
       FETCH cur INTO ids;
       IF done THEN
         LEAVE ins_loop;
       END IF;
       INSERT INTO requests (request_type,from_id,rec_id) VALUES ('block',new.userid,ids);
END LOOP;
CLOSE cur;
  end if;
  
end //
DELIMITER ;

-- Member_check3 on delete. When deleting a member (move), delete all the neighbor relation of the user while keep his friends.

DELIMITER //
create trigger member_check3 after delete on `member`
for each row
begin
  delete from neighbors where userid1 = old.userid or userid2 = old.userid;
end //
DELIMITER ;

-- Trigger on reply
-- When there's new reply, the corresponding post is unread to everyone except the replier

DELIMITER //
create trigger reply_check1 before insert on `reply` 
for each row
begin
   delete from readpost
   where postid = new.postid and userid <> new.replier;
end //
delimiter ;

-- Procedure for searching.
-- Search the keywords through all the accessible posts and output the posts containing the keywords. User can specify the field to search, such as subject, tile, text or all the above. 

drop procedure IF EXISTS postsSearcher;
DELIMITER //
CREATE PROCEDURE postsSearcher (IN uid int(10), searchType enum('subject','title','text','location','all'), keywords varchar(25))
BEGIN
case searchType

    when 'subject' then 
	BEGIN
	select postid,subject,title,uname,time,recipient_type
	from posts,users
	where `subject` like CONCAT('%',keywords,'%') and users.userid = author and ((recipient_type = 'block' and recipient_id = (select bid from member where userid = uid and status = 'Y'))
		or (recipient_type = 'hood' and recipient_id = (select hid from member natural join block where userid = uid and status = 'Y'))
		or (recipient_type = 'neighbors' and (uid in (select userid2 from neighbors where userid1 = author) or uid = author))
		or (recipient_type = 'friends' and (uid in (select userid1 from friends where userid2 = author and status = 'Y') or uid in (select userid2 from friends where userid1 = author and status = 'Y') or uid = author)));
	END;

	when 'title' then
	BEGIN
	select postid,subject,title,uname,time,recipient_type
	from posts,users
	where `title` like CONCAT('%',keywords,'%') and users.userid = author and ((recipient_type = 'block' and recipient_id = (select bid from member where userid = uid and status = 'Y'))
		or (recipient_type = 'hood' and recipient_id = (select hid from member natural join block where userid = uid and status = 'Y'))
		or (recipient_type = 'neighbors' and (uid in (select userid2 from neighbors where userid1 = author) or uid = author))
		or (recipient_type = 'friends' and (uid in (select userid1 from friends where userid2 = author and status = 'Y') or uid in (select userid2 from friends where userid1 = author and status = 'Y') or uid = author)));
	END;

	when 'text' then
	BEGIN
	select postid,subject,title,uname,time,recipient_type
	from posts,users
	where `text` like CONCAT('%',keywords,'%') and users.userid = author and ((recipient_type = 'block' and recipient_id = (select bid from member where userid = uid and status = 'Y'))
		or (recipient_type = 'hood' and recipient_id = (select hid from member natural join block where userid = uid and status = 'Y'))
		or (recipient_type = 'neighbors' and (uid in (select userid2 from neighbors where userid1 = author) or uid = author))
		or (recipient_type = 'friends' and (uid in (select userid1 from friends where userid2 = author and status = 'Y') or uid in (select userid2 from friends where userid1 = author and status = 'Y') or uid = author)));
	END;
    
    when 'location' then
	BEGIN
	select postid,subject,title,uname,time,recipient_type
	from posts,users
	where `location` like CONCAT('%',keywords,'%') and users.userid = author and ((recipient_type = 'block' and recipient_id = (select bid from member where userid = uid and status = 'Y'))
		or (recipient_type = 'hood' and recipient_id = (select hid from member natural join block where userid = uid and status = 'Y'))
		or (recipient_type = 'neighbors' and (uid in (select userid2 from neighbors where userid1 = author) or uid = author))
		or (recipient_type = 'friends' and (uid in (select userid1 from friends where userid2 = author and status = 'Y') or uid in (select userid2 from friends where userid1 = author and status = 'Y') or uid = author)));
	END;

	when 'all' then
	BEGIN
	select postid,subject,title,uname,time,recipient_type
	from posts,users
	where (`subject` like CONCAT('%',keywords,'%') or `title` like CONCAT('%',keywords,'%') or `text` like CONCAT('%',keywords,'%')) or `location` like CONCAT('%',keywords,'%') and users.userid = author and ((recipient_type = 'block' and recipient_id = (select bid from member where userid = uid and status = 'Y'))
		or (recipient_type = 'hood' and recipient_id = (select hid from member natural join block where userid = uid and status = 'Y'))
		or (recipient_type = 'neighbors' and (uid in (select userid2 from neighbors where userid1 = author) or uid = author))
		or (recipient_type = 'friends' and (uid in (select userid1 from friends where userid2 = author and status = 'Y') or uid in (select userid2 from friends where userid1 = author and status = 'Y') or uid = author)));
	END;
	END CASE;
END //
DELIMITER ;


-- Procedure for registering a new user. Check if the email is already registered.

use neighborhood;    
drop procedure IF EXISTS addUser;
DELIMITER // 
CREATE PROCEDURE addUser (IN inEmail varchar(32), inName varchar(32), inPassword varchar(32), inAddress varchar(32), inLat float, inLong float, inProfile text )
BEGIN
    declare userAdd bool;
    set @inLocation =POINT(inLat, inLong);
    if((select userid from users where email = inEmail) is NULL) then
        BEGIN
            insert into users (`uname`,`password`,`address`,`profile`,`email`,`locationpoint`) values(inName,inPassword,inAddress,inProfile,inEmail,@inLocation);
			select userid from users where email = inEmail;
        END;
    else
        BEGIN
            select FALSE;
        END;
    end if;

END // 
DELIMITER ;

-- Procedure for sending friend request. Insert a tuple into friends table and a request into the request table.

DELIMITER //
CREATE PROCEDURE addfriends (IN userid1 INT, IN userid2 INT)
BEGIN
insert into `friends` (`userid1`,`userid2`) VALUES (userid1,userid2);
insert into `requests` (`request_type`,`from_id`,`rec_id`) VALUES ('friends',userid1,userid2);
END //
DELIMITER ;

-- Procedure for adding neighbors. Insert a tuple into neighbors table and a message into messages table to inform the user that he has been added as a neighbor.

drop procedure IF EXISTS addneighbors;
DELIMITER //
CREATE PROCEDURE addneighbors (IN userid1 INT, IN userid2 INT)
BEGIN
insert into `neighbors` (`userid1`,`userid2`) VALUES (userid1,userid2);
insert into `messages` (`recipient_id`,`title`,`author`,`text`) VALUES (userid2, 'New Neighbor', userid1, 'I have added you as my neighbor.');
END //
DELIMITER ;
