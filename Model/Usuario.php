<?php

namespace App\Model;

class Usuario extends Table
{

    private
        $id,
        $usuario,
        $nome,
        $senha,
        $email;

    /**
     * Usuario constructor.
     * @param $id
     * @param $usuario
     * @param $nome
     * @param $senha
     * @param $email
     */
    public function __construct($id = null, $usuario = null, $nome = null, $senha = null, $email = null)
    {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->nome = $nome;
        $this->senha = $senha;
        $this->email = $email;
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
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
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


}