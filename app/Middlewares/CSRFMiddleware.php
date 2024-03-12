<?php

namespace App\Middlewares;

use Exception;
use Mvc\Core\Middleware;
use Mvc\Core\Request;

class CSRFMiddleware implements Middleware{
    public function handle(Request $request) {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $submittedToken = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
                if($request->isAjax()){
                    echo response()->json(["message"=>"CSRF token mismatch"],403);
                    exit;
                }
                throw new Exception("CSRF token mismatch",403);
                exit;
            }
        }
        return $request;
    }
}