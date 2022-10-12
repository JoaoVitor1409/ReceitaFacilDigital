$(document).ready(() => {

    // Masks
    $(document).on("focus", ".cpfInput", () => {
        $(".cpfInput").mask("000.000.000-00");
    });

    $(document).on("focus", ".crmInput", () => {
        $(".crmInput").mask("0#");
    });

    $(document).on("focus", ".cnpjInput", () => {
        $(".cnpjInput").mask("00.000.000 / 0000-00");
    });
    

    $(document).on("focus", ".phoneInput", () => {
        $(".phoneInput").mask("(00)00000-0000");
    });
    $(document).on("focus", ".telInput", () => {
        $(".telInput").mask("(00)0000-0000");
    });




    // Send form
    $(".btnAction").click(() => {

        error("name", "Insira seu nome completo");

        return false;
    });




    // Alter type for input Date

    $(document).on("focus", ".birthDateInput", () => {
        birthDateInput = $(".birthDateInput");
        birthDateInput.attr("type", "date");
        removeError("birthDate");
    });

    $(document).on("focusout", ".birthDateInput", () => {
        let birthDateInput = $(".birthDateInput");
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
        } else {
            birthDateInput.attr("type", "text");
        }
    });



    
    // Change options buttons
    let pacientActive = true;
    let doctorActive = false;
    let pharmacyActive = false;
    loadInputs();
    $(".pacientBtn").click(() => {
        if (!pacientActive) {
            if (doctorActive) {
                $(".doctorBtn").removeClass("actived");
            } else {
                $(".pharmacyBtn").removeClass("actived");
            }

            $(".pacientBtn").addClass("actived");
            pacientActive = true;
            doctorActive = false;
            pharmacyActive = false;

            loadInputs();
        }
    });
    $(".doctorBtn").click(() => {
        if (!doctorActive) {
            if (pacientActive) {
                $(".pacientBtn").removeClass("actived");
            } else {
                $(".pharmacyBtn").removeClass("actived");
            }

            $(".doctorBtn").addClass("actived");
            doctorActive = true;
            pacientActive = false;
            pharmacyActive = false;

            loadInputs();
        }
    });
    $(".pharmacyBtn").click(() => {
        if (!pharmacyActive) {
            if (pacientActive) {
                $(".pacientBtn").removeClass("actived");
            } else {
                $(".doctorBtn").removeClass("actived");
            }

            $(".pharmacyBtn").addClass("actived");
            pharmacyActive = true
            pacientActive = false;
            doctorActive = false;

            loadInputs();
        }
    });


    function loadInputs() {
        $(".allInputs").html("")

        let inputs = [
            [
                'name',
                'person',
                'text',
                'Nome Completo',
                true
            ],
            [
                'cpf',
                'badge',
                'text',
                'CPF',
                pacientActive
            ],
            [
                'crm',
                'badge',
                'text',
                'Número CRM',
                doctorActive
            ],
            [
                'cnpj',
                'badge',
                'text',
                'CNPJ',
                pharmacyActive
            ],
            [
                'birthDate',
                'calendar_month',
                'text',
                'Data de Nascimento',
                !pharmacyActive
            ],
            [
                'email',
                'email',
                'text',
                'E-mail',
                true
            ],
            [
                'phone',
                'phone_android',
                'text',
                'Número de Celular',
                (pacientActive || doctorActive)
            ],
            [
                'tel',
                'phone',
                'text',
                'Número de Telefone',
                pharmacyActive
            ],
        ]

        inputs.forEach(input => {
            if (input[4]) {
                $(".allInputs").append(`
                    <div class="row justify-content-center inputs">
                        <div class="col-md-8 inputArea">
                            <div class="input-group">
                                <label for="`+ input[0] + `" class="input-group-text inputIcon ` + input[0] + `Icon">
                                    <span class="material-icons-outlined">`+ input[1] + `</span>
                                </label>
                                <input type="`+ input[2] + `" name="` + input[0] + `" class="form-control ` + input[0] + `Input" id="` + input[0] + `" placeholder="` + input[3] + `" required>

                            </div>
                            <div class="errorDiv text-start d-none `+ input[0] + `Error">
                                <p class="ErrorMsg error text-danger `+ input[0] + `ErrorMsg"></p>
                            </div>
                        </div>
                    </div>
                `)
            }
        });
    }
});