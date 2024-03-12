<?php

namespace Mvc\Core;

class File {

    protected $name;
    protected $tmp_name;
    protected $size;
    protected $error;
    protected $type;

    protected $dir;

    public function __construct($name = null,$tmp_name = null,$size = null,$error = null,$type = null) 
    {
        $this->name = $name;
        $this->tmp_name = $tmp_name;
        $this->size = $size;
        $this->error = $error;
        $this->type = $type;
    }

    public function delete($file_path) 
    {
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function originalName()
    {
        return pathinfo($this->name, PATHINFO_FILENAME);
    }

    public function Extension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function save($path = null,$name = null)
    {
        $fileName = $this->name; 
        $fileTempName = $this->tmp_name; 
        $uploadDirectory = ROOT_PATH . "/public" . ($path === null ? "" : "/{$path}"); 
        if (!file_exists($uploadDirectory) && !is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true); 
        }
        $fileDestination = $uploadDirectory . ($name === null ? "/{$fileName}" : "/{$name}");
        if(move_uploaded_file($fileTempName, $fileDestination)) {
            return str_replace(ROOT_PATH . "/public/","",$fileDestination);
        } else {
            return null;
        }
    }

    public function dir($path)
    {   
        $this->dir = $path;
        return $this;
    }

    public function files()
    {   
        $files = scandir(ROOT_PATH . "/public/" . $this->dir);
        natsort($files);
        return array_map(function($item){
            return public_url($this->dir . "/" . $item);
        },array_values(array_filter($files,function($item){
            return !in_array($item, array('.', '..'));
        })));
    }

}