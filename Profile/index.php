
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../logo.ico">
    <script src="main.js"></script>
    <title>Profilo</title>
</head>
<body style="display:flex;flex-direction:column;align-items: center;">
<?php
//funzione per convertire le date in testo
function getMonthAndYear($date) {
    $months = [
        1 => "gennaio", 2 => "febbraio", 3 => "marzo", 4 => "aprile",
        5 => "maggio", 6 => "giugno", 7 => "luglio", 8 => "agosto",
        9 => "settembre", 10 => "ottobre", 11 => "novembre", 12 => "dicembre"
    ];

    $timestamp = strtotime($date);
    $month = $months[date("n", $timestamp)]; // Ottieni il mese dall'array
    $year = date("Y", $timestamp); // Ottieni l'anno

    return "$month $year"; // Formato "marzo 2024"
}
//funzione per visualizzare i numeri in formato k,mln,mld (es. 3000->3k) (l'ha sempre fatta chatgpt non avevo voglia)
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
require_once "../config_session.in.php";
require_once "../dbh.in.php";
require_once "query.php";

if(!isset($_SESSION["user_id"])) die(header("Location:../"));
try{
    $result=getInformation($pdo,$_SESSION["user_id"]);
    if(isset($_SESSION["first-time"]) or !$result){
        $_SESSION["first-time"]=true;
        die(header("Location:../UserInfo/info.php"));
    }
    if(isset($_GET["user"])){
        $info=getInformationByUsername($pdo,$_GET["user"]);
        if(!$info){
            echo "<div class='post'><h1>Non esiste questo Account :(</h1></div>";
            exit();
        }
    }else{
        $info=$result;
        $info["data"]=getIdByUsername($pdo,$_SESSION["user_username"])["data"];
    }
    $username=isset($_GET["user"])?$_GET["user"]:$_SESSION["user_username"];
    $result["pfp"]=empty($result["pfp"])?"default.png":$result["pfp"];
?>
    <input id="burger" type="checkbox" role="button" aria-label="Display the menu" class="burger" onclick="toggleMenu()">
    <nav class="menu" id="menu">
        <div style="display:flex;flex-direction:row;"><img id="img" src="../logo.png" alt="logo"><h1 class="typewriter" style="color: #722F37;margin-top:25px;">Flow</h1></div>
        <a class="links" href="../HomePage/index.php"><i class="fa fa-home"></i><h1>Home</h1></a>
        <a class="links" href="../Search/index.php"><i class="fa fa-search"></i><h1>Cerca</h1></a>
        <div class="pfp-menu"  onclick="toggleProfile()"><img id="img" src=<?php echo '"../UserInfo/pfp/'.$result["pfp"].'"'; ?> alt="">
            <div id="profile" class="profile" style="display: none;">
                <a href="../Profile/index.php" class="links link-active"><i class="fa fa-user"></i><h1>Il tuo account</h1></a>
                <a href="../UserInfo/info.php" class="links"><i class="fa fa-gear"></i><h1>Impostazioni</h1></a>
                <form class="links" action="../Logout/logout.php" method="POST" onclick="submit()" style="cursor:pointer;"><i class="fa fa-sign-out"></i><h1>Esci</h1></form>
            </div>
        </div>
    </nav>
    <div class="post not-hover">
        <div style="display:flex;flex-direction:row;justify-content:space-between;">
            <div style="display:flex;flex-direction:row;gap:10px;">
                <img id="img" src="../UserInfo/pfp/<?php echo isset($info["pfp"])?$info["pfp"]:"default.png";?>" alt="pfp" style="height:70px;width:70px;cursor:default;margin:auto">
                <div style="display: flex;flex-direction:column;font-size:14px;">
                    <h1><?php echo htmlspecialchars($info["nome"]);?></h1>
                    <h2 class="text-shadow">@<?php echo htmlspecialchars($username);?></h2>
                </div>
                <?php
                if(isset($_GET["user"]) and $_GET["user"]!==$_SESSION["user_username"]){
                    echo '<i id="icon" class="fa ';
                    if(weFollow($pdo,$_SESSION["user_id"],$username)){
                        echo 'fa-user-times';
                    }else echo 'fa-user-plus';
                    echo '" onclick="toggleFollow(\''.$username.'\')" style="color:#722F37;margin:auto;cursor:pointer;"></i>';
                }
                ?>
            </div>
            <?php
            if(!isset($_GET["user"]) or $_GET["user"]===$_SESSION["user_username"]){
                echo '<a href="../UserInfo/info.php"><i class="fa fa-gear" style="margin-top:10px;margin-right:100px;font-size:30px;color:#722F37;"></i></a>';
            }else{
                if(isset($_SESSION["isAdmin"])){
                    if(!getIdByUsername($pdo,$_GET["user"])["isAdmin"]){
                        echo '<i class="fa fa-trash-o" onclick="toggleAdminDelete('.getIdByUsername($pdo,$username)["id_user"].')" style="margin-top:10px;margin-right:100px;font-size:30px;color:#722F37;cursor:pointer;"></i>';
                    }
                }
            }
            ?>
        </div>
        <h2 style="width:90%;overflow-wrap:break-word;margin-left:5%;"><?php echo htmlspecialchars($info["bio"]);?></h2>
        <?php if(isset($_SESSION["isAdmin"]) and isset($_SESSION["admin_error"])){
            echo '<p class="error">'.$_SESSION["admin_error"].'</p>';
            unset($_SESSION["admin_error"]);
        }?>
        <div style="display:flex;flex-direction:row;color:#722F37;justify-content:space-between;border-top:solid 2px #722F37;"><p><i class="fa fa-calendar"></i> Iscritto da <?php echo getMonthAndYear($info["data"]);?></p>
        <?php echo "<p>Seguiti:".countFollowing($pdo,$info["id_user"])["following"]."</p><p id='follower'>Follower:".countFollower($pdo,$info["id_user"])["follower"]."</p>";?>
        </div>
    </div>
    <div class="nav">
        <div style="display: flex;flex-direction:row;justify-content:space-around;color:#722F37;"><p class="active switch" onclick="showPost()">Post</p><p class="deactive switch" onclick="showRepost()">Retweet</p></div>
    </div>
    <?php
    if(isset($_SESSION["isAdmin"]) and !getIdByUsername($pdo,$username)["isAdmin"]){
        $pfp=isset($info["pfp"])?$info["pfp"]:"default.png";
        echo '<div class="wrapper" id="Admin-delete" style="display:none;position:fixed;top:20%;left:10%;background-color:#EFDFBB;width:80%; z-index:1000;">
        <h1 style="font-size:20px;margin-bottom:30px;">Cancellare questo Account?</h1>
<h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare l\'account <span style="color:#722F37">@'.$username.'?</span></h2>
<div style="display:flex;justify-content:center;"><img id="img" src="../UserInfo/pfp/'.$pfp.'" alt="pfp" style="height:70px;width:70px;cursor:default;margin:auto"></div>
<p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
<div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="toggleAdminDelete(0)">Annulla</button><form action="AdminDelete/delete.php" method="POST"><input id="Admin-in" type="hidden" name="id" value="0"><button class="img-btn">Elimina</button></form></div>
        </div>   ';
    }
    ?>
    <div class="posted body" style="display:block;" id="post">
            <?php
                require_once "Show/post.php";
                viewPost($pdo,$info["id_user"],$info);
                if(!isset($_GET["user"]) or $_GET["user"]===$_SESSION["user_username"]){
                    echo '<div class="wrapper" id="delete" style="display:none;position:fixed;top:20%;left:10%;background-color:#EFDFBB;width:80%; z-index:1000;">
            <h1 style="font-size:20px;margin-bottom:30px;">Cancellare il proprio post?</h1>
    <h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare il tuo Post?</h2>
    <p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
    <div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="toggleDelete(0)">Annulla</button><form action="Delete/delete.php" method="POST"><input id="in" type="hidden" name="id" value="0"><button class="img-btn">Elimina</button></form></div>
            </div>   ';
                }
            ?>
    </div>
    <div class="repost body" style="display:none;" id="repost">
            <?php
                require_once "Show/repost.php";
                viewRepost($pdo,$info["id_user"]);
            ?>
    </div>
<?php
}
catch(PDOException $e){die("Error:".$e);}
?>
</body>

</html>