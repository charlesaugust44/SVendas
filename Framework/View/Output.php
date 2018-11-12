<?php

namespace App\Framework\View;

class Output extends Alias
{
    private
        $data;

    public function __construct($frame)
    {
        //echo.[name]
        parent::__construct($frame);
        $this->parse();
    }

    private function parse()
    {
        $tmp = explode(".", $this->frame);
        $this->name = $tmp[1];
    }

    public function parseResult(&$source, $data)
    {
        $source = str_replace("{{" . $this->frame . "}}",$data,$source);
    }
}