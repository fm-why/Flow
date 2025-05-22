<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $id=$_POST["id"];
    require_once "../../dbh.in.php";
    require_once "query.php";
    require_once "../../config_session.in.php";
    try{
        $user_id=getUserIdFromPost($pdo,$id)["user_id"];
        if($user_id===$_SESSION["user_id"]){
            deletePost($pdo,$id);
            exit(header("Location:../index.php"));
        } else die(header("Location:../index.php"));
    }catch(PDOException $e){die("Error:".$e);}
}else die(header("Location:../index.php"));
?>