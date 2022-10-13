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

    $(document).on("focus", ".cepInput", () => {
        $(".cepInput").mask("00000-000");
    });


    // TIRA DAQUI LOUCO

    // remover errors msg

    $(document).on("focusout", ".nameInput", () => removeError("name"));
    $(document).on("focusout", ".cpfInput", () => removeError("cpf"));
    $(document).on("focusout", ".crmInput", () => removeError("crm"));
    $(document).on("focusout", ".cnpjInput", () => removeError("cnpj"));
    $(document).on("focusout", ".emailInput", () => removeError("email"));
    $(document).on("focusout", ".phoneInput", () => removeError("phone"));
    $(document).on("focusout", ".passwordInput", () => removeError("password"));
    $(document).on("focusout", ".cepInput", () => removeError("cep"));
    $(document).on("focusout", ".streetInput", () => removeError("street"));
    $(document).on("focusout", ".numberInput", () => removeError("number"));


    let continueForm = true;

    // Send form
    $(".btnAction").click(() => {

        removeError("name");
        removeError("cpf");
        removeError("crm");
        removeError("cnpj");
        removeError("email");
        removeError("phone");
        removeError("password");
        removeError("city");
        removeError("cep");
        removeError("district");
        removeError("street");
        removeError("number");

        if ($(".nameInput").val().replaceAll(" ", "") == "" || $(".nameInput").val().split(" ").length <= 1) {
            error("name", "Digite seu nome completo!");
            $(".nameInput").focus();

            return false;
        }

        if (pacientActive) {
            if ($(".cpfInput").val().length != 14) {
                error("cpf", "Informe um CPF válido!");
                $(".cpfInput").focus();

                return false;
            }
        }

        if (doctorActive) {
            if ($(".crmInput").val().length == 0) {
                error("crm", "Informe um número CRM válido!");
                $(".crmInput").focus();

                return false;
            }
        }

        if (pharmacyActive) {
            if ($(".cnpjInput").val().length != 20) {
                error("cnpj", "Informe um CNPJ válido!");
                $(".cnpjInput").focus();

                return false;
            }
        }

        if (pacientActive || doctorActive) {
            if ($(".birthDateInput").val() == "") {
                error("birthDate", "Data de nascimento não pode ser vazia!");
                $(".birthDateInput").focus();

                return false;
            }
        }

        if (!emailValidation()) {
            error("email", "Informe um email válido!");
            $(".emailInput").focus();

            return false;
        }

        if (pacientActive || doctorActive) {
            if ($(".phoneInput").val().length != 14) {
                error("phone", "Informe um número de telefone válido!");
                $(".phoneInput").focus();

                return false;
            }
        }

        if (pharmacyActive) {
            if ($(".telInput").val().length != 13) {
                error("tel", "Informe um número de telefone válido!");
                $(".telInput").focus();

                return false;
            }
        }

        if ($(".passwordInput").val().replaceAll(" ", "") == "") {
            error("password", "A senha não pode ser vazia!");
            $(".passwordInput").focus();

            return false;
        }

        if (continueForm) {
            if ($(".inputsAdress").hasClass("d-none")) {
                showInputsAdress();

                return false;
            }
            loadAdreesInputs();

            return false;
        }

        if (!continueForm) {
            if ($(".cepInput").val().length != 9 || cepNotFound) {
                error("cep", "Digite um CEP válido!");
                $(".cepInput").focus();

                return false;
            }

            if (pharmacyActive) {
                if ($(".districtInput").val().replaceAll(" ", "") == "") {
                    error("district", "Bairro não pode ser vazio!");
                    $(".districtInput").focus();

                    return false;
                }

                if ($(".streetInput").val().replaceAll(" ", "") == "") {
                    error("street", "Logradouro não pode ser vazio!");
                    $(".streetInput").focus();

                    return false;
                }

                if ($(".numberInput").val().replaceAll(" ", "") == "") {
                    error("number", "Cidade não pode ser vazio!");
                    $(".numberInput").focus();

                    return false;
                }
            }

            if (doctorActive) {
                if (!doctorValidation()) {
                    return false;
                }
            }

            alert("Deu bom");

            console.log($(".formSignUp").serialize());
        }


        return false;
    });


    // Back Form
    $(".backSignUp").click(() => {
        showInputs();
        $(".backSignUp").addClass("d-none");
    });


    // fill CEP
    var cepNotFound = true;
    $(document).on("keyup", ".cepInput", () => {
        if ($(".cepInput").val().length == 9) {
            let cep = $(".cepInput").val().replace("-", "");
            let url = "https://viacep.com.br/ws/" + cep + "/json/";
            let request = new XMLHttpRequest();

            request.open('GET', url);
            request.onerror = (e) => {
                error("cep", "Insira um CEP válido");
                $(".cepInput").focus();
            };

            request.onload = () => {
                let response = JSON.parse(request.responseText);



                if (response.erro) {
                    error("cep", "Insira um CEP válido");
                    $(".cepInput").focus();
                    cepNotFound = true;
                } else {
                    $(".option" + response.uf + "").attr("selected", "selected");
                    $(".cityInput").val(response.localidade);
                    $(".stateInput").css("color", "#000");
                    $(".districtInput").val(response.bairro);
                    $(".streetInput").val(response.logradouro);
                    cepNotFound = false;
                }
            };

            request.send();
        }

    });


    // Alter color of select state
    $(document).on("focusout", ".stateInput", () => {
        if ($(".stateInput option:selected").text() != "Selecione o Estado") {
            $(".stateInput").css("color", "#000");
        }
        removeError("state")
    });



    // Alter type for input Date

    $(document).on("focus", ".birthDateInput", () => {
        $(".birthDateInput").attr("type", "date");
    });

    $(document).on("focusout", ".birthDateInput", () => {
        removeError("birthDate");
        let birthDateInput = $(".birthDateInput");
        if (birthDateInput.val() != "") {
            let birthDateFormated = birthDateInput.val();
            let year = birthDateFormated.split("-")[0];
            let curYear = new Date().getFullYear();
            if (year.length != 4 || year < 1900 || year >= curYear) {
                error("birthDate", "Insira uma data válida");
                $(".birthDateInput").focus();
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

    function showInputs() {
        $(".btnAction").text("Continuar");
        $(".options").removeClass("d-none");
        $(".inputs").removeClass("d-none");
        $(".inputsAdress").addClass("d-none");
        continueForm = true;
    }

    function showInputsAdress() {
        $(".btnAction").text("Criar Conta");
        $(".options").addClass("d-none");
        $(".inputs").addClass("d-none");
        $(".inputsAdress").removeClass("d-none");
        $(".backSignUp").removeClass("d-none");
        continueForm = false;
    }

    function loadInputs() {
        $(".allInputs").html("");

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

        continueForm = true;
    }

    function loadAdreesInputs() {
        $(".options").addClass("d-none");
        $(".inputs").addClass("d-none");

        let inputs = [
            [
                'cep',
                'pin',
                'text',
                'CEP',
                true,
                'required'
            ],
            [
                'state',
                'location_city',
                'text',
                'Estado',
                true,
                'required'
            ],
            [
                'city',
                'home_work',
                'text',
                'Cidade',
                true,
                'required'
            ],
            [
                'district',
                'apartment',
                'text',
                'Bairro',
                pharmacyActive,
                'required'
            ],
            [
                'street',
                'edit_road',
                'text',
                'Logradouro',
                pharmacyActive,
                'required'
            ],
            [
                'number',
                'pin',
                'text',
                'Número',
                pharmacyActive,
                'required'
            ],
            [
                'complement',
                'article',
                'text',
                'Complemento',
                pharmacyActive,
                ''
            ]
        ]

        inputs.forEach(input => {
            if (input[4]) {
                if (input[0] != "state") {
                    $(".allInputs").append(`
                    <div class="row justify-content-center inputs inputsAdress">
                        <div class="col-md-8 inputArea">
                            <div class="input-group">
                                <label for="`+ input[0] + `" class="input-group-text inputIcon ` + input[0] + `Icon">
                                    <span class="material-icons-outlined">`+ input[1] + `</span>
                                </label>
                                <input type="`+ input[2] + `" name="` + input[0] + `" class="form-control ` + input[0] + `Input" id="` + input[0] + `" placeholder="` + input[3] + `" ` + input[5] + `>

                            </div>
                            <div class="errorDiv text-start d-none `+ input[0] + `Error">
                                <p class="ErrorMsg error text-danger `+ input[0] + `ErrorMsg"></p>
                            </div>
                        </div>
                    </div>
                `);
                } else {
                    $(".allInputs").append(`
                        <div class="row justify-content-center inputs inputsAdress">
                            <div class="col-md-8 inputArea">
                                <div class="input-group">
                                    <label for="`+ input[0] + `" class="input-group-text inputIcon ` + input[0] + `Icon">
                                        <span class="material-icons-outlined">`+ input[1] + `</span>
                                    </label>
                                    <select class="form-select `+ input[0] + `Input" name="'` + input[0] + `'">
                                        <option class="disabled" selected disabled>Selecione o Estado</option>
                                        <option class="optionAC" value="AC">AC</option>
                                        <option class="optionAL" value="AL">AL</option>
                                        <option class="optionAP" value="AP">AP</option>
                                        <option class="optionAM" value="AM">AM</option>
                                        <option class="optionBA" value="BA">BA</option>
                                        <option class="optionCE" value="CE">CE</option>
                                        <option class="optionDF" value="DF">DF</option>
                                        <option class="optionES" value="ES">ES</option>
                                        <option class="optionGO" value="GO">GO</option>
                                        <option class="optionMA" value="MA">MA</option>
                                        <option class="optionMT" value="MT">MT</option>
                                        <option class="optionMS" value="MS">MS</option>
                                        <option class="optionMG" value="MG">MG</option>
                                        <option class="optionPA" value="PA">PA</option>
                                        <option class="optionPB" value="PB">PB</option>
                                        <option class="optionPR" value="PR">PR</option>
                                        <option class="optionPE" value="PE">PE</option>
                                        <option class="optionPI" value="PI">PI</option>
                                        <option class="optionRJ" value="RJ">RJ</option>
                                        <option class="optionRN" value="RN">RN</option>
                                        <option class="optionRS" value="RS">RS</option>
                                        <option class="optionRO" value="RO">RO</option>
                                        <option class="optionRR" value="RR">RR</option>
                                        <option class="optionSC" value="SC">SC</option>
                                        <option class="optionSP" value="SP">SP</option>
                                        <option class="optionSE" value="SE">SE</option>
                                        <option class="optionTO" value="TO">TO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);
                }
            }
        });

        $(".backSignUp").removeClass("d-none");
        $(".btnAction").text("Criar conta");
        continueForm = false;
    }

    function emailValidation() {
        let email = $(".emailInput").val();
        usuario = email.substring(0, email.indexOf("@"));
        dominio = email.substring(email.indexOf("@") + 1, email.length);

        if ((usuario.length >= 1) &&
            (dominio.length >= 3) &&
            (usuario.search("@") == -1) &&
            (dominio.search("@") == -1) &&
            (usuario.search(" ") == -1) &&
            (dominio.search(" ") == -1) &&
            (dominio.search(".") != -1) &&
            (dominio.indexOf(".") >= 1) &&
            (dominio.lastIndexOf(".") < dominio.length - 1)) {
            return true;
        }
        else {
            return false
        }
    }

    function doctorValidation() {
        let crmNotFound = true;
        let crm = $(".crmInput").val().replace("-", "");
        let state = $(".stateInput").val().replace("-", "");
        let key = "6976964646";
        let url = "https://www.consultacrm.com.br/api/index.php?tipo=crm&uf=" + state + "&q=" + crm + "&chave=" + key + "&destino=json";

        let request = new XMLHttpRequest();

        request.open('GET', url);
        request.onerror = (e) => {
            error("crm", "Insira um CRM válido");
            $(".crmInput").focus();
            crmNotFound = true;
            showInputs();
        };

        request.onload = () => {
            let response = JSON.parse(request.responseText);

            if (response.item.length == 0) {
                error("crm", "Insira um CRM válido");
                $(".crmInput").focus();
                crmNotFound = true;
                showInputs();
            } else {
                if (response.item[0].situacao == "Ativo") {
                    crmNotFound = false;
                } else {
                    error("crm", "Insira um CRM válido");
                    $(".crmInput").focus();
                    crmNotFound = true;
                    showInputs();
                }
            }
        };

        request.send();

        return crmNotFound;
    }
});