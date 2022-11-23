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
        deleteTemplate($(".templateId").val());
        loadTemplates();
        $("#template").modal("hide");

        return false;
    });


    // create Template

    $(document).on("click", ".createTemplateBtn", function () {
        $(".templateNameModalInput").val("");
        $(".medicineNameInput").val("");
        $(".medicineSizeInput").val("");
        $(".medicineFrequencyInput").val("");
        $(".medicineObsInput").val("");
        removeMedicineForm();
        $(".modal-title").text("");
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
        saveTemplate(data);

        return false;
    });


    // prescription detail popup

    $(document).on("click", ".cardSrcOption", function () {
        let srcMethod = $(this).attr("id");
        $(".btnDispensePrescription").addClass("d-none");
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
            let code = null;
            $(".allContent").addClass("d-none")
            $(".video").removeClass("d-none")
            scanQrCode().then(result => {
                if (result) {
                    $(".allContent").removeClass("d-none")
                    $(".video").addClass("d-none")

                    code = result.id
                    $(".modalTitle").html("Receita código: " + code);

                    $(".formSearchPrescription").addClass("hidden");
                    $(".modalTitle").removeClass("hidden");
                    $("#detailPrescription").modal("show");
                    getPrescription(code);
                }

            })
        }
        return false;
    });

    // Search prescription popup


    $(document).on("click", ".detailPrescription", function () {
        let prescriptionCode = $(this).attr("id");

        $(".modalTitle").html("Receita código: " + prescriptionCode);
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

    $(document).on("click", ".formSearchPrescription .searchBtnCPF", () => {
        let cpf = $(".cpfInput").val();
        if (cpf.length == 14) {
            getPrescription(cpf);
        } else {
            $(".modalBody").addClass("hidden");
            $(".notFound").removeClass("hidden");
        }

        return false;
    });

    //Dispense prescription

    $(document).on("click", ".btnDispensePrescription", () => {
        let cpf = $(".pacientCPF").val();

        dispensePrescription(cpf);
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
                $(".modal-title").html("Receita código: " + prescriptionCode);

                $(".pacientName").val(result["PacienteNome"]);
                $(".pacientCPF").val(result["PacienteCPF"]);
                $(".issueDate").val(result["ReceitaData"]);
                $(".doctorName").val(result["MedicoNome"]);

                $(".medicinesList").html("");
                let obs = 0;
                Object.values(result["medicines"]).forEach((medicine, index) => {
                    let length = index + 1 + ". " + medicine["MedicamentoDesc"] + " " + medicine["MedicamentoDosagem"] + " " + medicine["MedicamentoFrequencia"];
                    length = 35 - length.length;
                    let list = index + 1 + ". " + medicine["MedicamentoDesc"] + " " + medicine["MedicamentoDosagem"] + " ";
                    for (let i = 0; i <= length; i++) {
                        list += "_";
                    }
                    list += " " + medicine["MedicamentoFrequencia"];
                    if (medicine["MedicamentoObs"] != "") {
                        list += "\n  Obs: " + medicine["MedicamentoObs"];
                        obs++;
                    }
                    if (index != result["medicines"].length - 1) {
                        list += "\n";
                    }
                    $(".medicinesList").append(list);
                });
                $(".medicinesList").attr("rows", result["medicines"].length + obs);

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
            data: "js=true&page=" + page + "&paginationStyle=" + paginationStyle + "&" + data,
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
                    $(".tableDiv").html("Nenhuma receita encontrada!");
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
                        let page = `
                            <div class="template row mb-2" id="` + template["ModeloID"] + `">
                            <div class="row">
                                <h1 class="title">` + template["ModeloNome"] + `</h1></div>
                            <div class="row">
                            <p class="medicinesDesc">
                        `;

                        for (let i = 0; i < template.medicines.length; i++) {
                            if (i != template.medicines.length - 1) {
                                page += template.medicines[i].MedicamentoDesc + ";";
                            } else {
                                page += template.medicines[i].MedicamentoDesc;
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
                            <div class="templateCard card col-md-3 mb-2"  id="` + template["ModeloID"] + `">
                                <div class="row">
                                    <h1 class="title card-header text-center">` + template["ModeloNome"] + `</h1></div>
                                    <div class="row">
                                    <ul class="list-group list-group-flush">
                        `;

                        for (let i = 0; i < template.medicines.length; i++) {
                            page += '<li class="list-group-item listItem">';
                            if (i != template.medicines.length - 1) {
                                page += template.medicines[i].MedicamentoDesc + ";";
                            } else {
                                page += template.medicines[i].MedicamentoDesc;
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
                if (result["PacienteNome"]) {
                    $(".pacientName").val(result["PacienteNome"]);
                    $(".pacientCPF").val(result["PacienteCPF"]);
                    $(".issueDate").val(result["ReceitaData"]);
                    $(".doctorName").val(result["MedicoNome"]);

                    $(".medicinesList").html("");
                    let obs = 0;
                    Object.values(result["medicines"]).forEach((medicine, index) => {
                        let length = index + 1 + ". " + medicine["MedicamentoDesc"] + " " + medicine["MedicamentoDosagem"] + " " + medicine["MedicamentoFrequencia"];
                        length = 35 - length.length;
                        let list = index + 1 + ". " + medicine["MedicamentoDesc"] + " " + medicine["MedicamentoDosagem"] + " ";
                        for (let i = 0; i <= length; i++) {
                            list += "_";
                        }
                        list += " " + medicine["MedicamentoFrequencia"];
                        if (medicine["MedicamentoObs"] != "") {
                            list += "\n  Obs: " + medicine["MedicamentoObs"];
                            obs++;
                        }
                        if (index != result["medicines"].length - 1) {
                            list += "\n";
                        }
                        $(".medicinesList").append(list);
                    });
                    $(".medicinesList").attr("rows", result["medicines"].length + obs);
                    $(".btnDispensePrescription").removeClass("d-none");
                } else {
                    console.log(result["message"]);
                    $(".modalBody").addClass("hidden");
                    $(".notFound").text(result["message"]);
                    $(".notFound").removeClass("hidden");
                    $(".btnDispensePrescription").addClass("d-none");
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
                result = result.medicines;
                for (let i = 0; i < Object.values(result).length; i++) {
                    let medicineName = result[i].MedicamentoDesc;
                    let medicineSize = result[i].MedicamentoDosagem;
                    let medicineFrequency = result[i].MedicamentoFrequencia;
                    let medicineObs = null;
                    if (result[i].MedicamentoObs) {
                        medicineObs = result[i].MedicamentoObs;
                    }

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
                $(".templateId").val(result.ModeloID);
                $("#template .modal-title").html("Modelo <b>" + result.ModeloNome + "</b>")
                $(".templateNameModalInput").val(result.ModeloNome)
                result = result.medicines
                for (let i = 0; i < Object.values(result).length; i++) {
                    let medicineName = result[i].MedicamentoDesc;
                    let medicineSize = result[i].MedicamentoDosagem;
                    let medicineFrequency = result[i].MedicamentoFrequencia;
                    let medicineObs = null;
                    if (result[i].MedicamentoObs) {
                        medicineObs = result[i].MedicamentoObs;
                    }
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

    function dispensePrescription(cpf) {
        cpf = "cpf=" + cpf;
        $.ajax({
            type: "POST",
            url: "/plataforma/dispensaReceita",
            data: cpf,
            success: function () {
                alert("Receita Dispensada");

                $("#detailPrescription").modal("hide");
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
                if (result["code"] == 0) {
                    error(result["input"], result["message"]);
                } else if (result["code"] == 1) {
                    data = { id: result["prescriptionId"] }
                    let value = genQrCode(data);
                    console.log(value);

                    sendSMS(value);
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function sendSMS(text) {
        let tel = $(".phoneInput").val();
        tel = tel.replace("(", "");
        tel = tel.replace(")", "");
        tel = tel.replace("-", "");
        alert(tel);
        let apiKey = "brbb13b31812760c430d33468b4ef0f1bd4d774b8d1b5602f0bd1adab3abf201423d50"
        encodeQrCode(text, apiKey).then(link => {
            let url = "https://api.mobizon.com.br/service/Message/SendSmsMessage?recipient=%2B55" + tel + "&text=" + link + "&output=json&apiKey=" + apiKey;

            $.getJSON(url, (data) => {
                alert("Receita Emitida")
            });
        })


    }

    function encodeQrCode(text, apiKey) {
        text = encodeURIComponent(text)
        url = "https://api.mobizon.com.br/service/link/create?data%5BfullLink%5D=" + text + "&output=json&api=v1&apiKey=" + apiKey;

        return new Promise((resolve) => {
            $.getJSON(url, ({ data }) => {
                resolve(data.shortLink)
            });
        })
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
                    $(".medicineNameInput").eq(0).focus();
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
                if (result["code"] == 1) {
                    alert(result["message"])
                    $("#saveTemplate").modal("hide");
                } else if (result["code"] == 0) {                    
                    error(result["input"], result["message"]);
                }
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
