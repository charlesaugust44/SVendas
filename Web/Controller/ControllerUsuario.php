<?php

namespace App\Web\Controller;

use App\Framework\Persistence\Manager;
use App\Model\Usuario;
use App\Framework\View\View;
use App\Framework\Authentication\Auth;
use App\Framework\Utils;

class ControllerUsuario
{
    private $manager;
    private $view;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionAuthenticate($param)
    {
        $usuario = $_POST["usuario"];
        $senha = $_POST["senha"];

        $user = $this->manager->select(new Usuario(), $usuario, "usuario");

        $userExist = $user != null;

        if (($userExist) && (Utils::encrypt($senha) == $user[0]->getSenha())) {
            Auth::createSession(Auth::$LVL1, $user[0]->getNome(), $user[0]->getId());
            header("location: /Evento/Lista");
        } else
            header("location: /Login/Error");
    }

    public function actionLogin($param)
    {
        $error = "";
        if (isset($param[0]) && $param[0] == "Error")
            $error = "inputError";

        $this->view->load("Login");
        $this->view->parseEcho("error", $error);
        $this->view->show();
    }

    public function actionLogout($param)
    {
        Auth::sessionCheck();
        Auth::destroySession();
        header('location: /Login');
    }
}
