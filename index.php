<?php

namespace App;

$persistenceConnection = null;
$view = "Web/View/Assets/";

spl_autoload_register(function ($name) {
    $name = substr($name,4)  . '.php';
    require_once $name;
});

$router = new Router($_SERVER['REQUEST_URI']);
$router->call();

if($persistenceConnection != null)
    $persistenceConnection->disconnect();