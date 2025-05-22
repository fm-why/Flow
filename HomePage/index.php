<?php 
    require_once "../config_session.in.php";
    require_once "../dbh.in.php";
    require_once __DIR__."/query.php";
    //reindirizzamenti se non si è loggati o se info non settate
    if(!isset($_SESSION["user_id"]))die(header("Location:../index.php"));
    $result=getInformation($pdo,$_SESSION["user_id"]);
    if(isset($_SESSION["first-time"]) or empty($result)){
        $_SESSION["first-time"]=true;
        die(header("Location:../UserInfo/info.php"));
    }
    $result["pfp"]= $result["pfp"]=== null ? "default.png" : $result["pfp"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../logo.ico">
    <title>Home</title>
</head>
<body style="display:flex;flex-direction:column;align-items: center;">
    <input id="burger" type="checkbox" role="button" aria-label="Display the menu" class="burger" onclick="toggleMenu()">
    <nav class="menu" id="menu">
        <div style="display:flex;flex-direction:row;"><img id="img" src="../logo.png" alt="logo"><h1 class="typewriter" style="color: #722F37;margin-top:25px;">Flow</h1></div>
        <a class="links link-active" href=""><i class="fa fa-home"></i><h1>Home</h1></a>
        <a class="links" href="../Search/index.php"><i class="fa fa-search"></i><h1>Cerca</h1></a>
        <div class="pfp-menu"  onclick="toggleProfile()"><img id="img" src=<?php echo '"../UserInfo/pfp/'.$result["pfp"].'"'; ?> alt="">
            <div id="profile" class="profile" style="display: none;">
                <a href="../Profile/index.php" class="links"><i class="fa fa-user"></i><h1>Il tuo account</h1></a>
                <a href="../UserInfo/info.php" class="links"><i class="fa fa-gear"></i><h1>Impostazioni</h1></a>
                <form class="links" action="../Logout/logout.php" method="POST" onclick="submit()" style="cursor:pointer;"><i class="fa fa-sign-out"></i><h1>Esci</h1></form>
            </div>
        </div>
    </nav>
    <div class="post not-hover">
        <form action="Create/create.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
            <img id="img" src=<?php echo '"../UserInfo/pfp/'.$result["pfp"].'"'; ?> alt="pfp" style="cursor:auto;">
            <div class="input-box" style="height: 120px;margin:0">
                <textarea name="content" id="content" placeholder="A cosa stai pensando?" ><?php if(isset($_SESSION["post_data"])){echo $_SESSION["post_data"]["content"];unset($_SESSION["post_data"]);}?></textarea>
            </div>
            <input type="file" name="file" id="file" style="display:none;">
            <img id="content-img" src="" alt="" style="display: none;margin-bottom:10px;"><i id="x" class="fa fa-close x" style="display:none;"></i>
            <div style="width:100%;display:flex;flex-direction:row;border-top:3px solid #722F37;justify-content:space-around;vertical-allign:middle;">
                <i id="icon" class="fa fa-file-photo-o" style="padding-top:17px;color:#722F37;cursor:pointer;font-size:25px;margin-bottom:10px;"></i>
                <button type="submit" class="img-btn2" style="margin-top:10px;margin-bottom:10px;">Posta</button>
            </div>
        </form>
        <div class="errors">
            <?php
                if(isset($_SESSION["post_error"])){
                    foreach($_SESSION["post_error"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["post_error"]);
                }
            ?>
            </div>
    </div>
    <div class="nav">
        <div style="display: flex;flex-direction:row;justify-content:space-around;color:#722F37;"><p class="active switch" onclick="showHome()">Home</p><p class="deactive switch" onclick="showFollow()">Seguiti</p></div>
    </div>
    <?php
    if(isset($_SESSION["isAdmin"])){
        echo '<div class="wrapper" id="delete" style="display:none;position:fixed;top:20%;left:10%;background-color:#EFDFBB;width:80%; z-index:1000;">
        <h1 style="font-size:20px;margin-bottom:30px;">Cancellare questo post?</h1>
<h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare il Post?</h2>
<p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
<div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="toggleDelete(0)">Annulla</button><form action="Delete/delete.php" method="POST"><input id="in" type="hidden" name="id" value="0"><button class="img-btn">Elimina</button></form></div>
        </div>   ';
    }
    ?>
    <div id="home" class="home body" style="display:block;width:100%;" >
    <?php 
        require_once "View/view.php";
        $posts=getPosts($pdo);
        if(empty($posts)) echo "<div class='post'><h1 style='color:#722F37;'>Nessuno ha ancora postato qualcosa! Sii il primo</h1></div>";
        viewPosts($pdo,$posts);
    ?>
    </div>
    <div id="follow" class="home body" style="display:none;width:100%">
    <?php 

        $posts=getFollowPosts($pdo,$_SESSION["user_id"]);
        if(empty($posts)) echo "<div class='post'><h1 style='color:#722F37;'>Nessuno dei tuoi seguiti ha postato qualcosa</h1></div>";
        else viewFollowPosts($pdo,$posts);
    ?>
    </div>
</body>
<script>
    //funzione per aprire il menu su mobile
    function toggleMenu(){
        menu=document.getElementById("menu");
        if(menu.style.display==="none"){
            menu.style.display="block";
        }else{
            menu.style.display="none";
        }
    }
    function showHome(){
    switches=document.getElementsByClassName("switch");
    if(!switches[0].classList.contains("active")){
        switches[0].classList.remove("deactive");
        switches[0].classList.add("active");
        switches[1].classList.remove("active");
        switches[1].classList.add("deactive");
        document.getElementById("home").style.display="block";
        document.getElementById("follow").style.display="none";
    }
}
//funzione per visualizzare i repost
function showFollow(){
    switches=document.getElementsByClassName("switch");
    if(!switches[1].classList.contains("active")){
        switches[1].classList.remove("deactive");
        switches[1].classList.add("active");
        switches[0].classList.remove("active");
        switches[0].classList.add("deactive");
        document.getElementById("home").style.display="none";
        document.getElementById("follow").style.display="block";
    }
}
    //funzionalità AJAX
    $("document").ready(()=>{
         //aggiunge nell'html display:none al menù se ci troviamo su un dispositivo mobile
        if(window.matchMedia("(max-width:600px)").matches){
            document.getElementById("menu").style.display="none";
        }
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
    //aggiunta/rimozione like ad un post
    function ToggleLike(id_post,btn){
        $.post("Create/like.php",{"id":id_post},function(data,status){
            btn.textContent=" "+data;
            if(btn.classList.contains("fa-heart-o")){
                btn.classList.remove("fa-heart-o");
                btn.classList.add("fa-heart");
            }else{
                btn.classList.add("fa-heart-o");
                btn.classList.remove("fa-heart");
            }
        });
    }
    //aggiunta/rimozione repost da post
    function ToggleRepost(id_post,btn){
        $.post("Create/repost.php",{"id":id_post},function(data,status){
            btn.textContent=" "+data;
            if(btn.classList.contains("fa-undo")){
                btn.classList.remove("fa-undo");
                btn.classList.add("fa-retweet");
            }else{
                btn.classList.add("fa-undo");
                btn.classList.remove("fa-retweet");
            }
        });
    }
    //apertura schermata admin
    function toggleDelete(id){
        const deletes=document.getElementById("delete");
        document.getElementById("in").value=id;
        if(deletes.style.display==="block"){
            deletes.style.display="none";
        }else{
            deletes.style.display="block";
        }
    }
    //apertura schermata profilo nel menu
    function toggleProfile(){
        const profile=document.getElementById("profile");
        if(profile.style.display==="block"){
            profile.style.display="none";
        }else{
            profile.style.display="block";
        }
    }
    //apertura del singolo post
    function openPost(id){
        window.location="View/viewPost.php?id="+id;
    }
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
    //aggiunta/rimozione follow
    function toggleFollow(follower,i){
    $.post("../Follow/follow.php",{"follower":follower},function (data,status){
        if(data==="fa-user-plus"){
            document.getElementById("icon-"+i).classList.remove("fa-user-times");
            document.getElementById("icon-"+i).classList.add(data);
        }else if(data==="fa-user-times"){
            document.getElementById("icon-"+i).classList.remove("fa-user-plus");
            document.getElementById("icon-"+i).classList.add(data);
        }
        $("#follow").load(location.href+" #follow>*","");
    });

}
</script>
</html>