<?php
function getInfoBId($pdo,$id){
    $query="SELECT * FROM user_info WHERE id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

?>