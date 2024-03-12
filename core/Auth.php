<?php

namespace Mvc\Core;

use Exception;

class Auth {

    protected $guard;
    protected $valid_guards = array("user","admin");
    public function register($data) {
        //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try{
            DB::table("users")->insert($data);
        }catch(\Exception $e){  
           throw $e;
        }
    }

    public function guard($guard)
    {
        if(!in_array($guard,$this->valid_guards)){
            throw new Exception("invalid guards,allow admin|user");
        }
        $this->guard = $guard;
        return $this;
    }

    public function login($login_key, $password) {
        if($this->guard && $this->guard !== "user"){
            $user = DB::table("users")->where("role","!=","user")->where("login_key",$login_key)->first();
        }else{
            $user = DB::table("users")->where("role","user")->where("login_key",$login_key)->first();
        }
        if ($user && password_verify($password, $user->password)) {
            if($this->guard && $this->guard !== "user"){
                $_SESSION['admin']['user'] = $user;
            }else{
                if($user->email_verified_at === null){
                    message(["message" => "Tài khoản chưa được xác thực"]);
                    redirect_back();
                }
                $_SESSION['user'] = $user;
            }
            return true;
        }
        return false;
    }

    public function logout() {
        if($this->guard && $this->guard !== "user"){
            unset($_SESSION['admin']['user']);
        }else{
            unset($_SESSION['user']);
        }   
    }

    public function isAuthenticated() {
        if($this->guard && $this->guard !== "user"){
            return isset($_SESSION['admin']['user']);
        }else{
            return isset($_SESSION['user']);
        }   
    }

    public function getUser() {
        if($this->guard && $this->guard !== "user"){
            return isset($_SESSION['admin']['user']) ? $_SESSION['admin']['user'] : null;
        }else{
            return isset($_SESSION['user']) ? $_SESSION['user'] : null;
        }   
    }

    public function getUserId()
    {
        if($this->guard && $this->guard !== "user"){
            return isset($_SESSION['admin']['user']) ? $_SESSION['admin']['user']->id : null;
        }else{
            return isset($_SESSION['user']) ? $_SESSION['user']->id : null;
        }   
       
    }
}