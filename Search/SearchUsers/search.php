<?php
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
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once "../../dbh.in.php";
    require_once __DIR__."/query.php";
    require_once "../../config_session.in.php";
    $username=$_POST["username"];
    if(strlen($username)<=0) die('<h1 style="color: #722F37;margin-top:10%">Cerca persone e scopri nuovi profili</h1>');
    try{
        $results=getUsers($pdo,$username);
        if(empty($results)){
            echo '<h1 style="color: #722F37;margin-top:10%">Non esiste nessun\'account @'.htmlspecialchars($username).'</h1>';
            die();
        }
        $i=0;
        foreach($results as $user){
            $username=$user["username"];
            $name=$user["nome"];
            $pfp=empty($user["pfp"])?"default.png":$user["pfp"];
            $seguiti=formatNumber(countFollowing($pdo,$user["id_user"])["following"]);
            $follower=formatNumber(countFollower($pdo,$user["id_user"])["follower"]);
            echo '<div class="post">
                    <a href="../Profile/index.php?user='.htmlspecialchars($username).'" style="text-decoration:none;">
                    <div style="display:flex;flex-direction:row;">
                        <img id="img" src="../UserInfo/pfp/'.$pfp.'" style="margin-right: 20px;margin-top: 10px;margin-left:10px; height: 70px;width: 50px;">
                        <div style="display:flex;flex-direction:column;text-align:left;">
                            <h1 style="color:#722F37;width:fit-content;">@'.htmlspecialchars($username).'</h1>
                            <p class="text-shadow">'.htmlspecialchars($name).'</p>
                            <p id="counter-'.$i.'" style="color:gray;">'.$seguiti.' Seguiti  '.$follower.' Follower</p>
                        </div>
                    ';if($_SESSION["user_id"]!==$user["id_user"]){
                        echo '<div style="margin-left:auto;margin-top:20px;gap:10px;font-size:30px;cursor:pointer;">';
                        if(weFollow($pdo,$_SESSION["user_id"],$user["id_user"])) echo '<i id="icon-'.$i.'" class="fa fa-user-times" onclick=\'ToggleFollow("'.$username.'",'.$i++.')\' style="color:#722F37;"></i>';
                        else echo '<i id="icon-'.$i.'" class="fa fa-user-plus" onclick=\'ToggleFollow("'.$username.'",'.$i++.')\' style="color:#722F37;"></i>';
                        if(isset($_SESSION["isAdmin"]) and $user["isAdmin"]===0 ) echo '<i class="fa fa-trash-o" onclick=\'ToggleDelete("'.$user["id_user"].'")\' style="color:#722F37;"></i>';
                        echo "</div>";
                    }
                    echo'</div></a>
                    
                  </div>';
        }
    }catch(PDOException $e){die("ERROR:".$e);}

}else die(header("Location:../"));
?>