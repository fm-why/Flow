<?php
//funzioni di controllo dell'username e della password
function is_username_invalid($username){
    $length=strlen($username);
    if($length>25 or $length<4 or preg_match('/[^a-zA-Z0-9._\-!]/',$username)){
        return true;
    }
    else return false;
}
function username_not_exist($result){
    if(!$result) return true;
    else return false;
}

function is_password_inccorrect($result,$pass){
    $hash=$result["pass"];
    if(!password_verify($pass,$hash)) return true;
    else return false;
}