<?php 
function getInformation($pdo,$id){
    $query= "Select * from user_info where id_user=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getIdByUsername($pdo, $username){
    $query="SELECT * FROM users WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("username", $username);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getInformationByUsername($pdo,$username){
    $user=getIdbyUsername($pdo,$username);
    if($user){
        $result=getInformation($pdo,$user["id_user"]);
        if($result){
            $result["data"]=$user["data"];
            return $result; 
        }else return;
    }else return;
}
function weFollow($pdo,$id,$username){
    $other=getIdbyUsername($pdo,$username)["id_user"];
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