<?php

namespace App\Framework\View;

class Alias
{
    protected
        $name,
        $frame;

    public function __construct($frame)
    {
        $this->frame = $frame;
    }

    protected function search(&$source, $signature)
    {
        $list = Array();

        $start = -2;

        while (true) {
            $start = strpos($source, $signature, $start + 2);

            if ($start !== false) {
                $end = strpos($source, "}}", $start);

                array_push($list, Array("start" => $start, "end" => $end));
                $start = $end;
            } else
                break;
        }

        return $list;
    }

    protected function replace(&$source, $start, $end, $data)
    {
        $fp = substr($source, 0, $start);
        $lp = substr($source, $end, strlen($source) - $end);

        $source = $fp . $data . $lp;


    }

    /**
     * @return mixed
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * @param mixed $frame
     */
    public function setFrame($frame)
    {
        $this->frame = $frame;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}