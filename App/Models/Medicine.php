<?php

namespace App\Models;

use MF\Model\Model;

class Medicine extends Model
{
    private $id;
    private $desc;
    private $size;
    private $frequency;
    private $obs;
    private $templateId;
    private $prescriptionId;
    private $start;


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
        if ($this->__get("templateId")) {
            $query = "INSERT INTO MODELO_MEDICAMENTO(MedicamentoDesc,MedicamentoDosagem,MedicamentoFrequencia,MedicamentoObs,ModeloId) VALUES (:Pdesc,:Psize,:Pfrequency,:Pobs,:PtemplateId)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":PtemplateId", $this->__get("templateId"));
        } elseif ($this->__get("prescriptionId")) {
            $query = "INSERT INTO RECEITA_MEDICAMENTO(MedicamentoDesc,MedicamentoDosagem,MedicamentoFrequencia,MedicamentoObs,ReceitaId) VALUES (:Pdesc,:Psize,:Pfrequency,:Pobs,:PprescriptionId)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":PprescriptionId", $this->__get("prescriptionId"));
        }

        $stmt->bindValue(":Pdesc", $this->__get("desc"));
        $stmt->bindValue(":Psize", $this->__get("size"));
        $stmt->bindValue(":Pfrequency", $this->__get("frequency"));
        $stmt->bindValue(":Pobs", $this->__get("obs"));

        $stmt->execute();

        return $this;
    }

    public function update()
    {
        $query = "UPDATE MODELO_MEDICAMENTO SET MedicamentoDesc = :Pdesc, MedicamentoDosagem = :Psize, MedicamentoFrequencia = :Pfrequency, MedicamentoObs = :Pobs WHERE MedicamentoID = :Pid AND ModeloID = :PtemplateId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->bindValue(":Pdesc", $this->__get("desc"));
        $stmt->bindValue(":Psize", $this->__get("size"));
        $stmt->bindValue(":Pfrequency", $this->__get("frequency"));
        $stmt->bindValue(":Pobs", $this->__get("obs"));
        $stmt->bindValue(":PtemplateId", $this->__get("templateId"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $query = "DELETE FROM MODELO_MEDICAMENTO WHERE MedicamentoID = :Pid AND ModeloID = :PtemplateId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->bindValue(":PtemplateId", $this->__get("templateId"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMedicineByTemplate()
    {
        $query = "SELECT MedicamentoID, MedicamentoDesc,MedicamentoDosagem,MedicamentoFrequencia,MedicamentoObs FROM MODELO_MEDICAMENTO WHERE ModeloID = :PtemplateId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PtemplateId", $this->__get("templateId"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMedicineByPrescription()
    {
        $query = "SELECT MedicamentoID, MedicamentoDesc,MedicamentoDosagem,MedicamentoFrequencia,MedicamentoObs FROM RECEITA_MEDICAMENTO WHERE ReceitaID = :PprescriptionId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PprescriptionId", $this->__get("prescriptionId"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
