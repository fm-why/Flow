<?php
function insertInfo($pdo,$name,$bio,$file,$user_id){
    $query="INSERT INTO user_info(id_user,nome,bio,pfp) VALUES(:id,:name,:bio,:file);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("id",$user_id);
    $stmt->bindParam("name",$name);
    $stmt->bindParam("bio",$bio);
    $stmt->bindParam("file",$file);
    $stmt->execute();
}
function saveImg(array $img){
    print_r($img);
    $img_ex= pathinfo($img["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $new_img_name = uniqid("IMG-", true).'.'.$img_ex;
    $upload_path = '../pfp/'.$new_img_name;
    move_uploaded_file($img["tmp_name"],$upload_path);
    if($img_ex==="jpeg" || $img_ex==="jpg"){
        $img=imagecreatefromjpeg($upload_path);
        $new_img=imagescale($img,200,200);
        imagejpeg($new_img,$upload_path);
    }else{
        $img=imagecreatefrompng($upload_path);
        $new_img=imagescale($img,200,200);
        imagepng($new_img,$upload_path);
    }
    return $new_img_name;
}
function getUserInformation($pdo,$id){
    $query= "SELECT * FROM user_info WHERE id_user=:id";
    $stmt= $pdo->prepare($query);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function UpdateInfo($pdo,$name,$bio,$file,$user_id){
    $query = "UPDATE user_info SET nome=:name,bio=:bio,pfp=:file WHERE id_user=:id;";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam("name",$name);
    $stmt -> bindParam("bio",$bio);
    $stmt -> bindParam("file",$file);
    $stmt -> bindParam("id",$user_id);
    $stmt->execute();
}
function getUser($pdo,$id){
    $query= "SELECT * FROM users WHERE id_user=:id";
    $stmt= $pdo->prepare($query);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function UpdateUser($pdo,$id,$username,$email,$pass){
    $query = "UPDATE users SET username=:username,email=:email,pass=:pass WHERE id_user=:id;";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam("username",$username);
    $stmt -> bindParam("email",$email);
    $stmt -> bindParam("pass",$pass);
    $stmt -> bindParam("id",$id);
    $stmt->execute();
}
function getUsername($pdo,$username,$id){
    $query="SELECT * FROM users WHERE username = :username AND NOT id_user=:id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("username", $username);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getEmail($pdo,$email,$id){
    $query="SELECT email FROM users WHERE email = :email AND NOT id_user=:id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt -> bindParam("id",$id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
?>