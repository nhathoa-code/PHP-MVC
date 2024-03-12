<?php

namespace Mvc\Core;

class Response {
    
    public function send($content, $statusCode = 200 ) 
    {
        header("Content-Type: text/html");
        http_response_code($statusCode);
        return $content;
    }

    public function json($content,$statusCode)
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($content);
    }
}