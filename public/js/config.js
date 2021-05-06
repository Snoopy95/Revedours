const switch_on = document.querySelector("#online")
if (switch_on) {switch_on.addEventListener('click', switchonline)}

function switchonline() {
    axios
        .post("admin/online")
        .then(function (response) {
            console.log(response.data)
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                console.log(error.response.data)
            }
        })
}
