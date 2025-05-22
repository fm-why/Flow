<?php
function formatNumber($num) {
    if ($num >= 1000000000) {
        return round($num / 1000000000, 1) . " mld"; // Miliardi
    } elseif ($num >= 1000000) {
        return round($num / 1000000, 1) . " mln"; // Milioni
    } elseif ($num >= 1000) {
        return round($num / 1000, 1) . "k"; // Migliaia
    } else {
        return (string)$num; // Nessuna abbreviazione
    }
}
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once "../../config_session.in.php";
    require_once "../../dbh.in.php";
    require_once "query.php";
    require_once "error.php";
    $id_post=$_POST["id"];
    $id_user=$_SESSION["user_id"];
    try{
        $ERRORS=[];
        if(!getPostById($pdo,$id_post)){
            $ERRORS["invalid_post"]="Non esiste post";
        }
        if($ERRORS){
            header("Location:../");
            die();
        }

        if(alreadyReposted($pdo,$id_post,$id_user)){
            removeRepost($pdo,$id_post,$id_user);
        }else addRepost($pdo,$id_post,$id_user);
        echo formatNumber(getNumberOfReposts($pdo,$id_post));
    }
    catch(PDOException $e){
        die("Error:".$e);
    }
}else die("Location:../");
?>