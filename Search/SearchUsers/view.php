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
function viewUsers($pdo,$users){
    require_once "../../config_session.in.php";
    foreach($users as $user){
        $username=$user["username"];
        $name=$user["nome"];
        $pfp=empty($user["pfp"])?"default.png":$user["pfp"];
        $seguiti=formatNumber(countFollowing($pdo,$user["id_user"])["following"]);
        $follower=formatNumber(countFollower($pdo,$user["id_user"])["follower"]);
        echo '<div class="post">
                <div style="display:flex;flex-direction:row;">
                    <img src="../UserInfo/pfp/'.$pfp.'>
                    <div style="display:flex;flex-direction:column;">
                        <h1 style="color:#722F37;">@'.htmlspecialchars($username).'</h1>
                        <p class="text-shadow">'.htmlspecialchars($name).'</p>
                    </div>
                ';if($_SESSION["user_id"]!==$user["id_user"]){
                    if(weFollow($pdo,$_SESSION["user_id"],$user["id_user"])) echo '<i class="fa fa-user-times" onclick=\'ToggleFollow("'.$username.'")\'></i>';
                    else echo '<i class="fa fa-user-add" onclick=\'ToggleFollow("'.$username.'")\'></i>';
                }
                if(isset($_SESSION["isAdmin"])) echo '<i class="fa fa-trash-o" onclick=\'ToggleDelete("'.$user["id_user"].'")\'></i>';
                echo'</div>
                <div><p>'.$seguiti.' Seguiti  '.$follower.'Follower</p></div>
              </div>';
    }
}
?>