$(document).ready(() => {

    // Send form
    $(document).on("focus", ".emailInput", () => {

    });


    // See password
    let isPass = true;
    $(document).on("click", ".viewPassDiv", () => {
        if (isPass) {
            $(".passwordInput").attr("type", "text");
            isPass = false;
            $(".viewPassIcon").html("visibility_off");
        } else {
            $(".passwordInput").attr("type", "password");
            isPass = true;
            $(".viewPassIcon").html("visibility");
        }
    });
});

function error(input, msg) {
    let password = input == "password" || input == "passwordModal" ? "Password" : "";

    $("." + input + "Input").addClass("inputError" + password);
    $("." + input + "Icon").addClass("inputIconError");

    password == "Password" ? $("." + input + "IconView").addClass("inputIconError" + password) : "";

    $("." + input + "Error").removeClass("d-none");
    $("." + input + "ErrorMsg").text(msg);
}

function removeError(input) { 
    let password = input == "password" || input == "passwordModal" ? "Password" : "";

    $("." + input + "Input").removeClass("inputError" + password);
    $("." + input + "Icon").removeClass("inputIconError");

    $("." + input + "IconView").removeClass("inputIconError" + password);

    $("." + input + "Error").addClass("d-none");
 }