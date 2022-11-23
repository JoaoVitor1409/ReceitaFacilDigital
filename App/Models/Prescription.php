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
    private $start;
    private $limit;

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

    public function dispense()
    {
        $query = "UPDATE RECEITA SET FarmaciaId = :PpharmacyId, ReceitaAtiva = 0 WHERE ReceitaID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->bindValue(":PpharmacyId", $this->__get("pharmacyId"));

        $stmt->execute();

        return $this;
    }

    public function getAllPrescriptions()
    {
        $query = "SELECT ReceitaID, PacienteCPF, DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData FROM RECEITA WHERE 1=1";

        if ($this->__get("doctorId")) {
            $query .= " AND MedicoID = :PdoctorId";
        }
        if ($this->__get("pharmacyId")) {
            $query .= " AND FarmaciaID = :PpharmacyId";
        }
        if ($this->__get("pacientCPF")) {
            $query .= " AND PacienteCPF = :PpacientCPF";
        }

        $query .= " ORDER BY ReceitaID DESC";

        $stmt = $this->db->prepare($query);

        if ($this->__get("doctorId")) {
            $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));
        }
        if ($this->__get("pharmacyId")) {
            $stmt->bindValue(":PpharmacyId", $this->__get("pharmacyId"));
        }
        if ($this->__get("pacientCPF")) {
            $stmt->bindValue(":PpacientCPF", $this->__get("pacientCPF"));
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLastPrescription()
    {
        $query = "SELECT ReceitaID FROM RECEITA WHERE PacienteCPF = :PpacientCPF AND PacienteCelular = :PpacientPhone AND MedicoID = :PdoctorId AND ReceitaAtiva = 1 ORDER BY ReceitaID DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PpacientCPF", $this->__get("pacientCPF"));
        $stmt->bindValue(":PpacientPhone", $this->__get("pacientPhone"));
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionById()
    {
        $query = "SELECT DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE ReceitaId = :Pid AND ReceitaAtiva = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionHistoryById()
    {
        $query = "SELECT DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE ReceitaId = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByPacient()
    {
        $query = "SELECT ReceitaID, DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE PacienteCPF = :Pcpf AND ReceitaAtiva = 1 ORDER BY ReceitaID DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcpf", $this->__get("pacientCPF"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByDoctor()
    {
        $query = "SELECT ReceitaID, DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE MedicoID = :PdoctorId AND ReceitaAtiva = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByPharmacy()
    {
        $query = "SELECT ReceitaID, DATE_FORMAT(ReceitaData, '%d/%m/%Y') as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE FarmaciaID = :PpharmacyId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PpharmacyId", $this->__get("pharmacyId"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByFilter()
    {
        $query = "SELECT ReceitaID, DATE_FORMAT(ReceitaData, '%d/%m/%Y')as ReceitaData, PacienteCPF, MedicoID FROM RECEITA WHERE ReceitaAtiva = 1";

        if ($this->__get("id")) {
            $query .= " AND ReceitaID = :Pid";
        }
        if ($this->__get("pacientCPF")) {
            $query .= " AND PacienteCPF = :Pcpf";
        }
        if ($this->__get("date")) {
            $query .= " AND DATE_FORMAT(ReceitaData, '%d/%m/%Y') = DATE_FORMAT(:Pdate, '%d/%m/%Y')";
        }

        $stmt = $this->db->prepare($query);

        if ($this->__get("id")) {
            $stmt->bindValue(":Pid", $this->__get("id"));
        }
        if ($this->__get("pacientCPF")) {
            $stmt->bindValue(":Pcpf", $this->__get("pacientCPF"));
        }
        if ($this->__get("date")) {
            $stmt->bindValue(":Pdate", $this->__get("date"));
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
