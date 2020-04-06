// ----- Burger Button  push body --------------
document.querySelector("#header__icon").addEventListener("click", evt => {
  evt.preventDefault();
  document.querySelector("body").classList.toggle("with--sidebar");
});

//  -------  return body --------------------------
document.querySelector("#site-cache").addEventListener("click", evt => {
  evt.preventDefault();
  document.querySelector("body").classList.remove("with--sidebar");
});

// --------- Show password ---------
document.querySelector('.show').addEventListener("click", evt => {
  evt.preventDefault();
  showpwd = document.querySelector(".show-pwd")
  // console.log('je click', showpwd.type)
  showpwd.type =='password' ? showpwd.type = 'text' : showpwd.type= 'password';
  iconshow= document.querySelector('.iconshow')
  // console.log(iconshow.classList)
  iconshow.classList.toggle("fa-eye")
  iconshow.classList.toggle("fa-eye-slash")
});
// --------- Show confirmation password ---------
document.querySelector('.showconf').addEventListener("click", evt => {
  evt.preventDefault();
  showconfpwd = document.querySelector(".showconf-pwd")
  // console.log('je click', showconfpwd.type)
  showconfpwd.type =='password' ? showconfpwd.type = 'text' : showconfpwd.type= 'password';
  iconshow= document.querySelector('.iconshowconf')
  // console.log(iconshowconf.classList)
  iconshow.classList.toggle("fa-eye")
  iconshow.classList.toggle("fa-eye-slash")
});

// --------- PANIER --------------
addPanier = id => {
  var xhttp = new XMLHttpRequest();
  console.log(id);

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.querySelector("#reponsepanier").innerHTML = this.responseText;
      console.log(this.responseText);
      document.querySelector("#badge").textContent ++
      // document.location.reload(true);
      badgePanier();
    }
  };

  xhttp.open("GET", "././inc/funcPanier.php?ajout=" + id, true);
  xhttp.send();
};

supprimerProd = id => {
  var xhttp = new XMLHttpRequest();
  console.log(id);

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.querySelector("#reponsepanier").innerHTML = this.responseText;
      document.querySelector("#badge").textContent --
      badgePanier();
    }
  };

  xhttp.open("GET", "././inc/funcPanier.php?supp=" + id, true);
  xhttp.send();
};

supprimePanier = () => {
  var xhttp = new XMLHttpRequest();
  console.log("je supprime");

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.querySelector("#reponsepanier").innerHTML = this.responseText;
    }
  };

  xhttp.open("GET", "././inc/funcPanier.php?suppPanier", true);
  xhttp.send();
};

badgePanier = () => {
  console.log("c'est le badge ici");
  const badge = document.querySelector("#badge");
  if (badge.textContent >= 1) {
    badge.className = "vu badge badge-danger";
  } else {
    badge.className = "cacher";
  }
};

badgePanier();
