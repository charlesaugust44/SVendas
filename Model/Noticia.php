<?php

namespace App\Model;

class Noticia extends Table
{
    private
        $id,
        $titulo,
        $corpo,
        $imagem;

    /**
     * Noticia constructor.
     * @param $id
     * @param $titulo
     * @param $corpo
     * @param $imagem
     */
    public function __construct($id = null, $titulo = null, $corpo = null, $imagem = null)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->corpo = $corpo;
        $this->imagem = $imagem;
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
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getCorpo()
    {
        return $this->corpo;
    }

    /**
     * @param mixed $corpo
     */
    public function setCorpo($corpo)
    {
        $this->corpo = $corpo;
    }

    /**
     * @return mixed
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param mixed $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }
}