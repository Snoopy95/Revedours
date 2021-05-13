const pwd = document.querySelector('.show')
// --------- Show password ---------
    pwd.addEventListener("click", evt => {
    evt.preventDefault();
    showpwd = document.querySelector(".show-pwd")
    console.log('je click', showpwd.type)
    showpwd.type =='password' ? showpwd.type = 'text' : showpwd.type= 'password';
    iconshow= document.querySelector('.iconshow')
    // console.log(iconshow.classList)
    iconshow.classList.toggle("fa-eye")
    iconshow.classList.toggle("fa-eye-slash")
  });

  const pwdconf = document.querySelector('.showconf')
  // --------- Show confirmation password ---------
    pwdconf.addEventListener("click", evt => {
    evt.preventDefault();
    showconfpwd = document.querySelector(".showconf-pwd")
    // console.log('je click', showconfpwd.type)
    showconfpwd.type =='password' ? showconfpwd.type = 'text' : showconfpwd.type= 'password';
    iconshow= document.querySelector('.iconshowconf')
    // console.log(iconshowconf.classList)
    iconshow.classList.toggle("fa-eye")
    iconshow.classList.toggle("fa-eye-slash")
  });
  