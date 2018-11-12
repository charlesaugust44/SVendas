<?php

namespace App\Model;

use App\Framework\Model\Table;

class Estoque extends Table
{

    private
        $id,
        $quantidade,
        $produtoId,
        $eventoId;

    /**
     * Estoque constructor.
     * @param $quantidade
     * @param $produtoId
     * @param $eventoId
     */
    public function __construct($quantidade = null, $produtoId = null, $eventoId = null)
    {
        parent::__construct();
        $this->quantidade = $quantidade;
        $this->produtoId = $produtoId;
        $this->eventoId = $eventoId;
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
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return mixed
     */
    public function getProdutoId()
    {
        return $this->produtoId;
    }

    /**
     * @param mixed $produtoId
     */
    public function setProdutoId($produtoId)
    {
        $this->produtoId = $produtoId;
    }

    /**
     * @return mixed
     */
    public function getEventoId()
    {
        return $this->eventoId;
    }

    /**
     * @param mixed $eventoId
     */
    public function setEventoId($eventoId)
    {
        $this->eventoId = $eventoId;
    }


}