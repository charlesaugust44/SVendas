<?php

namespace App\Persistence;

use \PDO;

class Manager extends Database
{

    public function __construct()
    {
        parent::__construct();
        $this->connect();
    }

    public function insert($object)
    {
        $dataArray = $object->toArray();
        $tableName = $object->className();

        $data = $dataArray["data"];
        $columns = $dataArray["columns"];

        $reduceVal = function ($carry, $current) {
            return $carry . ", " . (($current == "") ? "null" : $current);
        };

        $reduceCol = function ($carry, $current) {
            return $carry . ", " . $current;
        };

        $mapAlias = function ($v) {
            return ":" . $v;
        };

        $aliasArray = array_map($mapAlias, $columns);
        $aliases = trim(array_reduce($aliasArray, $reduceVal, ""), ",");
        $columns = trim(array_reduce($columns, $reduceCol, ""), ",");

        $sql = "INSERT INTO $tableName ($columns) VALUES ($aliases)";

        $statement = $this->cnx->prepare($sql);

        foreach ($data as $i => $d)
            $statement->bindValue($aliasArray[$i], $d);

        $statement->execute();
    }

    public function select($object, $whereValue = null, $field = "id")
    {
        $dataArray = $object->toArray();
        $tableName = $object->className();
        $classPath = $object->classPath();

        $columns = $dataArray["columns"];

        $reduceCol = function ($carry, $current) {
            return $carry . ", " . $current;
        };

        $columns = trim(array_reduce($columns, $reduceCol, ""), ",");

        if ($whereValue == null) {
            $sql = "SELECT $columns FROM $tableName";

            $statement = $this->cnx->prepare($sql);
            $statement->execute();
        } else {
            $sql = "SELECT $columns FROM $tableName WHERE $field = :whereValue";

            $statement = $this->cnx->prepare($sql);
            $statement->bindValue(":whereValue", $whereValue);
            $statement->execute();
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $return = Array();

        if ($results != false && !empty($results)) {
            foreach ($results as $r) {
                $obj = new $classPath;
                $obj->load($r);
                array_push($return, $obj);
            }
        }

        return $return;
    }

    public function update($object, $whereValue, $field = "id")
    {
        $dataArray = $object->toArray();
        $tableName = $object->className();

        $data = $dataArray["data"];
        $columns = $dataArray["columns"];

        $aliasArray = Array();
        $newData = array();
        $dataSet = "";

        foreach ($columns as $key => $c) {
            if ($data[$key] != null) {
                array_push($newData, $data[$key]);
                array_push($aliasArray, ":" . $c);
                $dataSet .= ", " . $c . " = :" . $c;
            }
        }

        $dataSet = trim($dataSet, ",");

        $sql = "UPDATE $tableName SET $dataSet WHERE $field = :whereValue";

        $statement = $this->cnx->prepare($sql);
        $statement->bindValue(":whereValue", $whereValue);

        foreach ($newData as $i => $d)
            $statement->bindValue($aliasArray[$i], $d);

        $statement->execute();
    }

    public function delete($object, $whereValue, $field = "id")
    {
        $tableName = $object->className();

        $deleted = $this->select($object, $whereValue, $field);

        if (count($deleted) > 0) {
            $sql = "DELETE FROM $tableName WHERE $field = :whereValue";

            $statement = $this->cnx->prepare($sql);
            $statement->bindValue(":whereValue", $whereValue);
            $statement->execute();

            return $deleted;
        }

        return null;
    }
}