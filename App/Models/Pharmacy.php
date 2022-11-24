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
    private $adressState;
    private $adressCity;

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
        $query = "INSERT INTO FARMACIA(FarmaciaNome,FarmaciaCNPJ,FarmaciaEmail,FarmaciaTelefone,FarmaciaSenha, EnderecoID) VALUES (:Pname,:Pcnpj,:Pemail,:Ptel,:Ppassword,:PadressId)";

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

    public function updatePassword()
    {
        $query = "UPDATE FARMACIA SET FarmaciaSenha = :Ppassword WHERE FarmaciaID = :Pid";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Ppassword", $this->__get("password"));
        $stmt->bindValue(":Pid", $this->__get("id"));

        $stmt->execute();

        return $this;
    }

    public function getPharmacyByCNPJ()
    {
        $query = "SELECT FarmaciaID FROM FARMACIA WHERE FarmaciaCNPJ = :Pcnpj";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":Pcnpj", $this->__get("cnpj"));
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

    public function getPharmacyByAdress()
    {
        $query = "SELECT FarmaciaNome, FarmaciaTelefone, EnderecoBairro, EnderecoLogradouro, EnderecoNumero FROM FARMACIA f INNER JOIN ENDERECO e WHERE f.EnderecoID = e.EnderecoId AND e.EnderecoUF = :PadressState AND e.EnderecoCidade = :PadressCity AND f.FarmaciaAtivo = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":PadressState", $this->__get("adressState"));
        $stmt->bindValue(":PadressCity", $this->__get("adressCity"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
