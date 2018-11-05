<?php

namespace App\Web\View;


class View
{
    private $aliases;
    private $context;
    private $source;

    public function __construct($context)
    {
        $this->aliases = array();

        $class = get_class($context);
        $pos = strpos($class, "Controller", 17);
        $this->context = substr($class, $pos + 10);
    }

    public function load($viewName)
    {
        $html = file_get_contents("Web/View/" . $this->context . "/" . $viewName . ".html");
        $this->source = $html;
        $posOpen = 0;
        $posClose = null;

        while (true) {
            $posOpen = strpos($html, "{{", $posOpen + 2);
            if ($posOpen !== false) {
                $posClose = strpos($html, "}}", $posOpen);

                $alias = substr($html, $posOpen + 2, $posClose - $posOpen - 2);
                $aliasEx = explode(".", $alias);
                $exist = false;

                $walk = function ($v, $k) use ($alias, &$exist) {
                    if ($v->getFullName() == $alias)
                        $exist = true;
                };

                array_walk($this->aliases, function ($v, $k) use ($walk) {
                    array_walk($v, $walk);
                });

                if (!$exist) {
                    if ($aliasEx[0] == "iterator") {
                        $frame = explode("@@", $alias);
                        $index = explode("@@", $aliasEx[1])[0];

                        $a = Array($index => new Alias($frame[0], $frame[1]));
                    } else
                        $a = new Alias($alias);

                    if (!isset($this->aliases[$aliasEx[0]]))
                        $this->aliases[$aliasEx[0]] = Array();

                    array_push($this->aliases[$aliasEx[0]], $a);
                }
            } else
                break;
        }
    }

    public function insertData($index, $data, $iterable = false)
    {
        if ($iterable) {
           $a = $this->aliases["iterator"][0][$index];

            $posOpen = 0;
            $posClose = null;
            $frame = $a->getFrame();
            $iAlias = Array();

            while (true) {
                $posOpen = strpos($frame, "[[", $posOpen + 2);
                if ($posOpen !== false) {
                    $posClose = strpos($frame, "]]", $posOpen);

                    $alias = substr($frame, $posOpen + 2, $posClose - $posOpen - 2);

                    array_push($iAlias,$alias);
                } else
                    break;
            }

            $iterated = "";

            foreach ($data as $d){
                $r = $frame;
                foreach ($iAlias as $i) {
                    $r = str_replace("[[" . $i . "]]", $d->{$i}(), $r);
                }
                $iterated .= $r;
            }

            $a->replace($this->source,$iterated);

        } else {
            foreach ($this->aliases[$index] as $alias)
                $alias->replace($this->source, $data->{$alias->getName()}());
        }

    }

    public function show(){
        echo $this->source;
    }
}