<?php

namespace Mvc\Core;

class QueryBuilder {

    protected $db;

    protected $from;

    protected $fields = array("*");

    protected $limit;

    protected $offset;

    protected $order = array();

    protected $groupBy = null;

    protected $params = array();

    protected $join = array();
 
    protected $where = array();

    protected $where_index = 1;

    protected $distinct;

    protected $call_when = false;

    public $model = null;

    public function __construct() {
        $this->db = new Connector;
    }

    public function from($from)
    {
        if (empty($from))
        {
            throw new \Exception("Invalid argument");
        }
        $this->from = $from;
        return $this;
    }

    public function select(array $fields = ["*"])
    {
        $this->fields = $fields;
        return $this;
    }

    public function join($join, $on1, $operator, $on2)
    {
        if (empty($join) || empty($on1) || empty($operator) || empty($on2))
        {
          throw new \Exception("Invalid argument");
        }
        $this->join[] = "JOIN {$join} ON {$on1} {$operator} {$on2}";
        return $this;
    }

    public function leftJoin($join, $on1, $operator, $on2)
    {
        if (empty($join) || empty($on1) || empty($operator) || empty($on2))
        {
          throw new \Exception("Invalid argument");
        }
        $this->join[] = "LEFT JOIN {$join} ON {$on1} {$operator} {$on2}";
        return $this;
    }

    public function limit($limit)
    {
        if (!is_int($limit) || $limit < 0)
        {
            throw new \Exception("Invalid argument");
        }
        $this->limit = $limit;
        return $this;
    }

    public function offset($offet)
    {
        if (!is_int($offet) || $offet < 0)
        {
            throw new \Exception("Invalid argument");
        }
        $this->offset = $offet;
        return $this;
    }

    public function orderBy($order, $direction = "asc")
    {
        if (empty($order))
        {
         throw new \Exception("Invalid argument");
        }
        $this->order[] = "{$order} $direction";
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function where()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if($number_of_args === 1 && is_callable($args[0])){
            $this->where[] = "(";
            $args[0]($this);
            $this->where[] = ")";
        }else{
            if(func_num_args() < 2){
                throw new \Exception("Too less arguments,expected at least 2");
            }
            if($number_of_args === 2){
                $operator = "=";
                $column = $args[0];
                $value = $args[1];
            }else if($number_of_args === 3){
                $operator = $args[1];
                $column = $args[0];
                $value = $args[2];
            }
            $column_key = str_replace(".","_",$column);
            $this->where[] = "{$column} {$operator} :where_{$column_key}_{$this->where_index}";
            $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
            $this->where_index++;
        }
        return $this;
    }

    public function whereCase(callable $callback)
    {
        $this->where[] = "(CASE";
        $callback($this);
        $this->where[] = "END)";
        return $this;
    }

    public function whereDate()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        $this->where[] = "DATE({$column}) {$operator} :where_{$column_key}_{$this->where_index}";
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        return $this;
    }

    public function whereMonth()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        $this->where[] = "MONTH({$column}) {$operator} :where_{$column_key}_{$this->where_index}";
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        return $this;
    }

    public function whereYear()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        $this->where[] = "YEAR({$column}) {$operator} :where_{$column_key}_{$this->where_index}";
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        return $this;
    }

    public function when()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        if($this->call_when){
            $this->where[] = "{$column} {$operator} :where_{$column_key}_{$this->where_index}";
        }else{
            $this->where[] = "WHEN {$column} {$operator} :where_{$column_key}_{$this->where_index}";
        }
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        $this->call_when = true;
        return $this;
    }

    public function whenNull($column)
    {
        if(empty($column)){
            throw new \Exception("expected one argument");
        }
        if($this->call_when){
            $this->where[] = "{$column} IS NULL";
        }else{
            $this->where[] = "WHEN {$column} IS NULL";
        }
        $this->call_when = true;
        return $this;
    }

    public function whenNotNull($column)
    {
        if(empty($column)){
            throw new \Exception("expected one argument");
        }
        if($this->call_when){
            $this->where[] = "{$column} IS NOT NULL";
        }else{
            $this->where[] = "WHEN {$column} IS NOT NULL";
        }
        $this->call_when = true;
        return $this;
    }

    public function whenElse()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        $this->where[] = "ELSE {$column} {$operator} :where_{$column_key}_{$this->where_index}";
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        $this->call_when = false;
    }

    public function then()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if(func_num_args() < 2){
            throw new \Exception("Too less arguments,expected at least 2");
        }
        if($number_of_args === 2){
            $operator = "=";
            $column = $args[0];
            $value = $args[1];
        }else if($number_of_args === 3){
            $operator = $args[1];
            $column = $args[0];
            $value = $args[2];
        }
        $column_key = str_replace(".","_",$column);
        $this->where[] = "THEN {$column} {$operator} :where_{$column_key}_{$this->where_index}";
        $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
        $this->where_index++;
        $this->call_when = false;
    }
    

    public function orWhere()
    {
        $args = func_get_args();
        $number_of_args = func_num_args();
        if($number_of_args === 1 && is_callable($args[0])){
            $this->where[] = "OR (";
            $args[0]($this);
            $this->where[] = ")";
        }else{
            if(func_num_args() < 2){
                throw new \Exception("Too less arguments,expected at least 2");
            }
            $args = func_get_args();
            $number_of_args = func_num_args();
            if($number_of_args === 2){
                $operator = "=";
                $column = $args[0];
                $value = $args[1];
            }else if($number_of_args === 3){
                $operator = $args[1];
                $column = $args[0];
                $value = $args[2];
            }
            $column_key = str_replace(".","_",$column);
            $this->where[] = "OR {$column} {$operator} :where_{$column_key}_{$this->where_index}";
            $this->params[":where_{$column_key}_{$this->where_index}"] = $value;
            $this->where_index++;
        }
        return $this;
    }

    public function whereNull($column)
    {
        if(empty($column)){
            throw new \Exception("expected one argument");
        }
        $this->where[] = "{$column} IS NULL";
        return $this;
    }

    public function whereNotNull($column)
    {
        if(empty($column)){
            throw new \Exception("expected one argument");
        }
        $this->where[] = "{$column} IS NOT NULL";
        return $this;
    }

    protected function buildSelect()
    {
        $fields = array();
        $where= $order = $limit = $join ="";
        $template = "SELECT %s FROM %s %s %s %s %s %s";
        $fields = join(", ", $this->fields);
        if($this->distinct){
            $fields = "DISTINCT {$fields}"; 
        }
        $join = $this->join;
        if (!empty($join))
        {
            $join = join(" ", $join);
        }else{
            $join = "";
        }
        $where = $this->where;
        if (!empty($where))
        {
            $whereJoined = "";
            foreach($where as $index => $item){
                if($index === 0){
                    $whereJoined .= "{$item}";
                }else{
                    $whereJoined .= " AND {$item}";
                }
            }
            $where = "WHERE {$whereJoined}";
            $where = str_replace(["( AND "," AND )","AND OR","WHERE OR","(OR ","CASE AND WHEN","CASE AND","AND THEN","AND WHEN","AND END","AND ELSE"],["(",")","OR","WHERE","(","CASE WHEN","CASE","THEN","WHEN","END","ELSE"],$where);
        }else{
            $where = "";
        }
        if (!empty($this->order))
        {
            $orders = join(", ",$this->order);
            $order = "ORDER BY {$orders}";
        }
        $limit = $this->limit;
        if (!empty($limit))
        {
            $offset=$this->offset;
            if ($offset)
            {
                $limit = "LIMIT {$offset}, {$limit}";
            }
            else
            {
                $limit = "LIMIT {$limit}";
            }
        }
        $groupBy = $this->groupBy;
        if($groupBy)
        {
            if(is_array($groupBy)){
                $groupByColumns = join(", ",$groupBy);
                $groupBy = "GROUP BY {$groupByColumns}";
            }else{
                $groupBy = "GROUP BY {$groupBy}";
            }
        }
        $sql = sprintf($template, $fields, $this->from, $join, $where, $groupBy , $order, $limit);
        return $sql;
    }

    public function build()
    {
        var_dump($this->where);
        return $this->buildSelect();
    }

    protected function buildInsert($data)
    {
        $this->params = array();
        $fields=array();
        $values =array();
        $template ="INSERT INTO %s (%s) VALUES (%s)";
        foreach ($data as $field => $value)
        {
            $fields[] = $field;
            $values[] = ":{$field}";
            $this->params[":{$field}"] = $value;
        }
        $fields = join(", ", $fields);
        $values =join(", ", $values);
        return sprintf($template, $this->from, $fields, $values);
    }

    protected function buildUpdate($data)
    {
        $parts =array();
        $where =$limit="";
        $template ="UPDATE %s SET %s %s %s";
        foreach ($data as $field => $value)
        {
            $parts[] = "{$field} = :{$field}";
            $this->params[":{$field}"] = $value;
        }
        $parts =join(", ", $parts);
        $where =$this->where;
        if (!empty($where))
        {
            $joined=join(" AND ", $where);
            $where ="WHERE {$joined}";
        }
        $limit =$this->limit;
        if (!empty($limit))
        {
            $offset =$this->offset;
            $limit="LIMIT {$limit} {$offset}";
        }
        return sprintf($template, $this->from, $parts, $where, $limit);
    }

    protected function buildDelete()
    {
        $where = $limit= "";
        $template = "DELETE FROM %s %s %s";
        $where = $this->where;
        if (!empty($where))
        {
            $joined = join(" AND ", $where);
            $where = "WHERE {$joined}";
        }
        $limit = $this->limit;
        if (!empty($limit))
        {
            $limit = "LIMIT {$limit}";
        }
        return sprintf($template, $this->from, $where, $limit);
    }

    public function get($reset = true)
    {
        $sql = $this->buildSelect();
        $records = $this->db->selectAll($sql,$this->params);
        if(!empty($records)){
            if($this->model){
                $models = [];
                $class = $this->model;
                foreach($records as $item){
                    $properties = get_object_vars($item);
                    $model = new $class();
                    foreach($properties as $key => $value){
                        $model->$key = $value;
                    }
                    $models[] = $model;
                }
                if($reset){
                    $this->resetQuery();
                }
                return $models;
            }else{
                if($reset){
                    $this->resetQuery();
                }
                return $records;
            }
        }
        if($reset){
            $this->resetQuery();
        }
        return [];
    }

    public function insert($data)
    {
        $sql = $this->buildInsert($data);
        $records = $this->db->insert($sql,$data);
        $this->resetQuery();
        if($records){
            return $this->db->getLastInsertId();
        }
        return 0;
    }

    public function delete()
    {
        $sql = $this->buildDelete();
        $result = $this->db->execute($sql,$this->params);
        $this->resetQuery();
        return $result;
    }

    public function update($data)
    {
        $sql = $this->buildUpdate($data);
        $result = $this->db->execute($sql,$this->params);
        $this->resetQuery();
        return $result;
    }

    public function increase($column,$value)
    {
        $value = intval($value);
        $where = $limit="";
        $template ="UPDATE %s SET $column = $column + $value %s %s";
        $where =$this->where;
        if (!empty($where))
        {
            $joined=join(" AND ", $where);
            $where ="WHERE {$joined}";
        }
        $limit =$this->limit;
        if (!empty($limit))
        {
            $offset =$this->offset;
            $limit="LIMIT {$limit} {$offset}";
        }
        $sql = sprintf($template, $this->from, $where, $limit);
        $result = $this->db->execute($sql,$this->params);
        $this->resetQuery();
        return $result;
    }

    public function decrease($column,$value)
    {
        $value = intval($value);
        $where =$limit="";
        $template ="UPDATE %s SET $column = $column - $value %s %s";
        $where =$this->where;
        if (!empty($where))
        {
            $joined=join(" AND ", $where);
            $where ="WHERE {$joined}";
        }
        $limit =$this->limit;
        if (!empty($limit))
        {
            $offset =$this->offset;
            $limit="LIMIT {$limit} {$offset}";
        }
        $sql = sprintf($template, $this->from, $where, $limit);
        $result = $this->db->execute($sql,$this->params);
        $this->resetQuery();
        return $result;
    }

    public function first()
    {
        $sql = $this->buildSelect();
        $result = $this->db->selectOne($sql,$this->params);
        if($this->model && $result){
                $model = null;
                $class = $this->model;
                $properties = get_object_vars($result);
                $model = new $class();
                foreach($properties as $key => $value){
                    $model->$key = $value;
                }
                $this->resetQuery();
                return $model;
        }else{
            $this->resetQuery();
            return $result;
        }
    }

    public function count($reset = true)
    {
        $temp_fields = $this->fields;
        $this->fields = array("COUNT(*) AS records");
        $sql = $this->buildSelect();
        $result = $this->db->selectOne($sql,$this->params);
        $this->fields = $temp_fields;
        if($reset){
            $this->resetQuery();
        }
        return $result->records ?? 0;
    }

    public function groupBy($columns)
    {
        $this->groupBy = $columns;
        return $this;
    }

    public function getValue($column)
    {
       return $this->first()?->$column ?? null;
    }

    protected function resetQuery()
    {
        $this->order = array();

        $this->params = array();

        $this->join = array();
 
        $this->where = array();

        $this->from = "";

        $this->fields = array("*");

        $this->limit = null;

        $this->offset = null;

        $this->groupBy = null;

        $this->call_when = false;

        $this->where_index = 1;

        $this->model = null;
    }

    public function transaction(callable $callback)
    {
        $this->db->transaction($callback);
    }
}