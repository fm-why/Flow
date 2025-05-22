<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $content=$_POST["content"];
    $id=$_POST["id"];
    $file=$_FILES["file"];
    require_once "../../../dbh.in.php";
    require_once "../../../config_session.in.php";
    require_once "query.php";
    try{
        $post=getPostById($pdo,$id);
        $ERRORS=[];
        if($post["user_id"]!==$_SESSION["user_id"]) die(header("Location:../../"));
        if(empty($content) and $file["name"]===""){
            $ERRORS["empty"]="Inserire i campi da modificare";
        }
        if(strlen($content)>255 or empty($content)){
            $ERRORS["invalid_content"]="Il contenuto non deve essere vuoto e non deve superare i 255 caratteri";
        }
        if($file["name"]!=""){
            if($file["error"] !== 0){
                $ERRORS["img_error"] = "C'è stato un errore caricando l'immagine";
            }
            if($file["size"]>5000000){
                $ERRORS["img_size"] = "Il file deve essere più piccolo di 5 MB";
            }
            if(isExtensionInvalid($file)){
                $ERRORS["img_extension"] = "Il file non è un immagine";
            }
        }

        if($ERRORS){
            $_SESSION["update"]=$ERRORS;
            die(header("Location:../modify.php?id=".$id));
        }
        if($file["name"]!==""){
            if(!empty($post["img"]))unlink(filename: "../../../HomePage/Img/".$post["img"]);
            $file=saveImg($file);
        }else{
            if(!empty($post["img"]))unlink(filename: "../../../HomePage/Img/".$post["img"]);
            $file=null;
        }
        updatePost($pdo,$id,$content,$file);
        print_r($post);
        exit(header("Location:../../"));
    }catch(PDOException $e){die("Error:".$e);}
}else die(header("Location:../../"));
?>