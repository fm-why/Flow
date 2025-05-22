<?php 
function getInformation($pdo,$id){
    $query= "Select * from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getPosts($pdo){
    $query="select * from posts where parent_id is null order by created_at desc;";
    $stmt=$pdo->prepare($query);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
function getFollowPosts($pdo,$id){
    $query="SELECT p.* FROM POSTS as p INNER JOIN users AS u on p.user_id=u.id_user INNER JOIN segue AS s on u.id_user=s.id_following WHERE s.id_user=:id order by created_at desc;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
?>