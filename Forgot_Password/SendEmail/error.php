<?php
function is_email_invalid($email){
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}
function email_not_exist($pdo,$email){
    if(getEmail($pdo,$email)) return false;
    else return true;
}
function is_reset_alredy_available($pdo,$email){
    if(getTempFromEmail($pdo,$email)) return true;
    else return false;
}
function is_password_invalid($pass){
    $length=strlen($pass);
    //controllo se ci sono caratteri unicode o spazi
    if($length<8 or preg_match('/[^a-zA-Z0-9._\-!]/', $pass)) return true;
    else return false;
}
?>