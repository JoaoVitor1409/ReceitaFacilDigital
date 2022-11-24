$(document).ready(() => {

    // Masks Popup
    $(document).on("focus", ".codeInput", () => {
        $(".codeInput").mask("000000");
    });


    // remove Error
    $(document).on("focusout", ".emailInput", () => {
        removeError("email");

        $(".inputModal").css("border-color", "#8E9398");
    });
    $(document).on("focusout", ".passwordInput", () => removeError("password"));
    $(document).on("focusout", ".codeInput", () => {
        removeError("code");

        $(".inputModal").css("border-color", "#8E9398");
    });


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

        removeError("email");
        $(".emailInputModal").focus();
        $("#changePasswordModal").modal("show");
    });

    $(document).on("click", "#btnChangePassword", () => {

        if (verifyInput("emailModal", 1, "Email não pode ser vazio")) {
            getUser($(".emailInputModal").val());
            $(".emailInputModal").val("");
        }

        return false;
    });

    $(document).on("click", "#btnVerifyCode", () => {

        if (verifyInput("code", 6, "O código deve ter 6 digítos")) {
            verifyCode($(".codeInput").val());
            $(".codeInput").val("");
        }
        return false;
    });

    $(document).on("click", "#btnNewPassword", () => {

        if (verifyInput("passwordModal", 5, "A nova senha deve ter pelo menos 5 caracteres!")) {
            changePassword($(".passwordModalInput").val());
            $(".passwordModalInput").val("");
        }

        removeError("password");

        return false;
    });


    function verifyInput(input, length, msg) {
        if ($("." + input + "Input").val().length < length) {
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
                if (result["code"] == 1) {
                    window.location.href = "/plataforma";
                } else if (result["code"] == 0) {
                    error(result["input"], result["message"]);
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function getUser(email) {
        data = "email=" + email
        $("#changePasswordModal").modal("hide");
        $("#VerifyCodeModal").modal("show");
        $(".codeInput").focus();

        $.ajax({
            type: "POST",
            url: "/enviarCodigo",
            data: data,
            success: function (result) {
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function verifyCode(code) {
        data = "code=" + code
        $.ajax({
            type: "POST",
            url: "/verificarCodigo",
            data: data,
            dataType: "JSON",
            success: function (result) {
                if (result["code"] == 1) {
                    $("#VerifyCodeModal").modal("hide");
                    $("#newPasswordModal").modal("show");
                    $(".passwordModal").focus();
                } else if (result["code"] == 0) {
                    verifyInput(result["input"], 7, result["message"]);
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function changePassword(password) {
        data = "password=" + password;
        $.ajax({
            type: "POST",
            url: "/alterarSenha",
            data: data,
            success: function (result) {
                alert("Senha alterada com sucesso!")
                $("#newPasswordModal").modal("hide");
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
});