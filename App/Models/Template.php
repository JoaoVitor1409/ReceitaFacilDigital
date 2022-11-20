<?php

namespace App\Models;

use MF\Model\Model;

class Template extends Model
{
    private $id;
    private $name;
    private $active;
    private $doctorId;

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
        $query = "INSERT INTO MODELO(ModeloNome,MedicoID) VALUES (:Pname,:PdoctorId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pname", $this->__get("name"));
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));

        $stmt->execute();

        return $this;
    }

    public function update()
    {
        $query = "UPDATE FROM MODELO SET ModeloNome = :Pname WHERE ModeloID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->bindValue(":Pname", $this->__get("name"));

        $stmt->execute();

        return $this;
    }

    public function delete()
    {
        $query = "UPDATE FROM MODELO SET ModeloAtivo = 0 WHERE ModeloID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $this;
    }

    public function getTemplateByDoctor()
    {
        $query = "SELECT ModeloNome FROM MODELO WHERE MedicoID = :PdoctorId AND ModeloAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PdoctorId", $this->__get("doctorId"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTemplateByID()
    {
        $query = "SELECT ModeloNome FROM MODELO WHERE ModeloID = :Pid AND ModeloAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
