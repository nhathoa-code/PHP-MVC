<?php

namespace App\Controllers;
use App\Models\Category;
use Mvc\Core\File;
use Mvc\Core\Request;
use Mvc\Core\Validator;

class CategoryController {

    public function index()
    {
        $fetchCategories = function () {
            return $this->fetchCategories();
        };

        $fetchParentOptions = function() {
            return $this->fetchParentOptions();
        };

        return view("admin/category/index",["fetchCategories"=> $fetchCategories,"fetchParentOptions"=>$fetchParentOptions]);
    }

    public function add(Request $request)
    {   
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("cat_name",["required"]);
        $validator->validate("cat_slug",["required"]);
        if($validator->fails()){
            return response()->json(["message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
        }
        $data = array(
            "cat_name" => $request->input("cat_name"),
            "cat_slug" => $request->input("cat_slug"),
        );
        if(!empty($request->input("parent_id"))){
            $data["parent_id"] = $request->input("parent_id");
        }
        if($request->hasFile("cat_image")){
            $validator->validate("cat_image",["type:image/png,image/jpg"]);
            if($validator->fails()){
                return response()->json(["message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
            }
            $data["cat_image"] = $request->file("cat_image")->save("images/cat");
        }
        try{
            $id = Category::insert($data);
            $data["id"] = $id;
            if($request->hasFile("cat_image")){
                $data['cat_image'] = url($data['cat_image']);
            }
            $data['delete_url'] = url("admin/category/delete/{$id}");
            $data['edit_url'] = url("admin/category/edit/{$id}");
            return response()->json(["message"=>"item added","added_item"=> $data],200);
        }
        catch(\Exception $e)
        {
            return response()->json(["message"=>"something wrong,please try again"],400);
        }
    }

    public function delete($id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        try{
            $cat = Category::where("id","=",$id)->first();
            if($cat->cat_image){
                (new File)->delete(ROOT_PATH . "/" . $cat->cat_image);
            }
            $subCategories = Category::where("parent_id",$cat->id)->get();
            foreach($subCategories as $subCat){
                Category::where("id",$subCat->id)->update([
                    "parent_id" => $cat->parent_id
                ]);
            }
            Category::where("id","=",$id)->delete();
            return response()->json(["message"=>"item deleted","deleted_id" => $cat->id],200);
        }
        catch(\Exception $e)
        {
            return response()->json(["message"=>"something wrong,please try again"],400);
        }
    }

    public function edit($id)
    {
        $cat = Category::where("id","=",$id)->first();
        $fetchParentOptions = function() use($cat){
            return $this->fetchParentOptions(NULL, 0, $cat, TRUE);
        };
        return view("admin/category/edit",["cat"=>$cat,"fetchParentOptions"=>$fetchParentOptions]);
    }

    public function update(Request $request,$id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("cat_name",["required"]);
        $validator->validate("cat_slug",["required"]);
        if($validator->fails()){
            return response()->json(["message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
        }
        try{
            $cat = Category::where("id",$id)->first();
            $cat->cat_name = $request->input("cat_name");
            $cat->cat_slug = $request->input("cat_slug");
            if($cat->parent_id === null && $cat->hasChildren() && !empty($request->input("parent_id"))){
                foreach($cat->children() as $item){
                    $item->parent_id = null;
                    $item->update();
                }
            }
            $cat->parent_id = !empty($request->input("parent_id")) ? $request->input("parent_id") : NULL;
            if($request->hasFile("cat_image")){
                $validator->validate("cat_image",["type:image/png,image/jpg,image/jpeg"]);
                if($validator->fails()){
                    return response()->json(["message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
                }
                if(!empty($request->input("old_cat_image"))){
                    (new File)->delete(ROOT_PATH . "/" . $request->input("old_cat_image"));
                }
                $cat->cat_image = $request->file("cat_image")->save("images/cat");
            }
            $cat->update();
            return response()->json(["category_url"=>url("admin/category")],200);
        }
        catch(\Exception $e)
        {
           return response()->json(["message"=>"something wrong,please try again"],500);
        }
       
    }

    public function fetchCategories($parent_id = NULL, $indent = 0) {
        $categories = null;
        if($parent_id === NULL){
            $categories = Category::whereNull("parent_id");
        }else{
            $categories = Category::where("parent_id",$parent_id);
        }
        $categories = $categories->orderBy("id","desc")->get();
        foreach ($categories as $cat) { ?>
           <tr id="<?php echo "cat-{$cat->id}" ?>">
                <td>
                    <?php echo str_repeat("— ",$indent) . $cat->cat_name ?>
                    <div class="row-actions">
                        <span data-route="<?php echo url("admin/category/delete/{$cat->id}") ?>" class="delete"><a href="javascript:void(0)">Xóa</a></span>  | 
                        <span class="edit"><a href="<?php echo url("admin/category/edit/{$cat->id}") ?>">Sửa</a></span>
                    </div>
                </td>
                <td>
                    <?php if($cat->cat_image): ?>
                        <img style="width: 50px;" src="<?php echo url($cat->cat_image) ?>" alt="">
                    <?php else: ?>
                        <div style="width:50px;height:50px"></div>
                    <?php endif; ?>
                    
                </td>
                <td><?php echo $cat->cat_slug ?></td>
                <td><?php echo $cat->count(); ?></td>
            </tr>
        <?php
            $this->fetchCategories($cat->id, $indent + 1);
        }
    }

    public function fetchParentOptions($parent_id = NULL, $indent = 0, $edited_cat = NULL, $is_update = FALSE) {
        $categories = null;
        if($parent_id === NULL){
            $categories = Category::whereNull("parent_id");
        }else{
            $categories = Category::where("parent_id",$parent_id);
        }
        $categories = $categories->orderBy("id","desc")->get();
        foreach ($categories as $cat) { ?>
            <?php if($is_update): ?>
                <?php if($edited_cat->id != $cat->id): ?>
                    <option <?php echo $edited_cat->parent_id !== NULL && $edited_cat->parent_id === $cat->id ? "selected" : "" ?> id="<?php echo "parent-id-{$cat->id}" ?>" data-level="<?php echo $indent ?>" value="<?php echo $cat->id ?>"><?php echo str_repeat("— ",$indent) . $cat->cat_name ?></option>
                <?php endif; ?>
            <?php else: ?>
                <option id="<?php echo "parent-id-{$cat->id}" ?>" data-level="<?php echo $indent ?>" value="<?php echo $cat->id ?>"><?php echo str_repeat("— ",$indent) . $cat->cat_name ?></option>
            <?php endif; ?>    
        <?php
            $this->fetchParentOptions($cat->id, $indent + 1, $edited_cat, $is_update);
        }
    }
}