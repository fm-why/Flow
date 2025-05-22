<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $username=$_POST["follower"];
    require_once "../dbh.in.php";
    require_once "error.php";
    require_once "query.php";
    require_once "../config_session.in.php";
    try{
        $ERRORS=[];
        if(isUsernameInvalid($pdo,$username)){
            $ERRORS["invalid-username"]="Username non valido";
            echo "username non valido";
        }

        if($ERRORS){
            die();
        }
        if(alreadyFollowing($pdo,$_SESSION["user_id"],$username)){
            removeFollow($pdo,$_SESSION["user_id"],$username);
            echo "fa-user-plus";
        }else{
            addFollow($pdo,$_SESSION["user_id"],$username);
            echo "fa-user-times";
        }
    }catch(PDOException $e){echo("Error:".$e);}
}else die(header("Location:index.php"));
?>