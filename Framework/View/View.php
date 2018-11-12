<?php

namespace App\Framework\View;


class View
{
    private $aliases;
    private $context;
    private $source;
    public static
        $OBJECT = 0,
        $ITERATOR = 1,
        $ECHO = 2,
        $ITERATOR_ARRAY = 3,
        $IF;

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
        $start = -2;
        $end = null;

        while (true) {
            $start = strpos($this->source, "{{", $start + 2);
            if ($start !== false) {
                $end = strpos($this->source, "}}", $start);

                $frame = substr($this->source, $start + 2, $end - $start - 2);
                $frameArray = explode(".", $frame);

                if ($frameArray[0] == "include") {
                    $includeContent = file_get_contents("Web/View/Template/" . $frameArray[1] . ".html");

                    $this->source = str_replace("{{" . $frame . "}}", $includeContent, $this->source);
                    $start -= 2;
                } else {
                    $alias = null;

                    switch ($frameArray[0]) {
                        case "if":
                            $alias = new Conditional($frame);

                            if (!isset($this->aliases["if"]))
                                $this->aliases["if"] = Array();

                            $this->aliases["if"][$alias->getName()] = $alias;
                            break;
                        case "iterator":
                            $alias = new Iterator($frame);

                            if (!isset($this->aliases["iterator"]))
                                $this->aliases["iterator"] = Array();

                            $this->aliases["iterator"][$alias->getName()] = $alias;
                            break;
                        case "echo":
                            $alias = new Output($frame);

                            if (!isset($this->aliases["echo"]))
                                $this->aliases["echo"] = Array();

                            $this->aliases["echo"][$alias->getName()] = $alias;
                            break;
                        default:
                            $alias = new Object($frame);
                            $this->aliases[$alias->getName()] = $alias;
                            break;
                    }
                }
            } else
                break;
        }
    }

    public function parseConditional($index, $condition)
    {
        $alias = $this->aliases["if"][$index];
        $alias->parseResult($this->source, $condition);
    }

    public function parseIterator($index, $data)
    {
        $alias = $this->aliases["iterator"][$index];
        $alias->parseResult($this->source, $data);
    }

    public function parseEcho($index, $data)
    {
        $alias = $this->aliases["echo"][$index];
        $alias->parseResult($this->source, $data);
    }

    public function parseObject($index, $data)
    {
        $alias = $this->aliases[$index];
        $alias->parseResult($this->source, $data);
    }

    public function show()
    {
        echo $this->source;
    }
}