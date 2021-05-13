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
const pwdshow = document.querySelector('.mshow')
if (pwdshow) {
  pwdshow.addEventListener("click", evt => {
    evt.preventDefault();
    mshowpwd = document.querySelector(".mshow-pwd")
    // console.log('je click', showpwd.type)
    mshowpwd.type == 'password' ? mshowpwd.type = 'text' : mshowpwd.type = 'password';
    miconshow = document.querySelector('.miconshow')
    // console.log(iconshow.classList)
    miconshow.classList.toggle("fa-eye")
    miconshow.classList.toggle("fa-eye-slash")
  });
}

setTimeout(() => {
  $(".alert").alert("close");
}, 5000);
