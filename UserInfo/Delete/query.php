<?php
function getPfpById($pdo,$id){
    $query="Select * from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    return $result["pfp"];
}
function deleteUser($pdo,$id){
    $query="delete from users where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
?>