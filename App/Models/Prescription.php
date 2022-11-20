<?php

namespace App\Models;

use MF\Model\Model;

class Prescription extends Model
{
    private $id;
    private $date;
    private $pacientCPF;
    private $pacientPhone;
    private $active;
    private $doctorId;
    private $pharmacyId;

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
        $query = "INSERT INTO RECEITA(PacienteCPF,PacienteCelular,MedicoID) VALUES (:Pcpf,:Pphone,:PdoctorId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcpf", $this->__get("pacientCPF"));
        $stmt->bindValue(":Pphone", $this->__get("pacientPhone"));
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));

        $stmt->execute();

        return $this;
    }

    public function getPrescriptionById()
    {
        $query = "SELECT ReceitaID, ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE ReceitaId = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByPacient()
    {
        $query = "SELECT ReceitaID, ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE PacienteCPF = :Pcpf";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcpf", $this->__get("cpf"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByDoctor()
    {
        $query = "SELECT ReceitaID, ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE MedicoID = :PdoctorId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByPharmacy()
    {
        $query = "SELECT ReceitaID, ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE FarmaciaID = :PpharmacyId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PpharmacyId", $this->__get("pharmacyId"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByDate()
    {
        $query = "SELECT ReceitaID, ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE ReceitaData = :Pdate";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pdate", $this->__get("date"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
