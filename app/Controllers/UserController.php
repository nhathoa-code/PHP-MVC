<?php

namespace App\Controllers;
use Mvc\Core\Request;
use Mvc\Core\DB;
use App\Models\User;
use Mvc\Core\Validator;

class UserController {

    public function index(Request $request)
    {
        $limit = 10;
        $currentPage = $request->has("page") && is_numeric($request->input("page")) ? $request->input("page") : 1;
        $query = User::orderBy("id","desc");
        if($request->has("role")){
            $role = $request->input("role");
            $query->where("role",$role);
        }elseif($request->has("unverified")){
            $query->whereNull("email_verified_at")->where("role","user");
        }
        if($request->has("keyword")){
            $keyword = $request->input("keyword");
            $query->where(function($query) use($keyword){
                $query->where("name","like","%$keyword%")->orWhere("email","like","%$keyword%");
            });
        }
        $total_users = $query->count(false);
        $users = $query->limit($limit)->offset(($currentPage - 1) * $limit)->get();
        $number_map = array(
            "all" => DB::table("users")->count(),
            "admin" => DB::table("users")->where("role","admin")->count(),
            "user" => DB::table("users")->where("role","user")->count(),
            "unverified" => DB::table("users")->whereNull("email_verified_at")->where("role","user")->count(),
        );
        return view("admin/user/index",['users' =>$users,"total_orders"=>$total_users,"currentPage"=>$currentPage,"totalPages"=>ceil($total_users / $limit),"number_map" => $number_map]);
    }

    public function addAddress(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("name",["required"]);
        $validator->validate("phone",["required","pattern:/^0[0-9]{9}$/"]);
        $validator->validate("province",["required"]);
        $validator->validate("province_id",["required","number"],true);
        $validator->validate("district",["required"]);
        $validator->validate("district_id",["required","number"],true);
        $validator->validate("ward",["required"]);
        $validator->validate("ward_code",["required","number"],true);
        $validator->validate("address",["required"]);
        if($validator->fails()){
            message(["message"=>"Vui lòng nhập đầy đủ và chính xác dữ liệu!"]);
            redirect_back();
        }
        $name = $request->input("name");
        $phone = $request->input("phone");
        $province = $request->input("province");
        $district = $request->input("district");
        $ward = $request->input("ward");
        $address = $request->input("address");
        $province_id = $request->input("province_id");
        $district_id = $request->input("district_id");
        $ward_code = $request->input("ward_code");
        $data = array(
                "name" => $name,
                "phone" => $phone,
                "province" => $province,
                "district" => $district,
                "ward" => $ward,
                "address" => $address,
                "province_id" => $province_id,
                "district_id" => $district_id,
                "ward_code" => $ward_code
        );
        if($request->has("default")){
            $addresses = DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","address")->get();
            foreach($addresses as $item){
                $add = unserialize($item->meta_value);
                if(array_key_exists("default",$add)){
                    unset($add['default']);
                    DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","address")->where("id",$item->id)->limit(1)->update([
                        "meta_value" => serialize($add)
                    ]);
                }
            }
            $data['default'] = true;
        }
        DB::table("user_meta")->insert([
            "user_id" => getUser()->id,
            "meta_key" => "address",
            "meta_value" => serialize($data)
        ]);
        redirect("user/addresses");
    }

    public function deleteAddress(Request $request)
    {
        $id = $request->input("id");
        if($id){
            DB::table("user_meta")->where("user_id",getUser()->id)->where("id",$id)->where("meta_key","address")->limit(1)->delete();
        }
        redirect("user/addresses");
    }

    public function updateAddress(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("name",["required"]);
        $validator->validate("phone",["required"]);
        $validator->validate("province",["required"]);
        $validator->validate("province_id",["required","number"],true);
        $validator->validate("district",["required"]);
        $validator->validate("district_id",["required","number"],true);
        $validator->validate("ward",["required"]);
        $validator->validate("ward_code",["required","number"],true);
        $validator->validate("address",["required"]);
        $validator->validate("id",["required","number"],true);
        if($validator->fails()){
            message(["message"=>"Có lỗi xảy ra, vui lòng thử lại!"]);
            redirect_back();
        }
        $id = $request->input("id");
        if($id){
            $name = $request->input("name");
            $phone = $request->input("phone");
            $province = $request->input("province");
            $district = $request->input("district");
            $ward = $request->input("ward");
            $address = $request->input("address");
            $province_id = $request->input("province_id");
            $district_id = $request->input("district_id");
            $ward_code = $request->input("ward_code");
            $data = array(
                "name" => $name,
                "phone" => $phone,
                "province" => $province,
                "district" => $district,
                "ward" => $ward,
                "address" => $address,
                "province_id" => $province_id,
                "district_id" => $district_id,
                "ward_code" => $ward_code
            );
            if($request->has("default")){
                $addresses = DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","address")->get();
                foreach($addresses as $item){
                    $add = unserialize($item->meta_value);
                    if(array_key_exists("default",$add)){
                        unset($add['default']);
                        DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","address")->where("id",$item->id)->limit(1)->update([
                            "meta_value" => serialize($add)
                        ]);
                    }
                }
                $data['default'] = true;
            }
            DB::table("user_meta")->where("user_id",getUser()->id)->where("id",$id)->where("meta_key","address")->limit(1)->update([
                "meta_value" => serialize($data)
            ]);
        }
        redirect("user/addresses");
    }

    public function addView()
    {
        return view("admin/user/add");
    }

    public function addUser(Request $request)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            message(["message"=>"Bạn không có quyền này!"]);
            redirect_back();
        }
        $validator = new Validator($request->all());
        $validator->validate("name",["required" => "Vui lòng nhập họ tên"]);
        $validator->validate("login_key",["required" => "Vui lòng nhập mã đăng nhập","unique:users"=>"Mã đăng nhập này đã được đăng ký!"],true);
        $validator->validate("email",["required" => "Vui lòng nhập email","email","unique:users"=>"Email này đã được đăng ký!"],true);
        $validator->validate("password",["required" => "Vui lòng nhập mật khẩu","min:6"],true);
        $validator->validate("role",["required" => "Vui lòng chọn vai trò"]);
        if($validator->fails()){
            $validator->flashErrors();
            $validator->flashInputs();
            redirect_back();
        }
        try{
            $user_id = User::insert([
                "email" => $request->input("email"),
                "name" => $request->input("name"),
                "login_key" => $request->input("login_key"),
                "password" => password_hash($request->input("password"),PASSWORD_DEFAULT),
                "role" => $request->input("role"),
                "email_verified_at" => date("Y-m-d H:i:s", time())
            ]);
            if($request->input("role") === "user"){
                DB::table("user_meta")->insert([
                    "user_id" => $user_id,
                    "meta_key" => "point",
                    "meta_value" => 0
                ]);
                DB::table("user_meta")->insert([
                    "user_id" => $user_id,
                    "meta_key" => "total_spend",
                    "meta_value" => 0
                ]);
                DB::table("user_meta")->insert([
                    "user_id" => $user_id,
                    "meta_key" => "rank",
                    "meta_value" => "member"
                ]);
            }
            redirect("admin/user");
        }catch(\Exception $e)
        {
            message(["message"=>"Có lỗi xảy ra, vui lòng thử lại!"]);
            redirect_back();
        }
    }

    public function editView($id)
    {
        $user = User::where("id",$id)->first();
        return view("admin/user/edit",["user" => $user]);
    }

    public function update(Request $request,$id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            message(["message"=>"Bạn không có quyền này!"]);
            redirect_back();
        }
        $user = User::where("id",$id)->first();
        if(!$user){
            message(["message"=>"User không tồn tại!"]);
            redirect_back();
        }
        $validator = new Validator($request->all());
        $validator->validate("name",["required" => "Vui lòng nhập họ tên"]);
        $validator->validate("login_key",["required" => "Vui lòng nhập mã đăng nhập","unique:users_exclude-{$id}"=>"Mã đăng nhập này đã được đăng ký!"],true);
        $validator->validate("email",["required" => "Vui lòng nhập email","unique:users_exclude-{$id}"=>"Email này đã được đăng ký!"],true);
        $validator->validate("role",["required" => "Vui lòng chọn vai trò"]);
        if($request->has("password")){
            $validator->validate("password",["required" => "Vui lòng nhập mật khẩu","min:6"],true);
        }   
        $validator->validate("role",["required" => "Vui lòng chọn vai trò"]);
        if($validator->fails()){
            $validator->flashErrors();
            $validator->flashInputs();
            redirect_back();
        }
        try{
            $user->email = $request->input("email");
            $user->name = $request->input("name");
            $user->login_key = $request->input("login_key");
            if($user->role !== "admin" || ($user->role === "admin" && $user->email_verified_at !== null)){
                $user->role = $request->input("role");
            }
            if($request->has("password")){
                $user->password = password_hash($request->input("password"),PASSWORD_DEFAULT);
            }
            $user->update();
            redirect("admin/user");
        }catch(\Exception $e)
        {
            message(["message"=>"Có lỗi xảy ra, vui lòng thử lại!"]);
            redirect_back();
        }
    }

    public function delete($id)
    {
        $current_user = getUser("admin");
        if(!$current_user || $current_user->role !== "admin"){
            redirect_back();
        }
        $user = User::where("id",$id)->first();
        if($user->role === "admin" && $user->email_verified_at === null){
            redirect_back();
        }
        $user->delete();
        redirect_back();
    }

    public function updateProfile(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("name",["required"]);
        $validator->validate("birth_day",["required","pattern:/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/"],true);
        $validator->validate("phone",["required","pattern:/^0[0-9]{9}$/"]);
        $validator->validate("province",["required"]);
        $validator->validate("province_id",["required","number"],true);
        $validator->validate("district",["required"]);
        $validator->validate("district_id",["required","number"],true);
        $validator->validate("ward",["required"]);
        $validator->validate("ward_code",["required","number"],true);
        $validator->validate("gender",["required"]);
        if($validator->fails()){
            message(["message"=>"Có lỗi xảy ra, vui lòng thử lại!"]);
            redirect_back();
        }
        $data = array(
            "name" => $request->input("name"),
            "phone" => $request->input("phone"),
            "email" => $request->input("email"),
            "birth_day" => $request->input("birth_day"),
            "province" => $request->input("province"),
            "province_id" => $request->input("province_id"),
            "district" => $request->input("district"),
            "district_id" => $request->input("district_id"),
            "ward" => $request->input("ward"),
            "ward_code" => $request->input("ward_code"),
            "gender" => $request->input("gender")
        );
        if(DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","profile")->first()){
            DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","profile")->update([
                "meta_value" => serialize($data)
            ]);
        }else{
            DB::table("user_meta")->insert([
                "user_id" => getUser()->id,
                "meta_key" => "profile",
                "meta_value" => serialize($data)
            ]);
        }
        redirect_back();
    }

    public function profile($id)
    {
        $user = User::where("id",$id)->first();
        if($user){
            $user->meta = DB::table("user_meta")->where("user_id",$user->id)->get();
            if(!empty($user->meta)){
                $user->meta = array_map(function($item){
                    return [$item->meta_key => $item->meta_value];
                },$user->meta);
                $user->meta = array_merge(...$user->meta);
            }
            $user->profile = DB::table("user_meta")->select(["meta_value"])->where("meta_key","profile")->where("user_id",$user->id)->first();
            if($user->profile){
                $user->profile = unserialize($user->profile->meta_value);
            }
            $user->toship_orders = DB::table("orders")->where("user_id",$user->id)->where("status","toship")->count();
            $user->shipping_orders = DB::table("orders")->where("user_id",$user->id)->where("status","shipping")->count();
            $user->completed_orders = DB::table("orders")->where("user_id",$user->id)->where("status","completed")->count();
            $user->cancelled_orders = DB::table("orders")->where("user_id",$user->id)->where("status","cancelled")->count();
            $user->orders = DB::table("orders")->where("user_id",$user->id)->where("status","completed")->get();
        }
        return view("admin/user/profile",["user"=>$user]);
    }

    public function updatePassword(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("pass",["required"=>"Vui lòng nhập mật khẩu"]);
        $validator->validate("newpass",["required"=>"Vui lòng nhập mật khẩu","min:6"=>"Độ dài không được nhỏ hơn 6"],true);
        $validator->validate("retype_newpass",["required"=>"Vui lòng nhập mật khẩu","match:newpass"=>"Mật khẩu nhập lại không đúng"],true);
        if($validator->fails()){
            $validator->flashErrors();
            redirect_back();
        }
        $user = User::where("id",getUser()->id)->first();
        if(!password_verify($request->input("pass"),$user->password)){
            message(["message"=>"Mật khẩu hiện tại không đúng","type"=>"warning"]);
            redirect_back();
        }
        $user->password = password_hash($request->input("newpass"),PASSWORD_DEFAULT);
        $user->update();       
        message(["message"=>"Đổi mật khẩu thành công","type"=>"success"]);
        redirect_back();
    }
}