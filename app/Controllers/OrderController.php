<?php

namespace App\Controllers;
use Mvc\Core\DB;
use Mvc\Core\File;
use Mvc\Core\Request;
use Mvc\Core\Mail;
use App\Models\Order;

class OrderController {
    
    public function index(Request $request)
    {
        $limit = 10;
        $currentPage = $request->has("page") && is_numeric($request->input("page")) ? $request->input("page") : 1;
        $status = $request->hasQuery("status") ? $request->query("status") : null;
        if($status){
            $query = Order::where("status",$status)->orderBy("orders.created_at","desc");
        }else{
            $query = Order::orderBy("orders.created_at","desc");
        }
        if($request->has("keyword")){
            $keyword= $request->input("keyword");
            $query->where(function($query) use($keyword){
                $query->where("orders.id","like","%{$keyword}%")->orWhere("orders.name","like","%{$keyword}%")->orWhere("users.name","like","%{$keyword}%");
            });
        }
        $total_orders = $query->leftJoin("users","users.id","=","orders.user_id")->select(["orders.*","users.name"])->count(false);
        $orders = $query->limit($limit)->offset(($currentPage - 1) * $limit)->get();
        $number_map = array(
            "all" => DB::table("orders")->count(),
            "pending" => DB::table("orders")->where("status","pending")->count(),
            "toship" => DB::table("orders")->where("status","toship")->count(),
            "shipping" => DB::table("orders")->where("status","shipping")->count(),
            "completed" => DB::table("orders")->where("status","completed")->count(),
            "cancelled" => DB::table("orders")->where("status","cancelled")->count(),
            "returned" => DB::table("orders")->where("status","returned")->count()
        );
        $status_map = array(
            "pending" => "Chờ xác nhận",
            "toship" => "Chờ lấy hàng",
            "shipping" => "Đang vận chuyển",
            "completed" => "Hoàn thành",
            "cancelled" => "Đã hủy",
            "returned" => "Trả hàng"
        );
        return view("admin/order/index",["orders" => $orders,"total_orders"=>$total_orders,"currentPage"=>$currentPage,"totalPages"=>ceil($total_orders / $limit),"number_map"=>$number_map,"status_map"=>$status_map]);
    }

    public function order($id)
    {
        $order = Order::where("id",$id)->first();
        if(!$order){
            return;
        }
        $order->meta = DB::table("order_meta")->where("order_id",$order->id)->get();
        if(!empty($order->meta)){
            $order->meta = array_map(function($item){
                return ["$item->meta_key" => $item->meta_value];
            },$order->meta);
            $order->meta = array_merge(...$order->meta);
        }
        $file = new File();
        $order->items = array_map(function($item) use($file){
            if($item->p_color_id){
                $item->image = $file->dir("images/products/{$item->dir}/{$item->gallery_dir}")->files()[0];
            }else{
                $item->image = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
            }
            return $item;
        },DB::table("order_items as oi")->select(["oi.*","p.p_name","p.dir","pc.color_image","pc.color_name","pc.gallery_dir"])->join("products as p","p.id","=","oi.p_id")->leftJoin("product_colors as pc","pc.id","=","oi.p_color_id")->where("order_id",$id)->get());
        return view("admin/order/order",["order"=>$order]);
    }

    public function status(Request $request, $id)
    {
        if(!$request->has("status")){
            return response()->json("Yêu cầu không hợp lệ, vui lòng thử lại",400);
        }
        $status = $request->input("status");
        $order = Order::where("id",$id)->first();
        if($order){
            if($order->status === $status){
                return;
            }
            if($order->user_id){
                $current_point = DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->first();
                $current_spend = DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","total_spend")->first();
                $order_point = DB::table("order_meta")->where("order_id",$order->id)->where("meta_key","v_point")->first();
                $order_point_minus_history = DB::table("point_history")->where("user_id",$order->user_id)->where("order_id",$order->id)->where("action",0)->first();
                if($order_point && !$order_point_minus_history && $status !== "cancelled" && $status !== "returned"){
                    DB::table("point_history")->insert([
                        "user_id" => $order->user_id,
                        "order_id" => $order->id,
                        "order_total" => $order->total,
                        "point" => $order_point->meta_value,
                        "action" => 0
                    ]);
                    DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->limit(1)->update([
                        "meta_value" => intval($current_point->meta_value) - intval($order_point->meta_value)
                    ]);
                    $current_point->meta_value = intval($current_point->meta_value) - intval($order_point->meta_value);
                }
                $rank = DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","rank")->first()->meta_value;
                $coupon_amount = 0;
                $point = 0;
                $order_meta = DB::table("order_meta")->where("order_id",$order->id)->get();
                if(!empty($order_meta)){
                    $order_meta = array_map(function($item){
                        return ["$item->meta_key" => $item->meta_value];
                    },$order_meta);
                    $order_meta = array_merge(...$order_meta);
                    if(array_key_exists("coupon",$order_meta)){
                        $coupon = unserialize($order_meta['coupon']);
                        $coupon_amount = $coupon['coupon_amount'];
                    }
                    if(array_key_exists("v_point",$order_meta)){
                        $point = $order_meta["v_point"];
                    }                   
                }
                $plus_point = 0;
                $total_pay = $order->total - $coupon_amount - $point * 10000;
                if($total_pay < 0){
                    $total_pay = 0;
                }
                if($status === "completed"){
                    switch($rank){
                        case "member":
                            $plus_point = round($total_pay / 200000);
                            break;
                        case "silver":
                            $plus_point = round($total_pay / 175000);
                            break;   
                        case "gold":
                            $plus_point = round($total_pay / 150000);
                            break;
                        case "platinum":
                            $plus_point = round($total_pay / 125000);
                            break;   
                        case "diamond":
                            $plus_point = round($total_pay / 100000);
                            break;     
                    }
                    if($plus_point > 0){
                        DB::table("point_history")->insert([
                            "user_id" => $order->user_id,
                            "order_id" => $order->id,
                            "order_total" => $order->total,
                            "point" => $plus_point,
                            "action" => 1
                        ]);
                        DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->limit(1)->update([
                            "meta_value" => intval($current_point->meta_value) + $plus_point
                        ]);
                    }
                    $total_spend = intval($current_spend->meta_value) + $total_pay;
                    DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","rank")->update([
                        "meta_value" => $rank
                    ]);
                    DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","total_spend")->update([
                        "meta_value" => $total_spend
                    ]);
                    $mail = new Mail;
                    var_dump(json_decode($order->address,true));
                    return;
                    $mail->to($order->email)->subject("VNH - Hoàn thành đơn hàng")->template(VIEW_PATH . "/client/template/complete_order.php",array("name" => $order->name,"phone" => $order->phone,"payment_method"=> $order->payment_method,"order_id" => $order->id, "address" => json_decode($order->address,true)))->sendQueue();
                }else{
                    $total_spend = intval($current_spend->meta_value) - $total_pay;
                    $order_point_plus_history = DB::table("point_history")->where("user_id",$order->user_id)->where("order_id",$order->id)->where("action",1)->first();
                    if($order_point_plus_history){
                        DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->limit(1)->update([
                            "meta_value" => intval($current_point->meta_value) - intval($order_point_plus_history->point)
                        ]);
                        DB::table("point_history")->where("user_id",$order_point_plus_history->user_id)->where("order_id",$order_point_plus_history->order_id)->where("action",1)->limit(1)->delete();
                        $current_point->meta_value = intval($current_point->meta_value) - intval($order_point_plus_history->point);
                        switch (true) {
                            case $total_spend < 10000000:
                                $rank = "member";
                                break;
                            case $total_spend >= 10000000 && $total_spend < 20000000:
                                $rank = "silver";
                                break;
                            case $total_spend >= 20000000 && $total_spend < 35000000:
                                $rank = "gold";
                                break;
                            case $total_spend >= 35000000 && $total_spend < 50000000:
                                $rank = "platinum";
                            break;
                            case $total_spend > 50000000:
                                $rank = "diamond";
                        }
                        DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","rank")->update([
                            "meta_value" => $rank
                        ]);
                        DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","total_spend")->update([
                            "meta_value" => $total_spend
                        ]);
                    }   
                }
            }
            
            if($order->status !== "cancelled" && $order->status !== "returned" && ($status === "cancelled" || $status === "returned")){
                $order_items = DB::table("order_items")->where("order_id",$order->id)->get();
                foreach($order_items as $item){
                    if($item->p_color_id){
                        if($item->p_size){
                            DB::table("product_colors_sizes")->where("p_id",$item->p_id)->where("color_id",$item->p_color_id)->where("size",$item->p_size)->limit(1)->increase("stock",$item->quantity);
                        }else{
                            DB::table("product_colors")->where("p_id",$item->p_id)->where("id",$item->p_color_id)->limit(1)->increase("stock",$item->quantity);
                        }
                    }elseif($item->p_size){
                        DB::table("product_sizes")->where("p_id",$item->p_id)->where("size",$item->p_size)->limit(1)->increase("stock",$item->quantity);
                    }else{
                        DB::table("products")->where("id",$item->p_id)->limit(1)->increase("p_stock",$item->quantity);
                    }
                }
                $point_history = DB::table("point_history")->where("order_id",$order->id)->where("user_id",$order->user_id)->get();
                if(!empty($point_history)){
                    $current_point = DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->getValue("meta_value");
                    $current_spend = DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","total_spend")->first();
                    foreach($point_history as $item){
                        if($item->action == 0){
                            DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","point")->update([
                                "meta_value" => intval($current_point) + $item->point
                            ]);
                            $current_point = intval($current_point) + $item->point;
                            DB::table("point_history")->where("id",$item->id)->limit(1)->delete();
                        }
                    }
                    //------- update rank
                    $total_spend = intval($current_spend->meta_value) - $total_pay;
                    switch (true) {
                        case $total_spend < 10000000:
                            $rank = "member";
                            break;
                        case $total_spend >= 10000000 && $total_spend < 20000000:
                            $rank = "silver";
                            break;
                        case $total_spend >= 20000000 && $total_spend < 35000000:
                            $rank = "gold";
                            break;
                        case $total_spend >= 35000000 && $total_spend < 50000000:
                            $rank = "platinum";
                        break;
                        case $total_spend > 50000000:
                            $rank = "diamond";
                    }
                    DB::table("user_meta")->where("user_id",$order->user_id)->where("meta_key","rank")->update([
                        "meta_value" => $rank
                    ]);
                }
            }

            if(($order->status === "cancelled" || $order->status === "returned") && $status !== "cancelled" && $status !== "returned"){
                $order_items = DB::table("order_items")->where("order_id",$order->id)->get();
                foreach($order_items as $item){
                    if($item->p_color_id){
                        if($item->p_size){
                            DB::table("product_colors_sizes")->where("p_id",$item->p_id)->where("color_id",$item->p_color_id)->where("size",$item->p_size)->limit(1)->decrease("stock",$item->quantity);
                        }else{
                            DB::table("product_colors")->where("p_id",$item->p_id)->where("id",$item->p_color_id)->limit(1)->decrease("stock",$item->quantity);
                        }
                    }elseif($item->p_size){
                        DB::table("product_sizes")->where("p_id",$item->p_id)->where("size",$item->p_size)->limit(1)->decrease("stock",$item->quantity);
                    }else{
                        DB::table("products")->where("id",$item->p_id)->limit(1)->decrease("p_stock",$item->quantity);
                    }
                }
            }
        }else{
            return response()->json("Đơn hàng không tồn tại",400);
        }
        if($status !== "completed"){
            $order->paid_status = "Chưa thanh toán";
        }else{
            $order->paid_status = "Đã thanh toán";
        }
        $order->status = $status;
        $order->update();
        return response()->json("Trạng thái đơn hàng đã được chuyển sang \"{$request->input('status')}\"",200);
    }

    public function deleteOrder($id)
    {
        $order = Order::where("id",$id)->first();
        if($order->status === "cancelled"){
            $order->delete();
        }
        redirect_back();
    }
}