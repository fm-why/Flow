<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once "../../config_session.in.php";
    require_once "query.php";
    require_once "../../dbh.in.php";
    if(!isset($_SESSION["isAdmin"])) die(header("Location:../"));
    $id=$_POST["id"];
    try{
        deletePost($pdo,$id);
        $_SESSION["post_error"]["success"]="Post cancellato correttamente";
        exit(header("Location:".$_SERVER["HTTP_REFERER"]));
    }catch(PDOException $e){die("ERROR:".$e);}

}else die(header("Location:../"));
?>