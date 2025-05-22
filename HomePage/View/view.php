<?php
function viewPosts($pdo,$posts){
    require_once __DIR__."\query.php";
    $i=0;
    foreach ($posts as $post) {
        $username=getUsernameById($pdo,$post["user_id"]);
        $pfp=getPfpById($pdo,$post["user_id"]);
        $pfp=empty($pfp)?"default.png":$pfp;
        $likes=getNumberOfLikes($pdo,$post["post_id"]);
        $repost=getNumberOfReposts($pdo,$post["post_id"]);
        $comments=getNumberOfComments($pdo,$post["post_id"]);
        echo '<div class="post">';
                    echo '<div style="display:flex;flex-direction:row;margin-top:10px;"><a href="../Profile/index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]).'    ';if($_SESSION["user_id"]!=$post["user_id"]){if(weAreFollowing($pdo,$_SESSION["user_id"],$post["user_id"])) echo '<i id="icon-'.$i.'" class="fa fa-user-times" onclick="toggleFollow(\''.$username.'\','.$i.')" style="cursor:pointer"></i>'; else echo '<i id="icon-'.$i.'" class="fa fa-user-plus" onclick="toggleFollow(\''.$username.'\','.$i++.')" style="cursor:pointer"></i>';}echo'</div>
                    <h2 class="content" onclick="openPost('.$post["post_id"].')">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
        if(isset($post["img"])) echo '<img class="content-img" src="Img/'.$post["img"].'" onclick="openPost('.$post["post_id"].')">';
        if(isset($_SESSION["isAdmin"])) echo "<br><i style='color:#722F37;' class='fa fa-trash-o' onclick='toggleDelete(".$post["post_id"].")'></i>";
        echo '      <div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o" onclick="openPost('.$post["post_id"].')"> '.formatNumber($comments).'</i></div>
                </div>';
    }
}

function viewFollowPosts($pdo,$posts){
    require_once __DIR__."\query.php";
    $i=0;
    foreach ($posts as $post) {
        $username=getUsernameById($pdo,$post["user_id"]);
        $pfp=getPfpById($pdo,$post["user_id"]);
        $pfp=empty($pfp)?"default.png":$pfp;
        $likes=getNumberOfLikes($pdo,$post["post_id"]);
        $repost=getNumberOfReposts($pdo,$post["post_id"]);
        $comments=getNumberOfComments($pdo,$post["post_id"]);
        echo '<div class="post">';
                    echo '<div style="display:flex;flex-direction:row;margin-top:10px;"><a href="../Profile/index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]).'    ';if($_SESSION["user_id"]!=$post["user_id"]){if(weAreFollowing($pdo,$_SESSION["user_id"],$post["user_id"])) echo '<i id="icon-Follow-'.$i.'" class="fa fa-user-times" onclick="toggleFollow(\''.$username.'\',\'Follow-'.$i.'\')" style="cursor:pointer"></i>'; else echo '<i id="icon-Follow-'.$i.'" class="fa fa-user-plus" onclick="toggleFollow(\''.$username.'\',\'Follow-'.$i++.'\')" style="cursor:pointer"></i>';}echo'</div>
                    <h2 class="content" onclick="openPost('.$post["post_id"].')">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
        if(isset($post["img"])) echo '<img class="content-img" src="Img/'.$post["img"].'" onclick="openPost('.$post["post_id"].')">';
        if(isset($_SESSION["isAdmin"])) echo "<br><i style='color:#722F37;' class='fa fa-trash-o' onclick='toggleDelete(".$post["post_id"].")'></i>";
        echo '      <div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o" onclick="openPost('.$post["post_id"].')"> '.formatNumber($comments).'</i></div>
                </div>';
    }
}
function viewPost($pdo,$id,$info){
    require_once __DIR__."\query.php";
    $post=getPostById($pdo,$id);
    if(!$post){
        echo '<div class="post not-hover" style="margin-top:45%;"><h1>Questo post non esiste :(</h1></div>';
        exit();
    }
    $username=getUsernameById($pdo,$post["user_id"]);
    $pfp=getPfpById($pdo,$post["user_id"]);
    $pfp=empty($pfp)?"default.png":$pfp;
    $likes=getNumberOfLikes($pdo,$post["post_id"]);
    $repost=getNumberOfReposts($pdo,$post["post_id"]);
    $comments=getNumberOfComments($pdo,$post["post_id"]);
    $info["pfp"]= $info["pfp"]=== null ? "default.png" : $info["pfp"];
    echo '<div class="post not-hover">';
    
    if(isset($post["parent_id"])){
        $parent=getPostById($pdo,$post["parent_id"]);
        $parent_username=getUsernameById($pdo,$parent["user_id"]);
        echo '<div style="cursor:pointer;"><p class="text-shadow">In risposta a @'.htmlspecialchars($parent_username).'</p></div>';
    }
    echo '
                <div style="display:flex;flex-direction:row;margin-top:10px;"><a href="../../Profile/index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]).'    ';if($_SESSION["user_id"]!=$post["user_id"]){if(weAreFollowing($pdo,$_SESSION["user_id"],$post["user_id"])) echo '<i id="icon-0" class="fa fa-user-times" onclick="toggleFollow(\''.$username.'\',0)" style="cursor:pointer;"></i>'; else echo '<i id="icon-0" class="fa fa-user-plus" onclick="toggleFollow(\''.$username.'\',0)" style="cursor:pointer;"></i>';}echo'</div>
                <h2 class="content">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
    if(isset($post["img"])) echo '<img class="content-img" src="../Img/'.$post["img"].'">';
    if(isset($_SESSION["isAdmin"])) echo "<br><i style='color:#722F37;cursor:pointer;' class='fa fa-trash-o' onclick='toggleDelete(".$post["post_id"].")'></i>";
    echo '      <div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o"> '.formatNumber($comments).'</i></div>
            </div>';

           echo' <div class="post-reply">
            <form action="../Create/create.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                <img id="img" src="../../UserInfo/pfp/'.$info["pfp"].'" alt="pfp" style="cursor:auto;">
                <div class="input-box" style="height: 120px;margin:0">
                    <textarea name="content" id="content" placeholder="Rispondi a @'.htmlspecialchars($username).'" >'; if(isset($_SESSION["post_data"])){echo $_SESSION["post_data"]["content"];unset($_SESSION["post_data"]);}echo '</textarea>
                </div>
                <input type="text" name="parent_id" style="display:none;" value="'.$id.'">
                <input type="file" name="file" id="file" style="display:none;">
                <img id="content-img" src="" alt="" style="display: none;margin-bottom:10px;"><i id="x" class="fa fa-close x" style="display:none;"></i>
                <div style="width:100%;display:flex;flex-direction:row;border-top:3px solid #722F37;justify-content:space-around;vertical-allign:middle;">
                    <i id="icon" class="fa fa-file-photo-o" style="padding-top:17px;color:#722F37;cursor:pointer;font-size:25px;margin-bottom:10px;"></i>
                    <button type="submit" class="img-btn2" style="margin-top:10px;margin-bottom:10px;">Rispondi</button>
                </div>
            </form>
            <div class="errors">';

                    if(isset($_SESSION["post_error"])){
                        foreach($_SESSION["post_error"] as $error){
                            echo "<p class='form-error'>".$error."</p>";
                        }
                        unset($_SESSION["post_error"]);
                    }

                echo '</div>
        </div>';
        viewReplies($pdo,$id);
}
function viewReplies($pdo,$id){
    require_once __DIR__."\query.php";
    $posts=getRepliesById($pdo,$id);
    if(!$posts){
        echo '<div class="post not-hover"><h1>Non ci sono risposte.Avvia una discussione</h1></div>';
        exit();
    }
    $i=1;
    foreach ($posts as $post) {
        $username=getUsernameById($pdo,$post["user_id"]);
        $pfp=getPfpById($pdo,$post["user_id"]);
        $pfp= $pfp===null ? "default.png" : $pfp;
        $likes=getNumberOfLikes($pdo,$post["post_id"]);
        $repost=getNumberOfReposts($pdo,$post["post_id"]);
        $comments=getNumberOfComments($pdo,$post["post_id"]);
        echo '<div class="post">';
                echo '<div>
                    <p id="id" style="display:none;">'.$post["post_id"].'</p>
                    <div style="display:flex;flex-direction:row;margin-top:10px;"><a href="../../Profile/index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]).'    ';if($_SESSION["user_id"]!=$post["user_id"]){if(weAreFollowing($pdo,$_SESSION["user_id"],$post["user_id"])) echo '<i id="icon-'.$i.'" class="fa fa-user-times" onclick="toggleFollow(\''.$username.'\','.$i.')"></i>'; else echo '<i id="icon-'.$i.'" class="fa fa-user-plus" onclick="toggleFollow(\''.$username.'\','.$i++.')"></i>';}echo'</div>
                    <h2 class="content" onclick="openPost('.$post["post_id"].')">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
        if(isset($post["img"])) echo '<div style="width:100%;" onclick="openPost('.$post["post_id"].')"><img class="content-img" src="../Img/'.$post["img"].'"></div><br>';
        if(isset($_SESSION["isAdmin"])) echo "<i style='color:#722F37;text-allign:end;' class='fa fa-trash-o' onclick='toggleDelete(".$post["post_id"].")'></i>";
        echo '<div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o" onclick="openPost('.$post["post_id"].')"> '.formatNumber($comments).'</i></div>
                </div></div>';
    }
    echo '<div style="margin-top:250px"></div>';

}

//funzione per visualizzare la differenza di tempo tra la creazione del post e ora (l'ha fatta chatgpt non avevo voglia)
function timeAgo($datetime) {
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);

    // Se meno di 60 secondi
    if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
        return $diff->s . "s fa";
    }
    // Se meno di 60 minuti
    if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0) {
        return $diff->i . "m fa";
    }
    // Se meno di 24 ore
    if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0) {
        return $diff->h . "h fa";
    }
    // Se meno di 7 giorni
    if ($diff->y == 0 && $diff->m == 0 && $diff->d < 7) {
        return $diff->d . "g fa";
    }
    // Se nello stesso anno, mostra solo "8 mar"
    if ($diff->y == 0) {
        return date("j M", strtotime($datetime));
    }
    // Se più di un anno, mostra "17 giu 2023"
    return date("j M Y", strtotime($datetime));
}
//funzione per visualizzare i numeri in formato k,mln,mld (es. 3000->3k) (l'ha sempre fatta chatgpt per lo stesso motivo)
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
?>