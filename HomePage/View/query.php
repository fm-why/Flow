<?php

function getPostById($pdo,$id){
    $query="select * from posts where post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getRepliesById($pdo,$id){
    $query="select * from posts where parent_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
function getUsernameById($pdo,$id){
    $query="select username from users where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row["username"];
}
function getPfpById($pdo,$id){
    $query="select pfp from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row["pfp"];
}
function getNumberOfLikes($pdo,$id){
    $query="select ifnull(count(like_id) , 0) as likes from likes where post_id=:id group by post_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return $row["likes"];
    else return "0";
}
function getNumberOfReposts($pdo,$id){
    $query="select count(repost_id) as reposts from reposts where post_id=:id group by post_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return $row["reposts"];
    else return "0";
}
function getNumberOfComments($pdo,$id){
    $query="select count(parent_id) as comments from posts where parent_id=:id group by parent_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return $row["comments"];
    else return "0";
}
function weAreFollowing($pdo,$our_id,$other_id){
    $query="select * from Segue where id_user=:our_id and id_following=:other_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("our_id",$our_id);
    $stmt->bindParam("other_id",$other_id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return true;
    else return false;
}
function weLike($pdo,$id_post,$id_user){
    $query="select * from likes where user_id=:id_user and post_id=:id_post;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_user",$id_user);
    $stmt->bindParam("id_post",$id_post);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return true;
    else return false;
}
function weRepost($pdo,$id_post,$id_user){
    $query="select * from reposts where user_id=:id_user and post_id=:id_post;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_user",$id_user);
    $stmt->bindParam("id_post",$id_post);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return true;
    else return false;
}
?>