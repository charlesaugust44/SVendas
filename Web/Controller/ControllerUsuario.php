<?php

namespace App\Web\Controller;

use App\Persistence\Manager;
use App\Model\Usuario;
use App\Web\View\View;
use App\Auth;

class ControllerUsuario
{
    private $manager;
    private $view;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->view = new View($this);
    }

    public function actionShowEditar($param)
    {
        $user = $this->manager->select(new Usuario());

        $this->view->load("ShowEditar");
        $this->view->insertData("user",$user[0]);
        $this->view->insertData("users",$user,true);
        $this->view->show();
    }

    public function actionLogin($param)
    {
        $u = new Usuario(null,"aaa","aaa","aaaa","a@aa");

        $arr = $this->manager->select($u);

        foreach ($arr as $a)
        {
            print_r($a->toArray()["data"]);
            echo "<br>";
        }

        //$this->view->call('login', $this);
    }

    public function actionLogout($param)
    {
        Auth::sessionCheck();
        Auth::destroySession();
        header('location: /Login');
    }
}
