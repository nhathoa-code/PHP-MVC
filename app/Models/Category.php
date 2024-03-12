<?php

namespace App\Models;
use Mvc\Core\Model;
use Mvc\Core\DB;

class Category extends Model {
    
    protected $table = "categories";

    public function children()
    {
        return $this->where("parent_id",$this->id)->get();
    }

    public function hasChildren()
    {
        return $this->where("parent_id",$this->id)->count();
    }

    public function count()
    {
        return DB::table("product_categories")->where("cat_id",$this->id)->count();
    }

}