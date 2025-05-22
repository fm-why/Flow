<?php
function viewRepost($pdo,$id){
    require_once __DIR__."/query.php";
    $reposts=getReposts($pdo,$id);
    if($reposts){
        foreach ($reposts as $repost) {
            $post=getPostById($pdo,$repost["post_id"]);
            $username=getUserById($pdo,$repost["user_id"])["username"];
            $pfp=getPfpById($pdo,$repost["user_id"]);
            $pfp=empty($pfp)?"defaul.png":$pfp;
            $likes=getNumberOfLikes($pdo,$post["post_id"]);
            $repost=getNumberOfReposts($pdo,$post["post_id"]);
            $comments=getNumberOfComments($pdo,$post["post_id"]);
            echo '<div class="post" style="width:100%;">
                        <div style="display:flex;flex-direction:row;margin-top:10px;"><a href="../Profile/index.php?user='.$username.'" style="display:flex;flex-direction:row;text-decoration:none;"><img id="img" src="../UserInfo/pfp/'.$pfp.'"><h1 style="font-size:20px">@'.htmlspecialchars($username).'</h1></a><p class="date">'.timeAgo($post["created_at"]);echo'</div>
                        <h2 class="content" onclick="openPost('.$post["post_id"].')">'.nl2br(htmlspecialchars($post["content"])).'</h2>';
            if(isset($post["img"])) echo '<img class="content-img" src="../HomePage/Img/'.$post["img"].'" onclick="openPost('.$post["post_id"].')">';
            echo '      <div class="numbers" ><i id="likes" class="';if(weLike($pdo,$post["post_id"],$_SESSION["user_id"]))echo "fa fa-heart";else echo "fa fa-heart-o"; echo'" onClick="ToggleLike('.$post["post_id"].',this)"> '.formatNumber($likes).'</i><i class="fa ';if(weRepost($pdo,$post["post_id"],$_SESSION["user_id"])) echo "fa-undo";else echo "fa-retweet";echo'" onclick="ToggleRepost('.$post["post_id"].',this)"> '.formatNumber($repost).'</i><i class="fa fa-commenting-o" onclick="openPost('.$post["post_id"].')"> '.formatNumber($comments).'</i></div>
                    </div>';
        }
    }else echo '<div class="post not-hover" style="width:100%">
        <h1>Quest\'account non ha repostato nulla :(</h1>
        </div>';
}

?>