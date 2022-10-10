$(document).ready(() => {

    // Masks
    $(".cpfInput").mask("000.000.000-00");
    $(".phoneInput").mask("(00)00000-0000");


    // Send form
    $(".btnAction").click(() => {
        
        error("name", "nome");
        error("cpf", "cpf");
        error("email", "email");
        error("phone", "phone");
        error("password", "password");

        return false;
    });
});