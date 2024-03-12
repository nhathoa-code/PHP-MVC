<?php

namespace App\Controllers;
use Mvc\Core\DB;
use Mvc\Core\Mail;
use Mvc\Core\Session;
use Mvc\Core\Request;
use Mvc\Core\Validator;

class CartController {

    public function add(Request $request)
    {
        $session = new Session();
        $validator = new Validator($request->all());
        $validator->validate("price",["required","number"]);
        $validator->validate("p_id",["required"]);
        $validator->validate("p_name",["required"]);
        $validator->validate("p_image",["required"]);
        if($validator->fails()){
            return response()->json(["message"=>"Yêu cầu không hợp lệ"],400);
        }
        $product_id = $request->input("p_id");
        $color_id = $request->input("color_id");
        $size = $request->input("size");
        $matchIndex = -1;
        if($session->has("cart")){
            foreach ($session->get("cart") as $index => $cartItem) {
                if($request->has("color_id")){
                    if($request->has("size")){
                        if (
                        $cartItem['p_id'] == $product_id &&
                        $cartItem['color_id'] == $color_id &&
                        $cartItem['size'] == $size
                        ) {
                            $matchIndex = $index;
                            break;
                        }
                    }else{
                        if (
                        $cartItem['p_id'] == $product_id &&
                        $cartItem['color_id'] == $color_id
                        ) {
                            $matchIndex = $index;
                            break;
                        }
                    }
                }elseif($request->has("size")){
                    if (
                    $cartItem['p_id'] == $product_id &&
                    $cartItem['size'] == $size
                    ) {
                        $matchIndex = $index;
                        break;
                    }
                }else{
                    if (
                    $cartItem['p_id'] == $product_id
                    ) {
                        $matchIndex = $index;
                        break;
                    }
                }
            }
            if ($matchIndex !== -1) {
                $this->checkStock($product_id,$request->input("p_name"),$request->input("color_name"),$color_id,$size,$session->get("cart=>{$matchIndex}=>quantity") + 1);
                $session->set("cart=>{$matchIndex}=>quantity",$session->get("cart=>{$matchIndex}=>quantity") + 1);
            } else {
                $this->checkStock($product_id,$request->input("p_name"),$request->input("color_name"),$color_id,$size,1);
                $uniqid = uniqid();
                $data = array(
                    "p_id" => $product_id,
                    "p_name" => $request->input("p_name"),
                    "p_image" => $request->input("p_image"),
                    "quantity" => 1,
                    "price" => intval($request->input("price"))
                );
                if($request->has("size")){
                    $data['size'] = $request->input("size");
                }
                if($request->has("color_id")){
                    $data['color_id'] = $request->input("color_id");
                    $data['color_name'] = $request->input("color_name");
                    $data['color_image'] = $request->input("color_image");
                }
                $session->push("cart",$data,$uniqid);
            }
        }else{
            $this->checkStock($product_id,$request->input("p_name"),$request->input("color_name"),$color_id,$size,$session->get("cart=>{$matchIndex}=>quantity") + 1);
            $uniqid = uniqid();
            $session->push("cart",array(
                "p_id" => $product_id,
                "p_name" => $request->input("p_name"),
                "color_id" => $request->input("color_id"),
                "color_name" => $request->input("color_name"),
                "color_image" => $request->input("color_image"),
                "p_image" => $request->input("p_image"),
                "size" => $request->input("size"),
                "quantity" => 1,
                "price" => intval($request->input("price"))
            ),$uniqid);
        }
        if($request->has("buy_now")){
            return response()->json(["redirect" => url("checkout")],200);
        }
        $data = array();
        if($matchIndex === -1){
        ob_start(); 
        ?>
            <div class="row mini-cart-item mx-0 pb-3 cart-item-<?php echo $uniqid; ?>" style="font-size: 14px;">
                <div class="col-4 ps-0">
                    <img src="<?php echo $request->input("p_image") ?>" alt="">
                </div>
                <div class="col-8 ps-0">
                    <div class="name"><?php echo $request->input("p_name") ?></div>
                    <div class="variation d-flex">
                        <?php if($request->has("color_id")): ?>
                        <div class="color d-flex align-items-center">
                            <img src="<?php echo url($request->input("color_image")) ?>" alt="">
                            <span class="ms-1"><?php echo $request->input("color_name") ?></span>
                        </div>
                        <div class="vr mx-1 mx-1"></div>
                        <?php endif; ?>
                        <?php if($request->has("size")): ?>   
                        <div class="size"><?php echo $request->input("size") ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="price">Giá: <span style="font-weight:600"><?php echo number_format(intval($request->input("price")),0,"",".") ?></span></div>
                    <div class="quantity">Số lượng: <span style="font-weight:600"><?php echo 1 ?></span></div>
                </div>
            </div>
        <?php
        $item = ob_get_clean();
        $data['item'] = $item;
        }else{
            $data['index'] = $matchIndex;
            $data['quantity'] = $session->get("cart=>{$matchIndex}=>quantity");
        }
        $cartController = new CartController();
        $data['totalItems'] = $cartController->countTotalItems();
        $data['subtotal'] = $cartController->countSubtotal();
        return response()->json($data,200);
    }

    protected function checkStock($product_id,$product_name,$color_name,$color_id,$size,$quantity)
    {
        if($color_id){
            $message = "";
            if($size){
                $stock = DB::table("product_colors_sizes")->select(['stock'])->where("p_id",$product_id)->where("color_id",$color_id)->where("size",$size)->getValue("stock");
            }else{
                $stock = DB::table("product_colors")->select(['stock'])->where("p_id",$product_id)->where("id",$color_id)->getValue("stock");
            }
            if($stock === null){
                echo response()->json(["message"=>"Phân loại sản phẩm không đúng!"],400);
                exit;
            }
            if($quantity > $stock){
                $message .= "Sản phẩm {$product_name} - {$color_name}";
                if($size){
                    $message .= " | {$size}";
                }
                if($stock > 0){                   
                    $message .= " chỉ còn {$stock} sản phẩm trong kho!";                  
                }else{
                    $message .= " đã hết hàng!";
                }
                echo response()->json(["message" => $message],400);
                exit;
            }              
        }elseif($size){
            $stock = DB::table("product_sizes")->select(['stock'])->where("p_id",$product_id)->where("size",$size)->getValue("stock");
            if($stock === null){
                echo response()->json(["message"=>"Phân loại sản phẩm không đúng!"],400);
                exit;
            }
            if($quantity > $stock){
                if($stock > 0){
                    echo response()->json(["message" => "Sản phẩm {$product_name} - {$size} chỉ còn {$stock} sản phẩm trong kho!"],400);
                    exit;
                }else{
                    echo response()->json(["message" => "Sản phẩm {$product_name} - {$size} đã hết hàng!"],400);
                    exit;
                }
            }             
        }else{
            $stock = DB::table("products")->select(['p_stock'])->where("id",$product_id)->getValue("p_stock");
            if($stock === null){
                echo response()->json(["message"=>"Sản phẩm không tồn tại!"],400);
                exit;
            }
            if($quantity > $stock){
                if($stock > 0){
                    echo response()->json(["message" => "Sản phẩm {$product_name} chỉ còn {$stock} sản phẩm trong kho!"],400);
                    exit;
                }else{
                    echo response()->json(["message" => "Sản phẩm {$product_name} đã hết hàng!"],400);
                    exit;
                }
            }           
        }
    }

    public function delete(Request $request)
    {
        $session = new Session();
        $cartController = new CartController();
        $index = $request->input("index");
        $session->remove("cart=>{$index}");
        $data = array();
        if($session->has("cart")){
            $data['totalItems'] = $cartController->countTotalItems();
            $data['subtotal'] = $cartController->countSubtotal();
            if($cartController->countSubtotal() === 0){
                $data['home_url'] = url("/");
            }
            $data['index'] = $index;
        }
        return response()->json($data,200);
    }

    public function updateQty(Request $request)
    {
        $session = new Session();
        $cartController = new CartController();
        $sign = $request->input("sign");
        $index = $request->input("index");
        if(!$index || !$session->get("cart=>{$index}")){
            return;
        }
        $data = array(
            "index" => $index
        );
        $product_id = $session->get("cart=>{$index}=>p_id");
        $product_name = $session->get("cart=>{$index}=>p_name");
        $color_id = $session->get("cart=>{$index}=>color_id");
        $color_name = $session->get("cart=>{$index}=>color_name");
        $size = $session->get("cart=>{$index}=>size");
        switch($sign){
            case "+":
                $this->checkStock($product_id,$product_name,$color_name,$color_id,$size,$session->get("cart=>{$index}=>quantity") + 1);
                $session->set("cart=>{$index}=>quantity",$session->get("cart=>{$index}=>quantity") + 1);
                break;
            case "-":
                if($session->get("cart=>{$index}=>quantity") - 1 <= 0){
                    $session->remove("cart=>{$index}");
                }else{
                    $session->set("cart=>{$index}=>quantity",$session->get("cart=>{$index}=>quantity") - 1);
                }
                break;    
        }
        $data['quantity'] = $session->get("cart=>{$index}=>quantity");
        $data['totalItems'] = $cartController->countTotalItems();
        $data['subtotal'] = $cartController->countSubtotal();
        if($cartController->countSubtotal() === 0){
            $data['home_url'] = url("/abc");
        }
        $data['price'] = $session->get("cart=>{$index}=>price");
        return response()->json($data,200);

    }

    public function countTotalItems()
    {
        $session = new Session();
        if($session->has("cart")){
            $totalItems = 0;
            foreach($session->get("cart") as $item){
                $totalItems += $item['quantity'];
            }
            return $totalItems;
        }else{
            return 0;
        }
    }

    public function countSubtotal()
    {
        $session = new Session();
        if($session->has("cart")){
            $subtotal = 0;
            foreach($session->get("cart") as $item){
                $subtotal += $item['quantity'] * $item['price'];
            }
            return $subtotal;
        }else{
            return 0;
        }
    }

    public function checkout(Request $request)
    {
        $session = new Session();
        if(!$session->has("cart") || empty($session->get("cart"))){
            redirect("cart");
        }
        $validator = new Validator($request->all());
        $validator->validate("name",["required"]);
        $validator->validate("email",["required","email"],true);
        $validator->validate("phone",["required","pattern:/^0[0-9]{9}$/"=>"Số điện thoại nhập không đúng định dạng"],true);
        $validator->validate("province",["required"]);
        $validator->validate("district",["required"]);
        $validator->validate("ward",["required"]);
        $validator->validate("address",["required"]);
        $validator->validate("payment_method",["required","values:cod,vnpay"],true);
        $validator->validate("shipping_fee",["required","number"],true);
        if($validator->fails()){
            message(["message"=>"Yêu cầu không hợp lệ, vui lòng kiểm tra kỹ các trường thông tin, nhập đúng định dạng số điện thoại và email","status"=>"warning"]);
            redirect_back();
        }
        foreach($session->get("cart") as $index => $item){
            if(isset($item['color_id'])){
                $message = "";
                if(isset($item['size'])){
                    $stock = DB::table("product_colors_sizes")->select(['stock'])->where("p_id",$item['p_id'])->where("color_id",$item['color_id'])->where("size",$item['size'])->getValue("stock");
                }else{
                    $stock = DB::table("product_colors")->select(['stock'])->where("p_id",$item['p_id'])->where("id",$item['color_id'])->getValue("stock");
                }
                if($stock === null){
                    message(["message"=>"Phân loại sản phẩm không đúng!"]);
                    redirect_back();
                }
                if($item['quantity'] > $stock){
                    $message .= "Sản phẩm {$item['p_name']} - {$item['color_name']}";
                    if(isset($item['size'])){
                        $message .= " | {$item['size']}";
                    }
                    if($stock > 0){
                        $message .= " chỉ còn {$stock} sản phẩm trong kho!";
                    }else{
                        $message .= " đã hết hàng!";
                    }
                    message(["message" => $message]);
                    redirect_back();
                }              
            }elseif(isset($item['size'])){
                $stock = DB::table("product_sizes")->select(['stock'])->where("p_id",$item['p_id'])->where("size",$item['size'])->getValue("stock");
                if($stock === null){
                    message(["message"=>"Phân loại sản phẩm không đúng!"]);
                    redirect_back();
                }
                if($item['quantity'] > $stock){
                    if($stock > 0){
                        message(["message"=>"Sản phẩm {$item['p_name']} | {$item['size']} chỉ còn {$stock} sản phẩm trong kho!"]);
                    }else{
                        message(["message"=>"Sản phẩm {$item['p_name']} | {$item['size']} đã hết hàng!"]);
                    }
                    redirect_back();
                }             
            }else{
                $stock = DB::table("products")->select(['p_stock'])->where("id",$item['p_id'])->getValue("p_stock");
                if($stock === null){
                    message(["message"=>"Sản phẩm không tồn tại!"]);
                    redirect_back();
                }
                if($item['quantity'] > $stock){
                    if($stock > 0){
                        message(["message"=>"Sản phẩm {$item['p_name']} chỉ còn {$stock} sản phẩm trong kho!"]);
                    }else{
                        message(["message"=>"Sản phẩm {$item['p_name']} đã hết hàng!"]);
                    }
                    redirect_back();
                }           
            }
            // for update later
            $session->set("cart=>{$index}=>stock",$stock);
        }
        $cartController = new CartController();
        $order_total = $cartController->countSubtotal(); 
        $email = isLoggedIn() ? getUser()->email : $request->input("email");
        $name = $request->input("name");
        $address = array("ward"=>$request->input("ward"),"district"=>$request->input("district"),"city"=> $request->input("province"),"address"=>$request->input("address"));
        $phone = $request->input("phone");
        $payment_method = $request->input("payment_method") === "cod" ? "Thanh toán khi nhận hàng" : "Thanh toán online qua VNPAY";
        $data = [
            "user_id" => isLoggedIn() ? getUser()->id : null,
            "total" => $order_total,
            "payment_method" => $request->input("payment_method"),
            "status" => "pending",
            "shipping_fee" => $request->input("shipping_fee"),
            "name" => $name,
            "email" => $email,
            "phone" => $request->input("phone"),
            "address" => json_encode(array("ward"=>$request->input("ward"),"district"=>$request->input("district"),"city"=> $request->input("province"),"address"=>$request->input("address"))),
            "note" => $request->input("note")
        ];
        if($request->has("coupon")){
            if(!isLoggedIn()){
                message(["message" => "Đăng nhập để có thể áp dụng mã giảm giá"]);
                redirect_back();
            }
            $coupon_code = $request->input("coupon");
            $coupon = DB::table("coupons")->where("code",$coupon_code)->first();
            if($coupon){
                $user_usage = DB::table("coupon_usage")->where("user_id",getUser()->id)->get();
                if(count($user_usage) >= $coupon->per_user){
                    message(["message" => "Người mua chỉ có thể dùng mã \"{$coupon->code}\" {$coupon->per_user} lần"]);
                    redirect_back();
                }
                $subtotal = $cartController->countSubtotal();
                $current_time = time();
                if($subtotal < $coupon->minimum_spend)
                {
                    message(["message" => "Giá trị đơn hàng không hợp lệ!"]);
                    redirect_back();
                }
                if(strtotime($coupon->start_time) > $current_time || $current_time > strtotime($coupon->end_time))
                {
                    message(["message" => "Mã đã hết hạn sử dụng!"]);
                    redirect_back();
                }
                if($coupon->coupon_usage <= $coupon->coupon_used)
                {
                    message(["message" => "Mã đã hết lượt sử dụng!"]);
                    redirect_back();
                }
                $data['coupon'] = $coupon->amount;
            }else{
                message(["message"=>"Mã không tồn tại!"]);
                redirect_back();
            }
        }
        if($request->has("v_point")){
            $point = $request->input("v_point");
            if(!isLoggedIn()){
                message(["message"=>"vui lòng đăng nhập trước"]);
                redirect_back();
            }
            if(!is_numeric($point) || intval($point) <= 0){
                message(["message"=>"point không hợp lệ!"]);
                redirect_back();
            }
            $point = intval($point);
            $point_from_db = DB::table("user_meta")->select(['meta_value'])->where("user_id",getUser()->id)->where("meta_key","point")->first();
            if($point_from_db){
                $point_from_db = intval($point_from_db->meta_value);
                if($point > $point_from_db){
                    message(["message"=>"Số dư không khả dụng!"]);
                    redirect_back();
                }
                $V_point = $point;
            }
        }
        $order_id =  date('d') . date('m') . explode(".",number_format(microtime(true), 6))[1];
        $data['id'] = $order_id;
        try{
            DB::table("orders")->insert($data);
            if(array_key_exists("coupon",$data)){
                DB::table("order_meta")->insert([
                    "order_id" => $order_id,
                    "meta_key" => "coupon",
                    "meta_value" => serialize(array("coupon_code"=>$coupon->code,"coupon_amount"=>$coupon->amount))
                ]);
                DB::table("coupons")->where("code",$coupon->code)->limit(1)->update([
                    "coupon_used" => $coupon->used + 1
                ]);
                DB::table("coupon_usage")->insert([
                    "user_id" => getUser()->id,
                    "coupon_id" => $coupon->id,
                    "order_id" => $order_id,
                ]);
            }
            if(isset($V_point)){
                DB::table("order_meta")->insert([
                    "order_id" => $order_id,
                    "meta_key" => "v_point",
                    "meta_value" => $V_point,
                ]);
                DB::table("point_history")->insert([
                    "user_id" => getUser()->id,
                    "order_id" => $order_id,
                    "order_total" => $order_total,
                    "point" => $V_point,
                    "action" => 0
                ]);
                DB::table("user_meta")->where("user_id",getUser()->id)->where("meta_key","point")->limit(1)->update([
                    "meta_value" => $point_from_db - $V_point
                ]);
            }
            foreach($session->get("cart") as $item){
                DB::table("order_items")->insert([
                    "order_id" => $order_id,
                    "p_id" => $item['p_id'],
                    "quantity" => $item['quantity'],
                    "p_price" => $item['price'],
                    "p_size" => $item['size'],
                    "p_color_id" => $item['color_id']
                ]);
                if(isset($item['color_id'])){
                    if(isset($item['size'])){
                        DB::table("product_colors_sizes")->where("color_id",$item['color_id'])->where("size",$item['size'])->limit(1)->update([
                            "stock" => $item['stock'] - $item['quantity']
                        ]);
                    }else{
                        DB::table("product_colors")->where("id",$item['color_id'])->limit(1)->update([
                            "stock" => $item['stock'] - $item['quantity']
                        ]);
                    }
                  
                }elseif(isset($item['size'])){
                    DB::table("product_sizes")->where("size",$item['size'])->limit(1)->update([
                        "stock" => $item['stock'] - $item['quantity']
                    ]);
                }else{
                    DB::table("products")->where("id",$item['p_id'])->limit(1)->update([
                        "p_stock" => $item['stock'] - $item['quantity']
                    ]);
                }
            }
            $session->set("cart",array());
            $mail = new Mail;
            $mail->to($email)->subject("VNH - Xác nhận đơn hàng")->template(VIEW_PATH . "/client/template/confirm_order.php",array("name" => $name,"phone" => $phone,"payment_method"=>$payment_method,"order_id" => $order_id,"address" => $address))->sendQueue();
            if($request->input("payment_method") === "vnpay"){
                $vnpay_amount = $order_total + intval($request->input("shipping_fee")) - (isset($data['coupon']) ? $coupon->amount : 0) - (isset($V_point) ? $point * 10000 : 0);
                $this->vnpay($vnpay_amount,$order_id);
            }
            flash_session("success",true);
            redirect("success",['order_id' => $order_id]);
        }catch(\Exception $e){
            message(["message"=>"Có lỗi xảy ra vui lòng thử lại!"]);
            redirect_back();
        }
    }

    public function vnpay($amount,$order_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $vnp_TmnCode = "C3UM9OVV"; 
        $vnp_HashSecret = "HOBQFVGFSTRKIMJOTRJRIBQIZOUVTBWI"; 
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = url("vnpay/confirm");
        //Config input format
        //Expire
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
        $vnp_TxnRef = $order_id; //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $amount; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = "NCB"; //Mã phương thức thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

        $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount * 100,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => "Thanh toán đơn hàng: " . $vnp_TxnRef,
        "vnp_OrderType" => "other",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        header('Location: ' . $vnp_Url);
        die();
    }
}