<?php

namespace App\Models;
use Mvc\Core\Model;
use Mvc\Core\DB;

class Product extends Model {

    protected $table = "products";

    public function hasColorsSizes()
    {
        $colors_sizes = DB::table("product_colors_sizes")->where("p_id",$this->id)->count();
        if($colors_sizes > 0){
            return true;
        }else{
            return false;
        }
    }

    public function hasColors()
    {
        $colors = DB::table("product_colors")->where("p_id",$this->id)->count();
        if($colors > 0){
            return true;
        }else{
            return false;
        }
    }

    public function hasSizes()
    {
        $sizes = DB::table("product_sizes")->where("p_id",$this->id)->count();
        if($sizes > 0){
            return true;
        }else{
            return false;
        }
    }

   
}