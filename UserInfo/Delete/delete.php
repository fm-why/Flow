<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once "../../dbh.in.php";
    require_once "../../config_session.in.php";
    require_once "query.php";
    try{
        $id=$_SESSION["user_id"];
        $pfp=getPfpById($pdo,$id);
        if($pfp)unlink("../pfp/".$pfp);
        deleteUser($pdo,$id);
        session_destroy();
        setcookie("username","",time()-3600,"/");
        setcookie("password","",time()-3600,"/");
        exit(header("Location:../../index.php"));
    }catch(PDOException $e){die("Error:".$e);}
}else die(header("Location:info.php"));
?>