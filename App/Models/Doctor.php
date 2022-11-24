<?php

namespace App\Models;

use MF\Model\Model;

class Doctor extends Model
{
    private $id;
    private $name;
    private $crm;
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
        $query = "INSERT INTO MEDICO(MedicoNome,MedicoCRM,MedicoDataNasc,MedicoEmail,MedicoCelular,MedicoSenha,EnderecoID) VALUES (:Pname,:Pcrm,STR_TO_DATE(:PbirthDate, '%d/%m/%Y'),:Pemail,:Pphone,:Ppassword,:PadressId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pname", $this->__get("name"));
        $stmt->bindValue(":Pcrm", $this->__get("crm"));
        $stmt->bindValue(":PbirthDate", $this->__get("birthDate"));
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->bindValue(":Pphone", $this->__get("phone"));
        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt->bindValue(":PadressId", $this->__get("adressId"));

        $stmt->execute();

        return $this;
    }

    public function updatePassword()
    {
        $query = "UPDATE MEDICO SET MedicoSenha = :Ppassword WHERE MedicoID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $this;
    }

    public function getDoctorById()
    {
        $query = "SELECT MedicoNome, EnderecoID FROM MEDICO WHERE MedicoId = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDoctorByCRM()
    {
        $query = "SELECT MedicoID FROM MEDICO WHERE MedicoCRM = :Pcrm";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcrm", $this->__get("crm"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
