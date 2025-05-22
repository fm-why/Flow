<?php
function isNameInvalid($name){
    if(strlen($name)>20) return true;
    else return false;
}
function isBioInvalid($bio){
    if(strlen($bio)>180) return true;
    else return false;
}
function isExtensionInvalid($file){
    $img_ex= pathinfo($file["name"], PATHINFO_EXTENSION);
    $img_ex = strtolower($img_ex);
    $allowed_exs=array("jpg","jpeg","png");
    if(!(in_array($img_ex,$allowed_exs))){
        return true;
    }else return false;
}
function is_username_invalid($username){
    $length=strlen($username);
    if($length>15 or $length<4 or preg_match('/[^a-zA-Z0-9._\-!]/',$username)){
        return true;
    }
    else return false;
}
function is_email_invalid($email){
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}
function is_password_invalid($pass){
    $length=strlen($pass);
    //controllo se ci sono caratteri unicode o spazi
    if($length<8 or preg_match('/[^a-zA-Z0-9._\-!]/', $pass)) return true;
    else return false;
}
function is_email_taken($pdo,$email,$id){
    require_once "query.php";
    if(getEmail($pdo,$email,$id)) return true;
    else return false;
}
function is_username_taken($pdo,$username,$id){
    require_once "query.php";
    if(getUsername($pdo,$username,$id)) return true;
    else return false;
}
?>