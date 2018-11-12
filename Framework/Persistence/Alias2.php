<?php

namespace App\Framework\View;

class Alias
{
    private
        $fullName,
        $frame;

    public function __construct($fullName, $frame = null)
    {
        $this->fullName = $fullName;
        $this->frame = $frame;
    }

    public function replace(&$source, $value)
    {
        $source = str_replace("{{" . $this->fullName . (($this->frame == null) ? "" : "@@" . $this->frame) . "}}", $value, $source);
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function getName()
    {
        return explode(".", $this->getFullName())[1];
    }

    public function getFrame()
    {
        return $this->frame;
    }
}