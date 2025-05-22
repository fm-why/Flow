<?php
function createPost($pdo,$user_id,$content,$img,$parent_id){
    $query="Insert into posts(user_id,content,img,parent_id) values(:user_id,:content,:img,:parent_id);";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("user_id",$user_id);
    $stmt->bindParam("content",$content);
    $stmt->bindParam("img",$img);
    $stmt->bindParam("parent_id",$parent_id);
    $stmt->execute();

}
function getPostById($pdo,$id){
    $query="Select * from posts where post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function saveImg(array $img){
    $img_ex= pathinfo($img["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $new_img_name = uniqid("IMG-", true).'.'.$img_ex;
    $upload_path = '../Img/'.$new_img_name;
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
function getLike($pdo,$id_post,$id_user){
    $query="select * from likes where post_id=:id_post and user_id=:id_user;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function addLike($pdo,$id_post,$id_user){
    $query="insert into likes(post_id,user_id) values(:id_post,:id_user);";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
}
function removeLike($pdo,$id_post,$id_user){
    $query="delete from likes where post_id=:id_post and user_id=:id_user;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
}
function getNumberOfLikes($pdo,$id){
    $query="select ifnull(count(like_id) , 0) as likes from likes where post_id=:id group by post_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return $row["likes"];
    else return "0";
}
function getRepost($pdo,$id_post,$id_user){
    $query="select * from reposts where post_id=:id_post and user_id=:id_user;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function addRepost($pdo,$id_post,$id_user){
    $query="insert into reposts(post_id,user_id) values(:id_post,:id_user);";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
}
function removerepost($pdo,$id_post,$id_user){
    $query="delete from reposts where post_id=:id_post and user_id=:id_user;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id_post",$id_post);
    $stmt->bindParam("id_user",$id_user);
    $stmt->execute();
}
function getNumberOfReposts($pdo,$id){
    $query="select ifnull(count(repost_id) , 0) as reposts from reposts where post_id=:id group by post_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row) return $row["reposts"];
    else return "0";
}
?>