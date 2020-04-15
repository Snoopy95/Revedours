function creatpanier(prodpanier) {
  prodpanier.forEach((item) => {
    body = document.querySelector("#panierbody");
    ligne = document.createElement("tr");
    ligne.id = "prod" + item.id;
    body.appendChild(ligne);
    nam = document.createElement("th");
    nam.scope = "row";
    nam.textContent = item.name;
    ligne.appendChild(nam);
    price = document.createElement("td");
    price.className = "text-right";
    price.textContent = item.price + "€";
    ligne.appendChild(price);
    action = document.createElement("td");
    action.className = "text-center";
    ligne.appendChild(action);
    icon = document.createElement("i");
    icon.className = "fa fa-trash-alt fa-1x";
    ahref = document.createElement("a");
    ahref.className = "delprod";
    ahref.href = "/panier/delprod/" + item.id;
    action.appendChild(ahref);
    ahref.appendChild(icon);
    ahref.addEventListener("click", addpanier);
  });
}

// --------- PANIER --------------

// ------ Ajout d'un produit dans le panier ---------
function addpanier(event) {
  event.preventDefault();

  const url = this.href;

  axios
    .get(url)
    .then(function (response) {
      console.log(response.data);
      const countpanier = response.data.countpanier;
      const prodpanier = response.data.panier;
      const total = response.data.total;

      // ----  AFFICHAGE DU BADGE ---------
      badge = document.querySelector("#badge");
      badge.textContent = countpanier;
      if (countpanier >= 1) {
        badge.className = "badge badge-pill badge-danger";
      } else {
        badge.className = "cacher";
      }

      // ---- AFFICHAGE DU PANIER -------
      panierbody = document.querySelectorAll("#panierbody tr");
      paniertotal = document.querySelector("#paniertotal");
      paniertotal.textContent = total + "€";

      if (panierbody.length > 0) {
        panierbody.forEach((item) => item.remove());
      }
      creatpanier(prodpanier);
    })
    .catch(function (error) {
      console.log(error);
      if (error.response.status === 405) {
        window.alert("Ce produit est déjà dans votre panier");
      } else {
        window.alert("Une erreur s'est produite");
      }
    });
}
// ----- Ecoute du bouton ajoute panier -------
document.querySelectorAll("a.product-panier").forEach(function (panier) {
  panier.addEventListener("click", addpanier);
});
// -------- Ecoute du bouton poubelle -------
document.querySelectorAll("a.delprod").forEach(function (delprods) {
  delprods.addEventListener("click", addpanier);
});

// -----  Function en attente ---------
function delpanier() {
  console.log("je supprimer le panier");
}
