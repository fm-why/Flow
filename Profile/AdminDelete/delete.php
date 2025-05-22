<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once "../../config_session.in.php";
    require_once "../../dbh.in.php";
    require_once "query.php";
    if(!isset($_SESSION["isAdmin"])) die(header("Location:../"));
    try{
        $id=$_POST["id"];
        $result=getUser($pdo,$id);

        if($result===false){
            $_SESSION["admin_error"]="User non trovato";
            die(header("Location:".$_SERVER["HTTP_REFERER"]));
        }
        if($result["isAdmin"]===1){
            $_SESSION["admin_error"]="Non è possibile cancellare un altro amministratore";
            die(header("Location:".$_SERVER["HTTP_REFERER"]));
        }
        deleteUser($pdo,$id);
        $_SESSION["post_error"]["user_deleted"]="Account eliminato con successo";
        exit(header("Location:../../HomePage/"));
    }catch(PDOException $e){die("ERRROR:".$e);}
}else die(header("Location:../"));
?>