<?php
function getPostById($pdo,$id){
    $query="SELECT * FROM posts where post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function isExtensionInvalid($file){
    $img_ex= pathinfo($file["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $allowed_exs=array("jpg","jpeg","png");
    if(!(in_array($img_ex,$allowed_exs))){
        return true;
    }else return false;
}
function saveImg($img){
    $img_ex= pathinfo($img["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $new_img_name = uniqid("IMG-", true).'.'.$img_ex;
    $upload_path = '../../../HomePage/Img/'.$new_img_name;
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
function updatePost($pdo,$id,$content,$img){
    $query="UPDATE posts SET content=:content,img=:img WHERE post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("content",$content);
    $stmt->bindParam("img",$img);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
?>