<?php

namespace App\Framework\View;

class Iterator extends Alias
{
    private
        $displayable;

    public function __construct($frame)
    {
        //iterator.[name]@@[display]
        parent::__construct($frame);
        $this->parse();
    }

    private function parse()
    {
        $tmp = explode(".", $this->frame);
        $tmp = explode("@@", $tmp[1]);

        $this->displayable = $tmp[1];
        $this->name = $tmp[0];
    }

    public function parseResult(&$source, $data)
    {
        if (count($data) > 0) {
            $frame = explode("@@", $this->frame)[1];

            $start = -2;
            $end = null;
            $iterated = "";
            $internalAlias = Array();

            while (true) {
                $start = strpos($frame, "[[", $start + 2);
                if ($start !== false) {
                    $end = strpos($frame, "]]", $start);

                    $alias = substr($frame, $start + 2, $end - $start - 2);

                    if (array_search($alias, $internalAlias) === false) {
                        array_push($internalAlias, $alias);
                    }
                } else
                    break;
            }

            $isArray = is_array($data[0]);

            $objResult = function ($d, $i) use ($isArray) {
                if ($isArray)
                    return $d[$i];
                return $d->{$i}();
            };

            foreach ($data as $d) {
                $r = $this->displayable;
                foreach ($internalAlias as $i) {
                    $getResult = $objResult($d, $i);
                    $r = str_replace("[[" . $i . "]]", $getResult, $r);
                }
                $iterated .= $r;
            }

            $source = str_replace("{{" . $this->frame . "}}", $iterated, $source);
        }
    }
}