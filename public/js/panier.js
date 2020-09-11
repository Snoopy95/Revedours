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
    ahref.href = "/cart/delcart/" + item.id;
    action.appendChild(ahref);
    ahref.appendChild(icon);
    ahref.addEventListener("click", panier);
  });
}

// --------- PANIER --------------
function panier(event) {
  event.preventDefault();

  const url = this.href;

  axios
    .get(url)
    .then(function (response) {
      // console.log(response.data);
      const prodpanier = response.data.panier;
      const total = response.data.total.TTC;

      // ----  AFFICHAGE DU BADGE ---------
      badge = document.querySelector("#badge");
      badge.textContent = prodpanier.length;
      if (prodpanier.length >= 1) {
        badge.className = "badge badge-pill badge-danger";
        document.querySelector("#vide").className = "text-center cacher";
      
      } else {
        badge.className = "cacher";
        vider = document.querySelector("#vide");
        vider.className = "text-center vu";
        vider.textContent = "Votre panier est vide";
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
      // console.log(error);
      if (error.response.status === 405) {
        alert("Ce produit est déjà dans votre panier");
      } else {
        alert("Une erreur s'est produite");
      }
    });
}
// ----- Ecoute du bouton ajoute panier -------
document.querySelectorAll("a.product-panier").forEach(function (addpanier) {
  addpanier.addEventListener("click", panier);
});
// -------- Ecoute du bouton poubelle -------
document.querySelectorAll("a.delprod").forEach(function (delprods) {
  delprods.addEventListener("click", panier);
});
// -----  Ecoute du bouton annuler panier ---------
document.querySelector("a.removecart").addEventListener("click", panier);
