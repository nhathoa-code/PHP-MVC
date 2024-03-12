<?php 

namespace Mvc\Core;
use App\Middlewares\CSRFMiddleware;
use Exception;

class Router {
    
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    private $prefixes = [];
    private $groupMiddlewares = [];

    public function get($route,$action,$middleware = null)
    {
        $route = preg_replace('/\/\{([^\/]+)\}/', '/(?<$1>[0-9a-zA-Z-_/]+)', $route);
        if(count($this->prefixes) > 0){
            $route = implode('', $this->prefixes) . ($route === "/" ? "" : $route);
        }
        $actions["action"] = $action;
        $middlewares = array();
        if(!empty($this->groupMiddlewares)){
            foreach($this->groupMiddlewares as $item){
                array_push($middlewares,$item);
            }
        }
        if($middleware){
            if(is_array($middleware)){
               if(array_key_exists("excludes",$middleware)){
                    $excludes = $middleware['excludes'];
                    unset($middleware["excludes"]);
               } 
               foreach($middleware as $item){
                    array_push($middlewares,$item);
               } 
            }else{
                array_push($middlewares,$middleware);
            }
            if(isset($excludes)){
                if(is_array($excludes)){
                    $middlewares = array_diff($middlewares,$excludes);
                }
            }
        }
        $this->routes['GET'][$route] = [
            'action' => $action,
            'middlewares' => $middlewares,
        ];
        return $this;
    }

    public function post($route,$action,$middleware = null)
    {
        $route = preg_replace('/\/\{([^\/]+)\}/', '/(?<$1>[^\/]+)', $route);
        if(count($this->prefixes) > 0){
            $route = implode('', $this->prefixes) . $route;
        }
        $actions["action"] = $action;
        $middlewares = array();
        if(!empty($this->groupMiddlewares)){
            foreach($this->groupMiddlewares as $item){
                array_push($middlewares,$item);
            }
        }
        if($middleware){
            if(is_array($middleware)){
                if(array_key_exists("excludes",$middleware)){
                    $excludes = $middleware['excludes'];
                    unset($middleware["excludes"]);
                } 
                foreach($middleware as $item){
                    array_push($middlewares,$item);
                } 
            }else{
                array_push($middlewares,$middleware);
            }
            if(isset($excludes)){
                if(is_array($excludes)){
                    $middlewares = array_diff($middlewares,$excludes);
                }
            }
        }
        $this->routes['POST'][$route] = [
            'action' => $action,
            'middlewares' => $middlewares,
        ];
        return $this;
    }

    public function group($prefix,$callback,$middleware = null)
    {
        $this->prefixes[] = "/{$prefix}";
        if($middleware){
            if(is_array($middleware)){
                foreach($middleware as $item){
                   array_push($this->groupMiddlewares,$item);
                }
            }else{
                array_push($this->groupMiddlewares,$middleware);
            }
        }
        $callback();
        array_pop($this->prefixes);
        if($middleware){
            if(is_array($middleware)){
                array_splice($this->groupMiddlewares, -count($middleware));
            }else{
                array_splice($this->groupMiddlewares,-1);
            }
        }
    }

    public function route()
    {
        $is_route_found = false;
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $base_path = explode("?",$_SERVER['REQUEST_URI'])[0];
        $requestedRoute = substr($base_path, -1) === "/" ?  substr($base_path,0,-1) : $base_path;
        $requestedRoute = str_replace(ROOT_DIR,"",$requestedRoute);
        foreach($this->routes[$requestMethod] as $route => $actions)
        {
            $action = $actions['action'];
            $middlewares = $actions['middlewares'];
            $pattern = "@^" .  ($route === "/" ? "" : "$route") . "$@";
            preg_match($pattern,$requestedRoute,$matches);
            if($matches)
            {
                $is_route_found = true;
                $csrfInstance = new CSRFMiddleware;
                $request = new Request;
                $request = $csrfInstance->handle($request);
                $params = array_filter($matches,"is_string", ARRAY_FILTER_USE_KEY);
                foreach($middlewares as $item){
                    $middlewareInstance = new $item();
                    $request = $middlewareInstance->handle($request);
                }
                if(is_callable($action))
                {
                    $return = call_user_func_array($action,$params);
                }
                elseif(is_array($action))
                {
                    if(count($action) < 2){
                        throw new Exception("Invalid class'method");
                    }
                    list($controllerClass,$method) = $action;
                    if (method_exists($controllerClass, $method)) {
                        $controllerReflection = new \ReflectionClass($controllerClass);
                        $methodReflection = $controllerReflection->getMethod($method);
                        $requestParameter = false;
                        $parameters = $methodReflection->getParameters();
                        if(count($parameters) > 0){
                            if($parameters[0]->hasType()){
                                if($parameters[0]->getType()->getName() === "Mvc\Core\Request"){
                                    $requestParameter = true;
                                }
                            }
                        }
                        $controllerInstance = new $controllerClass();
                        if($requestParameter){
                            $return = call_user_func_array([$controllerInstance, $method],[$request,...$params]);
                        }else{
                            $return = call_user_func_array([$controllerInstance, $method],$params);
                        }
                        if($return){
                            if(is_array($return) || is_object($return)){
                                var_dump($return);
                            }else{
                                echo $return;
                            }
                        }
                    }
                }
                break;
            }
        }
        if(!$is_route_found){
            echo "route not found";
        }
    }
}