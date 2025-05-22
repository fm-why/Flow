<?php
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
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $username=$_POST["username"];
    require_once "../../dbh.in.php";
    require_once "../query.php";
    try{
        if(is_username_invalid($username)){
            echo "<p class='form-error'>Credenziali non valide</p>";
        }
        else{
           echo "";
        }

    }catch(PDOException $e){
        die("Error:".$e);
    }

}else{
    header("Location:../../index.php");
    die();
}
?>