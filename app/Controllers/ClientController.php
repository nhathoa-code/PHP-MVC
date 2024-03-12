<?php

namespace App\Controllers;
use App\Models\Category;
use Mvc\Core\DB;
use App\Models\User;
use App\Models\Order;
use Mvc\Core\File;
use Mvc\Core\Request;
use Mvc\Core\Session;
use Mvc\Core\Auth;
use Mvc\Core\Mail;
use Mvc\Core\Validator;
use App\Controllers\CartController;

class ClientController {

    public function index()
    {
        $file = new File();
        $data = array();
        $data['categories'] = Category::whereNull("parent_id")->get();
        $data['latest_products'] = array_map(function($item) use($file){
        $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
        $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
        return $item;
        },DB::table("products")->orderBy("id","desc")->limit(8)->get());
        return view("client/index",$data);
    }

    public function collection(Request $request,$categories)
    {
        $file = new File();
        $limit = 10;
        if($request->isAjax()){
            $category_id = $request->input("category_id");
            if(!$category_id || !is_numeric($category_id)){
                return "Danh mục không tồn tại";
            }
            $page = $request->has("page") ? intval($request->input("page")) : 1;
            if($request->has("filter")){
                echo $this->filter($request,$page,$limit);
                exit;
            }
            $collection = array_map(function($item) use($file){
                $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
                $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                return $item;
            },DB::table("product_categories as pc")->select(["ps.*"])->join("products as ps","ps.id","=","pc.p_id")->where("cat_id",$category_id)->offset(($page - 1) * $limit)->limit($limit)->orderBy("created_at","desc")->get());
            ob_start();
            foreach($collection as $p){ ?>
                <div class="col">
                    <div class="collection-item">
                        <a href="<?php echo url("product/detail/{$p->id}") ?>">
                            <img loading="lazy" src="<?php echo $p->thumbnail ?>" width="188" height="188">
                            <div class="title"><?php echo $p->p_name ?></div>
                            <div class="price"><span class="num"><?php echo number_format($p->p_price,0,"",".") ?></span><span class="currency">Đ</span></div>
                        </a>
                        <ul class="colors">
                        <?php if(count($p->colors) <= 3): ?>
                        <?php foreach($p->colors as $color): ?>
                        <li>
                            <span>
                                <img src="<?php echo url($color->color_image) ?>" alt="">
                            </span>
                        </li>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <?php 
                                $break_index = 0;
                            ?>
                            <?php foreach($p->colors as $color): ?>
                            <li>
                            <span>
                                <img src="<?php echo url($color->color_image) ?>" alt="">
                            </span>
                            <?php if($break_index === 2): ?>
                                <span style="color:#666">+<?php echo count($p->colors) - 3; ?></span>
                            <?php endif; ?>    
                            </li>
                            <?php if($break_index === 2){break;} $break_index++; endforeach; ?>
                        <?php endif; ?>    
                        </ul>
                    </div>
                </div>          
        <?php  }
        $products = ob_get_clean();
        return response()->json(["res" => $products,"products" => count($collection)],200);
        }else{
            $cats_string = urldecode($categories);
            $slugs_arr = explode("/",$cats_string);
            $last_id = 0;
            foreach($slugs_arr as $index => $cat_slug){
                if($index === 0){
                    $cat = Category::whereNull("parent_id")->where("cat_slug",$cat_slug)->first();
                    if($cat){
                        $last_id = $cat->id;
                    }
                    $parent = $cat;
                }else{
                    $last_id = $this->findCategoryBySlug($last_id,$cat_slug)->id ?? null;
                }
                if($last_id){
                    $children = Category::where("parent_id",$last_id)->get();
                    if(!empty($children)){
                        $categories = $children;
                    }
                }else{
                    break;
                }
            }
            if(!$last_id){
                return "Danh mục không tồn tại";;
            }
            $query = DB::table("product_categories as pc")->select(["ps.*"])->join("products as ps","ps.id","=","pc.p_id")->where("cat_id",$last_id);
            $number_of_products = $query->count(false);
            $total_pages = ceil($number_of_products / $limit);
            $collection = array_map(function($item) use($file){
            $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
            $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
            return $item;
            },$query->limit($limit)->orderBy("created_at","desc")->get());
            $siblings = Category::where("parent_id",$parent->id)->get();
            $sizes_filter = DB::table("product_categories as pc")->select(["pcs.size"])->leftJoin("products as p","p.id","=","pc.p_id")->leftJoin("product_sizes as ps","ps.p_id","=","p.id")->leftJoin("product_colors_sizes as pcs","pcs.p_id","=","p.id")->where("cat_id",$last_id)->whereNotNull("pcs.size")->distinct()->get();
            return view("client/product/collection",["collection"=>$collection,"siblings"=>$siblings,"parent"=>$parent,"collection_url"=> url("collection/{$cats_string}"),"category_id"=>$last_id,"total_pages" => $total_pages,"number_of_products"=>$number_of_products,"limit"=>$limit,"displayed_products"=>count($collection),"sizes_filter"=>$sizes_filter]);
        }

    }

    public function findCategoryBySlug($parent_id,$slug)
    {
       return Category::where("cat_slug",$slug)->where("parent_id",$parent_id)->first() ?? null;
    }
    
    public function cart()
    {      
        $session = new Session();
        $cartController = new CartController();
        $data = array();
        if($session->has("cart")){
            $data['cart'] = $session->get("cart");
            $data['totalItems'] = $cartController->countTotalItems();
            $data['subtotal'] = $cartController->countSubtotal();
        }
        return view("client/cart",$data);
    }

    public function checkout()
    {
        $data = array();
        $session = new Session();
        if(!$session->has("cart") || count($session->get("cart")) <= 0){
            redirect("cart");
        }
        $cartController = new CartController();
        if($session->has("cart")){
            $data['cart'] = $session->get("cart");
            $data['totalItems'] = $cartController->countTotalItems();
            $data['subtotal'] = $cartController->countSubtotal();
        }
        if(isLoggedIn()){
            $data['addresses'] = DB::table("user_meta")->where("meta_key","address")->where("user_id",getUser()->id)->orderBy("id","desc")->get();
            $data['v_point'] = DB::table("user_meta")->where("meta_key","point")->where("user_id",getUser()->id)->getValue("meta_value");
        }
        return view("client/checkout",$data);
    }

    public function success()
    {
        if(!flash_session("success")){
            redirect("/");
        }
        return view("client/success");
    }

    public function orderTrack()
    {
        $data = array();
        $order_id = get_query("order_id");
        if($order_id){
           if(is_numeric($order_id) && strlen($order_id) === 10){
                $order = DB::table("orders")->where("id",$order_id)->first();
                if($order){
                    $status_map = array(
                        "pending" => "Chờ xác nhận",
                        "toship" => "Chờ lấy hàng",
                        "shipping" => "Đang vận chuyển",
                        "completed" => "Hoàn thành",
                        "cancelled" => "Đã hủy"
                    );
                    $data['status_map'] = $status_map;
                    $file = new File();
                    $data['order_meta'] = array();
                    $data['order'] = $order;
                    $data['order_items'] = array_map(function($item) use($file){
                        if($item->p_color_id){
                            $item->p_image = $file->dir("images/products/{$item->dir}/{$item->gallery_dir}")->files()[0];
                        }else{
                            $item->p_image = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                        }
                        return $item;
                    },DB::table("order_items as oi")->select(["oi.*","pc.color_image","pc.color_name","pc.gallery_dir","p.dir","p.p_name"])->leftJoin("product_colors as pc","pc.id","=","oi.p_color_id")->join("products as p","p.id","=","oi.p_id")->where("order_id",$order->id)->get());
                    $order_meta = DB::table("order_meta")->where("order_id",$order->id)->get();
                    if(!empty($order_meta)){
                        $order_meta = array_map(function($item){
                            return [$item->meta_key => $item->meta_value];
                        },$order_meta);
                        $data['order_meta'] = array_merge(...$order_meta);
                    }
                }else{
                    $data['not_found'] = true;
                }
           }else{
                $data["message"] = "Mã đơn hàng không hợp lệ";
           }
        }
        return view("client/order_track",$data);
    }

    public function resetSuccess()
    {
        if(!flash_session("success")){
            redirect("/");
        }
        return view("client/reset_success");
    }

    public function authLoginView()
    {
        return view("client/auth/login");
    }

    public function authLogin(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("login_key",["required" => "Vui lòng nhập email","email"=>"email không đúng định dạng"],true);
        $validator->validate("password",["required" => "Vui lòng nhập mật khẩu"]);
        if($validator->fails()){
            $validator->flashErrors();
            redirect_back();
        }
        $auth = new Auth();
        $login_key = $request->input("login_key");
        $password = $request->input("password");
        if($auth->login($login_key,$password)){
            redirect_to($request->input("redirect_to"));
        }else{
            message(["message" => "Username hoặc mật khẩu không đúng"]);
            redirect_back();
        }
       
    }

    public function authLogout()
    {
        $auth = new Auth();
        $role = $auth->getUser()->role;
        $auth->logout();
        if($role !== "user"){
            redirect("admin/login");
        }else{
            redirect("auth/login");
        }
    }

    public function authRegistryView()
    {
        return view("client/auth/registry");
    }

    public function authRegistry(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("name",["required" => "Vui lòng nhập họ tên","min:3"=>"Độ dài ký tự không được ít hơn 5"],true);
        $validator->validate("email",["required" => "Vui lòng nhập email","email"=>"Email không đúng định dạng","unique:users"=>"Email này đã được đăng ký!"],true);
        $validator->validate("password",["required" => "Vui lòng nhập mật khẩu","min:6"=>"Tối thiểu 6 ký tự"],true);
        if($validator->fails()){
            $validator->flashErrors();
            $validator->flashInputs();
            redirect_back();
        }
        $user_id = User::insert([
            "email" => $request->input("email"),
            "name" => $request->input("name"),
            "login_key" => $request->input("email"),
            "password" => password_hash($request->input("password"),PASSWORD_DEFAULT),
        ]);
        $verify_token = generateToken();
        DB::table("user_meta")->insert([
            "user_id" => $user_id,
            "meta_key" => "verify_token",
            "meta_value" => $verify_token,
        ]);
        $mail = new Mail();
        $email = $request->input("email");
        $href = url("auth/email/verify?token={$verify_token}");
        $message = "<a href='{$href}'>Vui lòng click vào đây để xác thực tài khoản</a>";
        $mail->to($email)->subject("Xác thực tài khoản")->message($message)->sendQueue();
        message(["message"=>"Đăng ký thành công, vui lòng xác thực tài khoản qua email","type"=>"success"]);
        redirect_back();
    }

    public function authVerify()
    {
        $token = get_query("token");
        if($token){
            $user = DB::table("user_meta")->where("meta_key","verify_token")->where("meta_value",$token)->first();
            if($user){
                User::where("id",$user->user_id)->limit(1)->update([
                    "email_verified_at"=> now()
                ]);
                DB::table("user_meta")->where("meta_key","verify_token")->where("user_id",$user->user_id)->limit(1)->delete();
                DB::table("user_meta")->insert([
                    "user_id" => $user->user_id,
                    "meta_key" => "point",
                    "meta_value" => 0
                ]);
                DB::table("user_meta")->insert([
                    "user_id" => $user->user_id,
                    "meta_key" => "total_spend",
                    "meta_value" => 0
                ]);
                DB::table("user_meta")->insert([
                    "user_id" => $user->user_id,
                    "meta_key" => "rank",
                    "meta_value" => "member"
                ]);
                return "Email verified successfully";
            }
        }
        echo "invalid token";
    }

    public function user($part)
    {
        $data = array();
        switch($part){
            case "member":
                $data['user_rank'] = DB::table("user_meta")->select(['meta_value'])->where("meta_key","rank")->where("user_id",getUser()->id)->first();
                $data['user_point'] = DB::table("user_meta")->select(['meta_value'])->where("meta_key","point")->where("user_id",getUser()->id)->first();
                $data['user_total_spend'] = DB::table("user_meta")->select(['meta_value'])->where("meta_key","total_spend")->where("user_id",getUser()->id)->first();
                $data['point_history'] = DB::table("point_history")->where("user_id",getUser()->id)->get();
                break;
            case "profile":
                $data['profile'] = DB::table("user_meta")->select(['meta_value'])->where("meta_key","profile")->where("user_id",getUser()->id)->first();
                break;
            case "addresses":
                $data['addresses'] = DB::table("user_meta")->where("meta_key","address")->where("user_id",getUser()->id)->orderBy("id","desc")->get();
                break;
            case "address/edit":
                if(get_query("id")){
                    $address = DB::table("user_meta")->where("user_id",getUser()->id)->where("id",get_query("id"))->where("meta_key","address")->first();
                    if(!$address){
                        redirect("user/addresses");
                    }
                    $data['address'] = unserialize($address->meta_value);
                    $data['id'] = $address->id;
                }else{
                    redirect("user/addresses");
                }
                break;
            case "orders":
                $status_map = array(
                    "pending" => "Chờ xác nhận",
                    "toship" => "Chờ lấy hàng",
                    "shipping" => "Đang vận chuyển",
                    "completed" => "Hoàn thành",
                    "cancelled" => "Đã hủy",
                    "returned" => "Trả hàng"
                );
                $number_map = array(
                    "all" => DB::table("orders")->count(),
                    "pending" => DB::table("orders")->where("user_id",getUser()->id)->where("status","pending")->count(),
                    "toship" => DB::table("orders")->where("user_id",getUser()->id)->where("status","toship")->count(),
                    "shipping" => DB::table("orders")->where("user_id",getUser()->id)->where("status","shipping")->count(),
                    "completed" => DB::table("orders")->where("user_id",getUser()->id)->where("status","completed")->count(),
                    "cancelled" => DB::table("orders")->where("user_id",getUser()->id)->where("status","cancelled")->count(),
                    "returned" => DB::table("orders")->where("user_id",getUser()->id)->where("status","returned")->count()
                );
                $data['status_map'] = $status_map;
                $data['number_map'] = $number_map;
                $status = get_query("status");
                if($status){
                    $data['status'] = $status;
                    $data['orders'] = DB::table("orders")->where("user_id",getUser()->id)->where('status',$status)->orderBy("created_at","desc")->get();
                }else{
                    $data['orders'] = DB::table("orders")->where("user_id",getUser()->id)->orderBy("created_at","desc")->get();
                }
                break;
            case "order":
                $status_map = array(
                    "pending" => "Chờ xác nhận",
                    "toship" => "Chờ lấy hàng",
                    "shipping" => "Đang vận chuyển",
                    "completed" => "Hoàn thành",
                    "cancelled" => "Đã hủy"
                );
                $data['status_map'] = $status_map;
                if(get_query("id")){
                    $order = DB::table("orders")->where("id",get_query("id"))->where("user_id",getUser()->id)->first();
                    if($order){
                        $file = new File();
                        $data['order_meta'] = array();
                        $data['order'] = $order;
                        $data['order_items'] = array_map(function($item) use($file){
                            if($item->p_color_id){
                                $item->p_image = $file->dir("images/products/{$item->dir}/{$item->gallery_dir}")->files()[0];
                            }else{
                                $item->p_image = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                            }
                            return $item;
                        },DB::table("order_items as oi")->select(["oi.*","pc.color_image","pc.color_name","pc.gallery_dir","p.dir","p.p_name"])->leftJoin("product_colors as pc","pc.id","=","oi.p_color_id")->join("products as p","p.id","=","oi.p_id")->where("order_id",$order->id)->get());
                        $order_meta = DB::table("order_meta")->where("order_id",$order->id)->get();
                        if(!empty($order_meta)){
                            $order_meta = array_map(function($item){
                                return [$item->meta_key => $item->meta_value];
                            },$order_meta);
                            $data['order_meta'] = array_merge(...$order_meta);
                        }
                    }
                }
                break;
            case "wishlist":
                $file = new File;
                $products = array_map(function($item) use($file){
                    $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                    return $item;
                },DB::table("wish_list as wl")->join("products as p","p.id","=","wl.p_id")->where("user_id",getUser()->id)->get());
                $data['products'] = $products;
                break;
            case "coupons":
                $data['coupons'] = DB::table("coupons")->get();
                break;
        }
        return view("client/auth/user",["part" => $part,"data" => $data]);
    }

    public function applyCoupon(Request $request)
    {
        $cartController = new CartController();
        $coupon_code = $request->input("coupon_code");
        $coupon = DB::table("coupons")->where("code",$coupon_code)->first();
        if($coupon){
            if(!isLoggedIn()){
                return response()->json(["message" => "Đăng nhập để có thể áp dụng mã giảm giá"],400);
            }else{
               $user_usage = DB::table("coupon_usage")->where("user_id",getUser()->id)->get();
               if(count($user_usage) >= $coupon->per_user){
                    return response()->json(["message" => "Người mua chỉ có thể dùng mã \"{$coupon->code}\" {$coupon->per_user} lần"],400);
                }
            }
            $subtotal = $cartController->countSubtotal();
            $current_time = time();
            if($subtotal < $coupon->minimum_spend)
            {
                return response()->json(["message" => "Giá trị đơn hàng không hợp lệ!"],400);
            }
            if(strtotime($coupon->start_time) > $current_time || $current_time > strtotime($coupon->end_time))
            {
                return response()->json(["message" => "Mã đã hết hạn sử dụng!"],400);
            }
            if($coupon->coupon_usage <= $coupon->coupon_used)
            {
                return response()->json(["message" => "Mã đã hết lượt sử dụng!"],400);
            }
            return response()->json(["message"=>"Áp dụng mã giảm giá thành công","amount" => $coupon->amount,"coupon_code" => $coupon->code],200);
        }else{
            return response()->json(["message" => "Mã không tồn tại!"],400);
        }
    }

    public function applyPoint(Request $request)
    {
        $point = $request->input("point");
        if(!isLoggedIn()){
            return response()->json(["message" => "vui lòng đăng nhập trước"],400);
        }
        if(!is_numeric($point) || intval($point) <= 0){
            return response()->json(["message" => "point không hợp lệ"],400);
        }
        $point_from_db = DB::table("user_meta")->select(['meta_value'])->where("user_id",getUser()->id)->where("meta_key","point")->first();
        if($point_from_db){
            $point_from_db = intval($point_from_db->meta_value);
            if($point > $point_from_db){
               return response()->json(["message" => "Số dư không khả dụng!"],400);
            }
            return response()->json(["point" => $point,"message"=>"Áp dụng v-point thành công"],200);
        }
    }

    public function search()
    {
        $keyword = get_query("keyword");
        $request = request();
        $limit = 10;
        if($keyword){
            $file = new File();
            if($request->isAjax()){
                $page = $request->has("page") ? intval($request->input("page")) : 1;
                $collection = array_map(function($item) use($file){
                    $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
                    $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                    return $item;
                },DB::table("products")->where("p_name","like","%{$keyword}%")->offset(($page - 1) * $limit)->limit($limit)->get());
                ob_start();
                foreach($collection as $p){ ?>
                    <div class="col">
                        <div class="collection-item">
                            <a href="<?php echo url("product/detail/{$p->id}") ?>">
                                <img loading="lazy" src="<?php echo $p->thumbnail ?>" width="188" height="188">
                                <div class="title"><?php echo $p->p_name ?></div>
                                <div class="price"><span class="num">392.000</span><span class="currency">VND</span></div>
                            </a>
                            <ul class="colors">
                                <?php if(count($p->colors) <= 3): ?>
                                <?php foreach($p->colors as $color): ?>
                                <li>
                                    <span>
                                        <img src="<?php echo url($color->color_image) ?>" alt="">
                                    </span>
                                </li>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <?php 
                                        $break_index = 0;
                                    ?>
                                    <?php foreach($p->colors as $color): ?>
                                    <li>
                                    <span>
                                        <img src="<?php echo url($color->color_image) ?>" alt="">
                                    </span>
                                    <?php if($break_index === 2): ?>
                                        <span style="color:#666">+<?php echo count($p->colors) - 3; ?></span>
                                    <?php endif; ?>    
                                    </li>
                                    <?php if($break_index === 2){break;} $break_index++; endforeach; ?>
                                <?php endif; ?>    
                            </ul>
                        </div>
                    </div>          
            <?php  }
            $products = ob_get_clean();
            return response()->json(["res" => $products,"products" => count($collection)],200);
            }else{
                $number_of_products = DB::table("products")->where("p_name","like","%{$keyword}%")->count();
                $total_pages = ceil($number_of_products / $limit);
                $products = array_map(function($item) use($file){
                    $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
                    $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
                    return $item;
                },DB::table("products")->where("p_name","like","%{$keyword}%")->limit($limit)->get());
                return view("client/search",["products" => $products,"count" => DB::table("products")->where("p_name","like","%{$keyword}%")->count(),"total_pages" => $total_pages,"number_of_products" => $number_of_products,"limit" => $limit,"displayed_products" => count($products)]);
            }
        }
    }

    public function filter(Request $request,$page = 1,$limit = 10)
    {
        if(!$request->has("category_id") || !is_numeric($request->input("category_id"))){
            return;
        }
        $category_id = $request->input("category_id");
        $query = DB::table("product_categories as pc")->join("products as p","p.id","=","pc.p_id")->leftJoin("product_colors as pcl","pcl.p_id","=","pc.p_id")->leftJoin("product_sizes as ps","ps.p_id","=","pc.p_id")->leftJoin("product_colors_sizes as pcs","pcs.p_id","=","pc.p_id")->leftJoin("product_colors as pc1","pc1.id","=","pcs.color_id")->select(['p.*'])->where("pc.cat_id",$category_id);

        if($request->has("colors")){
            $colors = $request->input("colors");
            $query->where(function($query) use($colors){
                foreach($colors as $index => $color){
                    if($index === 0){
                        $query->where("pcl.color_name","like","%{$color}%");
                    }else{
                        $query->orWhere("pcl.color_name","like","%{$color}%");
                    }
                    
                }
            });
        }

        if($request->has("sizes")){
            $sizes = $request->input("sizes");
            $query->where(function($query) use ($sizes){
                foreach($sizes as $index => $size){
                    if(count($sizes) === 1){
                        $query->where(function($query) use($size){
                            $query->where("ps.size","like","%{$size}%")
                                ->where("ps.stock",">",0);
                        });
                        $query->orWhere(function($query) use($size){
                             $query->where("pcs.size","like","%{$size}%")
                                ->where("pcs.stock",">",0);
                        });
                    }else{
                        $query->orWhere(function($query) use ($size){
                            $query->where(function($query) use($size){
                                $query->where("ps.size","like","%{$size}%")
                                    ->where("ps.stock",">",0);
                            });
                            $query->orWhere(function($query) use($size){
                                $query->where("pcs.size","like","%{$size}%")
                                    ->where("pcs.stock",">",0);
                            });
                        });        
                    }        
                }
            });
        }

        if($request->has("prices")){
            $prices = $request->input("prices");
            $query->where(function($query) use ($prices){
                foreach($prices as $index => $price){
                    $query->orWhere(function($query) use($price){
                        $min = explode("-",$price)[0];
                        $max = explode("-",$price)[1];
                        $query->where(function($query) use ($min,$max){
                                $query->where("p.p_price",">=",$min)
                                    ->Where("p.p_price","<=",$max)
                                    ->where("p.p_price",">",0);
                        });
                        $query->orWhere(function($query) use($min,$max){
                            $query->where("pcl.price",">=",$min)
                                    ->Where("pcl.price","<=",$max)
                                    ->where("pcl.price",">",0);
                        });         
                        $query->orWhere(function($query) use($min,$max){
                            $query->where("ps.price",">=",$min)
                                    ->Where("ps.price","<=",$max)
                                    ->where("ps.price",">",0);
                        }); 
                        $query->orWhere(function($query) use($min,$max){
                            $query->where("pcs.price",">=",$min)
                                    ->Where("pcs.price","<=",$max)
                                    ->Where("pcs.price",">",0);
                        });                  
                    });    
                }
            });
        }
        $number_of_products = count($query->distinct()->get(false));
        $collection = $query->distinct()->limit($limit)->offset(($page - 1) * $limit)->orderBy("p.created_at","desc")->get();
        if(!empty($collection)){
            $file = new File();
            $collection = array_map(function($item) use($file){
            $item->colors = DB::table("product_colors")->where("p_id",$item->id)->get();
            $item->thumbnail = $file->dir("images/products/{$item->dir}/product_images")->files()[0];
            return $item;
            },$collection);
            ob_start();
            foreach($collection as $p){ ?>
                <div class="col">
                    <div class="collection-item">
                        <a href="<?php echo url("product/detail/{$p->id}") ?>">
                            <img loading="lazy" src="<?php echo $p->thumbnail ?>" width="188" height="188">
                            <div class="title"><?php echo $p->p_name ?></div>
                            <div class="price"><span class="num"><?php echo number_format($p->p_price,0,"",".") ?></span><span class="currency">Đ</span></div>
                        </a>
                        <ul class="colors">
                            <?php if(count($p->colors) <= 3): ?>
                            <?php foreach($p->colors as $color): ?>
                            <li>
                                <span>
                                    <img src="<?php echo url($color->color_image) ?>" alt="">
                                </span>
                            </li>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <?php 
                                    $break_index = 0;
                                ?>
                                <?php foreach($p->colors as $color): ?>
                                <li>
                                <span>
                                    <img src="<?php echo url($color->color_image) ?>" alt="">
                                </span>
                                <?php if($break_index === 2): ?>
                                    <span style="color:#666">+<?php echo count($p->colors) - 3; ?></span>
                                <?php endif; ?>    
                                </li>
                                <?php if($break_index === 2){break;} $break_index++; endforeach; ?>
                            <?php endif; ?>    
                            </ul>
                    </div>
                </div>          
            <?php  }
            $products = ob_get_clean();
            $total_pages = ceil($number_of_products / $limit);
            return response()->json(["res" => $products,"products" => count($collection),"number_of_products"=>$number_of_products,"total_pages"=>$total_pages],200);  
        }else{
            return response()->json(["res"=>[],"products" => 0],200);
        }
    }

    public function forgotPassword()
    {
        return view("client/auth/forgot");
    }

    public function retrievePassword(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate("email",["required" => "Vui lòng nhập email","email"=>"Email không hợp lệ"],true);
        if($validator->fails()){
            $validator->flashErrors();
            $validator->flashInputs();
            redirect_back();
        }
        $email = $request->input("email");
        if(!DB::table("users")->where("email",$email)->first()){
            $validator->flashInputs();
            message(["message"=>"Email không tồn tại!","type"=>"error"]);
            redirect_back();
        }
        $verify_token = generateToken();
        DB::table("password_resets")->insert([
            "email" => $email,
            "token" => $verify_token
        ]);
        $mail = new Mail();
        $href = url("auth/resetpassword?token={$verify_token}");
        $message = "<a href='{$href}'>Vui lòng click vào đây để lấy lại mật khẩu</a>";
        $mail->to($email)->subject("Lấy lại mật khẩu")->message($message)->sendQueue();
        message(["message"=>"Link lấy lại mật khẩu đã được gửi đến email","type"=>"success"]);
        redirect_back();
    }

    public function resetPasswordView()
    {
        $token = get_query("token");
        if(!$token){
            return;
        }
        if(!DB::table("password_resets")->where("token",$token)->first()){
            return;
        }
        return view("client/auth/reset");
    }

    public function resetPassword()
    {
        $request = request();
        $token = $request->input("token");
        if(!$token){
            return;
        }
        $email_token = DB::table("password_resets")->where("token",$token)->first();
        if(!$email_token){
            return;
        }
        $validator = new Validator($request->all());
        $validator->validate("password",["required" => "Vui lòng nhập mật khẩu","min:6"=>"Độ dài ký tự không nhỏ hỏn 6"],true);
        $validator->validate("retype_password",["required"=>"Vui lòng nhập lại mật khẩu","match:password"=>"Nhập lại mật khẩu không đúng"],true);
        if($validator->fails()){
            $validator->flashErrors();
            redirect_back();
        }
        DB::table("users")->where("email",$email_token->email)->limit(1)->update([
            'password' => password_hash($request->input("password"),PASSWORD_DEFAULT)
        ]);
        DB::table("password_resets")->where("email",$email_token->email)->where("token",$email_token->token)->limit(1)->delete();
        flash_session("success",true);
        redirect("reset_success");
    }

    public function vnpayConfirm(Request $request)
    {
        $vnp_HashSecret = "HOBQFVGFSTRKIMJOTRJRIBQIZOUVTBWI";
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $order_id = $request->query("vnp_TxnRef");
                $order = Order::where("id",$order_id)->first();
                if($order->paid_status === "Đã thanh toán | MGD: " . $request->query("vnp_TransactionNo")){
                    redirect("/");
                }
                $order->paid_status = "Đã thanh toán | MGD: " . $request->query("vnp_TransactionNo");
                $order->update();
                flash_session("success",true);
                redirect("success",['order_id' => $order_id]);
            }
        } 
    }
}
