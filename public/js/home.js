// ----- Burger Button  push body --------------
const header_icon = document.querySelector("#header__icon")
if (header_icon) {
  header_icon.addEventListener("click", evt => {
    evt.preventDefault();
    document.querySelector("body").classList.toggle("with--sidebar");
  });
}

//  -------  return body --------------------------
const site_cache = document.querySelector("#site-cache")
if (site_cache) {
  site_cache.addEventListener("click", evt => {
    evt.preventDefault();
    document.querySelector("body").classList.remove("with--sidebar");
  });
}

// --------- Show password ---------
document.querySelectorAll(".btnpwd").forEach(function (selected) {
  selected.addEventListener("click", showpwd)
})
function showpwd() {
  inputpwd = document.querySelector("." + this.dataset.btn)
  inputpwd.type == 'password' ? inputpwd.type = 'text' : inputpwd.type = 'password';
  iconpwd = this.querySelector('.iconpwd')
  iconpwd.classList.toggle("fa-eye")
  iconpwd.classList.toggle("fa-eye-slash")
}

// ---------- time affichage message ------
setTimeout(() => {
  var alertList = document.querySelectorAll('.alert')
  alertList.forEach(function (alert) {
    new bootstrap.Alert(alert).close()
  })
}, 5000);

// -----------  Slide commentaire ----------
const slider = document.querySelectorAll("#slider");
function slideritems() {
  for (i = 0; i < slider.length; i++) {
    if (slider[i].classList == "view") {
      k = i;
      slider[k].classList.toggle("view");
      slider[k].classList.toggle("noview");
      k++;
      if (k > slider.length - 1) {
        k = 0;
      }
      slider[k].classList.toggle("noview");
      slider[k].classList.toggle("view");
      break;
    }
  }
}
if (slider) {
  setInterval(() => slideritems(), 8000);
}
