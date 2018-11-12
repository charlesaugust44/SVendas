<?php

namespace App\Framework\Persistence;

use \PDO;
use \PDOException;

class Database
{
    protected
        $ini,
        $cnx,
        $dsn;

    public function __construct()
    {
        $this->ini = parse_ini_file('Persistence/database_config.ini');
        $this->dsn =
            'mysql:dbname=' . $this->ini['db'] .
            ';host=' . $this->ini['host'] .
            ';port=' . $this->ini['port'];
    }


    protected function connect()
    {
        if (!isset($this->cnx)) {
            try {
                $this->cnx = new PDO($this->dsn, $this->ini['user'], $this->ini['pass']);
                $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $GLOBALS["persistenceConnection"] = $this;
            } catch (PDOException $e) {
                echo 'Connection Failure: ' . $e->getMessage();
            }
        }
    }

    public function disconnect()
    {
        $this->cnx = null;
    }
}