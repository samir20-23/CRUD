// Select submit buttons
let submits = document.querySelectorAll(".submit");

// Select input fields
let inputs = document.querySelectorAll("input");

// Element references
let username = document.getElementById("username");
let email = document.getElementById("email");
let password = document.getElementById("password");

// Clear fields function
function clearFields() {
    [username, email, password].forEach(function (e) {
        e.style.background = "";
        e.style.color = "red";
    });
}

// Attach event listeners to submit buttons
submits.forEach(submit => {
    submit.addEventListener("click", function() {
        let request = new XMLHttpRequest();
        request.open("POST", "sql.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(
            "username=" + username.value +
            "&email=" + email.value +
            "&password=" + password.value 
        );
        request.responseType = "document";
        request.onload = () => {
            response = request.response.body.innerHTML;
            console.log(response);  
            if (response.trim().toLowerCase() == "emptyusername") {
                username.style.background = "red";
                clearFields();
            } else if (response.trim().toLowerCase() == "emptyemail") {
                email.style.background = "red";
                clearFields();
            } else if (response.trim().toLowerCase() == "emptypassword" ) {
                password.style.background = "red";
                clearFields();
            } else if ( response.trim().toLowerCase() == "noteconnected") {
                submit.style.background = "red";
                clearFields();
            }
        }; //onload
        this.style.background = ""; // Reset submit button style
    }); //click

    // Attach input event listeners to clear fields on input change
    inputs.forEach(input => {
        input.addEventListener("input", function() {
            clearFields();
        });
    });
});
