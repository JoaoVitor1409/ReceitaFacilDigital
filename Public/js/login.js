$(document).ready(() => {

    // Masks Popup
    $(document).on("focus",".cpfInput", ()=>{
        $(".cpfInput").mask("000.000.000-00");
    });

    $(document).on("focus",".codeInput", ()=>{
        $(".codeInput").mask("000000");
    });

    // See password
    let isPass = true;
    $(document).on("click",".viewPassIcon", () => {
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


    // Change Password

    $(".modals").load("/modals?js=true");

    $("#btnforgotPassword").on("click",() => {
        $("#changePasswordModal").modal("show");
    });

    $(document).on("click","#btnChangePassword", () => {
        $("#changePasswordModal").modal("hide");
        $(".cpfInput").val("");
        $("#VerifyCodeModal").modal("show");

        return false;
    });

    $(document).on("click", "#btnVerifyCode", () => {
        $("#VerifyCodeModal").modal("hide");
        $(".codeInput").val("");
        $("#newPasswordModal").modal("show");

        return false;
    });

    $(document).on("click", "#btnNewPassword", () => {
        alert("Senha alterada com sucesso!")
        $("#newPasswordModal").modal("hide");
        $(".passwordInput").val("");
    });
});