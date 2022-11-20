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
        $query = "INSERT INTO MEDICO(MedicoNome,MedicoCRM,MedicoDataNasc,MedicoEmail,MedicoCelular,MedicoSenha,EnderecoID) VALUES (:Pname,:Pcrm,:PbirthDate,:Pemail,:Pphone,:Ppassword,:PadressId)";

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

    public function authenticate()
    {
        $query = "SELECT MedicoID, MedicoNome FROM MEDICO WHERE MedicoEmail = :Pemail AND MedicoSenha = :Ppassword AND MedicoAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->bindValue(":Ppassword", $this->__get("password"));

        $stmt->execute();

        $pacient = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($pacient["MedicoID"] && $pacient["MedicoNome"]) {
            $this->__set("id", $pacient["MedicoID"]);
            $this->__set("name", $pacient["MedicoNome"]);
        }

        return $this;
    }

    public function getDoctorByCRM()
    {
        $query = "SELECT MedicoID FROM MEDICO WHERE MedicoCRM = :Pcrm";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcrm", $this->__get("crm"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDoctorByEmail()
    {
        $query = "SELECT MedicoID FROM MEDICO WHERE MedicoEmail = :Pemail";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDoctorByPhone()
    {
        $query = "SELECT MedicoID FROM MEDICO WHERE MedicoCelular = :Pphone";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pphone", $this->__get("phone"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
