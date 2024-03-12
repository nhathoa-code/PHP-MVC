<?php include_once ROOT_PATH . "/views/admin/header.php" ?>
<div class="heading">Sửa mã giảm giá</div>
<form id="update-coupon-form" action="<?php echo url("admin/coupon/update/{$coupon->code}") ?>">
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Code</label>
        </div>
        <div class="col-5">
            <input type="text" name="code" value="<?php echo $coupon->code ?>" class="form-control">
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Mức giảm</label>
        </div>
        <div class="col-5">
            <input type="number" name="amount" value="<?php echo $coupon->amount ?>" class="form-control">
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Đơn hàng tối thiểu</label>
        </div>
        <div class="col-5">
            <input type="number" name="minimum_spend" value="<?php echo $coupon->minimum_spend ?>" class="form-control">
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Lượt dùng</label>
        </div>
        <div class="col-5">
            <input type="number" name="usage" value="<?php echo $coupon->coupon_usage ?>" class="form-control">
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Đã dùng</label>
        </div>
        <div class="col-5">
            <input type="number" name="used" value="<?php echo $coupon->coupon_used ?>" class="form-control">
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Thời gian bắt đầu</label>
        </div>
        <div class="col-5">
            <input type="text" name="start_time" value="<?php echo DateTime::createFromFormat('Y-m-d H:i:s', $coupon->start_time)->format("d-m-Y H:i"); ?>" class="form-control coupon-time">
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Thời gian kết thúc</label>
        </div>
        <div class="col-5">
            <input type="text" name="end_time" value="<?php echo DateTime::createFromFormat('Y-m-d H:i:s', $coupon->end_time)->format("d-m-Y H:i"); ?>" class="form-control coupon-time">
        </div>
    </div> 
    <div class="row">
        <button class="btn btn-primary mr-2">Update</button>
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
</form>
<script>
    const update_coupon_url = "<?php echo url("admin/coupon/update/{$coupon->id}") ?>";
</script>
<?php include_once ROOT_PATH . "/views/admin/footer.php" ?>