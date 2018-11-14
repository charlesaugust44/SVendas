<?php

namespace App\Model;

use App\Framework\Model\Table;

class Venda extends Table
{

    private
        $id,
        $total,
        $eventoId,
        $clienteId;

    /**
     * Venda constructor.
     * @param $total
     * @param $eventoId
     * @param $clienteId
     */
    public function __construct($total = null, $eventoId = null, $clienteId = null)
    {
        parent::__construct();
        $this->total = $total;
        $this->eventoId = $eventoId;
        $this->clienteId = $clienteId;
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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
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

    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * @param mixed $clienteId
     */
    public function setClienteId($clienteId)
    {
        $this->clienteId = $clienteId;
    }


}