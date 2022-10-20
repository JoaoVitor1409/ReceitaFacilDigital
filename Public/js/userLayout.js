$(document).ready(() => {

    // prescription detail popup

    $(".modals").load("/plataforma/components/modalReceita?js=true");

    $(document).on('click', '.detailPrescription', function () {
        
        let prescriptionCode = $(this).attr("id");

        $(".modal-title").html("Receita número " + prescriptionCode)
        $("#detailPrescription").modal("show");
        return false;
    });



    // Load screens
    loadScreen("home");
    // loadScreen("history");

    $(document).on("click",".home", () => {
        loadScreen("home");

        return false;
    });
    $(document).on("click",".prescription", () => {
        loadScreen("prescription");

        return false;
    });
    $(document).on("click",".history", () => {
        loadScreen("history");

        return false;
    });
    $(document).on("click",".templates", () => {
        loadScreen("templates");

        return false;
    });
    $(document).on("click",".settings", () => {
        loadScreen("settings");

        return false;
    });


    function loadScreen(screen) {
        let screens = ["home", "prescription", "history", "templates", "settings"]

        $.ajax({
            type: "POST",
            url: "/plataforma/loadScreen",
            data: "screen=" + screen,
            success: function (result) {
                $(".content").html(result);
            },
            error: function (error) {
                console.log(error);
            }
        });

        screens.forEach(atualScreen => {
            $("."+atualScreen).removeClass("active");
            $("."+screen).addClass("active");
        });
    }
});