<?php
function isExtensionInvalid($file){
    $img_ex= pathinfo($file["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $allowed_exs=array("jpg","jpeg","png");
    if(!(in_array($img_ex,$allowed_exs))){
        return true;
    }else return false;
}
function is_respondeId_invalid($pdo,$id){
    $result=getPostById($pdo,$id);
    if($result) return false;
    else return true;
}
function alreadyLiking($pdo,$id_post,$id_user){
    if(getLike($pdo,$id_post,$id_user)) return true;
    else return false;
}
function alreadyReposted($pdo,$id_post,$id_user){
    if(getRepost($pdo,$id_post,$id_user)) return true;
    else return false;
}
?>