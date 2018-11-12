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
        $graduacaoId;

    /**
     * Cliente constructor.
     */
    public function __construct()
    {
        parent::__construct();
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