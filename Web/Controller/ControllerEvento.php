<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Evento;
use App\Framework\View\View;
use App\Framework\Authentication\Auth;
use App\Framework\Utils;
use App\Model\ListaVenda;
use App\Model\Produto;
use App\Model\Venda;

class ControllerEvento
{
    private $manager;
    private $view;

    public function __construct()
    {
        Auth::sessionCheck(Auth::$LVL1);
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionLista($param)
    {
        $eventos = $this->manager->select(new Evento());

        if ($eventos != null) {
            $eventos = array_map(function ($e) {
                $vendas = $this->manager->select(new Venda());
                $cld = ($e->getEncerrado()) ? "disabled" : "";
                $del = ($vendas != null) ? "disabled" : "";

                return Array("cld" => $cld, "del" => $del, "getNome" => $e->getNome(), "getCidade" => $e->getCidade(), "getData" => $e->getData(), "getId" => $e->getId());
            }, $eventos);
        } else
            $eventos = Array();


        $this->view->load("Lista");

        $this->view->parseIterator("evento", $eventos);

        $this->view->parseEcho("title", "Lista de Eventos");
        $this->view->parseEcho("context", "evento");
        $this->view->parseEcho("deleteFrom", "Evento");

        $this->view->show();
    }

    public function actionShowCadastrar($param)
    {
        $this->view->load("CadastrarForm");

        $this->view->parseEcho("title", "Cadastrar Evento");
        $this->view->parseEcho("context", "evento");

        $this->view->show();
    }

    public function actionCadastrar($param)
    {
        $nome = $_POST['nome'];
        $cidade = $_POST['cidade'];
        $data = $_POST['data'];

        $object = new Evento($nome, $cidade, $data);

        $this->manager->insert($object);

        header("location: /Evento/ShowCadastrar");
    }

    public function actionDeletar($param)
    {
        if (isset($param[0])) {
            $id = intval($param[0]);

            $this->manager->delete(new Evento(), $id);
            header("location: /Evento/Lista");
        } else
            Utils::e404();
    }

    public function actionConfirmarRelatorio($param)
    {
        $idEvento = intval($param[0]);

        $evento = $this->manager->select(new Evento(), $idEvento);

        if ($evento != null) {
            if ($evento[0]->getEncerrado()) {
                header("location: /Evento/Relatorio/" . $idEvento);
            } else {
                $this->view->load("Confirmar");

                $this->view->parseEcho("title", "Confirmar Relatorio");
                $this->view->parseEcho("nomeEvento", $evento[0]->getNome());
                $this->view->parseEcho("id", $idEvento);

                $this->view->show();
            }
        } else
            Utils::e404();
    }

    public function actionRelatorio($param)
    {
        $idEvento = intval($param[0]);

        $evento = new Evento();
        $evento->setEncerrado(true);
        $this->manager->update($evento, $idEvento);

        $this->view->load("Relatorio");

        $this->view->parseEcho("title", "Relatorio");
        $this->view->parseEcho("id", $idEvento);

        $this->view->show();
    }

    public function actionIframeRel($param)
    {
        $idEvento = intval($param[0]);

        $evento = $this->manager->select(new Evento(), $idEvento)[0];

        $tot = $this->manager->query("
        SELECT
	      sum((select count(produtoId) from listavenda where vendaid = v.id)) as qtdTotal,
	      sum((select sum(subtotal) from listavenda where vendaid = v.id)) as receitaTotal
        FROM venda v	
        WHERE v.eventoid = $idEvento");

        $data = new \DateTime($evento->getData());
        $data = date("d/m/Y", $data->getTimestamp());
        $repasse = 0;

        $desconto = Array();
        $semDesconto = Array();

        //-----------------------------------------------------
        // qtdSemDesc / qtdComDesc / subtotalSem / subtotalCom
        // repasseSem / repasseCom / nomeProduto / precoLista / tableClientes
        $produtos = $this->manager->select(new Produto());

        foreach ($produtos as $p) {
            $vs = $this->manager->query("
            SELECT
	          l.produtoId
            FROM listavenda l
            INNER JOIN venda v
	          ON v.id = l.vendaid
            WHERE l.produtoId = " . $p->getId() . " AND v.eventoid = " . $idEvento . "
            GROUP BY l.produtoId
            ");

            if (isset($vs[0])) {
                $new = Array();

                $sql = "SELECT	l.quantidade, l.subtotal, c.nome, c.cpf, c.idh, c.email, (SELECT nome FROM graduacao WHERE id = c.graduacaoId) as graduacao FROM listavenda l INNER JOIN venda v ON v.id = l.vendaId INNER JOIN cliente c ON c.id = v.clienteId WHERE v.eventoId = $idEvento AND l.produtoId = " . $p->getId();
                $result = $this->manager->query($sql);

                $tableClientes = "<table class='table'><tr><th>Nome</th><th>Email</th><th>Graduação</th><th>ID</th><th>Qtd.</th><th>Subtotal</th></tr>";

                foreach ($result as $r) {
                    $tableClientes .= "<tr>
                <td>" . $r['nome'] . "</td>
                <td>" . $r['email'] . "</td>
                <td>" . $r['graduacao'] . "</td>
                <td>" . $r['idh'] . "</td>
                <td>x" . $r['quantidade'] . "</td>
                <td>R$" . number_format($r['subtotal'], 2, ',', '') . "</td>
            </tr>";
                }

                $new["tableClientes"] = $tableClientes;
                $new["nomeProduto"] = $p->getNome();

                if ($p->getPrecoDesconto() == 0) {
                    $new["precoLista"] = "Sem desconto: R$" . $p->getPreco();
                    $totais = $this->manager->query("
                    SELECT
                      sum(l.quantidade * l.subtotal) as total,
                      sum(l.quantidade) as qtd
                    FROM listavenda l
                    INNER JOIN venda v
                      ON v.id = l.vendaId
                    INNER JOIN produto p
                      ON l.produtoId = p.id
                    WHERE v.eventoId = " . $idEvento . " AND l.produtoId = " . $p->getId());

                    $new["qtdSemDesc"] = $totais[0]["qtd"];

                    $new["subtotalSem"] = $totais[0]["total"];

                    $new["repasseSem"] = $totais[0]["total"] * 0.15;
                    $repasse += $new["repasseSem"];

                    array_push($semDesconto, $new);
                } else {
                    $new["precoLista"] = "Sem desconto: R$" . $p->getPreco() . " | Com desconto: R$" . ($p->getPreco() - $p->getPrecoDesconto());
                    $totais = $this->manager->query("
                    SELECT
	                  sum(l.quantidade * l.subtotal) as total,
	                  sum(l.quantidade) as qtd,
	                  l.desconto
                    FROM listavenda l
                    INNER JOIN venda v
	                  ON v.id = l.vendaId
                    INNER JOIN produto p
	                  ON l.produtoId = p.id
                    WHERE v.eventoId = ".$idEvento." AND l.produtoId = ".$p->getId()."
                    GROUP BY l.desconto
                    ORDER BY desconto ");

                    $new["qtdComDesc"] = 0;
                    $new["subtotalCom"] = 0;
                    $new["repasseCom"] = 0;
                    $new["qtdSemDesc"] = 0;
                    $new["subtotalSem"] = 0;
                    $new["repasseSem"] = 0;

                    foreach ($totais as $t) {
                        if ($t["desconto"] == 1) {
                            $new["qtdComDesc"] = @intval($t["qtd"]);
                            $new["subtotalCom"] = @number_format($t["total"], 2, ',', '');
                            $new["repasseCom"] = @number_format($t["total"] * 0.1, 2, ',', '');
                            $repasse += $new["repasseCom"];
                        } else {
                            $new["qtdSemDesc"] = @intval($t["qtd"]);
                            $new["subtotalSem"] = @number_format($t["total"], 2, ',', '');
                            $new["repasseSem"] = @number_format($t["total"] * 0.15, 2, ',', '');
                            $repasse += $new["repasseSem"];
                        }


                    }
                    array_push($desconto, $new);
                }
            }
        }


        //-----------------------------------------------------

        $this->view->load("IframeRel");

        $this->view->parseObject("evento", $evento);
        $this->view->parseEcho("data", $data);
        $this->view->parseEcho("qtdTotal", $tot[0]["qtdTotal"]);
        $this->view->parseEcho("receitaTotal", number_format($tot[0]["receitaTotal"], 2, ',', ''));
        $this->view->parseEcho("repasseTotal", number_format($repasse, 2, ',', ''));
        $this->view->parseIterator("desconto", $desconto);
        $this->view->parseIterator("semDesconto", $semDesconto);

        $this->view->show();
    }
}
