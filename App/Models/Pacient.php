<?php

namespace App\Models;

use MF\Model\Model;

class Pacient extends Model
{
    private $id;
    private $cpf;
    private $name;
    private $birthDate;
    private $email;
    private $phone;
    private $password;
    private $active;
    private $adressId;

    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
    }

    public function save()
    {
        $query = "INSERT INTO PACIENTE(PacienteCPF,PacienteNome,PacienteDataNasc,PacienteEmail,PacienteCelular,PacienteSenha, EnderecoID) VALUES (:Pcpf,:Pname,STR_TO_DATE(:PbirthDate, '%d/%m/%Y'),:Pemail,:Pphone,:Ppassword,:PadressId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcpf", $this->__get("cpf"));
        $stmt->bindValue(":Pname", $this->__get("name"));
        $stmt->bindValue(":PbirthDate", $this->__get("birthDate"));
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->bindValue(":Pphone", $this->__get("phone"));
        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt->bindValue(":PadressId", $this->__get("adressId"));

        $stmt->execute();

        return $this;
    }

    public function getPacientByCPF()
    {
        $query = "SELECT PacienteID FROM PACIENTE WHERE PacienteCPF = :Pcpf";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcpf", $this->__get("cpf"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
