<?php
function viewPost($pdo,$id,$info){
    require_once __DIR__."/query.php";
    $user=getUserById($pdo,$id);
    $username=$user["username"];
    $pfp=empty($info["pfp"])?"default.png":$info["pfp"];
    $arr_posts=getPosts($pdo,$id);
    $i=0;
    if($arr_posts){
        foreach ($arr_posts as $post) {
            $likes=getNumberOfLikes($pdo,$post["post_id"]);
            $repost=getNumberOfReposts($pdo,$post["post_id"]);
            $comments=getNumberOfComments($pdo,$post["post_id"]);
            echo '<div class="post hover" style="width:100%;z-index:1;">';
            if($post["parent_id"]!==null){
                $parent_username=getParentUsername($pdo,$post["parent_id"]);
                echo "<h4>In risposta a <span style='color:#722F37'>@". htmlspecialchars($parent_username)."</span></h4>";
            }
                        echo'<div style="display:flex;flex-direction:row;margin-top:10px;"><a href="index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]);echo'</div>
                        <h2 class="content" onclick="openPost('.$post["post_id"].')">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
            if(isset($post["img"])) echo '<div style="width:100%;" onclick="openPost('.$post["post_id"].')"><img class="content-img" src="../HomePage/Img/'.$post["img"].'"></div>';
            if($post["user_id"]===$_SESSION["user_id"]) echo '<div style="display:flex;flex-direction:row;gap:10px;justify-content:flex-end;margin-right:25px;"><i class="fa fa-edit" style="color:#722F37;font-size:20px;" onclick="editPost('.$post["post_id"].')"></i><i class="fa fa-trash-o" style="color:#722F37;font-size:20px;" onclick="toggleDelete('.$post["post_id"].')"></i></div>';
            echo '      <div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o" onclick="openPost('.$post["post_id"].')"> '.formatNumber($comments).'</i></div>
            </div>';
        }
    }else echo '<div class="post not-hover" style="width:100%">
    <h1>Quest\'account non ha postato nulla :(</h1>
    </div>';
}
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


?>