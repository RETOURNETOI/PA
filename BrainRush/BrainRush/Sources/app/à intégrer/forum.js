document.addEventListener("DOMContentLoaded", function(){

  var formu = document.getElementById("formulaire-nouveau-post");
  var zonePosts = document.getElementById("liste-publications");

fetch("get_posts.php")
.then(function(d){
  return d.json();
}).then(function(posts){
    for(let k = 0; k<posts.length;k++) {
  let p = posts[k];
  afficherPost(p);
}
});

formu.addEventListener("submit",function(ev){
ev.preventDefault();
  let t = document.getElementById("champ-titre").value;
  let c = document.getElementById("champ-contenu").value;
let t2 = t.trim();
let c2 = c.trim();
if(t2.length>0 && c2!=""){
  fetch("add_post.php",{
  method:"POST",
  headers:{"Content-Type":"application/json"},
  body:JSON.stringify({title:t2,content:c2})
  }).then(function(r){return r.json();})
  .then(function(rep){
    if(rep.success==true){
    afficherPost({id:rep.id,title:t2,content:c2,created_at:new Date().toISOString(),replies:[]});
    formu.reset();
    } else {
      alert("erreur serveure");
    }
  })
}
})

function afficherPost(pst){
let el = document.createElement("article");
el.classList.add("publication");
el.dataset.id=pst.id;

let h = document.createElement("h3");
h.innerText = pst.title;
let para = document.createElement("p");
para.textContent = pst.content;
let s = document.createElement("small");
s.innerText = "Posté le " + new Date(pst.created_at).toLocaleString();

let divAction = document.createElement("div");
divAction.className = "actions-publication";

let btn1 = document.createElement("button");
btn1.className = "btn btn-outline-success";
btn1.innerHTML = "Répondre";

let btn2 = document.createElement("button");
btn2.setAttribute("class","btn btn-outline-danger");
btn2.innerText = "Signaler";

divAction.appendChild(btn1);
divAction.appendChild(btn2);

let f = document.createElement("form");
f.className = "formulaire-reponse cache";
let ta = document.createElement("textarea");
ta.required = true;
ta.placeholder = "Votre réponse...";
ta.className = "champ-texte";
let bsend = document.createElement("button");
bsend.type = "submit";
bsend.textContent = "Envoyer";
bsend.classList.add("btn","btn-outline-primaire");
f.appendChild(ta);
f.appendChild(bsend);

let divRep = document.createElement("div");
divRep.className = "zone-reponses";

if(pst.replies && pst.replies.length > 0){
  for(var zz = 0; zz < pst.replies.length; zz++){
    var d = document.createElement("div");
    d.className = "reponse";
    d.innerHTML = "<p>"+pst.replies[zz].content+"</p><small>Réponse le "+new Date(pst.replies[zz].created_at).toLocaleString()+"</small>";
    divRep.appendChild(d);
  }
}

btn1.onclick = function(){
  if(f.classList.contains("cache")){
    f.classList.remove("cache");
  } else {
    f.classList.add("cache");
  }
};

f.onsubmit = function(evt){
evt.preventDefault();
  let contenuReponse = ta.value;
if(contenuReponse.trim()!=""){
  fetch("add_reply.php",{
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify({post_id:pst.id,content:contenuReponse})
  }).then(r=>r.json())
  .then(function(r){
    if(r.success){
      let nouv = document.createElement("div");
      nouv.className = "reponse";
      nouv.innerHTML = "<p>"+contenuReponse+"</p><small>Réponse le "+new Date().toLocaleString()+"</small>";
      divRep.appendChild(nouv);
      ta.value="";
      f.classList.add("cache");
    } else {
      alert("erreur");
    }
  })
}
};

btn2.addEventListener("click",function(){
let pourquoi = prompt("Pourquoi ?");
if(pourquoi){
  fetch("report_post.php",{
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify({post_id:pst.id,reason:pourquoi})
  }).then(r=>r.json())
  .then(function(resp){
    if(resp.success){
      alert("merci, bien reçu");
    } else {
      alert("ça n'a pas marché");
    }
  })
}
});

el.appendChild(h);
el.appendChild(para);
el.appendChild(s);
el.appendChild(divAction);
el.appendChild(f);
el.appendChild(divRep);

zonePosts.appendChild(el);
}

});


