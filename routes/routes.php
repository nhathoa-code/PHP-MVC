<?php

use Mvc\Core\Router;
use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Controllers\ClientController;
use App\Controllers\CartController;
use App\Controllers\CouponController;
use App\Controllers\OrderController;
use App\Controllers\UserController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\LoggedInMiddleware;
use App\Middlewares\NotLoggedInMiddleware;

$router = new Router();

$router->get("/test",function(){


}); 

$router->get("/",[ClientController::class,"index"]);

$router->get("/collection/{categories}",[ClientController::class,"collection"]);

$router->get("/product/detail/{id}",[ProductController::class,"detail"]);

$router->post("/product/add/wl",[ProductController::class,"addWl"]);

$router->post("/product/remove/wl",[ProductController::class,"removeWl"]);

$router->get("/filter",[ClientController::class,"filter"]);

$router->get("/search",[ClientController::class,"search"]);

$router->get("/cart",[ClientController::class,"cart"]);

$router->get("/checkout",[ClientController::class,"checkout"]);

$router->post("/apply/coupon",[ClientController::class,"applyCoupon"]);

$router->post("/apply/point",[ClientController::class,"applyPoint"]);

$router->get("/success",[ClientController::class,"success"]);

$router->get("/order/track",[ClientController::class,"orderTrack"]);

$router->get("/vnpay/confirm",[ClientController::class,"vnpayConfirm"]);

$router->get("/reset_success",[ClientController::class,"resetSuccess"]);

$router->get("/user/{part}",[ClientController::class,"user"],[LoggedInMiddleware::class]);

$router->group("auth",function() use($router){
    $router->get("/login",[ClientController::class,"authLoginView"],[NotLoggedInMiddleware::class]);
    $router->get("/logout",[ClientController::class,"authLogout"],[LoggedInMiddleware::class]);
    $router->get("/registry",[ClientController::class,"authRegistryView"],[NotLoggedInMiddleware::class]);
    $router->post("/registry",[ClientController::class,"authRegistry"]);
    $router->post("/login",[ClientController::class,"authLogin"]);
    $router->post("/retrievepassword",[ClientController::class,"retrievePassword"]);
    $router->get("/email/verify",[ClientController::class,"authVerify"]);
    $router->get("/forgotpassword",[ClientController::class,"forgotPassword"],[NotLoggedInMiddleware::class]);
    $router->get("/resetpassword",[ClientController::class,"resetPasswordView"]);
    $router->post("/resetpassword",[ClientController::class,"resetPassword"]);
});

$router->group("user",function() use($router){
    $router->post("/address/add",[UserController::class,"addAddress"]);
    $router->get("/user/address/edit/{id}",[UserController::class,"editAddress"]);
    $router->post("/address/delete",[UserController::class,"deleteAddress"]);
    $router->post("/address/update",[UserController::class,"updateAddress"]);
    $router->post("/profile/update",[UserController::class,"updateProfile"]);
    $router->post("/password/update",[UserController::class,"updatePassword"]);
},[LoggedInMiddleware::class]);

$router->group("cart",function() use($router){ 
    $router->post("/add",[CartController::class,"add"]);
    $router->post("/delete",[CartController::class,"delete"]);
    $router->post("/updateQty",[CartController::class,"updateQty"]);
    $router->post("/checkout",[CartController::class,"checkout"]);
});

$router->group("admin",function() use($router){
    
    $router->get("/login",[AdminController::class,"loginView"],["excludes"=>[AdminMiddleware::class]]);

    $router->post("/login",[AdminController::class,"login"],["excludes"=>[AdminMiddleware::class]]);

    $router->get("/logout",[AdminController::class,"logout"]);

    $router->get("/",[AdminController::class,"index"]);

    $router->get("/statistical/{type}",[AdminController::class,"statistical"]);

    $router->group("category",function() use($router){
        $router->get("/",[CategoryController::class,"index"]);
        $router->post("/add",[CategoryController::class,"add"]);
        $router->post("/delete/{id}",[CategoryController::class,"delete"]);
        $router->get("/edit/{id}",[CategoryController::class,"edit"]);
        $router->post("/update/{id}",[CategoryController::class,"update"]);
    });

    $router->group("product",function() use($router){
        $router->get("/",[ProductController::class,"index"]);
        $router->get("/add",[ProductController::class,"saveView"]);
        $router->post("/add",[ProductController::class,"save"]);
        $router->get("/edit/{id}",[ProductController::class,"edit"]);
        $router->post("/update/{id}",[ProductController::class,"update"]);
        $router->post("/delete/{id}",[ProductController::class,"delete"]);
    });

    $router->group("coupon",function() use($router){
        $router->get("/",[CouponController::class,"index"]);
        $router->post("/add",[CouponController::class,"add"]);
        $router->post("/delete/{id}",[CouponController::class,"delete"]);
        $router->get("/edit/{id}",[CouponController::class,"edit"]);
        $router->post("/update/{id}",[CouponController::class,"update"]);
    });

    $router->group("order",function() use($router){
        $router->get("/",[OrderController::class,"index"]);
        $router->get("/delete/{id}",[OrderController::class,"deleteOrder"]);
        $router->get("/{id}",[OrderController::class,"order"]);
        $router->post("/status/{id}",[OrderController::class,"status"]);
    });
    
    $router->group("user",function() use($router){
        $router->get("/",[UserController::class,"index"]);
        $router->get("/add",[UserController::class,"addView"]);
        $router->post("/add",[UserController::class,"addUser"]);
        $router->get("/edit/{id}",[UserController::class,"editView"]);
        $router->post("/update/{id}",[UserController::class,"update"]);
        $router->post("/delete/{id}",[UserController::class,"delete"]);
        $router->get("/profile/{id}",[UserController::class,"profile"]);
    });
   
},AdminMiddleware::class);

$router->route();