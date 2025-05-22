<?php
function isUsernameInvalid($pdo,$username){
    if(getUsername($pdo,$username)) return false;
    else return true;
}
function alreadyFollowing($pdo,$id,$username){
    $following=getIdByUsername($pdo,$username);
    if(getFollow($pdo,$id,$following)) return true;
    else return false;
}
function addFollow($pdo,$id,$username){
    $following=getIdByUsername($pdo,$username);
    add($pdo,$id,$following);
}
function removeFollow($pdo,$id,$username){
    $following=getIdByUsername($pdo,$username);
    remove($pdo,$id,$following);
}
?>