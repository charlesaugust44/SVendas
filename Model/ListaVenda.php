<?php

namespace App\Model;

use App\Framework\Model\Table;

class ListaVenda extends Table
{

    private
        $produtoId,
        $vendaId,
        $quantidade,
        $subtotal,
        $desconto;

    /**
     * ListaVenda constructor.
     * @param $produtoId
     * @param $vendaId
     * @param $quantidade
     * @param $subtotal
     * @param $desconto
     */
    public function __construct($produtoId = null, $vendaId = null, $quantidade = null, $subtotal = null, $desconto = null)
    {
        parent::__construct();
        $this->produtoId = $produtoId;
        $this->vendaId = $vendaId;
        $this->quantidade = $quantidade;
        $this->subtotal = $subtotal;
        $this->desconto = $desconto;
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
    public function getVendaId()
    {
        return $this->vendaId;
    }

    /**
     * @param mixed $vendaId
     */
    public function setVendaId($vendaId)
    {
        $this->vendaId = $vendaId;
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
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param mixed $subtotal
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return mixed
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @param mixed $desconto
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
    }


}