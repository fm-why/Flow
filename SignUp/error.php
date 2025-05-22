<?php
function is_username_invalid($username){
    $length=strlen($username);
    if($length>25 or $length<4 or preg_match('/[^a-zA-Z0-9._\-!]/',$username)){
        return true;
    }
    else return false;
}
function username_already_used($pdo,$username){
    require_once "query.php";
    if(getUsername($pdo,$username)) return true;
    else return false;
}
function is_email_invalid($email){
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}
function email_already_used($pdo,$email){
    require_once "query.php";
    if(getEmail($pdo,$email)) return true;
    else return false;
}
function is_password_invalid($pass){
    $length=strlen($pass);
    if($length<8 or preg_match('/[^a-zA-Z0-9._\-!]/', $pass)) return true;
    else return false;
}
?>