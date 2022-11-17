$(document).ready(() => {
    // Masks

    $(document).on("focus", ".cpfInput", () => {
        $(".cpfInput").mask("000.000.000-00");
    });

    $(document).on("focus", ".phoneInput", () => {
        $(".phoneInput").mask("(00)00000-0000");
    });

    $(document).on("focus", ".medicineSizeInput", function () {
        $(this).mask("0#");
    });

    $(document).on("focus", ".medicineFrequencyInput", function () {
        $(this).mask("0#");
    });

    // Datalists

    // Medicine Size Type
    var dataSizeType;
    $(document).on("keyup", ".medicineSizeInput", function () {
        let val = $(this).val();
        val = val.replace(/cp|ml|gotas/, "");
        val = val.replace(/\s/, "");

        dataSizeType = [
            { value: val + "cp" },
            { value: val + "ml" },
            { value: val + " gotas" },
        ];
    });

    $(document).on("focus click", ".medicineSizeInput", function () {
        let val = $(this).val();
        val = val.replace(/cp|ml|gotas/, "");
        val = val.replace(/\s/, "");

        dataSizeType = [
            { value: val + "cp" },
            { value: val + "ml" },
            { value: val + " gotas" },
        ];

        if (!$(this).data("autocomplete")) {
            $(this).autocomplete({
                minLength: 0,
                source: function (_, response) {
                    response(dataSizeType);
                },
                select: function (_, { item }) {
                    $(this).val(item.value);
                    return false;
                },
            });
        }

        $(this).eq(0).select();
        $(this).autocomplete("search");
    });

    // Medicine Frequency Type
    var dataFrequencyTypel;
    $(document).on("keyup", ".medicineFrequencyInput", function () {
        let val = $(this).val();
        val = val.replace(/hora|vez ao dia|horas|vezes ao dia/, "");
        val = val.replace(/\s/, "");
        if (val == 1) {
            dataFrequencyType = [
                { value: val + " hora" },
                { value: val + " vez ao dia" },
            ];
        } else {
            dataFrequencyType = [
                { value: val + " horas" },
                { value: val + " vezes ao dia" },
            ];
        }
    });

    $(document).on("focus click", ".medicineFrequencyInput", function () {
        let val = $(this).val();
        val = val.replace(/hora|vez ao dia|horas|vezes ao dia/, "");
        val = val.replace(/\s/, "");

        if (val == 1) {
            dataFrequencyType = [
                { value: val + " hora" },
                { value: val + " vez ao dia" },
            ];
        } else {
            dataFrequencyType = [
                { value: val + " horas" },
                { value: val + " vezes ao dia" },
            ];
        }

        if (!$(this).data("autocomplete")) {
            $(this).autocomplete({
                minLength: 0,
                source: function (_, response) {
                    response(dataFrequencyType);
                },
                select: function (_, { item }) {
                    $(this).val(item.value);
                    return false;
                },
            });
        }

        $(this)[0].setSelectionRange(0, val.length);
        $(this).autocomplete("search");
    });

    // Medicine OBS Type
    var dataObsType = [
        { value: "Tomar durante X dias" },
        { value: "Tomar após o café da manhã" },
        { value: "Tomar após o almoço" },
        { value: "Tomar após a janta" },
        { value: "Tomar após as refeições" },
    ];

    $(document).on("focus click", ".medicineObsInput", function () {
        if (!$(this).data("autocomplete")) {
            $(this).autocomplete({
                minLength: 0,
                source: function (_, response) {
                    response(dataObsType);
                },
                select: function (_, { item }) {
                    $(this).val(item.value);
                    return false;
                },
            });
        }
        $(this).autocomplete("search");
    });


    // search Template

    $(document).on("click", ".searchBtnTemplates", () => {
        loadTemplates();

        return false;
    });


    // edit Template

    $(document).on("click", ".templateCard", function () {
        openTemplate($(this).attr("id"));
        $("#template").modal("show");
    });

    $(document).on("click", ".btnTemplate", () => {
        removeError("templateNameModal");
        if ($(".templateNameModalInput").val().length == 0) {
            error("templateNameModal", "Nome não pode ser vazio");
            $(".templateNameModalInput").focus();

            return false;
        }

        if (!validEmitPrescription(false)) {
            $("#saveTemplate").modal("hide");

            return false;
        }

        let data = $(".formTemplate").serialize();
        saveTemplate(data);
        loadTemplates();
        $("#template").modal("hide");

        return false;
    });


    // delete Template

    $(document).on("click", ".btnDeleteTemplate", function () {
        deleteTemplate($(this).attr("id"));
        loadTemplates();
        $("#template").modal("hide");

        return false;
    });


    // create Template

    $(document).on("click", ".createTemplateBtn", function () {
        $("#template").modal("show");

        return false;
    });


    // select Template popup

    $(document).on("click", ".useTemplate", () => {
        loadTemplateList();
        $("#useTemplate").modal("show");
    });

    $(document).on("click", ".searchBtnTemplate", () => {
        loadTemplateList();

        return false;
    });

    $(document).on("click", ".template", function () {
        id = $(this).attr("id");
        getTemplate(id);

        $("#useTemplate").modal("hide");

        return false;
    });

    // save Template popup


    $(document).on("click", ".saveTemplate", () => {
        $("#saveTemplate").modal("show");

        return false;
    });

    $(document).on("focusout", ".templateNameInput", () =>
        removeError("templateName")
    );

    $(document).on("click", ".btnSaveTemplate", () => {
        removeError("templateName");
        if ($(".templateNameInput").val().length == 0) {
            error("templateName", "Nome não pode ser vazio");
            $(".templateNameInput").focus();

            return false;
        }

        if (!validEmitPrescription(false)) {
            $("#saveTemplate").modal("hide");

            return false;
        }

        let data = $(".formSaveTemplate").serialize() + "&";
        data += $(".formAddPrescription").serialize();
        console.log(data)
        saveTemplate(data);
        $("#saveTemplate").modal("hide");

        return false;
    });


    // prescription detail popup

    $(document).on("click", ".cardSrcOption", function () {
        let srcMethod = $(this).attr("id");
        if (srcMethod == "CPF") {
            $(".modalBody").removeClass("hidden");
            $(".notFound").addClass("hidden");
            $(".cpfInput").val("");
            $(".pacientName").val("");
            $(".pacientCPF").val("");
            $(".issueDate").val("");
            $(".doctorName").val("");

            $(".medicinesList").html("");

            $(".formSearchPrescription").removeClass("hidden");
            $(".modalTitle").addClass("hidden");
            $("#detailPrescription").modal("show");
        } else if (srcMethod == "qrCode") {
            alert(srcMethod);
            let code = "P14";
            $(".modalTitle").html("Receita número " + code);

            $(".formSearchPrescription").addClass("hidden");
            $(".modalTitle").removeClass("hidden");
            $("#detailPrescription").modal("show");
            getPrescription(code);
        }
        return false;
    });

    // Search prescription popup


    $(document).on("click", ".detailPrescription", function () {
        let prescriptionCode = $(this).attr("id");

        $(".modalTitle").html("Receita número " + prescriptionCode);
        loadMedicines(prescriptionCode);
        $("#detailPrescription").modal("show");
        return false;
    });

    // Search prescriptions on table

    $(document).on("click", ".searchBtn", () => {
        loadHistoryTable();

        return false;
    });

    //Search prescription

    $(document).on("click", ".searchBtnCPF", () => {
        let cpf = $(".cpfInput").val();
        getPrescription(cpf);

        return false;
    });

    // Pagination

    $(document).on("click", ".pageNumber", function () {
        $(".pageNumber").removeClass("active");
        $(this).addClass("active");

        paginationStyle();
    });

    $(document).on("click", ".pagePrevious", () => {
        if (!$(".pagePrevious").hasClass("disabled")) {
            let curPage = $(".pagination .active");
            let previousPageNumber = parseInt(curPage.text(), 10) - 1;

            $("." + previousPageNumber).addClass("active");
            curPage.removeClass("active");

            paginationStyle();
        }
    });

    $(document).on("click", ".pageNext", () => {
        if (!$(".pageNext").hasClass("disabled")) {
            let curPage = $(".pagination .active");
            let nextPageNumber = parseInt(curPage.text(), 10) + 1;

            $("." + nextPageNumber).addClass("active");
            curPage.removeClass("active");

            paginationStyle();
        }
    });

    // Create Prescriptions btns

    $(document).on("click", ".removeMedicine", function () {
        removeMedicineForm(this);

        return false;
    });

    $(document).on("click", ".addMedicine", () => {
        addMedicineForm();

        return false;
    });

    $(document).on("focusout", ".cpfFormInput", () => {
        searchPacient();
    });

    // Emit Prescription
    $(document).on("focusout", ".cpfFormInput", () => removeError("cpfForm"));
    $(document).on("focusout", ".phoneInput", () => removeError("phone"));
    $(document).on("focusout", ".medicineNameInput", () =>
        removeError("medicineName")
    );
    $(document).on("focusout", ".medicineSizeInput", () =>
        removeError("medicineSize")
    );
    $(document).on("focusout", ".medicineFrequencyInput", () =>
        removeError("medicineFrequency")
    );

    $(document).on("click", ".btnEmit", () => {
        if (validEmitPrescription(true)) {
            emitPrescription();
        }

        return false;
    });

    // Load screens

    loadScreen("home");

    $(document).on("click", ".home", () => {
        removeModals();
        loadScreen("home");

        return false;
    });
    $(document).on("click", ".prescription", () => {
        removeModals();
        addModal(["modalReceita", "modalSalvarModelo", "modalSelecionarModelo"])
        loadScreen("prescription");


        return false;
    });
    $(document).on("click", ".history", () => {
        removeModals();
        addModal(["modalReceita"])
        loadScreen("history");
        loadHistoryTable();

        return false;
    });

    $(document).on("click", ".templates", () => {
        removeModals();
        addModal(["modalModelo"])
        loadScreen("templates");
        loadTemplates();

        return false;
    });
    $(document).on("click", ".settings", () => {
        removeModals();
        loadScreen("settings");

        return false;
    });

    function loadScreen(screen) {
        let screens = ["home", "prescription", "history", "templates", "settings"];

        $.ajax({
            type: "POST",
            url: "/plataforma/loadScreen",
            data: "screen=" + screen,
            success: function (result) {
                $(".content").html(result);
            },
            error: function (error) {
                console.log(error);
            },
        });

        screens.forEach((atualScreen) => {
            $("." + atualScreen).removeClass("active");
            $("." + screen).addClass("active");
        });
    }

    function addModal(modalArray) {
        modalArray.forEach(modalName => {
            modal = "/plataforma/components/" + modalName + "?js=true";
            $(".modals").append("<div class='" + modalName + "'></div>");
            $("." + modalName).load(modal);
        });

    }

    function removeModals() {
        $(".modals").html("");
    }

    function loadMedicines(prescriptionCode) {
        $.ajax({
            type: "POST",
            url: "/plataforma/medicamentos",
            data: "js=true&prescriptionCode=" + prescriptionCode,
            dataType: "JSON",
            success: function (result) {
                $(".modal-title").html("Receita número " + prescriptionCode);

                $(".pacientName").val(result["pacientName"]);
                $(".pacientCPF").val(result["pacientCPF"]);
                $(".issueDate").val(result["issueDate"]);
                $(".doctorName").val(result["doctorName"]);

                $(".medicinesList").html("");
                Object.values(result["medicines"]).forEach((medicine, index) => {
                    let length =
                        index +
                        1 +
                        ". " +
                        medicine["medicineName"] +
                        " " +
                        medicine["medicineSize"] +
                        " " +
                        medicine["medicineFrequency"];
                    length = 35 - length.length;
                    let list =
                        index +
                        1 +
                        ". " +
                        medicine["medicineName"] +
                        " " +
                        medicine["medicineSize"] +
                        " ";
                    for (let i = 0; i <= length; i++) {
                        list += "_";
                    }
                    list += " " + medicine["medicineFrequency"];
                    if (index != result["medicines"].length - 1) {
                        list += "\n";
                    }
                    $(".medicinesList").append(list);
                });
                $(".medicinesList").attr("rows", result["medicines"].length);

                $("#detailPrescription").modal("show");
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function addMedicineForm() {
        $("#medicinesData").append($(".medicineData")[0].outerHTML);
        let removeBtn = $(".medicinesData")[0].lastChild;
        removeBtn = $(removeBtn)[0].firstChild.nextSibling;
        $(removeBtn).removeClass("hidden");
    }

    function removeMedicineForm(removeBtn = ".removeMedicine:not(.hidden)") {
        $(removeBtn).parent().remove();
    }

    function loadHistoryTable(page = 1) {
        let data = $(".formSearchPrescription").serialize();
        $.ajax({
            type: "POST",
            url: "/plataforma/tabelaHistorico",
            data:
                "js=true&page=" +
                page +
                "&paginationStyle=" +
                paginationStyle +
                "&" +
                data,
            success: function (result) {
                $(".tableDiv").html(result);
                if (page != 1) {
                    $(".pagePrevious").removeClass("disabled");
                } else {
                    $(".pagePrevious").addClass("disabled");
                }

                if (page != $(".pageNumber").length) {
                    $(".pageNext").removeClass("disabled");
                } else {
                    $(".pageNext").addClass("disabled");
                }

                if (!result) {
                    $(".paginationMenu").remove();
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function loadTemplateList() {
        let templateName = $(".formSearchTemplate").serialize();
        $.ajax({
            type: "POST",
            url: "/plataforma/listaModelos",
            data: templateName,
            dataType: "JSON",
            success: function (result) {
                if (result["notFound"]) {
                    $(".templatesList").html("");
                    $(".messageTemplateSelect").html("Nenhum template encontrado");
                } else {
                    $(".messageTemplateSelect").html("");
                    $(".templatesList").html("");
                    Object.values(result).forEach((template) => {
                        let page =
                            `
                <div class="template row mb-2" id="` +
                            template.templateId +
                            `">
                    <div class="row">
                        <h1 class="title">` +
                            template.templateName +
                            `</h1></div>
                        <div class="row">
                        <p class="medicinesDesc">
            `;

                        for (let i = 0; i < template.templateMedicines.length; i++) {
                            if (i != template.templateMedicines.length - 1) {
                                page += template.templateMedicines[i].medicineName + ";";
                            } else {
                                page += template.templateMedicines[i].medicineName;
                            }
                        }

                        page += "</p></div></div>";

                        $(".templatesList").append(page);
                    });
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function loadTemplates() {
        let templateName = $(".formSearchTemplates").serialize();
        $.ajax({
            type: "POST",
            url: "/plataforma/listaModelos",
            data: templateName,
            dataType: "JSON",
            success: function (result) {
                if (result["notFound"]) {
                    $(".templatesCards").html("");
                    $(".messageTemplateSelect").html("Nenhum template encontrado");
                } else {
                    $(".messageTemplateSelect").html("");
                    $(".templatesCards").html("");
                    Object.values(result).forEach((template) => {
                        let page = `
                            <div class="templateCard card col-md-3 mb-2"  id="` + template.templateId + `">
                                <div class="row">
                                    <h1 class="title card-header text-center">` + template.templateName + `</h1></div>
                                    <div class="row">
                                    <ul class="list-group list-group-flush">
                        `;

                        for (let i = 0; i < template.templateMedicines.length; i++) {
                            page += '<li class="list-group-item listItem">';
                            if (i != template.templateMedicines.length - 1) {
                                page += template.templateMedicines[i].medicineName + ";";
                            } else {
                                page += template.templateMedicines[i].medicineName;
                            }
                            page += '</li>'
                        }
                        page += '</ul></div></div>';


                        $(".templatesCards").append(page);
                    });
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function paginationStyle() {
        let curPage = parseInt($(".pagination .active").text(), 10);

        loadHistoryTable(curPage);
    }

    function getPrescription(code) {
        let key = "";

        if (code.length == 14) {
            key = "cpf=" + code;
        } else {
            key = "code=" + code;
        }
        $(".modalBody").removeClass("hidden");
        $(".notFound").addClass("hidden");

        $.ajax({
            type: "POST",
            url: "/plataforma/pesquisaReceita",
            data: key,
            dataType: "JSON",
            success: function (result) {
                if (result["pacientName"]) {
                    $(".pacientName").val(result["pacientName"]);
                    $(".pacientCPF").val(result["pacientCPF"]);
                    $(".issueDate").val(result["issueDate"]);
                    $(".doctorName").val(result["doctorName"]);

                    $(".medicinesList").html("");
                    Object.values(result["medicines"]).forEach((medicine, index) => {
                        let length =
                            index +
                            1 +
                            ". " +
                            medicine["medicineName"] +
                            " " +
                            medicine["medicineSize"] +
                            " " +
                            medicine["medicineFrequency"];
                        length = 35 - length.length;
                        let list =
                            index +
                            1 +
                            ". " +
                            medicine["medicineName"] +
                            " " +
                            medicine["medicineSize"] +
                            " ";
                        for (let i = 0; i <= length; i++) {
                            list += "_";
                        }
                        list += " " + medicine["medicineFrequency"];
                        if (index != result["medicines"].length - 1) {
                            list += "\n";
                        }
                        $(".medicinesList").append(list);
                    });
                    $(".medicinesList").attr("rows", result["medicines"].length);
                } else {
                    $(".modalBody").addClass("hidden");
                    $(".notFound").removeClass("hidden");
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function getTemplate(id) {
        id = "templateId=" + id;
        $.ajax({
            type: "POST",
            url: "/plataforma/pesquisaTemplate",
            data: id,
            dataType: "JSON",
            success: function (result) {
                removeMedicineForm();
                result = result.templateMedicines
                for (let i = 0; i < Object.values(result).length; i++) {
                    let medicineName = result[i].medicineName;
                    let medicineSize = result[i].medicineSize;
                    let medicineFrequency = result[i].medicineFrequency;
                    let medicineObs = result[i].medicineObs;

                    $(".medicineNameInput").eq(i).val(medicineName);
                    $(".medicineSizeInput").eq(i).val(medicineSize);
                    $(".medicineFrequencyInput").eq(i).val(medicineFrequency);
                    $(".medicineObsInput").eq(i).val(medicineObs);

                    if (i != Object.values(result).length - 1) {
                        addMedicineForm();
                    }
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function openTemplate(id) {
        id = "templateId=" + id;
        $.ajax({
            type: "POST",
            url: "/plataforma/pesquisaTemplate",
            data: id,
            dataType: "JSON",
            success: function (result) {

                removeMedicineForm();
                $("#template .modal-title").html("Modelo <b>" + result.templateName + "</b>")
                $(".templateNameModalInput").val(result.templateName)
                result = result.templateMedicines
                for (let i = 0; i < Object.values(result).length; i++) {
                    let medicineName = result[i].medicineName;
                    let medicineSize = result[i].medicineSize;
                    let medicineFrequency = result[i].medicineFrequency;
                    let medicineObs = result[i].medicineObs;

                    $(".medicineNameInput").eq(i).val(medicineName);
                    $(".medicineSizeInput").eq(i).val(medicineSize);
                    $(".medicineFrequencyInput").eq(i).val(medicineFrequency);
                    $(".medicineObsInput").eq(i).val(medicineObs);

                    if (i != Object.values(result).length - 1) {
                        addMedicineForm();
                    }
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function deleteTemplate(id) {
        id = "templateId=" + id;
        $.ajax({
            type: "POST",
            url: "/plataforma/excluirTemplate",
            data: id,
            success: function (result) {
                alert(result)
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function validEmitPrescription(fullValidation) {
        removeError("cpfForm");
        removeError("phone");
        removeError("medicineName");
        removeError("medicineSize");
        removeError("medicineFrequency");

        if (fullValidation) {
            if ($(".cpfFormInput").val().length != 14) {
                error("cpfForm", "Informe um CPF válido!");
                $(".cpfFormInput").focus();

                return false;
            }

            if ($(".phoneInput").val().length != 14) {
                error("phone", "Informe um número de telefone válido!");
                $(".phoneInput").focus();

                return false;
            }
        }

        for (let i = 0; i < $(".medicineNameInput").length; i++) {
            let name = $(".medicineNameInput").eq(i);
            let size = $(".medicineSizeInput").eq(i);
            let frequency = $(".medicineFrequencyInput").eq(i);
            if (name.val().length == 0) {
                error("medicineName", "Nome não pode ser vazio", i);
                name.focus();

                return false;
            }
            if (size.val().length == 0) {
                error("medicineSize", "Dosagem não pode ser vazio", i);
                size.focus();

                return false;
            }
            if (!size.val().match(/cp|ml|gotas/)) {
                error("medicineSize", "Selecione um tipo", i);
                size.focus();

                return false;
            }
            if (frequency.val().length == 0) {
                error("medicineFrequency", "Frequência não pode ser vazio", i);
                frequency.focus();

                return false;
            }
            if (!frequency.val().match(/hora|vez ao dia|horas|vezes ao dia/)) {
                error("medicineFrequency", "Selecione um tipo", i);
                frequency.focus();

                return false;
            }
        }

        return true;
    }

    function emitPrescription() {
        let data = $(".formAddPrescription").serialize();
        $.ajax({
            type: "POST",
            url: "/plataforma/emiteReceita",
            data: data,
            dataType: "JSON",
            success: function (result) {
                console.log(result);
                alert("Receita emitida com sucesso!");
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function searchPacient() {
        let cpf = "cpf=" + $(".cpfFormInput").val();
        $.ajax({
            type: "POST",
            url: "/plataforma/procuraPaciente",
            data: cpf,
            dataType: "JSON",
            success: function (result) {
                if (result) {
                    $(".phoneInput").val(result["phone"]);
                    $(".medicineNameInput").focus();
                } else {
                    $(".phoneInput").val("");
                }
            },
            error: function (error) {
                console.log(error);
                $(".phoneInput").val("");
                $(".phoneInput").removeAttr("disabled");
                $(".phoneInput").focus();
            },
        });
    }

    function saveTemplate(data) {

        $.ajax({
            type: "POST",
            url: "/plataforma/salvarModelo",
            data: data,
            dataType: "JSON",
            success: function (result) {
                console.log(result);
                alert("Template salvo com sucesso!");
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function error(input, msg, index = 0) {
        let password = input == "password" || input == "passwordModal" ? "Password" : "";

        $("." + input + "Input").eq(index).addClass("inputError" + password);
        $("." + input + "Icon").eq(index).addClass("inputIconError");

        password == "Password" ? $("." + input + "IconView").eq(index).addClass("inputIconError" + password) : "";

        $("." + input + "Error").eq(index).removeClass("d-none");
        $("." + input + "ErrorMsg").eq(index).text(msg);
    }

    function removeError(input) {
        let password = input == "password" || input == "passwordModal" ? "Password" : "";

        $("." + input + "Input").removeClass("inputError" + password);
        $("." + input + "Icon").removeClass("inputIconError");

        $("." + input + "IconView").removeClass("inputIconError" + password);

        $("." + input + "Error").addClass("d-none");
    }
});
