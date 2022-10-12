$(document).ready(() => {

    // Masks
    $(".cpfInput").mask("000.000.000-00");
    $(".phoneInput").mask("(00)00000-0000");


    // Send form
    $(".btnAction").click(() => {

        error("name", "Insira seu nome completo");

        return false;
    });


    // Alter type for input Date
    let birthDateInput = $(".birthDateInput");
    birthDateInput.focus(() => {
        birthDateInput.val("");
        birthDateInput.attr("type", "date");
        removeError("birthDate");
    });

    birthDateInput.focusout(() => {
        if (birthDateInput.val() != "") {
            let birthDateFormated = birthDateInput.val();
            let year = birthDateFormated.split("-")[0];
            let curYear = new Date().getFullYear();
            if (year.length != 4 || year < 1900 || year >= curYear) {
                error("birthDate", "Insira uma data válida");
                return;
            }

            birthDateInput.attr("type", "text");
            if (birthDateFormated != "") {
                birthDateFormated = birthDateFormated.replace(/(\d*)-(\d*)-(\d*).*/, '$3/$2/$1');
            }
            birthDateInput.val(birthDateFormated);
        }
    });
});