# Neighborhood
link:52.39.170.67/neighborhood/overview.php

You can use sj321@gmail.com and password 0000 for test.

A Neighborhood Community Website

Users are able to register for the service and specify where they live. In the website, there are two levels of locality, hoods (neighborhoods, such as Bay Ridge or Park Slope) and blocks (a part of a neighborhood but not necessarily one block, e.g., “7th Avenue between 3rd and 6th Street” in Park Slope). Users can apply to join a block; they are accepted as a new member if at least three existing members (or all members if there are less than three) approve. A user can only be member of one block, and is also automatically a member of the neighborhood in which the block is located. For simplicity, we assume that the names and definitions of blocks and neighborhoods are predefined by the company, so that users cannot create new ones; also, blocks and neighborhoods are modeled as (possibly overlapping) axis-aligned rectangles that can be defined by two corner points (say, their southwest and northeast corner).

Members can specify two types of relationships with other members. They can friend other members in the same hood, and they can specify (direct) neighbors, i.e., members living next door or in the same building or very close by. Friendship is symmetric and requires both sides to accept, while neighbors can be chosen unilaterally. Also, people should be able to post, read, and reply to messages. To start a new topic, a user chooses a subject, and also chooses who can read the message and reply to it. A user can direct a message to a particular person who is a friend or a neighbor, or all of their friends, or to the entire block or the entire hood they are a member of. When others reply to a message, their reply can be read and replied to by anyone who received the earlier message. Thus, messages are organized into threads, where each thread is started by an initial message and is visible by the group of people specified in the initial message. A message consists of a title and a set of recipients (specified in the inital message), an author, a timestamp, a text body, and optionally the coordinates of a location the message refers to; thus, a message about a stoop sale or a traffic accident can potentially be placed on a map in the second part of the project.

## Database Schema
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/schema.jpg)

## Functionality
### general user case
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/generalusercase.jpg)
A user uses his email and password to log in. After that, the user is directed to a page presenting group category of the posts. A user can start a new post after choose a subject from a predefined set of subjects and specify a title and add content in text. Also user can review a post and reply. A user can do some operations on the posts, like search the keywords. Messages are kept in a mailbox. Every time a user logs out, the lastlogtime is updated in the users table.

### hood and block
hood
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/hood.jpg)

blcok
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/block.jpg)

In our schema, the blocks and hoods are predefined and assigned to a unique id. Every block belongs to a hood. Blocks and hoods are rectangles defined by their southwest points and northeast points.

### message,post,reply
message
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/message.jpg)

Messages is categorized into request, unread messages, read messages.
posts
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/post.jpg)

There are new posts, old posts when the creation time is concerned. There are read posts and unread posts when the action of the user is concerned.
The messages in our system are categorized into 3 types. A post is posted to a group people (hood, block, friends, or neighbors). A reply is a reply to the post, which can be seen by all the people specified by the original post. A message is private message between two users while a sender and unlike a post reply, a reply to a message is also a message. So the post is assigned a postid while reply only have the original postid and a time so as to be displayed in order of time. When a user click on a message or a post, the message or the post is considered as read (orange colored title). If a new reply is added to the post, the post is considered as unread to everyone except the replier. And a post created after the user’s last logout time is considered as a new post.

### member
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/member.jpg)

When a user registers in our system, the name, address, password, and a unique email are required. And a unique user id will be assigned to the user automatically. The user is not a member of any block at first. And according to the address the user provide, we’ll recommend a block for the user. Then after the user apply for the membership of the block, requests will be sent to all the current members in the block. And a tuple with the user id and block id is inserted into member table but with status set to ``. A member then choose yes or no to indicate whether to accept the user as a member. When there are 3 approval or all the members in a block in the case that there are less than 3 members in the block, the status of the corresponding tuple in the member table will be updated to ‘Y’, which suggests the membership is established. Upon the admission, we will send a post on the block feeds whose author is set as the new member. This post functions as a notification of a new member.

### relationship
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/relationship.jpg)

When a user registers in our system, the name, address, password, and a unique email are required. And a unique user id will be assigned to the user automatically. The user is not a member of any block at first. And according to the address the user provide, we’ll recommend a block for the user. Then after the user apply for the membership of the block, requests will be sent to all the current members in the block. And a tuple with the user id and block id is inserted into member table but with status set to ``. A member then choose yes or no to indicate whether to accept the user as a member. When there are 3 approval or all the members in a block in the case that there are less than 3 members in the block, the status of the corresponding tuple in the member table will be updated to ‘Y’, which suggests the membership is established. Upon the admission, we will send a post on the block feeds whose author is set as the new member. This post functions as a notification of a new member.

### moving
![alt text](https://github.com/haoyu987/Neighborhood/blob/master/img/moving.jpg)

When user changes his address in his profile, the system will check if the user are still in the same block. If user is still in the same block, everything remains unchanged. If the user moves to another block, the messages are kept while the previous posts cannot be accessed any more. Also the user lost all his neighbor relations while keeping the friend relations. So the user can still see the previous friend feeds. But not until he gets the admission to the new block. Because we only give content access to the user who has a block membership. But the user can still send and receive messages.

## Additional
For other advanced feature and implementation details. See the [report](https://github.com/haoyu987/Neighborhood/blob/master/doc/project.pdf) in doc.
