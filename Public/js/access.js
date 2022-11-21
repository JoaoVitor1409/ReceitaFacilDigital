$(document).ready(() => {

    // Masks Popup
    $(document).on("focus", ".cpfInput", () => {
        $(".cpfInput").mask("000.000.000-00");
    });

    $(document).on("focus", ".codeInput", () => {
        $(".codeInput").mask("000000");
    });

    
    // remove Error
    $(document).on("focusout", ".emailInput", () => removeError("email"));
    $(document).on("focusout", ".passwordInput", () => removeError("password"));


    // Send form
    $(".btnAction").click(() => {

        removeError("email");
        removeError("password");

        if ($(".emailInput").val() == "") {
            error("email", "Email não pode ser vazio");
            $(".emailInput").focus();

            return false;
        }

        if ($(".passwordInput").val() == "") {
            error("password", "Senha não pode ser vazia");
            $(".passwordInput").focus();

            return false;
        }

        login();

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

    function login() {
        let data = $(".formLogin").serialize();

        $.ajax({
            type: "POST",
            url: "/autenticar",
            data: data,
            dataType: "JSON",
            success: function (result) {
                if(result["code"] == 1){
                    window.location.href = "/plataforma";
                }else if(result["code"] == 0){
                    error(result["input"], result["message"]);
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
});