<?php

namespace App\Middlewares;
use Mvc\Core\Middleware;
use Mvc\Core\Auth;
use Mvc\Core\Request;

class LoggedInMiddleware implements Middleware{
    public function handle(Request $request) {
        $auth = new Auth();
        if(!$auth->isAuthenticated()){
           redirect("auth/login"); 
        }
        return $request;
    }
}