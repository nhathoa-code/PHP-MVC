<?php

namespace Mvc\Core;

class Request {

    public function method() 
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri() 
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function input($name)
    {
        if(isset($_REQUEST[$name])){
            return $_REQUEST[$name];
        }
        return null;
    }

    public function has($name)
    {
        return isset($_REQUEST[$name]);
    }

    public function file($name)
    {
        if(isset($_FILES[$name])){
            if(is_array($_FILES[$name]['name'])){
                $files = array();
                $fileCount = count($_FILES[$name]['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    $file_name = $_FILES[$name]['name'][$i];
                    $tmp_name = $_FILES[$name]['tmp_name'][$i];
                    $size = $_FILES[$name]['size'][$i];
                    $error = $_FILES[$name]['error'][$i];
                    $type = $_FILES[$name]['type'][$i];
                    $files[] = new File($file_name,$tmp_name,$size,$error,$type);
                }
                return $files;
            }else{
                return new File($_FILES[$name]['name'],$_FILES[$name]['tmp_name'],$_FILES[$name]['size'],$_FILES[$name]['error'],$_FILES[$name]['type']);
            }
        }
        return null;
    }

    public function hasFile($name)
    {
        return isset($_FILES[$name]) && !empty($_FILES[$name]['name'][0]);
    }

    public function all()
    {
        return [...$_REQUEST,...$_FILES];
    }

    public function query($name)
    {
        return $_GET[$name];
    }

    public function hasQuery($name)
    {
        return isset($_GET[$name]);
    }

    public function isAjax()
    {
       return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

}