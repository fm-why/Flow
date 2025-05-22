 //aggiunge nell'html display:none al menù se ci troviamo su un dispositivo mobile
 $("document").ready(()=>{
    if(window.matchMedia("(max-width:600px)").matches){
        document.getElementById("menu").style.display="none";
    }
 });
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

function toggleAdminDelete(id){
    const deletes=document.getElementById("Admin-delete");
    document.getElementById("Admin-in").value=id;
    if(deletes.style.display==="block"){
        deletes.style.display="none";
    }else{
        deletes.style.display="block";
    }
}
function toggleDelete(id){
    const deletes=document.getElementById("delete");
    document.getElementById("in").value=id;
    if(deletes.style.display==="block"){
        deletes.style.display="none";
    }else{
        deletes.style.display="block";
    }
}
function editPost(id){
    location.href="ModifyPost/modify.php?id="+id;
}
function toggleFollow(follower){
    $.post("../Follow/follow.php",{"follower":follower},function (data,status){
        if(data==="fa-user-plus"){
            document.getElementById("icon").classList.remove("fa-user-times");
            document.getElementById("icon").classList.add(data);
        }else if(data==="fa-user-times"){
            document.getElementById("icon").classList.remove("fa-user-plus");
            document.getElementById("icon").classList.add(data);
        }
        $("#follower").load(location.href + " #follower");
    });

}
 //aggiunta/rimozione like ad un post
 function ToggleLike(id_post,btn){
    $.post("../HomePage/Create/like.php",{"id":id_post},function(data,status){
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
    $.post("../HomePage/Create/repost.php",{"id":id_post},function(data,status){
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
//apertura del singolo post
function openPost(id){
    window.location="../HomePage/View/viewPost.php?id="+id;
}
//funzione per visualizzare i post
function showPost(){
    switches=document.getElementsByClassName("switch");
    if(!switches[0].classList.contains("active")){
        switches[0].classList.remove("deactive");
        switches[0].classList.add("active");
        switches[1].classList.remove("active");
        switches[1].classList.add("deactive");
        document.getElementById("post").style.display="block";
        document.getElementById("repost").style.display="none";
    }
}
console.log(document.getElementsByClassName("switch"))
//funzione per visualizzare i repost
function showRepost(){
    switches=document.getElementsByClassName("switch");
    if(!switches[1].classList.contains("active")){
        switches[1].classList.remove("deactive");
        switches[1].classList.add("active");
        switches[0].classList.remove("active");
        switches[0].classList.add("deactive");
        document.getElementById("post").style.display="none";
        document.getElementById("repost").style.display="block";
    }
}