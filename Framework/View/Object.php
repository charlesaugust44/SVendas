<?php

namespace App\Framework\View;

class Object extends Alias
{
    private
        $method;

    public function __construct($frame)
    {
        //[name].[method]
        parent::__construct($frame);
        $this->parse();
    }

    private function parse()
    {
        $tmp = explode(".", $this->frame);
        $this->name = $tmp[0];
        $this->method = $tmp[1];
    }

    public function parseResult(&$source, $data)
    {
        $source = str_replace("{{" . $this->frame . "}}",$data->{$this->method}(),$source);
    }
}