<?php
require_once "../../config_session.in.php";
require_once "../query.php";
require_once "../../dbh.in.php";
if(!isset($_GET["id"])){
    die(header("Location:../"));
}
if(!isset($_SESSION["user_id"])) die(header("Location:../../"));
try{
    $result=getInformation($pdo,$_SESSION["user_id"]);
    if(isset($_SESSION["first-time"]) or !$result){
        $_SESSION["first-time"]=true;
        die(header("Location:../../UserInfo/info.php"));
    }
    $id=$_GET["id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="main.js"></script>
    <link rel="icon" type="image/x-icon" href="../../logo.ico">
    <title>Visualizza Post</title>
</head>
<body style="display:flex;flex-direction:column;align-items: center;">
<input id="burger" type="checkbox" role="button" aria-label="Display the menu" class="burger" onclick="toggleMenu()">
    <nav class="menu" id="menu">
        <div style="display:flex;flex-direction:row;"><img id="img" src="../../logo.png" alt="logo"><h1 class="typewriter" style="color: #722F37;margin-top:25px;">Flow</h1></div>
        <a class="links" href="../index.php"><i class="fa fa-home"></i><h1>Home</h1></a>
        <a class="links" href="../../Search/index.php"><i class="fa fa-search"></i><h1>Cerca</h1></a>
        <div class="pfp-menu"  onclick="toggleProfile()"><img id="img" src=<?php echo '"../../UserInfo/pfp/'.$result["pfp"].'"'; ?> alt="">
            <div id="profile" class="profile" style="display: none;">
                <a href="../../Profile/index.php" class="links"><i class="fa fa-user"></i><h1>Il tuo account</h1></a>
                <a href="../../UserInfo/info.php" class="links"><i class="fa fa-gear"></i><h1>Impostazioni</h1></a>
                <form class="links" action="../../Logout/logout.php" method="POST" onclick="submit()" style="cursor:pointer;"><i class="fa fa-sign-out"></i><h1>Esci</h1></form>
            </div>
        </div>
    </nav>
    <?php
        require_once "view.php";
        viewPost($pdo,$id,$result);
        if(isset($_SESSION["isAdmin"])){
            echo '<div class="wrapper" id="delete" style="display:none;position:fixed;top:20%;left:10%;background-color:#EFDFBB;width:80%; z-index:1000;">
            <h1 style="font-size:20px;margin-bottom:30px;">Cancellare questo post?</h1>
    <h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare il Post?</h2>
    <p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
    <div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="toggleDelete(0)">Annulla</button><form action="../Delete/delete.php" method="POST"><input id="in" type="hidden" name="id" value="0"><button class="img-btn">Elimina</button></form></div>
            </div>   ';
        }
        $pdo=null;
        $stmt=null;
    ?>
    
    <?php
    }catch(PDOException $e){
        die("Error:".$e);
    }
    ?>

</body>

</html>