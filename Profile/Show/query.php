<?php
function getUserById($pdo,$id){
    $query="SELECT * FROM users WHERE id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getPosts($pdo,$id){
    $query="SELECT * FROM posts where user_id=:id order by created_at desc;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
function getParentUsername($pdo,$id){
    $query="select u.username from posts as p join users as u on p.user_id=u.id_user where p.post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row["username"];
}
function getPostById($pdo,$id){
    $query="SELECT * FROM posts where post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getPfpById($pdo,$id){
    $query="select pfp from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row["pfp"];
}

function getReposts($pdo,$id){
    $query="SELECT * FROM reposts where user_id=:id order by created_at desc;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
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