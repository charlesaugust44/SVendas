<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Evento;
use App\Framework\View\View;
use App\Framework\Authentication\Auth;
use App\Framework\Utils;

class ControllerEvento
{
    private $manager;
    private $view;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionLista($param)
    {
        $eventos = $this->manager->select(new Evento());

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
}