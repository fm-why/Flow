<?php
require_once "../config_session.in.php";
require_once "../dbh.in.php";
require_once __DIR__."/query.php";
if(!isset($_SESSION["user_id"])) die(header("Location:../"));
try{
    $info=getInfoBId($pdo,$_SESSION["user_id"]);
    if($info===false){
        $_SESSION["first-time"]=true;
        die(header("Location:../UserInfo/info.php"));
    }
    $info["pfp"]= empty($info["pfp"])?"default.png":$info["pfp"];
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
    <title>Cerca Utenti</title>
</head>
<body style="display: flex; flex-direction: column; text-align: center;">
<input id="burger" type="checkbox" role="button" aria-label="Display the menu" class="burger" onclick="toggleMenu()">
    <nav class="menu" id="menu">
        <div style="display:flex;flex-direction:row;"><img id="img" src="../logo.png" alt="logo"><h1 class="typewriter" style="color: #722F37;margin-top:25px;">Flow</h1></div>
        <a class="links" href="../HomePage/index.php"><i class="fa fa-home"></i><h1>Home</h1></a>
        <a class="links link-active" href="../Search/index.php"><i class="fa fa-search"></i><h1>Cerca</h1></a>
        <div class="pfp-menu"  onclick="toggleProfile()"><img id="img" src=<?php echo '"../UserInfo/pfp/'.$info["pfp"].'"'; ?> alt="">
            <div id="profile" class="profile" style="display: none;">
                <a href="../Profile/index.php" class="links"><i class="fa fa-user"></i><h1>Il tuo account</h1></a>
                <a href="../UserInfo/info.php" class="links"><i class="fa fa-gear"></i><h1>Impostazioni</h1></a>
                <form class="links" action="../Logout/logout.php" method="POST" onclick="submit()" style="cursor:pointer;"><i class="fa fa-sign-out"></i><h1>Esci</h1></form>
            </div>
        </div>
    </nav>
<div id="search">
	<svg viewBox="0 0 420 60" xmlns="http://www.w3.org/2000/svg">
		<rect class="bar"/>
		
		<g class="magnifier">
			<circle class="glass"/>
			<line class="handle" x1="32" y1="32" x2="44" y2="44"></line>
		</g>

		<g class="sparks">
			<circle class="spark"/>
			<circle class="spark"/>
			<circle class="spark"/>
		</g>

		<g class="burst pattern-one">
			<circle class="particle circle"/>
			<path class="particle triangle"/>
			<circle class="particle circle"/>
			<path class="particle plus"/>
			<rect class="particle rect"/>
			<path class="particle triangle"/>
		</g>
		<g class="burst pattern-two">
			<path class="particle plus"/>
			<circle class="particle circle"/>
			<path class="particle triangle"/>
			<rect class="particle rect"/>
			<circle class="particle circle"/>
			<path class="particle plus"/>
		</g>
		<g class="burst pattern-three">
			<circle class="particle circle"/>
			<rect class="particle rect"/>
			<path class="particle plus"/>
			<path class="particle triangle"/>
			<rect class="particle rect"/>
			<path class="particle plus"/>
		</g>
	</svg>
	<input type="search" id="username" name="username" aria-label="Search for inspiration"/>
</div>

<div id="result" class="result" style="display:flex;flex-direction:column;justify-content:center;height:100%;    align-items: center;">
    <h1 style="color: #722F37;margin-top:10%">Cerca persone e scopri nuovi profili</h1>
</div>
<?php
if(isset($_SESSION["isAdmin"])){
    $pfp=isset($info["pfp"])?$info["pfp"]:"default.png";
    echo '<div class="wrapper" id="delete" style="display:none;position:fixed;top:20%;left:10%;background-color:#EFDFBB;width:80%; z-index:1000;">
    <h1 style="font-size:20px;margin-bottom:30px;">Cancellare questo Account?</h1>
<h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare quest\'account ?</h2>
<p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
<div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="ToggleDelete(0)">Annulla</button><form action="AdminDelete/delete.php" method="POST"><input id="in" type="hidden" name="id" value="0"><button class="img-btn">Elimina</button></form></div>
    </div>   ';
}
?>
</body>
<?php
}catch(PDOException $e){die("ERROR:".$e);}
?>
<script>
function ToggleDelete(id){
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
//funzione per aprire il menu su mobile
function toggleMenu(){
    menu=document.getElementById("menu");
    if(menu.style.display==="none"){
        menu.style.display="block";
    }else{
        menu.style.display="none";
    }
}
//funzionalità ajax
$("document").ready(()=>{
     //aggiunge nell'html display:none al menù se ci troviamo su un dispositivo mobile
    if(window.matchMedia("(max-width:600px)").matches){
        document.getElementById("menu").style.display="none";
    }
    $("#username").keyup( debounce( ()=>{
        $.post("SearchUsers/search.php",{"username":$("#username").val()},function (data,status){
            $("#result").html(data);
        });
    }));
});

//aggiunta delay alla funzione 
function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }

    function ToggleFollow(follower,id){
        console.log(follower);
    $.post("../Follow/follow.php",{"follower":follower},function (data,status){
        console.log(data);
        if(data==="fa-user-plus"){
            document.getElementById("icon-"+id).classList.remove("fa-user-times");
            document.getElementById("icon-"+id).classList.add(data);
        }else if(data==="fa-user-times"){
            document.getElementById("icon-"+id).classList.remove("fa-user-plus");
            document.getElementById("icon-"+id).classList.add(data);
        }
        

    });

}
</script>
</html>