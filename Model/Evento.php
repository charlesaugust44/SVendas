<?php

namespace App\Model;

use App\Framework\Model\Table;

class Evento extends Table
{

    private
        $id,
        $nome,
        $cidade,
        $data,
        $encerrado;

    public function __construct($nome = null, $cidade = null, $data = null)
    {
        parent::__construct();

        $this->nome = $nome;
        $this->cidade = $cidade;
        $this->data = $data;
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
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getEncerrado()
    {
        return $this->encerrado;
    }

    /**
     * @param mixed $encerrado
     */
    public function setEncerrado($encerrado)
    {
        $this->encerrado = $encerrado;
    }


}