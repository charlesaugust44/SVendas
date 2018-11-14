<?php

namespace App\Model;

use App\Framework\Model\Table;

class Cliente extends Table
{

    private
        $id,
        $cpf,
        $idh,
        $nome,
        $email,
        $telefone,
        $graduacaoId;

    /**
     * Cliente constructor.
     * @param $cpf
     * @param $idh
     * @param $nome
     * @param $email
     * @param $telefone
     * @param $graduacaoId
     */
    public function __construct($cpf = null, $idh = null, $nome = null, $email = null, $telefone = null, $graduacaoId = null)
    {
        parent::__construct();
        $this->cpf = $cpf;
        $this->idh = $idh;
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->graduacaoId = $graduacaoId;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getIdh()
    {
        return $this->idh;
    }

    /**
     * @param mixed $idh
     */
    public function setIdh($idh)
    {
        $this->idh = $idh;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getGraduacaoId()
    {
        return $this->graduacaoId;
    }

    /**
     * @param mixed $graduacaoId
     */
    public function setGraduacaoId($graduacaoId)
    {
        $this->graduacaoId = $graduacaoId;
    }

}