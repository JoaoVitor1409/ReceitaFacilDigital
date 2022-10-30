$(document).ready(() => {

    // prescription detail popup

    $(".modals").load("/plataforma/components/modalReceita?js=true");

    $(document).on('click', '.detailPrescription', function () {

        let prescriptionCode = $(this).attr("id");

        $(".modal-title").html("Receita número " + prescriptionCode);
        loadMedicines(prescriptionCode);
        $("#detailPrescription").modal("show");
        return false;
    });


    // Search prescriptions

    $(document).on("click", ".searchBtn", () => {
        let data = $(".formSearchPrescription").serialize();

        loadHistoryTable(data);

        return false;
    });



    // Load screens

    // loadScreen("home");
    loadScreen("history");

    $(document).on("click", ".home", () => {
        loadScreen("home");

        return false;
    });
    $(document).on("click", ".prescription", () => {
        loadScreen("prescription");

        return false;
    });
    $(document).on("click", ".history", () => {
        loadScreen("history");

        return false;
    });
    $(document).on("click", ".templates", () => {
        loadScreen("templates");

        return false;
    });
    $(document).on("click", ".settings", () => {
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
            $("." + atualScreen).removeClass("active");
            $("." + screen).addClass("active");
        });
    }

    function loadMedicines(prescriptionCode) {
        $.ajax({
            type: "POST",
            url: "/plataforma/medicamentos",
            data: "js=true&prescriptionCode=" + prescriptionCode,
            dataType:"JSON",
            success: function (result) {
                $(".modal-title").html("Receita número " + prescriptionCode);

                /*
                        <input type="text" class="pacientName" disabled>

                        <input type="text" class="pacientCPF" disabled>

                        <input type="text" class="issueDate" disabled>

                        <input type="text" class="doctorName" disabled>

                */

                $(".pacientName").val(result["pacientName"]);
                $(".pacientCPF").val(result["pacientCPF"]);
                $(".issueDate").val(result["issueDate"]);
                $(".doctorName").val(result["doctorName"]);
                
                $(".medicinesList").html("");
                Object.values(result["medicines"]).forEach((medicine, index)=> {
                    let length = index + 1 + '. ' + medicine["medicineName"] + ' ' + medicine["medicineSize"] + ' ' + medicine["medicineTime"];
                    length = 35 - length.length;
                    let list = index + 1 + '. ' + medicine["medicineName"] + ' ' + medicine["medicineSize"] + ' ';
                    for (let i = 0; i <= length; i++) {
                        list += '_';
                    }
                    list += ' ' + medicine["medicineTime"];
                    if(index != result["medicines"].length - 1){
                        list += '\n';
                    }
                    $(".medicinesList").append(list);
                });
                $(".medicinesList").attr("rows", result["medicines"].length);

                $("#detailPrescription").modal("show");
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function loadHistoryTable(data) {
        $.ajax({
            type: "POST",
            url: "/plataforma/tabelaHistorico",
            data: "js=true&" + data,
            success: function (result) {
                $("tbody").html(result);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
});