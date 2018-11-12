<?php

namespace App\Framework;


class Router
{
    private $request;
    private $home = array("Usuario", "Login");
    private $login = array("Usuario", "Login");

    public function __construct($request)
    {
        $this->request = $request;
    }

    private function e404()
    {
        $uri = trim($this->request, "/");
        $uri = explode("/", $uri)[0];

        if ($uri == "404") {
            require_once "404.php";
            return true;
        }
        return false;
    }


    private function callController()
    {
        header('Content-Type: text/html; charset=uft-8');
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $changeUri = function (&$uri, $newUri) {
            $temp = $newUri;
            if (count($uri) > 1) {
                for ($i = 1; $i < count($uri); $i++)
                    array_push($temp, $uri[$i]);


            }
            $uri = $temp;
        };

        $uri = trim($this->request, "/");
        $uri = explode("/", $uri);
        switch ($uri[0]) {
            case "":
                $changeUri($uri, $this->home);
                break;
            case "Login":
            case "login":
            case "admin":
                $changeUri($uri, $this->login);
        }

        $controllerNamespacePath = "App\\Web\\Controller\\Controller" . $uri[0];
        $controllerFilePath = "Web/Controller/Controller" . $uri[0] . ".php";

        $action = "action" . $uri[1];

        if (file_exists($controllerFilePath)) {
            $actions = get_class_methods($controllerNamespacePath);

            if (in_array($action, $actions)) {
                try {
                    $obj = new $controllerNamespacePath;

                    $param = array();
                    for ($i = 2; $i < count($uri); $i++)
                        array_push($param, urldecode($uri[$i]));

                    $obj->{$action}($param);
                } catch (CredentialsException $e) {
                    header("HTTP/1.0 404 Not Found");
                    header('location: /Usuario/Login');
                }
            } else
                Utils::e404();
        } else
            Utils::e404();
    }

    private function callResource()
    {
        $uri = trim($this->request, "/");
        $extension = explode(".", $uri);
        $uri = explode("$", $uri);

        if ((count($extension) > 0) && (count($uri) >= 2)) {
            $file = $GLOBALS['view'] . $uri[count($uri) - 1];

            if (file_exists($file)) {
                switch ($extension[count($extension) - 1]) {
                    case "js":
                        header("Content-Type: application/javascript");
                        break;
                    case "css":
                        header("Content-Type: text/css");
                        break;
                    case "woff":
                        header("Content-Type: application/font-woff");
                        break;
                    case "woff2":
                        header("Content-Type: application/font-woff2");
                        break;
                    case "ttf":
                        header("Content-Type: application/x-font-ttf");
                        break;
                    case "svg":
                        header("Content-Type: image/svg+xml");
                        break;
                    case "eot":
                        header("Content-Type: application/vnd.ms-fontobject");
                        break;
                    case "png":
                        header("Content-Type: image/png");
                        break;
                    case "jpg":
                    case "jpeg":
                        header("Content-Type: image/jpeg");
                        break;
                    case "wav":
                        header("Content-Type: audio/x-wav");
                        break;
                    case "aac":
                        header("Content-Type: audio/aac");
                        break;
                    case "avi":
                        header("Content-Type: video/x-msvideo");
                        break;
                    case "mpeg":
                        header("Content-Type: video/mpeg");
                        break;
                    case "webm":
                        header("Content-Type: video/webm");
                        break;
                }

                include $file;
            } else
                header("HTTP/1.0 404 Not Found");
            return true;
        } else
            return false;
    }

    public function call()
    {
        if (!$this->e404())
            if (!$this->callResource())
                $this->callController();
    }
}