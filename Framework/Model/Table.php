<?php

namespace App\Framework\Model;

class Table
{
    private
        $types,
        $columns;

    protected static
        $OBJECT = "object",
        $DATA = "data";

    public function __construct()
    {
        $this->columns = Array();
        $this->types = Array();
        $this->loadColumns();

        foreach ($this->columns as $c)
            array_push($this->types, self::$DATA);
    }

    protected function columnType($column, $type)
    {
        foreach ($this->columns as $key => $c) {
            if ($c == $column) {
                $this->types[$key] = $type;
                break;
            }
        }

        print_r($this->types);
        echo "<br>";
    }

    private function loadColumns()
    {
        $methods = get_class_methods($this);

        foreach ($methods as $m) {
            if (!(strpos($m, "get", 0) === false)) {
                array_push($this->columns, strtolower(substr($m, 3)));
            }
        }
    }

    public function toArray()
    {
        $data = Array();

        foreach ($this->columns as $c) {
            $c[0] = strtoupper($c[0]);
            array_push($data, addslashes(htmlspecialchars($this->{"get" . $c}())));
        }

        return Array(
            "data" => $data,
            "columns" => $this->columns,
            "types" => $this->types
        );
    }

    public function load($dataArray)
    {
        foreach ($dataArray as $key => $data) {
            $key[0] = strtoupper($key[0]);
            $this->{"set" . $key}($data);
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