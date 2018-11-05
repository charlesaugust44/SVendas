<?php

namespace App\Model;

class Table
{

    public function __construct()
    {
    }

    public function toArray()
    {
        $methods = get_class_methods($this);

        $data = Array();
        $columns = Array();

        foreach ($methods as $m) {
            if (!(strpos($m, "get", 0) === false)) {
                array_push($data, addslashes(htmlspecialchars($this->{$m}())));
                array_push($columns, strtolower(substr($m, 3)));
            }
        }

        return Array(
            "data" => $data,
            "columns" => $columns
        );
    }

    public function load($dataArray)
    {
        foreach ($dataArray as $key => $data) {
            $key[0] = strtoupper($key[0]);
            $this->{"set".$key}($data);
        }
    }

    public function className()
    {
        $classPath = str_split(get_class($this));

        $className = "";

        for ($i = count($classPath) - 1; $i >= 0; $i--) {
            if ($classPath[$i] == "\\")
                break;
            else
                $className = $classPath[$i] . $className;
        }

        return $className;
    }

    public function classPath()
    {
        return get_class($this);
    }
}