<?php

namespace Mvc\Core;

use PDOException;

class Connector {

    protected $pdo;

    protected $host = DB_HOST;

    protected $username = DB_USER;

    protected $password = DB_PASS;

    protected $dbname = DB_NAME;

    protected $lastStatement;

    protected $schema;

    protected $port="3306";

    protected $charset = "utf8";
 
    protected $engine ="InnoDB";
 
    protected $isConnected =false;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $username = $this->username;
        $password = $this->password;
        try {
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function migrate($sql)
    {
        $this->pdo->exec($sql);
    }

    public function execute($sql,$params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            return $statement->rowCount();
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function selectOne($sql,$params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            return $statement->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function selectAll($sql,$params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            $this->lastStatement = $statement;
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function insert($sql,$data)
    {
        $this->pdo->prepare($sql)->execute($data);
        return $this->getLastInsertId();
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function getAffectedRows()
    {
        return $this->lastStatement->rowCount();
    }

    public function transaction(callable $callback)
    {
        try{
            $this->pdo->beginTransaction();
            $callback();
            $this->pdo->commit();
        }catch(PDOException $e)
        {
            $this->pdo->rollBack();
            echo "Transaction failed: " . $e->getMessage();
        }
    }

}