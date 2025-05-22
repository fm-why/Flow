<?php
function getUsername($pdo,$username){
    $query="SELECT * FROM users WHERE username=:username;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("username",$username);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getIdByUsername($pdo,$username){
    $result=getUsername($pdo,$username);
    return $result["id_user"];
}
function getFollow($pdo,$id,$following){
    $query="SELECT * from segue WHERE id_following=:following and id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("following",$following);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function add($pdo,$id,$following){
    $query="INSERT INTO segue (id_following,id_user) VALUES(:following, :id);";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("following",$following);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
function remove($pdo,$id,$following){
    $query="DELETE FROM segue WHERE id_following=:following and id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("following",$following);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
?>