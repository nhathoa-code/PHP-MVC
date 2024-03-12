<?php

namespace App\Controllers;
use App\Models\Product;
use App\Models\Category;
use Mvc\Core\DB;
use Mvc\Core\File;
use Ramsey\Uuid\Uuid;
use Mvc\Core\Request;
use Mvc\Core\Session;
use Mvc\Core\Validator;

class ProductController {

    public function index(Request $request)
    {
        $file = new File();
        $limit = 10;
        $currentPage = $request->has("page") && is_numeric($request->input("page")) ? $request->input("page") : 1;
        if($request->has("keyword") && !empty($request->input("keyword"))){
            $keyword = $request->input("keyword");
            $query = Product::where("p_name","like","%{$keyword}%")->orWhere("id","like","%{$keyword}%");
        }else{
            $query = Product::orderBy("created_at","desc");
        }
        $number_of_products = $query->count(false);
        $totalPages = ceil($number_of_products / $limit);
        $products = $query->limit($limit)->offset(($currentPage - 1) * $limit)->get();
        $products = array_map(function($item) use($file){
           $item->product_images = $file->dir("images/products/{$item->dir}/product_images")->files();
           if($item->hasColorsSizes()){
                $item->colors_sizes = DB::table("product_colors_sizes as pcs")->select(["pc.color_name","pcs.size","pcs.price","pcs.stock"])->join("product_colors as pc","pcs.color_id","=","pc.id")->where("pcs.p_id",$item->id)->get();
           }elseif($item->hasColors()){
                $item->colors = DB::table("product_colors as pc")->where("pc.p_id",$item->id)->get();
           }elseif($item->hasSizes()){
                $item->sizes = DB::table("product_sizes as ps")->where("ps.p_id",$item->id)->get();
           }
           return $item;
        },$products);
        return view("admin/product/index",["products"=>$products,"totalPages" => $totalPages,"currentPage"=>$currentPage]);
    }

    public function saveView()
    {
        $categories = $this->fetchCategories();
        return view("admin/product/add",["categories" => $categories]);
    }

    public function save(Request $request)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("p_name",["required"]);
        $validator->validate("p_price",["required","integer","greaterThan:10000"],true);
        $validator->validate("p_images",["required","type:image/png,image/jpg,image/jpeg"],true);
        $validator->validate("p_desc",["required","min:100"],true);
        if($request->hasFile("colors")){
            $validator->validate("colors",["type:image/png,image/jpg,image/jpeg"],true);
            foreach($request->file("colors") as $color_index => $file){
                $validator->validate("name_of_color_{$color_index}",["required"]);
                $validator->validate("gallery_of_color_{$color_index}",["required","type:image/png,image/jpg,image/jpeg"],true);
                if($request->has("sizes_of_color_{$color_index}")){-
                    $validator->validate("sizes_of_color_{$color_index}",["array"]);
                    if(is_array($request->input("sizes_of_color_{$color_index}"))){
                        foreach($request->input("sizes_of_color_{$color_index}") as $index => $item){
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>size",["required"]);
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>price",["required","number","greaterThan:10000"],true);
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>stock",["required","number","greaterThan:0"],true);
                        }
                    }
                }
            }
        }else if($request->has("sizes")){
            $validator->validate("sizes",["array"]);
            if(is_array($request->input("sizes"))){
                foreach($request->input("sizes") as $index => $size){
                    $validator->validate("sizes=>{$index}=>value",["required"]);
                    $validator->validate("sizes=>{$index}=>price",["required","number","greaterThan:0"],true);
                    $validator->validate("sizes=>{$index}=>stock",["required","number","greaterThan:0"],true);
                }
            }
        }else{
            $validator->validate("p_stock",["required","number","greaterThan:0"],true);
        }
        if($validator->fails()){
           return response()->json(["error_bag"=> json_encode($validator->getErrors()),"message"=>"Yêu cầu không hợp lệ!"],400);
        }
        $dir = Uuid::uuid4();
        try{
            $product_id = $this->generateNumericID();
            Product::insert([
                "id" => $product_id,
                "p_name" => $request->input("p_name"),
                "p_price" => $request->has("p_price") ? $request->input("p_price") : 0,
                "p_stock" => $request->has("p_stock") ? $request->input("p_stock") : 0,
                "p_desc" => $request->input("p_desc"),
                "dir" => $dir
            ]);
            foreach($request->input("categories") as $cat){
                DB::table("product_categories")->insert([
                    "p_id" => $product_id,
                    "cat_id" => $cat
                ]);
            }
            if($request->hasFile("p_images")){
                foreach($request->file("p_images") as $key => $file){
                    $file_name = $file->name();
                    $file_full_name = ($key + 1) . "_" . $file_name;
                    $file->save("images/products/{$dir}/product_images",$file_full_name);
                }
            }       
            if($request->hasFile("colors")){
                foreach($request->file("colors") as $index => $file){
                    $color_image = $file->save("images/products/{$dir}/colors");
                    $gallery_dir = Uuid::uuid4();
                    $color_id = DB::table("product_colors")->insert([
                        "p_id" => $product_id,
                        "color_image" => $color_image,
                        "color_name" => $request->input("name_of_color_{$index}"),
                        "price" => $request->has("price_of_color_{$index}") ? $request->input("price_of_color_{$index}") : 0,
                        "stock" => $request->has("stock_of_color_{$index}") ? $request->input("stock_of_color_{$index}") : 0,
                        "gallery_dir" => $gallery_dir
                    ]);
                    if($request->hasFile("gallery_of_color_{$index}")){
                        foreach($request->file("gallery_of_color_{$index}") as $key => $file){
                            $file_name = $file->name();
                            $file_full_name = ($key + 1) . "_" . $file_name;
                            $file->save("images/products/{$dir}/{$gallery_dir}",$file_full_name);
                        }
                    }
                    if($request->has("sizes_of_color_{$index}")){
                        foreach($request->input("sizes_of_color_{$index}") as $item){
                            DB::table("product_colors_sizes")->insert([
                                "p_id" => $product_id,
                                "color_id" => $color_id,
                                "size" => $item['size'] ?? "",
                                "price" => $item['price'] ?? 0,
                                "stock" => $item['stock'] ?? 0
                            ]);
                        }
                    }
                }
            }else if($request->has("sizes")){
                foreach($request->input("sizes") as $size){
                    DB::table("product_sizes")->insert([
                        "p_id" => $product_id,
                        "size" => $size['value'] ?? "",
                        "price" => $size['price'] ?? 0,
                        "stock" => $size['stock'] ?? 0,
                    ]);
                }
            }
            message(["message"=>"Lưu sản phẩm thành công"],false);
            return response()->json(["back_url" => url("admin/product")],200);
        }catch(\Exception $e)
        {
            return response()->json("Có lỗi xảy ra, vui lòng thử lại",500);
        }      
      
    }

    public function edit($id)
    {
        $file = new File();
        $product = Product::where("id",$id)->first();
        if(!$product){
            return;
        }
        $categories = $this->fetchCategories();
        $product->categories = array_map(function($item){
            return $item->cat_id;
        },DB::table("product_categories")->where("p_id",$product->id)->get());
        $product->images = $file->dir("images/products/{$product->dir}/product_images")->files();
        if($product->hasColorsSizes()){
            $colors = DB::table("product_colors")->where("p_id",$product->id)->get();
            $product->colors_sizes = array_map(function($color) use($product,$file){
                $sizes = DB::table("product_colors_sizes")->select(["size","price","stock"])->where("p_id",$product->id)->where("color_id",$color->id)->get();
                if(!empty($sizes)){
                    $color->sizes = $sizes;
                }
                $color->gallery_images = $file->dir("images/products/{$product->dir}/{$color->gallery_dir}")->files();
                return $color;
            },$colors);
        }else if($product->hasColors()){
            $colors = DB::table("product_colors")->where("p_id",$product->id)->get();
            $product->colors = array_map(function($color) use($product,$file){
                $color->gallery_images = $file->dir("images/products/{$product->dir}/{$color->gallery_dir}")->files();
                return $color;
            },$colors);
        }else if($product->hasSizes()){
            $sizes = DB::table("product_sizes")->where("p_id",$product->id)->get();
            $product->sizes = $sizes;
        }
        return view("admin/product/edit",["product" => $product,"categories"=>$categories]);
    }

    public function update(Request $request,$id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("p_name",["required"]);
        $validator->validate("p_price",["required","integer","greaterThan:10000"],true);
        $validator->validate("p_images",["required","type:image/png,image/jpg,image/jpeg"],true);
        $validator->validate("p_desc",["required","min:100"]);
        if($request->hasFile("colors")){
            $validator->validate("colors",["type:image/png,image/jpg,image/jpeg"]);
            foreach($request->file("colors") as $color_index => $file){
                $validator->validate("name_of_color_{$color_index}",["required"]);
                $validator->validate("gallery_of_color_{$color_index}",["required","type:image/png,image/jpg,image/jpeg"],true);
                if($request->has("sizes_of_color_{$color_index}")){-
                    $validator->validate("sizes_of_color_{$color_index}",["array"]);
                    if(is_array($request->input("sizes_of_color_{$color_index}"))){
                        foreach($request->input("sizes_of_color_{$color_index}") as $index => $item){
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>size",["required"]);
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>price",["required","number","greaterThan:10000"],true);
                            $validator->validate("sizes_of_color_{$color_index}=>{$index}=>stock",["required","number","greaterThan:0"],true);
                        }
                    }
                }
            }
        }else if($request->has("sizes")){
            $validator->validate("sizes",["array"]);
            if(is_array($request->input("sizes"))){
                foreach($request->input("sizes") as $index => $size){
                    $validator->validate("sizes=>{$index}=>value",["required"]);
                    $validator->validate("sizes=>{$index}=>price",["required","number","greaterThan:0"],true);
                    $validator->validate("sizes=>{$index}=>stock",["required","number","greaterThan:0"],true);
                }
            }
        }else{
            $validator->validate("p_stock",["required","number","greaterThan:0"],true);
        }
        if($validator->fails()){
           return response()->json(["error_bag"=> json_encode($validator->getErrors()),"message"=>"Yêu cầu không hợp lệ!"],400);
        }
        $product = Product::where("id",$id)->first();
        Product::where("id",$id)->update([
            "p_name" => $request->input("p_name"),
            "p_price" => $request->input("p_price"),
            "p_stock" => $request->has("p_stock") ? $request->input("p_stock") : 0,
            "p_desc" => $request->input("p_desc"),
        ]);

        $categories_from_db = array_map(function($item){
            return $item->cat_id;
        },DB::table("product_categories")->where("p_id",$id)->get());

        $categories_from_client = $request->input("categories");

        $full_categories_diff = array_merge(array_diff($categories_from_db,$categories_from_client),array_diff($categories_from_client,$categories_from_db));

        foreach($full_categories_diff as $item){
            if(in_array($item,$categories_from_db)){
                DB::table("product_categories")->where("p_id",$id)->where("cat_id",$item)->delete();
            }else{
                DB::table("product_categories")->insert([
                    "p_id" => $id,
                    "cat_id" => $item
                ]);
            }
        }
        
        // process update product images
        if($request->hasFile("p_images")){
            $p_images_meta = $request->input("p_images_meta");
            if($request->has("p_images_to_delete")){
                foreach($request->input("p_images_to_delete") as $image){
                    delete_file(PUBLIC_PATH . "/images/products/{$product->dir}/product_images/{$image}");
                }
            }
            foreach($request->file("p_images") as $index => $file){
                if($p_images_meta[$index]["status"] === "new"){
                    $file_name = $file->name();
                    $file_full_name = ($index + 1) . "_" . $file_name;
                    $file->save("images/products/{$product->dir}/product_images",$file_full_name);
                }else{
                    $new_name = ($index + 1) . substr($file->name(), 1);
                    rename_file(PUBLIC_PATH . "/images/products/{$product->dir}/product_images/{$file->name()}",PUBLIC_PATH . "/images/products/{$product->dir}/product_images/$new_name");
                    
                }
            }
        }
        // delete colors
        if($request->has("colors_to_delete")){
            foreach($request->input("colors_to_delete") as $color){
                DB::table("product_colors")->where("p_id",$product->id)->where("id",$color["color_id"])->delete();
                remove_dir(PUBLIC_PATH . "/images/products/{$product->dir}/{$color['gallery_dir']}");
                delete_file(PUBLIC_PATH . "/{$color['color_path']}");
            }
        }
        if($request->hasFile("colors")){
            $meta_of_color = $request->input("meta_of_color");
            $colors_from_db = DB::table("product_colors")->select(["id"])->where("p_id",$product->id)->get();
            foreach($request->file("colors") as $index => $file){
                $status = $meta_of_color[$index]['status'];
                $color_id = $status === "old" ? $meta_of_color[$index]['color_id'] : 0;
                $color_name = $request->input("name_of_color_{$index}");
                $gallery_dir = $status === "new" ? Uuid::uuid4() : $meta_of_color[$index]['gallery_dir'];
                if($status === "new"){
                    $color_image = $file->save("images/products/{$product->dir}/colors");
                    if($request->hasFile("gallery_of_color_{$index}")){
                        foreach($request->file("gallery_of_color_{$index}") as $key => $file){
                            $file_name = $file->name();
                            $file_full_name = ($key + 1) . "_" . $file_name;
                            $file->save("images/products/{$product->dir}/{$gallery_dir}",$file_full_name);
                        }
                    }
                }else{
                    if($request->has("new_image_color_{$index}")){
                        delete_file(PUBLIC_PATH . "/{$meta_of_color[$index]['path']}");
                        $color_image = $file->save("images/products/{$product->dir}/colors");
                    }else{
                        $color_image = $meta_of_color[$index]['path'];
                    }
                    $meta_gallery_of_color = $request->input("meta_gallery_of_color_{$index}");
                    if($request->hasFile("gallery_of_color_{$index}")){
                        if($request->has("gallery_images_to_delete_of_color_{$index}")){
                            foreach($request->input("gallery_images_to_delete_of_color_{$index}") as $image){
                                delete_file(PUBLIC_PATH . "/images/products/{$product->dir}/{$gallery_dir}/{$image}");
                            }
                        }
                        foreach($request->file("gallery_of_color_{$index}") as $key => $file){              
                            if($meta_gallery_of_color[$key]["status"] === "new"){
                                $file_name = $file->name();
                                $file_full_name = ($key + 1) . "_" . $file_name;
                                $file->save("images/products/{$product->dir}/{$gallery_dir}",$file_full_name);
                            }else{
                                $new_name = ($key + 1) . substr($file->name(), 1);
                                rename_file(PUBLIC_PATH . "/images/products/{$product->dir}/{$gallery_dir}/{$file->name()}",PUBLIC_PATH . "/images/products/{$product->dir}/{$gallery_dir}/$new_name");
                            }
                        }
                    }
                }
                if(isset($colors_from_db[$index])){
                    DB::table("product_colors")->where("p_id",$product->id)->where("id",$colors_from_db[$index]->id)->update([
                        "color_image" => $color_image,
                        "color_name" => $color_name,
                        "price" => $request->has("price_of_color_{$index}") ? $request->input("price_of_color_{$index}") : 0,
                        "stock" => $request->has("stock_of_color_{$index}") ? $request->input("stock_of_color_{$index}") : 0,
                        "gallery_dir" => $gallery_dir
                    ]);
                }else{
                    $color_id = DB::table("product_colors")->insert([
                        "p_id" => $product->id,
                        "color_image" => $color_image,
                        "color_name" => $request->input("name_of_color_{$index}"),
                        "price" => $request->has("price_of_color_{$index}") ? $request->input("price_of_color_{$index}") : 0,
                        "stock" => $request->has("stock_of_color_{$index}") ? $request->input("stock_of_color_{$index}") : 0,
                        "gallery_dir" => $gallery_dir
                    ]);
                }

                if($request->has("sizes_of_color_{$index}")){
                    $colors_sizes_from_db = DB::table("product_colors_sizes")->where("p_id",$product->id)->where("color_id",$color_id)->get();
                    foreach($request->input("sizes_of_color_{$index}") as $size_index => $item){
                        if(isset($colors_sizes_from_db[$size_index])){
                            DB::table("product_colors_sizes")->where("id",$colors_sizes_from_db[$size_index]->id)->update([
                                "size" => $item['size'],
                                "price" => $item['price'],
                                "stock" => $item['stock']
                            ]);
                        }else{
                            DB::table("product_colors_sizes")->insert([
                                "p_id" => $product->id,
                                "color_id" => $color_id,
                                "size" => $item['size'],
                                "price" => $item['price'],
                                "stock" => $item['stock']
                            ]);
                        }
                    }
                    if(count($colors_sizes_from_db) > count($request->input("sizes_of_color_{$index}"))){
                        for($i = count($request->input("sizes_of_color_{$index}")); $i < count($colors_sizes_from_db); $i++){
                            DB::table("product_colors_sizes")->where("id",$colors_sizes_from_db[$i]->id)->delete();
                        }   
                    }
                }else{
                    DB::table("product_colors_sizes")->where("p_id",$product->id)->where("color_id",$color_id)->delete();
                }
            }
            if($product->hasSizes()){
                DB::table("product_sizes")->where("p_id",$product->id)->delete();
            }
        }else{
            if($product->hasColorsSizes() || $product->hasColors()){
                $colors = DB::table("product_colors")->where("p_id",$product->id)->get();
                foreach($colors as $color){
                    remove_dir(PUBLIC_PATH . "/images/products/{$product->dir}/{$color->gallery_dir}");
                    delete_file(ROOT_PATH . "/{$color->color_image}");
                    DB::table("product_colors")->where("id",$color->id)->delete();
                }
            }
            if($request->has("sizes")){
                $sizes_from_db = DB::table("product_sizes")->where("p_id",$product->id)->get();
                foreach($request->input("sizes") as $index => $size){
                    if(isset($sizes_from_db[$index])){
                        DB::table("product_sizes")->where("id",$sizes_from_db[$index]->id)->where("p_id",$product->id)->update([
                            "size" => $size['value'],
                            "price" => $size['price'],
                            "stock" => $size['stock']
                        ]);
                    }else{
                        DB::table("product_sizes")->insert([
                            "p_id" => $product->id,
                            "size" => $size['value'],
                            "price" => $size['price'],
                            "stock" => $size['stock']
                        ]);
                    }
                }
                if(count($sizes_from_db) > count($request->input("sizes"))){
                    for($i = count($request->input("sizes")); $i < count($sizes_from_db); $i++){
                        DB::table("product_sizes")->where("id",$sizes_from_db[$i]->id)->delete();
                    }   
                }
            }else{
                DB::table("product_sizes")->where("p_id",$product->id)->delete();
            }
        }
        message(["message"=>"Cập nhật sản phẩm thành công"],false);
        return response()->json("",200);
    }

    public function delete($id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json("Bạn không có quyền này",403);
        }
        $product = Product::where("id",$id)->first();
        remove_dir(PUBLIC_PATH . "/images/products/{$product->dir}");
        $product->delete();
        message(["message"=>"Xóa sản phẩm thành công"],false);
        return response()->json("",200);
    }

    public function fetchCategories($parent_id = NULL,) 
    {
        $categories = [];
        if($parent_id === NULL){
            $categories_from_db = Category::whereNull("parent_id");
        }else{
            $categories_from_db = Category::where("parent_id",$parent_id);
        }
        $categories_from_db = $categories_from_db->orderBy("id","desc")->get();
        foreach ($categories_from_db as $cat) { 
            $category = [
                "id" => $cat->id,
                "cat_name" => $cat->cat_name,
                "parent_id" => $cat->parent_id
            ];
            $children = $this->fetchCategories($cat->id);
            if(!empty($children)){
                $category['children'] = $children;
            }

            $categories[] = $category;
        }
    
        return $categories;
    }

    public function generateNumericID($length = 10) 
    {
        $id = 'VNH';
        for ($i = 0; $i < $length; $i++) {
            $id .= mt_rand(0, 9); 
        }
        return $id;
    }
    
    // client
    public function detail($id)
    {
        $data = array();
        $file = new File();
        $session = new Session;
        $product = Product::where("id",$id)->first();
        if(!$product){
            return "Sản phẩm không tồn tại";
        }
        $product->images = $file->dir("images/products/{$product->dir}/product_images")->files();
        $colors = DB::table("product_colors")->where("p_id",$product->id)->get();
        if(!empty($colors)){
            $product->colors = array_map(function($color) use($product,$file){
                $sizes = DB::table("product_colors_sizes")->select(["size","price","stock"])->where("p_id",$product->id)->where("color_id",$color->id)->get();
                if(!empty($sizes)){
                    $color->sizes = $sizes;
                }
                $color->gallery_images = $file->dir("images/products/{$product->dir}/{$color->gallery_dir}")->files();
                return $color;
            },$colors);
            $colors_gallery = array_map(function($item){
                $array = array(
                            "color_id" => $item->id,
                            "color_name" => $item->color_name,
                            "gallery" => $item->gallery_images
                        );
                if(isset($item->sizes)){
                    $array["sizes"] = array_map(function($size){
                                            return array(
                                                "size" => $size->size,
                                                "price" => $size->price,
                                                "stock" => $size->stock
                                            );
                                        },$item->sizes);
                }else{
                    $array["price"] = $item->price;
                    $array["stock"] = $item->stock;
                }        
                return $array;
            },$product->colors);
        }else{
            $sizes = DB::table("product_sizes")->select(["size","price","stock"])->where("p_id",$product->id)->get();
            if(!empty($sizes)){
                $product->sizes = $sizes;
            }
        }
        if(isLoggedIn()){
            if(DB::table("wish_list")->where("user_id",getUser()->id)->where("p_id",$product->id)->first()){
                $product->wl = true;
            }
        }
        $data['recent_viewed_products'] = $session->get("recent_viewed_products");
        //$session->remove("recent_viewed_products");
        //array_print($session->get("recent_viewed_products"));
        if(!$session->has("recent_viewed_products=>{$product->id}")){
            $product_info = array(
                "thumbnail" => $product->images[0],
                "name" => $product->p_name,
                "price" => $product->p_price,
            );
            if(isset($product->colors)){
                $product_info['colors'] = array_map(function($item){
                    return $item->color_image;
                },$product->colors);
            }
            $session->push("recent_viewed_products",$product_info,$product->id);
        }
        $data["product"] = $product;
        if(isset($colors_gallery)){
            $data["colors_gallery"] = $colors_gallery; 
        }
        return view("client/product/detail",$data);
    }

    public function addWl(Request $request)
    {
        if(!isLoggedIn()){
            message(["message" => "Vui lòng đăng nhập trước!"]);
            return response()->json("Vui lòng đăng nhập trước!",400);
        }
        if($request->has("p_id")){
            try{
                if(!DB::table("wish_list")->where("user_id",getUser()->id)->where("p_id",$request->input("p_id"))->first()){
                    DB::table("wish_list")->insert([
                        "user_id" => getUser()->id,
                        "p_id" => $request->input("p_id"),
                    ]);
                }
                return response()->json("Sản phẩm đã được thêm vào wish list",200);
            }catch(\Exception $e)
            {
                return response()->json("Có lỗi xảy ra, vui lòng thử lại!",400);
            }
        }
    }

    public function removeWl(Request $request)
    {
        if(!isLoggedIn()){
            message(["message" => "Vui lòng đăng nhập trước!"]);
            return response()->json("Vui lòng đăng nhập trước!",400);
        }
        if($request->has("p_id")){
            if(DB::table("wish_list")->where("user_id",getUser()->id)->where("p_id",$request->input("p_id"))->limit(1)->delete()){
                return response()->json("Sản phẩm đã được xóa khỏi wish list",200);
            }else{
                return response()->json("Có lỗi xảy ra, vui lòng thử lại!",400);
            }
        }
    }
}