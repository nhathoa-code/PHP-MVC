<?php

namespace App\Middlewares;
use Mvc\Core\Middleware;
use Mvc\Core\Auth;
use Mvc\Core\Request;

class AdminMiddleware implements Middleware{
    public function handle(Request $request) {
        $auth = new Auth();
        if(!$auth->guard("admin")->isAuthenticated()){
            if($request->isAjax()){
                return response()->json(["unauthorize"],403);
                exit;
            }
            $redirect_to = current_url();
            redirect("admin/login?redirect_to={$redirect_to}");
        }
        return $request;
    }
}