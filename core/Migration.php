<?php
namespace Mvc\Core;

class Migration {
    protected $migrations = [];
    protected $db;
    public function __construct() {
        $this->db = new Connector;
    }

    public function addMigration($migration) {
        $this->migrations[] = $migration;
    }

    public function migrate() {
        foreach ($this->migrations as $migration) {
            $migration->up($this->db);
        }
    }

    public function rollback() {
        foreach (array_reverse($this->migrations) as $migration) {
            $migration->down($this->db);
        }
    }
}