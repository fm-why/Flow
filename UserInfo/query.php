<?php
function getUserInformation($pdo,$id){
    $query= "SELECT * FROM user_info WHERE id_user=:id";
    $stmt= $pdo->prepare($query);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt=null;
    $pdo=null;
    return $row;
}
function getUser($pdo,$id){
    $query= "SELECT * FROM users WHERE id_user=:id";
    $stmt= $pdo->prepare($query);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt=null;
    $pdo=null;
    return $row;
}
?>