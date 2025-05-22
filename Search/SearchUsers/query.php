<?php
function getUsers($pdo,$username){
    $username=$username."%";
    $query="SELECT * FROM users INNER JOIN user_info ON users.id_user = user_info.id_user WHERE username LIKE :username;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("username",$username);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function weFollow($pdo,$id,$other){
    $query = "SELECT * FROM segue WHERE id_following=:other and id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("other",$other);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function countFollowing($pdo,$id){
    $query="SELECT count(id_following) as following from segue where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function countFollower($pdo,$id){
    $query="SELECT count(id_following) as follower from segue where id_following=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
?>