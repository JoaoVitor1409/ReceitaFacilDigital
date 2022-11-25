<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Action
{
    public function login()
    {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $user = Container::getModel('User');

        $user->__set('email', $email);
        $user->__set('password', $password);

        $user = $user->authenticate();

        $result = ["code" => 0, "message" => "email ou senha inválida", "input" => "email"];

        if ($user->__get('id')) {
            session_start();
            $_SESSION['rfd']['user'] = [
                'id' => $user->__get('id'),
                'name' => $user->__get('name'),
                'email' => $user->__get('email'),
                'type' => $user->__get('type'),
            ];
            if ($user->__get('type') == 'pacient') {
                $_SESSION['rfd']['user'] += ['cpf' => $user->__get('cpf')];
            }

            if ($user->__get('type') != 'pharmacy') {
                $_SESSION['rfd']['user'] += ['phone' => $user->__get('phone')];
            }else{
                $_SESSION['rfd']['user'] += ['phone' => $user->__get('tel')];
            }

            $result = ["code" => 1];
        }

        echo json_encode($result);
    }

    public function register()
    {
        $name = $_POST['name'];

        if (isset($_POST['cpf'])) {
            $cpf = $_POST['cpf'];
        }

        if (isset($_POST['crm'])) {
            $crm = $_POST['crm'];
        }

        if (isset($_POST['cnpj'])) {
            $cnpj = $_POST['cnpj'];
        }

        if (isset($_POST['birthDate'])) {
            $birthDate = $_POST['birthDate'];
        }

        $email = $_POST['email'];

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
        }

        if (isset($_POST['tel'])) {
            $tel = $_POST['tel'];
        }

        $password = md5($_POST['password']);
        $cep = $_POST['cep'];
        $city = $_POST['city'];
        $state = $_POST['state'];

        if (isset($_POST['district'])) {
            $district = $_POST['district'];
        }
        if (isset($_POST['street'])) {
            $street = $_POST['street'];
        }
        if (isset($_POST['number'])) {
            $number = $_POST['number'];
        }
        if (isset($_POST['complement'])) {
            $complement = $_POST['complement'];
        }

        $options = $_POST['options'];

        $result = ['code' => 1, 'message' => 'Usuário cadastrado com sucesso'];

        if ($options == 'Paciente') {
            if (!$this->CpfValidation($cpf)) {
                $result = ['code' => 0, 'message' => 'Digite um CPF válido', 'input' => 'cpf'];

                echo json_encode($result);
                return;
            }

            $pacient = Container::getModel('Pacient');
            $adress = Container::getModel('Adress');
            $user = Container::getModel('User');

            $adress->__set('cep', $cep);
            $adress->__set('city', $city);
            $adress->__set('state', $state);

            $pacient->__set('cpf', $cpf);
            $pacient->__set('name', $name);
            $pacient->__set('birthDate', $birthDate);
            $pacient->__set('email', $email);
            $pacient->__set('phone', $phone);
            $pacient->__set('password', $password);

            $user->__set('email', $email);
            $user->__set('phone', $phone);

            if ($pacient->getPacientByCPF()) {
                $result = ['code' => 0, 'message' => 'CPF já cadastrado', 'input' => 'cpf'];

                echo json_encode($result);
                return;
            }

            if ($user->getUserByEmail()) {
                $result = ['code' => 0, 'message' => 'Email já cadastrado', 'input' => 'email'];

                echo json_encode($result);
                return;
            }

            if ($user->getUserByPhone()) {
                $result = ['code' => 0, 'message' => 'Telefone já cadastrado', 'input' => 'phone'];

                echo json_encode($result);
                return;
            }

            $adress->save();
            $adressId = $adress->getLastAdress()[0]['EnderecoID'];

            $pacient->__set('adressId', $adressId);
            $pacient->save();
        } elseif ($options == 'Medico') {
            $doctor = Container::getModel('Doctor');
            $adress = Container::getModel('Adress');
            $user = Container::getModel('User');

            $adress->__set('cep', $cep);
            $adress->__set('city', $city);
            $adress->__set('state', $state);

            $doctor->__set('name', $name);
            $doctor->__set('crm', $crm);
            $doctor->__set('birthDate', $birthDate);
            $doctor->__set('email', $email);
            $doctor->__set('phone', $phone);
            $doctor->__set('password', $password);

            $user->__set('email', $email);
            $user->__set('phone', $phone);

            if ($doctor->getDoctorByCRM()) {
                $result = ['code' => 0, 'message' => 'CRM já cadastrado', 'input' => 'crm'];

                echo json_encode($result);
                return;
            }

            if ($user->getUserByEmail()) {
                $result = ['code' => 0, 'message' => 'Email já cadastrado', 'input' => 'email'];

                echo json_encode($result);
                return;
            }

            if ($user->getUserByPhone()) {
                $result = ['code' => 0, 'message' => 'Telefone já cadastrado', 'input' => 'phone'];

                echo json_encode($result);
                return;
            }

            $adress->save();
            $adressId = $adress->getLastAdress()[0]['EnderecoID'];

            $doctor->__set('adressId', $adressId);
            $doctor->save();
        } elseif ($options == 'Farmacia') {
            $pharmacy = Container::getModel('Pharmacy');
            $adress = Container::getModel('Adress');
            $user = Container::getModel('User');

            $adress->__set('cep', $cep);
            $adress->__set('city', $city);
            $adress->__set('state', $state);
            $adress->__set('district', $district);
            $adress->__set('street', $street);
            $adress->__set('number', $number);
            $adress->__set('complement', $complement);

            $pharmacy->__set('name', $name);
            $pharmacy->__set('cnpj', $cnpj);
            $pharmacy->__set('email', $email);
            $pharmacy->__set('tel', $tel);
            $pharmacy->__set('password', $password);

            $user->__set('email', $email);

            if ($pharmacy->getPharmacyByCNPJ()) {
                $result = ['code' => 0, 'message' => 'CNPJ já cadastrado', 'input' => 'cnpj'];

                echo json_encode($result);
                return;
            }

            if ($user->getUserByEmail()) {
                $result = ['code' => 0, 'message' => 'Email já cadastrado', 'input' => 'email'];

                echo json_encode($result);
                return;
            }

            if ($pharmacy->getPharmacyByTel()) {
                $result = ['code' => 0, 'message' => 'Telefone já cadastrado', 'input' => 'tel'];

                echo json_encode($result);
                return;
            }

            $adress->save();
            $adressId = $adress->getLastAdress()[0]['EnderecoID'];

            $pharmacy->__set('adressId', $adressId);
            $pharmacy->save();
        }

        echo json_encode($result);
    }

    private function CpfValidation($cpf)
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public function sendVerificationCode()
    {
        $emailTo = $_POST["email"];

        $user = Container::getModel("User");
        $user->__set("email", $emailTo);
        $userExists = $user->getUserByEmail();

        if ($userExists) {
            session_start();
            $code = rand(100000, 999999);
            $_SESSION['rfd']['user'] = ['code' => $code];

            $this->sendEmail($emailTo);

            if (isset($userExists[0]["PacienteID"])) {
                $_SESSION['rfd']['user'] += [
                    "id" => $userExists[0]["PacienteID"],
                    "type" => "pacient"
                ];
            } else if (isset($userExists[0]["MedicoID"])) {
                $_SESSION['rfd']['user'] += [
                    "id" => $userExists[0]["MedicoID"],
                    "type" => "doctor"
                ];
            } else if (isset($userExists[0]["FarmaciaID"])) {
                $_SESSION['rfd']['user'] += [
                    "id" => $userExists[0]["FarmaciaID"],
                    "type" => "pharmacy"
                ];
            }
        }
    }

    public function verifyCode()
    {
        session_start();
        $code = $_POST["code"];
        $result = ["code" => 1];

        if (!isset($_SESSION["rfd"]["user"]["code"])) {
            $result = ["code" => 0, "message" => "Código inválido", "input" => "code"];
        } else {
            if ($code != $_SESSION["rfd"]["user"]["code"]) {
                $result = ["code" => 0, "message" => "Código inválido", "input" => "code"];
            }
        }

        echo json_encode($result);
    }

    public function changePassword()
    {
        session_start();
        $password = md5($_POST["password"]);
        $user = $_SESSION["rfd"]["user"]["type"];
        $user = Container::getModel(ucfirst($user));

        $user->__set("id", $_SESSION["rfd"]["user"]["id"]);
        $user->__set("password", $password);
        $user->updatePassword();
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public function sendEmail($emailTo)
    {
        $mail = new PHPMailer(true);
        $user = "receitafacildigital@gmail.com";
        $password = "gqmpnkyzpcttzggy";

        $body = "<h1>Receita Fácil Digital</h1>";
        $body .= "<h3>Para a alteração de senha, o código é: {$_SESSION['rfd']['user']['code']}</h3>";

        $altBody = "Receita Fácil Digital";
        $altBody .= "\n Para a alteração de senha, o código é: {$_SESSION['rfd']['user']['code']}";

        try {

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = true;
            $mail->Username   = $user;
            $mail->Password   = $password;
            $mail->Port       = 587;
            $mail->CharSet = "UTF-8";

            $mail->setFrom($user, 'Receita Facil Digital');
            $mail->addReplyTo('no-reply@gmail.com');
            $mail->addAddress($emailTo);

            $mail->isHTML(true);
            $mail->Subject = 'Código para alteração de senha';
            $mail->Body    = $body;
            $mail->AltBody = $altBody;

            $mail->send();
        } catch (Exception $e) {
            return $e;
        }
    }
}
