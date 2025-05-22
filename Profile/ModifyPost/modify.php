<?php
require_once "../../config_session.in.php";
require_once "../../dbh.in.php";
require_once "query.php";
if(!isset($_SESSION["user_id"])) die(header("Location:../"));
try{
    $result=getInformation($pdo,$_SESSION["user_id"]);
    if(isset($_SESSION["first-time"]) or !$result){
        $_SESSION["first-time"]=true;
        die(header("Location:../../UserInfo/info.php"));
    }
    $post=getPostById($pdo,$_GET["id"]);
    if(empty($post)) die(header("Location:../../"));
    if($post["user_id"]!==$_SESSION["user_id"]) die(header("Location:../../"));
    $pfp=isset($result["pfp"])?$result["pfp"]:"default.png";
    $username=$_SESSION["user_username"];
    $pic=$post["img"];
    $content=$post["content"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body style="display:flex;justify-content: center;">
<div class="post">
    <div style="display: flex;flex-direction:row;border-bottom:solid 2px #722F37;justify-content:center;"><i style="cursor:pointer;position:absolute;left:1%;top:2%;color:#722F37;border-style:solid;border-radius:50%" onclick="leave()" class="fa fa-arrow-left"></i><h1>Modifica Post</h1></div>
        <form action="Update/update.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
            <img id="img" src=<?php echo '"../../UserInfo/pfp/'.$pfp.'"'; ?> alt="pfp" style="cursor:auto;">
            <div class="input-box" style="height: 120px;margin:0">
                <textarea name="content" id="content" placeholder="A cosa stai pensando?" ><?php echo $content;?></textarea>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET["id"];?>">
            <input type="file" name="file" id="file" style="display:none;">
            <img id="content-img" alt="" <?php if(empty($pic)) echo 'style="display:none;margin-bottom:10px;" src=""';else echo 'style="margin-bottom:10px;" src="../../HomePage/Img/'.$pic.'"';?>><i id="x" class="fa fa-close x" <?php if(empty($pic)) echo 'style="display:none;"';else echo 'style="display:block;"';?>></i>
            <div style="width:100%;display:flex;flex-direction:row;border-top:3px solid #722F37;justify-content:space-around;vertical-allign:middle;">
                <i id="icon" class="fa fa-file-photo-o" style="padding-top:17px;color:#722F37;cursor:pointer;font-size:25px;margin-bottom:10px;"></i>
                <button type="submit" class="img-btn2" style="margin-top:10px;margin-bottom:10px;">Aggiorna</button>
            </div>
        </form>
        <div class="errors">
            <?php
                if(isset($_SESSION["update"])){
                    foreach($_SESSION["update"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["update"]);
                }
            ?>
            </div>
    </div>
</body>
<?php }catch(PDOException $e){die("Error:".$e);} ?>
<script>
    function leave(){
        location.href="../";
    }
    //funzionalità AJAX
    $("document").ready(()=>{
        $("#file").on("change", () => {
            $("#content-img").attr("style","display:none;");
            document.getElementById("x").style="display:none;";
            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file deve essere più piccolo di 2 MB</p>',""));
            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file non è un immagine</p>',""));
            if($("#file").val()===""){
                $("#content-img").attr("src","");
                $("#content-img").attr("style","display=none;");
            }else{
                const [file]= $("#file").prop('files');
                if(!(file.name.includes(".png")||file.name.includes(".jpeg")||file.name.includes(".jpg"))){
                    $(".errors:first").html($(".errors:first").html()+'<p class="form-error">Il file non è un immagine</p>');
                    document.getElementById("file").value="";

                }else{
                    if(file.size>5000000){
                        $(".errors:first").html($(".errors:first").html()+'<p class="form-error">Il file deve essere più piccolo di 5 MB</p>');
                        document.getElementById("file").value="";
                    }else{
                        document.getElementById("x").style="display:block;";
                        $("#content-img").attr("src",URL.createObjectURL(file));
                        $("#content-img").attr("style","display:block;");
                        $("#content-img").attr("style","display:block;margin-bottom:10px;margin-top:5px;");
                    }
                }
                
            }
        });
        $("#content").keyup( debounce( ()=>{
            if($("#content").val().length > 255){
                if(!$(".errors:first").html().includes('<p class="form-error">Il post non deve superari i 255 caratteri</p>')){
                    $(".errors:first").html($(".errors:first").html()+"<p class='form-error'>Il post non deve superare i 255 caratteri</p>");

                }
            }else{
                $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il post non deve superare i 255 caratteri</p>',""));
            }
        },1000));
    });
    //aggiunta event listener
    document.getElementById("icon").addEventListener("click",fileChoose,false);
    document.getElementById("x").addEventListener("click",ripristino,false)
    //funzione per la selezione del file cliccando sull'icona
    function fileChoose(){
        document.getElementById("file").click();
        event.preventDefault();
    }
    //funzione per la rimozione dell'immagine
    function ripristino(){
        document.getElementById("content-img").src="";
        document.getElementById("content-img").style="display:none;";
        document.getElementById("file").value="";
        document.getElementById("x").style="display:none;";
        event.preventDefault();
    }
    //aggiunta delay alla funzione 
    function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }
</script>
</html>