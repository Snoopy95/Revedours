function nameFileChange(path, id) {
  var name = path[0].name;
  document.getElementById(id).textContent = name;
  console.log(name)
}
