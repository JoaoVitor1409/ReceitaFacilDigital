<?php

namespace App\Models;

use MF\Model\Model;

class Adress extends Model
{
    private $id;
    private $cep;
    private $state;
    private $city;
    private $district;
    private $street;
    private $number;
    private $complement;


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
        $query = "INSERT INTO ENDERECO(EnderecoCEP,EnderecoUF,EnderecoCidade,EnderecoBairro,EnderecoLogradouro,EnderecoNumero,EnderecoComplemento) VALUES (:Pcep,:Pstate,:Pcity,:Pdistrict,:Pstreet,:Pnumber,:Pcomplement)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcep", $this->__get("cep"));
        $stmt->bindValue(":Pstate", $this->__get("state"));
        $stmt->bindValue(":Pcity", $this->__get("city"));
        $stmt->bindValue(":Pdistrict", $this->__get("district"));
        $stmt->bindValue(":Pstreet", $this->__get("street"));
        $stmt->bindValue(":Pnumber", $this->__get("number"));
        $stmt->bindValue(":Pcomplement", $this->__get("complement"));

        $stmt->execute();

        return $this;
    }

    public function getAdressById()
    {
        $query = "SELECT EnderecoCEP,EnderecoUF,EnderecoCidade,EnderecoBairro,EnderecoLogradouro,EnderecoNumero,EnderecoComplemento FROM ENDERECO WHERE EnderecoID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pid", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
