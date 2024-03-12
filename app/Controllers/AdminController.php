<?php

namespace App\Controllers;
use Mvc\Core\Request;
use Mvc\Core\Auth;
use Mvc\Core\Validator;
use Mvc\Core\DB;

class AdminController {

    public function index()
    {
        $data = array();
        $data['x'] = DB::table("orders")->select(["HOUR(created_at) as hour","COUNT(*) as total_orders","SUM(total) as total"])->whereDate("created_at",date('Y-m-d'))->groupBy("HOUR(created_at)")->get();
        $data["total_sales"] = DB::table("orders")->select(["SUM(total) as total_sales"])->where("status","!=","cancelled")->where("status","!=","returned")->getValue("total_sales");
        $data["orders"] = array(
            "all" => DB::table("orders")->count(),
            "pending" => DB::table("orders")->where("status","pending")->count(),
            "toship" => DB::table("orders")->where("status","toship")->count(),
            "shipping" => DB::table("orders")->where("status","shipping")->count(),
            "completed" => DB::table("orders")->where("status","completed")->count(),
            "cancelled" => DB::table("orders")->where("status","cancelled")->count(),
            "returned" => DB::table("orders")->where("status","returned")->count(),
        );
        $data["total_products"] = DB::table("products")->count();
        $data["total_categories"] = DB::table("categories")->count();
        $data["top_10_saled_products"] = DB::table("order_items ot")->join("products as p","p.id","=","ot.p_id")->join("orders as o","o.id","=","ot.order_id")->leftJoin("product_colors as pcl","pcl.id","=","ot.p_color_id")->select(["p.p_name","p.id","SUM(ot.quantity) as total_saled_items","pcl.color_name","ot.p_size"])->where("o.status","!=","cancelled")->where("o.status","!=","returned")->limit(20)->orderBy("SUM(ot.quantity)","desc")->groupBy("ot.p_id")->get();
        $threshold = 5;
        $data["out_of_stock_products"] = DB::table("products as p")->leftJoin("product_colors as pcl","pcl.p_id","=","p.id")->leftJoin("product_sizes as ps","ps.p_id","=","p.id")->leftJoin("product_colors_sizes as pcs","pcs.color_id","=","pcl.id")->select(["p.id","p.p_name","p.p_stock as p_stock","pcl.stock as pcl_stock","pcl.color_name","ps.stock as ps_stock","ps.size as ps_size","pcs.stock as pcs_stock","pcs.size as pcs_size"])->whereCase(function($query) use ($threshold){
            $query->whenNull("ps.stock")->whenNotNull("pcs.stock")->then("pcs.stock","<",$threshold);
            $query->whenNull("ps.stock")->whenNull("pcs.stock")->then("pcl.stock","<",$threshold);
            $query->whenNull("pcl.stock")->whenNull("pcs.stock")->then("ps.stock","<",$threshold);
            $query->whenElse("p.p_stock","<",$threshold);
        })->orderBy("p.created_at","desc")->get();
        return view("admin/index",$data);
    }

    public function loginView()
    {
        if(getUser("admin")){
            redirect("admin");
        }
        return view("admin/login");
    }

    public function login(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("login_key",["required" => "Username không được để trống"]);
        $validator->validate("password",["required" => "Mật khẩu không được để trống"]);
        if($validator->fails()){
            $validator->flashErrors();
            $validator->flashInputs();
            redirect_back();
        }
        $auth = new Auth();
        $login_key = $request->input("login_key");
        $password = $request->input("password");
        if($auth->guard("admin")->login($login_key,$password)){
            if($request->has("redirect_to")){
                echo "xx";
                redirect_to($request->input("redirect_to"));
            }else{
                echo "abc";
                redirect("admin");
            }
          
        }else{
            message(["message" => "Username hoặc mật khẩu không đúng"]);
            redirect_back();
        }
       
    }

    public function logout()
    {
        $auth = new Auth();
        $auth->guard("admin")->logout();
        redirect("admin/login");
    }

    public function statistical(Request $request,$type)
    {
        switch($type){
            case "date":
                $data = DB::table("orders")->select(["HOUR(created_at) as hour","COUNT(*) as total_orders","SUM(total) as total"])->where("status","!=","cancelled")->where("status","!=","returned")->whereDate("created_at",$request->input("date"))->groupBy("HOUR(created_at)")->get();
                return response()->json(["data"=> $data],200);
                break;
            case "week":
                $data = DB::table("orders")->select(["DATE(created_at) as date","COUNT(*) as total_orders","SUM(total) as total"])->where("status","!=","cancelled")->where("status","!=","returned")->whereDate("created_at",">=",$request->input("start_date"))->whereDate("created_at","<=",$request->input("end_date"))->groupBy("DATE(created_at)")->get();
                return response()->json(["data"=> $data],200);
                break;
            case "month":
                $data = DB::table("orders")->select(["DATE(created_at) as date","COUNT(*) as total_orders","SUM(total) as total"])->where("status","!=","cancelled")->where("status","!=","returned")->whereMonth("created_at",$request->input("month"))->whereYear("created_at",$request->input("year"))->groupBy("DATE(created_at)")->get();
                return response()->json(["data"=> $data],200);
                break;
            case "year":
                $data = DB::table("orders")->select(["MONTH(created_at) as month","COUNT(*) as total_orders","SUM(total) as total"])->where("status","!=","cancelled")->where("status","!=","returned")->whereYear("created_at",$request->input("year"))->groupBy("MONTH(created_at)")->get();
                return response()->json(["data"=> $data],200);
                break;
            default:
                return null; 
        }

    }
}