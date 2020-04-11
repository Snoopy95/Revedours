slider = document.querySelectorAll("#slider");

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
setInterval(() => slideritems(), 8000);
