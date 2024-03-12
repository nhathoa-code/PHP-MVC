<?php
namespace Mvc\Core;

class Model {
    protected $queryBuilder;
    protected $table = null;
    protected $properties = [];

    public function __construct() {
        $this->queryBuilder = new QueryBuilder();
    }

    public function __call($method, $args) {
        if (method_exists($this->queryBuilder, $method)) {
            $this->queryBuilder->model = get_class($this);
            if($this->table){
                $tableName = $this->table;
            }else{
                $tableName = strtolower(get_called_class());
            }
            $this->queryBuilder->from($tableName);
            call_user_func_array([$this->queryBuilder, $method], $args);
        } else {
            throw new \BadMethodCallException("Method $method does not exist");
        }
        return $this->queryBuilder;
    } 

    public static function __callStatic($name, $arguments)
    {
        try{
            $query_instance = new QueryBuilder();
            $instance = new static();
            $query_instance->model = get_class($instance);
            if($instance->table){
                $tableName = $instance->table;
            }else{
                $fullClassName = explode("\\",static::class);
                $className = strtolower(end($fullClassName));
                $tableName = $className;
            }
            $query_instance->from($tableName);
            switch($name){
                case "where":
                    if(count($arguments) === 2){
                        $query_instance->where($arguments[0],$arguments[1]);
                    }elseif(count($arguments) === 3){
                        $query_instance->where($arguments[0],$arguments[1],$arguments[2]);
                    }
                    break;
                case "whereNull":
                    $query_instance->whereNull($arguments[0]);
                    break;  
                case "select":
                    $query_instance->from($tableName);
                    return $query_instance->select($arguments[0]);
                    break;
                case "insert":
                    $query_instance->from($tableName);
                    return $query_instance->insert($arguments[0]);
                    break;
                case "all":
                    $query_instance->from($tableName);
                    return $query_instance->get();
                    break;
                case "orderBy":
                    if(!isset($arguments[1])){
                        $sort = "asc";
                    }else{
                        $sort = $arguments[1];
                    }
                    $query_instance->orderBy($arguments[0],$sort);
                    return $query_instance;
                    break;
                case "count":
                    return $query_instance->count();
                    break;
                default:
                    throw new \Exception("method not found");
            }
            return $query_instance;
        }catch(\Exception $e)
        {
            if(defined("ENVIRONMENT") && ENVIRONMENT === "dev"){
                echo 'Caught exception: ' . $e->getMessage();
            }else{
                error_log('Caught exception: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
                echo 'An error occurred. Please try again later.';
            }  
        } 
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->properties[$name])){
            return $this->properties[$name];
        }else{
            return null;
        }
    }

    public function __isset($name)
    {
        if(isset($this->$name)){
            return true;
        }else{
            if(isset($this->properties[$name])){
                return true;
            }
        }
        return false;
    }

    public function update()
    {
        $table = "";
        $primary_key = isset($this->primary_key) ? $this->$this->primary_key : "id";
        if(isset($this->table)){
            $table = $this->table;
        }else{
            $fullClassName = explode('\\', get_class($this));
            $table = strtolower(end($fullClassName)) . "s";
        }
        $this->queryBuilder->from($table)->where($primary_key, $this->$primary_key)->limit(1)->update($this->properties);
    }

    public function delete()
    {
        $table = "";
        $primary_key = isset($this->primary_key) ? $this->$this->primary_key : "id";
        if(isset($this->table)){
            $table = $this->table;
        }else{
            $fullClassName = explode('\\', get_class($this));
            $table = strtolower(end($fullClassName)) . "s";
        }
        $this->queryBuilder->from($table)->where($primary_key, $this->$primary_key)->limit(1)->delete();
    }
}