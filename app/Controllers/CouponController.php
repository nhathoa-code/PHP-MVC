<?php

namespace App\Controllers;
use Mvc\Core\Request;
use Mvc\Core\Validator;
use App\Models\Coupon;

class CouponController {
    
    public function index()
    {
        $coupons = Coupon::orderBy("id","desc")->get();
        return view("admin/coupon/index",["coupons"=>$coupons]);
    }

    public function add(Request $request)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("code",["required","unique:coupons"],true);
        $validator->validate("amount",["required","integer"],true);
        $validator->validate("minimum_spend",["required","integer","greaterThan:amount"],true);
        $validator->validate("usage",["required","integer","greaterThan:0"],true);
        $validator->validate("per_user",["required","integer","greaterThan:0"],true);
        $validator->validate("start_time",["required","datetime|d-m-Y H:i"],true);
        $validator->validate("end_time",["required","datetime|d-m-Y H:i","greaterThan:start_time"],true);
        if($validator->fails()){
            return response()->json(["error_bag" => json_encode($validator->getErrors()),"message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
        }
        try{
            Coupon::insert([
                "code" => $request->input("code"),
                "amount" => $request->input("amount"),
                "minimum_spend" => $request->input("minimum_spend"),
                "coupon_usage" => $request->input("usage"),
                "per_user" => $request->input("per_user"),
                "start_time" => \DateTime::createFromFormat("d-m-Y H:i", $request->input("start_time"))->format("Y-m-d H:i"),
                "end_time" => \DateTime::createFromFormat("d-m-Y H:i", $request->input("end_time"))->format("Y-m-d H:i"),
            ]);
            ?>
                <tr>
                    <td>
                        <?php echo $request->input("code"); ?>
                        <div class="row-actions">
                            <span data-route="<?php echo url("admin/coupon/delete/{$request->input("code")}") ?>" class="delete"><a href="javascript:void(0)">Delete</a></span>  | 
                            <span class="edit"><a href="<?php echo url("admin/coupon/edit/{$request->input("code")}") ?>">Edit</a></span>
                        </div>
                    </td>
                    <td><?php echo number_format($request->input("amount"),0,"",".") ?>đ</td>
                    <td><?php echo number_format($request->input("minimum_spend"),0,"","."); ?>đ</td>
                    <td><?php echo $request->input("usage") ?></td>
                    <td><?php echo 0 ?></td>
                    <td><?php echo \DateTime::createFromFormat("d-m-Y H:i", $request->input("start_time"))->format("Y-m-d H:i") ?></td>
                    <td><?php echo \DateTime::createFromFormat("d-m-Y H:i", $request->input("end_time"))->format("Y-m-d H:i") ?></td>
                </tr>
            <?php
        }catch(\Exception $e){
            return response()->json("something wrong,try again",500);
        }
    }

    public function delete($id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        Coupon::where("id",$id)->limit(1)->delete();
        return response()->json("coupon deleted successfully",200);
    }

    public function edit($id)
    {
        $coupon = Coupon::where("id",$id)->first();
        if(!$coupon){
            return;
        }
        return view("admin/coupon/edit",["coupon"=>$coupon]);
    }

    public function update(Request $request,$id)
    {
        $user = getUser("admin");
        if(!$user || $user->role !== "admin"){
            return response()->json(["message"=>"Bạn không có quyền này"],403);
        }
        $validator = new Validator($request->all());
        $validator->validate("code",["required","unique:coupons_exclude-{$id}","max:10"],true);
        $validator->validate("amount",["required","integer"],true);
        $validator->validate("minimum_spend",["required","integer","greaterThan:amount"],true);
        $validator->validate("usage",["required","integer","greaterThan:0"],true);
        $validator->validate("used",["required","integer","lessThan:usage"],true);
        $validator->validate("start_time",["required","datetime|d-m-Y H:i"],true);
        $validator->validate("end_time",["required","datetime|d-m-Y H:i","greaterThan:start_time"],true);
        if($validator->fails()){
            return response()->json(["error_bag" => json_encode($validator->getErrors()),"message"=>"Yêu cầu không hợp lệ, vui lòng thử lại"],400);
        }
        Coupon::where("id",$id)->limit(1)->update([
            "code" => $request->input("code"),
            "amount" => $request->input("amount"),
            "minimum_spend" => $request->input("minimum_spend"),
            "coupon_usage" => $request->input("usage"),
            "coupon_used" => $request->input("used"),
            "start_time" => \DateTime::createFromFormat("d-m-Y H:i", $request->input("start_time"))->format("Y-m-d H:i"),
            "end_time" => \DateTime::createFromFormat("d-m-Y H:i", $request->input("end_time"))->format("Y-m-d H:i")
        ]);
        return response()->json(["coupon_url"=>url("admin/coupon")],200);
    }
}