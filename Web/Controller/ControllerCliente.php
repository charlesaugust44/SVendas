<?php

namespace App\Web\Controller;

use App\Framework\Authentication\Auth;
use App\Framework\Persistence\Manager;
use App\Model\Cliente;
use App\Model\Estoque;
use App\Model\Evento;
use App\Framework\View\View;
use App\Model\Graduacao;
use App\Model\ListaVenda;
use App\Model\Produto;
use App\Model\Venda;

class ControllerCliente
{
    private $manager;
    private $view;

    public function __construct()
    {
        Auth::sessionCheck(Auth::$LVL1);
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionShowCadastrar($param)
    {
        $id = intval($param[0]);
        $cpf = htmlspecialchars($_POST["cpf"]);
        $total = htmlspecialchars($_POST["total"]);
        $graduacaoId = intval($_POST["graduacao"]);
        $compra = $_POST["compra"];

        $compra = array_map(function ($c) {
            return array_map(function ($e) {
                return htmlspecialchars($e);
            }, $c);
        }, $compra);

        $graduacao = $this->manager->select(new Graduacao(), $graduacaoId)[0];
        $evento = $this->manager->select(new Evento(), $id)[0];
        $cliente = $this->manager->select(new Cliente(), $cpf, "cpf");
        $exists = false;

        if ($cliente == null)
            $cliente = new Cliente();
        else {
            $cliente = $cliente[0];
            $exists = $cliente->getId();
        }

        $this->view->load("CadastrarForm");

        $this->view->parseEcho("title", "Dados do Cliente");
        $this->view->parseEcho("exists", $exists);
        $this->view->parseEcho("cpf", $cpf);
        $this->view->parseEcho("total", $total);
        $this->view->parseEcho("graduacao", $graduacao->getNome());
        $this->view->parseEcho("graduacaoId", $graduacaoId);
        $this->view->parseObject("cliente", $cliente);
        $this->view->parseObject("evento", $evento);
        $this->view->parseIterator("compra", $compra);

        $this->view->show();
    }

    public function actionCadastrar($param)
    {
        $exists = intval($_POST["exists"]);
        $cpf = htmlspecialchars($_POST["cpf"]);
        $graduacao = intval($_POST["graduacao"]);
        $compra = $_POST["compra"];
        $idEvento = intval($param[0]);

        $cliente = new Cliente($cpf, $_POST["idh"], $_POST["nome"], $_POST["email"], $_POST["telefone"], $graduacao);

        if ($exists > 0) {
            $this->manager->update($cliente, $cpf, "cpf");
        } else {
            $exists = $this->manager->insert($cliente);
        }

        $desconto = $this->manager->select(new Graduacao(), $graduacao)[0]->getDesconto();
        $total = 0;

        $compra = array_map(function ($c) use (&$total, $desconto) {
            $com["qtd"] = intval($c["qtd"]);
            $com["id"] = intval($c["id"]);
            $com["idProduto"] = intval($c["idProduto"]);

            $estoque = $this->manager->select(new Estoque(), $com["id"])[0];
            $estoque->setQuantidade($estoque->getQuantidade() - $com["qtd"]);

            $this->manager->update($estoque, $com["id"]);
            $produto = $this->manager->select(new Produto(), $com["idProduto"])[0];

            $com["subtotal"] = ($produto->getPreco() - (($desconto == 1) ? $produto->getPrecoDesconto() : 0)) * $com["qtd"];
            $total += $com["subtotal"];

            return $com;
        }, $compra);

        $venda = new Venda($total, $idEvento, $exists);
        $idVenda = $this->manager->insert($venda);

        foreach ($compra as $k => $c) {
            $listaVenda = new ListaVenda($c["idProduto"], $idVenda, $c["qtd"], $c["subtotal"], $desconto);
            $this->manager->insert($listaVenda);
        }

        header("location: /Venda/Vender/" . $idEvento);
    }
}
