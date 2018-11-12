<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Estoque;
use App\Framework\View\View;
use App\Framework\Utils;
use App\Model\Produto;

class ControllerEstoque
{
    private $manager;
    private $view;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionGerenciar($param)
    {
        if (isset($param[0])) {
            $id = intval($param[0]);

            $mapProdutos = function ($p) use ($id) {
                $qtd = 0;
                $estoque = $this->manager->select(new Estoque(), $p->getId(), "produtoId", $id, "eventoId");

                if ($estoque != null)
                    $qtd = $estoque[0]->getQuantidade();

                return Array("nome" => $p->getNome(), "qtd" => $qtd, "id" => $p->getId());
            };

            $produtos = $this->manager->select(new Produto());
            $produtos = array_map($mapProdutos, $produtos);

            $this->view->load("Gerenciar");

            $this->view->parseConditional("save", isset($param[1]) && $param[1] == "Success");

            $this->view->parseIterator("produtos", $produtos);

            $this->view->parseEcho("title", "Gerenciar Estoque");
            $this->view->parseEcho("idEvento", $id);


            $this->view->show();
        } else
            Utils::e404();
    }

    public function actionCadastrar($param)
    {
        if (isset($param[0])) {
            $id = intval($param[0]);
            $prv = $_POST['prv'];
            $qtd = $_POST['qtd'];

            foreach ($qtd as $k => $q) {
                if ($q != $prv[$k]) {
                    $q = intval($q);

                    $estoque = $this->manager->select(new Estoque(), $id, "eventoId", $k, "produtoId");

                    if ($estoque != null) {
                        $r = new Estoque();
                        $r->setQuantidade($q);
                        $this->manager->update($r, $estoque[0]->getId());
                        echo $k . " " . $id . "<br>";
                    } else {
                        $estoque = new Estoque($q, $k, $id);
                        $this->manager->insert($estoque);
                    }
                }
            }


            header("location: /Estoque/Gerenciar/" . $id . "/Success");
        } else
            Utils::e404();
    }
}