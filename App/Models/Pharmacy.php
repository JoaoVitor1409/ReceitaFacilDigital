<?php

namespace App\Models;

use MF\Model\Model;

class Pharmacy extends Model
{
    private $id;
    private $name;
    private $cnpj;
    private $email;
    private $tel;
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
        $query = "INSERT INTO FARMACIA(FarmaciaNome,FarmaciaCPNJ,FarmaciaEmail,FarmaciaTelefone,FarmaciaSenha, EnderecoID) VALUES (:Pname,:Pcnpj,:Pemail,:Ptel,:Ppassword,:PadressId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pname", $this->__get("name"));
        $stmt->bindValue(":Pcnpj", $this->__get("cnpj"));
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->bindValue(":Ptel", $this->__get("tel"));
        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt->bindValue(":PadressId", $this->__get("adressId"));

        $stmt->execute();

        return $this;
    }

    public function authenticate()
    {
        $query = "SELECT FarmaciaID, FarmaciaNome FROM FARMACIA WHERE FarmaciaEmail = :Pemail AND FarmaciaSenha = :Ppassword AND FarmaciaAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->bindValue(":Ppassword", $this->__get("password"));

        $stmt->execute();

        $pacient = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($pacient["FarmaciaID"] && $pacient["FarmaciaNome"]) {
            $this->__set("id", $pacient["FarmaciaID"]);
            $this->__set("name", $pacient["FarmaciaNome"]);
        }

        return $this;
    }

    public function getPharmacyByCRM()
    {
        $query = "SELECT FarmaciaID FROM FARMACIA WHERE FarmaciaCNPJ = :Pcnpj";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcnpj", $this->__get("cnpj"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPharmacyByEmail()
    {
        $query = "SELECT FarmaciaID FROM FARMACIA WHERE FarmaciaEmail = :Pemail";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pemail", $this->__get("email"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPharmacyByTel()
    {
        $query = "SELECT FarmaciaID FROM FARMACIA WHERE FarmaciaTelefone = :Ptel";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Ptel", $this->__get("tel"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
