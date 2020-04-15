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
