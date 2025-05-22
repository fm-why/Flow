<?php
function getUser($pdo,$id){
    $query="SELECT * FROM users WHERE id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function deleteUser($pdo,$id){
    $query="DELETE FROM users WHERE id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
?>