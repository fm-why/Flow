<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $content = $_POST["content"];
    $file=$_FILES["file"];
    $parent_id=isset($_POST["parent_id"]) ? $_POST["parent_id"] : null;
    //chiamata alle funzioni
    require_once "error.php";
    require_once "../../dbh.in.php";
    require_once "../../config_session.in.php";
    require_once "query.php";
    try{
        //error handling
        $ERRORS=[];
        if(empty($content) && $file["name"]==""){
            $ERRORS["empty-data"]="Inserire il contenuto da postare";
        }else{
            if(!empty($content) && strlen($content)>255){
                $ERRORS["invalid-content"]="Il post non deve superari i 255 caratteri";
            }
            if(isset($parent_id) && is_respondeId_invalid($pdo,$parent_id)){
                $ERRORS["invalid-id"]="Stai cercando di rispondere a un post che non esiste";
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
            } else $file=null;
        }
        //invio errori e dati
        if($ERRORS){
            $_SESSION["post_error"]=$ERRORS;
            $postData= [
                "content"=>$content
            ];
            $_SESSION["post_data"]=$postData;
            if(isset($parent_id)) die(header("Location:../View/viewPost.php?id=".$parent_id));
            header("Location:../");
            die();
        }
        if(isset($file))$file=saveImg($file);
        createPost($pdo,$_SESSION["user_id"],$content,$file,$parent_id);
        $pdo=null;
        $stmt=null;
        if(isset($parent_id)) header("Location:../View/viewPost.php?id=".$parent_id);
        else header("Location:../");
        exit();
    }catch(PDOException $e){
        die("Error:".$e);
    }
}else die(header("Location../"));
?>