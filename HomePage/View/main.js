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

//funzionalità AJAX
$("document").ready(()=>{
     //aggiunge nell'html display:none al menù se ci troviamo su un dispositivo mobile
    if(window.matchMedia("(max-width:600px)").matches){
        document.getElementById("menu").style.display="none";
    }
    //aggiunta event listener
    document.getElementById("icon").addEventListener("click",fileChoose,false);
    document.getElementById("x").addEventListener("click",ripristino,false)
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
                if(file.size>2000000){
                    $(".errors:first").html($(".errors:first").html()+'<p class="form-error">Il file deve essere più piccolo di 2 MB</p>');
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
    $.post("../Create/like.php",{"id":id_post},function(data,status){
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
    $.post("../Create/repost.php",{"id":id_post},function(data,status){
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
    window.location="viewPost.php?id="+id;
}

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
    $.post("../../Follow/follow.php",{"follower":follower},function (data,status){
        if(data==="fa-user-plus"){
            document.getElementById("icon-"+i).classList.remove("fa-user-times");
            document.getElementById("icon-"+i).classList.add(data);
        }else if(data==="fa-user-times"){
            document.getElementById("icon-"+i).classList.remove("fa-user-plus");
            document.getElementById("icon-"+i).classList.add(data);
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