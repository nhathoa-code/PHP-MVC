<?php

namespace Mvc\Core;

class DB {

    protected static $instance;
    protected $queryBuilder;

    private function __construct() {
        $this->queryBuilder = new QueryBuilder();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function table($tableName) {
        return self::getInstance()->queryBuilder->from($tableName);
    }

    public static function transaction(callable $callback)
    {
        self::getInstance()->queryBuilder->transaction($callback);
    }
}