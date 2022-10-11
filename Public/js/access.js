$(document).ready(() => {

    // Masks Popup
    $(document).on("focus", ".cpfInput", () => {
        $(".cpfInput").mask("000.000.000-00");
    });

    $(document).on("focus", ".codeInput", () => {
        $(".codeInput").mask("000000");
    });


    // Send form
    $(".btnAction").click(() => {

        error("email", "Escreve algo");

        return false;
    });


    // Change Password

    $(".modals").load("/components/modals?js=true");

    $("#btnforgotPassword").on("click", () => {
        
        $("#changePasswordModal").modal("show");
    });

    $(document).on("click", "#btnChangePassword", () => {

        if (verifyInput("cpf", 14, "Escreva Certo!")) {
            $("#changePasswordModal").modal("hide");
            $(".cpfInput").val("");
            $("#VerifyCodeModal").modal("show");
        }

        return false;
    });

    $(document).on("click", "#btnVerifyCode", () => {

        if (verifyInput("code", 6, "Escreva Certo!")) {
            $("#VerifyCodeModal").modal("hide");
            $(".codeInput").val("");
            $("#newPasswordModal").modal("show");
        }
        return false;
    });

    $(document).on("click", "#btnNewPassword", () => {

        if (verifyInput("passwordModal", 8, "Senha não é válida!")) {
            alert("Senha alterada com sucesso!")
            $("#newPasswordModal").modal("hide");
            $(".passwordModalInput").val("");
        }

        removeError("password");

        return false;
    });


    function verifyInput(input, length, msg) {
        if ($("." + input + "InputModal").val().length < length) {
            error(input, msg);

            $(".inputModal").css("border-color", "transparent");

            return false;
        }

        removeError(input);
        $(".inputModal").css("border-color", "#8E9398");
        return true;
    }
});