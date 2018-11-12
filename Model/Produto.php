<?php

namespace App\Model;

use App\Framework\Model\Table;

class Produto extends Table
{

    private
        $id,
        $nome,
        $preco,
        $precoDesconto;

    /**
     * Produto constructor.
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
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * @param mixed $preco
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    /**
     * @return mixed
     */
    public function getPrecoDesconto()
    {
        return $this->precoDesconto;
    }

    /**
     * @param mixed $precoDesconto
     */
    public function setPrecoDesconto($precoDesconto)
    {
        $this->precoDesconto = $precoDesconto;
    }


}