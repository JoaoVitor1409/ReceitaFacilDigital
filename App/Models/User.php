<?php

namespace App\Models;

use MF\Model\Model;

class User extends Model
{
    private $id;
    private $cpf;
    private $name;
    private $email;
    private $phone;
    private $type;

    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
    }

    public function authenticate()
    {
        $query = "SELECT PacienteID, PacienteCPF, PacienteNome FROM PACIENTE WHERE PacienteEmail = :Pemail AND PacienteSenha = :Ppassword AND PacienteAtivo = 1";
        $query2 = "SELECT MedicoID, MedicoNome FROM MEDICO WHERE MedicoEmail = :Pemail AND MedicoSenha = :Ppassword AND MedicoAtivo = 1";
        $query3 = "SELECT FarmaciaID, FarmaciaNome FROM FARMACIA WHERE FarmaciaEmail = :Pemail AND FarmaciaSenha = :Ppassword AND FarmaciaAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt2 = $this->db->prepare($query2);
        $stmt3 = $this->db->prepare($query3);

        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt2->bindValue(":Pemail", $this->__get("email"));
        $stmt3->bindValue(":Pemail", $this->__get("email"));

        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt2->bindValue(":Ppassword", $this->__get("password"));
        $stmt3->bindValue(":Ppassword", $this->__get("password"));

        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();

        $pacient = $stmt->fetch(\PDO::FETCH_ASSOC);
        $doctor = $stmt2->fetch(\PDO::FETCH_ASSOC);
        $pharmacy = $stmt3->fetch(\PDO::FETCH_ASSOC);

        if ($pacient) {
            $this->__set("id", $pacient["PacienteID"]);
            $this->__set("cpf", $pacient["PacienteCPF"]);
            $this->__set("name", $pacient["PacienteNome"]);
            $this->__set("type", "pacient");
        }
        if ($doctor) {
            $this->__set("id", $doctor["MedicoID"]);
            $this->__set("name", $doctor["MedicoNome"]);
            $this->__set("type", "doctor");
        }
        if ($pharmacy) {
            $this->__set("id", $pharmacy["FarmaciaID"]);
            $this->__set("name", $pharmacy["FarmaciaNome"]);
            $this->__set("type", "pharmacy");
        }

        return $this;
    }

    public function getUserByEmail()
    {
        $query = "SELECT PacienteID FROM PACIENTE WHERE PacienteEmail = :Pemail";
        $query2 = "SELECT MedicoID FROM MEDICO WHERE MedicoEmail = :Pemail";
        $query3 = "SELECT FarmaciaID FROM FARMACIA WHERE FarmaciaEmail = :Pemail";

        $stmt = $this->db->prepare($query);
        $stmt2 = $this->db->prepare($query2);
        $stmt3 = $this->db->prepare($query3);
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt2->bindValue(":Pemail", $this->__get("email"));
        $stmt3->bindValue(":Pemail", $this->__get("email"));
        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();

        $stmt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
        $stmt3 = $stmt3->fetchAll(\PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        }
        if ($stmt2) {
            return $stmt2;
        }
        return $stmt3;
    }

    public function getUserByPhone()
    {
        $query = "SELECT PacienteID FROM PACIENTE WHERE PacienteCelular = :Pphone";
        $query2 = "SELECT MedicoID FROM MEDICO WHERE MedicoCelular = :Pphone";

        $stmt = $this->db->prepare($query);
        $stmt2 = $this->db->prepare($query2);
        $stmt->bindValue(":Pphone", $this->__get("phone"));
        $stmt2->bindValue(":Pphone", $this->__get("phone"));
        $stmt->execute();
        $stmt2->execute();

        $stmt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        }

        return $stmt2;
    }
}
