<?php
function getInformation($pdo,$id){
    $query= "Select * from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getPostById($pdo,$id){
    $query="SELECT * FROM posts where post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
?>