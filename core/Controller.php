<?php

class Controller 
{
    protected function view($name, $data = []) 
    {
        extract($data);
        
        return require "app/views/{$name}.php";
    }
    
    protected function redirect($path) 
    {
        header("Location: {$path}");
        exit;
    }
    
    protected function json($data, $statusCode = 200) 
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
} 