<?php

namespace App\Framework\View;

class Conditional extends Alias
{
    private
        $displayable;

    public function __construct($frame)
    {
        //if.[name]@@[displayable]
        parent::__construct($frame);
        $this->parse();
    }

    private function parse()
    {
        $tmp = explode("@@", $this->frame);

        $this->displayable = $tmp[1];

        $tmp = explode(".", $tmp[0]);

        $this->name = $tmp[1];
    }

    public function parseResult(&$source, $data)
    {
        $signature = "if." . $this->name;
        $positions = $this->search($source, $signature);

        foreach ($positions as $p)
            $this->replace($source, $p["start"] - 2, $p["end"] + 2, ($data) ? $this->displayable : "");
    }
}