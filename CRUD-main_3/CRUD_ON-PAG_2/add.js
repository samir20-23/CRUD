let submit = document.getElementById("submit");
let inputs = document.querySelectorAll("input");

// Error elements
let errtourName = document.getElementById("errtourName");
let errtourImgg = document.getElementById("errtourImgg");
let verified = document.getElementById("verified");

// Loader
let loader = document.querySelector(".loader");

// Elements
let tourName = document.getElementById("tourName");
let tourPrice = document.getElementById("tourPrice");
let tourDescription = document.getElementById("tourDescription");
let touradddescription = document.getElementById("touradddescription");
let tourImgg = document.getElementById("tourImgg");

// Clear errors
function clear() {
    errtourName.innerHTML = "";
    errtourImgg.innerHTML = "";
}

submit.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(document.getElementById("form"));

    let request = new XMLHttpRequest();
    request.open("POST", "add.php");

    // No need to set content-type, FormData handles it automatically

    request.send(formData);
    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            let response = request.responseText.trim().toLowerCase();
            console.log(response)
            if (response === "tournameempty") {
                loader.style.display = "none";
                clear();
                errtourName.innerHTML = "Tour name is empty!";
            } else if (response === "tourimgeempty") {
                loader.style.display = "none";
                clear();
                errtourImgg.innerHTML = "Tour image is empty!";
            } else if (response === "notverified") {
                loader.style.display = "none";
                clear();
                verified.innerHTML = "Error 404!";
                verified.style.color = "red";
                verified.style.textShadow = "1px 1px 12px #e7422c";
                setTimeout(function () {
                    window.location.replace("error404.php");
                }, 999);
            } else if (response === "verified") {
                loader.style.display = "block";
                clear();
                let iconVerified = document.createElement("img");
                iconVerified.setAttribute("src", "img/check.gif");
                iconVerified.id = "iconVerified";
                verified.appendChild(iconVerified);
                setTimeout(function () {
                    window.location.replace("sql.php");
                }, 999);
            }
        }
    };

    loader.style.display = "block";
});

inputs.forEach((input) => {
    input.addEventListener("input", function () {
        clear();
    });
});
