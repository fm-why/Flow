<?php
function deletePost($pdo,$id){
    $query="DELETE FROM posts WHERE post_id=:id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("id",$id);
    $stmt->execute();
}
?>