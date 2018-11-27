<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Estoque;
use App\Model\Evento;
use App\Model\Graduacao;
use App\Model\Produto;
use App\Model\Venda;
use App\Framework\View\View;
use App\Framework\Authentication\Auth;
use App\Framework\Utils;

class ControllerVenda
{
    private $manager;
    private $view;

    public function __construct()
    {
        Auth::sessionCheck(Auth::$LVL1);
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionVender($param)
    {
        if (isset($param[0])) {
            $id = intval($param[0]);

            $evento = $this->manager->select(new Evento(), $id);
            $graduacao = $this->manager->select(new Graduacao());

            if ($evento != null) {
                $evento = $evento[0];
                $this->view->load("Vender");

                $this->view->parseIterator("graduacao", $graduacao);
                $this->view->parseEcho("title", $evento->getNome());
                $this->view->parseEcho("id", $evento->getId());
                $this->view->parseObject("evento", $evento);

                $this->view->show();

            } else
                Utils::e404();
        } else
            Utils::e404();
    }

    public function actionListaEstoque($param)
    {
        if (isset($param[0])) {
            $id = intval($param[0]);

            $mapProdutos = function ($p) use ($id) {
                $qtd = 0;
                $qtd = 0;
                $eid = 0;
                $estoque = $this->manager->select(new Estoque(), $p->getId(), "produtoId", $id, "eventoId");
                if ($estoque != null) {
                    $qtd = $estoque[0]->getQuantidade();
                    $eid = $estoque[0]->getId();
                }

                $preco = number_format($p->getPreco(), 2, '.', '');
                $idProduto = $p->getId();

                return Array("nome" => $p->getNome(), "qtd" => $qtd, "id" => $eid, "preco" => $preco, "idProduto" => $idProduto, "precoDesc" => $p->getPrecoDesconto());
            };

            $es = $this->manager->select(new Estoque(), $id, "eventoId");

            if (count($es) > 0) {
                $produtos = $this->manager->select(new Produto());
                $produtos = array_map($mapProdutos, $produtos);
                $produtos = array_filter($produtos, function ($p) {
                    return ($p["qtd"] == 0) ? false : true;
                });
            } else
                $produtos = Array();

            $this->view->load("ListaEstoque");
            $this->view->parseIterator("produtos", $produtos);
            $this->view->parseIterator("produtosVar", $produtos);
            $this->view->show();
        } else
            Utils::e404();
    }
}