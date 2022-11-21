<?php

namespace App\Models;

use MF\Model\Model;

class User extends Model
{
    private $email;
    private $phone;

    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
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
