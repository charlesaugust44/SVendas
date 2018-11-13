<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Cliente;
use App\Model\Evento;
use App\Framework\View\View;

class ControllerCliente
{
    private $manager;
    private $view;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionShowCadastrar($param)
    {
        $cpf = "048.147.345-90";
        $graduacao = 1;

        $cliente = $this->manager->select(new Cliente(), $cpf, "cpf");
        $exists = false;

        if (count($cliente) == 0)
            $cliente = new Cliente();
        else {
            $exists = true;
            $cliente = $cliente[0];
        }

        $this->view->load("CadastrarForm");

        $this->view->parseEcho("title", "Dados do Cliente");
        $this->view->parseObject("cliente", $cliente);
        $this->view->parseEcho("exists", $exists);

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
}